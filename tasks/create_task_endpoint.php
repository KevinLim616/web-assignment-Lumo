<?php
header('Content-Type:application/json');
include __DIR__ . './../include/db/database.php';
include __DIR__ . "/create_task.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? "";
    $time = $_POST['time'] ?? "";
    $description = $_POST['description'] ?? "";
    $category = $_POST['category'] ?? "";

    try {
        createTask($title, $date, $time, $description, $category);
        $task_id = $conn->lastInsertId(); //fetch newest task
        $sql = "SELECT id, title, time, category FROM task WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);
        $stmt->execute();
        $new_task = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => "success",
            'message' => "Task createed successfully",
            'task' => $new_task
        ]);
    } catch (Exception $error) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to create task:' . $error->getMessage()
        ]);
    }
}
