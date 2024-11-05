<?php

$method = $_SERVER['REQUEST_METHOD'];

if ($method == "POST") {
    $name = htmlspecialchars($_POST['iname']);
    $description = htmlspecialchars($_POST['idescription']);
    $latitude = htmlspecialchars($_POST['ilatitude']);
    $longitude = htmlspecialchars($_POST['ilongitude']);

    $itemData = [
        'name' => $name,
        'description' => $description,
        'latitude' => $latitude,
        'longitude' => $longitude
    ];

    $json_data = json_encode($itemData);

    $api_url = 'https://lc1453.brighton.domains/skipapp/includes/api.php';

    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data),
    ]);

    $response = curl_exec($ch);
    
    

    if ($response === false) {
        // Capture and display detailed cURL error information
        echo 'cURL Error: ' . curl_error($ch);
    } else {
        // If there was a response, get the HTTP status code
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Display the response and HTTP status code for further debugging
        echo 'Response from API: ' . $response;
        echo '<br>HTTP Status Code: ' . $httpCode;

        // Optionally, handle specific HTTP status codes
        if ($httpCode !== 200) {
            echo '<br>Unexpected HTTP status code, may indicate an error.';
        }
    }

    curl_close($ch);

}
