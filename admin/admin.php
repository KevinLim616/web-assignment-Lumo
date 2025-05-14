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
    <link rel="stylesheet" href="../style/side_bar.css">
    <script src="../include/sideBar.js" defer></script>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
    <style>
        .grid-container {
            display: grid;
            grid-template-columns: 200px 1fr;
            height: 100vh;
            grid-template-areas: "sidebar main";
        }

        .sidebar {

            grid-area: sidebar;
        }


        .main {
            grid-area: main;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header-title {
            font-weight: 700;
            font-size: 36px;
        }

        .stats-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            margin-bottom: 4px;
        }

        .stats-box {
            background-color: black;
            color: white;
            border-radius: 10px;
            padding: 15px 20px;
            width: 200px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .stats-box .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.5rem;
        }

        .stats-box .stat-bottom {
            font-size: 1rem;
        }

        .btn-announcement {
            background-color: black;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .pagination {
            margin-top: 20px;
        }

        .pagination .page-item.active .page-link {
            background-color: #f4b4ef;
            border: none;
            color: white;
        }

        .status-read {
            background-color: #e3fff2;
            color: #38c993;
            padding: 2px 10px 2px 10px;
            border-radius: 24px;
            display: inline-block;
        }

        .status-delivered {
            background-color: #e3fff2;
            color: #38c993;
            padding: 2px 12px 2px 12px;
            border-radius: 24px;
            display: inline-block;
        }

        h4 {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="grid-container">
        <?php include __DIR__ . "./../include/side_bar.php" ?>

        <div class="main">
            <div style="display: flex; justify-content: space-between">
                <h2 class="header-title">Welcome, Admin.</h2>
                <button class="btn btn-announcement">Post Announcement</button>
            </div>

            <div class="stats-header">
                <h4 class="fw-bold" style="font-size: 28px">Stats</h4>
            </div>

            <div class="stats-box">
                <div class="stat-top">
                    <span>10</span>
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="stat-bottom">Members</div>
            </div>

            <!-- Table -->
            <div class="mt-4">
                <table class="table">
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
                        <tr>
                            <td>#20462</td>
                            <td>John Doe</td>
                            <td>13/05/2022</td>
                            <td>2</td>
                            <td><span class="status-delivered">Delivered</span></td>
                            <td><i class="bi bi-three-dots-vertical"></i></td>
                        </tr>
                        <tr>
                            <td>#18933</td>
                            <td>Mathew</td>
                            <td>22/05/2022</td>
                            <td>3</td>
                            <td><span class="status-read">Read</span></td>
                            <td><i class="bi bi-three-dots-vertical"></i></td>
                        </tr>
                        <tr>
                            <td>#45169</td>
                            <td>Arthur</td>
                            <td>15/06/2022</td>
                            <td>1</td>
                            <td><span class="status-read">Read</span></td>
                            <td><i class="bi bi-three-dots-vertical"></i></td>
                        </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</body>

</html>