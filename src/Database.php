<?php

declare(strict_types=1);

namespace Ses;

use mysqli;
use mysqli_stmt;
use RuntimeException;

/**
 * Thin wrapper around mysqli that only runs prepared statements.
 *
 * @phpstan-type Param scalar|null
 * @phpstan-type DbRow array<string, mixed>
 */
final class Database
{
    public function __construct(private readonly mysqli $connection) {}

    public static function connect(Config $config): self
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $connection = new mysqli($config->dbHost, $config->dbUser, $config->dbPassword, $config->dbName);
        $connection->set_charset('utf8mb4');

        return new self($connection);
    }

    /**
     * @param list<Param> $params
     * @return list<DbRow>
     */
    public function all(string $sql, array $params = []): array
    {
        $result = $this->run($sql, $params)->get_result();
        if ($result === false) {
            return [];
        }

        /** @var list<DbRow> $rows */
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        return $rows;
    }

    /**
     * @param list<Param> $params
     * @return DbRow|null
     */
    public function one(string $sql, array $params = []): ?array
    {
        return $this->all($sql, $params)[0] ?? null;
    }

    /**
     * Returns the first column of the first row as an integer (for COUNT/MAX queries).
     *
     * @param list<Param> $params
     */
    public function int(string $sql, array $params = []): int
    {
        $result = $this->run($sql, $params)->get_result();
        $row = $result === false ? null : $result->fetch_row();
        $value = $row[0] ?? 0;

        return is_numeric($value) ? (int) $value : 0;
    }

    /**
     * Runs an INSERT/UPDATE/DELETE and returns the number of affected rows.
     *
     * @param list<Param> $params
     */
    public function execute(string $sql, array $params = []): int
    {
        return (int) $this->run($sql, $params)->affected_rows;
    }

    /**
     * @param list<Param> $params
     */
    private function run(string $sql, array $params): mysqli_stmt
    {
        $statement = $this->connection->prepare($sql);
        if ($statement === false) {
            throw new RuntimeException('Could not prepare query: ' . $this->connection->error);
        }
        if ($params !== []) {
            // Bind with real types so that e.g. "LIMIT ?" receives an integer.
            $types = implode('', array_map(
                static fn(mixed $param): string => match (true) {
                    \is_int($param), \is_bool($param) => 'i',
                    \is_float($param) => 'd',
                    default => 's',
                },
                $params,
            ));
            $values = array_map(static fn(mixed $param): mixed => \is_bool($param) ? (int) $param : $param, $params);
            $statement->bind_param($types, ...$values);
        }
        $statement->execute();

        return $statement;
    }
}
