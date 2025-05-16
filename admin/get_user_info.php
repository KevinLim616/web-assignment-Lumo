<?php
error_reporting(E_ALL & ~E_NOTICE); // Show all errors except notices
ini_set('display_errors', 0); // Ensure errors don't output to response

session_start();

header('Content-Type: application/json; charset=utf-8');

// Ensure no output before JSON
if (ob_get_level()) {
    ob_end_clean();
}

include __DIR__ . "./../include/db/database.php";

// Log entry point
error_log("get_user_info.php - Script started");

// Ensure only admins can access this script
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    $query = "SELECT 
                    u.id AS user_id,
                    a.username,
                    a.CreatedTime,
                    (SELECT COUNT(*) FROM task t WHERE t.user_id = u.id) AS task_count,
                    COALESCE(
                        (SELECT un.status 
                         FROM user_notifications un 
                         WHERE un.user_id = u.id 
                         ORDER BY un.received_at DESC 
                         LIMIT 1),
                        'No Notifications'
                    ) AS notification_status
                FROM 
                    users u
                JOIN 
                    account a ON u.Acc_id = a.id
                WHERE 
                    u.Acc_id != 1 AND u.id != 11
                ORDER BY 
                    a.CreatedTime DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Debug: Raw users from DB: " . print_r($users, true));
    error_log("get_user_info.php - Query executed, rows returned: " . count($users));

    // Check if any users were found
    if (empty($users)) {
        error_log("get_user_info.php - No users found in the database");
        echo json_encode(['error' => 'No users found']);
        exit;
    }

    // Format the data for the table
    $user_data = [];
    foreach ($users as $user) {
        // Handle null createdTime
        $createdTime = $user['CreatedTime'];
        $formattedDate = date('d/m/Y', strtotime($createdTime));
        $user_data[] = [
            'id' => $user['user_id'], // Send numeric ID without # prefix
            'display_id' => '#' . $user['user_id'], // Separate field for display with # prefix
            'username' => htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'),
            'CreatedTime' => date('d/m/Y', strtotime($user['CreatedTime'])), // Format as DD/MM/YYYY
            'task_count' => $user['task_count'] ?? 0,
            'notification_status' => $user['notification_status']
        ];
    }
    // Log user_data before encoding
    error_log("Debug: user_data before JSON encode: " . print_r($user_data, true));

    // Prepare response
    $response = ['users' => $user_data, 'total' => count($user_data)];

    // Log JSON string
    $json_output = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    error_log("Debug: JSON output: " . $json_output);

    // Output JSON
    echo $json_output;
} catch (PDOException $error) {
    error_log("get_user_info.php - Database error: " . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} catch (JsonException $error) {
    error_log("get_user_info.php - JSON encode error: " . $error->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'JSON encoding error']);
}

exit;
