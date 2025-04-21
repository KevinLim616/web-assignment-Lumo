<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="global.css" />
  <link rel="stylesheet" href="./style/sign_in.css" />
  <link rel="stylesheet" href="./style//form.css">
  <script src="authentication/signIn.js" defer type="module"></script>
  <title>Document</title>
</head>

<?php
//import login functions from login_functions.php
include("authentication/login_functions.php");
include __DIR__ . ("/include/db/database.php");
// TODO : add sessions

?>

<body>
  <div class="container">

    <section class="left">

      <div style="display: flex;">
        <img src="assets/img/LOGO.svg" alt="logo" width="150px" />
      </div>
      <img src="./assets/img/signinpage_mascot.png" alt="sign in mascot" class="sign-in-mascot">
    </section>
    <section class="right">
      <div class="form-container">
        <h1>Sign In</h1>
        <form
          action="index.php"
          method="post"
          class="sign-in-form"
          id="sign-in-form">
          <div class="inputs-container">
            <!--email field-->
            <div class="input-control">
              <div class="input-box">
                <img src="assets/icons/mail.svg" alt="email" />
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
            <!--password field-->
            <div class="input-control">
              <div class="input-box">
                <img src="assets/icons/lock.svg" alt="password" />
                <label for="password">Password:</label>

                <input
                  type="password"
                  name="password"
                  class="input-field"
                  placeholder="Password"
                  id="password-field" />
                <img
                  src="assets/icons/eye-off.svg"
                  alt="eyes-off"
                  class="eye"
                  id="show-password" />
              </div>
              <div class="error-message"></div>
            </div>
            <!--TODO-->
            <!-- warning message-->
            <a href="#" class="link">forgot password?</a>
          </div>

          <div class="sign-in-action">
            <input type="submit" value="Sign In" class="button" name="login" />
            <p class="link">
              Don't have an account? <a href="#">Sign up now!</a>
            </p>
          </div>
        </form>
      </div>
    </section>
  </div>
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

<?php

if (isset($_POST["login"])) {
  login($_POST["email"], $_POST["password"]);
}
//change to pdo method
mysqli_close($conn);
?>