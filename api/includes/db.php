<?php

$servername = "localhost";
$dbname = "lc1453_db_skipapp";
$username = "lc1453_api_user";
$password = "ISWxc}p;6(ML";

// Initiates database connection

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection to the database has encountered a failure: " . $e->getMessage();
}
