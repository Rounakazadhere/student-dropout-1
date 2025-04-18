<?php
session_start();
require_once "includes/session.php";
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notifications - Student Dropout Analysis</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="bg-gray-100 min-h-screen">
  <?php include "components/navbar.php"; ?>
  <div class="flex">
    <?php include "components/sidebar.php"; ?>
    <main class="flex-1 p-8">
      <h1 class="text-3xl font-bold mb-6">Notifications</h1>
      <div class="bg-white p-6 rounded-lg shadow max-w-4xl">
        <p class="mb-4">Here are your recent alerts and updates.</p>
        <ul class="list-disc list-inside space-y-2">
          <?php
          require_once "includes/db.php";
          $result = $conn->query("SELECT title, message, created_at FROM notifications ORDER BY created_at DESC");
          if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<li><strong>" . htmlspecialchars($row['title']) . "</strong>: " . htmlspecialchars($row['message']) . " <em>(" . date('M d, Y', strtotime($row['created_at'])) . ")</em></li>";
              }
          } else {
              echo "<li>No notifications available.</li>";
          }
          ?>
        </ul>
      </div>
    </main>
  </div>
  <?php include "components/footer.php"; ?>
</body>
</html>
