let passwordIcon = document.getElementById("show-password");
let passwordInput = document.getElementById("password-field");
import {
  setError,
  setSuccess,
  isValidEmail,
  validateInput,
} from "../utils/utils.js";
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

//setError, setSuccess, isValidEmail
const emailInput = document.getElementById("mail-field");
const validateEmail = () => {
  const emailValue = emailInput.value.trim();

  if (emailValue === "") {
    setError(emailInput, "Please enter your email");
    return false;
  } else if (!isValidEmail(emailValue)) {
    setError(emailInput, "Please enter a valid email");
    return false;
  } else {
    setSuccess(emailInput);
    return true;
  }
};

const validatePassword = () => {
  const passwordValue = passwordInput.value.trim();
  if (passwordValue === "") {
    setError(passwordInput, "Please enter your password");
    return false;
  } else {
    setSuccess(passwordInput);
    return true;
  }
};

emailInput.addEventListener("blur", validateEmail);
passwordInput.addEventListener("blur", validatePassword);
const form = document.getElementById("sign-in-form");

form.addEventListener("submit", (event) => {
  const isValidForm = validateInput([validateEmail, validatePassword]);
  console.log(isValidForm);
  if (!isValidForm) {
    console.log("Invalid form");
    event.preventDefault();
  }
});
