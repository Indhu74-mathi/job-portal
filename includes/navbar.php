<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/timeago.php';

// Fetch user
$user = [];
if (!empty($_SESSION['user_id'])) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

// Recent jobs count
$notifCount = 0;
if (!empty($user)) {
    $n = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 3 DAY)");
    $n->execute();
    $notifCount = $n->fetchColumn();
}
?>

<header class="top-nav">
  <div class="brand">
    <a href="main.php"><img src="/job-register/job-portal/public/assets/image/serphawklogo.png" alt="Logo" class="logo" style="width:60px; height:60px;"></a>
  </div>

  <div class="hamburger" onclick="toggleMenu()">☰</div>

  <nav id="navbarMenu" class="nav-links">
    <ul>
      <li class="dropdown">
        <a href="#">Jobs <i class="fa fa-caret-down"></i></a>
        <div class="dropdown-content">
          <a href="/job-register/job-portal/public/recommended_jobs.php">Recommended Jobs</a>
          <a href="/job-register/job-portal/public/applied_status.php">Applied Status</a>
        </div>
      </li>
      <li class="dropdown">
        <a href="#">Companies <i class="fa fa-caret-down"></i></a>
        <div class="dropdown-content">
          <span class="dropdown-heading">Explore</span>
          <a href="/job-register/job-portal/public/companies.php?filter=top">Top Companies</a>
          <a href="/job-register/job-portal/public/companies.php?filter=it">IT Companies</a>
          <a href="/job-register/job-portal/public/companies.php?filter=mnc">MNC</a>
          <a href="/job-register/job-portal/public/companies.php?filter=startup">Startup</a>
        </div>
      </li>
      <li class="dropdown">
        <a href="#">Services <i class="fa fa-caret-down"></i></a>
        <div class="dropdown-content">
          <a href="/job-register/job-portal/public/resume.php">Resume Writing</a>
          <a href="/job-register/job-portal/public/resume_text.php">Resume Text</a>
        </div>
      </li>
    </ul>
  </nav>

  <form action="search.php" method="get" style="display:flex;gap:4px;">
  <input type="text" name="q" placeholder="Search jobs..." style="padding:8px;border-radius:30px;border:1px solid #ccc;">
  <button type="submit" style="padding:8px 12px;border-radius:50px;border:1px solid #ccc;background:#fff;cursor:pointer;">
    <i class="fa fa-search"></i>
  </button>
  </form>

  <!-- <div class="search-box">
    <input id="jobSearch" placeholder="Search jobs...">
    <button id="searchBtn"><i class="fa fa-search"></i></button>
  </div> -->

  <div class="notif" onclick="window.location='/job-register/job-portal/public/notifications.php'">
    <i class="fa fa-bell"></i>
    <?php if ($notifCount > 0): ?>
      <span class="badge"><?= $notifCount ?></span>
    <?php endif; ?>
  </div>

  <!-- <div class="profile-menu">
    <img src="<?= e($user['profile_photo'] ?? '/job-register/job-portal/assets/image/avatar.png') ?>"
         alt="Profile" class="profile-pic" onclick="toggleProfileMenu()">
    <div id="profileDropdown" class="profile-dropdown">
      <a href="/job-register/job-portal/public/profile.php">View / Update Profile</a>
      <a href="/job-register/job-portal/public/logout.php">Logout</a>
    </div>
  </div> -->

  <img id="profileAvatar" src="<?= e($user['profile_photo'] ?: 'assets/image/avatar.png') ?>" style="width:40px;height:40px;border-radius:50%;cursor:pointer"> </div> <div id="profileMenu" style="display:none;position:absolute;right:0;top:48px;background:#fff;border:1px solid #eee;padding:8px;border-radius:8px;min-width:160px"> <a href="profile.php" style="display:block;padding:8px;color:#111;text-decoration:none">View / Update Profile</a> <a href="logout.php" style="display:block;padding:8px;color:#111;text-decoration:none">Logout</a> </div> </div> </div>
</header>

<style>
.top-nav {
  display:flex; align-items:center; justify-content:space-between;
  background:#fff; padding:12px 20px; border-bottom:1px solid #eee; position:relative;
}
.logo { height:40px; }
.hamburger { display:none; font-size:24px; cursor:pointer; }
.nav-links ul { list-style:none; display:flex; gap:20px; margin:0; padding:0; }
.nav-links a { color:#111; text-decoration:none; font-weight:500; }
.dropdown { position:relative; }
.dropdown-content {
  display:none; position:absolute; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1);
  border-radius:8px; padding:8px 0; top:100%; left:0; min-width:180px; z-index:10;
}
.dropdown:hover .dropdown-content { display:block; }
.dropdown-heading {
  font-weight:bold; margin:0 12px 6px; color:#555; font-size:13px;
  border-bottom:1px solid #eee; padding-bottom:4px;
}
.dropdown-content a {
  display:block; padding:8px 12px; color:#111;
}
.dropdown-content a:hover {
  background:#f6f8fa;
}
.search-box {
  display:flex; align-items:center; gap:6px; max-width:300px; flex:1;
}
.search-box input {
  flex:1; padding:6px 10px; border:1px solid #ddd; border-radius:20px; outline:none;
}
.search-box button {
  background:#fff; border:1px solid #ddd; border-radius:50%; padding:6px 8px;
  cursor:pointer;
}
.notif { position:relative; cursor:pointer; margin-right:20px; }
.badge {
  position:absolute; top:-6px; right:-6px; background:red; color:#fff;
  border-radius:50%; padding:3px 6px; font-size:12px;
}
.profile-menu { position:relative; }
.profile-pic {
  width:40px; height:40px; border-radius:50%; cursor:pointer;
}
.profile-dropdown {
  display:none; position:absolute; right:0; top:48px; background:#fff;
  border:1px solid #eee; border-radius:8px; min-width:160px;
  box-shadow:0 2px 6px rgba(0,0,0,0.1);
}
.profile-dropdown a {
  display:block; padding:10px; text-decoration:none; color:#111;
}
.profile-dropdown a:hover { background:#f6f8fa; }
@media (max-width:768px) {
  .hamburger { display:block; }
  .nav-links { display:none; position:absolute; top:60px; left:0; width:100%; background:#fff; }
  .nav-links.show { display:flex; flex-direction:column; }
  .nav-links ul { flex-direction:column; gap:0; }
  .search-box { display:none; }
}
</style>

<script>
function toggleMenu() {
  document.getElementById('navbarMenu').classList.toggle('show');
}
function toggleProfileMenu() {
  document.getElementById('profileDropdown').classList.toggle('show');
}
document.getElementById('searchBtn').addEventListener('click', function(){
  const q = document.getElementById('jobSearch').value.trim();
  if (q) window.location = '/job-register/job-portal/public/search.php?q=' + encodeURIComponent(q);
});
</script>
