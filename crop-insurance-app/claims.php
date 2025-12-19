<?php
require_once __DIR__ . '/db.php';

/**
 * CLAIMS TABLE HELPERS
 * Schema:
 *  claims(claim_id, policy_id, crop_id, claim_status, reason, created_at)
 */

function claims_get_all()
{
    global $conn;
    $sql = "SELECT claim_id, policy_id, crop_id, claim_status, reason, created_at FROM claims ORDER BY claim_id";
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

function claim_create($policy_id, $crop_id, $claim_status, $reason)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO claims (policy_id, crop_id, claim_status, reason) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('iiss', $policy_id, $crop_id, $claim_status, $reason);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert claim: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


