<?php

declare(strict_types=1);

namespace Ses\Service;

use RuntimeException;
use Ses\Http\UploadedImage;

/**
 * Stores user avatars in uploads/avatars with random file names.
 */
final readonly class AvatarStorage
{
    private const DIRECTORY = 'uploads/avatars';

    public function __construct(private string $rootDir) {}

    /**
     * @return string path relative to the project root, as stored in user.avatar
     */
    public function store(int $userId, UploadedImage $image): string
    {
        $dir = $this->rootDir . '/' . self::DIRECTORY;
        if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
            throw new RuntimeException('Could not create the avatar folder.');
        }

        $path = \sprintf('%s/%d-%s.%s', self::DIRECTORY, $userId, bin2hex(random_bytes(6)), $image->type->extension());
        if (file_put_contents($this->rootDir . '/' . $path, $image->bytes) === false) {
            throw new RuntimeException('Could not save the avatar.');
        }

        return $path;
    }

    /** Deletes a previously uploaded avatar. Default images are left alone. */
    public function delete(?string $path): void
    {
        if ($path === null || !str_starts_with($path, self::DIRECTORY . '/')) {
            return;
        }
        $file = $this->rootDir . '/' . $path;
        if (is_file($file)) {
            unlink($file);
        }
    }
}
