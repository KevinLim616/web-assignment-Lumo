import { svgIcons } from "../utils/svgIcons.js";
import { handleMoodClick } from "./diary.js";
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
      console.log(`Day card clicked for date: ${dateString}`);
      document
        .querySelectorAll(".card")
        .forEach((c) => c.classList.remove("selected"));
      card.classList.add("selected");
      window.calA.setDate(date); // Keep for Calendar UI sync
      console.log(
        "Calendar selected date after setDate:",
        getLocalDateString(
          window.calA.getDate ? window.calA.getDate() : new Date()
        )
      );
      // Directly update components with the clicked date

      fetchDiaryEntry(date);
    });

    weekGrid.appendChild(card);
  }
}

// Fetch and populate tasks for a specific date
async function populateTasks(selectedDate = new Date()) {
  try {
    const dateString = getLocalDateString(selectedDate);
    const url = `../tasks/get_tasks.php?date=${encodeURIComponent(dateString)}`;
    console.log("Fetching tasks from URL:", url);
    const response = await fetch(url);
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
async function fetchDiaryEntry(selectedDate = new Date()) {
  try {
    const dateString = getLocalDateString(selectedDate);
    const moods = await window.diary.fetchMoods(
      selectedDate.getFullYear(),
      selectedDate.getMonth()
    );
    if (!moods || typeof moods !== "object") {
      console.error("Invalid mood data:", moods);
      throw new Error("Failed to fetch moods");
    }
    const mood = moods[dateString] || "empty";
    console.log(`Mood for ${dateString}: ${mood}`);
    const diaryEntry = document.querySelector(".diary-entry");

    if (mood === "empty") {
      //Alterntive scenario
      diaryEntry.innerHTML = `
      <h3 class"emtpy-scene">How do you feel today?</h3>
        <div class="mood-icons-container">
        <span id="happy">
          <!--Happy icon-->

          <svg
            class="mood-icon"
            id="circle_1"
            data-name="circle 1"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24.21 24.21">
            <circle
              cx="12.1"
              cy="12.1"
              r="12"
              fill="#83d42f"
              stroke="#83d42f"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <path
              d="M6.22,9.03s.05-.08,.08-.13c.01-.02,.03-.05,.05-.06,.06-.08-.05,.06,0,0s.09-.11,.15-.16c.06-.06,.13-.11,.18-.16-.05,.05-.03,.02,0,0,.02-.01,.03-.02,.06-.03,.05-.02,.08-.06,.13-.08s.08-.05,.13-.07c.01,0,.09-.05,.05-.02s.03-.01,.06-.02c.11-.03,.22-.07,.33-.09h.06c.06,0-.06,0,0,0h.34c.21,0,.29,.02,.49,.07,.18,.05,.39-.06,.43-.25,.05-.18-.06-.39-.25-.43-.64-.15-1.29-.13-1.89,.17-.37,.17-.66,.46-.9,.78-.05,.06-.08,.11-.11,.18-.09,.17-.05,.39,.13,.48,.16,.09,.4,.05,.48-.13h0l.02-.05Z"
              fill="#4a7a13" />
            <path
              d="M16.38,8.2c.08-.02,.15-.03,.23-.05-.14,.03,0,0,.03,0h.38c.1,0-.05-.01,.01,0,.13,.02,.25,.05,.37,.09,.03,.01,.07,.02,.09,.03-.09-.03,.02,0,.03,.02,.07,.03,.14,.07,.19,.11,.03,.02,.07,.05,.1,.07,.01,.01,.03,.02,.05,.03t0,0c.07,.06,.14,.11,.19,.18,.03,.03,.07,.07,.09,.1,0,0,.08,.1,.06,.07,.07,.08,.13,.17,.18,.26,.1,.16,.32,.23,.48,.13s.23-.32,.13-.48c-.34-.55-.82-1.01-1.44-1.22-.39-.14-.8-.16-1.2-.09-.07,0-.14,.02-.21,.05-.18,.06-.3,.24-.25,.43,.05,.18,.25,.31,.43,.25h.05Z"
              fill="#4a7a13" />
            <ellipse
              cx="7.48"
              cy="11.3"
              rx="1.3"
              ry="1.5"
              fill="#4a7a13"
              stroke="#4a7a13"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <ellipse
              cx="17.11"
              cy="11.3"
              rx="1.3"
              ry="1.5"
              fill="#4a7a13"
              stroke="#4a7a13"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <path
              d="M9.69,16.8c-.08-.1-.1-.24-.05-.35,.06-.1,.16-.17,.27-.18l4.82-.18s.22-.01,.33,.14c.1,.14,.06,.33,.02,.43-.16,.61-1.39,1.34-1.59,1.45-.38,.14-1.28,.42-2.15,.06-.13-.06-.43-.19-.94-.61-.34-.27-.58-.56-.74-.77h.01Z"
              fill="#4a7a13"
              stroke="#4a7a13"
              stroke-miterlimit="10"
              stroke-width=".21" />
          </svg>
        </span>
        <span id="normal">
          <!--Normal icon-->

          <svg
            class="mood-icon"
            id="circle_2"
            data-name="circle 2"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24.21 24.21">
            <circle
              cx="12.1"
              cy="12.1"
              r="12"
              fill="#cdf51d"
              stroke="#cdf51d"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <g id="Layer_7" data-name="Layer 7">
              <g>
                <path
                  d="M7.99,8.41c-1.38-.7-2.21,.25-2.21,.25"
                  fill="#68a500" />
                <path
                  d="M8.17,8.1c-.58-.29-1.27-.41-1.89-.17-.27,.1-.56,.26-.75,.48-.13,.14-.15,.37,0,.5,.14,.13,.37,.15,.5,0,.05-.05,.09-.09,.14-.13,.06-.05-.07,.05-.02,.01,.01,0,.02-.02,.03-.02,.03-.02,.07-.05,.1-.07s.08-.05,.11-.07c.02,0,.03-.02,.06-.02,.08-.03-.02,0,0,0,.1-.03,.19-.07,.3-.09,.02,0,.05,0,.07-.01t-.02,0h.24c.07,0,.13,0,.19,.01-.09,0,.05,0,.06,.01,.03,0,.07,.01,.09,.02,.08,.02,.16,.05,.23,.07s.1,.03,.19,.09c.17,.08,.39,.05,.48-.13,.08-.16,.05-.4-.13-.48h.01Z"
                  fill="#68a500" />
              </g>
            </g>
            <g id="Layer_8" data-name="Layer 8">
              <g>
                <path
                  d="M18.05,8.69c-1.17-1.02-2.21-.3-2.21-.3"
                  fill="#68a500" />
                <path
                  d="M18.3,8.44c-.49-.42-1.12-.71-1.78-.63-.3,.03-.61,.11-.86,.29-.15,.1-.24,.32-.13,.48,.1,.15,.32,.24,.48,.13,.05-.03,.1-.06,.16-.09,.09-.05-.07,.02,.02,0,.03-.01,.07-.02,.11-.03,.08-.02,.17-.03,.25-.06,.03,0-.08,0,0,0h.31s.08,.01,.01,0c.07,.01,.13,.02,.18,.03,.07,.02,.13,.03,.19,.07-.06-.02,.02,.01,.03,.02,.03,.01,.07,.03,.09,.05,.15,.08,.27,.17,.38,.26,.14,.13,.37,.15,.5,0,.13-.14,.15-.38,0-.5h.03Z"
                  fill="#68a500" />
              </g>
            </g>
            <g id="Layer_9" data-name="Layer 9">
              <ellipse
                cx="7.27"
                cy="11.58"
                rx="1.5"
                ry="1.65"
                fill="#68a500"
                stroke="#68a500"
                stroke-miterlimit="10"
                stroke-width=".21" />
              <ellipse
                cx="16.55"
                cy="11.82"
                rx="1.5"
                ry="1.65"
                fill="#68a500"
                stroke="#68a500"
                stroke-miterlimit="10"
                stroke-width=".21" />
              <path
                d="M9.69,17.28h4.5c.46,0,.46-.71,0-.71h-4.5c-.46,0-.46,.71,0,.71h0Z"
                fill="#68a500" />
            </g>
          </svg>
        </span>
        <span id="stress">
          <!--Stress icon-->
          <svg
            class="mood-icon"
            id="circle_3"
            data-name="circle 3"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24.21 24.21">
            <circle
              cx="12.1"
              cy="12.1"
              r="12"
              fill="#fffd1a"
              stroke="#fffd1a"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <g>
              <path d="M4.84,8.88s1.03-1.62,2.56-1.71" fill="#d2b900" />
              <path
                d="M5.15,9.06s.03-.05,.05-.07c.02-.02,.03-.05,.06-.08,.01-.01,.02-.03,.03-.05,0,0,.01-.01,.02-.02,.02-.02,.01-.02-.02,.03,.02,0,.08-.1,.09-.13,.03-.05,.08-.09,.11-.14,.09-.1,.18-.21,.29-.3,.06-.06,.11-.1,.17-.15,.03-.02,.06-.05,.09-.08,.01-.01,.03-.02,.05-.03t0,0c.14-.1,.27-.19,.42-.27,.08-.05,.15-.08,.23-.11-.07,.03,.06-.02,.07-.02,.05-.01,.08-.03,.13-.05,.08-.02,.17-.05,.26-.07,.01,0,.15-.02,.08-.01,.05,0,.09,0,.14-.01,.19-.01,.35-.15,.35-.35,0-.18-.16-.37-.35-.35-1.13,.08-2.06,.81-2.72,1.68-.06,.07-.1,.14-.15,.22-.1,.16-.03,.39,.13,.48,.17,.1,.38,.03,.48-.13h0Z"
                fill="#d2b900" />
            </g>
            <g>
              <path d="M16.45,7.43s1.7,.37,2.4,1.75" fill="#d2b900" />
              <path
                d="M16.36,7.77s.11,.02,.16,.05c.06,.02,.13,.03,.18,.06,.07,.02,.18,.05,.24,.09,0,0-.07-.03-.03-.01,.01,0,.02,.01,.05,.02,.02,0,.05,.02,.06,.02l.14,.07c.1,.05,.19,.1,.3,.16,.1,.07,.22,.14,.32,.21,.01,0,.02,.02,.03,.02,0,0-.05-.03-.01,0,.02,.02,.05,.03,.07,.06,.06,.05,.1,.09,.16,.14s.1,.1,.15,.15c.02,.02,.05,.06,.07,.08,0,.01,.08,.1,.05,.06,.1,.13,.18,.27,.26,.42,.09,.17,.33,.22,.48,.13,.17-.1,.22-.31,.13-.48-.48-.93-1.41-1.54-2.38-1.85-.08-.02-.15-.05-.23-.07-.18-.05-.39,.06-.43,.25-.05,.18,.06,.4,.25,.43h0Z"
                fill="#d2b900" />
            </g>
            <ellipse
              cx="7.13"
              cy="11.49"
              rx="1.6"
              ry="1.55"
              fill="#d2b900"
              stroke="#d2b900"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <ellipse
              cx="16.86"
              cy="11.49"
              rx="1.6"
              ry="1.55"
              fill="#d2b900"
              stroke="#d2b900"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <path
              d="M9.58,17.57c1.44-.88,3.38-.88,4.82,0,.39,.24,.74-.38,.35-.62-1.65-1.01-3.87-1.01-5.53,0-.39,.24-.03,.85,.35,.62h0Z"
              fill="#d2b900" />
          </svg>
        </span>
        <span id="sad">
          <!--sad icon-->
          <svg
            class="mood-icon"
            id="circle_4"
            data-name="circle 4"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24.21 24.21">
            <circle
              cx="12.1"
              cy="12.1"
              r="12"
              fill="#61d6f3"
              stroke="#61d6f3"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <path d="M5.3,8.09s2.5,.7,3.07-1.84" fill="#0275aa" />
            <path d="M15.85,6.13s.75,2.48,3.2,1.61" fill="#0275aa" />
            <ellipse
              cx="7.78"
              cy="11.42"
              rx="1.46"
              ry="1.57"
              fill="#0275aa" />
            <ellipse
              cx="16.38"
              cy="11.42"
              rx="1.46"
              ry="1.57"
              fill="#0275aa" />
            <path
              d="M7.83,13.46c-.58,.59-.82,1.26-.57,1.66,.09,.15,.33,.41,.63,.37,.24-.03,.43-.25,.49-.55,0-.18,0-.49-.15-.85-.11-.3-.29-.5-.4-.63Z"
              fill="#0275aa" />
            <path
              d="M10.16,17.37c.11-.17,.85-1.25,2.21-1.42,.21-.02,1.33-.03,2.29,.71,.87,.67,.95,1.63,.57,2-.29,.27-.87,.27-1.43,.02-.46-.09-1.13-.17-1.91-.06-1.11,.16-1.73,.58-2,.32-.15-.15-.22-.51,.27-1.58h0Z"
              fill="#0275aa" />
          </svg>
        </span>
        <span id="angry">
          <!--Angry Icon-->

          <svg
            class="mood-icon"
            id="circle_5"
            data-name="circle 5"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24.21 24.21">
            <circle
              cx="12.1"
              cy="12.1"
              r="12"
              fill="#fe5528"
              stroke="#fe5528"
              stroke-miterlimit="10"
              stroke-width=".21" />
            <g>
              <line x1="7.03" y1="7.2" x2="10.05" y2="8.58" fill="none" />
              <path
                d="M6.88,7.47c.88,.4,1.75,.8,2.63,1.2l.38,.17c.15,.07,.33,.04,.42-.11,.08-.13,.04-.35-.11-.42-.88-.4-1.75-.8-2.63-1.2l-.38-.17c-.15-.07-.33-.04-.42,.11-.08,.13-.04,.35,.11,.42h0Z"
                fill="#d00100" />
            </g>
            <g>
              <line x1="14.16" y1="8.53" x2="16.84" y2="6.6" fill="none" />
              <path
                d="M14.31,8.8c.78-.56,1.56-1.13,2.35-1.69l.34-.24c.14-.1,.2-.27,.11-.42-.08-.13-.29-.21-.42-.11-.78,.56-1.56,1.13-2.35,1.69l-.34,.24c-.14,.1-.2,.27-.11,.42,.08,.13,.29,.21,.42,.11h0Z"
                fill="#d00100" />
            </g>
            <ellipse cx="9.28" cy="11.4" rx="1.27" ry="1.37" fill="#d00100" />
            <ellipse
              cx="14.86"
              cy="11.4"
              rx="1.27"
              ry="1.37"
              fill="#d00100" />
            <path
              d="M9.52,17.69c.49-.54,1.15-.99,1.83-1.25,.58-.23,1.21-.33,1.83-.19,.89,.2,1.58,.81,2.14,1.51,.25,.31,.69-.13,.44-.44-.6-.75-1.38-1.41-2.34-1.65-.7-.17-1.44-.11-2.11,.13-.82,.29-1.63,.81-2.22,1.46-.27,.29,.17,.73,.44,.44h-.01Z"
              fill="#d00100" />
          </svg>
        </span>
      </div>
        `;
      handleMoodClick(selectedDate);
      console.log(`Date:${dateString}, Rendering alternative`);
      return;
    }

    const response = await fetch(`../diary/get_diaries.php?date=${dateString}`);

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    const text = await response.text();
    console.log("Diary raw response:", text);
    const data = text
      ? JSON.parse(text)
      : { title: "No Title", content: "No Content" };

    diaryEntry.innerHTML = `
        <h3>${data.title}</h3>
        <p>${data.content}</p>
    `;

    console.log(
      `Date:${dateString}, Title: ${data.title}, Content: ${data.content} `
    );
  } catch (error) {
    console.error("Error fetching diary entry:", error.message);
    // if (error instanceof SyntaxError) {
    //   console.error("Invalid JSON response:", error);
    // }
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
      fetchDiaryEntry(currentDate);
    },
  });
  window.calA = calA;

  //   populateTasks(new Date());
  // Fallback if dateChanged doesn't fire
  setTimeout(() => {
    if (!document.getElementById("task-list").innerHTML) {
      const initialDate = window.calA.getDate
        ? window.calA.getDate()
        : new Date();
      populateTasks(initialDate);
    }
  }, 100);
});

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
