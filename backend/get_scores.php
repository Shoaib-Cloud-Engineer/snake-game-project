<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

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

// Query top 10 scores
$result = $conn->query("SELECT username, score FROM scores ORDER BY score DESC LIMIT 10");

$scores = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
    echo json_encode($scores);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Query failed"]);
}

$conn->close();
?>
