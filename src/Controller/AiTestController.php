<?php

namespace App\Controller;

use App\Service\AiAssistant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AiTestController extends AbstractController
{
    public function __construct(
        private readonly AiAssistant $aiAssistant
    ) {
    }

    #[Route('/test-ai', name: 'test_ai')]
    public function test(): Response
    {
        return new Response($this->aiAssistant->ask('Hello, how are you?'));
    }

    #[Route('/test-assess', name: 'test_assess')]
    public function assess(): Response
    {
        $result = $this->aiAssistant->assessFinding('SQL injection in login form');

        return $this->json($result);
    }
}