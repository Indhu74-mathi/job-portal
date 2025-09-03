<?php
require_once __DIR__."/../config/db.php";

$user_id = $_POST['user_id'];
$record_id = $_POST['record_id'] ?? null;
$degree = $_POST['degree'];
$college = $_POST['college'];
$year_passed = $_POST['year_passed'];
$marks = $_POST['marks'];

if($record_id){ 
    // Update existing
    $stmt = $pdo->prepare("UPDATE education SET degree=?, college=?, year_passed=?, marks=? WHERE id=? AND user_id=?");
    $stmt->execute([$degree,$college,$year_passed,$marks,$record_id,$user_id]);
} else { 
    // Insert new
    $stmt = $pdo->prepare("INSERT INTO education (user_id, degree, college, year_passed, marks) VALUES (?,?,?,?,?)");
    $stmt->execute([$user_id,$degree,$college,$year_passed,$marks]);
}

// Return updated HTML
$stmt = $pdo->prepare("SELECT * FROM education WHERE user_id=? LIMIT 1");
$stmt->execute([$user_id]);
$edu = $stmt->fetch();

if($edu){
    echo "<p><b>{$edu['degree']}</b> - {$edu['college']} ({$edu['year_passed']}) | Marks: {$edu['marks']}</p>";
} else {
    echo "<p class='text-muted'>No education added yet.</p>";
}
