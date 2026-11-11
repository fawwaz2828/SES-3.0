<?php

declare(strict_types=1);

namespace Ses\Session;

use Ses\Enum\FlashType;

/**
 * One-time messages shown on the next page load.
 */
final class Flash
{
    private const KEY = 'flash';

    public static function success(string $message): void
    {
        self::add(FlashType::Success, $message);
    }

    public static function error(string $message): void
    {
        self::add(FlashType::Error, $message);
    }

    /**
     * Returns and clears all pending messages.
     *
     * @return list<FlashMessage>
     */
    public static function pull(): array
    {
        $messages = [];
        foreach (self::stored() as $item) {
            $type = FlashType::tryFrom($item['type']);
            if ($type !== null) {
                $messages[] = new FlashMessage($type, $item['message']);
            }
        }
        unset($_SESSION[self::KEY]);

        return $messages;
    }

    private static function add(FlashType $type, string $message): void
    {
        $stored = self::stored();
        $stored[] = ['type' => $type->value, 'message' => $message];
        $_SESSION[self::KEY] = $stored;
    }

    /**
     * @return list<array{type: string, message: string}>
     */
    private static function stored(): array
    {
        $raw = $_SESSION[self::KEY] ?? [];
        if (!\is_array($raw)) {
            return [];
        }

        $items = [];
        foreach ($raw as $item) {
            if (\is_array($item) && \is_string($item['type'] ?? null) && \is_string($item['message'] ?? null)) {
                $items[] = ['type' => $item['type'], 'message' => $item['message']];
            }
        }

        return $items;
    }
}
