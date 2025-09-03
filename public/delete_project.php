<?php
require_once __DIR__ . '/../config/db.php';

// Set response header
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(["success" => true, "message" => "Project deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Project not found."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid ID."]);
}
