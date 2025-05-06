document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("popupModal");
  const createBtn = document.getElementById("createBtn");
  const closeBtn = document.querySelector(".close-btn");

  createBtn.addEventListener("click", () => {
    modal.style.display = "block";
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    document.getElementById("create-task-form").reset();
  });

  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      document.getElementById("create-task-form").reset();
    }
  });
});
