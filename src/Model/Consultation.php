<?php

declare(strict_types=1);

namespace Ses\Model;

use DateTimeImmutable;
use Ses\Enum\ConsultationStatus;
use Ses\Support\Row;

/** A consultation request (table `consultation_form`). */
final readonly class Consultation
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $companyName,
        public string $companyField,
        public int $companySize,
        public string $companyAddress,
        public ?string $currentLighting,
        public ?string $problemDetail,
        public ?string $goals,
        public ?int $minBudget,
        public ?int $maxBudget,
        public ConsultationStatus $status,
        public ?DateTimeImmutable $preferredDate,
        public ?DateTimeImmutable $createdAt,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            id: Row::int($row, 'id'),
            userId: Row::int($row, 'id_user'),
            companyName: Row::string($row, 'company_name'),
            companyField: Row::string($row, 'company_field'),
            companySize: Row::int($row, 'company_size'),
            companyAddress: Row::string($row, 'company_address'),
            currentLighting: Row::nullableString($row, 'current_lighting'),
            problemDetail: Row::nullableString($row, 'problem_detail'),
            goals: Row::nullableString($row, 'goals'),
            minBudget: Row::nullableInt($row, 'min_budget'),
            maxBudget: Row::nullableInt($row, 'max_budget'),
            status: ConsultationStatus::tryFrom(Row::nullableString($row, 'status') ?? '') ?? ConsultationStatus::Running,
            preferredDate: Row::nullableDate($row, 'preferredDate'),
            createdAt: Row::nullableDate($row, 'created_at'),
        );
    }
}
