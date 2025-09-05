<?php
session_start();
require_once __DIR__ . '/../config/db.php';

// ✅ Ensure only recruiter can access
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'recruiter') {
    header('Location: ../login.php');
    exit;
}

// 🔹 Simulate recruiter_id based on username from session
// In real system, you will fetch recruiter_id from `recruiters` table using logged-in user
// Example: SELECT id FROM recruiters WHERE user_id = ?
$recruiter_id = 1; // Replace this with dynamic fetch if needed

// 1. Total Jobs posted by recruiter
$stmt = $pdo->prepare("SELECT COUNT(*) FROM recruiter_jobs WHERE recruiter_id = ?");
$stmt->execute([$recruiter_id]);
$total_jobs = $stmt->fetchColumn();

// 2. Total Applications for recruiter's jobs
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM job_applications ja
    INNER JOIN recruiter_jobs rj ON ja.job_id = rj.job_id
    WHERE rj.recruiter_id = ?
");
$stmt->execute([$recruiter_id]);
$total_applications = $stmt->fetchColumn();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Recruiter Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Inter, Segoe UI, Arial, sans-serif;
      background: #f6f8fb;
    }
    .layout { display: flex; min-height: 100vh; }
    .side {
      width: 230px;
      background: #111827;
      color: #fff;
      padding: 18px;
    }
    .side a {
      display: block;
      color: #e5e7eb;
      text-decoration: none;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 6px;
    }
    .side a:hover { background: #1f2937; }
    .main { flex: 1; padding: 20px; }
    .card {
      background: #fff;
      border: 1px solid #eef2f7;
      border-radius: 12px;
      padding: 16px;
      margin-bottom: 12px;
    }
    h2 { margin-top: 0; }
  </style>
</head>
<body>
  <div class="layout">
    <!-- Sidebar -->
    <aside class="side">
      <div style="font-weight:700;margin-bottom:14px">Recruiter Panel</div>
      <a href="dashboard.php">Overview</a>
      <a href="add_job.php">Add Job</a>
      <a href="view_job.php">View Jobs</a>
      <a href="delete_job.php">Delete Jobs</a>
      <a href="applied_jobs.php">View Applications</a>
      <a href="../register/logout.php">Logout</a>
    </aside>

    <!-- Main Content -->
    <main class="main">
      <h2>Overview</h2>
      <div class="card">Total Jobs Posted: <?= (int)$total_jobs ?></div>
      <div class="card">Total Applications: <?= (int)$total_applications ?></div>
      <div class="card">
        Use <b>Add Job</b> to create job posts.<br>
        Use <b>View Applications</b> to see who applied.
      </div>
    </main>
  </div>
</body>
</html>
