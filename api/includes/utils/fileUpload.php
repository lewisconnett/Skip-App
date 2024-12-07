<?php


function uploadFile(array $image, string $target_dir, string $uniqueFilename): array
{
    $target_file = $target_dir . basename($uniqueFilename);
    if (!move_uploaded_file($image['tmp_name'], $target_file)) {
        return [
            'error' => true,
            'message' => 'Image could not be uploaded'
        ];
    }

    return [
        'error' => false,
        'message' => 'File uploaded successfully',
        'filename' => $uniqueFilename
    ];
}
