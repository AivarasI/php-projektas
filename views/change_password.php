<?php
session_start();
require_once "../classes/Database.php";
require_once "../classes/Encryptor.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$db = (new Database())->connect();
$user = $_SESSION['user'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];

    // 1. Tikrinamas senas slaptažodis
    if (!password_verify($oldPassword, $user['password_hash'])) {
        $message = "Senas slaptažodis neteisingas!";
    } else {

        // 2. Iššifruojamas raktas su senu slaptažodžiu
        $key = Encryptor::decrypt($user['encrypted_key'], $oldPassword);

        if (!$key) {
            $message = "Nepavyko iššifruoti rakto!";
        } else {

            // 3. Naujas hash
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // 4. Peršifruojamas raktas su nauju slaptažodžiu
            $newEncryptedKey = Encryptor::encrypt($key, $newPassword);

            // 5. Atnaujinama duomenų bazė
            $stmt = $db->prepare("
                UPDATE users
                SET password_hash = ?, encrypted_key = ?
                WHERE id = ?
            ");
            $stmt->execute([$newHash, $newEncryptedKey, $user['id']]);

            // 6. Atnaujinama sesija
            $_SESSION['user']['password_hash'] = $newHash;
            $_SESSION['user']['encrypted_key'] = $newEncryptedKey;
            $_SESSION['plain_password'] = $newPassword;

            $message = "Slaptažodis sėkmingai pakeistas!";
        }
    }
}
?>

<a href="dashboard.php">⬅ Grįžti atgal</a>
<hr>

<h3>Keisti prisijungimo slaptažodį</h3>

<?php if ($message): ?>
    <p><b><?= $message ?></b></p>
<?php endif; ?>

<form method="post">
    <input type="password" name="old_password" placeholder="Senas slaptažodis" required><br>
    <input type="password" name="new_password" placeholder="Naujas slaptažodis" required><br>
    <button>Pakeisti slaptažodį</button>
</form>
