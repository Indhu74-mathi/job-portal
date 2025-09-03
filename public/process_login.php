<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

if (!verify_csrf($_POST['csrf'] ?? '')) {
    $_SESSION['errors'] = ['Invalid request.'];
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    $_SESSION['errors'] = ['Enter valid credentials.'];
    header('Location: login.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, password_hash FROM `users` WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['errors'] = ['Invalid email or password.'];
        header('Location: login.php');
        exit;
    }

    // Login OK
    $_SESSION['user_id'] = $user['id'];
    header('Location: main.php');
    exit;

} catch (Throwable $e) {
    $_SESSION['errors'] = ['Error: ' . $e->getMessage()];
    header('Location: login.php');
    exit;
}
