<?php

namespace App\Controller;

use App\Rag\SyntheticDocumentLoader;
use App\Service\AiAssistant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AiTestController extends AbstractController
{
    public function __construct(
        private readonly AiAssistant $aiAssistant,
    ) {
    }

    #[Route('/test-assess', name: 'test_assess')]
    public function assess(Request $request): Response
    {
        $finding = $request->query->get('finding', 'SQL injection in login form');
        $tenant  = $request->query->get('tenant', 'tenant-a');

        return $this->json($this->aiAssistant->assessFinding($finding, $tenant));
    }

    #[Route('/rag-test', name: 'rag_test')]
    public function ragTest(SyntheticDocumentLoader $loader): Response
    {
        $docs = $loader->load();

        return $this->json([
            'total_chunks' => count($docs),
            'by_source'    => array_count_values(array_column($docs, 'source')),
            'by_tenant'    => array_count_values(array_column($docs, 'tenant')),
            'by_type'      => array_count_values(array_column($docs, 'type')),
            'chunks'       => array_map(static fn ($d) => [
                'id'      => $d['id'],
                'source'  => $d['source'],
                'tenant'  => $d['tenant'],
                'type'    => $d['type'],
                'preview' => substr($d['text'], 0, 100) . '...',
            ], $docs),
        ]);
    }
}