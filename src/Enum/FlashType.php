<?php

declare(strict_types=1);

namespace Ses\Enum;

enum FlashType: string
{
    case Success = 'success';
    case Error = 'error';

    /** Tailwind classes that tint the (glass) notification box. */
    public function classes(): string
    {
        return match ($this) {
            self::Success => 'text-emerald-800',
            self::Error => 'text-red-700',
        };
    }
}
