<?php

declare(strict_types=1);

namespace Ses\Repository;

use Ses\Database;
use Ses\Model\Admin;

final readonly class AdminRepository
{
    public function __construct(private Database $db) {}

    /** Finds an admin by username (`nama`) or email. */
    public function findByLogin(string $login): ?Admin
    {
        $row = $this->db->one('SELECT * FROM admin WHERE nama = ? OR email = ?', [$login, $login]);

        return $row === null ? null : Admin::fromRow($row);
    }
}
