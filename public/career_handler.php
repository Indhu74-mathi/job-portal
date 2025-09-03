<?php
require_once __DIR__."/../config/db.php";

$user_id = $_POST['user_id'];
$record_id = $_POST['record_id'] ?? null;
$desired_role = $_POST['desired_role'];
$preferred_location = $_POST['preferred_location'];
$expected_ctc = $_POST['expected_ctc'];

if($record_id){
    $stmt = $pdo->prepare("UPDATE career_profile SET desired_role=?, preferred_location=?, expected_ctc=? WHERE id=? AND user_id=?");
    $stmt->execute([$desired_role,$preferred_location,$expected_ctc,$record_id,$user_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO career_profile (user_id, desired_role, preferred_location, expected_ctc) VALUES (?,?,?,?)");
    $stmt->execute([$user_id,$desired_role,$preferred_location,$expected_ctc]);
}

// Return updated HTML
$stmt = $pdo->prepare("SELECT * FROM career_profile WHERE user_id=? LIMIT 1");
$stmt->execute([$user_id]);
$career = $stmt->fetch();

if($career){
    echo "<p><b>Desired Role:</b> {$career['desired_role']}</p>";
    echo "<p><b>Preferred Location:</b> {$career['preferred_location']}</p>";
    echo "<p><b>Expected CTC:</b> {$career['expected_ctc']}</p>";
} else {
    echo "<p class='text-muted'>No career profile added yet.</p>";
}
