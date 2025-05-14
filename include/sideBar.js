const popButton = document.querySelector(".logout-pop");
const popover = document.querySelector(".popover");

// popButton.addEventListener("click", () => {
//   popover.classList.toggle("active");
// });

// document.addEventListener("click", (event) => {
//   if (!popover.contains(event.target) && !popButton.contains(event.target)) {
//     popover.classList.remove("active");
//   }
// });

const navigators = [
  {
    name: "Dashboard",
    url: "../user/dashboard.php",
    icon: ` <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-grid"
              >
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
              </svg>`,
    alt: "Dashboard Icon",
  },
  {
    name: "Calendar",
    url: "../user/diary.php",
    icon: `<svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="#fffef8"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-calendar"
              >
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg>`,
    alt: "Calendar Icon",
  },
  {
    name: "Notifications",
    url: "../user/notification.php",
    icon: `<svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-bell"
              >
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
              </svg>`,
    alt: "Notifications Icon",
  },
];

const validateNavigator = (navigator, index) => {
  const requiredProperties = ["name", "url", "icon", "alt"];
  const missingProperties = requiredProperties.filter(
    (property) =>
      !(property in navigator) ||
      navigator[property] === undefined ||
      navigator[property] === null
  );
  if (missingProperties.length > 0) {
    console.error(
      `Error: Navigator at index ${index} is missing required properties:${missingProperties.join(
        ","
      )}. Skippping this navigator.`
    );
    return false;
  }
  const stringAttributes = ["name", "url", "icon", "alt"];
  const invalidTypes = stringAttributes.filter(
    (attr) => typeof navigator[attr] !== "string"
  );
  if (invalidTypes.length > 0) {
    console.error(
      `Error: Navigator at indext ${index} has invalid types for properties: ${invalidTypes.join(
        ", "
      )}. All must be strings. Skipping this navigator.`
    );
    return false;
  }
  console.log(`Navigator: ${navigator.name} loaded successfully.`);
  return true;
};

const sideBar = () => {
  const navList = document.querySelector(".side-bar nav ul");
  // Clear existing html
  navList.innerHTML = "";

  const currentPath = window.location.pathname;

  navigators.forEach((navigator, index) => {
    if (!validateNavigator(navigator, index)) {
      return;
    }
    const li = document.createElement("li");
    const isActive = currentPath.includes(navigator.url.split("/").pop());
    li.classList = isActive ? "active" : "inactive"; //first child is active by default

    const a = document.createElement("a");
    a.href = navigator.url;

    a.innerHTML = `
    ${navigator.icon}
    <span>${navigator.name}</span>
    `;
    a.setAttribute("aria-label", navigator.alt);
    li.appendChild(a);
    navList.appendChild(li);
  });

  //check if any navigators were rendered
  if (navList.children.length === 0) {
    console.error(
      "Warning: No navigators were redndered. Please check the navigators array for errors."
    );
  }
};

const logoutElement = document.querySelector(".user-icon ul li.logout");
if (logoutElement) {
  logoutElement.addEventListener("click", () => {
    window.location.href = "../authentication/logout.php";
  });
} else {
  console.error("Error: .user-icon ul li.logout element not found.");
}

document.addEventListener("DOMContentLoaded", () => {
  sideBar();
  // popButton.addEventListener("click", () => {
  //   popover.classList.toggle("active");
  // });
  // document.addEventListener("click", (event) => {
  //   if (!popover.contains(event.target) && !popButton.contains(event.target)) {
  //     popover.classList.remove("active");
  //   }
  // });
});
