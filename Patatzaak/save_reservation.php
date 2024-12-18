<?php
// Include the database connection
require_once 'database.php'; // Ensure the path is correct

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $naam = $_POST['naam'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $date_time = $_POST['date_time'];

    try {
        // Insert the reservation data into the database
        $stmt = $pdo->prepare("INSERT INTO orders (order_type, naam, telefoonnummer, date_time, payment_method) 
                               VALUES ('reserve', ?, ?, ?, 'not_applicable')");
        $stmt->execute([$naam, $telefoonnummer, $date_time]);

        // Success message or redirect
        echo "Reservation saved successfully!";
        header('Location: index.html'); // Replace with your success page
    } catch (PDOException $e) {
        // Handle errors gracefully
        error_log("Database error: " . $e->getMessage());
        die("An error occurred while saving your reservation. Please try again later.");
    }
}
?>
