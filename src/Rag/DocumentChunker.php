<?php

namespace App\Rag;

final class DocumentChunker
{
    public function __construct(
        private readonly int $maxChars = 800,
        private readonly int $overlap = 100,
    ) {
    }

    public function chunk(string $text): array
    {
        $text = $this->normalize($text);

        if ($text === '') {
            return [];
        }

        $chunks = [];
        $length = mb_strlen($text);
        $step   = $this->maxChars - $this->overlap;

        for ($start = 0; $start < $length; $start += $step) {
            $chunk = trim(mb_substr($text, $start, $this->maxChars));

            if ($chunk !== '') {
                $chunks[] = $chunk;
            }
        }

        return $chunks;
    }

    private function normalize(string $text): string
    {
        $text = str_replace(["\r\n", "\r", "\n", "\t"], ' ', $text);

        $clean = '';

        foreach (explode(' ', $text) as $word) {
            if ($word !== '') {
                $clean .= $word . ' ';
            }
        }

        return trim($clean);
    }
}
