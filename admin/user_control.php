<?php
ob_start();
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

session_start();

header('Content-Type: application/json');
include __DIR__ . "./../include/db/database.php";

// Log entry point
error_log("delete_user.php - Script started");

// Ensure only admins can access this script
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Check if user_id is provided
if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit;
}

$user_id = (int)$_POST['user_id'];

// Prevent deletion of admin user
if ($user_id === 11) {
    http_response_code(403);
    echo json_encode(['error' => 'Cannot delete admin user']);
    exit;
}

try {
    $conn->beginTransaction();

    // Get Acc_id from users table
    $query = "SELECT Acc_id FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $conn->rollBack();
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $acc_id = $user['Acc_id'];

    // Delete related tasks
    $query = "DELETE FROM task WHERE user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['user_id' => $user_id]);

    // Delete from users table
    $query = "DELETE FROM users WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['user_id' => $user_id]);

    // Delete from account table
    $query = "DELETE FROM account WHERE id = :acc_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['acc_id' => $acc_id]);

    $conn->commit();
    error_log("delete_user.php - User ID {$user_id} deleted successfully");
    echo json_encode(['success' => 'User deleted successfully']);
} catch (PDOException $error) {
    $conn->rollBack();
    error_log("delete_user.php - Database error: " . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

ob_end_flush();
exit;
