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

try {
    // Insert announcement into notifications table
    $query = "INSERT INTO notifications (title, message, created_at) VALUES (:title, :message, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        'title' => $title,
        'message' => $content
    ]);

    error_log("post_announcement.php - Announcement posted successfully: $title");
    echo json_encode(['success' => 'Announcement posted successfully']);
} catch (PDOException $error) {
    error_log("post_announcement.php - Database error: " . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

ob_end_flush();
exit;
