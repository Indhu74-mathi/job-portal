<?php
// Simple upload for CKEditor -> returns JSON {url: "..."}
session_start();
$uploadDir = __DIR__ . '/../public_uploads'; // or ../public/uploads
$publicBase = '/public_uploads';             // adjust to your web root

if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }

if (!empty($_FILES['upload']['name'])) {
    $name = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/','_', $_FILES['upload']['name']);
    $dest = $uploadDir . '/' . $name;
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $dest)) {
        header('Content-Type: application/json');
        echo json_encode(['url' => $publicBase . '/' . $name]);
        exit;
    }
}
http_response_code(400);
header('Content-Type: application/json');
echo json_encode(['error' => 'Upload failed']);
