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
