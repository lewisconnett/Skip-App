<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';

$sql = 'DELETE FROM objects WHERE status = "taken"';
$stmt = $pdo->prepare($sql);

if ($stmt->execute()) {
    $deletedCount = $stmt->rowCount();
    if ($deletedCount > 0) {
        sendResponse(200, 'success', 'Removed items successfully', $deletedCount);
    } else {
        sendResponse(204, 'success', 'No rows deleted');
    }
} else {
    sendResponse(500, 'error', 'Error removing items');
}
