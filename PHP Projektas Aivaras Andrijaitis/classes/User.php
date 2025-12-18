<?php
require_once 'Encryptor.php';

class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function register($username, $plainPassword) {
        $passwordHash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $key = bin2hex(random_bytes(16)); // RAKTAS
        $encryptedKey = Encryptor::encrypt($key, $plainPassword);

        $stmt = $this->db->prepare(
            "INSERT INTO users (username, password_hash, encrypted_key)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$username, $passwordHash, $encryptedKey]);
    }

    public function login($username, $plainPassword) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($plainPassword, $user['password_hash'])) {
            $_SESSION['user'] = $user;
            $_SESSION['plain_password'] = $plainPassword;
            return true;
        }
        return false;
    }
}
