<?php
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json');
include __DIR__ . "./../include/db/database.php";

// Log entry point
error_log("post_announcement.php - Script started");

// Ensure only admins can access this script
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    error_log("post_announcement.php - Unauthorized access attempt");
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check if title and content are provided
if (!isset($_POST['title']) || !isset($_POST['content']) || empty(trim($_POST['title'])) || empty(trim($_POST['content']))) {
    error_log("post_announcement.php - Missing or empty title/content");
    http_response_code(400);
    echo json_encode(['error' => 'Title and message are required']);
    exit;
}

$title = trim($_POST['title']);
$content = trim($_POST['content']);
$user_id = isset($_POST['user_id']) && is_numeric($_POST['user_id']) && (int)$_POST['user_id'] > 0 ? (int)$_POST['user_id'] : null;

error_log("post_announcement.php - Received: title='$title', content='$content', user_id=" . ($user_id !== null ? $user_id : 'null'));

try {
    $conn->beginTransaction();

    // Insert into notifications table
    $query = "INSERT INTO notifications (title, message, created_at) VALUES (:title, :message, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        'title' => $title,
        'message' => $content
    ]);
    $notification_id = $conn->lastInsertId();
    error_log("post_announcement.php - Inserted notification ID: $notification_id");

    // If user_id is provided, insert into user_notifications
    if ($user_id !== null) {
        // Verify user exists
        $query = "SELECT id FROM users WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $user_exists = $stmt->fetch(PDO::FETCH_ASSOC);

        error_log("post_announcement.php - User validation for user_id: $user_id, exists: " . ($user_exists ? 'yes' : 'no'));

        if (!$user_exists) {
            $conn->rollBack();
            error_log("post_announcement.php - Invalid user_id: $user_id");
            http_response_code(400);
            echo json_encode(['error' => 'Invalid user ID']);
            exit;
        }

        // Insert into user_notifications
        $query = "INSERT INTO user_notifications (status, received_at, user_id, notification_id) 
                  VALUES ('delivered', NOW(), :user_id, :notification_id)";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            'user_id' => $user_id,
            'notification_id' => $notification_id
        ]);
        error_log("post_announcement.php - Inserted user_notification for user_id: $user_id, notification_id: $notification_id");
    } else {
        // If no user_id is provided, send to all users
        $query = "SELECT id FROM users";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        error_log("post_announcement.php - Sending announcement to all users, total users: " . count($users));

        // Insert into user_notifications for each user
        $query = "INSERT INTO user_notifications (status, received_at, user_id, notification_id) 
                  VALUES ('delivered', NOW(), :user_id, :notification_id)";
        $stmt = $conn->prepare($query);

        foreach ($users as $user) {
            $stmt->execute([
                'user_id' => $user['id'],
                'notification_id' => $notification_id
            ]);
            error_log("post_announcement.php - Inserted user_notification for user_id: {$user['id']}, notification_id: $notification_id");
        }
    }

    $conn->commit();
    error_log("post_announcement.php - Announcement posted successfully: $title" . ($user_id !== null ? " for user_id: $user_id" : " for all users"));
    echo json_encode(['success' => 'Announcement posted successfully']);
} catch (PDOException $error) {
    $conn->rollBack();
    error_log("post_announcement.php - Database error: " . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
ob_end_flush();
exit;
