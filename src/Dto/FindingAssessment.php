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

    /** @var string[] */
    #[Assert\Count(min: 1)]
    public array $remediationSteps = [];

    #[Assert\Count(min: 1)]
    public array $references = [];

    #[Assert\Range(min: 0, max: 1)]
    public float $confidence = 0.0;

    /** @var string[] */
    public array $missingInformation = [];
}