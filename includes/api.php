<?php

header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

function fetchItems($pdo)
{
    $sql = "SELECT * FROM `objects`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result, JSON_PRETTY_PRINT);
}

function addItem($pdo)
{
    // Gather info from POST request
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);

    // File upload
    $filename = $_FILES['imageFile']['name'];
    $target_dir = 'uploads/';
    $target_file = $target_dir . basename($filename);

    // File upload
    if (isset($_FILES["imageFile"]) && $_FILES["imageFile"]["error"] == 0) {
        if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
            echo "The file " . basename($filename) . " has been uploaded successfully!";
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error uploading file."
            ]);
            return;
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No file uploaded or error in file upload."
        ]);
        return;
    }


    // Insert record into database
    $sql = "INSERT INTO objects (name, description, latitude, longitude, image_filename) VALUES (:name, :description, :latitude, :longitude, :image_filename)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':image_filename', $filename);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Record inserted"
        ]);
        http_response_code(201);
    } else {
        echo json_encode([
            "status" => "error",
            "error" => "Error inserting record: " . $stmt->errorInfo()[2]
        ]);
        http_response_code(500);
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
