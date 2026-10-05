<?php

namespace App\Service;

use App\Dto\FindingAssessment;
use Psr\Log\LoggerInterface;
use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AiAssistant
{
    public function __construct(
        private readonly AgentInterface $agent,
        private readonly LoggerInterface $logger,
        private readonly ValidatorInterface $validator
    ) {
    }

    public function ask(string $question): string
    {
        $start = microtime(true);

        $messages = new MessageBag(
            Message::forSystem('You are a helpful assistant.'),
            Message::ofUser($question)
        );

        $response = $this->agent->call($messages);

        $tokenUsage = $response->getMetadata()->get('token_usage');

        $this->logger->info('AI call', [
            'latency_ms' => round((microtime(true) - $start) * 1000),
            'tokens' => $tokenUsage?->getTotalTokens() ?? 'unavailable',
        ]);

        return $response->getContent();
    }

    public function assessFinding(string $findingDescription): FindingAssessment
    {
        $start = microtime(true);

        $messages = new MessageBag(
            Message::forSystem('You are a CTI analyst assistant. Analyze the security finding and return a structured assessment.'),
            Message::ofUser($findingDescription)
        );

        $response = $this->agent->call($messages, [
            'response_format' => FindingAssessment::class,
        ]);

        $tokenUsage = $response->getMetadata()->get('token_usage');

        $this->logger->info('AI structured call', [
            'latency_ms' => round((microtime(true) - $start) * 1000),
            'tokens' => $tokenUsage?->getTotalTokens() ?? 'unavailable',
        ]);

        $assessment = $response->asObject();

        $violations = $this->validator->validate($assessment);

        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                $this->logger->warning('Invalid AI output', [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                ]);
            }

            throw new \RuntimeException('Invalid AI output: ' . $violations->get(0)->getMessage());
        }

        return $assessment;
    }
}