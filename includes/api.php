<?php

header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

function fetchItems($pdo)
{
    $sql = "SELECT * FROM `objects`";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);

        echo json_encode([
            'status' => 'success',
            'message' => 'Items fetched successfully',
            'data' => $result
        ]);
    } else {

        http_response_code(500);

        echo json_encode([
            'status' => 'error',
            'message' => 'Error fetching items'
        ]);
    }
}

function addItem($pdo)
{
    if (!isset($_POST['iname']) || !isset($_POST['idescription']) || !isset($_POST['ilatitude']) || !isset($_POST['ilongitude']) || !isset($_FILES['iimage'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'error_code' => 'bad_request',
            'message' => 'Missing required fields'
        ]);
        return;
    }

    $isDataValid = true;
    $isImageValid = true;

    $name = htmlspecialchars($_POST['iname']);
    $description = htmlspecialchars($_POST['idescription']);
    $latitude = htmlspecialchars($_POST['ilatitude']);
    $longitude = htmlspecialchars($_POST['ilongitude']);
    $image = $_FILES['iimage']['name'];
    $uniqueFilename = uniqid() . "_" . $image;

    $target_dir = '../uploads/';
    $target_file = $target_dir . basename($uniqueFilename);

    if ($_FILES['iimage']['size'] > 5242880) {
        $isImageValid = false;
    }

    if ($isImageValid && $isDataValid) {
        if (move_uploaded_file($_FILES['iimage']['tmp_name'], $target_file)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Image was uploaded'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Image failed to upload'
            ]);
        }
        $sql = "INSERT INTO objects (name, description, latitude, longitude, stored_filename, original_filename) VALUES (:name, :description, :latitude, :longitude, :stored_filename, :original_filename)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':stored_filename', $uniqueFilename);
        $stmt->bindParam(':original_filename', $image);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Record inserted"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "error" => "Error inserting record: " . $stmt->errorInfo()[2]
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data is invalid'
        ]);
        http_response_code(400);
    }
}

function updateItemStatus($pdo)
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

function removeTakenItems($pdo)
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
