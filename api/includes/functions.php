<?php

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
