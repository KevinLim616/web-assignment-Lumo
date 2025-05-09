<?php
// Prevent any output before headers
ob_start(); // Start output buffering to catch any accidental output

include __DIR__ . "./../include/db/database.php";

// Suppress PHP errors in the response
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

function getTasks($date = null)
{
    global $conn;
    try {
        $sql = "SELECT id, title, date, time, description, category FROM task WHERE status = 'pending'";
        if ($date) {
            $sql .= " AND DATE(date) = :date";
        }
        $sql .= " ORDER BY time";
        $stmt = $conn->prepare($sql);
        if ($date) {
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $error) {
        error_log("Error fetching tasks: " . $error->getMessage());
        return [];
    }
}


// Only output JSON if this script is the main entry point
if (basename($_SERVER['SCRIPT_FILENAME']) === 'get_tasks.php') {
    header('Content-Type: application/json');
    $tasks = getTasks($date);
    // Ensure no trailing output
    ob_end_clean(); // Clear any buffered output
    echo json_encode($tasks);
    exit; // Terminate script to prevent further output
}
