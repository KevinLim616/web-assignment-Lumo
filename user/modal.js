import { svgIcons } from "../utils/svgIcons.js";
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("popupModal");
  const diaryModal = document.getElementById("diary-modal");
  const createBtn = document.getElementById("createBtn");
  const closeBtn = document.querySelector(".close-btn");
  const closeDiaryBtn = document.getElementById("close-diary");

  const toolbarImage = document.getElementById("toolbar-image");
  const toolbarBold = document.getElementById("toolbar-bold");
  const toolbarItalic = document.getElementById("toolbar-italic");
  const toolbarUnderline = document.getElementById("toolbar-underline");
  const toolbarLock = document.getElementById("toolbar-lock");
  const diaryContentHidden = document.getElementById("diary-content-hidden");
  const diaryContent = document.getElementById("diary-content");

  const diaryForm = document.getElementById("diary-content-form");

  toolbarImage.appendChild(
    svgIcons["imageIcon"]("toolbar-icon", "toolbar-image-icon")
  );
  toolbarBold.appendChild(
    svgIcons["boldIcon"]("toolbar-icon", "toolbar-bold-icon")
  );
  toolbarItalic.appendChild(
    svgIcons["italicIcon"]("toolbar-icon", "toolbar-italic-icon")
  );
  toolbarUnderline.appendChild(
    svgIcons["underlineIcon"]("toolbar-icon", "toolbar-underline-icon")
  );
  toolbarLock.appendChild(
    svgIcons["lockIcon"]("toolbar-icon", "toolbar-lock-icon")
  );

  // // Initialize Quill with theme
  // const quill = new Quill("#diary-content", {
  //   placeholder: "Content...",

  //   modules: {
  //     toolbar: false, // Use custom toolbar
  //   },
  // });

  // // Toolbar Functions using Quill
  // toolbarImage.addEventListener("click", () => {
  //   const fileInput = document.createElement("input");
  //   fileInput.type = "file";
  //   fileInput.accept = "image/*";
  //   fileInput.onchange = (e) => {
  //     const file = e.target.files[0];
  //     if (file) {
  //       console.log("Image selected:", file.name);
  //       const range = quill.getSelection();
  //       if (range) {
  //         const imageUrl = URL.createObjectURL(file);
  //         quill.insertEmbed(range.index, "image", imageUrl);
  //       }
  //     }
  //   };
  //   fileInput.click();
  // });

  // toolbarBold.addEventListener("click", () => {
  //   quill.format("bold", !quill.getFormat().bold);
  //   quill.focus();
  // });

  // toolbarItalic.addEventListener("click", () => {
  //   quill.format("italic", !quill.getFormat().italic);
  //   quill.focus();
  // });

  // toolbarUnderline.addEventListener("click", () => {
  //   quill.format("underline", !quill.getFormat().underline);
  //   quill.focus();
  // });

  // let isLocked = false;
  // toolbarLock.addEventListener("click", () => {
  //   isLocked = !isLocked;
  //   if (isLocked) {
  //     quill.disable();
  //     diaryModal.classList.add("locked");
  //     toolbarLock.querySelector("svg").style.stroke = "#dc3545";
  //   } else {
  //     quill.enable();
  //     diaryModal.classList.remove("locked");
  //     toolbarLock.querySelector("svg").style.stroke = "#6c757d";
  //   }
  // });

  // // Sync Quill content with hidden input for form submission
  // quill.on("text-change", () => {
  //   diaryContentHidden.value = quill.root.innerHTML;
  // });

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
      // quill.setText("");
    }
  });

  let currentSelectedDate = null;
  // Expose showDiaryModal function for diary.js
  window.showDiaryModal = (selectedDate) => {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    currentSelectedDate = selectedDate; //store globally
    if (selectedDate) {
      const selected = new Date(selectedDate);
      selected.setHours(0, 0, 0, 0);
      if (selected > today) {
        console.log(`Cannot open diary for future date: ${selectedDate}`);
        alert("Warning: cannot open diary for future date.");
        return;
      }
      console.log("Diary modal triggered for date:", selectedDate);
    } else {
      console.warn(
        "No date provided to showDiaryModal; using current date:",
        currentSelectedDate
      );
    }
    diaryModal.style.display = "block";
  };

  // Handle diary form submission
  diaryForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const title = document.getElementById("diary-title").value.trim();
    const content = diaryContent.value.trim();
    const date = currentSelectedDate
      ? String(currentSelectedDate)
      : new Date().toISOString().split("T")[0]; // Ensure date is a string
    console.log("Form values:", { title, content, date });

    if (!title || !content || !date) {
      alert("Title, content, and date are required.");
      return;
    }

    const formData = new FormData();
    formData.append("title", title);
    formData.append("content", content);
    formData.append("date", date);

    for (let [key, value] of formData.entries()) {
      console.log(`FormData ${key}: ${value}`);
    }

    console.log("Sending formData to save_mood.php");

    fetch("../diary/save_diary.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "success") {
          alert("Diary saved successfully!");
          diaryModal.style.display = "none";
          document.getElementById("diary-content-form").reset();
          // quill.setText("");
        } else {
          alert("Failed to save diary: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error saving diary:", error);
        alert("An error occurred while saving the diary.");
      });
  });
});
