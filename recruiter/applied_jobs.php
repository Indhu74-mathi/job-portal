<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$sql = "SELECT ja.id, ja.applied_at, j.title, j.company, u.full_name, u.email, u.phone
        FROM job_applications ja
        JOIN jobs j ON ja.job_id = j.id
        JOIN users u ON ja.user_id = u.id
        ORDER BY ja.applied_at DESC";

$applications = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Applied Jobs</title>
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
    <h2>Applied Jobs</h2>
    <table>
      <tr>
        <th>Application ID</th>
        <th>Job Title</th>
        <th>Company</th>
        <th>Candidate Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Applied At</th>
      </tr>
      <?php foreach($applications as $app): ?>
      <tr>
        <td><?= $app['id'] ?></td>
        <td><?= htmlspecialchars($app['title']) ?></td>
        <td><?= htmlspecialchars($app['company']) ?></td>
        <td><?= htmlspecialchars($app['full_name']) ?></td>
        <td><?= htmlspecialchars($app['email']) ?></td>
        <td><?= htmlspecialchars($app['phone']) ?></td>
        <td><?= $app['applied_at'] ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
  </main>
</div>
</body>
</html>
