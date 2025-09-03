<?php
require_once __DIR__ . '/../includes/helpers.php';
if (!empty($_SESSION['user_id'])) {
    header('Location: main.php');
    exit;
}
$errors = flash_errors();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login — Job Portal</title>
  <style>
    body{font-family:Arial,Segoe UI;margin:40px;background:#fff}
    .box{max-width:420px;margin:40px auto;padding:24px;border:1px solid #eee;border-radius:8px}
    label{display:block;margin-bottom:6px}
    input{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;margin-bottom:12px}
    .btn{background:#457eff;color:#fff;border:none;padding:10px 14px;border-radius:6px;cursor:pointer}
    .error{background:#ffe6e6;padding:10px;color:#900;border-radius:6px;margin-bottom:12px}
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>
    <?php if($errors): ?><div class="error"><ul><?php foreach($errors as $e){echo '<li>'.htmlspecialchars($e).'</li>';}?></ul></div><?php endif; ?>
    <form action="process_login.php" method="post">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <label>Email</label>
      <input type="email" name="email" required>
      <label>Password</label>
      <input type="password" name="password" required>
      <button class="btn" type="submit">Login</button>
    </form>
    <p style="margin-top:12px">Don't have account? <a href="register.php">Register here</a></p>
  </div>
</body>
</html>
