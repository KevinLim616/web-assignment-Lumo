<?php
include __DIR__ . "./../utils/get_notifications.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../global.css" />
  <link rel="stylesheet" href="../style/side_bar.css" />
  <link rel="stylesheet" href="../style/notification.css" />
  <script src="../include/sideBar.js" defer></script>
  <script src="../user/notification.js" defer></script>
  <title>Notification</title>
</head>

<body>
  <!--navigation drawer-->
  <?php include __DIR__ . "./../include/side_bar.php" ?>
  <main class="content-wrapper">

    <div class="notifications-list">
      <h1 style="display: inline-block">Notification</h1>

      <div class="notifications-container">

        <section style="display: flex">
          <ul>
            <?php
            if (!empty($notifications)) {
              foreach ($notifications as $index => $notification) {
                $created_at = new DateTime($notification['created_at']);
                $formatted_date = $created_at->format("M d");

                $title = htmlspecialchars($notification['title']);
                $full_message = htmlspecialchars($notification['message']);
                $formatted_date = htmlspecialchars($formatted_date);
                $fixed_length = 70;
                $display_text = "$title. ";
                $remaining_length = $fixed_length - strlen($display_text);

                if ($remaining_length > 0) {
                  if (strlen($full_message) > $remaining_length) {
                    $display_message = substr($full_message, 0, $remaining_length - 3) . "...";
                  } else {
                    $display_message = str_pad($full_message, $remaining_length, "");
                  }
                  $display_text .= $display_message;
                } else {
                  $display_text = substr($display_text, 0, $fixed_length - 3) . "...";
                }
                echo "
                    <li class=\"notification\" data-title=\"$title\" data-message=\"$full_message\" data-date=\"{$created_at->format('d/M/Y')}\">
                    $title. $display_message
                    <span class=\"notification-date\">· $formatted_date</span>
                    </li>";
              }
            } else {
              echo "<li>No notification</li>";
            }

            ?>

          </ul>
        </section>
      </div>
    </div>
    <!--notification detail-->
    <div class="notification-details">
      <!--child 1-->
      <div class="notification-title">
        <h3></h3>
        <h6></h6>
      </div>
      <!--child 2-->

      <div class="notification-description">
        <p></p>
      </div>
    </div>

  </main>
</body>

</html>