<?php

namespace App\Tests\Service;

use App\Dto\FindingAssessment;
use App\Service\AiAssistant;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\AI\Agent\MockAgent;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AiAssistantTest extends TestCase
{
    private function createValidator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    private function createAssistant(MockAgent $mockAgent): AiAssistant
    {
        return new AiAssistant(
            $mockAgent,
            new NullLogger(),
            $this->createValidator()
        );
    }

    public function testAskReturnsText(): void
    {
        $mockAgent = new MockAgent([
            'Hello' => 'Hi there!',
        ]);

        $assistant = $this->createAssistant($mockAgent);

        $result = $assistant->ask('Hello');

        $this->assertSame('Hi there!', $result);
    }

    public function testFindingAssessmentValidation(): void
    {
        $assessment = new FindingAssessment();
        $assessment->summary = 'solved';
        $assessment->severity = 'medium';
        $assessment->likelyImpact = 'cause problem';
        $assessment->remediationSteps = ['solve it'];
        $assessment->confidence = 0.6;
        $assessment->references = ['realmadrid'];

        $violations = $this->createValidator()->validate($assessment);

        $this->assertCount(0, $violations);
    }

    public function testFindingAssessmentRejectsInvalidSeverity(): void
    {
        $assessment = new FindingAssessment();
        $assessment->summary = 'Test';
        $assessment->severity = 'super-critical'; // invalid enum
        $assessment->likelyImpact = 'Test';
        $assessment->remediationSteps = ['Test'];
        $assessment->references = ['Test'];
        $assessment->confidence = 0.5; // valid — only severity should fail

        $violations = $this->createValidator()->validate($assessment);

        $this->assertGreaterThan(0, $violations->count());
    }

    public function testFindingAssessmentRejectsOutOfRangeConfidence(): void
    {
        $assessment = new FindingAssessment();
        $assessment->summary = 'Test';
        $assessment->severity = 'medium';
        $assessment->likelyImpact = 'Test';
        $assessment->remediationSteps = ['Test'];
        $assessment->references = ['Test'];
        $assessment->confidence = 1.5; // invalid range

        $violations = $this->createValidator()->validate($assessment);

        $this->assertGreaterThan(0, $violations->count());
    }
}