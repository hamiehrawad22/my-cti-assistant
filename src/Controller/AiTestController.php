<?php

namespace App\Controller;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AiTestController
{
    public function __construct(
        private readonly AgentInterface $agent
    ) {
    }

    #[Route('/test-ai', name: 'test_ai')]
    public function test(): Response
    {
        $messages = new MessageBag(
            Message::forSystem('You are a helpful assistant.'),
            Message::ofUser('Hello, how are you?')
        );

        $response = $this->agent->call($messages);

        return new Response($response->getContent());
    }
}