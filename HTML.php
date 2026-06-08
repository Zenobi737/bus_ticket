<?php
// db_connect.php
$dsn = "mysql:host=127.0.0.1;dbname=bus_ticket_system;charset=utf8mb4"; [cite: 48]
$user = "root"; // Default XAMPP username [cite: 23, 48]
$pass = "";     // Default XAMPP password [cite: 48]

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on SQL errors [cite: 49]
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return records as associative arrays [cite: 16, 49]
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements [cite: 49]
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); [cite: 50]
} catch (\PDOException $e) {
    // Safe failure message to hide internal connection paths [cite: 47, 50]
    die("Database connection failed safely."); [cite: 50]
}
?>