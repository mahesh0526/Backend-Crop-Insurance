<?php
require_once __DIR__ . '/db.php';

/**
 * FARMER_PROFILE TABLE HELPERS
 * Schema:
 *  farmer_profile(farmer_id, user_id, full_name, age, aadhaar_number)
 */

function farmers_get_all()
{
    global $conn;
    $sql = "SELECT farmer_id, user_id, full_name, age, aadhaar_number FROM farmer_profile ORDER BY farmer_id";
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

function farmer_create($user_id, $full_name, $age, $aadhaar_number)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO farmer_profile (user_id, full_name, age, aadhaar_number) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('isis', $user_id, $full_name, $age, $aadhaar_number);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert farmer profile: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


