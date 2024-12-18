<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // No password as per your request
$dbname = "patatzaak";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Retrieve the JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Prepare SQL statement
$sql = "INSERT INTO orders (order_type, naam, telefoonnummer, voornaam, achternaam, email, address, payment_method, omschrijving, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);

// Bind parameters to the SQL query
$stmt->bind_param(
    "sssssssss",
    $data['order_type'],
    $data['naam'],
    $data['telefoonnummer'],
    $data['voornaam'],
    $data['achternaam'],
    $data['email'],
    $data['address'],
    $data['payment_method'],
    $data['omschrijving']
);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Order inserted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to insert order"]);
}

// Close the connection
$stmt->close();
$conn->close();
?>
