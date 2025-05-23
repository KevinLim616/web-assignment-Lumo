import { showDiaryModal } from "../user/modal.js";
window.moods = {}; // Global store for mood data

// Fetch moods for a given year and month
const fetchMoods = async (year, month) => {
  try {
    const response = await fetch(
      `../diary/get_moods.php?year=${year}&month=${month + 1}`,
      {
        credentials: "same-origin",
      }
    );
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const data = await response.json();
    if (data.status === "success") {
      window.moods = data.moods; // Store moods globally
      return data.moods; // Return for immediate use
    } else {
      console.error("Failed to fetch moods:", data.message);
      window.moods = {};
      return {};
    }
  } catch (error) {
    console.error("Error fetching moods:", error);
    window.moods = {};
    return {};
  }
};

function getLocalDateString(date = new Date()) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const day = date.getDate().toString().padStart(2, "0");
  return `${year}-${month}-${day}`;
}

let currentSelectedDate = getLocalDateString(new Date()); //default to today

export function handleMoodClick(selectedDate) {
  if (selectedDate) {
    currentSelectedDate = selectedDate; // Update the global selected date
    console.log("Updated selected date to:", currentSelectedDate);
  } else {
    console.warn(
      "No selectedDate provided to handleMoodClick, using current:",
      currentSelectedDate
    );
  }

  const moodIcons = document.querySelectorAll(
    ".mood-icons-container > span[id]"
  );

  // Debug: Log the NodeList and its contents
  console.log("moodIcons NodeList:", moodIcons);
  console.log("moodIcons length:", moodIcons.length);
  moodIcons.forEach((icon, index) => {
    console.log(`Icon ${index} id: ${icon.id}`); // Log only the id
  });

  if (moodIcons.length !== 5) {
    console.warn("Expected 5 mood icons, found:", moodIcons.length);
  }

  // Remove existing listeners to prevent duplicates (optional, depending on your setup)
  moodIcons.forEach((icon) => {
    const newIcon = icon.cloneNode(true);
    icon.parentNode.replaceChild(newIcon, icon);
  });

  // Re-query after cloning to attach new listeners
  const newMoodIcons = document.querySelectorAll(
    ".mood-icons-container > span[id]"
  );

  const today = new Date();
  const selected = new Date(currentSelectedDate);

  // Set time to midnight for comparing only the dates
  today.setHours(0, 0, 0, 0);
  selected.setHours(0, 0, 0, 0);

  newMoodIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      console.log("Mood icon clicked, date:", currentSelectedDate);
      if (selected > today) {
        console.log(
          `Cannot change mood for future date: ${getLocalDateString(
            currentSelectedDate
          )}`
        );
        alert("Warning: cannot set a mood for future date.");
        return;
      }

      const mood = icon.id; // Use id as mood value (e.g., "happy")
      const dateString = getLocalDateString(new Date(currentSelectedDate)); // e.g., "2025-05-10"

      const formData = new FormData();
      formData.append("date", dateString);
      formData.append("mood", mood);

      // Log FormData entries
      for (let [key, value] of formData.entries()) {
        console.log(`FormData ${key}: ${value}`);
      }

      fetch("../diary/save_mood.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => {
          console.log(`Response status: ${res.status}`);
          console.log(`Response headers:`, [...res.headers.entries()]);
          if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
          }
          return res.json();
        })
        .then((data) => {
          console.log("Server response:", data);
          if (data.status === "success") {
            console.log("Mood saved", data.message);
            // Update global moods
            window.moods[dateString] = mood;

            // Dispatch event to notify calendar
            const event = new CustomEvent("moodUpdated", {
              detail: { date: dateString, mood: mood },
            });
            document.dispatchEvent(event);
            showDiaryModal(currentSelectedDate); // Pass the current selected date
          } else {
            console.error("Failed to save mood:", data.message);
            alert("Failed to save mood: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error saving mood:", error);
          alert("An error occurred while saving the mood: " + error.message);
        });
    });
  });
}
// Handle mood icon clicks
document.addEventListener("DOMContentLoaded", () => {
  handleMoodClick(currentSelectedDate);
});

// Expose fetchMoods for calendar.js
window.diary = {
  fetchMoods,
  handleMoodClick,
};
