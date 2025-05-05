const monthYearElement = document.getElementById("monthYear");
const datesElement = document.getElementById("dates");
const arrowleftElement = document.getElementById("arrowleft");
const arrowrightElement = document.getElementById("arrowright");

let currentDate = new Date();

const updateCalendar = () => {
  const currentYear = currentDate.getFullYear();
  const currentMonth = currentDate.getMonth();

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
    const isToday = date.toDateString() === new Date().toDateString();
    const activeClass = isToday ? "active" : "";
    datesHTML += `
    <div class="date ">
      <span class=${activeClass}>
        ${i}
      </span>
      <div> 
        image here
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

arrowleftElement.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  updateCalendar();
});

arrowrightElement.addEventListener("click", () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  updateCalendar();
});

updateCalendar();
