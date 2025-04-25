<?php
include __DIR__ . ("./../include/db/database.php"); // adjust if needed

//TODO: add sessions & prevent sql injection
$sql = "SELECT title, message, created_at FROM notifications ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);

$notifications = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
