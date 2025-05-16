<?php
ob_start();

session_start();

include __DIR__ . "./../include/db/database.php";

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

function getTasks($user_id, $date = null)
{
    global $conn;
    try {
        error_log("get_tasks.php - Fetching tasks for user_id: " . $user_id);
        $sql = "SELECT id, title, date, time, description, category, status FROM task WHERE status = 'pending' AND user_id = :user_id ";
        $params = [':user_id' => $user_id];
        if ($date) {
            $sql .= " AND DATE(date) = :date";
            $params[':date'] = $date;
        }
        $sql .= " ORDER BY time";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        if ($date) {
            $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        }
        $stmt->execute($params);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("SQL Query: $sql, Params: " . json_encode($params));
        error_log("Fetched tasks for user_id:$user_id, date: " . ($date ?: 'null') . ", Count: " . count($tasks) . ", Data: " . json_encode($tasks));
        return $tasks;
    } catch (Exception $error) {
        error_log("Error fetching tasks: " . $error->getMessage());
        return [];
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === 'get_tasks.php') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $date = $_GET['date'] ?? null;
    error_log("Received date: " . ($date ?: 'null'));

    $tasks = getTasks($user_id, $date);
    ob_end_clean();
    echo json_encode($tasks);
    exit;
}
