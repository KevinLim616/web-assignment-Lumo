let passwordIcon = document.getElementById("show-password");
let passwordInput = document.getElementById("password-field");
let confirmPasswordInput = document.getElementById("confirm-password-field");
let confirmPasswordIcon = document.getElementById("confirm-show-password");

passwordIcon.onclick = function () {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    passwordIcon.src = "./../assets/icons/eye.svg";
    passwordIcon.alt = "show password";
  } else {
    passwordInput.type = "password";
    passwordIcon.src = "./../assets/icons/eye-off.svg";
    passwordIcon.alt = "hide password";
  }
  console.log("Password visibility toggled");
};

confirmPasswordIcon.onclick = function () {
  if (confirmPasswordInput.type === "password") {
    confirmPasswordInput.type = "text";
    confirmPasswordIcon.src = "./../assets/icons/eye.svg";
    confirmPasswordIcon.alt = "show password";
  } else {
    confirmPasswordInput.type = "password";
    confirmPasswordIcon.src = "./../assets/icons/eye-off.svg";
    confirmPasswordInput.alt = "hide password";
  }
  console.log("Toggled");
};
