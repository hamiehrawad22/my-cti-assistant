<?php

namespace App\Rag;

use Psr\Log\LoggerInterface;
use Symfony\AI\Platform\PlatformInterface;

final class VectorIndexer
{
    public function __construct(
        private readonly SimpleMemoryStore $store,
        private readonly PlatformInterface $platform,
        private readonly SyntheticDocumentLoader $loader,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function reindex(): int
    {
        $docs = $this->loader->load();
        $count = 0;

        foreach ($docs as $doc) {
            $result = $this->platform->invoke('gemini-embedding-001', $doc['text']);
            $vector = $result->asVectors()[0] ?? null;

            if ($vector === null) {
                continue;
            }

            $this->store->add($vector, [
                'id'     => $doc['id'],
                'text'   => $doc['text'],
                'source' => $doc['source'],
                'type'   => $doc['type'],
                'tenant' => $doc['tenant'],
            ]);

            $count++;
        }

        $this->logger->info('Vector index rebuilt', ['chunks' => $count]);

        return $count;
    }
}
