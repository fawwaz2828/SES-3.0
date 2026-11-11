<?php

declare(strict_types=1);

namespace Ses\Session;

use Ses\Enum\FlashType;

final readonly class FlashMessage
{
    public function __construct(
        public FlashType $type,
        public string $message,
    ) {}
}
