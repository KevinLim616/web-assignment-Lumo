<?php
include __DIR__ . "./../include/db/database.php";

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $date = isset($_GET['date']) ? $_GET['date'] : null;

    if ($date && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        try {
            $sql = "SELECT title, content 
                    FROM diaries 
                    WHERE DATE_FORMAT(created_at, '%Y-%m-%d') = :date 
                    LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->execute();

            $entry = $stmt->fetch(PDO::FETCH_ASSOC);
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
