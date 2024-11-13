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

        $data = [
            'status' => 'success',
            'message' => 'Items fetched successfully',
            'data' => $result
        ];

        echo json_encode($data);
    } else {

        http_response_code(500);

        $data = [
            'status' => 'error',
            'message' => 'Error fetching items'
        ];

        echo json_encode($data);
    }
}

function addItem($pdo)
{

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'status' => 'error',
            'message' => 'Method Not Allowed'
        ]);
        return;
    }

    // Check for required fields
    if (!isset($_POST['iname']) || !isset($_POST['idescription']) || !isset($_POST['ilatitude']) || !isset($_POST['ilongitude']) || !isset($_FILES['iimage'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
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

// Route the request based on the HTTP method
switch ($method) {
    case "GET":
        fetchItems($pdo);
        break;
    case "POST":
        addItem($pdo);
        break;
    default:
        echo json_encode([
            "status" => "error",
            "error" => "Unsupported HTTP method"
        ]);
        http_response_code(405);
        break;
}
