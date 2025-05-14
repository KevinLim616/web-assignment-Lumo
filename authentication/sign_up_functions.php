<?php
// session_start(); // Start session at the beginning
include_once __DIR__ . "/../include/db/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST['mode'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $dob = $_POST['date_of_birth'] ?? '';

    if (isset($_POST['sign-up']) || $mode === "signup") {
        $email = filter_var(trim(strtolower($email)), FILTER_SANITIZE_EMAIL);
        if (signUpUserExist($email)) {
            echo "email_exists";
            exit;
        }

        $result = signUp($username, $email, $password, $dob);
        if ($result === "user created") {
            // Fetch the newly created user
            $stmt = $conn->prepare("SELECT id, username, email FROM account WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$account) {
                error_log("sign_up_functions.php - Failed to fetch account for email: $email");
                echo "error_fetching_account";
                exit;
            }
            // MODIFIED: Added logging for account ID
            error_log("sign_up_functions.php - Account ID: " . $account['id']);
            // Fetch the users.id using account.id
            $stmt = $conn->prepare("SELECT id FROM users WHERE Acc_id = :account_id");
            $stmt->bindParam(':account_id', $account['id'], PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Store user in session
            $_SESSION['user'] = [
                'id' => $account['id'],
                'email' => $email,
                'username' => $username,
                'role' => 'user'
            ];
            $_SESSION['user_id'] = $user['id']; // Explicitly set user_id

            // Set auto-login cookie
            $token = bin2hex(random_bytes(16));
            $expires = time() + (7 * 24 * 60 * 60); // 7 days
            setcookie('auth_token', $token, $expires, '/', '', true, true);

            // Store token in database
            $stmt = $conn->prepare("UPDATE account SET auth_token = :token WHERE id = :id");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':id', $account['id'], PDO::PARAM_INT);
            $stmt->execute();

            ob_clean();
            echo "success";
            exit;
        }
        error_log("sign_up_functions.php - Signup failed: $result");
        echo $result;
        exit;
    }
}

function signUpUserExist($email)
{
    global $conn;
    $email = trim(strtolower($email));
    $sql = "SELECT id FROM account WHERE LOWER(email) = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->rowCount() > 0;
}

function signUp($username, $email, $password, $date_of_birth)
{
    global $conn;

    $username = trim($username);
    $email = filter_var(trim(strtolower($email)), FILTER_SANITIZE_EMAIL);
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
        $conn->rollBack();
        return "Error: " . $error->getMessage();
    }
}
