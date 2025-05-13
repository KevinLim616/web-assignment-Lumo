const icons = {
  home: `<svg width="30" height="24" viewBox="0 0 30 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.6026 6.38854L4.99999 14.2974V22.8333C4.99999 23.0543 5.08778 23.2663 5.24406 23.4226C5.40034 23.5789 5.61231 23.6667 5.83332 23.6667L11.6698 23.6516C11.8901 23.6505 12.101 23.5622 12.2563 23.406C12.4117 23.2499 12.4989 23.0385 12.4989 22.8182V17.8333C12.4989 17.6123 12.5867 17.4004 12.743 17.2441C12.8993 17.0878 13.1113 17 13.3323 17H16.6656C16.8866 17 17.0986 17.0878 17.2549 17.2441C17.4111 17.4004 17.4989 17.6123 17.4989 17.8333V22.8146C17.4986 22.9242 17.5199 23.0329 17.5616 23.1343C17.6034 23.2357 17.6647 23.3279 17.7421 23.4055C17.8195 23.4832 17.9115 23.5448 18.0128 23.5868C18.114 23.6289 18.2226 23.6505 18.3323 23.6505L24.1667 23.6667C24.3877 23.6667 24.5996 23.5789 24.7559 23.4226C24.9122 23.2663 25 23.0543 25 22.8333V14.2917L15.3995 6.38854C15.2866 6.29756 15.146 6.24795 15.001 6.24795C14.8561 6.24795 14.7155 6.29756 14.6026 6.38854ZM29.7708 11.7641L25.4167 8.175V0.960937C25.4167 0.795177 25.3508 0.636206 25.2336 0.518996C25.1164 0.401786 24.9574 0.335938 24.7917 0.335938H21.875C21.7092 0.335938 21.5503 0.401786 21.433 0.518996C21.3158 0.636206 21.25 0.795177 21.25 0.960937V4.74271L16.587 0.90625C16.1395 0.53801 15.5779 0.336674 14.9984 0.336674C14.4189 0.336674 13.8574 0.53801 13.4099 0.90625L0.226029 11.7641C0.162741 11.8164 0.11038 11.8806 0.0719388 11.9532C0.0334971 12.0257 0.00972777 12.1052 0.00198892 12.1869C-0.00574994 12.2686 0.00269334 12.3511 0.0268363 12.4296C0.0509793 12.5081 0.0903489 12.581 0.142695 12.6443L1.47082 14.2589C1.52302 14.3223 1.58724 14.3749 1.65978 14.4135C1.73232 14.4521 1.81177 14.4761 1.89358 14.484C1.97538 14.4919 2.05794 14.4835 2.13653 14.4595C2.21511 14.4354 2.28818 14.3961 2.35155 14.3438L14.6026 4.25313C14.7155 4.16215 14.8561 4.11254 15.001 4.11254C15.146 4.11254 15.2866 4.16215 15.3995 4.25313L27.651 14.3438C27.7143 14.3961 27.7872 14.4355 27.8657 14.4596C27.9442 14.4838 28.0267 14.4922 28.1084 14.4845C28.1901 14.4767 28.2696 14.4529 28.3421 14.4145C28.4147 14.3761 28.4789 14.3237 28.5312 14.2604L29.8594 12.6458C29.9117 12.5822 29.9509 12.5089 29.9747 12.4301C29.9986 12.3512 30.0066 12.2685 29.9984 12.1865C29.9902 12.1046 29.9658 12.0251 29.9268 11.9526C29.8877 11.8801 29.8347 11.816 29.7708 11.7641Z" fill="#FFA4DF"/>
                </svg>`,
  work: `<svg width="30" height="28" viewBox="0 0 30 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18.75 18.6875C18.75 19.2055 18.3305 19.625 17.8125 19.625H12.1875C11.6695 19.625 11.25 19.2055 11.25 18.6875V15.875H0V24.3125C0 25.8125 1.3125 27.125 2.8125 27.125H27.1875C28.6875 27.125 30 25.8125 30 24.3125V15.875H18.75V18.6875ZM27.1875 6.5H22.5V3.6875C22.5 2.1875 21.1875 0.875 19.6875 0.875H10.3125C8.8125 0.875 7.5 2.1875 7.5 3.6875V6.5H2.8125C1.3125 6.5 0 7.8125 0 9.3125V14H30V9.3125C30 7.8125 28.6875 6.5 27.1875 6.5ZM18.75 6.5H11.25V4.625H18.75V6.5Z" fill="#FADA4D"/>
                </svg>`,
  school: `<svg width="28" height="30" viewBox="0 0 28 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.125 21.0938V1.40625C27.125 0.626953 26.498 0 25.7188 0H6.5C3.39453 0 0.875 2.51953 0.875 5.625V24.375C0.875 27.4805 3.39453 30 6.5 30H25.7188C26.498 30 27.125 29.373 27.125 28.5938V27.6563C27.125 27.2168 26.9199 26.8184 26.6035 26.5605C26.3574 25.6582 26.3574 23.0859 26.6035 22.1836C26.9199 21.9316 27.125 21.5332 27.125 21.0938ZM8.375 7.85156C8.375 7.6582 8.5332 7.5 8.72656 7.5H21.1484C21.3418 7.5 21.5 7.6582 21.5 7.85156V9.02344C21.5 9.2168 21.3418 9.375 21.1484 9.375H8.72656C8.5332 9.375 8.375 9.2168 8.375 9.02344V7.85156ZM8.375 11.6016C8.375 11.4082 8.5332 11.25 8.72656 11.25H21.1484C21.3418 11.25 21.5 11.4082 21.5 11.6016V12.7734C21.5 12.9668 21.3418 13.125 21.1484 13.125H8.72656C8.5332 13.125 8.375 12.9668 8.375 12.7734V11.6016ZM23.2227 26.25H6.5C5.46289 26.25 4.625 25.4121 4.625 24.375C4.625 23.3438 5.46875 22.5 6.5 22.5H23.2227C23.1113 23.502 23.1113 25.248 23.2227 26.25Z" fill="#7EBAF5"/>
                </svg>`,
};

const formatTime = (rawTime) => {
  const [hours, minutes] = rawTime.split(":");
  return `${hours}:${minutes}`;
};

const updateTaskStatus = (checkbox) => {
  const taskId = checkbox.getAttribute("data-task-id");
  const drawer = document.getElementById("task-details-drawer");
  const drawerTaskId = drawer.getAttribute("data-task-id");

  // If drawer is open for this task, sync the drawer checkbox
  if (drawer.classList.contains("open") && drawerTaskId === taskId) {
    const drawerCheckbox = document.getElementById("drawer-task-checkbox");
    drawerCheckbox.checked = checkbox.checked;
  }

  const formData = new FormData();
  formData.append("task_id", taskId);
  formData.append("status", checkbox.checked ? "completed" : "pending");

  // TODO: Add logic to update task status in backend if needed
  fetch("../tasks/update_task.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => {
      if (!res.ok) {
        throw new Error(`HTTP error! Status: ${res.status}`);
      }
      return res.json();
    })
    .then((data) => {
      if (data.status !== "success") {
        console.error("Failed to update task status:", data.message);
        //revert checkboox state
        checkbox.checked = !checkbox.checked;
        //Re-synce drawer checkbox if open
        if (drawer.classList.contains("open") && drawerTaskId === taskId) {
          document.getElementById("drawer-task-checkbox").checked =
            checkbox.checked;
        }
        alert("Failed to update task status: " + data.message);
      } else {
        console.log(`✅ Task ${taskId} status updated to ${checkbox.checked}`);
      }
    })
    .catch((error) => {
      console.error("Error updating task status: ", error);
      //revert checkboox state
      checkbox.checked = !checkbox.checked;
      //Re-synce drawer checkbox if open
      if (drawer.classList.contains("open") && drawerTaskId === taskId) {
        document.getElementById("drawer-task-checkbox").checked =
          checkbox.checked;
      }
      alert("An error occurred while updating the task: " + error.message);
    });
};

const appendTask = (task) => {
  const tasksList = document.querySelector(".tasks-list");
  const taskBox = document.createElement("div");
  taskBox.className = "task-box";

  // Encode task details as JSON for the data attribute
  const taskDetails = JSON.stringify({
    id: task.id,
    title: task.title,
    date: task.date || "Not specified", // Fallback if date is missing
    time: task.time,
    description: task.description || "No description", // Fallback if description is missing
    category: task.category,
  });
  taskBox.setAttribute("data-task-details", taskDetails);

  const formattedTime = formatTime(task.time);
  const categoryIcon = icons[task.category] || "<span>?</span>";

  taskBox.innerHTML = `
      <div class="task">
        <label for="task-${task.id}" class="task-label custom-checkbox">
          <input type="checkbox" name="task-${task.id}" id="task-${task.id}" data-task-id="${task.id}" />
          <span class="checkmark"></span>
          ${task.title}
        </label>
      </div>
      <div class="info">
        <span class="task-time" data-time="${task.time}">${formattedTime}</span>
        <div class="icons" data-category="${task.category}">
          ${categoryIcon}
        </div>
      </div>
    `;
  tasksList.appendChild(taskBox);

  // Attach event listeners to the new task box
  const checkbox = taskBox.querySelector(`input[data-task-id="${task.id}"]`);
  checkbox.addEventListener("change", () => updateTaskStatus(checkbox));
  taskBox.addEventListener("click", (e) => {
    // Prevent opening drawer when clicking the checkbox
    if (
      e.target.type !== "checkbox" &&
      !e.target.classList.contains("checkmark")
    ) {
      openTaskDrawer(taskBox);
    }
  });
};

const updateTimeDisplay = () => {
  document.querySelectorAll(".task-time").forEach((timeSpan) => {
    const rawTime = timeSpan.getAttribute("data-time");
    timeSpan.textContent = formatTime(rawTime);
  });
};

const insertIcons = () => {
  document.querySelectorAll(".icons").forEach((iconContainer) => {
    const category = iconContainer.getAttribute("data-category");
    if (icons[category]) {
      iconContainer.innerHTML = icons[category];
    } else {
      iconContainer.innerHTML = "<span>?</span>";
    }
  });
};

// Function to open the task drawer and populate it with details
const openTaskDrawer = (taskBox) => {
  const rightPanel = document.querySelector(".right-panel");
  const drawer = document.getElementById("task-details-drawer");
  drawer.style.width = `${rightPanel.offsetWidth}px`;

  const taskDetails = JSON.parse(taskBox.getAttribute("data-task-details"));
  console.log(
    `id:${taskDetails.id}, title:${taskDetails.title}, date:${taskDetails.date},${taskDetails.time}, category:${taskDetails.category}`
  );

  const taskId = taskDetails.id;
  // Set drawer checkbox state to match task checkbox
  const taskCheckbox = document.querySelector(
    `input[data-task-id="${taskId}"]`
  );
  const drawerCheckbox = document.getElementById("drawer-task-checkbox");
  drawerCheckbox.checked = taskCheckbox.checked;

  // Store task ID in drawer for reference
  drawer.setAttribute("data-task-id", taskId);

  const titleElement = document.getElementById("drawer-task-title");
  const dateTimeElement = document.getElementById("drawer-task-date-time");
  const descriptionElement = document.getElementById("drawer-task-description");
  const categoryElement = document.getElementById("drawer-task-category");

  titleElement.textContent = taskDetails.title || "Untitled Task";
  dateTimeElement.textContent = `${taskDetails.date}, ${taskDetails.time}`;
  descriptionElement.textContent = taskDetails.description || "No description";
  categoryElement.textContent = taskDetails.category;

  // Show the drawer
  drawer.classList.add("open");
};

// Function to close the task drawer
const closeTaskDrawer = () => {
  const drawer = document.getElementById("task-details-drawer");
  drawer.classList.remove("open");
};

//TODO: custom checkbox case
document.addEventListener("DOMContentLoaded", () => {
  insertIcons();
  updateTimeDisplay();

  // Attach event listeners to existing checkboxes and task boxes
  document
    .querySelectorAll('input[type="checkbox"][data-task-id]')
    .forEach((checkbox) => {
      checkbox.addEventListener("change", () => updateTaskStatus(checkbox));
    });

  document.querySelectorAll(".task-box").forEach((taskBox) => {
    taskBox.addEventListener("click", (e) => {
      // Prevent opening drawer when clicking the checkbox
      if (
        e.target.type !== "checkbox" &&
        !e.target.classList.contains("checkmark")
      ) {
        openTaskDrawer(taskBox);
      }
    });
  });

  // Add event listener for drawer checkbox
  const drawerCheckbox = document.getElementById("drawer-task-checkbox");
  drawerCheckbox.addEventListener("change", () => {
    const drawer = document.getElementById("task-details-drawer");
    const taskId = drawer.getAttribute("data-task-id");
    const taskCheckbox = document.querySelector(
      `input[data-task-id="${taskId}"]`
    );
    if (taskCheckbox) {
      taskCheckbox.checked = drawerCheckbox.checked;
      // Trigger updateTaskStatus to handle any additional logic
      updateTaskStatus(taskCheckbox);
    }
  });

  //FIXME: Close drawer when clicking outside
  window.addEventListener("click", (e) => {
    const drawer = document.getElementById("task-details-drawer");
    if (e.target === drawer && drawer.classList.contains("open")) {
      closeTaskDrawer();
    }
  });

  const createTaskForm = document.getElementById("create-task-form");
  createTaskForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const formData = new FormData(createTaskForm);
    const submitBtn = createTaskForm.querySelector(".save-btn");
    submitBtn.disabled = true;

    fetch("../tasks/create_task_endpoint.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => {
        if (!res.ok) {
          return res.text().then((text) => {
            console.error("Raw response:", text);
            throw new Error(
              `HTTP error! Status: ${res.status}, Response: ${text}`
            );
          });
          // throw new Error(`HTTP error! Status: ${res.status}`);
        }
        return res.json();
      })
      .then((data) => {
        if (data.status === "success") {
          appendTask(data.task);
          document.getElementById("popupModal").style.display = "none";
          createTaskForm.reset();
        } else {
          console.error("Error:", data.message);
          alert("Failed to create task: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Fetch error:", error);
        alert("An error occurred while creating the task: " + error.message);
      })
      .finally(() => {
        submitBtn.disabled = false;
      });
  });
});
