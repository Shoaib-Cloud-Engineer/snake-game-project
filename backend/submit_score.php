<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Read DB config from environment variables
$host     = getenv('DB_HOST');
$user     = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

// Connect to RDS
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Read input
$username = $_POST['username'] ?? '';
$score    = $_POST['score'] ?? 0;

// Validate input
if (!empty($username) && is_numeric($score)) {
    $stmt = $conn->prepare("INSERT INTO scores (username, score) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $score);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Score submitted successfully!"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Insert failed"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid data"]);
}

$conn->close();
?>
