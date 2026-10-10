<?php

namespace App\Controller;

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
}
