<?php
session_start();
require_once "../includes/session.php";
requireLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Profile - Student Dropout Analysis</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="bg-gray-100 min-h-screen">
  <?php include "../components/navbar.php"; ?>
  <div class="flex">
    <?php include "../components/sidebar.php"; ?>
    <main class="flex-1 p-8">
      <h1 class="text-3xl font-bold mb-6">User Profile</h1>
      <div class="bg-white p-6 rounded-lg shadow max-w-3xl">
        <p class="mb-4">This is your profile page. You can update your personal information here.</p>
        <form action="profile_update.php" method="POST" class="space-y-4">
          <div>
            <label for="username" class="block font-semibold mb-1">Username</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" class="form-input" readonly />
          </div>
          <div>
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" class="form-input" />
          </div>
          <div>
            <label for="password" class="block font-semibold mb-1">New Password</label>
            <input type="password" id="password" name="password" placeholder="Enter new password" class="form-input" />
          </div>
          <div>
            <button type="submit" class="btn-primary">Update Profile</button>
          </div>
        </form>
      </div>
    </main>
  </div>
  <?php include "../components/footer.php"; ?>
</body>
</html>
