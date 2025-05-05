<?php
include __DIR__ . "/../include/db/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST['mode'] ?? '';

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $dob = $_POST['date_of_birth'] ?? '';

    if ($mode === "signup") {
        if (userExist($email)) {
            echo "email_exists";
            exit;
        }

        // Proceed to sign up
        $result = signUp($username, $email, $password, $dob);
        echo $result;
        exit;
    }
}

function userExist($email)
{
    global $conn;
    $sql = $conn->prepare("SELECT id FROM account WHERE email = :email");
    $sql->bindParam(':email', $email, PDO::PARAM_STR);
    $sql->execute();
    return $sql->rowCount() > 0;
}

// Sign up function
function signUp($username, $email, $password, $date_of_birth)
{
    global $conn;

    $username = trim($username);
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn->beginTransaction();

    try {
        $stmt1 = $conn->prepare("INSERT INTO account (username, email, password) VALUES (:username, :email, :password)");
        $stmt1->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt1->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt1->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt1->execute();

        $account_id = $conn->lastInsertId();

        $stmt2 = $conn->prepare("INSERT INTO users (Acc_id, date_of_birth) VALUES (:acc_id, :dob)");
        $stmt2->bindParam(':acc_id', $account_id, PDO::PARAM_INT);
        $stmt2->bindParam(':dob', $date_of_birth, PDO::PARAM_STR);
        $stmt2->execute();

        $conn->commit();

        return "user created";
    } catch (Exception $error) {
        // Rollback on error
        $conn->rollBack();
        return "Error: " . $error->getMessage();
    }
}
