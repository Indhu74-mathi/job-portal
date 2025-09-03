<?php
require_once __DIR__."/../config/db.php";

$user_id = $_POST['user_id'];
$record_id = $_POST['record_id'] ?? null;
$achievement = $_POST['achievement'];
$description = $_POST['description'];

if($record_id){
    $stmt = $pdo->prepare("UPDATE accomplishments SET achievement=?, description=? WHERE id=? AND user_id=?");
    $stmt->execute([$achievement,$description,$record_id,$user_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO accomplishments (user_id, achievement, description) VALUES (?,?,?)");
    $stmt->execute([$user_id,$achievement,$description]);
}

// Return updated HTML
$stmt = $pdo->prepare("SELECT * FROM accomplishments WHERE user_id=? LIMIT 1");
$stmt->execute([$user_id]);
$acc = $stmt->fetch();

if($acc){
    echo "<p><b>{$acc['achievement']}:</b> {$acc['description']}</p>";
} else {
    echo "<p class='text-muted'>No accomplishments added yet.</p>";
}
