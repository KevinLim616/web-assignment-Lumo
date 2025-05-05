<?php
include __DIR__ . ("./../include/db/database.php"); // adjust if needed

//TODO: add sessions & prevent sql injection
$sql = "SELECT title, message, created_at FROM notifications ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . print_r($conn->errorInfo(), true));
}

$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
