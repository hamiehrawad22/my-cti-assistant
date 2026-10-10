<?php

namespace App\Rag;

use Symfony\AI\Platform\Vector\Vector;

final class SimpleMemoryStore
{
    private array $items = [];

    public function add(array|Vector $vector, array $metadata = []): void
    {
        $this->items[] = [
            'vector'   => $this->toFloats($vector),
            'metadata' => $metadata,
        ];
    }

    public function query(array|Vector $vector, int $topK = 3, array $filters = []): array
    {
        $needle = $this->toFloats($vector);
        $scored = [];

        foreach ($this->items as $item) {
            if (!$this->matchesFilters($item['metadata'], $filters)) {
                continue;
            }

            $scored[] = [
                'score'    => $this->cosine($needle, $item['vector']),
                'metadata' => $item['metadata'],
            ];
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        $top = array_slice($scored, 0, $topK);

        return array_map(
            fn ($row) => $row['metadata'] + ['score' => $row['score']],
            $top,
        );
    }

    private function toFloats(array|Vector $vector): array
    {
        if (is_array($vector)) {
            return array_map('floatval', $vector);
        }

        return array_map('floatval', $vector->getData());
    }

    private function cosine(array $a, array $b): float
    {
        $dot    = 0.0;
        $normA  = 0.0;
        $normB  = 0.0;
        $length = min(count($a), count($b));

        for ($i = 0; $i < $length; $i++) {
            $dot   += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA === 0.0 || $normB === 0.0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    private function matchesFilters(array $metadata, array $filters): bool
    {
        foreach ($filters as $key => $expected) {
            $value = $metadata[$key] ?? null;

            if (is_array($expected) && isset($expected['in'])) {
                if (!in_array($value, $expected['in'], true)) {
                    return false;
                }
                continue;
            }

            if ($value !== $expected) {
                return false;
            }
        }

        return true;
    }
}
