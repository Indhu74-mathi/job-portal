<?php
require_once __DIR__ . "/../config/db.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_POST['user_id'];
    $degree = $_POST['degree'];
    $college = $_POST['college'];
    $year_passed = $_POST['year_passed'];
    $marks = $_POST['marks'];

    $stmt = $pdo->prepare("INSERT INTO education (user_id, degree, college, year_passed, marks) VALUES (?,?,?,?,?)");
    $stmt->execute([$user_id,$degree,$college,$year_passed,$marks]);

    echo "<p><b>".htmlspecialchars($degree)."</b> - ".htmlspecialchars($college)." (".htmlspecialchars($year_passed).") | Marks: ".htmlspecialchars($marks)."</p>";
}
?>
