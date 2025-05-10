<?php
header("Content-Type: application/json");
include __DIR__ . "/../include/db/database.php";

function saveDiary($title, $content, $date)
{
    global $conn;

    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !strtotime($date)) {
        error_log("Invalid date format: " . json_encode($date));
        throw new Exception('Invalid date format: ' . $date);
    }

    // Check if an entry exists for the date
    $sql = "SELECT id FROM diaries WHERE created_at = :date";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Update existing entry
        $sql = "UPDATE diaries SET title = :title, content = :content WHERE created_at = :date";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    } else {
        // Insert new entry with default values
        $mood = null; // Mood can be updated separately via save_mood.php
        $image = null;
        $sql = "INSERT INTO diaries (title, content, created_at, image, mood) VALUES (:title, :content, :created_at, :image, :mood)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);
        $stmt->bindParam(":created_at", $date, PDO::PARAM_STR);
        $stmt->bindParam(":image", $image, PDO::PARAM_STR);
        $stmt->bindParam(":mood", $mood, PDO::PARAM_STR);
    }

    return $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"] ?? null;
    $content = $_POST["content"] ?? null;
    $date = $_POST["date"] ?? null;

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
