<?php

declare(strict_types=1);

namespace Ses\Enum;

enum ConsultationStatus: string
{
    case Running = 'running';
    case Finished = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::Running => 'Running',
            self::Finished => 'Finished',
        };
    }

    /** Tailwind classes for the status badge. */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Running => 'bg-amber-100 text-amber-700',
            self::Finished => 'bg-emerald-100 text-emerald-700',
        };
    }
}
