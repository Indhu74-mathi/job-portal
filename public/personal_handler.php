<?php
require_once __DIR__."/../config/db.php";

$user_id = $_POST['user_id'];
$record_id = $_POST['record_id'] ?? null;
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$marital_status = $_POST['marital_status'];
$address = $_POST['address'];

if($record_id){
    $stmt = $pdo->prepare("UPDATE personal_details SET dob=?, gender=?, marital_status=?, address=? WHERE id=? AND user_id=?");
    $stmt->execute([$dob,$gender,$marital_status,$address,$record_id,$user_id]);
} else {
    $stmt = $pdo->prepare("INSERT INTO personal_details (user_id,dob,gender,marital_status,address) VALUES (?,?,?,?,?)");
    $stmt->execute([$user_id,$dob,$gender,$marital_status,$address]);
}

// Return updated HTML
$stmt = $pdo->prepare("SELECT * FROM personal_details WHERE user_id=? LIMIT 1");
$stmt->execute([$user_id]);
$personal = $stmt->fetch();

if($personal){
    echo "<p><b>DOB:</b> {$personal['dob']}</p>";
    echo "<p><b>Gender:</b> {$personal['gender']}</p>";
    echo "<p><b>Marital Status:</b> {$personal['marital_status']}</p>";
    echo "<p><b>Address:</b> {$personal['address']}</p>";
} else {
    echo "<p class='text-muted'>No personal details added yet.</p>";
}
