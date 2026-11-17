<?php
namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database {
    private static ?PDO $connection = null;
    private function __construct() {}
    private function __clone() {}

    public static function connection(): PDO {
        if (self::$connection === null) {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $name = $_ENV['DB_NAME'] ?? 'chikondicare';
            $user = $_ENV['DB_USER'] ?? 'root';
            $pass = $_ENV['DB_PASS'] ?? '';
            $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";
            try {
                self::$connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $ex) {
                error_log($ex->getMessage());
                throw new RuntimeException('Database connection failed. Check .env settings.');
            }
        }
        return self::$connection;
    }
}
