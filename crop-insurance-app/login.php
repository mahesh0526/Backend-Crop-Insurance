<?php
include "db.php";

$email    = trim($_POST['email'] ?? "");
$password = trim($_POST['password'] ?? "");

if ($email == "" || $password == "") {
    echo json_encode(["status"=>"error","message"=>"Email and password required"]);
    exit;
}

$stmt = $conn->prepare(
    "SELECT id, full_name, password FROM users WHERE email=?"
);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["status"=>"error","message"=>"User not registered"]);
    exit;
}

$user = $result->fetch_assoc();

if (password_verify($password, $user['password'])) {
    echo json_encode([
        "status"=>"success",
        "message"=>"Login successful",
        "user_id"=>$user['id'],
        "full_name"=>$user['full_name']
    ]);
} else {
    echo json_encode(["status"=>"error","message"=>"Incorrect password"]);
}
?>
