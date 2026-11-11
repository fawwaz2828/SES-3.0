<?php

declare(strict_types=1);

namespace Ses\Dto;

use DateTimeImmutable;
use Ses\Support\Input;

/** Data from the "Book a Consultation" form. */
final readonly class ConsultationRequest
{
    public function __construct(
        public string $companyName,
        public string $companyField,
        public ?int $companySize,
        public string $companyAddress,
        public string $currentLighting,
        public string $problemDetail,
        public string $goals,
        public ?int $minBudget,
        public ?int $maxBudget,
        public ?DateTimeImmutable $preferredDate,
        public bool $acceptsPrivacyPolicy,
        public bool $wantsUpdates,
    ) {}

    /**
     * @param array<mixed> $input
     */
    public static function fromArray(array $input): self
    {
        return new self(
            companyName: Input::string($input, 'company_name', 255),
            companyField: Input::string($input, 'company_field', 255),
            companySize: Input::int($input, 'company_size'),
            companyAddress: Input::string($input, 'company_address'),
            currentLighting: Input::string($input, 'current_lighting'),
            problemDetail: Input::string($input, 'problem_detail'),
            goals: Input::string($input, 'goals'),
            minBudget: Input::int($input, 'min_budget'),
            maxBudget: Input::int($input, 'max_budget'),
            preferredDate: Input::date($input, 'preferred_date'),
            acceptsPrivacyPolicy: Input::bool($input, 'privacy_policy'),
            wantsUpdates: Input::bool($input, 'updates_promotions'),
        );
    }

    /**
     * @return list<string>
     */
    public function errors(DateTimeImmutable $today): array
    {
        $errors = [];
        if ($this->companyName === '' || $this->companyField === '' || $this->companyAddress === '') {
            $errors[] = 'Please fill in all company details.';
        }
        if ($this->companySize === null || $this->companySize < 1) {
            $errors[] = 'Company size must be at least 1.';
        }
        if ($this->preferredDate === null || $this->preferredDate < $today->setTime(0, 0)) {
            $errors[] = 'Please choose a preferred date from today onwards.';
        }
        if (($this->minBudget ?? 0) < 0 || ($this->maxBudget ?? 0) < 0) {
            $errors[] = 'Budget cannot be negative.';
        } elseif ($this->minBudget !== null && $this->maxBudget !== null && $this->minBudget > $this->maxBudget) {
            $errors[] = 'Minimum budget cannot be higher than maximum budget.';
        }
        if (!$this->acceptsPrivacyPolicy) {
            $errors[] = 'Please agree to the privacy policy.';
        }

        return $errors;
    }
}
