import { svgIcons } from "../utils/svgIcons.js";
// import { getLocalDateString } from "../user/calendar.js";
// Make checkboxes interactive
document.querySelectorAll(".form-check-input").forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    console.log("Task status changed:", this.checked);
  });
});

//✅
function getLocalDateString(date = new Date()) {
  const year = date.getFullYear();
  const month = (date.getMonth() + 1).toString().padStart(2, "0");
  const day = date.getDate().toString().padStart(2, "0");
  return `${year}-${month}-${day}`;
}

// Fetch and populate moods for the week ✅
async function populateWeekMoods(selectedDate = new Date()) {
  const today = new Date();
  const todayString = getLocalDateString(today);
  const weekGrid = document.getElementById("week-grid");
  weekGrid.innerHTML = "";

  for (let i = -3; i <= 3; i++) {
    const date = new Date(today);
    date.setDate(today.getDate() + i);
    const dateString = getLocalDateString(date);
    const dayName = date.toLocaleString("en-US", { weekday: "short" });

    let moodIcon = "";
    const moods = await window.diary.fetchMoods(
      date.getFullYear(),
      date.getMonth()
    );
    const mood = moods[dateString] || "empty";
    console.log(`${dayName},${mood}`);

    if (mood && svgIcons[mood]) {
      const svg = svgIcons[mood]("mood-icon", `mood-${dateString}`);
      moodIcon = svg.outerHTML;
    }

    const card = document.createElement("div");
    card.className = "card shadow-sm";
    card.dataset.date = dateString;

    if (dateString === todayString) card.classList.add("current-day");
    if (dateString === getLocalDateString(selectedDate))
      card.classList.add("selected");

    card.innerHTML = `
      <div class="card-body day-card">
        <p class="day-name">${dayName}</p>
        ${moodIcon}
      </div>
    `;

    card.addEventListener("click", () => {
      document
        .querySelectorAll(".card")
        .forEach((c) => c.classList.remove("selected"));
      card.classList.add("selected");
      window.calA.setDate(date); // Update the calendar's selected date, triggering dateChanged
    });

    weekGrid.appendChild(card);
  }
}

// Fetch and populate tasks for a specific date
async function populateTasks(selectedDate = new Date()) {
  try {
    const dateString = getLocalDateString(selectedDate);
    const response = await fetch(`../tasks/get_tasks.php?date=${dateString}`);
    console.log("Fetch response status:", response.status);
    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
    const data = await response.json();
    console.log("Parsed JSON data:", data);

    const taskList = document.getElementById("task-list");
    taskList.innerHTML = "";

    if (!Array.isArray(data) || data.length === 0) {
      taskList.innerHTML = "<p>No tasks for this date.</p>";
      return;
    }

    // Group tasks by time
    const tasksByTime = {};
    data.forEach((task) => {
      const time = task.time.split(":").slice(0, 2).join(":"); // e.g., "09:00" from "09:00:00"
      if (!tasksByTime[time]) tasksByTime[time] = [];
      tasksByTime[time].push(task);
    });

    // Render tasks
    for (const [time, tasks] of Object.entries(tasksByTime)) {
      const formattedTime = `${time.slice(0, 2)}${time.slice(2)} ${
        time < "12:00" ? "AM" : "PM"
      }`;
      taskList.innerHTML += `
          <div class="task-time">
            <h5>${formattedTime}</h5>
          </div>
        `;
      tasks.forEach((task) => {
        const iconClass = `task-${task.category.toLowerCase()}-icon`;
        const iconSvg = svgIcons[task.category.toLowerCase()](
          "task-icon",
          `${task.category}-${task.id}`
        );
        taskList.innerHTML += `
            <div class="task-item">
              <input type="checkbox" class="form-check-input" ${
                task.status === "completed" ? "checked" : ""
              } data-task-id="${task.id}" />
              <span>${task.title}</span>
              <div class="task-icon ${iconClass}">
                ${iconSvg.outerHTML}
              </div>
            </div>
          `;
      });
    }
  } catch (error) {
    console.error("Error fetching tasks:", error.message);
    if (error instanceof SyntaxError) {
      const response = await fetch(
        `../tasks/get_tasks.php?date=${getLocalDateString(selectedDate)}`
      );
      const text = await response.text();
      console.log("Full response text:", text);
    }
    document.getElementById("task-list").innerHTML =
      "<p>Error loading tasks.</p>";
  }
}

//✅
async function fetchDiaryEntry() {
  try {
    const today = new Date();
    const dateString = getLocalDateString(today);
    const response = await fetch(`../diary/get_diaries.php?date=${dateString}`);

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const text = await response.text();
    console.log("Diary raw response:", text);
    const data = text
      ? JSON.parse(text)
      : { title: "No Title", content: "No Content" };

    const diaryEntry = document.querySelector(".diary-entry");
    diaryEntry.innerHTML = `
        <h3>${data.title}</h3>
        <p>${data.content}</p>
    `;

    console.log(
      `Date:${dateString}, Title: ${data.title}, Content: ${data.content} `
    );
  } catch (error) {
    console.error("Error fetching diary entry:", error.message);
    if (error instanceof SyntaxError) {
      console.error("Invalid JSON response:", error);
    }
    const diaryEntry = document.querySelector(".diary-entry");
    diaryEntry.innerHTML = `<h3>No Title</h3><p>No Content</p>`;
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

      populateWeekMoods(currentDate);
      populateTasks(currentDate);
    },
  });
  window.calA = calA;

  populateTasks(new Date());
  fetchDiaryEntry();
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
