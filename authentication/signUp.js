const passwordIcon = document.getElementById("show-password");
const passwordInput = document.getElementById("password-field");
const confirmPasswordInput = document.getElementById("confirm-password-field");
const confirmPasswordIcon = document.getElementById("confirm-show-password");

passwordIcon.onclick = () => {
  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    passwordIcon.src = "./../assets/icons/eye.svg";
    passwordIcon.alt = "show password";
  } else {
    passwordInput.type = "password";
    passwordIcon.src = "./../assets/icons/eye-off.svg";
    passwordIcon.alt = "hide password";
  }
};

confirmPasswordIcon.onclick = () => {
  if (confirmPasswordInput.type === "password") {
    confirmPasswordInput.type = "text";
    confirmPasswordIcon.src = "./../assets/icons/eye.svg";
    confirmPasswordIcon.alt = "show password";
  } else {
    confirmPasswordInput.type = "password";
    confirmPasswordIcon.src = "./../assets/icons/eye-off.svg";
    confirmPasswordInput.alt = "hide password";
  }
};

//input validation

const usernameInput = document.getElementById("name-field");
const emailInput = document.getElementById("mail-field");
const dateOfBirthInput = document.getElementById("date-input");

import {
  setError,
  setSuccess,
  validateInput,
  isValidEmail,
} from "../utils/utils.js";

const isStrongPassword = (password) => {
  const regex = /^(?=.*[A-Z])(?=.*\d).+$/;
  return regex.test(password);
};

const isValidAge = (dateOfBirth) => {
  const today = new Date();
  const birthDate = new Date(dateOfBirth);
  const age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (
    monthDiff < 0 ||
    (monthDiff === 0 && today.getDate() < birthDate.getDate())
  ) {
    return age - 1;
  }
  return age;
};

const validateUsername = () => {
  const usernameValue = usernameInput.value.trim();
  if (usernameValue === "") {
    setError(usernameInput, "Username cannot be blank");
    return false;
  } else if (usernameValue.length < 8) {
    setError(usernameInput, "Username must be at least 8 characters");
    return false;
  } else if (usernameValue.length > 30) {
    setError(usernameInput, "Username must be less than 30 characters");
    return false;
  } else {
    setSuccess(usernameInput);
    return true;
  }
};

const validateEmail = () => {
  const emailValue = emailInput.value.trim();
  if (emailValue === "") {
    setError(emailInput, "Email cannot be empty");
    return false;
  } else if (!isValidEmail(emailValue)) {
    setError(emailInput, "Please enter a valid emal");
    return false;
  } else {
    setSuccess(emailInput);
    return true;
  }
};

const validatePassword = () => {
  const passwordValue = passwordInput.value.trim();
  if (passwordValue === "") {
    setError(passwordInput, "Password cannot be empty");
    return false;
  } else if (passwordValue.length < 8) {
    setError(passwordInput, "Password must be at least 8 characters");
    return false;
  } else if (passwordValue.length > 20) {
    setError(passwordInput, "Password must be less than 20 characters");
    return false;
  } else if (!isStrongPassword(passwordValue)) {
    setError(
      passwordInput,
      "Password must contain at least one uppercase and one number"
    );
    return false;
  } else {
    setSuccess(passwordInput);
    return true;
  }
};

const validateConfirmPassword = () => {
  const confirmPasswordValue = confirmPasswordInput.value.trim();
  const passwordValue = passwordInput.value.trim();
  if (confirmPasswordValue === "") {
    setError(confirmPasswordInput, "Please confirm your password");
    return false;
  } else if (confirmPasswordValue !== passwordValue) {
    setError(confirmPasswordInput, "Password does not match");
    return false;
  } else {
    setSuccess(confirmPasswordInput);
    return true;
  }
};

const validateBirthDate = () => {
  const dateOfBirthValue = dateOfBirthInput.value.trim();
  if (dateOfBirthValue === "") {
    setError(dateOfBirthInput, "Please select your date of birth");
    return false;
  } else if (isValidAge(dateOfBirthValue) < 13) {
    setError(dateOfBirthInput, "You must be at least 13 years old");
    return false;
  } else if (isValidAge(dateOfBirthValue) > 120) {
    setError(dateOfBirthInput, "You are too old to sign up");
    return false;
  } else {
    setSuccess(dateOfBirthInput);
    console.log(dateOfBirthValue);
    return true;
  }
};

const form = document.getElementById("sign-up-form");
form.addEventListener("submit", async (event) => {
  event.preventDefault(); // Prevent form submission

  const isValidForm = validateInput([
    validateUsername,
    validateEmail,
    validatePassword,
    validateConfirmPassword,
    validateBirthDate,
  ]);
  console.log("isValidForm:", isValidForm);

  if (isValidForm) {
    const usernameValue = usernameInput.value.trim();
    const emailValue = emailInput.value.trim();
    const passwordValue = passwordInput.value.trim();
    const dateOfBirthValue = dateOfBirthInput.value.trim();
    const result = await handleSignUp(
      usernameValue,
      emailValue,
      passwordValue,
      dateOfBirthValue
    );
    // if (result === "user created") {
    //   // form.submit();
    //   window.location.href = "../user/dashboard.php";
    // }
  }

  // if (isValidForm) {
  //   form.submit(); dk why not submitting the data to database
  //   console.log("submitted");
  // }
});

// listen for the action and trigger the function
usernameInput.addEventListener("blur", validateUsername);
emailInput.addEventListener("blur", validateEmail);
passwordInput.addEventListener("blur", validatePassword);
confirmPasswordInput.addEventListener("blur", validateConfirmPassword);
dateOfBirthInput.addEventListener("blur", validateBirthDate);

const handleSignUp = async (username, email, password, dateOfBirth) => {
  try {
    const response = await fetch("../public/sign_up.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        mode: "signup",
        username,
        email,
        password,
        date_of_birth: dateOfBirth,
      }),
    });

    const data = await response.text();
    console.log("Server response:", data);

    if (data === "email_exists") {
      setError(emailInput, "Email is already registered");
      return false;
    } else if (data === "success") {
      setSuccess(emailInput);
      window.location.href = "../user/dashboard.php";

      return true;
    } else {
      console.log("Unexpected response:", data);
      return false;
    }
  } catch (error) {
    console.error("Error checking email:", error);
    return false;
  }
};
