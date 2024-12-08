<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';
include '../includes/services/ItemService.php';

if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

try {
    $deletedCount = deleteItems($pdo);
    if ($deletedCount === 0) {
        sendResponse(200, 'success', 'No items were deleted');
        exit;
    }
    sendResponse(200, 'success', 'Removed items successfully', ['deleted_count' => $deletedCount]);
} catch (PDOException $e) {
    sendResponse(500, 'error', 'Error removing items: ' . $e->getMessage());
}
