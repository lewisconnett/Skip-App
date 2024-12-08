<?php

function createItem(PDO $pdo, string $name, string $description, float $latitude, float $longitude, string $image): ?int
{

    $sql = "INSERT INTO objects (name, description, latitude, longitude, image) 
            VALUES (:name, :description, :latitude, :longitude, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':image', $image, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return (int)$pdo->lastInsertId();
    }

    return null;
}

function updateItem(PDO $pdo, int $itemId): bool
{

    $sql = 'UPDATE objects SET status = "taken" WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $itemId, PDO::PARAM_INT);

    return $stmt->execute() && $stmt->rowCount() > 0;
}

function deleteItems(PDO $pdo): int
{
    $sql = 'DELETE FROM objects WHERE status = "taken"';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->rowCount();
}

function fetchItems(PDO $pdo): array
{
    $sql = "SELECT * FROM `objects`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
