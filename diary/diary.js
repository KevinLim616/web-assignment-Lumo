window.moods = {}; // Global store for mood data

// Fetch moods for a given year and month
// const fetchMoods = async (year, month) => {
//   try {
//     const response = await fetch(
//       `../tasks/get_moods.php?year=${year}&month=${month + 1}`
//     );
//     if (!response.ok) {
//       throw new Error(`HTTP error! Status: ${response.status}`);
//     }
//     const data = await response.json();
//     if (data.status === "success") {
//       window.moods = data.moods; // Store moods globally
//       return data.moods; // Return for immediate use
//     } else {
//       console.error("Failed to fetch moods:", data.message);
//       window.moods = {};
//       return {};
//     }
//   } catch (error) {
//     console.error("Error fetching moods:", error);
//     window.moods = {};
//     return {};
//   }
// };

// Handle mood icon clicks
document.addEventListener("DOMContentLoaded", () => {
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

  moodIcons.forEach((icon) => {
    icon.addEventListener("click", () => {
      const mood = icon.id; // Use id as mood value (e.g., "happy")
      const today = new Date().toISOString().split("T")[0]; // e.g., "2025-05-07"

      console.log("clicked", mood);
      console.log(`Sending AJAX request - date: ${today}, mood: ${mood}`);

      const formData = new FormData();
      formData.append("date", today);
      formData.append("mood", mood);

      //Log FormData entries
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
          if (data.status === "success") {
            console.log("Mood saved", data.message);
            // Update global moods
            // window.moods[today] = mood;

            // Dispatch event to notify calendar
            // document.dispatchEvent(new Event("moodUpdated"));
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
});

// Expose fetchMoods for calendar.js
// window.diary = {
//     fetchMoods,
//   };
