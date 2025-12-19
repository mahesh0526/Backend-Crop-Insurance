<?php
require_once __DIR__ . '/db.php';

/**
 * CROP_DETAILS TABLE HELPERS
 * Schema:
 *  crop_details(crop_id, farmer_id, crop_name, season, sowing_date, status)
 */

function crops_get_all()
{
    global $conn;
    $sql = "SELECT crop_id, farmer_id, crop_name, season, sowing_date, status FROM crop_details ORDER BY crop_id";
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

function crop_create($farmer_id, $crop_name, $season, $sowing_date, $status = 'Active')
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO crop_details (farmer_id, crop_name, season, sowing_date, status) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('issss', $farmer_id, $crop_name, $season, $sowing_date, $status);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert crop details: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


