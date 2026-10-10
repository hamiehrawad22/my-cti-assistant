<?php

namespace App\Service;

use App\Dto\FindingAssessment;
use App\Rag\ContextBuilder;
use App\Rag\VectorIndexer;
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
        private readonly ValidatorInterface $validator,
        private readonly ContextBuilder $contextBuilder,
        private readonly VectorIndexer $indexer,
    ) {
    }

    public function assessFinding(string $finding, ?string $tenant = null): FindingAssessment
    {
        $start = microtime(true);

        $this->indexer->reindex();

        $grounded = $this->contextBuilder->build($finding, $tenant, 3);

        if (empty($grounded['sources'])) {
            $this->logger->info('No relevant context found', ['finding' => $finding, 'tenant' => $tenant]);

            return $this->refusal();
        }

        $result = $this->attempt($grounded['context'], $finding, $grounded['sources'])
            ?? $this->attempt(
                $grounded['context'],
                $finding,
                $grounded['sources'],
                'IMPORTANT: remediationSteps must contain at least 2 items. references will be filled by the system, you may leave it empty.',
            )
            ?? throw new \RuntimeException('Invalid AI output after retry.');

        $this->logger->info('Assessment complete', ['latency_ms' => round((microtime(true) - $start) * 1000)]);

        return $result;
    }

    private function attempt(string $context, string $question, array $sources, string $extra = ''): ?FindingAssessment
    {
        $assessment = $this->finalize($this->callModel($context, $question, $extra), $sources);

        return count($this->validator->validate($assessment)) === 0 ? $assessment : null;
    }

    private function refusal(): FindingAssessment
    {
        $refusal = new FindingAssessment();
        $refusal->summary = 'Insufficient evidence in the knowledge base to assess this finding.';
        $refusal->severity = 'low';
        $refusal->likelyImpact = 'Unknown — no relevant sources found.';
        $refusal->remediationSteps = ['Provide a security finding description or ID that matches the knowledge base.'];
        $refusal->confidence = 0.10;
        $refusal->missingInformation = [
            'A relevant finding ID or description',
            'Any public or sanitized advisory related to this issue',
        ];

        return $refusal;
    }

    private function finalize(FindingAssessment $assessment, array $sources): FindingAssessment
    {
        $assessment->references = array_values(array_unique(array_column($sources, 'source')));
        $assessment->sources = $sources;
        $assessment->confidence = min(0.95, max(0.10, $assessment->confidence));

        return $assessment;
    }

    private function callModel(string $context, string $question, string $extra = ''): FindingAssessment
    {
        $system = <<<PROMPT
You are a CTI analyst assistant. Analyze the security finding using ONLY the
provided context. Return a structured assessment.

Rules:
- Every claim must be supported by the context below.
- If the context does not contain enough evidence, say so and lower confidence.
- Never invent facts, sources, or severities not present in the context.
- remediationSteps must contain at least 2 items.
- references will be filled by the system; you may leave it empty.
{$extra}

Context:
{$context}
PROMPT;

        $start = microtime(true);

        $response = $this->agent->call(new MessageBag(
            Message::forSystem($system),
            Message::ofUser($question),
        ), [
            'response_format' => FindingAssessment::class,
        ]);

        $this->logCall('AI structured call', $start, $response);

        return $response->asObject();
    }

    private function logCall(string $label, float $start, $response): void
    {
        $tokens = $response->getMetadata()->get('token_usage');
        $this->logger->info($label, [
            'latency_ms' => round((microtime(true) - $start) * 1000),
            'tokens'     => $tokens?->getTotalTokens() ?? 'unavailable',
        ]);
    }
}
