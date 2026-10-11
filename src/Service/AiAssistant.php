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
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function assessFinding(string $finding, ?string $tenant = null): FindingAssessment
    {
        $result = $this->attempt($finding)
            ?? $this->attempt($finding, 'IMPORTANT: remediationSteps must contain at least 2 items.')
            ?? throw new \RuntimeException('Invalid AI output after retry.');

        return $result;
    }

    private function attempt(string $finding, string $extra = ''): ?FindingAssessment
    {
        $system = "You are a CTI analyst assistant. Return STRICT JSON with keys: "
                . "summary, severity (low|medium|high|critical), likelyImpact, "
                . "remediationSteps[], references[], confidence (0..1), missingInformation[]. "
                . $extra;

        $response = $this->agent->call(new MessageBag(
            Message::forSystem($system),
            Message::ofUser($finding),
        ), [
            'response_format' => FindingAssessment::class,
        ]);

        $dto = $response->asObject();

        return count($this->validator->validate($dto)) === 0 ? $dto : null;
    }
}