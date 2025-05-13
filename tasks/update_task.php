<?php
session_start();

header('Content-Type: application/json');
include __DIR__ . "/../include/db/database.php";


function updateTaskStatus($task_id, $status)
{
    global $conn;
    $user_id = $_SESSION['user_id'];


    $sql = "UPDATE task SET status = :status WHERE id = :taskId AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":taskId", $task_id, PDO::PARAM_INT);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $task_id = $_POST["task_id"] ?? null;
    $status = $_POST["status"] ?? null;

    if ($task_id && in_array($status, ["pending", "completed"])) {
        try {
            $result = updateTaskStatus($task_id, $status);
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => "Task status updated sucessfully"
                ]);
            } else {
                throw new Exception('Failed to update task status');
            }
        } catch (Exception $error) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        };
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid task ID or status'
        ]);
    }
}
