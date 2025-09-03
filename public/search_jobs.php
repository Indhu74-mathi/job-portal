<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode([]); exit; }

$stmt = $pdo->prepare("SELECT * FROM jobs WHERE title LIKE ? OR company LIKE ? OR skills LIKE ? OR location LIKE ? ORDER BY created_at DESC LIMIT 20");
$like = "%$q%";
$stmt->execute([$like,$like,$like,$like]);
$rows = $stmt->fetchAll();
echo json_encode($rows);
