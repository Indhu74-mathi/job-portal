<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/timeago.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// protect page
if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// fetch user
$stmt = $pdo->prepare('SELECT * FROM `users` WHERE id = ? LIMIT 1');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) {
    session_unset(); session_destroy();
    header('Location: login.php'); exit;
}

// helper: get recommended jobs (limit 5 for dashboard)
function getRecommendedJobs($pdo, $user, $limit = 5) {
    $city = $user['current_city'] ?? '';
    if ($city !== '') {
        $stmt = $pdo->prepare('SELECT * FROM jobs WHERE location LIKE ? ORDER BY created_at DESC LIMIT ?');
        $stmt->bindValue(1, "%$city%");
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) return $rows;
    }
    $stmt = $pdo->prepare('SELECT * FROM jobs ORDER BY created_at DESC LIMIT ?');
    $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

$recommended = getRecommendedJobs($pdo, $user, 5);

// escape helper
function e($v){ return htmlspecialchars((string)$v); }
?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Main — Job Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    :root{--muted:#6b7280;--accent:#457eff}
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f8fafc;color:#111}
    header.top{background:#fff;padding:18px 28px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eee}
    .brand{display:flex;align-items:center;gap:12px}
    .brand img{height:36px}
    .nav-links{display:flex;gap:18px;align-items:center}
    .search-box{flex:1;max-width:520px;margin:0 24px;display:flex}
    .search-box input{flex:1;padding:10px 14px;border:1px solid #e6e9ee;border-radius:24px 0 0 24px;outline:none}
    .search-box button{padding:10px 14px;border:1px solid #e6e9ee;border-left:0;background:#fff;border-radius:0 24px 24px 0;cursor:pointer}
    .container{display:flex;gap:28px;padding:28px;max-width:1200px;margin:0 auto}
    .left{flex:0 0 260px}
    .card{background:#fff;border-radius:12px;padding:18px;border:1px solid #f1f4f8}
    .profile-circle{width:88px;height:88px;border-radius:50%;background:#eef2ff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;color:#3b82f6;margin-bottom:12px}
    .center{flex:1}
    .right{flex:0 0 300px}
    /* Jobs list */
    .jobs-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
    .job-card{background:#fff;padding:16px;border-radius:10px;border:1px solid #f0f3f7}
    .job-title{font-weight:600;margin-bottom:8px}
    .job-meta{font-size:13px;color:var(--muted)}
    .small{font-size:13px;color:var(--muted)}
    @media(max-width:900px){.container{flex-direction:column}.jobs-grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <header class="top">
    <div class="brand">
      <img src="assets/image/serphawklogo.png" alt="Logo">
      <div class="nav-links">
        <a href="main.php">Jobs</a>
        <a href="#">Companies</a>
        <a href="#">Services</a>
      </div>
    </div>

    <div class="search-box">
      <input id="jobSearch" placeholder="Search jobs here (title, skill or company)" />
      <button id="searchBtn"><i class="fa fa-search"></i></button>
    </div>

    <div style="display:flex;align-items:center;gap:14px">
      <div id="notif" style="position:relative;cursor:pointer">
        <i class="fa fa-bell"></i>
        <span id="notifCount" style="position:absolute;top:-8px;right:-10px;background:#ef4444;color:#fff;border-radius:999px;padding:3px 7px;font-size:12px;display:none"></span>
      </div>
      <div style="position:relative">
        <div>
          <img id="profileAvatar" src="<?= e($user['profile_photo'] ?: 'assets/image/avatar.png') ?>" style="width:40px;height:40px;border-radius:50%;cursor:pointer">
        </div>        
        <div id="profileMenu" style="display:none;position:absolute;right:0;top:48px;background:#fff;border:1px solid #eee;padding:8px;border-radius:8px;min-width:160px">
          <a href="profile.php" style="display:block;padding:8px;color:#111;text-decoration:none">View / Update Profile</a>
          <a href="logout.php" style="display:block;padding:8px;color:#111;text-decoration:none">Logout</a>
        </div>
      </div>
    </div>
  </header>

  <main class="container">
    <aside class="left">
      <div class="card" style="text-align:center">
        <div>
            <a href="profile.php"><img id="profileAvatar" src="<?= e($user['profile_photo'] ?: 'assets/image/avatar.png') ?>" style="width:90px;height:90px;border-radius:50%;cursor:pointer"></a>
        </div>        
        <div style="font-weight:700"><?= e($user['full_name'] ?? '') ?></div>
        <div class="small" style="margin-top:8px"><?= e($user['current_city'] ?: '—') ?></div>
        <div style="margin-top:12px"><a href="profile.php" class="small">Complete profile</a></div>
      </div>

      <div style="height:12px"></div>

      <div class="card">
        <div style="font-weight:600;margin-bottom:8px">Profile performance</div>
        <div class="small">Search appearances: 0</div>
        <div class="small">Recruiter actions: 0</div>
      </div>
    </aside>

    <section class="center">
      <div class="card" style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <div>
            <div style="font-size:18px;font-weight:700">Recommended jobs for you</div>
            <div class="small">Recommended based on your profile</div>
          </div>
          <a href="recommended_jobs.php" class="small">View all</a>
        </div>
        <div style="height:14px"></div>

        <div class="jobs-grid" id="recommendJobs">
          <?php foreach($recommended as $job): ?>
            <div class="job-card">
              <div class="job-title"><?= e($job['title']) ?></div>
              <div class="job-meta"><?= e($job['company']) ?> • <?= e($job['location']) ?></div>
              <div style="height:8px"></div>
              <div class="small"><?= e(mb_strimwidth(strip_tags($job['description']),0,120,'...')) ?></div>
              <div style="margin-top:12px;display:flex;justify-content:space-between;align-items:center">
                <div class="small"><?= e($job['rating'] ? $job['rating'].'★' : '') ?></div>
                <div class="small"><?= time_ago($job['created_at']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="card">
        <div style="font-weight:700;margin-bottom:8px">Top companies hiring</div>
        <div class="small">Siemens • Calibre Infotech • TopTech</div>
      </div>
    </section>

    <aside class="right">
      <div class="card" style="margin-bottom:12px">
        <div style="font-weight:700">Tips</div>
        <div class="small">Never pay anyone to get a job. Learn more.</div>
      </div>

      <div class="card">
        <div style="font-weight:700">Ads / Promotions</div>
        <div class="small">Place your adverts here</div>
      </div>
    </aside>
  </main>

<script>
document.getElementById('profileAvatar').addEventListener('click', ()=>{
  const m = document.getElementById('profileMenu');
  m.style.display = m.style.display === 'block' ? 'none' : 'block';
});

// notifications
function refreshNotifs(){
  fetch('notifications.php').then(r=>r.json()).then(j=>{
    const c = document.getElementById('notifCount');
    if (j.count && j.count > 0){ c.style.display='inline-block'; c.textContent = j.count; }
    else c.style.display='none';
  }).catch(()=>{});
}
refreshNotifs();
</script>
</body>
</html>
