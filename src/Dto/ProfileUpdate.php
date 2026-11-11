<?php

declare(strict_types=1);

namespace Ses\Dto;

use Ses\Support\Input;

/** Data from the edit-profile form. Email cannot be changed. */
final readonly class ProfileUpdate
{
    public function __construct(
        public string $name,
        public string $lastName,
        public string $phoneNumber,
        public string $businessName,
    ) {}

    /**
     * @param array<mixed> $input
     */
    public static function fromArray(array $input): self
    {
        return new self(
            name: Input::string($input, 'name', 50),
            lastName: Input::string($input, 'last_name', 50),
            phoneNumber: Input::string($input, 'phone_number', 20),
            businessName: Input::string($input, 'business_name', 30),
        );
    }

    /**
     * @return list<string>
     */
    public function errors(): array
    {
        return $this->name === '' || $this->lastName === '' ? ['First and last name are required.'] : [];
    }
}
