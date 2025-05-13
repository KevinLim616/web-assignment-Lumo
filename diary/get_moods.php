<?php
ob_start(); // Start output buffering
ini_set('display_errors', 0); // Disable error display
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); // Log all errors
session_start();
include __DIR__ . "./../include/db/database.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    ob_end_clean();
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized: User not logged in'
    ]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $month = isset($_GET['month']) ? intval($_GET['month']) : null;

    error_log("get_moods.php - Year: " . ($year ?? 'null') . ", Month: " . ($month ?? 'null') . ", User ID: " . ($_SESSION['user_id'] ?? 'undefined'));

    if ($year && $month && $month >= 1 && $month <= 12) {
        try {
            // Calculate date range for the month
            $startDate = sprintf("%04d-%02d-01", $year, $month);
            $endDate = date("Y-m-t", strtotime($startDate)); // Last day of month

            // Query diaries for moods in the month
            $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, mood 
                    FROM diaries 
                    WHERE created_at BETWEEN :startDate AND :endDate 
                    AND mood IS NOT NULL 
                    AND user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $stmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
            $stmt->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);

            $stmt->execute();

            $moods = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $moods[$row['date']] = $row['mood'];
            }

            echo json_encode([
                'status' => 'success',
                'moods' => $moods
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to fetch moods: ' . $e->getMessage()
            ]);
        }
    } else {
        error_log("get_moods.php - Invalid year or month: Year=$year, Month=$month");
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid year or month'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
}
