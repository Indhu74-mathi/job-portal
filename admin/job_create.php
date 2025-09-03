<?php
require_once __DIR__ . '/../config/db.php';
session_start();
if (empty($_SESSION['user_id'])) { header('Location: ../login.php'); exit; }

// delete job
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $pdo->prepare("DELETE FROM jobs WHERE id=?")->execute([$id]);
  header("Location: job_create.php?deleted=1");
  exit;
}

// fetch jobs
$stmt = $pdo->query("SELECT * FROM jobs ORDER BY created_at DESC");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Job — Admin</title>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f6f8fb;color:#111}
    .layout{display:flex;min-height:100vh}
    /* Sidebar */
    .side{width:230px;background:#111827;color:#fff;padding:18px}
    .side a{display:block;color:#e5e7eb;text-decoration:none;padding:10px;border-radius:8px;margin-bottom:6px}
    .side a:hover,.side a.active{background:#1f2937}
    /* Main content */
    .main{flex:1;padding:20px}
    .wrap{max-width:950px;margin:0 auto;background:#fff;border:1px solid #eef2f7;border-radius:12px;padding:18px}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
    label{font-weight:600;font-size:14px;margin-bottom:6px;display:block}
    input,textarea{width:90%;padding:12px;border:1px solid #d1d5db;border-radius:8px;font-size:14px}
    input:focus,textarea:focus{outline:none;border-color:#3b82f6;box-shadow:0 0 0 2px rgba(59,130,246,0.2)}
    .btn{padding:10px 16px;border:0;border-radius:8px;background:#3b82f6;color:#fff;cursor:pointer;font-weight:600}
    .btn:hover{background:#2563eb}
    table{width:100%;border-collapse:collapse;margin-top:20px;font-size:14px}
    th,td{padding:10px;border-bottom:1px solid #e5e7eb;text-align:left}
    th{background:#f9fafb}
    .actions a{margin-right:8px;text-decoration:none;font-size:13px;padding:6px 10px;border-radius:6px}
    .view{background:#10b981;color:#fff}
    .delete{background:#ef4444;color:#fff}
    .view:hover{background:#059669}
    .delete:hover{background:#dc2626}
  </style>
  <!-- CKEditor 5 Classic -->
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
</head>
<body>
<div class="layout">
  <!-- Sidebar -->
  <aside class="side">
    <div style="font-weight:700;margin-bottom:14px">Admin</div>
    <a href="dashboard.php">Overview</a>
    <a href="job_create.php" class="active">Job Update (Add New)</a>
    <a href="applied_jobs.php">Applied Jobs</a>
    <a href="../main.php">Back to Site</a>
  </aside>

  <!-- Main content -->
  <main class="main">
    <div class="wrap">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <h2 style="margin:0">Add New Job</h2>
        <a href="dashboard.php" style="text-decoration:none;font-size:14px;color:#3b82f6">← Back</a>
      </div>

      <!-- Add Job Form -->
      <form method="post" action="job_save.php">
        <div class="row">
          <div>
            <label>Title</label>
            <input name="title" required>
          </div>
          <div>
            <label>Company</label>
            <input name="company" required>
          </div>
          <div>
            <label>Location</label>
            <input name="location" required>
          </div>
          <div>
            <label>Skills (comma separated)</label>
            <input name="skills" placeholder="php,mysql,html,css">
          </div>
          <div>
            <label>Rating</label>
            <input name="rating" type="number" step="0.1" min="0" max="5" placeholder="4.2">
          </div>
          <div>
            <label>Posted Days (optional)</label>
            <input name="posted_days" type="number" min="0" placeholder="0">
          </div>
        </div>

        <div style="margin-top:12px">
          <label>Description</label>
          <textarea id="desc" name="description" rows="10"></textarea>
        </div>

        <div style="margin-top:14px">
          <button class="btn" type="submit">Save Job</button>
        </div>
      </form>

      <!-- Jobs Table -->
      <?php if ($jobs): ?>
      <h3 style="margin-top:30px;margin-bottom:10px">All Jobs</h3>
      <table>
        <thead>
          <tr>
            <th>Title</th>
            <th>Company</th>
            <th>Location</th>
            <th>Rating</th>
            <th>Posted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($jobs as $job): ?>
          <tr>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars($job['company']) ?></td>
            <td><?= htmlspecialchars($job['location']) ?></td>
            <td><?= htmlspecialchars($job['rating']) ?></td>
            <td><?= date("M d, Y", strtotime($job['created_at'])) ?></td>
            <td class="actions">
              <!-- <a class="view" href="job_view.php?id=<?= $job['id'] ?>">View</a> -->
              <a class="delete" href="?delete=<?= $job['id'] ?>" onclick="return confirm('Delete this job?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p style="margin-top:20px;color:#6b7280">No jobs added yet.</p>
      <?php endif; ?>

    </div>
  </main>
</div>

<script>
ClassicEditor
  .create(document.querySelector('#desc'), {
    ckfinder: { uploadUrl: 'upload_image.php' }
  })
  .catch(console.error);
</script>
</body>
</html>
