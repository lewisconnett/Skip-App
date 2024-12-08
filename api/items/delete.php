<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== "DELETE") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

$sql = 'DELETE FROM objects WHERE status = "taken"';
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute();
    $deletedCount = $stmt->rowCount();
    if ($deletedCount > 0) {
        sendResponse(200, 'success', 'Removed items successfully', ['deleted_count' => $deletedCount]);
    } else {
        sendResponse(200, 'success', 'No items were deleted');
    }
} catch (PDOException $e) {
    sendResponse(500, 'error', 'Error removing items: ' . $e->getMessage());
}
