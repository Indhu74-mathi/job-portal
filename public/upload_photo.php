<?php
session_start();
require_once __DIR__ . "/../config/db.php";

// Assuming logged in user
$user_id = 1; // replace with $_SESSION['user_id']

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_photo'])) {
    $targetDir = __DIR__ . "/uploads/profile/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["profile_photo"]["name"]);
    $targetFile = $targetDir . $fileName;
    $dbPath = "uploads/profile/" . $fileName;

    if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $targetFile)) {
        $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE id = ?");
        $stmt->execute([$dbPath, $user_id]);

        header("Location: profile.php");
        exit;
    } else {
        echo "Error uploading file.";
    }
}
