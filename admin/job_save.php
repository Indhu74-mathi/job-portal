<?php
require_once __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

$title = trim($_POST['title'] ?? '');
$company = trim($_POST['company'] ?? '');
$location = trim($_POST['location'] ?? '');
$skills = trim($_POST['skills'] ?? '');
$rating = $_POST['rating'] !== '' ? (float)$_POST['rating'] : null;
$posted_days = $_POST['posted_days'] !== '' ? (int)$_POST['posted_days'] : 0;
$description = $_POST['description'] ?? '';

if ($title && $company && $location) {
  $stmt = $pdo->prepare('INSERT INTO jobs (title, company, location, description, skills, rating, posted_days) VALUES (?,?,?,?,?,?,?)');
  $stmt->execute([$title, $company, $location, $description, $skills, $rating, $posted_days]);
}
header('Location: dashboard.php');
