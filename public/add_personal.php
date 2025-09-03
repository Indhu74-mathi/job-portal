<?php
require_once __DIR__ . "/../config/db.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_POST['user_id'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("INSERT INTO personal_details (user_id, dob, gender, marital_status, address) VALUES (?,?,?,?,?)");
    $stmt->execute([$user_id, $dob, $gender, $marital_status, $address]);

    echo '<p><b>DOB:</b> '.htmlspecialchars($dob).'</p>
          <p><b>Gender:</b> '.htmlspecialchars($gender).'</p>
          <p><b>Marital Status:</b> '.htmlspecialchars($marital_status).'</p>
          <p><b>Address:</b> '.htmlspecialchars($address).'</p>';
}
?>
