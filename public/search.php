<?php
require_once __DIR__ . '/../config/db.php';
session_start();

$q = trim($_GET['q'] ?? '');

if ($q === '') {
    echo "<p>Please enter a search term.</p>";
    exit;
}

$sql = "
    SELECT id, title, company, location, skills, created_at 
    FROM jobs
    WHERE title LIKE :search
       OR company LIKE :search
       OR location LIKE :search
       OR skills LIKE :search
    ORDER BY created_at DESC
";

$stmt = $pdo->prepare($sql);

$searchTerm = "%" . $q . "%";
$stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);

$stmt->execute();

$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Search Results for <?= htmlspecialchars($q) ?></title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9fafb; margin: 0; padding: 0; }
    .container { max-width: 800px; margin: 40px auto; background: #fff; padding: 20px; border-radius: 8px; }
    h2 { margin-bottom: 20px; }
    .job-card {
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-bottom: 15px;
        background: #fefefe;
    }
    .job-card h3 { margin: 0 0 8px; }
    .job-card p { margin: 4px 0; color: #555; }
    .view-btn {
        display: inline-block;
        padding: 8px 12px;
        background: #4f46e5;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }
    .no-results {
        text-align: center;
        padding: 20px;
        color: #666;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Search Results for "<?= htmlspecialchars($q) ?>"</h2>

    <?php if (count($jobs) > 0): ?>
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h3><?= htmlspecialchars($job['title']) ?></h3>
                <p><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                <p><?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...</p>
                <a class="view-btn" href="job.php?id=<?= (int)$job['id'] ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-results">
            No jobs found for "<strong><?= htmlspecialchars($q) ?></strong>".
        </div>
    <?php endif; ?>
  </div>
</body>
</html>
