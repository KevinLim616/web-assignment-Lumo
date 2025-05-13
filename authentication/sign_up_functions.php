<?php
// MODIFIED: Prevent multiple inclusions



// MODIFIED: Use include_once for database.php
include_once __DIR__ . "/../include/db/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mode = $_POST['mode'] ?? '';

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $username = $_POST['username'] ?? '';
    $dob = $_POST['date_of_birth'] ?? '';

    // MODIFIED: Handle sign-up form submission
    if (isset($_POST['sign-up']) || $mode === "signup") {
        // MODIFIED: Normalize email
        $email = filter_var(trim(strtolower($email)), FILTER_SANITIZE_EMAIL);
        if (signUpUserExist($email)) {
            echo "email_exists";
            exit;
        }

        // Proceed to sign up
        $result = signUp($username, $email, $password, $dob);
        if ($result === "user created") {
            // Fetch the newly created user
            $stmt = $conn->prepare("SELECT id, username, email FROM account WHERE email = :email");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Store user in session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => 'user'
            ];

            // NEW: Set auto-login cookie
            $token = bin2hex(random_bytes(16));
            $expires = time() + (7 * 24 * 60 * 60); // 7 days
            setcookie('auth_token', $token, $expires, '/', '', true, true);

            // NEW: Store token in database
            $stmt = $conn->prepare("UPDATE account SET auth_token = :token WHERE id = :id");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            // MODIFIED: Updated redirect path
            // header("Location: ../user/dashboard.php");
            ob_clean();
            echo "success";
            exit;
        }
        echo $result;
        exit;
    }
}

// MODIFIED: Renamed to avoid conflicts
function signUpUserExist($email)
{
    global $conn;
    // Normalize email for comparison
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
