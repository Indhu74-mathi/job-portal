<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['user_id'])) { echo json_encode(['count'=>0]); exit; }
$stmt = $pdo->prepare('SELECT current_city FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$city = $user['current_city'] ?? '';

if ($city) {
    $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM jobs WHERE location LIKE ?');
    $stmt->execute(["%$city%"]);
    $row = $stmt->fetch();
    $count = (int)$row['c'];
} else {
    $stmt = $pdo->prepare('SELECT COUNT(*) as c FROM jobs');
    $stmt->execute();
    $count = (int)$stmt->fetch()['c'];
}

echo json_encode(['count'=>$count]);
