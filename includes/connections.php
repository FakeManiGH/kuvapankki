
<?php
// Path: includes/connection.php

// settings for local development
require_once 'settings.php';

// database connection settings
$dsn = "mysql:host=$db_server;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// database connection
try {
    $pdo = new PDO($dsn, $db_username, $db_password, $options);
} catch (PDOException $e) {
    die("Yhteysvirhe: " . $e->getMessage());
}