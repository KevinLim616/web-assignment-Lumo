<?php
//sql query
//include database connection as global variable
include __DIR__ . "/../include/db/database.php";


function userExist($email, $password)
{
    global $conn;
    // select from account table where username and password match
    $sql = "SELECT * FROM account WHERE email = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();


    //if there are results
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return true;
        }
    } else {
        //if there are no results
        return false;
    }
}

// Login
function login($email, $password)
{
    //sanitize input
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    //filter input

    // Check if the email and password are correct
    //TODO: hash password
    if ($email == "admin@example.com" && $password == "password") {
        //redirect to admin page
        header("Location: admin/admin.php");
    } else if (userExist($email, $password)) { //search existed user in db
        echo "user logged in";
    } else {
        echo "Invalid email or password.";
    }
}
