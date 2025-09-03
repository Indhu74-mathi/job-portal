<?php
require_once __DIR__ . '/../config/db.php';

// Read POST values safely
$user_id     = $_POST['user_id']     ?? null;
$skill_name  = trim($_POST['skill_name'] ?? '');
$proficiency = $_POST['proficiency'] ?? 'Beginner';

// Insert new skill only if valid
if ($user_id && $skill_name) {
    try {
        $stmt = $pdo->prepare("INSERT INTO skills (user_id, skill_name, proficiency) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $skill_name, $proficiency]);
    } catch (Exception $e) {
        // Handle insert error (optional logging)
    }
}

// Fetch updated skills list
$stmt = $pdo->prepare("SELECT * FROM skills WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user_id]);
$skills = $stmt->fetchAll();

if ($skills) {
    foreach ($skills as $skill) {
        echo "<span class='badge bg-primary me-1' data-id='{$skill['id']}'>
                {$skill['skill_name']} ({$skill['proficiency']})
                <span class='text-white ms-1 skill-delete' style='cursor:pointer;'>&times;</span>
              </span>";
    }
} else {
    echo "<p class='text-muted'>No skills added yet.</p>";
}
