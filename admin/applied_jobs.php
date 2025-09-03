<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/timeago.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

// list who applied to which job
$sql = "SELECT ja.id, ja.applied_at, u.full_name, u.email, j.title, j.company
        FROM job_applications ja
        JOIN users u ON u.id = ja.user_id
        JOIN jobs j ON j.id = ja.job_id
        ORDER BY ja.applied_at DESC";
$rows = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Applied Jobs — Admin</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f6f8fb;color:#111}
    .layout{display:flex;min-height:100vh}
    /* Sidebar */
    .side{width:230px;background:#111827;color:#fff;padding:18px}
    .side a{display:block;color:#e5e7eb;text-decoration:none;padding:10px;border-radius:8px;margin-bottom:6px}
    .side a:hover,.side a.active{background:#1f2937}
    /* Main content */
    .main{flex:1;padding:20px}
    .wrap{max-width:1000px;margin:0 auto;background:#fff;border:1px solid #eef2f7;border-radius:12px;padding:18px}
    table{width:100%;border-collapse:collapse;font-size:14px}
    th,td{padding:10px;border-bottom:1px solid #eef2f7;text-align:left}
    th{background:#f9fafb}
    .muted{color:#6b7280;font-size:13px}
    .btn{padding:8px 12px;border:1px solid #e5e7eb;border-radius:8px;background:#fff;cursor:pointer;text-decoration:none;color:#111;font-size:14px}
    .btn:hover{background:#f3f4f6}
  </style>
</head>
<body>
<div class="layout">
  <!-- Sidebar -->
  <aside class="side">
    <div style="font-weight:700;margin-bottom:14px">Admin</div>
    <a href="dashboard.php">Overview</a>
    <a href="job_create.php">Job Update (Add New)</a>
    <a href="applied_jobs.php" class="active">Applied Jobs</a>
    <a href="../main.php">Back to Site</a>
  </aside>

  <!-- Main content -->
  <main class="main">
    <div class="wrap">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h2 style="margin:0">Applied Jobs</h2>
        <a href="dashboard.php" class="btn">← Back</a>
      </div>

      <table>
        <thead>
          <tr>
            <th>Candidate</th>
            <th>Email</th>
            <th>Job</th>
            <th>Company</th>
            <th>Applied</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($rows): ?>
            <?php foreach($rows as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['full_name']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['title']) ?></td>
                <td><?= htmlspecialchars($r['company']) ?></td>
                <td class="muted"><?= time_ago($r['applied_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5" class="muted">No applications yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</div>
</body>
</html>
