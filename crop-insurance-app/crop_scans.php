<?php
require_once __DIR__ . '/db.php';

/**
 * CROP_SCANS TABLE HELPERS
 * Schema:
 *  crop_scans(scan_id, crop_id, image_url, diagnosis_result, confidence_score, scanned_at)
 */

function scans_get_all()
{
    global $conn;
    $sql = "SELECT scan_id, crop_id, image_url, diagnosis_result, confidence_score, scanned_at FROM crop_scans ORDER BY scan_id";
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

function scan_create($crop_id, $image_url, $diagnosis_result, $confidence_score)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO crop_scans (crop_id, image_url, diagnosis_result, confidence_score) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('issd', $crop_id, $image_url, $diagnosis_result, $confidence_score);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert crop scan: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


