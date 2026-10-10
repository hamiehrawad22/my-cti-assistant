<?php

namespace App\Rag;

final class ContextBuilder
{
    private const MIN_SCORE = 0.65;

    public function __construct(
        private readonly Retriever $retriever,
    ) {
    }

    public function build(string $question, ?string $tenant = null, int $topK = 3): array
    {
        $hits = $this->retriever->search($question, $topK, $tenant);

        $contextParts = [];
        $sources      = [];

        foreach ($hits as $hit) {
            if ($hit['score'] < self::MIN_SCORE) {
                continue;
            }

            $sources[] = [
                'source' => $hit['source'],
                'score'  => $hit['score'],
            ];

            $contextParts[] = sprintf(
                "[%d] %s\n%s",
                count($sources),
                $hit['source'],
                $hit['text'],
            );
        }

        return [
            'context' => implode("\n\n---\n\n", $contextParts),
            'sources' => $sources,
        ];
    }
}
