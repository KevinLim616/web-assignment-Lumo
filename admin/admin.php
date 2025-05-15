<?php
ob_start();
session_start();
include __DIR__ . "./../authentication/login_functions.php";
error_log("admin.php - Session data: " . json_encode($_SESSION));

// Check if user is logged in and has admin role
if (!isset($_SESSION['user']) || !is_array($_SESSION['user']) || empty($_SESSION['user'])) {
    error_log("admin.php - No valid user session, redirecting to login.php");
    header("Location: ../index.php");
    ob_end_clean();
    exit;
}
if ($_SESSION['user']['role'] !== 'admin') {
    error_log("admin.php - Non-admin user attempted access, redirecting to dashboard.php");
    header("Location: ../user/dashboard.php");
    ob_end_clean();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../global.css">
    <script src="./userInfo.js" defer></script>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />

    <link rel="stylesheet" href="../style/side_bar.css">
    <script src="../include/sideBar.js" defer></script>
    <script src="./popover.js" type="module" defer></script>
    <script src="./adminModal.js" type="module" defer></script>
    <link rel="stylesheet" href="../style/admin.css">
</head>

<body>
    <div class="grid-container">
        <?php include __DIR__ . "./../include/side_bar.php" ?>

        <div class="main">
            <div style="display: flex; justify-content: space-between">
                <h2 class="header-title">Welcome, Admin.</h2>
                <button class="btn btn-announcement" id="post-announcement">Post Announcement</button>
            </div>

            <div class="stats-header">
                <h4 class="fw-bold" style="font-size: 28px">Stats</h4>
            </div>

            <div class="stats-box">
                <div class="stat-top">
                    <span id="total-members">10</span>
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="stat-bottom">Members</div>
            </div>

            <!-- Table -->
            <div class="mt-4  table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Join Date</th>
                            <th>Tasks</th>
                            <th>Read Receipt</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

                <nav aria-label="Page navigation example" id="pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link page-button" href="#">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item disabled">
                            <a class="page-link page-button" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>

</html>