<?php

$servername = "localhost";
$dbname = "lc1453_db_skipapp";
$username = "lc1453_api_user";
$password = "ISWxc}p;6(ML";

// Create connection using PDO

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection Successful";
} catch (PDOException $e) {
    echo "Connection Failed: " . $e->getMessage();
}
