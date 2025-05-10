<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>LUMO - Productivity App</title>
  <link rel="stylesheet" href="../global.css">
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"
    rel="stylesheet" />
  <link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/color-calendar/dist/css/theme-basic.css" />
  <style>
    body {
      background-color: #fffef8;
      font-family: "SF PRO";
      min-height: 100vh;
      margin: 0;
      overflow-x: hidden;
      display: flex;
      align-items: stretch;
      justify-content: center;
      position: relative;
    }

    .grid-container {
      display: grid;
      grid-template-columns: minmax(250px, 1fr) minmax(400px, 2fr);
      grid-template-areas: "calendar-section main-content";
      width: 100%;
      max-width: 1200px;
      min-height: 100vh;
      margin: 0 auto;
      padding-left: 200px;
    }

    .sidebar {
      position: absolute;
      top: 0;
      left: 0;
      width: 200px;
      background-color: #1e1e2d;
      color: white;
      height: 100%;
      z-index: 1;
    }

    .calendar-section {
      grid-area: calendar-section;
      padding: 1rem;
    }

    .main-content {
      grid-area: main-content;
      padding: 1rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .logo {
      font-size: 3rem;
      font-weight: bold;
      padding: 1.5rem;
    }

    .nav-link {
      color: white;
      padding: 1rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }

    .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.1);
      color: white;
    }

    /* Calendar styles */
    .color-calendar.basic {
      color: black;
      border: 1px solid #ccc;
      border-radius: 1rem;
      overflow: hidden;
    }

    .color-calendar.basic .calendar__header {
      padding: 0.8rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .color-calendar.basic .calendar__header .calendar__month,
    .color-calendar.basic .calendar__header .calendar__year {
      color: #263238;
      font-size: 14px;
    }

    .color-calendar.basic .calendar__days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
    }

    .color-calendar.basic .calendar__days .calendar__day .calendar__day-box {
      padding: 0.6rem;
      text-align: center;
    }

    .color-calendar.basic .calendar__days .calendar__day-selected .calendar__day-box {
      border-radius: 0.5rem;
      background-color: #f8a5c2;
      opacity: 1;
      box-shadow: 0 3px 15px -5px #f8a5c2;
      color: rgb(248, 120, 233);
    }

    .tasks-section {
      margin-bottom: 1rem;
    }

    .task-list {
      background-color: white;
      border-radius: 1rem;
      padding: 1rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .task-time {
      font-weight: bold;
      margin-top: 0.5rem;
      margin-bottom: 0.25rem;
      font-size: 0.9rem;
    }

    .task-item {
      background-color: #fdf2f2;
      display: flex;
      align-items: center;
      padding: 0.3rem;
      gap: 0.5rem;
      margin: 5px;
      border-radius: 8px;
      font-size: 0.85rem;
    }

    .task-item span {
      font-family: "SF Pro text", "Poppins", sans-serif;

    }

    .task-icon {
      margin-left: auto;
      font-size: 0.9rem;
    }

    .task-home-icon {
      color: #f8a5c2;
    }

    .task-work-icon {
      color: #90caf9;
    }

    .task-finance-icon {
      color: #ffeb3b;
    }

    .week-grid {
      display: grid;
      max-width: 730px;
      grid-template-columns: repeat(7, 1fr);
      gap: 7px;
    }

    .card {
      border-radius: 1rem;
      width: 90px;
      height: 68px;
      transition: 0.2s ease-in-out;
    }

    .card:hover {
      cursor: pointer;
      background-color: #ffc0e9;
      opacity: 0.3;
    }

    .day-card {
      display: flex;
      gap: 4px;
      padding: 4px;
    }

    .day-name {
      margin: 0;
    }

    .mood-icon {
      align-self: flex-end;
      width: 40px;
      height: 40px;
    }

    .card.selected {
      background-color: #ffc0e9;
      opacity: 1;
    }

    .card.current-day {
      background-color: #ffc0e9;
      opacity: 0.7;
    }

    .current-day.selected {
      opacity: 1;
    }

    .emoji-img {
      margin-left: 2rem;
      align-self: end;
      justify-self: end;
      max-height: 3rem;
    }

    .diary-entry {
      background-color: white;
      border-radius: 1rem;
      padding: 1.5rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      width: 100%;
      height: 80vh;
      margin-top: 1rem;
      margin-left: auto;
      /* temporary position absolute, TODO: make the diary entry center */

      top: 180px;
      margin-right: auto;
    }

    .user-profile {
      position: absolute;
      bottom: 20px;
      left: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .username-avatar {
      width: 40px;
      height: 40px;
      background-color: #e6d5e9;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #333;
    }

    /* Checkbox */
    .form-check-input {
      border-color: #808080;
    }

    .form-check-input:checked {
      background-color: #808080;
      border-color: #808080;
    }

    .form-check-input:focus {
      border-color: #808080;
      box-shadow: 0 0 0 0.25rem rgba(128, 128, 128, 0.25);
    }

    /* Month Year font */
    .color-calendar.basic .calendar__header .calendar__month,
    .color-calendar.basic .calendar__header .calendar__year,
    .color-calendar.basic .calendar__days .calendar__day-name,
    .color-calendar.basic .calendar__days .calendar__day .calendar__day-box {
      color: rgb(0, 0, 0);
    }

    .mood-icon {
      width: 48px;
      height: 48px;
      cursor: pointer;
      transition: transform 0.2s ease-out;
    }

    .mood-icons-container span:hover .mood-icon {
      transform: scale(1.4);
      z-index: 5;
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .mood-icons-container span:hover+span .mood-icon,
    .mood-icons-container span:has(+ span:hover) .mood-icon {
      transform: scale(1.1);
    }

    .diary-entry .empty-scene {
      font-weight: 200;
      color: #abbaa9;
    }

    .mood-icons-container {
      display: flex;
      padding: 8px;
      max-width: 367px;
      justify-content: space-evenly;
      background-color: #f1f2f2;
      border-radius: 8px;
    }
  </style>
  <!--javascript here-->
  <script src="../diary/diary.js" defer></script>
  <!-- <script src="../tasks/tasks.js" defer></script> -->
  <script src="../utils/svgIcons.js" defer type="module"></script>
</head>

<body>
  <div class="grid-container">
    <div class="sidebar">
      <div class="logo">LUMO</div>
      <nav>
        <a class="nav-link" href="#">
          <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a class="nav-link" href="#">
          <i class="far fa-calendar"></i> Calendar
        </a>
        <a class="nav-link" href="#">
          <i class="far fa-bell"></i> Notification
        </a>
      </nav>
      <div class="user-profile">
        <div class="username-avatar">
          <i class="far fa-user"></i>
        </div>
        <span>Username</span>
      </div>
    </div>

    <div class="calendar-section">
      <h2>Calendar</h2>
      <div id="calendar-a"></div>
      <div id="calendar-bottom"></div>

      <h2 class="mt-4">Daily Tasks</h2>
      <div class="task-list border" id="task-list">
        <div class="task-time">
          <h5>9:00 AM</h5>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" checked />
          <span>assignment</span>
          <div class="task-icon task-work-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M22.5 16.875V1.125C22.5 0.501562 21.9984 0 21.375 0H6C3.51562 0 1.5 2.01562 1.5 4.5V19.5C1.5 21.9844 3.51562 24 6 24H21.375C21.9984 24 22.5 23.4984 22.5 22.875V22.125C22.5 21.7734 22.3359 21.4547 22.0828 21.2484C21.8859 20.5266 21.8859 18.4688 22.0828 17.7469C22.3359 17.5453 22.5 17.2266 22.5 16.875ZM7.5 6.28125C7.5 6.12656 7.62656 6 7.78125 6H17.7188C17.8734 6 18 6.12656 18 6.28125V7.21875C18 7.37344 17.8734 7.5 17.7188 7.5H7.78125C7.62656 7.5 7.5 7.37344 7.5 7.21875V6.28125ZM7.5 9.28125C7.5 9.12656 7.62656 9 7.78125 9H17.7188C17.8734 9 18 9.12656 18 9.28125V10.2188C18 10.3734 17.8734 10.5 17.7188 10.5H7.78125C7.62656 10.5 7.5 10.3734 7.5 10.2188V9.28125ZM19.3781 21H6C5.17031 21 4.5 20.3297 4.5 19.5C4.5 18.675 5.175 18 6 18H19.3781C19.2891 18.8016 19.2891 20.1984 19.3781 21Z"
                fill="#7EBAF5" />
            </svg>
          </div>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" checked />
          <span>do house chores</span>
          <div class="task-icon task-home-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_721_3276)">
                <path
                  d="M11.682 7.51084L3.99989 13.8379V20.6667C3.99989 20.8435 4.07013 21.0131 4.19515 21.1381C4.32018 21.2631 4.48975 21.3333 4.66656 21.3333L9.33572 21.3213C9.51196 21.3204 9.68067 21.2498 9.80497 21.1248C9.92928 20.9999 9.99906 20.8308 9.99906 20.6546V16.6667C9.99906 16.4899 10.0693 16.3203 10.1943 16.1953C10.3193 16.0702 10.4889 16 10.6657 16H13.3324C13.5092 16 13.6788 16.0702 13.8038 16.1953C13.9288 16.3203 13.9991 16.4899 13.9991 16.6667V20.6517C13.9988 20.7394 14.0158 20.8263 14.0492 20.9074C14.0826 20.9886 14.1316 21.0623 14.1936 21.1244C14.2555 21.1866 14.3291 21.2358 14.4101 21.2695C14.4911 21.3031 14.578 21.3204 14.6657 21.3204L19.3332 21.3333C19.51 21.3333 19.6796 21.2631 19.8046 21.1381C19.9297 21.0131 19.9999 20.8435 19.9999 20.6667V13.8333L12.3195 7.51084C12.2292 7.43806 12.1167 7.39837 12.0007 7.39837C11.8847 7.39837 11.7723 7.43806 11.682 7.51084ZM23.8166 11.8113L20.3332 8.94001V3.16876C20.3332 3.03615 20.2805 2.90898 20.1868 2.81521C20.093 2.72144 19.9658 2.66876 19.8332 2.66876H17.4999C17.3673 2.66876 17.2401 2.72144 17.1463 2.81521C17.0526 2.90898 16.9999 3.03615 16.9999 3.16876V6.19418L13.2695 3.12501C12.9115 2.83042 12.4623 2.66935 11.9986 2.66935C11.535 2.66935 11.0858 2.83042 10.7278 3.12501L0.180725 11.8113C0.130095 11.8531 0.0882067 11.9045 0.0574534 11.9626C0.0267 12.0206 0.00768456 12.0841 0.00149348 12.1495C-0.00469761 12.2149 0.00205702 12.2809 0.0213714 12.3437C0.0406858 12.4065 0.0721814 12.4648 0.114059 12.5154L1.17656 13.8071C1.21832 13.8579 1.26969 13.8999 1.32772 13.9308C1.38576 13.9617 1.44932 13.9809 1.51476 13.9872C1.58021 13.9935 1.64626 13.9868 1.70913 13.9676C1.77199 13.9483 1.83045 13.9169 1.88114 13.875L11.682 5.80251C11.7723 5.72973 11.8847 5.69004 12.0007 5.69004C12.1167 5.69004 12.2292 5.72973 12.3195 5.80251L22.1207 13.875C22.1713 13.9169 22.2297 13.9484 22.2925 13.9677C22.3553 13.987 22.4212 13.9938 22.4866 13.9876C22.552 13.9814 22.6155 13.9624 22.6736 13.9316C22.7316 13.9009 22.783 13.859 22.8249 13.8083L23.8874 12.5167C23.9292 12.4658 23.9606 12.4071 23.9797 12.3441C23.9988 12.281 24.0052 12.2148 23.9986 12.1492C23.992 12.0837 23.9726 12.0201 23.9413 11.9621C23.9101 11.9041 23.8677 11.8528 23.8166 11.8113Z"
                  fill="#FFA4DF" />
              </g>
              <defs>
                <clipPath id="clip0_721_3276">
                  <rect width="24" height="24" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
        </div>

        <div class="task-time">
          <h5>12:00 PM</h5>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" />
          <span>receive salary</span>
          <div class="task-icon task-finance-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M15 15.75C15 16.1644 14.6644 16.5 14.25 16.5H9.75C9.33562 16.5 9 16.1644 9 15.75V13.5H0V20.25C0 21.45 1.05 22.5 2.25 22.5H21.75C22.95 22.5 24 21.45 24 20.25V13.5H15V15.75ZM21.75 6H18V3.75C18 2.55 16.95 1.5 15.75 1.5H8.25C7.05 1.5 6 2.55 6 3.75V6H2.25C1.05 6 0 7.05 0 8.25V12H24V8.25C24 7.05 22.95 6 21.75 6ZM15 6H9V4.5H15V6Z"
                fill="#FADA4D" />
            </svg>
          </div>
        </div>

        <div class="task-time">
          <h5>14:30 PM</h5>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" checked />
          <span>draw 2 sketches</span>
          <div class="task-icon task-work-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M22.5 16.875V1.125C22.5 0.501562 21.9984 0 21.375 0H6C3.51562 0 1.5 2.01562 1.5 4.5V19.5C1.5 21.9844 3.51562 24 6 24H21.375C21.9984 24 22.5 23.4984 22.5 22.875V22.125C22.5 21.7734 22.3359 21.4547 22.0828 21.2484C21.8859 20.5266 21.8859 18.4688 22.0828 17.7469C22.3359 17.5453 22.5 17.2266 22.5 16.875ZM7.5 6.28125C7.5 6.12656 7.62656 6 7.78125 6H17.7188C17.8734 6 18 6.12656 18 6.28125V7.21875C18 7.37344 17.8734 7.5 17.7188 7.5H7.78125C7.62656 7.5 7.5 7.37344 7.5 7.21875V6.28125ZM7.5 9.28125C7.5 9.12656 7.62656 9 7.78125 9H17.7188C17.8734 9 18 9.12656 18 9.28125V10.2188C18 10.3734 17.8734 10.5 17.7188 10.5H7.78125C7.62656 10.5 7.5 10.3734 7.5 10.2188V9.28125ZM19.3781 21H6C5.17031 21 4.5 20.3297 4.5 19.5C4.5 18.675 5.175 18 6 18H19.3781C19.2891 18.8016 19.2891 20.1984 19.3781 21Z"
                fill="#7EBAF5" />
            </svg>
          </div>
        </div>

        <div class="task-time">
          <h5>20:00 PM</h5>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" />
          <span>groceries</span>
          <div class="task-icon task-home-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_721_3276)">
                <path
                  d="M11.682 7.51084L3.99989 13.8379V20.6667C3.99989 20.8435 4.07013 21.0131 4.19515 21.1381C4.32018 21.2631 4.48975 21.3333 4.66656 21.3333L9.33572 21.3213C9.51196 21.3204 9.68067 21.2498 9.80497 21.1248C9.92928 20.9999 9.99906 20.8308 9.99906 20.6546V16.6667C9.99906 16.4899 10.0693 16.3203 10.1943 16.1953C10.3193 16.0702 10.4889 16 10.6657 16H13.3324C13.5092 16 13.6788 16.0702 13.8038 16.1953C13.9288 16.3203 13.9991 16.4899 13.9991 16.6667V20.6517C13.9988 20.7394 14.0158 20.8263 14.0492 20.9074C14.0826 20.9886 14.1316 21.0623 14.1936 21.1244C14.2555 21.1866 14.3291 21.2358 14.4101 21.2695C14.4911 21.3031 14.578 21.3204 14.6657 21.3204L19.3332 21.3333C19.51 21.3333 19.6796 21.2631 19.8046 21.1381C19.9297 21.0131 19.9999 20.8435 19.9999 20.6667V13.8333L12.3195 7.51084C12.2292 7.43806 12.1167 7.39837 12.0007 7.39837C11.8847 7.39837 11.7723 7.43806 11.682 7.51084ZM23.8166 11.8113L20.3332 8.94001V3.16876C20.3332 3.03615 20.2805 2.90898 20.1868 2.81521C20.093 2.72144 19.9658 2.66876 19.8332 2.66876H17.4999C17.3673 2.66876 17.2401 2.72144 17.1463 2.81521C17.0526 2.90898 16.9999 3.03615 16.9999 3.16876V6.19418L13.2695 3.12501C12.9115 2.83042 12.4623 2.66935 11.9986 2.66935C11.535 2.66935 11.0858 2.83042 10.7278 3.12501L0.180725 11.8113C0.130095 11.8531 0.0882067 11.9045 0.0574534 11.9626C0.0267 12.0206 0.00768456 12.0841 0.00149348 12.1495C-0.00469761 12.2149 0.00205702 12.2809 0.0213714 12.3437C0.0406858 12.4065 0.0721814 12.4648 0.114059 12.5154L1.17656 13.8071C1.21832 13.8579 1.26969 13.8999 1.32772 13.9308C1.38576 13.9617 1.44932 13.9809 1.51476 13.9872C1.58021 13.9935 1.64626 13.9868 1.70913 13.9676C1.77199 13.9483 1.83045 13.9169 1.88114 13.875L11.682 5.80251C11.7723 5.72973 11.8847 5.69004 12.0007 5.69004C12.1167 5.69004 12.2292 5.72973 12.3195 5.80251L22.1207 13.875C22.1713 13.9169 22.2297 13.9484 22.2925 13.9677C22.3553 13.987 22.4212 13.9938 22.4866 13.9876C22.552 13.9814 22.6155 13.9624 22.6736 13.9316C22.7316 13.9009 22.783 13.859 22.8249 13.8083L23.8874 12.5167C23.9292 12.4658 23.9606 12.4071 23.9797 12.3441C23.9988 12.281 24.0052 12.2148 23.9986 12.1492C23.992 12.0837 23.9726 12.0201 23.9413 11.9621C23.9101 11.9041 23.8677 11.8528 23.8166 11.8113Z"
                  fill="#FFA4DF" />
              </g>
              <defs>
                <clipPath id="clip0_721_3276">
                  <rect width="24" height="24" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
        </div>
        <div class="task-item">
          <input type="checkbox" class="form-check-input" checked />
          <span>find part time</span>
          <div class="task-icon task-finance-icon">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M15 15.75C15 16.1644 14.6644 16.5 14.25 16.5H9.75C9.33562 16.5 9 16.1644 9 15.75V13.5H0V20.25C0 21.45 1.05 22.5 2.25 22.5H21.75C22.95 22.5 24 21.45 24 20.25V13.5H15V15.75ZM21.75 6H18V3.75C18 2.55 16.95 1.5 15.75 1.5H8.25C7.05 1.5 6 2.55 6 3.75V6H2.25C1.05 6 0 7.05 0 8.25V12H24V8.25C24 7.05 22.95 6 21.75 6ZM15 6H9V4.5H15V6Z"
                fill="#FADA4D" />
            </svg>
          </div>
        </div>
      </div>
    </div>
    <!--Main here-->
    <main class="main-content">
      <div style="display: flex; flex-direction: column; max-width: 750px">
        <h3>Diary</h3>
        <div class="container">
          <div class="week-grid" id="week-grid">
            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">MON</p>
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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">TUE</p>
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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">WED</p>
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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">THU</p>

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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">FRI</p>

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
                    <line
                      x1="7.03"
                      y1="7.2"
                      x2="10.05"
                      y2="8.58"
                      fill="none" />
                    <path
                      d="M6.88,7.47c.88,.4,1.75,.8,2.63,1.2l.38,.17c.15,.07,.33,.04,.42-.11,.08-.13,.04-.35-.11-.42-.88-.4-1.75-.8-2.63-1.2l-.38-.17c-.15-.07-.33-.04-.42,.11-.08,.13-.04,.35,.11,.42h0Z"
                      fill="#d00100" />
                  </g>
                  <g>
                    <line
                      x1="14.16"
                      y1="8.53"
                      x2="16.84"
                      y2="6.6"
                      fill="none" />
                    <path
                      d="M14.31,8.8c.78-.56,1.56-1.13,2.35-1.69l.34-.24c.14-.1,.2-.27,.11-.42-.08-.13-.29-.21-.42-.11-.78,.56-1.56,1.13-2.35,1.69l-.34,.24c-.14,.1-.2,.27-.11,.42,.08,.13,.29,.21,.42,.11h0Z"
                      fill="#d00100" />
                  </g>
                  <ellipse
                    cx="9.28"
                    cy="11.4"
                    rx="1.27"
                    ry="1.37"
                    fill="#d00100" />
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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">SAT</p>

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
              </div>
            </div>

            <div class="card shadow-sm">
              <div class="card-body day-card">
                <p class="day-name">SUN</p>

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
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="diary-entry border">
        <h3>Feeling Lost</h3>
        <p>
          Today felt like a blur. I woke up with so many things on my to-do
          list, but somehow, I ended up just staring at my screen for what
          felt like hours. It's frustrating—wanting to be productive but
          feeling stuck in an endless cycle of procrastination.
        </p>

        <p>
          I did make some progress, though. I worked a bit on structuring the
          notification system for my to-do list app. It's starting to make
          sense now, and I feel like I'm getting closer to making it
          functional. Small wins, I guess?
        </p>

        <p>
          On another note, I've been thinking a lot about why motivation feels
          so fleeting. One moment, I'm inspired, ready to take on the world.
          The next, I'm questioning everything. Maybe I just need to take
          things one step at a time instead of overwhelming myself with big
          expectations.
        </p>

        <p>
          For now, I'll try to end the day on a good note. A little bit of
          work, a little bit of rest—I think that's the balance I need to
          find.
        </p>
      </div>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/color-calendar/dist/bundle.js"></script>

  <script src="../diary/calendarPage.js" defer type="module"></script>
  <!-- prettier-ignore -->
</body>

</html>