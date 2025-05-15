// Remove any existing popovers before creating a new one
function removeExistingPopovers() {
  document.querySelectorAll(".popover").forEach((p) => p.remove());
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
    // Add your send message logic here
    hidePopover();
  });

  popover.querySelector(".delete-user").addEventListener("click", (e) => {
    e.preventDefault();
    if (confirm("Are you sure you want to delete this user?")) {
      console.log("Delete user:", userId);
      // Add your delete user logic here
      hidePopover();
    }
  });
}

// Add event listener to action buttons
document.addEventListener("click", (event) => {
  if (event.target.closest(".action-btn")) {
    console.log("Button clicked");
    showPopover(event);
  }
});
