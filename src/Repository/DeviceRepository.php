<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Model\Device;

final readonly class DeviceRepository
{
    public function __construct(private Database $db) {}

    /**
     * @return list<Device>
     */
    public function forUser(int $userId): array
    {
        $rows = $this->db->all('SELECT * FROM devices WHERE id_user = ? ORDER BY created_at DESC', [$userId]);

        return array_map(Device::fromRow(...), $rows);
    }

    public function countForUser(int $userId): int
    {
        return $this->db->int('SELECT COUNT(*) FROM devices WHERE id_user = ?', [$userId]);
    }

    public function add(int $userId, string $name): void
    {
        $this->db->execute('INSERT INTO devices (id_user, device_name) VALUES (?, ?)', [$userId, $name]);
    }

    /** Only deletes the device when it belongs to the given user. */
    public function delete(int $id, int $userId): void
    {
        $this->db->execute('DELETE FROM devices WHERE id = ? AND id_user = ?', [$id, $userId]);
    }
}
