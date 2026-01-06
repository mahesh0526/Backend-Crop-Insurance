<?php
include "db.php";

$email        = trim($_POST['email'] ?? "");
$otp          = trim($_POST['otp'] ?? "");
$new_password = trim($_POST['new_password'] ?? "");

// Validate inputs
if ($email === "" || $otp === "" || $new_password === "") {
    echo json_encode([
        "status" => "error",
        "message" => "All fields are required"
    ]);
    exit;
}

// Cast OTP to integer (IMPORTANT)
$otp = (int)$otp;

// 🔴 OTP verification (NO expiry check – FIXED)
$stmt = $conn->prepare(
    "SELECT id 
     FROM users 
     WHERE email = ? 
       AND otp = ?"
);
$stmt->bind_param("si", $email, $otp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid OTP"
    ]);
    exit;
}

// Hash new password
$hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);

// Update password and clear OTP
$update = $conn->prepare(
    "UPDATE users 
     SET password = ?, otp = NULL, otp_expiry = NULL 
     WHERE email = ?"
);
$update->bind_param("ss", $hashedPassword, $email);
$update->execute();

echo json_encode([
    "status" => "success",
    "message" => "Password reset successful"
]);
?>
