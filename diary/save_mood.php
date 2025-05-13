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


function saveMood($date, $mood)
{
    global $conn;
    $user_id = $_SESSION['user_id'];


    // Validate mood against ENUM values
    $validMoods = ['happy', 'normal', 'stress', 'sad', 'angry'];
    if (!in_array($mood, $validMoods)) {
        throw new Exception('Invalid mood value');
    }

    // Validate date format (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !strtotime($date)) {
        error_log("Invalid date format: " . json_encode($date));
        throw new Exception('Invalid date format: ' . $date);
    }




    // Check if a mood entry exists for the date
    $sql = "SELECT id FROM diaries WHERE created_at = :date AND user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":date", $date, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Update existing entry
        $sql = "UPDATE diaries SET mood = :mood WHERE created_at = :date AND user_id = :user_id";
        $stmt = $conn->prepare($sql); // Prepare the UPDATE query
        $stmt->bindParam(":mood", $mood, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    } else {
        //FIXME
        // Insert new entry with default values
        $title = "Daily Mood";
        $content = null;
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
    return $stmt->execute();
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"] ?? null;
    $mood = $_POST["mood"] ?? null;

    if ($date && in_array($mood, ["happy", "normal", "stress", "sad", "angry"])) {
        try {
            $result = saveMood($date, $mood);
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Mood saved successfully'
                ]);
            } else {
                throw new Exception('Failed to save mood');
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
            'message' => 'Invalid date or mood'
        ]);
    }
}
