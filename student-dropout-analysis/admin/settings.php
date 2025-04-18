<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";
require_once "../includes/functions.php";

if (!isAdmin()) {
    header("Location: ../dashboard/index.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update risk threshold settings
    $gpa_threshold = $_POST['gpa_threshold'] ?? 2.0;
    $attendance_threshold = $_POST['attendance_threshold'] ?? 75;
    
    // In a real application, you would save these to a settings table
    // For now, we'll just show a success message
    $success_message = "Settings updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>System Settings - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include "../components/navbar.php"; ?>

    <div class="flex">
        <?php include "../components/sidebar.php"; ?>

        <main class="flex-1 p-8">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h1 class="text-2xl font-bold mb-6">System Settings</h1>

                    <?php if ($success_message): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($success_message) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error_message): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">Risk Assessment Thresholds</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="gpa_threshold">
                                        GPA Threshold
                                    </label>
                                    <input type="number" id="gpa_threshold" name="gpa_threshold" 
                                           value="2.0" step="0.1" min="0" max="4"
                                           class="form-input">
                                    <p class="text-sm text-gray-600 mt-1">
                                        Students below this GPA will be marked as at risk
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="attendance_threshold">
                                        Attendance Threshold (%)
                                    </label>
                                    <input type="number" id="attendance_threshold" name="attendance_threshold" 
                                           value="75" step="1" min="0" max="100"
                                           class="form-input">
                                    <p class="text-sm text-gray-600 mt-1">
                                        Students below this attendance rate will be marked as at risk
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">Data Management</h2>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-semibold">Export Student Data</h3>
                                        <p class="text-sm text-gray-600">Download all student records as CSV</p>
                                    </div>
                                    <a href="export_csv.php" class="btn-primary">
                                        <i class="fas fa-download mr-2"></i> Export CSV
                                    </a>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <h3 class="font-semibold">Backup Database</h3>
                                        <p class="text-sm text-gray-600">Create a backup of all system data</p>
                                    </div>
                                    <button type="button" class="btn-primary">
                                        <i class="fas fa-database mr-2"></i> Create Backup
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h2 class="text-xl font-semibold mb-4">Email Notifications</h2>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="notify_risk" name="notify_risk" class="form-checkbox h-5 w-5 text-blue-600">
                                    <label class="ml-2 block text-gray-700" for="notify_risk">
                                        Send notifications when students are marked as at risk
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="notify_dropout" name="notify_dropout" class="form-checkbox h-5 w-5 text-blue-600">
                                    <label class="ml-2 block text-gray-700" for="notify_dropout">
                                        Send notifications for student dropouts
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include "../components/footer.php"; ?>
</body>
</html>
