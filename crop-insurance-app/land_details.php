<?php
require_once __DIR__ . '/db.php';

/**
 * LAND_DETAILS TABLE HELPERS
 * Schema:
 *  land_details(land_id, farmer_id, land_area, soil_type, irrigation_type)
 */

function lands_get_all()
{
    global $conn;
    $sql = "SELECT land_id, farmer_id, land_area, soil_type, irrigation_type FROM land_details ORDER BY land_id";
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

function land_create($farmer_id, $land_area, $soil_type, $irrigation_type)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO land_details (farmer_id, land_area, soil_type, irrigation_type) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('idss', $farmer_id, $land_area, $soil_type, $irrigation_type);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert land details: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


