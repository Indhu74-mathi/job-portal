<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$jobs = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>View Jobs</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f6f8fb}
    .layout{display:flex;min-height:100vh}
    table{width:100%;border-collapse:collapse;background:#fff}
    th,td{border:1px solid #ddd;padding:8px;text-align:left}
    th{background:#f3f4f6}
  </style>
</head>
<body>
<div class="layout">
  <?php include 'recruiter_sidebar.php'; ?>
  <main class="main">
    <h2>All Jobs</h2>
    <table>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Company</th>
        <th>Location</th>
        <th>Posted Days</th>
        <th>Created At</th>
      </tr>
      <?php foreach($jobs as $job): ?>
      <tr>
        <td><?= $job['id'] ?></td>
        <td><?= htmlspecialchars($job['title']) ?></td>
        <td><?= htmlspecialchars($job['company']) ?></td>
        <td><?= htmlspecialchars($job['location']) ?></td>
        <td><?= (int)$job['posted_days'] ?></td>
        <td><?= $job['created_at'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</div>
</body>
</html>
