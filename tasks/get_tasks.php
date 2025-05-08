<?php
include __DIR__ . "./../include/db/database.php";

// header('Content-Type:application/json');

function getTasks()
{
    global $conn;
    try {
        global $conn;
        $sql = "SELECT id, title,date, time,description, category FROM task WHERE status ='pending' ORDER BY date, time";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $error) {
        error_log("Error fetching tasks:" . $error->getMessage());
        return [];
    }
}
