<?php
session_start();
$conn = new mysqli(
    "localhost",
    "root",
    "",
    "neptuneiq"
);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die("No data received");
}

$username = $_SESSION['user'] ?? "Guest";
$sql = "INSERT INTO predictions 
(username, ph, turbidity, dissolved_oxygen, temperature, nitrate, tds, coliform, risk_level, advice)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sdddddddss",
    $username,
    $data['ph'],
    $data['turbidity'],
    $data['dissolved_oxygen'],
    $data['temperature'],
    $data['nitrate'],
    $data['tds'],
    $data['coliform'],
    $data['risk_level'],
    $data['advice']
);

if ($stmt->execute()) {
    echo "Prediction saved successfully";
} else {
    echo "Insert failed: " . $stmt->error;
}
$stmt->close();
$conn->close();

?>