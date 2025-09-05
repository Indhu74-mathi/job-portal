<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $company = trim($_POST['company']);
    $location = trim($_POST['location']);
    $skills = trim($_POST['skills']);
    $rating = $_POST['rating'] !== '' ? floatval($_POST['rating']) : null;
    $posted_days = $_POST['posted_days'] !== '' ? intval($_POST['posted_days']) : 0;
    $description = trim($_POST['description']);

    if ($title && $company && $location) {
        $stmt = $pdo->prepare("INSERT INTO jobs (title, company, location, skills, rating, posted_days, description) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $company, $location, $skills, $rating, $posted_days, $description]);
        $success = "Job added successfully!";
    } else {
        $error = "Please fill all required fields.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Job</title>
  <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
  <style>
    body{margin:0;font-family:Inter,Segoe UI,Arial;background:#f6f8fb}
    .layout{display:flex;min-height:100vh}
    .main{flex:1;padding:20px}
    form input, form textarea {width:100%;padding:8px;margin-bottom:10px;border:1px solid #ccc;border-radius:6px;}
    button{padding:10px 14px;border:none;background:#111827;color:white;border-radius:6px;cursor:pointer}
    .alert{padding:10px;border-radius:6px;margin-bottom:10px;}
    .success{background:#d1fae5;color:#065f46;}
    .error{background:#fee2e2;color:#b91c1c;}
  </style>
</head>
<body>
<div class="layout">
  <?php include 'recruiter_sidebar.php'; ?>
  <main class="main">
    <h2>Add New Job</h2>
    <?php if(!empty($success)): ?><div class="alert success"><?= $success ?></div><?php endif; ?>
    <?php if(!empty($error)): ?><div class="alert error"><?= $error ?></div><?php endif; ?>

    <form method="post">
      <input type="text" name="title" placeholder="Job Title" required>
      <input type="text" name="company" placeholder="Company Name" required>
      <input type="text" name="location" placeholder="Location" required>
      <input type="text" name="skills" placeholder="Skills (comma separated)">
      <input type="number" step="0.1" name="rating" placeholder="Rating (optional)">
      <input type="number" name="posted_days" placeholder="Posted Days (optional)">
      <textarea name="description" placeholder="Job Description"></textarea>
      <script>CKEDITOR.replace('description');</script>
      <button type="submit">Submit Job</button>
    </form>
  </main>
</div>
</body>
</html>
