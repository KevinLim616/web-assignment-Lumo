import { svgIcons } from "../utils/svgIcons.js";

document.addEventListener("DOMContentLoaded", () => {
  const notifications = document.querySelectorAll(".notification");
  const notificationDetails = document.querySelector(".notification-details");
  const titleElement = document.querySelector(".notification-title h3");
  const dateElement = document.querySelector(".notification-title h6");
  const descriptionElement = document.querySelector(
    ".notification-description p"
  );
  if (!notifications.length) {
    console.error("Error: No .notification elements found.");
    return;
  }
  if (!notificationDetails) {
    console.error("Error: .notification-details element not found.");
    return;
  }
  if (!titleElement) {
    console.error("Error: .notification-title h3 element not found.");
    return;
  }
  if (!dateElement) {
    console.error("Error: .notification-date element not found.");
    return;
  }
  if (!descriptionElement) {
    console.error("Error: .notification-description p element not found.");
    return;
  }

  // Create and add arrow icon for notification details drawer
  const arrowIcon = document.createElement("div");
  arrowIcon.className = "arrow-icon";
  arrowIcon.appendChild(svgIcons.arrowLeft());
  notificationDetails.insertBefore(arrowIcon, notificationDetails.firstChild);

  notifications.forEach((notification) => {
    notification.addEventListener("click", () => {
      const title = notification.getAttribute("data-title");
      const message = notification.getAttribute("data-message");
      const date = notification.getAttribute("data-date");
      titleElement.textContent = title || "No title";
      dateElement.textContent = date || "No date";
      descriptionElement.textContent = message || "No message";

      notificationDetails.classList.add("active");
    });
  });
  // Close drawer when arrow icon is clicked
  arrowIcon.addEventListener("click", (event) => {
    event.stopPropagation(); // Prevent click from bubbling up
    if (notificationDetails.classList.contains("active")) {
      notificationDetails.classList.remove("active");
    }
  });
});
