<?php

namespace App\Tests\Service;

use App\Dto\FindingAssessment;
use PHPUnit\Framework\TestCase;
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

    public function testFindingAssessmentValidation(): void
    {
        $assessment = new FindingAssessment();
        $assessment->summary = 'we not to stop it now';
        $assessment->severity = 'medium';
        $assessment->likelyImpact = 'cause high  problem';
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
        $assessment->severity = 'super-critical';
        $assessment->likelyImpact = 'Test';
        $assessment->remediationSteps = ['Test'];
        $assessment->references = ['Test'];
        $assessment->confidence = 0.5;

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
        $assessment->confidence = 1.4;

        $violations = $this->createValidator()->validate($assessment);

        $this->assertGreaterThan(0, $violations->count());
    }
}
