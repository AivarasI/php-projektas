<?php
class PasswordGenerator {
    public $lowercase = 0;
    public $uppercase = 0;
    public $numbers   = 0;
    public $specials  = 0;

    public function generate() {
        $password = '';
        $password .= $this->pick("abcdefghijklmnopqrstuvwxyz", $this->lowercase);
        $password .= $this->pick("ABCDEFGHIJKLMNOPQRSTUVWXYZ", $this->uppercase);
        $password .= $this->pick("0123456789", $this->numbers);
        $password .= $this->pick("!@#$%^&*.", $this->specials);

        return str_shuffle($password);
    }

    private function pick($chars, $count) {
        $out = '';
        for ($i = 0; $i < $count; $i++) {
            $out .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $out;
    }
}
