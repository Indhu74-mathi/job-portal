<?php
// Toggle debug on while fixing problems
define('DEBUG', true);

// DEV: show errors while debugging (turn off in production)
if (DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// includes
require_once __DIR__ . '/../includes/helpers.php'; // session, csrf, helpers
require_once __DIR__ . '/../config/db.php';         // must create $pdo

// only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// CSRF
$csrf_token = $_POST['csrf'] ?? '';
if (!verify_csrf($csrf_token)) {
    $_SESSION['errors'] = ['Invalid request. Please try again.'];
    header('Location: register.php');
    exit;
}

// collect + sanitize
$input = [
    'full_name' => trim($_POST['full_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => preg_replace('/\D+/', '', $_POST['phone'] ?? ''),
    'password' => $_POST['password'] ?? '',
    'work_status' => $_POST['work_status'] ?? 'fresher',
    'total_exp_months' => (int)($_POST['total_exp_months'] ?? 0),
    'current_city' => trim($_POST['current_city'] ?? ''),
    'current_company' => trim($_POST['current_company'] ?? ''),
    'current_role' => trim($_POST['current_role'] ?? ''),
    'current_ctc_lpa' => $_POST['current_ctc_lpa'] !== '' ? (float)$_POST['current_ctc_lpa'] : null,
    'notice_period_days' => $_POST['notice_period_days'] !== '' ? (int)$_POST['notice_period_days'] : null,
];

put_old($input);
$errors = [];

// validations
if ($input['full_name'] === '' || mb_strlen($input['full_name']) < 3) {
    $errors[] = 'Please enter your full name (min 3 chars).';
}
if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Enter a valid email address.';
}
// note: phone optional? your earlier code required Indian phone - keeping it required
if (!preg_match('/^[6-9]\d{9}$/', $input['phone'])) {
    $errors[] = 'Enter a valid 10-digit mobile number (India).';
}
if (strlen($input['password']) < 8) {
    $errors[] = 'Password must be at least 8 characters.';
}
if (!in_array($input['work_status'], ['experienced', 'fresher'], true)) {
    $errors[] = 'Invalid work status.';
}

// only require city for experienced users
if ($input['work_status'] === 'experienced') {
    if ($input['current_city'] === '') {
        $errors[] = 'Current city is required for experienced candidates.';
    }
} else {
    // normalize fresher values so DB gets sensible defaults
    $input['total_exp_months'] = 0;
    $input['current_city'] = '';
    $input['current_company'] = null;
    $input['current_role'] = null;
    $input['current_ctc_lpa'] = null;
    $input['notice_period_days'] = null;
}

// resume file validation (helper returns array of errors)
$resumeErrors = validate_upload($_FILES['resume'] ?? []);
$errors = array_merge($errors, $resumeErrors);

if ($errors) {
    $_SESSION['errors'] = $errors;
    header('Location: register.php');
    exit;
}

/**
 * Handle resume upload (if provided).
 * We store under public/uploads/resumes/, which is: __DIR__ . '/uploads/resumes/'
 * __DIR__ is the full path to public/ because this file is in public/
 */
$resumePath = null;
if (!empty($_FILES['resume']) && ($_FILES['resume']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
    $fname = 'resume_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $targetDir = __DIR__ . '/uploads/resumes/';

    // create folder safely
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            $_SESSION['errors'] = ['Failed to create uploads directory. Please create public/uploads/resumes and make it writable.'];
            header('Location: register.php');
            exit;
        }
    }

    $full = $targetDir . $fname;
    if (!move_uploaded_file($_FILES['resume']['tmp_name'], $full)) {
        $_SESSION['errors'] = ['Could not save resume.'];
        header('Location: register.php');
        exit;
    }
    // relative path stored in DB (public/uploads/resumes/...)
    $resumePath = 'uploads/resumes/' . $fname;
}

// Database insert (with backticks to avoid identifier issues)
try {
    if (!isset($pdo)) {
        throw new \Exception('Database connection ($pdo) not found. Check config/db.php');
    }

    // duplicate email check
    $stmt = $pdo->prepare('SELECT id FROM `users` WHERE `email` = ? LIMIT 1');
    $stmt->execute([$input['email']]);
    if ($stmt->fetch()) {
        $_SESSION['errors'] = ['Email already registered. Try logging in.'];
        header('Location: register.php');
        exit;
    }

    $hash = password_hash($input['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO `users`
      (`full_name`, `email`, `phone`, `password_hash`, `work_status`, `total_exp_months`,
       `current_city`, `current_company`, `current_role`, `current_ctc_lpa`, `notice_period_days`, `resume_path`)
      VALUES
      (:full_name, :email, :phone, :password_hash, :work_status, :total_exp_months,
       :current_city, :current_company, :current_role, :current_ctc_lpa, :notice_period_days, :resume_path)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $input['full_name'],
        ':email' => $input['email'],
        ':phone' => $input['phone'],
        ':password_hash' => $hash,
        ':work_status' => $input['work_status'],
        ':total_exp_months' => $input['total_exp_months'],
        ':current_city' => $input['current_city'],
        ':current_company' => $input['current_company'],
        ':current_role' => $input['current_role'],
        ':current_ctc_lpa' => $input['current_ctc_lpa'],
        ':notice_period_days' => $input['notice_period_days'],
        ':resume_path' => $resumePath,
    ]);

    clear_old();
    header('Location: main.php');
    exit;

} catch (Throwable $e) {
    // show full error when DEBUG true
    if (defined('DEBUG') && DEBUG) {
        $_SESSION['errors'] = ['Error: ' . $e->getMessage()];
    } else {
        $_SESSION['errors'] = ['Something went wrong, please try again.'];
    }
    header('Location: register.php');
    exit;
}
