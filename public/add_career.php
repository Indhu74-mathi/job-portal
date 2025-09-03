<?php
require_once __DIR__ . "/../config/db.php";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $user_id = $_POST['user_id'];
    $desired_role = $_POST['desired_role'];
    $preferred_location = $_POST['preferred_location'];
    $expected_ctc = $_POST['expected_ctc'];

    $stmt = $pdo->prepare("INSERT INTO career_profile (user_id, desired_role, preferred_location, expected_ctc) VALUES (?,?,?,?)");
    $stmt->execute([$user_id, $desired_role, $preferred_location, $expected_ctc]);

    echo '<p><b>Desired Role:</b> '.htmlspecialchars($desired_role).'</p>
          <p><b>Preferred Location:</b> '.htmlspecialchars($preferred_location).'</p>
          <p><b>Expected CTC:</b> '.htmlspecialchars($expected_ctc).'</p>';
}
?>
