<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

include __DIR__ . "/../include/db/database.php";

// Log entry point
error_log("get_notifications.php - Script started");

// Check if user is logged in and has a valid user_id
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || empty($_SESSION['user']) || !isset($_SESSION['user']['id']) || !is_numeric($_SESSION['user']['id'])) {
    error_log("get_notifications.php - Unauthorized access attempt");
    header("Location: ../index.php"); // Redirect to login page
    exit;
}

$user_id = (int)$_SESSION['user_id'];
error_log("get_notifications.php - Fetching notifications for user_id: $user_id");
error_log("get_notifications.php - Session user data: " . print_r($_SESSION['user'], true));

try {
    // Query to fetch notifications for the user from user_notifications, joined with notifications
    $sql = "SELECT n.title, n.message, n.created_at 
            FROM notifications n
            INNER JOIN user_notifications un ON n.id = un.notification_id
            WHERE un.user_id = :user_id
            ORDER BY n.created_at DESC 
            LIMIT 5";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        error_log("get_notifications.php - Prepare failed: " . print_r($conn->errorInfo(), true));
        $notifications = []; // Set empty array to prevent errors in notification.php
        return;
    }

    $stmt->execute(['user_id' => $user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("get_notifications.php - Retrieved " . count($notifications) . " notifications for user_id: $user_id");
} catch (PDOException $error) {
    error_log("get_notifications.php - Database error: " . $error->getMessage());
    $notifications = []; // Set empty array to prevent errors in notification.php
}
