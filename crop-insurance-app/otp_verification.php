<?php
require_once __DIR__ . '/db.php';

/**
 * OTP_VERIFICATION TABLE HELPERS
 * Schema:
 *  otp_verification(otp_id, user_id, otp_code, expires_at, verified)
 */

function otp_get_all()
{
    global $conn;
    $sql = "SELECT otp_id, user_id, otp_code, expires_at, verified FROM otp_verification ORDER BY otp_id";
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

function otp_create($user_id, $otp_code, $expires_at, $verified = 0)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO otp_verification (user_id, otp_code, expires_at, verified) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('issi', $user_id, $otp_code, $expires_at, $verified);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert OTP: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


