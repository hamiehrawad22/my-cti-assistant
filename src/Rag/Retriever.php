<?php

namespace App\Rag;

use Symfony\AI\Platform\PlatformInterface;

final class Retriever
{
    public function __construct(
        private readonly SimpleMemoryStore $store,
        private readonly PlatformInterface $platform,
    ) {
    }

    public function search(string $query, int $topK = 3, ?string $tenant = null): array
    {
        $result = $this->platform->invoke('gemini-embedding-001', $query);
        $vector = $result->asVectors()[0] ?? null;

        if ($vector === null) {
            return [];
        }

        $filters = [];
        if ($tenant !== null) {
            $filters['tenant'] = ['in' => [$tenant, 'global']];
        }

        $hits = $this->store->query($vector, $topK, $filters);

        return array_map(static fn ($hit) => [
            'id'     => $hit['id'] ?? '',
            'text'   => $hit['text'] ?? '',
            'source' => $hit['source'] ?? '',
            'score'  => $hit['score'] ?? 0.0,
        ], $hits);
    }
}
