<?php
require_once __DIR__ . '/users.php';

header('Content-Type: application/json');

try {
    // Insert a demo user each call; adjust as needed
    $newId = users_create('9998887776', null, 1);

    $all = users_get_all();

    echo json_encode([
        'inserted_user_id' => $newId,
        'users' => $all
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}


