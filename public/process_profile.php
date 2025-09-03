<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['user_id'])) {
    header('Location: profile.php'); exit;
}
if (!verify_csrf($_POST['csrf'] ?? '')) {
    $_SESSION['errors'] = ['Invalid request.']; header('Location: profile.php'); exit;
}

$userId = $_SESSION['user_id'];
$full_name = trim($_POST['full_name'] ?? '');
$current_city = trim($_POST['current_city'] ?? '');
$work_status = $_POST['work_status'] ?? 'fresher';
$total_exp_months = (int)($_POST['total_exp_months'] ?? 0);

// basic validation
$errs = [];
if ($full_name === '') $errs[] = 'Full name required';
if (!in_array($work_status, ['fresher','experienced'])) $work_status='fresher';

if ($errs) { $_SESSION['errors']=$errs; header('Location: profile.php'); exit; }

// resume upload (optional)
$resumePath = null;
if (!empty($_FILES['resume']) && ($_FILES['resume']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $fname = 'resume_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetDir = __DIR__ . '/uploads/resumes/';
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    $full = $targetDir . $fname;
    if (!move_uploaded_file($_FILES['resume']['tmp_name'], $full)) {
        $_SESSION['errors'] = ['Could not save resume.']; header('Location: profile.php'); exit;
    }
    $resumePath = 'uploads/resumes/' . $fname;
}

// update DB
$sql = "UPDATE users SET full_name = :full_name, current_city=:current_city, work_status=:work_status, total_exp_months=:total_exp_months"
     . ($resumePath ? ", resume_path = :resume_path" : "") . " WHERE id = :id";
$stmt = $pdo->prepare($sql);
$params = [
  ':full_name'=>$full_name, ':current_city'=>$current_city, ':work_status'=>$work_status,
  ':total_exp_months'=>$total_exp_months, ':id'=>$userId
];
if ($resumePath) $params[':resume_path']=$resumePath;
$stmt->execute($params);

header('Location: profile.php');
exit;
