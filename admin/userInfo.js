const ITEMS_PER_PAGE = 3;
let currentPage = 1;
let allUsers = [];

async function fetchUsers() {
  try {
    // Add cache-busting to avoid stale responses
    const response = await fetch("./get_user_info.php?_=" + Date.now());
    if (!response.ok) {
      throw new Error("Failed to fetch user data");
    }
    const data = await response.json();
    if (data.error) {
      console.error("Error from server:", data.error);
      return;
    }
    // Log raw data for debugging
    console.log("Raw JSON response:", data);
    allUsers = data.users || [];
    // Log allUsers to confirm structure
    console.log("allUsers array:", allUsers);
    document.getElementById("total-members").textContent = data.total || 0;
    renderTable();
  } catch (error) {
    console.error("Fetch error:", error);
  }
}

function renderTable() {
  const tbody = document.querySelector("tbody");
  tbody.innerHTML = "";

  const start = (currentPage - 1) * ITEMS_PER_PAGE;
  const end = start + ITEMS_PER_PAGE;
  const paginatedUsers = allUsers.slice(start, end);

  paginatedUsers.forEach((user) => {
    // Log each user object for debugging
    console.log("User object:", user);
    const row = document.createElement("tr");

    // Map notification status to display text and class
    let statusClass = "";
    let statusText = user.notification_status || "No Notifications";
    switch (user.notification_status) {
      case "Read":
        statusClass = "read";
        break;
      case "Delivered":
        statusClass = "delivered";
        break;
      default:
        statusClass = "no-notifications";
        statusText = "No Notifications";
    }

    row.innerHTML = `
                    <td>${user.display_id}</td>
                    <td>${user.username}</td>
                    <td>${user.CreatedTime}</td>
                    <td>${user.task_count}</td>
                    <td><span class="read-receipt ${statusClass}">${statusText}</span></td>
                    <td><button class="action-btn" data-dropdown-id="${user.id}"><i class="bi bi-three-dots-vertical"></i></button></td>
                `;
    tbody.appendChild(row);
  });

  renderPagination();
}

function renderPagination() {
  const totalPages = Math.ceil(allUsers.length / ITEMS_PER_PAGE);
  const pagination = document.getElementById("pagination");
  const ul = pagination.querySelector("ul") || document.createElement("ul");
  ul.className = "pagination justify-content-center";

  ul.innerHTML = ""; // Clear existing items

  const prevLi = document.createElement("li");
  prevLi.className = "page-item" + (currentPage === 1 ? " disabled" : "");
  const prevLink = document.createElement("a");
  prevLink.className = "page-link page-button";
  prevLink.href = "#";
  prevLink.textContent = "Previous";
  prevLink.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentPage > 1) {
      currentPage--;
      renderTable();
    }
  });
  prevLi.appendChild(prevLink);
  ul.appendChild(prevLi);

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    const li = document.createElement("li");
    li.className = "page-item" + (i === currentPage ? " active" : "");
    const link = document.createElement("a");
    link.className = "page-link";
    link.href = "#";
    link.textContent = i;
    link.addEventListener("click", (e) => {
      e.preventDefault();
      currentPage = i;
      renderTable();
    });
    li.appendChild(link);
    ul.appendChild(li);
  }

  // Next button
  const nextLi = document.createElement("li");
  nextLi.className =
    "page-item" + (currentPage === totalPages ? " disabled" : "");
  const nextLink = document.createElement("a");
  nextLink.className = "page-link page-button";
  nextLink.href = "#";
  nextLink.textContent = "Next";
  nextLink.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentPage < totalPages) {
      currentPage++;
      renderTable();
    }
  });
  nextLi.appendChild(nextLink);
  ul.appendChild(nextLi);

  // Replace or append ul to pagination
  if (!pagination.querySelector("ul")) {
    pagination.appendChild(ul);
  }
}

// Fetch users on page load
document.addEventListener("DOMContentLoaded", fetchUsers);
