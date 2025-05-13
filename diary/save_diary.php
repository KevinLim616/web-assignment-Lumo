<?php
session_start();
header("Content-Type: application/json");
include __DIR__ . "/../include/db/database.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: User not logged in'
    ]);
    exit;
}

function saveDiary($title, $content, $date)
{
    global $conn;
    $user_id = $_SESSION['user_id'];

    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !strtotime($date)) {
        error_log("save_diary.php - Invalid date format: " . json_encode($date));
        throw new Exception('Invalid date format: ' . $date);
    }

    // Check if an entry exists for the date
    $sql = "SELECT id FROM diaries WHERE created_at = :date AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    if (!$stmt->execute()) {
        error_log("save_diary.php - Failed to execute SELECT query: " . print_r($conn->errorInfo(), true));
        throw new Exception('Failed to check existing diary entry');
    }
    error_log("save_diary.php - SELECT query executed, rows: " . $stmt->rowCount());

    if ($stmt->rowCount() > 0) {
        // Update existing entry
        $sql = "UPDATE diaries SET title = :title, content = :content 
                WHERE created_at = :date AND user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    } else {
        // Insert new entry with default values
        $mood = null;
        $image = null;
        $sql = "INSERT INTO diaries (title, content, created_at, image, mood, user_id) 
                VALUES (:title, :content, :created_at, :image, :mood, :user_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $date, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_STR);
        $stmt->bindParam(":mood", $mood, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    }

    if (!$stmt->execute()) {
        error_log("save_diary.php - Failed to execute query: " . print_r($conn->errorInfo(), true));
        throw new Exception('Failed to save diary: ' . print_r($conn->errorInfo(), true));
    }
    error_log("save_diary.php - Diary saved successfully for date: $date, user_id: $user_id");
    return true;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"] ?? null;
    $content = $_POST["content"] ?? null;
    $date = $_POST["date"] ?? null;

    error_log("save_diary.php - Received POST data: title=$title, date=$date, user_id=" . ($_SESSION['user_id'] ?? 'undefined'));

    if ($title && $content && $date) {
        try {
            $result = saveDiary($title, $content, $date);
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Diary saved successfully'
                ]);
            } else {
                throw new Exception('Failed to save diary');
            }
        } catch (Exception $error) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Title, content, and date are required'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
