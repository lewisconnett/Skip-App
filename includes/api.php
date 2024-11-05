<?php

header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$json_file = '../dummy_data.json';

function fetchItems($pdo)
{
    $sql = "SELECT * FROM `objects`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result, JSON_PRETTY_PRINT);
    return json_encode($result);
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
        echo "Record inserted successfully.";
    } else {
        echo "Error inserting record: " . $stmt->errorInfo()[2];
    }
}

switch ($method) {
    case "GET":
        fetchItems($pdo);
        break;
    case "POST":
        $json_input = file_get_contents("php://input");
        $data = json_decode($json_input, true);
        if ($data) {
            addItem($pdo, $data);
        } else {
            echo json_encode(['error' => 'Invalid JSON']);
        }
        break;
}
