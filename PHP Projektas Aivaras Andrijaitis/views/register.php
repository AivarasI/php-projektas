<?php
session_start();
require_once "../classes/Database.php";
require_once "../classes/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->connect();
    $user = new User($db);
    $user->register($_POST['username'], $_POST['password']);
    header("Location: login.php");
}
?>

<form method="post">
    Vardas: <input name="username" required><br>
    Slaptažodis: <input type="password" name="password" required><br>
    <button>Registruotis</button>
</form>
