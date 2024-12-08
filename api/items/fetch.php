<?php

header("Content-Type: application/json");

include '../includes/functions.php';
include '../includes/db.php';
include '../includes/services/ItemService.php';

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

try {
    $items = fetchItems($pdo);
    if (count($items) === 0) {
        sendResponse(200, 'success', 'No items were fetched');
        exit;
    }
    sendResponse(200, 'success', 'Items fetched successfully', $items);
} catch (PDOException $e) {
    sendResponse(500, 'error', 'Error fetching items');
}
