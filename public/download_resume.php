<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
if (empty($_SESSION['user_id'])) { header('HTTP/1.1 403 Forbidden'); exit; }

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: main.php'); exit; }

// only allow admin or the owner to download — here we allow owner only
if ($id !== (int)$_SESSION['user_id']) { header('HTTP/1.1 403 Forbidden'); exit; }

$stmt = $pdo->prepare('SELECT resume_path FROM users WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$row = $stmt->fetch();
if (!$row || empty($row['resume_path'])) { header('HTTP/1.1 404 Not Found'); exit; }

$path = __DIR__ . '/' . $row['resume_path'];
if (!file_exists($path)) { header('HTTP/1.1 404 Not Found'); exit; }

$filename = basename($path);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
