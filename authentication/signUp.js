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
const form = document.getElementById("sign-up-form");
const username = document.getElementById("name-field");
const email = document.getElementById("mail-field");
const dateOfBirth = document.getElementById("date-input");
const password = passwordInput;
const confirmPassword = confirmPasswordInput;

form.addEventListener("submit", (event) => {
  event.preventDefault(); // Prevent form submission

  validateInput();
});

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
  const inputControl = element.parentElement;
  const errorMessage = inputControl.querySelector(".error-message");
  errorMessage.innerText = "";
  // inputControl.classList.add("success");
  inputControl.classList.remove("error");
};

const validateInput = () => {
  const usernameValue = username.value.trim();
  const emailValue = email.value.trim();
  const passwordValue = password.value.trim();
  const confirmPasswordValue = confirmPassword.value.trim();
  const dateOfBirthValue = dateOfBirth.value.trim();

  switch (usernameValue) {
    case "":
      setError(username, "Username cannot be blank");
      break;
    case usernameValue.length < 8:
      setError(username, "Username must be at least 8 characters");
      break;
    case usernameValue.length > 30:
      setError(username, "Username must be less than 30 characters");
      break;
    default:
      setSuccess(username);
  }
  switch (emailValue) {
    case "":
      setError(email, "Email cannot be empty");
      break;
    case !isValidEmail(emailValue):
      setError(email, "Please enter a valid email");
      break;
    default:
      setSuccess(email);
  }
  switch (passwordValue) {
    case "":
      setError(password, "Password cannot be empty");
      break;
    case passwordValue.length < 8:
      setError(password, "Password must be at least 8 characters");
      break;
    case passwordValue.length > 20:
      setError(password, "Password must be less than 20 characters");
      break;
    case !isStrongPassword(passwordValue):
      setError(
        password,
        "Password must contain at least one uppercase and one number"
      );
      break;
    default:
      setSuccess(password);
  }
  switch (confirmPasswordValue) {
    case "":
      setError(confirmPassword, "Please confirm your password");
      break;
    case confirmPasswordValue !== passwordValue:
      setError(confirmPassword, "Password does not match");
      break;
    default:
      setSuccess(confirmPassword);
  }
  switch (dateOfBirthValue) {
    case "":
      setError(dateOfBirth, "This input field cannot be empty");
      break;
    case isValidAge(dateOfBirthValue) < 13:
      setError(dateOfBirth, "You must be at least 13 years old");
      break;
    case isValidAge(dateOfBirthValue) > 120:
      setError(dateOfBirth, "You are too old to sign up");
  }
};
