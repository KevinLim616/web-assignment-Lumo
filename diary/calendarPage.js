import { svgIcons } from "../utils/svgIcons.js";
// import { getLocalDateString } from "../user/calendar.js";
// Make checkboxes interactive
document.querySelectorAll(".form-check-input").forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    console.log("Task status changed:", this.checked);
  });
});

function getLocalDateString(date = new Date()) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const day = date.getDate().toString().padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// Fetch and populate moods for the week
async function populateWeekMoods() {
  const today = new Date();
  const weekGrid = document.getElementById("week-grid");
  weekGrid.innerHTML = ""; // Clear existing content

  // Get the current date and the next 6 days i=7;i<7
  //current day in the middle = i= -3; i<=3
  for (let i = -3; i <= 3; i++) {
    const date = new Date(today);
    date.setDate(today.getDate() + i);
    const dateString = getLocalDateString(date);
    const dayName = date.toLocaleString("en-US", {
      weekday: "short",
    });

    let moodIcon = "";
    // Fetch moods
    const moods = await window.diary.fetchMoods(
      date.getFullYear(),
      date.getMonth()
    );
    const mood = moods[dateString] || "empty";
    console.log(`${dayName},${mood}`); //Fri, sad

    //TODO: display relavant emoji from svgIcons.js
    if (mood && svgIcons[mood]) {
      const svg = svgIcons[mood]("mood-icon", `mood-${dateString}`);
      moodIcon = svg.outerHTML;
    }

    const card = document.createElement("div");
    card.className = "card shadow-sm";
    card.innerHTML = `
          <div class="card-body day-card">
            <p class="day-name">${dayName}</p>
                ${moodIcon}
          </div>
        `;
    weekGrid.appendChild(card);
  }
}

// Fetch and populate tasks
async function populateTasks() {
  try {
    const response = await fetch("../tasks/get_tasks.php"); //fetch tasks
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const text = await response.text(); // Get raw response as text first
    console.log("Raw response:", text); // Log raw response for debugging
    const data = text ? JSON.parse(text) : [];
    const taskList = document.getElementById("task-list");
    // taskList.innerHTML = ""; // Clear existing tasks

    data.forEach((task) => {
      appendTask(task); // Use the appendTask function from tasks.js
    });
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
    if (error instanceof SyntaxError) {
      console.error("Invalid JSON response:", error);
    }
  }
}

// Modified: Wrapped in DOMContentLoaded to ensure scripts are loaded
document.addEventListener("DOMContentLoaded", () => {
  // Calendar part
  let calA = new Calendar({
    id: "#calendar-a",
    theme: "basic",
    primaryColor: "#FFA4DF",
    weekdayType: "short",
    border: "5px solid rgba(4, 64, 160, 0.1)",
    eventsData: [
      {
        id: 1,
        name: "French class",
        start: "2020-12-07T06:00:00",
        end: "2020-12-09T20:30:00",
      },
      {
        id: 2,
        name: "Blockchain 101",
        start: "2020-12-20T10:00:00",
        end: "2020-12-20T11:30:00",
      },
    ],
    dateChanged: (currentDate, filteredDateEvents) => {
      // Update the week grid when the date changes

      populateWeekMoods();
      populateTasks();
    },
  });
});
// Update moods when calendar date changes (simplified)

(function () {
  function c() {
    var b = a.contentDocument || a.contentWindow.document;
    if (b) {
      var d = b.createElement("script");
      d.innerHTML =
        "window.__CF$cv$params={r:'937c2416d8f053f9',t:'MTc0NTkwMTgzMi4wMDAwMDA='};var a=document.createElement('script');a.nonce='';a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js';document.getElementsByTagName('head')[0].appendChild(a);";
      b.getElementsByTagName("head")[0].appendChild(d);
    }
  }
  if (document.body) {
    var a = document.createElement("iframe");
    a.height = 1;
    a.width = 1;
    a.style.position = "absolute";
    a.style.top = 0;
    a.style.left = 0;
    a.style.border = "none";
    a.style.visibility = "hidden";
    document.body.appendChild(a);
    if ("loading" !== document.readyState) c();
    else if (window.addEventListener)
      document.addEventListener("DOMContentLoaded", c);
    else {
      var e = document.onreadystatechange || function () {};
      document.onreadystatechange = function (b) {
        e(b);
        "loading" !== document.readyState &&
          ((document.onreadystatechange = e), c());
      };
    }
  }
})();
