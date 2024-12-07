<?php

header("Content-Type: application/json");

include '../includes/functions.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== "GET") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

$sql = "SELECT * FROM `objects`";
$stmt = $pdo->prepare($sql);

if ($stmt->execute()) {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(200, 'success', 'Items fetched successfully', $result);
} else {

    sendResponse(500, 'error', 'Error fetching items');
}
