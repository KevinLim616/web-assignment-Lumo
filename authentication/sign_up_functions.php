<?php
include("../include/db/database.php");

//sign up function
function signUp($username, $password, $email)
{
    //sanitize input
    $username = htmlspecialchars($username);
    //filter input

    $sql = "INSERT INTO account (username, password, email) VALUES ('$username', '$password','$email')";
    include("../include/db/database.php");
    $result = mysqli_query($conn, $sql);

    //if there are results
    if ($result) {
        echo "user created";
    } else {
        //if there are no results
        echo "Error: user could not be created.";
        // echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="sign_up.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" name="signUp" value="sign Up">
    </form>
</body>

</html>

<?php
if (isset($_POST["signUp"])) {
    $username = $_POST["username"];
    $password =  $_POST["password"];
    $email = $_POST["email"];
    signUp($username, $password, $email);
}

mysqli_close($conn);
?>