<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';
include '../includes/services/ItemService.php';

if ($_SERVER['REQUEST_METHOD'] !== "PATCH") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

$data = json_decode(file_get_contents("php://input"), true);
$itemId = $data['item_id'] ?? null;


$itemId = filter_var($data['item_id'] ?? null, FILTER_VALIDATE_INT);
if (!$itemId) {
    sendResponse(400, 'error', 'Item ID is required');
    exit;
}

try {

    if (!updateItem($pdo, $itemId)) {
        sendResponse(404, 'error', 'Item not found');
        exit;
    }
    sendResponse(200, 'success', 'Item status updated successfully');
} catch (PDOException $e) {
    sendResponse(500, 'error', 'There was a problem updating the record: ' . $e->getMessage());
}
