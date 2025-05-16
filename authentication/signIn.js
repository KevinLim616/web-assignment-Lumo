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

form.addEventListener("submit", async (event) => {
  const isValidForm = validateInput([validateEmail, validatePassword]);
  console.log(isValidForm);
  if (!isValidForm) {
    console.log("Invalid form");
    event.preventDefault();
  }
  const email = emailInput.value.trim();
  const password = passwordInput.value.trim();

  try {
    const response = await fetch("index.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        email,
        password,
        login: "true",
      }),
    });

    const data = await response.text();
    if (data.includes("user logged in")) {
      // MODIFIED: Updated redirect path
      window.location.href = "user/dashboard.php";
    } else {
      setError(emailInput, "Invalid email or password");
      setError(passwordInput, "Invalid email or password");
    }
  } catch (error) {
    console.error("Login error:", error);
    setError(emailInput, "An error occurred. Please try again.");
  }
});

// TODO: handleSignIn function
