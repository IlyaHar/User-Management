<?php

namespace App\Kernel\Database;

use App\Kernel\Config\ConfigInterface;

class Model implements ModelInterface
{
    private static \PDO $pdo;
    protected static $table = null;

    public function __construct(private ConfigInterface $config)
    {
        $this->connect();
    }

    /**
     * @throws \Exception
     */

    public static function getAll(): array
    {
        self::getTable();

        $stmt = self::$pdo->query("SELECT * FROM `" . static::$table . "`");

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function create(array $data): int
    {
        self::getTable();

        $fields = array_keys($data);

        $columns = implode(', ', array_map(fn($field) => "`$field`", $fields));
        $binds = implode(', ', array_map(fn($field) => ":$field", $fields));

        $sql = "INSERT INTO `" . static::$table . "` ($columns) VALUES ($binds)";

        $stmt = self::$pdo->prepare($sql);

        $stmt->execute($data);

        return (int) self::$pdo->lastInsertId();
    }

    public static function update(array $data, int $id): bool
    {
        self::getTable();

        $fields = array_keys($data);

        $set = implode(', ', array_map(fn($field) => "$field = :$field", $fields));

        $sql = "UPDATE `" . static::$table . "` SET $set WHERE `id` = :id";

        $data['id'] = $id;

        $stmt = self::$pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public static function updateMany(array $data, array $ids): bool
    {
        self::getTable();

        $fields = array_keys($data);

        $set = implode(', ', array_map(fn($field) => "$field = ?", $fields));
        $placeholders = implode(', ', array_fill(0, count($ids), '?'));

        $sql = "UPDATE `" . static::$table . "` SET $set WHERE `id` IN ($placeholders)";

        $data = array_merge(array_values($data), $ids);

        $stmt = self::$pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public static function delete(int $id): bool
    {
        self::getTable();

        $sql = "DELETE FROM `" . static::$table . "` WHERE `id` = :id";

        $stmt = self::$pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public static function deleteMany(array $ids): bool
    {
        self::getTable();

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));

        $sql = "DELETE FROM `" . static::$table . "` WHERE `id` IN ($placeholders)";

        $stmt = self::$pdo->prepare($sql);

        return $stmt->execute($ids);
    }

    public static function find(int $id): array
    {
        self::getTable();

        $sql = "SELECT * FROM `" . static::$table  . "` WHERE `id` = :id";

        $stmt = self::$pdo->prepare($sql);

        $stmt->execute(['id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private static function getTable(): void
    {
        if (!isset(static::$table)) {
            $array = explode('\\', strtolower(get_called_class() . 's'));
            static::$table = $array[2];
        }
    }

    private function connect(): void
    {
        $driver = $this->config->get('database.driver');
        $host = $this->config->get('database.host');
        $port = $this->config->get('database.port');
        $database = $this->config->get('database.database');
        $username = $this->config->get('database.username');
        $password = $this->config->get('database.password');
        $charset = $this->config->get('database.charset');

        try {
            self::$pdo = new \PDO("$driver:host=$host;port=$port;dbname=$database;charset=$charset;", $username, $password);
        } catch (\PDOException $exception) {
            die('Database connection failed: ' . $exception->getMessage());
        }
    }
}
