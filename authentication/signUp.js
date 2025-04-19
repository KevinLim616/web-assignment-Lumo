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
  console.log("Password visibility toggled");
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
  console.log("Toggled");
};

//input validation

const usernameInput = document.getElementById("name-field");
const emailInput = document.getElementById("mail-field");
const dateOfBirthInput = document.getElementById("date-input");

const isValidEmail = (email) => {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(String(email).toLowerCase());
};

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

const setError = (element, message) => {
  const inputBox = element.parentElement; //input-box
  const inputControl = inputBox.parentElement; //input-box -> input-control
  const errorMessage = inputControl.querySelector(".error-message");
  errorMessage.innerText = message;

  inputBox.classList.add("error");
  // inputControl.classList.remove("success");
};

const setSuccess = (element) => {
  const inputBox = element.parentElement;
  const inputControl = inputBox.parentElement; //input-box -> input-control
  const errorMessage = inputControl.querySelector(".error-message");
  errorMessage.innerText = "";
  // inputControl.classList.add("success");
  inputBox.classList.remove("error");
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
    return true;
  }
};

const form = document.getElementById("sign-up-form");
form.addEventListener("submit", (event) => {
  const isValidForm = validateInput();
  console.log(isValidForm);

  if (!isValidForm) {
    event.preventDefault(); // Prevent form submission
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

// check all the validations and submit if no problems
const validateInput = () => {
  const validUsername = validateUsername();
  const validEmail = validateEmail();
  const validPassword = validatePassword();
  const validConfirmPassword = validateConfirmPassword();
  const validBirthDate = validateBirthDate();
  if (
    validUsername &&
    validEmail &&
    validPassword &&
    validConfirmPassword &&
    validBirthDate
  ) {
    return true;
  }
};
