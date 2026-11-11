<?php

declare(strict_types=1);

namespace Ses\Session;

use Ses\Model\Admin;
use Ses\Model\User;

/**
 * Who is logged in. Users and admins are tracked separately.
 */
final class Auth
{
    public static function userId(): ?int
    {
        $id = $_SESSION['user_id'] ?? null;

        return \is_int($id) ? $id : null;
    }

    public static function check(): bool
    {
        return self::userId() !== null;
    }

    public static function userName(): string
    {
        $name = $_SESSION['user_name'] ?? '';

        return \is_string($name) ? $name : '';
    }

    public static function login(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->name;
    }

    public static function rename(string $name): void
    {
        $_SESSION['user_name'] = $name;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id'], $_SESSION['user_name']);
        session_regenerate_id(true);
    }

    public static function adminName(): ?string
    {
        $name = $_SESSION['admin'] ?? null;

        return \is_string($name) ? $name : null;
    }

    public static function loginAdmin(Admin $admin): void
    {
        session_regenerate_id(true);
        $_SESSION['admin'] = $admin->name;
    }

    public static function logoutAdmin(): void
    {
        unset($_SESSION['admin']);
        session_regenerate_id(true);
    }
}
