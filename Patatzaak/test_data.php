<?php
// Include the database connection code
require_once 'database.php'; // Replace with the path to your connection file

try {
    // Test the connection by running a simple query
    $query = $pdo->query("SELECT 1");
    
    if ($query) {
        echo "Database connection is successful!";
    } else {
        echo "Database connection failed.";
    }
} catch (PDOException $e) {
    // Catch and display any connection errors
    echo "Error: " . $e->getMessage();
}
