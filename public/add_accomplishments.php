<?php
require_once __DIR__ . "/../config/db.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_POST['user_id'];
    $achievement = $_POST['achievement'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO accomplishments (user_id, achievement, description) VALUES (?,?,?)");
    $stmt->execute([$user_id, $achievement, $description]);

    echo '<p><b>'.htmlspecialchars($achievement).':</b> '.htmlspecialchars($description).'</p>';
}
?>
