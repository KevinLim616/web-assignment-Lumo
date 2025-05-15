import { svgIcons } from "./../utils/svgIcons.js";

const announceBtn = document.getElementById("post-announcement");

announceBtn.addEventListener("click", (event) => {
  event.preventDefault();
  showModal();
});

const showModal = () => {
  // Remove existing modal if any
  document.querySelectorAll(".announcement-modal").forEach((m) => m.remove());

  const modal = document.createElement("div");
  modal.className = "announcement-modal";
  modal.id = "announcement-modal";

  // Convert SVG element to string using outerHTML
  const closeIconString = svgIcons["closeIcon"]("announce-close").outerHTML;

  modal.innerHTML = `
    <div class="announcement-modal-container">
      <form id="announcement-content-form">
        <div class="announcement-modal-header">
          <label for="announcement-title">Diary Title</label>
          <input type="text" id="announcement-title" name="announcement-title" placeholder="Title">
          <span id="close-modal">${closeIconString}</span>
        </div>
        <div class="announcement-modal-content">
          <label for="announcement-content" id="announcement-content-label">Message</label>
          <textarea name="announcement-content" id="announcement-content" placeholder="Message...."></textarea>
        </div>
        <div class="submit-container">
          <label for="post-announcement">Post</label>
          <input type="submit" value="Post" id="post-announcement">
        </div>
      </form>
    </div>
  `;

  document.body.appendChild(modal);
  modal.style.display = "block";

  // Handle form submission
  const form = modal.querySelector("#announcement-content-form");
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const title = modal.querySelector("#announcement-title").value.trim();
    const content = modal.querySelector("#announcement-content").value.trim();

    if (title === "" || content === "") {
      alert("Please fill in both the title and message");
      return;
    }

    // Send announcement to backend
    fetch("post_announcement.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `title=${encodeURIComponent(title)}&content=${encodeURIComponent(
        content
      )}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Announcement posted successfully");
          alert("Announcement posted successfully");
          modal.remove();
          // Optionally refresh or update UI
        } else {
          console.error("Error:", data.error);
          alert("Failed to post announcement: " + data.error);
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("An error occurred while posting the announcement");
      });
  });

  // Handle close icon
  modal.querySelector("#close-modal").addEventListener("click", () => {
    modal.remove();
  });

  // Close modal when clicking outside the modal container
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.remove();
    }
  });
};
