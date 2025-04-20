<?php
//sql query
//include database connection as global variable
include __DIR__ . "/../include/db/database.php";


function userExist($email, $password)
{
    // select from account table where username and password match
    $sql = "SELECT * FROM account WHERE email = '$email' AND password = '$password'";
    global $conn;
    $result = mysqli_query($conn, $sql);

    //if there are results
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        //if there are no results
        return false;
    }
}

// Login
function login($email, $password)
{
    //sanitize input
    $email = htmlspecialchars($email);
    $password = htmlspecialchars($password);
    //filter input

    // Check if the email and password are correct
    //TODO: hash password
    if ($email == "admin@example.com" && $password == "password") {
        //redirect to admin page
        header("Location: admin/admin.php");
    } else if (userExist($email, $password) == true) { //search existed user in db
        echo "user logged in";
    } else {
        echo "Invalid email or password.";
    }
}
