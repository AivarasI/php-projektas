<?php
session_start();
require_once "../classes/Database.php";
require_once "../classes/Encryptor.php";
require_once "../classes/PasswordGenerator.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>

<a href="dashboard.php">⬅ Grįžti atgal</a>
<hr>

<?php
$db = (new Database())->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $gen = new PasswordGenerator();
    $gen->lowercase = $_POST['lower'];
    $gen->uppercase = $_POST['upper'];
    $gen->numbers   = $_POST['numbers'];
    $gen->specials  = $_POST['specials'];

    $generatedPassword = $gen->generate();

    $key = Encryptor::decrypt(
        $_SESSION['user']['encrypted_key'],
        $_SESSION['plain_password']
    );

    $encryptedPassword = Encryptor::encrypt($generatedPassword, $key);

    $stmt = $db->prepare(
        "INSERT INTO passwords (user_id, title, encrypted_password)
         VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_SESSION['user']['id'],
        $_POST['title'],
        $encryptedPassword
    ]);

    echo "Jūsų sugeneruotas {$_POST['title']} slaptažodis: <b>$generatedPassword</b>";
}
?>

<form method="post">
    <br>Generuojamo slaptažodžio pavadinimas: <input name="title" required><br>
    Generacijos nustatymai:<br>
    Mažosios: <input type="number" name="lower" value="2"><br>
    Didžiosios: <input type="number" name="upper" value="2"><br>
    Skaičiai: <input type="number" name="numbers" value="2"><br>
    Specialūs: <input type="number" name="specials" value="1"><br>
    <button>Generuoti ir išsaugoti</button>
</form>
