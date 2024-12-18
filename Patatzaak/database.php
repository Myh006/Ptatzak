<?php
// Database configuration
define('DB_HOST', '127.0.0.1'); // Use localhost or 127.0.0.1
define('DB_NAME', 'patatzaak');
define('DB_USER', 'root');
define('DB_PASS', ''); // Leave empty for no password

try {
    // Create a new PDO instance with secure options
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch associative arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
        ]
    );
} catch (PDOException $e) {
    // Log the error securely and exit (never display details in production)
    error_log("Database connection error: " . $e->getMessage());
    die("A database connection error occurred. Please try again later.");
}
