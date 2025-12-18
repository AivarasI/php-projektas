<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<h2>Sveiki, <?= $_SESSION['user']['username'] ?></h2>

<a href="add_password.php">Pridėti slaptažodį</a><br>
<a href="change_password.php">Keisti prisijungimo slaptažodį</a>

