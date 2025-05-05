<?php
include __DIR__ . "./../include/db/database.php";

function getTasks()
{
    global $conn;
    $sql = "SELECT id, title, time, category FROM task WHERE status ='pending' ORDER BY date, time";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
