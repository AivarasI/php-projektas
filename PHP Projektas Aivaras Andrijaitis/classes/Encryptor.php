<?php
class Encryptor {
    private static $method = "AES-256-CBC";

    public static function encrypt($data, $password) {
        $key = hash('sha256', $password, true);
        $iv = openssl_random_pseudo_bytes(16);
        $encrypted = openssl_encrypt($data, self::$method, $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($data, $password) {
        $data = base64_decode($data);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $key = hash('sha256', $password, true);
        return openssl_decrypt($encrypted, self::$method, $key, 0, $iv);
    }
}
