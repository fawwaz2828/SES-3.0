<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Dto\ProfileUpdate;
use Ses\Dto\Registration;
use Ses\Model\User;

final readonly class UserRepository
{
    public const DEFAULT_AVATAR = 'assets/images/default-avatar.jpg';

    public function __construct(private Database $db) {}

    public function find(int $id): ?User
    {
        $row = $this->db->one('SELECT * FROM user WHERE id_user = ?', [$id]);

        return $row === null ? null : User::fromRow($row);
    }

    public function findByEmail(string $email): ?User
    {
        $row = $this->db->one('SELECT * FROM user WHERE email = ?', [$email]);

        return $row === null ? null : User::fromRow($row);
    }

    public function emailExists(string $email): bool
    {
        return $this->db->int('SELECT COUNT(*) FROM user WHERE email = ?', [$email]) > 0;
    }

    public function count(): int
    {
        return $this->db->int('SELECT COUNT(*) FROM user');
    }

    public function create(Registration $registration): void
    {
        $this->db->execute(
            'INSERT INTO user (name, last_name, business_name, email, phone_number, password, avatar)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $registration->name,
                $registration->lastName,
                $registration->businessName,
                $registration->email,
                $registration->phoneNumber,
                password_hash($registration->password, PASSWORD_DEFAULT),
                self::DEFAULT_AVATAR,
            ],
        );
    }

    public function updateProfile(int $id, ProfileUpdate $update, ?string $avatar): void
    {
        $this->db->execute(
            'UPDATE user SET name = ?, last_name = ?, phone_number = ?, business_name = ?, avatar = ? WHERE id_user = ?',
            [$update->name, $update->lastName, $update->phoneNumber, $update->businessName, $avatar ?? self::DEFAULT_AVATAR, $id],
        );
    }
}
