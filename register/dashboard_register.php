<?php
session_start();
require_once "../config/db.php";

$error = "";

// Default hard-coded credentials
$admin_username = "admin";
$admin_password = "admin123";

$recruiter_username = "recruiter";
$recruiter_password = "recruiter123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (!empty($username) && !empty($password) && !empty($role)) {
        
        // ADMIN LOGIN
        if ($role === "admin" && $username === $admin_username && $password === $admin_password) {
            $_SESSION['role'] = 'admin';
            $_SESSION['username'] = $username;
            header("Location: ../admin/dashboard.php");
            exit;
        }

        // RECRUITER LOGIN
        if ($role === "recruiter" && $username === $recruiter_username && $password === $recruiter_password) {
            $_SESSION['role'] = 'recruiter';
            $_SESSION['username'] = $username;
            header("Location: ../recruiter/dashboard.php");
            exit;
        }

        $error = "Invalid credentials or role selection!";
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin / Recruiter Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 360px;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-box input, 
        .login-box select, 
        .login-box button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-box button {
            background: #28a745;
            margin-top: 50px;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        .login-box button:hover {
            background: #218838;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>

        <select name="role" required>
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="recruiter">Recruiter</option>
        </select>

        <button type="submit">Login</button>
    </form>

    <?php if (!empty($error)) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</div>
</body>
</html>
