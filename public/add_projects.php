<?php
require_once __DIR__ . '/../config/db.php';

// Read POST values safely
$user_id      = $_POST['user_id']      ?? null;
$title        = trim($_POST['title'] ?? '');
$description  = trim($_POST['description'] ?? '');
$technologies = trim($_POST['technologies'] ?? '');

// Insert new project only if valid
if ($user_id && $title) {
    try {
        $stmt = $pdo->prepare("INSERT INTO projects (user_id, title, description, technologies) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_id, $title, $description, $technologies]);
    } catch (Exception $e) {
        // Handle insert error (optional logging)
    }
}

// Fetch updated projects list
$stmt = $pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$projects = $stmt->fetchAll();

if ($projects) {
    foreach ($projects as $proj) {
        echo "<div class='mb-2 p-2 border rounded' data-id='{$proj['id']}'>
                <b>{$proj['title']}</b>
                <span class='text-danger ms-2 project-delete' style='cursor:pointer;'>&times;</span>
                <p class='mb-1'>{$proj['description']}</p>
                <small>Technologies: {$proj['technologies']}</small>
              </div>";
    }
} else {
    echo "<p class='text-muted'>No projects added yet.</p>";
}
