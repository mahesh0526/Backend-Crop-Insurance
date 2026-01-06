<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/PHPMailer/src/Exception.php";
require __DIR__ . "/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/PHPMailer/src/SMTP.php";

// Get email
$email = trim($_POST['email'] ?? "");

// Validation
if ($email === "") {
    echo json_encode([
        "status" => "error",
        "message" => "Email required"
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email format"
    ]);
    exit;
}

// Check email exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email not registered"
    ]);
    exit;
}

// Generate OTP
$otp = random_int(100000, 999999);
$otp_expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));

// Save OTP
$update = $conn->prepare(
    "UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?"
);
$update->bind_param("iss", $otp, $otp_expiry, $email);
$update->execute();

// Send email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;

    // ✅ YOUR GMAIL CONFIG
    $mail->Username = "sigamahesh009@gmail.com";
    $mail->Password = "ytoupvgqbsrydizq"; // spaces removed

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom("sigamahesh009@gmail.com", "AgroSureAI");
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "AgroSureAI - Password Reset OTP";
    $mail->Body = "
        <h2>Password Reset OTP</h2>
        <h1>$otp</h1>
        <p>This OTP is valid for 10 minutes.</p>
        <p>If you did not request this, please ignore.</p>
        <br>
        <strong>AgroSureAI Team</strong>
    ";

    $mail->send();

    echo json_encode([
        "status" => "success",
        "message" => "OTP sent to email"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $mail->ErrorInfo
    ]);
}
?>
