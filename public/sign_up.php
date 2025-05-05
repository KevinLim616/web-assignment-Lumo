<?php
include("../include/db/database.php");
include("../authentication/sign_up_functions.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../style//form.css">
    <link rel="stylesheet" href="../style/sign_up.css">

    <script src="../authentication/signUp.js" type="module" defer></script>
    <title>Document</title>
</head>


<body>

    <div class="container">
        <section class="left">
            <div style="display: flex;">
                <img src="../assets/img/LOGO.svg" alt="logo" width="100px" />
            </div>
            <img src="../assets/img/signuppage_mascot.png" alt="sign up mascot" class="sign-up-mascot">
        </section>
        <section class="right">
            <div class="form-container">
                <form
                    action="sign_up.php"
                    method="post"
                    class="sign-up-form"
                    id="sign-up-form"
                    novalidate>
                    <h1>Sign Up</h1>
                    <div class="inputs-container">
                        <!--name-->
                        <div class="input-control">
                            <div class="input-box ">
                                <img src="./../assets/icons/user.svg" alt="user" />
                                <label for="name">Name:</label>

                                <input
                                    type="text"
                                    name="name"
                                    class="input-field"
                                    placeholder="Name"

                                    id="name-field" />
                            </div>
                            <div class="error-message"></div>

                        </div>
                        <!--email-->
                        <div class="input-control">
                            <div class="input-box">
                                <img src="./../assets/icons/mail.svg" alt="email" />
                                <label for="email">Email:</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="input-field"
                                    placeholder="Email"

                                    id="mail-field" />
                            </div>
                            <div class="error-message"></div>
                        </div>
                        <!--Password field-->
                        <div class="input-control">
                            <div class="input-box">
                                <img src="./../assets/icons/lock.svg" alt="password" />
                                <label for="password">Password:</label>

                                <input
                                    type="password"
                                    name="password"
                                    class="input-field"
                                    placeholder="Password"

                                    id="password-field" />
                                <img
                                    src="./../assets/icons/eye-off.svg"
                                    alt="eyes-off"
                                    class="eye"
                                    id="show-password" />
                            </div>
                            <div class="error-message"></div>

                        </div>
                        <!--confirm Password -->
                        <div class="input-control">
                            <div class="input-box">
                                <img src="./../assets/icons/lock.svg" alt="password" />
                                <label for="confirm password"> Confirm Password:</label>

                                <input
                                    type="password"
                                    name="confirm password"
                                    class="input-field"
                                    placeholder="Confirm Password"

                                    id="confirm-password-field" />
                                <img
                                    src="./../assets/icons/eye-off.svg"
                                    alt="eyes-off"
                                    class="eye"
                                    id="confirm-show-password" />
                            </div>
                            <div class="error-message"></div>

                        </div>
                        <!--Date of Birth -->
                        <div class="input-control">
                            <div class="input-box" onclick="document.getElementById('date-input').showPicker()">
                                <img src="./../assets/icons/calendar.svg" alt="calendar" />
                                <label for="DOB">Date of Birth:</label>
                                <!--TODO: datepicker style-->
                                <input
                                    type="date"
                                    name="DOB"
                                    id="date-input"
                                    class="input-field date-input"

                                    placeholder="Date of Birth" id="date-field" />
                            </div>
                            <div class="error-message"></div>

                        </div>

                    </div>
                    <div class="sign-up-action">
                        <input type="submit" value="Sign Up" class="button" name="sign-up" />
                        <p class="link">Already have an account?
                            <a href="../index.php">sign In here!</a>
                        </p>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>


</html>

<?php
if (isset($_POST["sign-up"])) {
    $username = $_POST["name"];
    $password =  $_POST["password"];
    $email = $_POST["email"];
    $date_of_birth = $_POST["DOB"];
    signUp($username, $email, $password, $date_of_birth);
}


?>