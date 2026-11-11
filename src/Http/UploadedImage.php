<?php

declare(strict_types=1);

namespace Ses\Http;

use finfo;
use Ses\Enum\ImageType;

/**
 * A validated image from $_FILES.
 */
final readonly class UploadedImage
{
    private const DEFAULT_MAX_BYTES = 5 * 1024 * 1024;

    public function __construct(
        public string $bytes,
        public ImageType $type,
    ) {}

    /**
     * Returns null when the field was left empty.
     *
     * @throws UploadException when the upload failed or is not an allowed image
     */
    public static function fromRequest(string $field, int $maxBytes = self::DEFAULT_MAX_BYTES): ?self
    {
        $file = $_FILES[$field] ?? null;
        if (!\is_array($file) || ($file['error'] ?? null) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $tmpName = $file['tmp_name'] ?? null;
        $size = $file['size'] ?? null;
        if (($file['error'] ?? null) !== UPLOAD_ERR_OK || !\is_string($tmpName) || !\is_int($size)) {
            throw new UploadException('Upload failed. Please try again.');
        }
        if ($size > $maxBytes) {
            throw new UploadException(\sprintf('Image is too large (max %d MB).', intdiv($maxBytes, 1024 * 1024)));
        }

        $type = ImageType::tryFrom((string) (new finfo(FILEINFO_MIME_TYPE))->file($tmpName));
        $bytes = file_get_contents($tmpName);
        if ($type === null || $bytes === false) {
            throw new UploadException('Only JPG, PNG, GIF and WEBP images are allowed.');
        }

        return new self($bytes, $type);
    }
}
