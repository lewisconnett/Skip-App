<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== "PATCH") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

$data = json_decode(file_get_contents("php://input"), true);
$itemId = $data['item_id'] ?? null;

if ($itemId) {
    $sql = 'UPDATE objects SET status = "taken" WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            sendResponse(200, 'success', 'Item status updated successfully');
        } else {
            sendResponse(404, 'error', 'Item not found');
        }
    } else {
        sendResponse(500, 'error', 'Error updating item');
    }
} else {
    sendResponse(400, 'error', 'Item ID is required');
}
