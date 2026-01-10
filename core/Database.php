<?php

// core/Database.php
require_once __DIR__ . '/Env.php';

class Database {
    private static ?PDO $conn = null;

    public static function connect(): PDO {
        if (!self::$conn) {

            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                Env::get('DB_HOST'),
                Env::get('DB_NAME'),
                Env::get('DB_CHARSET', 'utf8mb4')
            );

            self::$conn = new PDO(
                $dsn,
                Env::get('DB_USER'),
                Env::get('DB_PASS'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }

        return self::$conn;
    }
}


?>