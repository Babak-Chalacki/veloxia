<?php

namespace Veloxia\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $connection = null;

    public static function connect(): PDO {
        if (self::$connection === null) {
            $driver   = Env::get('DB_CONNECTION', 'mysql');
            $host     = Env::get('DB_HOST', '127.0.0.1');
            $port     = Env::get('DB_PORT', '3306');

            $defaultDb = basename(dirname(__DIR__, 1));
            $db        = Env::get('DB_DATABASE', $defaultDb);

            $user     = Env::get('DB_USERNAME', 'root');
            $password = Env::get('DB_PASSWORD', '');

            $dsn = "$driver:host=$host;port=$port;dbname=$db;charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
