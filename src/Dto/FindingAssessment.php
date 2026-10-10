<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class FindingAssessment
{
    #[Assert\NotBlank]
    public string $summary = '';

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['low', 'medium', 'high', 'critical'])]
    public string $severity = '';

    #[Assert\NotBlank]
    public string $likelyImpact = '';

    #[Assert\Count(min: 1)]
    public array $remediationSteps = [];

    public array $references = [];

    #[Assert\Range(min: 0, max: 1)]
    public float $confidence = 0.0;

    public array $missingInformation = [];

    public array $sources = [];
}
