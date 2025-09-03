<?php
require_once __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$job_id = isset($_POST['job_id']) ? (int)$_POST['job_id'] : 0;
if ($job_id <= 0){ header('Location: recommended_jobs.php'); exit; }

// insert ignore duplicate
try{
    $stmt = $pdo->prepare('INSERT INTO job_applications (job_id, user_id) VALUES (?, ?)');
    $stmt->execute([$job_id, $_SESSION['user_id']]);
} catch (PDOException $e) {
    // unique constraint means already applied — ignore
}
header('Location: job.php?id=' . $job_id);
exit;
