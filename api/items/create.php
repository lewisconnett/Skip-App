<?php

header("Content-Type: application/json");
include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    sendResponse(405, 'error', 'Method Not Allowed');
}

if (!isset($_POST['iname']) || !isset($_POST['idescription']) || !isset($_POST['ilatitude']) || !isset($_POST['ilongitude']) || !isset($_FILES['iimage'])) {
    sendResponse(400, 'error', 'Missing required fields');
    return;
}

$isDataValid = true;

$name = htmlspecialchars($_POST['iname']);
$description = htmlspecialchars($_POST['idescription']);
$latitude = htmlspecialchars($_POST['ilatitude']);
$longitude = htmlspecialchars($_POST['ilongitude']);
$image = $_FILES['iimage'];
$uniqueFilename = uniqid() . "_" . $image['name'];

$target_dir = '../../uploads/';
$target_file = $target_dir . basename($uniqueFilename);

if (validateImage($image) && $isDataValid) {
    if (!(move_uploaded_file($image['tmp_name'], $target_file))) {

        sendResponse(422, 'error', 'Image could not be uploaded');
    } else {

        $sql = "INSERT INTO objects (name, description, latitude, longitude, image) VALUES (:name, :description, :latitude, :longitude, :image)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':image', $uniqueFilename);

        if ($stmt->execute()) {

            $newItem = [
                'id' => $pdo->lastInsertId(),
                'name' => $name,
                'description' => $description,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'image' => $uniqueFilename
            ];

            sendResponse(201, 'success', 'Item successfully added', $newItem);
        } else {
            sendResponse(500, 'error', 'Error inserting record' . $stmt->errorInfo()[2]);
        }
    }
} else {
    sendResponse(400, 'error', 'Data is invalid');
}
