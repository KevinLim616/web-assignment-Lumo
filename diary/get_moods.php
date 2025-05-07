<?php
include __DIR__ . "./../include/db/database.php";

header('Content-Type: application/json');


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $month = isset($_GET['month']) ? intval($_GET['month']) : null;

    if ($year && $month && $month >= 1 && $month <= 12) {
        try {
            // Calculate date range for the month
            $startDate = sprintf("%04d-%02d-01", $year, $month);
            $endDate = date("Y-m-t", strtotime($startDate)); // Last day of month

            // Query diaries for moods in the month
            $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS date, mood 
                    FROM diaries 
                    WHERE created_at BETWEEN :startDate AND :endDate 
                    AND mood IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":startDate", $startDate, PDO::PARAM_STR);
            $stmt->bindParam(":endDate", $endDate, PDO::PARAM_STR);
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
