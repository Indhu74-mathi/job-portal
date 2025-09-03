<?php
require_once __DIR__ . '/session.php';


function csrf_token(): string {
if (empty($_SESSION['csrf'])) {
$_SESSION['csrf'] = bin2hex(random_bytes(32));
}
return $_SESSION['csrf'];
}


function verify_csrf(string $token): bool {
return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}


function old(string $key, $default = ''): string {
return isset($_SESSION['old'][$key]) ? htmlspecialchars((string)$_SESSION['old'][$key]) : htmlspecialchars((string)$default);
}


function flash_errors(): array {
$errs = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
return $errs;
}


function put_old(array $data): void {
$_SESSION['old'] = $data;
}


function clear_old(): void {
unset($_SESSION['old']);
}


function validate_upload(array $file): array {
$errors = [];
if ($file['error'] === UPLOAD_ERR_NO_FILE) {
return $errors; // resume optional; enforce if you want
}


if ($file['error'] !== UPLOAD_ERR_OK) {
$errors[] = 'Resume upload failed.';
return $errors;
}


// 2 MB limit
if ($file['size'] > 2 * 1024 * 1024) {
$errors[] = 'Resume must be <= 2 MB.';
}


$allowed = ['pdf','doc','docx'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
$errors[] = 'Resume must be PDF/DOC/DOCX.';
}


return $errors;
}