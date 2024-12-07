<?php

// TODO: Declare types

// TODO: Adjust image file storage - 1. Generate unique file name, 2. Store in image_file column

header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

function sendResponse(int $statusCode, string $status, string $message, $data = null): void
{
    http_response_code($statusCode);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
}

function validateImage(array $image): bool
{

    $maxFileSize = 5242880;
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    $fileType = mime_content_type($image['tmp_name']);
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);

    if ($image['size'] >= $maxFileSize) {
        return false;
    }

    if (!(in_array($fileType, $allowedMimeTypes))) {
        return false;
    }

    if (!(in_array($fileExtension, $allowedExtensions))) {
        return false;
    }

    return true;
}



function fetchItems(PDO $pdo)
{
    $sql = "SELECT * FROM `objects`";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo sendResponse(200, 'success', 'Items fetched successfully', $result);
    } else {

        echo sendResponse(500, 'error', 'Error fetching items');
    }
}

function addItem(PDO $pdo)
{
    if (!isset($_POST['iname']) || !isset($_POST['idescription']) || !isset($_POST['ilatitude']) || !isset($_POST['ilongitude']) || !isset($_FILES['iimage'])) {
        echo sendResponse(400, 'error', 'Missing required fields');
        return;
    }

    $isDataValid = true;

    $name = htmlspecialchars($_POST['iname']);
    $description = htmlspecialchars($_POST['idescription']);
    $latitude = htmlspecialchars($_POST['ilatitude']);
    $longitude = htmlspecialchars($_POST['ilongitude']);
    $image = $_FILES['iimage'];
    $uniqueFilename = uniqid() . "_" . $image['name'];

    $target_dir = '../uploads/';
    $target_file = $target_dir . basename($uniqueFilename);

    if (validateImage($image) && $isDataValid) {
        if (!(move_uploaded_file($image['tmp_name'], $target_file))) {

            echo sendResponse(422, 'error', 'Image could not be uploaded');
        } else {

            $sql = "INSERT INTO objects (name, description, latitude, longitude, image) VALUES (:name, :description, :latitude, :longitude, :image)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
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

                echo sendResponse(201, 'success', 'Item successfully added', $newItem);
            } else {
                echo sendResponse(500, 'error', 'Error inserting record' . $stmt->errorInfo()[2]);
            }
        }
    } else {
        echo sendResponse(400, 'error', 'Data is invalid');
    }
}

function updateItemStatus(PDO $pdo)
{
    $data = json_decode(file_get_contents("php://input"), true);
    $itemId = $data['item_id'] ?? $_GET['item_id'] ?? null;

    if ($itemId) {
        $sql = 'UPDATE objects SET status = "taken" WHERE id = :name';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $itemId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Record updated successfully'
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error',
                    'error_code' => 'not_found',
                    'message' => 'No record was updated'
                ]);
            }
        } else {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'error_code' => 'internal_server_error',
                'message' => 'An error occured while updating the item'
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error_code' => 'bad_request',
            'message' => 'item_Id is required'
        ]);
    }
}

function removeTakenItems(PDO $pdo)
{
    $sql = 'DELETE FROM objects WHERE status = "taken"';
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode([
                'status' => 'success',
                'message' => 'Removed items',
                'deleted_count' => $stmt->rowCount()
            ]);
        } else {
            http_response_code(204);
            echo json_encode([
                'status' => 'success',
                'message' => 'No rows were deleted'
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'error_code' => 'internal_server_error',
            'message' => 'An error occured while removing items'
        ]);
    }
}

// Route the request based on the HTTP method
switch ($method) {
    case "GET":
        fetchItems($pdo);
        break;
    case "POST":
        addItem($pdo);
        break;
    case "PUT":
        updateItemStatus($pdo);
        break;
    case "DELETE":
        removeTakenItems($pdo);
        break;
    default:
        echo json_encode([
            "status" => "error",
            "error" => "Unsupported HTTP method"
        ]);
        http_response_code(405);
        break;
}
