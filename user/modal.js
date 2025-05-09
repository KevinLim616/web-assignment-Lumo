import { svgIcons } from "../utils/svgIcons.js";
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("popupModal");
  const diaryModal = document.getElementById("diary-modal");
  const createBtn = document.getElementById("createBtn");
  const closeBtn = document.querySelector(".close-btn");
  const closeDiaryBtn = document.getElementById("close-diary");

  createBtn.addEventListener("click", () => {
    modal.style.display = "block";
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    document.getElementById("create-task-form").reset();
  });

  closeDiaryBtn.innerHTML = "";
  closeDiaryBtn.appendChild(svgIcons["closeIcon"]("diary-close"));

  closeDiaryBtn.addEventListener("click", () => {
    diaryModal.style.display = "none";
    document.getElementById("diary-content-form").reset();
  });
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
      document.getElementById("create-task-form").reset();
    }
    if (e.target === diaryModal) {
      diaryModal.style.display = "none";
      document.getElementById("diary-content-form").reset();
    }
  });
});
