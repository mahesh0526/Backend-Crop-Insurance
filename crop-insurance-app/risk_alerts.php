<?php
require_once __DIR__ . '/db.php';

/**
 * RISK_ALERTS TABLE HELPERS
 * Schema:
 *  risk_alerts(alert_id, farmer_id, alert_type, description, risk_level, created_at)
 */

function alerts_get_all()
{
    global $conn;
    $sql = "SELECT alert_id, farmer_id, alert_type, description, risk_level, created_at FROM risk_alerts ORDER BY alert_id";
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

function alert_create($farmer_id, $alert_type, $description, $risk_level)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO risk_alerts (farmer_id, alert_type, description, risk_level) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('isss', $farmer_id, $alert_type, $description, $risk_level);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert risk alert: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


