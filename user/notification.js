document.addEventListener("DOMContentLoaded", () => {
  const notifications = document.querySelectorAll(".notification");
  const notificationDetails = document.querySelector(".notification-details");
  const titleElement = document.querySelector(".notification-title h3");
  const dateElement = document.querySelector(".notification-title h6");
  const descriptionElement = document.querySelector(
    ".notification-description p"
  );

  notifications.forEach((notification) => {
    notification.addEventListener("click", () => {
      const title = notification.getAttribute("data-title");
      const message = notification.getAttribute("data-message");
      const date = notification.getAttribute("data-date");
      console.log(title, date);
      titleElement.textContent = title;
      dateElement.textContent = date;
      descriptionElement.textContent = message;

      notificationDetails.classList.add("active");
    });
  });
});
