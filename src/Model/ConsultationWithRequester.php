<?php

declare(strict_types=1);

namespace Ses\Model;

use Ses\Support\Row;

/** A consultation together with the name of the user who requested it (admin list). */
final readonly class ConsultationWithRequester
{
    public function __construct(
        public Consultation $consultation,
        public string $requesterName,
    ) {}

    /**
     * @param array<string, mixed> $row
     */
    public static function fromRow(array $row): self
    {
        return new self(
            Consultation::fromRow($row),
            trim(Row::string($row, 'name') . ' ' . Row::string($row, 'last_name')),
        );
    }
}
