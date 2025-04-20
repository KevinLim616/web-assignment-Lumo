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
    $sql = $conn->prepare("SELECT id FROM account WHERE email = ?");
    $sql->bind_param("s", $email);
    $sql->execute();
    $sql->store_result();

    return $sql->num_rows > 0;
}

//sign up function
function signUp($username, $email, $password, $date_of_birth)
{
    global $conn;

    $username = htmlspecialchars($username);
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_begin_transaction($conn);

    try {
        $stmt1 = $conn->prepare("INSERT INTO account (username, email, password) VALUES (?, ?, ?)");
        $stmt1->bind_param("sss", $username, $email, $hashed_password);
        $stmt1->execute();

        $account_id = $conn->insert_id;

        $stmt2 = $conn->prepare("INSERT INTO users (Acc_id, date_of_birth) VALUES (?, ?)");
        $stmt2->bind_param("is", $account_id, $date_of_birth);
        $stmt2->execute();

        mysqli_commit($conn);

        return "user created";
    } catch (Exception $error) {
        // Rollback on error
        mysqli_rollback($conn);
        echo "Error: " . $error->getMessage();
    }
}
