<?php

declare(strict_types=1);

namespace Ses\Enum;

/**
 * Image formats accepted for uploads, keyed by MIME type.
 */
enum ImageType: string
{
    case Jpeg = 'image/jpeg';
    case Png = 'image/png';
    case Gif = 'image/gif';
    case Webp = 'image/webp';

    public function extension(): string
    {
        return match ($this) {
            self::Jpeg => 'jpg',
            self::Png => 'png',
            self::Gif => 'gif',
            self::Webp => 'webp',
        };
    }
}
