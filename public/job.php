<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/timeago.php'; 
require_once __DIR__ . '/../includes/navbar.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$job = $stmt->fetch();
if (!$job){ http_response_code(404); echo "Job not found"; exit; }

// has user applied?
$chk = $pdo->prepare('SELECT 1 FROM job_applications WHERE job_id=? AND user_id=? LIMIT 1');
$chk->execute([$id, $_SESSION['user_id']]);
$applied = (bool)$chk->fetchColumn();

function e($v){ return htmlspecialchars((string)$v); }
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= e($job['title']) ?> — Job</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f8fafc;color:#111}
    .wrap{max-width:900px;margin:24px auto;padding:0 16px}
    .card{background:#fff;border:1px solid #eef2f7;border-radius:12px;padding:18px}
    .muted{color:#6b7280}
    .btn{padding:10px 14px;border-radius:10px;border:0;cursor:pointer}
    .btn-primary{background:#3b82f6;color:#fff}
    .btn-disabled{background:#a7b3c3;color:#fff;cursor:not-allowed}
  </style>
</head>

<body>
  <div class="wrap">
    <div style="margin-bottom:10px"><a href="recommended_jobs.php" style="text-decoration:none">&larr; Back</a></div>
    <div class="card">
      <h2 style="margin:0"><?= e($job['title']) ?></h2>
      <div class="muted" style="margin-top:6px"><?= e($job['company']) ?> • <?= e($job['location']) ?> • <?= $job['rating']? e($job['rating']).'★' : '' ?></div>
      <?php if ($job['skills']): ?>
        <div class="muted" style="margin-top:6px">Skills: <?= e($job['skills']) ?></div>
      <?php endif; ?>
      <div class="muted" style="margin-top:6px"><?= time_ago($job['created_at']) ?></div>

      <hr style="margin:16px 0;border:0;border-top:1px solid #eee">

      <!-- Admin entered rich HTML from CKEditor -->
      <div><?= $job['description'] /* trusted admin content */ ?></div>

      <div style="margin-top:18px">
        <?php if ($applied): ?>
          <button class="btn btn-disabled" disabled>Already Applied</button>
        <?php else: ?>
          <form method="post" action="apply_job.php" style="display:inline">
            <input type="hidden" name="job_id" value="<?= (int)$job['id'] ?>">
            <button class="btn btn-primary" type="submit">Apply</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
