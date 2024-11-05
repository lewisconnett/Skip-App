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

function addItem($pdo, $data)
{
    $sql = "INSERT INTO objects (name, description, latitude, longitude) VALUES (:name, :description, :latitude, :longitude)";
    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':latitude', $data['latitude']);
    $stmt->bindParam(':longitude', $data['longitude']);

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

switch ($method) {
    case "GET":
        fetchItems($pdo);
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data) {
            addItem($pdo, $data);
        } else {
            echo json_encode([
                "status" => "error",
                "error" => "Invalid JSON"
            ]);
            http_response_code(400); 
        }
        break;
}
