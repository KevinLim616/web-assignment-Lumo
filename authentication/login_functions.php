<?php
// MODIFIED: Prevent multiple inclusions
if (!defined('LOGIN_FUNCTIONS_INCLUDED')) {
    define('LOGIN_FUNCTIONS_INCLUDED', true);

    // MODIFIED: Use include_once
    include_once __DIR__ . "/../include/db/database.php";

    // MODIFIED: Renamed to avoid conflicts
    function loginUserExist($email, $password)
    {
        global $conn;
        $sql = "SELECT * FROM account WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    function login($email, $password)
    {
        global $conn;
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        // MODIFIED: Removed hardcoded admin check; use loginUserExist for all users
        if ($user = loginUserExist($email, $password)) {
            // Fetch users.id using account.id
            $stmt = $conn->prepare("SELECT id FROM users WHERE Acc_id = :account_id");
            $stmt->bindParam(':account_id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();
            $user_record = $stmt->fetch(PDO::FETCH_ASSOC);
            // NEW: Check if user record exists
            if (!$user_record) {
                error_log("login_functions.php - No user found for account_id: " . $user['id']);
                echo "error_fetching_user";
                exit;
            }

            // MODIFIED: Set role based on email
            $role = ($email === 'admin@example.com') ? 'admin' : 'user';

            $_SESSION['user'] = [
                'id' => $user['id'], // account.id
                'email' => $user['email'],
                'username' => $user['username'],
                'role' => $role
            ];
            $_SESSION['user_id'] = $user_record['id']; // users.id

            // Set auto-login cookie
            $token = bin2hex(random_bytes(16));
            $expires = time() + (7 * 24 * 60 * 60); // 7 days
            setcookie('auth_token', $token, $expires, '/', '', true, true);

            // Store token in database
            $stmt = $conn->prepare("UPDATE account SET auth_token = :token WHERE id = :id");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
            $stmt->execute();

            echo "user logged in";
            // MODIFIED: Redirect based on role
            $redirect = ($role === 'admin') ? '../web/admin/admin.php' : '../web/user/dashboard.php';
            header("Location: $redirect");
            exit;
        } else {
            echo "Invalid email or password.";
        }
    }

    // MODIFIED: Check cookie for auto-login
    function checkAutoLogin()
    {
        global $conn;
        if (isset($_COOKIE['auth_token']) && !isset($_SESSION['user'])) {
            $token = $_COOKIE['auth_token'];
            $stmt = $conn->prepare("SELECT * FROM account WHERE auth_token = :token");
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Fetch users.id using account.id
                $stmt = $conn->prepare("SELECT id FROM users WHERE Acc_id = :account_id");
                $stmt->bindParam(':account_id', $user['id'], PDO::PARAM_INT);
                $stmt->execute();
                $user_record = $stmt->fetch(PDO::FETCH_ASSOC);
                // NEW: Check if user record exists
                if (!$user_record) {
                    error_log("login_functions.php - No user found for account_id: " . $user['id']);
                    return false;
                }

                // MODIFIED: Set role based on email
                $role = ($user['email'] === 'admin@example.com') ? 'admin' : 'user';

                $_SESSION['user'] = [
                    'id' => $user['id'], // account.id
                    'email' => $user['email'],
                    'username' => $user['username'],
                    'role' => $role
                ];
                $_SESSION['user_id'] = $user_record['id']; // users.id
                // MODIFIED: Fixed duplicate user_id setting and added logging
                error_log("login_functions.php - Auto-login successful, account_id: " . $user['id'] . ", user_id: " . $user_record['id']);
                return true;
            }
        }
        return false;
    }
}
