import { svgIcons } from "../utils/svgIcons.js";
// Remove any existing popovers before creating a new one
function removeExistingPopovers() {
  document.querySelectorAll(".popover").forEach((p) => p.remove());
}

function createMessageModal(userId) {
  // Remove existing modal if any
  document.querySelectorAll(".message-modal").forEach((m) => m.remove());

  const modal = document.createElement("div");
  modal.className = "message-modal";
  modal.id = "message-modal";

  // Convert SVG element to string
  const closeIconString = svgIcons["closeIcon"]("message-close").outerHTML;

  modal.innerHTML = `
    <div class="announcement-modal-container">
      <form id="message-content-form">
        <div class="announcement-modal-header">
          <label for="message-title">Message Title</label>
          <input type="text" id="message-title" name="message-title" placeholder="Title">
          <span id="close-modal">${closeIconString}</span>
        </div>
        <div class="announcement-modal-content">
          <label for="message-content" id="message-content-label">Message</label>
          <textarea name="message-content" id="message-content" placeholder="Message...."></textarea>
        </div>
        <div class="submit-container">
          <label for="send-message">Send</label>
          <input type="submit" value="Send" id="send-message">
        </div>
      </form>
    </div>
  `;

  document.body.appendChild(modal);
  modal.style.display = "block";

  // Handle form submission
  const form = modal.querySelector("#message-content-form");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const title = modal.querySelector("#message-title").value.trim();
    const content = modal.querySelector("#message-content").value.trim();

    if (title === "" || content === "") {
      alert("Please fill in both the title and message");
      return;
    }
    console.log("Sending message with userId:", userId); // Debug log
    fetch("post_announcement.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `title=${encodeURIComponent(title)}&content=${encodeURIComponent(
        content
      )}&user_id=${encodeURIComponent(userId)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Message sent successfully to user:", userId);
          alert("Message sent successfully");
          modal.remove();
        } else {
          console.error("Error:", data.error);
          alert("Failed to send message: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("An error occurred while sending the message");
      });
  });

  // Handle close icon
  modal.querySelector("#close-modal").addEventListener("click", () => {
    modal.remove();
  });

  // Close modal when clicking outside
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.remove();
    }
  });
}

function createConfirmationModal(userId) {
  // Ensure userId is numeric
  const numericUserId = parseInt(userId, 10);
  if (isNaN(numericUserId)) {
    console.error("Invalid userId:", userId);
    alert("Invalid user ID");
    return;
  }
  // Remove existing modal if any
  document.querySelectorAll(".confirmation-modal").forEach((m) => m.remove());

  const modal = document.createElement("div");
  modal.className = "confirmation-modal";
  modal.id = "delete-modal";
  modal.innerHTML = `
    <div class="confirmation-modal-container">
      <div>
        <h2>Delete User?</h2>
        <p>This action can't be undone</p>
      </div>
      <div class='btn-container'>
        <button id="cancel-btn">Cancel</button>
        <button id="delete-btn" data-user-id="${userId}">Delete</button>
      </div>
    </div>
  `;
  document.body.appendChild(modal);

  // Show modal by adding a class or style (assuming CSS handles visibility)
  modal.style.display = "block";

  // Handle cancel button
  modal.querySelector("#cancel-btn").addEventListener("click", () => {
    modal.remove();
  });

  // Handle delete confirmation
  modal.querySelector("#delete-btn").addEventListener("click", () => {
    fetch("./user_control.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `user_id=${encodeURIComponent(userId)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("User deleted successfully");
          modal.remove();
          location.reload();
        } else {
          console.error("Error:", data.error);
          alert("Failed to delete user: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("An error occurred while deleting the user");
      });
  });
}
function showPopover(event) {
  removeExistingPopovers(); // Ensure only one popover is open at a time

  const button = event.target.closest(".action-btn");
  if (!button) return;

  const userId = button.getAttribute("data-dropdown-id");
  const popover = document.createElement("div");
  popover.className = "popover";
  popover.innerHTML = `
            <ul>
                <li><a href="#" class="send-message" data-user-id="${userId}">Send message</a></li>
                <li><a href="#" class="delete-user" data-user-id="${userId}">Delete User</a></li>
            </ul>
  `;
  const rect = button.getBoundingClientRect();
  const scrollY = window.scrollY || window.pageYOffset;
  const scrollX = window.scrollX || window.pageXOffset;

  // Position the popover
  popover.style.position = "absolute";
  popover.style.top = scrollY + rect.bottom + 5 + "px"; // Add 5px offset below the button
  popover.style.left = scrollX + rect.left + "px";
  //   popover.style.zIndex = "10000"; // Increase z-index to ensure visibility
  //   popover.style.minWidth = "150px"; // Ensure it has a visible size
  //   popover.style.minHeight = "fit-content";
  //   popover.style.display = "block"; // Explicitly set display

  document.body.appendChild(popover);
  console.log("Popover created at", popover.style.top, popover.style.left);

  // Log computed styles to debug visibility
  const computedStyle = window.getComputedStyle(popover);
  console.log("Computed styles:", {
    display: computedStyle.display,
    visibility: computedStyle.visibility,
    opacity: computedStyle.opacity,
    position: computedStyle.position,
    top: computedStyle.top,
    left: computedStyle.left,
  });

  // Ensure popover stays within viewport
  const popoverRect = popover.getBoundingClientRect();
  if (popoverRect.right > window.innerWidth) {
    popover.style.left =
      scrollX + rect.left - (popoverRect.right - window.innerWidth) - 10 + "px";
  }
  if (popoverRect.bottom > window.innerHeight) {
    popover.style.top = scrollY + rect.top - popoverRect.height - 5 + "px"; // Move above button
  }

  function hidePopover(e) {
    // Only hide if the click is outside the popover and button
    if (!popover.contains(e.target) && !button.contains(e.target)) {
      if (popover.parentNode) {
        popover.parentNode.removeChild(popover);
      }
      document.removeEventListener("click", hidePopover);
    }
  }
  // Close popover when clicking outside
  function hidePopover() {
    if (popover.parentNode) {
      popover.parentNode.removeChild(popover);
    }
    document.removeEventListener("click", hidePopover);
  }
  // Add the click listener for closing after a slight delay to avoid immediate closure
  setTimeout(() => document.addEventListener("click", hidePopover), 0);

  // Prevent popover from closing when clicking inside
  popover.addEventListener("click", (e) => e.stopPropagation());

  // Handle send message and delete user actions
  popover.querySelector(".send-message").addEventListener("click", (e) => {
    e.preventDefault();
    console.log("Send message to user:", userId);
    createMessageModal(userId);
    hidePopover();
  });

  popover.querySelector(".delete-user").addEventListener("click", (e) => {
    e.preventDefault();
    createConfirmationModal(userId);
    hidePopover();
    // if (confirm("Are you sure you want to delete this user?")) {
    //   console.log("Delete user:", userId);
    //   // Add your delete user logic here
    //   hidePopover();
    // }
  });
}

// Add event listener to action buttons
document.addEventListener("click", (event) => {
  if (event.target.closest(".action-btn")) {
    console.log("Button clicked");
    showPopover(event);
  }
});
