<?php
ob_start();

include __DIR__ . "./../include/db/database.php";

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

function getTasks($date = null)
{
    global $conn;
    try {
        $sql = "SELECT id, title, date, time, description, category, status FROM task WHERE status = 'pending'";
        $params = [];
        if ($date) {
            $sql .= " AND DATE(date) = :date";
            $params[':date'] = $date;
        }
        $sql .= " ORDER BY time";
        $stmt = $conn->prepare($sql);
        if ($date) {
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        }
        $stmt->execute($params);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("SQL Query: $sql, Params: " . json_encode($params));
        error_log("Fetched tasks for date: " . ($date ?: 'null') . ", Count: " . count($tasks) . ", Data: " . json_encode($tasks));
        return $tasks;
    } catch (Exception $error) {
        error_log("Error fetching tasks: " . $error->getMessage());
        return [];
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === 'get_tasks.php') {
    header('Content-Type: application/json');
    $date = isset($_GET['date']) ? $_GET['date'] : null;
    error_log("Received date parameter: " . ($date ?: 'null'));
    $tasks = getTasks($date);
    ob_end_clean();
    if (empty($tasks)) {
        echo json_encode([]);
    } else {
        echo json_encode($tasks);
    }
    exit;
}
