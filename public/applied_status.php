<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/timeago.php'; 
require_once __DIR__ . '/../includes/navbar.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch only the jobs the user has applied for
$stmt = $pdo->prepare("
    SELECT j.* 
    FROM jobs j
    INNER JOIN job_applications a ON j.id = a.job_id
    WHERE a.user_id = ?
    ORDER BY a.applied_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$appliedJobs = $stmt->fetchAll();

function e($v) { return htmlspecialchars((string)$v); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Applied Jobs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body { margin:0; font-family:Inter,Segoe UI,Arial; background:#f8fafc; color:#111 }
    .wrap { max-width:1000px; margin:24px auto; padding:0 16px }
    .card { background:#fff; border:1px solid #eef2f7; border-radius:12px; padding:16px; margin-bottom:12px }
    .title { font-weight:700; font-size:16px; }
    .muted { color:#6b7280; font-size:13px; }
    .job { display:flex; justify-content:space-between; gap:12px; align-items:center }
    a { color:#111; text-decoration:none }
    .btn { padding:8px 12px; border:1px solid #e5e7eb; border-radius:8px; background:#fff; cursor:pointer; font-size:13px }
    .no-jobs { text-align:center; color:#6b7280; margin-top:40px; }
  </style>
</head>
<body>
  <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
      <h2 style="margin:0">Applied Jobs</h2>
      <a class="btn" href="main.php">&larr; Back</a>
    </div>

    <?php if (count($appliedJobs) === 0): ?>
      <div class="no-jobs">
        <p>You haven't applied to any jobs yet.</p>
      </div>
    <?php else: ?>
      <?php foreach($appliedJobs as $j): ?>
        <div class="card job">
          <div>
            <a class="title" href="job.php?id=<?= (int)$j['id'] ?>"><?= e($j['title']) ?></a>
            <div class="muted" style="margin-top:4px"><?= e($j['company']) ?> • <?= e($j['location']) ?></div>
            <?php if (!empty($j['skills'])): ?>
              <div class="muted" style="margin-top:6px"><?= e($j['skills']) ?></div>
            <?php endif; ?>
            <div class="muted" style="margin-top:6px"><?= time_ago($j['created_at']) ?></div>
          </div>
          <div class="muted"><?= $j['rating'] ? e($j['rating']).'★' : '' ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</body>
</html>
