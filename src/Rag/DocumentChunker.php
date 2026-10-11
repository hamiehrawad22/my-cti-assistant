<?php

namespace App\Rag;

final class DocumentChunker
{
    public function __construct(
        private readonly int $maxChars = 800,
        private readonly int $overlap = 100,
    ) {
        if ($maxChars <= 0) {
            throw new \InvalidArgumentException('maxChars must be greater than 0.');
        }

        if ($overlap < 0 || $overlap >= $maxChars) {
            throw new \InvalidArgumentException('overlap must be between 0 and maxChars - 1.');
        }
    }

    /**
     * @return string[]
     */
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
        return trim(preg_replace('/\s+/u', ' ', $text));
    }
}