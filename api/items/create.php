<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';
include '../includes/validation.php';
include '../includes/utils/fileUpload.php';
include '../includes/services/ItemService.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    sendResponse(405, 'error', 'Method No   t Allowed');
}

if (!isset($_POST['iname']) || !isset($_POST['idescription']) || !isset($_POST['ilatitude']) || !isset($_POST['ilongitude']) || !isset($_FILES['iimage'])) {
    sendResponse(400, 'error', 'Missing required fields');
    return;
}

$name = htmlspecialchars($_POST['iname']);
$description = htmlspecialchars($_POST['idescription']);
$latitude = htmlspecialchars($_POST['ilatitude']);
$longitude = htmlspecialchars($_POST['ilongitude']);
$image = $_FILES['iimage'];
$uniqueFilename = uniqid() . "_" . $image['name'];

$target_dir = '../../uploads/';

// Form Validation

$validateImageResult = validateImage($image);
if ($validateImageResult['error'] === true) {
    sendResponse(422, 'error', $validateImageResult['message']);
    exit;
}

$validateFieldsResults = validateFields($name, $description, $latitude, $longitude);
if ($validateFieldsResults['error'] === true) {
    sendResponse(400, 'error', $validateFieldsResults['message']);
    exit;
}

// Upload Image

$uploadResult = uploadFile($image, $target_dir, $uniqueFilename);
if ($uploadResult['error'] === true) {
    sendResponse(422, 'error', $uploadResult['message']);
    exit;
}


try {
    $newItemId = createItem($pdo, $name, $description, $latitude, $longitude, $uniqueFilename);

    if ($newItemId) {
        $newItem = [
            'id' => $newItemId,
            'name' => $name,
            'description' => $description,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image' => $uniqueFilename
        ];

        sendResponse(201, 'success', 'Item successfully added', $newItem);
    } else {
        sendResponse(500, 'error', 'Error inserting record');
    }
} catch (PDOException $e) {
    sendResponse(500, 'error', 'Error inserting record' . $e->getMessage());
}
