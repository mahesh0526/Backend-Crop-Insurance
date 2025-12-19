<?php
require_once __DIR__ . '/db.php';

/**
 * USERS TABLE HELPERS
 * Schema:
 *  users(user_id, mobile_number, google_id, is_verified, created_at)
 */

function users_get_all()
{
    global $conn;
    $sql = "SELECT user_id, mobile_number, google_id, is_verified, created_at FROM users ORDER BY user_id";
    $result = $conn->query($sql);

    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
    }
    return $rows;
}

function users_create($mobile_number, $google_id = null, $is_verified = 0)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO users (mobile_number, google_id, is_verified) VALUES (?, ?, ?)"
    );
    $stmt->bind_param('ssi', $mobile_number, $google_id, $is_verified);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert user: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


