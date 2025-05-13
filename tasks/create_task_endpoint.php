<?php
// // HIGHLIGHT START: Enforce output buffering and error suppression
// ob_start();
// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type:application/json');
include __DIR__ . './../include/db/database.php';
include __DIR__ . "/create_task.php";

// error_log("create_task_endpoint.php - Session ID: " . session_id() . ", User ID: " . ($_SESSION['user_id'] ?? 'undefined') . ", POST: " . json_encode($_POST));

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: User not logged in'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? "";
    $time = $_POST['time'] ?? "";
    $description = $_POST['description'] ?? "";
    $category = $_POST['category'] ?? "";

    try {
        createTask($title, $date, $time, $description, $category);
        $task_id = $conn->lastInsertId(); //fetch newest task
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT id, title, time, category FROM task WHERE id = :id AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->execute();
        $new_task = $stmt->fetch(PDO::FETCH_ASSOC);
        ob_end_clean();

        echo json_encode([
            'status' => "success",
            'message' => "Task createed successfully",
            'task' => $new_task
        ]);
    } catch (Exception $error) {
        error_log("Task creation error: " . $error->getMessage());
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create task:' . $error->getMessage()
        ]);
    }
}
