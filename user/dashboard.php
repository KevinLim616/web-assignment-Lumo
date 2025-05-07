<?php
include __DIR__ . "./../tasks/get_tasks.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Dashboard</title>
  <link rel="stylesheet" href="../global.css" />
  <link rel="stylesheet" href="../style/dashboard.css" />
  <link rel="stylesheet" href="../style/calendar-style.css" />
  <script src="./../tasks/tasks.js" defer></script>
</head>

<body>


  <!--sidebar here-->

  <!--main content here-->
  <main class="main-layout">
    <div class="left-panel">
      <div class="today-title">Today</div>
      <div style="padding-left: 8px">
        <div class="today-date">Mon 09 Jan 2025</div>
        <div class="today-subtext">
          <p>
            Let's plan today's to do list! Intelly wishes you a good and
            productive day.
          </p>
        </div>
      </div>
      <div class="tasks-wrapper">
        <div
          style="
              display: flex;
              align-items: center;
              justify-content: space-between;
              margin-bottom: -17px;
            ">
          <div style="display: flex; align-items: center">
            <h2 style="font-size: 40px">Tasks</h2>
          </div>
          <div>
            <!--TODO: onclick show modal-->
            <button id="createBtn" class="create-button">
              <span style="height: 24px">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-plus">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
              </span>

              create
            </button>
          </div>
        </div>

        <div class="tasks-list">
          <form>
            <?php
            $tasks = getTasks();
            if (!empty($tasks)) {
              foreach ($tasks as $task) {
                $title = htmlspecialchars($task['title']);
                $time = htmlspecialchars($task['time']);
                $category = htmlspecialchars($task['category']);
                $task_id = $task['id'];
                $date = htmlspecialchars($task['date']);
                $description = htmlspecialchars($task['description']);
                // Encode task details as JSON for the data attribute
                $task_details = json_encode([
                  'id' => $task_id,
                  'title' => $title,
                  'date' => $date,
                  'time' => $time,
                  'description' => $description,
                  'category' => $category
                ]);
            ?>
                <div class="task-box" data-task-details='<?php echo $task_details; ?>'>
                  <div class="task">
                    <label for="task-<?php echo $task_id; ?>" class="task-label custom-checkbox">
                      <input type="checkbox" name="task-<?php echo $task_id; ?>" id="task-<?php echo $task_id; ?>" data-task-id="<?php echo $task_id; ?>" />
                      <span class="checkmark"></span>
                      <?php echo $title; ?>
                    </label>
                  </div>
                  <div class="info">
                    <span class="task-time" data-time="<?php echo $time; ?>"></span>
                    <div class="icons" data-category="<?php echo $category; ?>"></div>
                  </div>
                </div>
            <?php
              }
            } else {
              echo "<p>No tasks available.</p>";
            }
            ?>
          </form>
        </div>
        <form action="">
          <div>
            <!--FIXME: Fix the goal section at the bottom-->
            <!--Goal-->
            <div class="flag-box">
              <h2>Flag</h2>

              <div style="display: flex; align-items: center">
                <label for="flag1" style="margin-left: 8px">
                  <input type="checkbox" id="flag1" name="flag1" />
                  Save Ringgit 5,000 this year
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- <div class="vertical-line"></div> -->
    </div>
  </main>
  <!--right panel here-->
  <!--FIXME: font-size font-weight mood-icon-->
  <div class="right-panel">
    <div class="mood-log-container">
      <div class="mood-log-header">
        <h2>Daily Mood Log</h2>
        <div>
          <p class="mood-log-subtext">
            Track your mood today and make every day <br />
            more meaningful!
          </p>
        </div>
      </div>
      <div class="mood-icons-container">
        <span>
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
    </div>
    <!--FIXME: Calendar here-->
    <div class="calendar">
      <div class="header">
        <button id="arrowleft">
          <svg
            width="10"
            height="16"
            viewBox="0 0 10 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M7.75 13.5L2.25 8L7.75 2.5"
              stroke="#1E1E1E"
              stroke-width="4"
              stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
        <div class="monthYear" id="monthYear"></div>
        <button id="arrowright">
          <svg
            width="10"
            height="16"
            viewBox="0 0 10 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
              d="M2.25 13.5L7.75 8L2.25 2.5"
              stroke="#1E1E1E"
              stroke-width="4"
              stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
        </button>
      </div>
      <div class="days">
        <div class="day">MO</div>
        <div class="day">TU</div>
        <div class="day">WE</div>
        <div class="day">TH</div>
        <div class="day">FR</div>
        <div class="day">SA</div>
        <div class="day">SU</div>
      </div>

      <div class="dates" id="dates"></div>
    </div>
  </div>
  <!--Modal Here-->
  <div id="popupModal" class="modal">
    <div class="modal-content">
      <!--removed action="" because its handdled in task.js via ajax-->
      <form method="POST" id="create-task-form">
        <div style="display: flex; flex-direction: column; gap: 24px">
          <div class="modal-first-line">
            <input
              type="text"
              class="textplace"
              id="task-title"
              name="title"
              placeholder="Add Title"
              required />
            <div class="close-btn">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#aaaaaa"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </div>
          </div>

          <div class="form-group">
            <div class="date-time-group">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#000000"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="clock-icon">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
              </svg>
              <label for="task-date">Date:</label>
              <input type="date" id="task-date" name="date" required />
              <label for="task-time">Time:</label>
              <input type="time" id="task-time" name="time" required />
            </div>
          </div>

          <div class="description-group">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="#000000"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-align-left">
              <line x1="17" y1="10" x2="3" y2="10"></line>
              <line x1="21" y1="6" x2="3" y2="6"></line>
              <line x1="21" y1="14" x2="3" y2="14"></line>
              <line x1="17" y1="18" x2="3" y2="18"></line>
            </svg>
            <textarea rows="5" class="textdescription" name="description">
          Description
          </textarea>
          </div>

          <div class="category-group">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-grid">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
            <label for="category-select">Category:</label>
            <select id="category-select" name="category">
              <option value="home">Home</option>
              <option value="work">Work</option>
              <option value="school">school</option>
            </select>
          </div>
        </div>
        <input type="submit" class="save-btn" value="Save" name="create-task" />
      </form>
    </div>
  </div>
  <!--Task details drawer-->

  <div class="task-drawer" id="task-details-drawer">
    <div class="drawer-content-container">
      <!--this is a checkbox-->
      <label class="task-label custom-checkbox">
        <input type="checkbox" id="drawer-task-checkbox" />
        <span class="drawer-checkmark" id="custom-drawer-checkbox"></span>
      </label>

      <div class="drawer-content">
        <div class="drawer-header">
          <h2 id="drawer-task-title"></h2>
        </div>
        <div class="drawer-body">
          <div class="task-info">
            <div class="tag" id="drawer-task-date-time">today,1:00 PM</div>
            <div class="tag" id="drawer-task-category"></div>
          </div>
          <div class="task-description">
            <p id="drawer-task-description"></p>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- <div id="task-details-drawer" class="task-drawer">
    <div class="drawer-content">
      <div class="drawer-header">
        <h2 id="task-title"></h2>
      </div>
      <div class="drawer-body">
        <p><strong>Date:</strong> <span id="drawer-task-date"></span></p>
        <p><strong>Time:</strong> <span id="drawer-task-time"></span></p>
        <p><strong>Description:</strong> <span id="drawer-task-description"></span></p>
        <p><strong>Category:</strong> <span id="drawer-task-category"></span></p>
      </div>
    </div>
  </div> -->

  <script src="./modal.js" defer></script>

  <script src="./calendar.js" defer></script>
</body>

</html>