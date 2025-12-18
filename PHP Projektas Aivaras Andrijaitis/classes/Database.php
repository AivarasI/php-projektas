<?php
class Database {
    private $conn;

    public function connect() {
        $config = require __DIR__ . '/../config/config.php';

        $db = $config['db'];

        $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";

        $this->conn = new PDO(
            $dsn,
            $db['user'],
            $db['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        return $this->conn;
    }
}