<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../style/sign_in.css">
    <link rel="stylesheet" href="../style/sign_up.css">

    <title>Document</title>
</head>

<body>
    <header>
        <img src="./../assets/img/LOGO.svg" alt="logo" />
    </header>
    <section>
        <div>
            <img src="../assets/img/signuppage_mascot.png" alt="sign up mascot" width="60%">
        </div>
        <div class="form-container">
            <h1>Sign In</h1>
            <form
                action="index.php"
                method="post"
                class="sign-in-form">
                <div class="inputs-container">
                    <!--name-->
                    <div class="input-box">
                        <img src="./../assets/icons/user.svg" alt="user" />
                        <label for="name">Name:</label>

                        <input
                            type="text"
                            name="name"
                            class="input-field"
                            placeholder="Name"
                            id="name-field" />
                    </div>
                    <!--email-->
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
                    <!--Password field-->
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
                    <!--confirm Password -->
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
                            id="show-password" />
                    </div>
                    <!--Date of Birth -->
                    <div class="input-box">
                        <img src="./../assets/icons/calendar.svg" alt="calendar" />
                        <label for="DOB">Date of Birth:</label>
                        <!-- TODO: remove date picker -->
                        <input
                            type="date"
                            name="DOB"
                            class="input-field date-input"
                            placeholder="Date of Birth"
                            id="date-field" />
                    </div>

                </div>

                <input type="submit" value="Sign In" class="button" name="login" />
            </form>
        </div>
    </section>
</body>
<script>
    let passwordIcon = document.getElementById("show-password");
    let passwordInput = document.getElementById("password-field");

    passwordIcon.onclick = function() {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordIcon.src = "assets/icons/eye.svg";
            passwordIcon.alt = "show password";
        } else {
            passwordInput.type = "password";
            passwordIcon.src = "assets/icons/eye-off.svg";
            passwordIcon.alt = "hide password";
        }
        console.log("Password visibility toggled");
    };
</script>

</html>