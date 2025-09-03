<?php
require_once __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }
// TODO: add admin role check if needed

// recent applications count
$apps = $pdo->query('SELECT COUNT(*) FROM job_applications')->fetchColumn();
$jobs = $pdo->query('SELECT COUNT(*) FROM jobs')->fetchColumn();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f6f8fb}
    .layout{display:flex;min-height:100vh}
    .side{width:230px;background:#111827;color:#fff;padding:18px}
    .side a{display:block;color:#e5e7eb;text-decoration:none;padding:10px;border-radius:8px;margin-bottom:6px}
    .side a:hover{background:#1f2937}
    .main{flex:1;padding:20px}
    .card{background:#fff;border:1px solid #eef2f7;border-radius:12px;padding:16px;margin-bottom:12px}
    .muted{color:#6b7280}
    .btn{padding:8px 12px;border-radius:8px;border:1px solid #e5e7eb;background:#fff;cursor:pointer}
  </style>
</head>
<body>
  <div class="layout">
    <aside class="side">
      <div style="font-weight:700;margin-bottom:14px">Admin</div>
      <a href="dashboard.php">Overview</a>
      <a href="job_create.php">Job Update (Add New)</a>
      <a href="applied_jobs.php">Applied Jobs</a>
      <a href="../main.php">Back to Site</a>
    </aside>
    <main class="main">
      <h2>Overview</h2>
      <div class="card">Total Jobs: <?= (int)$jobs ?></div>
      <div class="card">Total Applications: <?= (int)$apps ?></div>
      <div class="card">
        Use <b>Job Update</b> to create jobs with rich description (images allowed).  
        View who applied inside <b>Applied Jobs</b>.
      </div>
    </main>
  </div>
</body>
</html>
