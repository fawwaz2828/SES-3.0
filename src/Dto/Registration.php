<?php

declare(strict_types=1);

namespace Ses\Dto;

use Ses\Support\Input;

/** Data from the registration form. */
final readonly class Registration
{
    private const MIN_PASSWORD_LENGTH = 6;

    public function __construct(
        public string $name,
        public string $lastName,
        public string $businessName,
        public string $email,
        public string $phoneNumber,
        #[\SensitiveParameter]
        public string $password,
        #[\SensitiveParameter]
        public string $passwordConfirmation,
    ) {}

    /**
     * @param array<mixed> $input
     */
    public static function fromArray(array $input): self
    {
        $password = $input['password'] ?? '';
        $confirmation = $input['confirm_password'] ?? '';

        return new self(
            name: Input::string($input, 'name', 50),
            lastName: Input::string($input, 'last_name', 50),
            businessName: Input::string($input, 'business_name', 30),
            email: Input::string($input, 'email', 50),
            phoneNumber: Input::string($input, 'phone_number', 20),
            password: \is_string($password) ? $password : '',
            passwordConfirmation: \is_string($confirmation) ? $confirmation : '',
        );
    }

    /**
     * Validation that does not need the database.
     *
     * @return list<string>
     */
    public function errors(): array
    {
        $errors = [];
        if ($this->name === '' || $this->lastName === '' || $this->businessName === '' || $this->phoneNumber === '') {
            $errors[] = 'Please fill in all fields.';
        }
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $errors[] = 'Please enter a valid email address.';
        }
        if (\strlen($this->password) < self::MIN_PASSWORD_LENGTH) {
            $errors[] = \sprintf('Password must be at least %d characters.', self::MIN_PASSWORD_LENGTH);
        } elseif ($this->password !== $this->passwordConfirmation) {
            $errors[] = 'Passwords do not match.';
        }

        return $errors;
    }
}
