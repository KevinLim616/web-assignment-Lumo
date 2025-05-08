import { svgIcons } from "../utils/svgIcons.js";

const monthYearElement = document.getElementById("monthYear");
const datesElement = document.getElementById("dates");
const arrowleftElement = document.getElementById("arrowleft");
const arrowrightElement = document.getElementById("arrowright");

let currentDate = new Date();
let moods = window.moods || {}; //use global window.moods

const updateCalendar = async (forceFetch = false) => {
  const currentYear = currentDate.getFullYear();
  const currentMonth = currentDate.getMonth();

  // Fetch moods only on month change or initial load
  if (forceFetch || Object.keys(moods).length === 0) {
    try {
      moods = await window.diary.fetchMoods(currentYear, currentMonth);
      console.log(`Moods for ${currentYear}-${currentMonth + 1}:`, moods);
    } catch (error) {
      console.error("Failed to fetch moods: ", error);
      moods = {};
    }
  }

  const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
  const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);

  const totalDays = lastDayOfMonth.getDate();
  const firstDayIndex = (firstDayOfMonth.getDay() + 6) % 7;
  const monthYearString = currentDate.toLocaleString("en-US", {
    month: "long",
    year: "numeric",
  });

  monthYearElement.textContent = monthYearString;

  let datesHTML = "";

  // 上个月的日期（灰色）
  const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
  for (let i = firstDayIndex - 1; i >= 0; i--) {
    datesHTML += `<div class="date inactive">${prevMonthLastDay - i}</div>`;
  }

  // 当前月日期
  for (let i = 1; i <= totalDays; i++) {
    const date = new Date(currentYear, currentMonth, i);
    const dateString = date.toISOString().split("T")[0];
    const isToday = date.toDateString() === new Date().toDateString();
    const activeClass = isToday ? "active" : "";
    const mood = moods[dateString];

    let moodIcon = "";

    if (mood && svgIcons[mood]) {
      const svg = svgIcons[mood]("mood-icon", `mood-${dateString}`);
      moodIcon = svg.outerHTML;
    }

    datesHTML += `
    <div class="date ">
      <span class=${activeClass}>
        ${i}
      </span>
      <div class="calendar-mood-container"> 
        ${moodIcon}
      </div>
    </div>
        
    `;
  }

  // 下个月开头日期（灰色）
  const totalCells = firstDayIndex + totalDays;
  const nextDays = 42 - totalCells;
  for (let i = 1; i <= nextDays; i++) {
    datesHTML += `<div class="date inactive">${i}</div>`;
  }

  datesElement.innerHTML = datesHTML;
};

const updateSingleMood = (date, mood) => {
  const dateElement = datesElement.querySelector(`.date[data-date="${date}"]`);
  if (dateElement) {
    const moodContainer = dateElement.querySelector(".calendar-mood-container");
    let moodIcon = "";
    if (mood && svgIcons[mood]) {
      const svg = svgIcons[mood]("mood-icon", `mood-${date}`);
      moodIcon = svg.outerHTML;
    }
    moodContainer.innerHTML = moodIcon;
    console.log(`updated mood for ${date}:${mood}`);
  } else {
    //FIXME: the calendar wont save icon to new day
    // (the warning probably cause by this)

    console.warn(`date element not found for ${date}`);
  }
};

arrowleftElement.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  updateCalendar(true);
});

arrowrightElement.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  updateCalendar(true);
});

document.addEventListener("moodUpdated", (event) => {
  console.log("moodUpdated event received:", event.detail);
  const { date, mood } = event.detail;
  moods[date] = mood; //update local moods
  updateSingleMood(date, mood); // update only the affected date
});

updateCalendar(true);
