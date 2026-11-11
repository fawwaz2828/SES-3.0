<?php

declare(strict_types=1);

/**
 * Small global functions used by pages and templates.
 * Application logic lives in src/ (namespace Ses).
 */

use Ses\App;
use Ses\Database;
use Ses\Repository\UserRepository;
use Ses\Session\Auth;
use Ses\Session\Csrf;
use Ses\Session\Flash;
use Ses\Support\Input;

// ---------------------------------------------------------------- output

/** Escape a value for safe HTML output. */
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** URL relative to the project root, e.g. url('blog.php'). */
function url(string $path = ''): string
{
    return App::baseUrl() . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

/** Shorten text to $length characters, adding an ellipsis. */
function excerpt(?string $text, int $length = 120): string
{
    $text = trim(strip_tags($text ?? ''));

    return mb_strlen($text) > $length ? rtrim(mb_substr($text, 0, $length)) . '…' : $text;
}

function format_date(?DateTimeInterface $date, string $format = 'd M Y'): string
{
    return $date?->format($format) ?? '-';
}

function format_price(float $amount): string
{
    return '$' . number_format($amount, 2);
}

function format_rupiah(?int $amount): string
{
    return $amount === null ? '-' : 'Rp ' . number_format($amount, 0, ',', '.');
}

/** Turn an image stored as a BLOB into a data URI for <img src>. */
function blob_src(?string $blob): ?string
{
    if ($blob === null || $blob === '') {
        return null;
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->buffer($blob);

    return 'data:' . ($mime !== false ? $mime : 'image/jpeg') . ';base64,' . base64_encode($blob);
}

/** URL of a user's avatar, falling back to the default image. */
function avatar_url(?string $path): string
{
    return url($path !== null && is_file(App::rootDir() . '/' . $path) ? $path : UserRepository::DEFAULT_AVATAR);
}

function csrf_field(): string
{
    return Csrf::field();
}

/** Previously submitted value of a POST field (to refill a form). */
function old(string $key): string
{
    return Input::string($_POST, $key);
}

/** Trimmed query-string value. */
function query(string $key): string
{
    return Input::string($_GET, $key);
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function db(): Database
{
    return App::db();
}

// -------------------------------------------------------- access control

/** Returns the logged-in user's id, or redirects to the login page. */
function require_login(): int
{
    $userId = Auth::userId();
    if ($userId === null) {
        Flash::error('Please log in to continue.');
        redirect('login.php');
    }

    return $userId;
}

function require_guest(): void
{
    if (Auth::check()) {
        redirect('dashboard.php');
    }
}

/** Returns the logged-in admin's name, or redirects to the admin login. */
function require_admin(): string
{
    return Auth::adminName() ?? redirect('admin/login.php');
}
