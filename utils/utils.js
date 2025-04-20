export const isValidEmail = (email) => {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(String(email).toLowerCase());
};

export const setError = (element, message) => {
  const inputBox = element.parentElement; //input-box
  const inputControl = inputBox.parentElement; //input-box -> input-control
  const errorMessage = inputControl.querySelector(".error-message");
  errorMessage.innerText = message;

  inputBox.classList.add("error");
  // inputControl.classList.remove("success");
};

export const setSuccess = (element) => {
  const inputBox = element.parentElement;
  const inputControl = inputBox.parentElement; //input-box -> input-control
  const errorMessage = inputControl.querySelector(".error-message");
  errorMessage.innerText = "";
  // inputControl.classList.add("success");
  inputBox.classList.remove("error");
};

export const validateInput = (validators = []) => {
  let isValid = true;

  for (const fn of validators) {
    const result = fn();
    if (!result) {
      isValid = false;
    }
  }
  return isValid;
};
