<?php
session_start();
include __DIR__ . "./../include/db/database.php";

header('Content-Type: application/json');
// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: User not logged in'
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $date = isset($_GET['date']) ? $_GET['date'] : null;
    $user_id = $_SESSION['user_id'];

    error_log("get_diaries.php - Fetching diary for date: $date, user_id: $user_id");

    if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        try {
            $sql = "SELECT title, content 
                    FROM diaries 
                    WHERE DATE_FORMAT(created_at, '%Y-%m-%d') = :date 
                    AND user_id = :user_id 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                error_log("get_diaries.php - Failed to execute query: " . print_r($conn->errorInfo(), true));
                throw new Exception('Failed to execute diary query');
            }
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("get_diaries.php - Diary fetched, entry: " . json_encode($entry));
            if ($entry) {
                echo json_encode([
                    'status' => 'success',
                    'title' => $entry['title'],
                    'content' => $entry['content']
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'title' => 'No Title',
                    'content' => 'No Content'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch diary entry: ' . $e->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid date format'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
