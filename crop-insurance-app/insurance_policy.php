<?php
require_once __DIR__ . '/db.php';

/**
 * INSURANCE_POLICY TABLE HELPERS
 * Schema:
 *  insurance_policy(policy_id, farmer_id, policy_number, provider_name, policy_status, start_date, end_date)
 */

function policies_get_all()
{
    global $conn;
    $sql = "SELECT policy_id, farmer_id, policy_number, provider_name, policy_status, start_date, end_date FROM insurance_policy ORDER BY policy_id";
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

function policy_create($farmer_id, $policy_number, $provider_name, $policy_status, $start_date, $end_date)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO insurance_policy (farmer_id, policy_number, provider_name, policy_status, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('isssss', $farmer_id, $policy_number, $provider_name, $policy_status, $start_date, $end_date);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert insurance policy: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


