<?php
include __DIR__ . "/../include/db/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST["mode"] ?? "";
    $title = $_POST["title"] ?? "";
    $date = $_POST["date"] ?? "";
    $time = $_POST["time"] ?? "";
    $description = $_POST["description"] ?? "";
    $category = $_POST["category"] ?? "";
}

function createTask($title, $date, $time, $description, $category)
{
    global $conn;

    $title = trim($title);
    $description = trim($description);
    $category = trim($category);

    $dateObj = DateTime::createFromFormat("Y-m-d", $date);
    $timeObj = DateTime::createFromFormat("H:i", $time);

    if (!$dateObj || !$timeObj) {
        throw new Exception("Invalid date or time format");
    }

    $allowedCategories = ['home', 'work', 'school'];
    if (!in_array(strtolower($category), $allowedCategories)) {
        throw new Exception("Invalid categories");
    }

    $sql = "INSERT INTO task (title, date, time, description, category, status) VALUES (:title, :date, :time, :description, :category, :status)";
    $stmt = $conn->prepare($sql);


    if ($stmt === false) {
        throw new Exception("Prepare failed: " . print_r($conn->errorInfo(), true));
    }


    $status = 'pending';
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':time', $time, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);

    if ($stmt->execute()) {
        return true;
    } else {
        throw new Exception("Execute failed: " . print_r($stmt->errorInfo(), true));
    }
}
