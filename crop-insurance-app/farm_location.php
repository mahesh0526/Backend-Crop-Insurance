<?php
require_once __DIR__ . '/db.php';

/**
 * FARM_LOCATION TABLE HELPERS
 * Schema:
 *  farm_location(location_id, farmer_id, latitude, longitude, district, state)
 */

function locations_get_all()
{
    global $conn;
    $sql = "SELECT location_id, farmer_id, latitude, longitude, district, state FROM farm_location ORDER BY location_id";
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

function location_create($farmer_id, $latitude, $longitude, $district, $state)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO farm_location (farmer_id, latitude, longitude, district, state) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('iddss', $farmer_id, $latitude, $longitude, $district, $state);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert farm location: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


