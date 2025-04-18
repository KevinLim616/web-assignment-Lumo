<?php
include("../include/db/database.php");

//sign up function
function signUp($username, $email, $password, $date_of_birth)
{
    global $conn;
    //sanitize input
    $username = htmlspecialchars($username);
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //filter input

    mysqli_begin_transaction($conn);

    try {
        // 1. Insert into `account`
        $stmt1 = $conn->prepare("INSERT INTO account (username, email, password) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $username, $email, $hashed_password);
        $stmt1->execute();
        // Get the newly inserted account's ID (if you have a foreign key in `user`)
        $account_id = $conn->insert_id;
        // 2. Insert into `user` (assuming you want to link with account_id)
        $stmt2 = $conn->prepare("INSERT INTO users (Acc_id, date_of_birth) VALUES (?, ?)");
        $stmt2->bind_param("is", $account_id, $date_of_birth);
        $stmt2->execute();
        // Commit the transaction
        mysqli_commit($conn);

        echo "User created";
    } catch (Exception $error) {
        // Rollback on error
        mysqli_rollback($conn);
        echo "Error: " . $error->getMessage();
    }
}
