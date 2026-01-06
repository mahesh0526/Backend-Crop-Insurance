<?php
include "db.php";

$full_name = trim($_POST['full_name'] ?? "");
$email     = trim($_POST['email'] ?? "");
$password  = trim($_POST['password'] ?? "");

if ($full_name == "" || $email == "" || $password == "") {
    echo json_encode(["status"=>"error","message"=>"All fields required"]);
    exit;
}

// Check if already registered
$check = $conn->prepare("SELECT id FROM users WHERE email=?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status"=>"error","message"=>"User already exists"]);
    exit;
}

$hashed = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password) VALUES (?,?,?)"
);
$stmt->bind_param("sss", $full_name, $email, $hashed);

if ($stmt->execute()) {
    echo json_encode(["status"=>"success","message"=>"Account created successfully"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Registration failed"]);
}
?>
