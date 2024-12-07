<?php

function validateImage(array $image): array
{

    $maxFileSize = 5242880;
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];

    $fileType = mime_content_type($image['tmp_name']);
    $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);

    if ($image['size'] >= $maxFileSize) {
        return [
            'error' => true,
            'message' => 'Image size is too big'
        ];
    }

    if (!(in_array($fileType, $allowedMimeTypes, true))) {
        return [
            'error' => true,
            'message' => 'Image is wrong MIME type'
        ];
    }

    if (!(in_array($fileExtension, $allowedExtensions, true))) {
        return [
            'error' => true,
            'message' => 'Image extension is wrong type'
        ];
    }

    return [
        'error' => false,
        'message' => 'Image is valid'
    ];
}

function validateFields(string $name, string $description, $latitude, $longitude): array
{
    if (empty(trim($name))) {
        return [
            'error' => true,
            'message' => 'Name is empty'
        ];
    }

    if (empty(trim($description))) {
        return [
            'error' => true,
            'message' => 'Description is empty'
        ];
    }

    if (mb_strlen($name) > 100) {
        return [
            'error' => true,
            'message' => 'Name exceeds 100 characters'
        ];
    }

    if (mb_strlen($description) > 255) {
        return [
            'error' => true,
            'message' => 'Description exceeds 255 characters'
        ];
    }

    $latitudeVal = filter_var($latitude, FILTER_VALIDATE_FLOAT);
    $longitudeVal = filter_var($longitude, FILTER_VALIDATE_FLOAT);

    if ($latitudeVal === false) {
        return [
            'error' => true,
            'message' => 'Invalid latitude value'
        ];
    }

    if ($longitudeVal === false) {
        return [
            'error' => true,
            'message' => 'Invalid longitude value'
        ];
    }

    return [
        'error' => false,
        'message' => 'Form fields are valid'
    ];
}
