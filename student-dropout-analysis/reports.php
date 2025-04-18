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
  <title>Export Reports - Student Dropout Analysis</title>
  <script>
    tailwind.config = {
      darkMode: 'class',
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body class="bg-gray-100 min-h-screen reports-bg">
  <?php include "components/navbar.php"; ?>
  <div class="flex">
    <?php include "components/sidebar.php"; ?>
    <main class="flex-1 p-8">
      <h1 class="text-3xl font-bold mb-6">Export Reports</h1>
      <div class="bg-white p-8 rounded-lg shadow-lg max-w-3xl mx-auto">
        <h1 class="text-4xl font-extrabold mb-6 text-center text-gray-800">Export Reports</h1>
        <p class="mb-6 text-center text-gray-600 text-lg">Download reports in CSV or PDF format.</p>
        <form action="export_reports.php" method="POST" class="space-y-6">
          <div>
            <label for="reportType" class="block text-lg font-semibold mb-2 text-gray-700">Select Report Type</label>
            <select id="reportType" name="reportType" class="form-input w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-300" required>
              <option value="" disabled selected>-- Select --</option>
              <option value="dropout">Dropout Report</option>
              <option value="attendance">Attendance Report</option>
              <option value="performance">Performance Report</option>
            </select>
          </div>
          <div>
            <label for="format" class="block text-lg font-semibold mb-2 text-gray-700">Select Format</label>
            <select id="format" name="format" class="form-input w-full border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-300" required>
              <option value="" disabled selected>-- Select --</option>
              <option value="csv">CSV</option>
              <option value="pdf">PDF</option>
            </select>
          </div>
          <div class="text-center">
            <button type="submit" class="btn-primary px-10 py-3 text-lg font-semibold">Export</button>
          </div>
        </form>
      </div>
    </main>
  </div>
  <?php include "components/footer.php"; ?>
</body>
</html>
