<?php
// Database connection for AgroSure AI - Crop Insurance app

// XAMPP MySQL settings
$DB_HOST = '127.0.0.1';
$DB_PORT = 3307;
$DB_NAME = 'agrosure_ai';
$DB_USER = 'root';
$DB_PASS = '';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');


