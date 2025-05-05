<?php
$db_host = '127.0.0.1'; // Changed from 'localhost'
$db_user = 'root';
$db_password = 'root';
$db_name = 'website_db';
$charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;port=8889; dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $conn = new PDO($dsn, $db_user, $db_password, $options);
} catch (PDOException $error) {
    die("Connection failed: " . $error->getMessage());
}

return $conn;
