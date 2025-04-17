let passwordIcon = document.getElementById("show-password");
let passwordInput = document.getElementById("password-field");

passwordIcon.onclick = function () {
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
