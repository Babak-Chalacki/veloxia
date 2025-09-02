<?php

namespace Veloxia\Core;

use PDO;

abstract class Model {
    protected string $table;
    protected static string $primaryKey = 'id';

    public function __construct() {
        if (empty($this->table)) {
            $class = (new \ReflectionClass($this))->getShortName();
            $this->table = strtolower($class) . 's';
        }
    }

    public static function find(int $id): ?array {
        $db = Database::connect();
        $table = (new static())->table;
        $stmt = $db->prepare("SELECT * FROM {$table} WHERE " . static::$primaryKey . " = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public static function all(): array {
        $db = Database::connect();
        $table = (new static())->table;
        $stmt = $db->query("SELECT * FROM {$table}");
        return $stmt->fetchAll();
    }

    public static function create(array $data): int {
        $db = Database::connect();
        $table = (new static())->table;

        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_map(fn($k) => ':' . $k, array_keys($data)));

        $stmt = $db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);

        return (int) $db->lastInsertId();
    }
}
