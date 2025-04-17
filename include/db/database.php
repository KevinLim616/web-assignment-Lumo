<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = 'root';
$db_name = 'website_db';
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

try {
    $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
} catch (mysqli_sql_exception) {
    echo "Error: " . $conn->connect_error;
}
// $conn->close();
