<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";

// Fetch summary statistics
$total_students = 0;
$total_dropouts = 0;
$at_risk_students = 0;

$result = $conn->query("SELECT COUNT(*) as total FROM students");
if ($result) {
    $total_students = $result->fetch_assoc()['total'];
}

$result = $conn->query("SELECT COUNT(*) as dropouts FROM students WHERE status = 'Dropped Out'");
if ($result) {
    $total_dropouts = $result->fetch_assoc()['dropouts'];
}

$result = $conn->query("SELECT COUNT(*) as at_risk FROM students WHERE status = 'At Risk'");
if ($result) {
    $at_risk_students = $result->fetch_assoc()['at_risk'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen dashboard-bg">
    <?php include "../components/navbar.php"; ?>

    <div class="flex">
        <?php include "../components/sidebar.php"; ?>

        <main class="flex-1 p-8">
            <div class="hero-pattern text-white rounded-lg p-8 mb-8 animate-fadeInDown">
                <h1 class="text-4xl font-bold mb-4">Welcome to Student Dropout Analysis</h1>
                <p class="text-lg opacity-90">Monitor, analyze, and prevent student dropouts effectively.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-3 mr-4">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-green-600"><?= $total_students ?></div>
                            <div class="text-gray-600">Total Students</div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="rounded-full bg-red-100 p-3 mr-4">
                            <i class="fas fa-user-minus text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-red-600"><?= $total_dropouts ?></div>
                            <div class="text-gray-600">Dropouts</div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 mr-4">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <div class="text-4xl font-bold text-yellow-600"><?= $at_risk_students ?></div>
                            <div class="text-gray-600">At-Risk Students</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="chart-container bg-white p-6 rounded-lg shadow animate-fadeInLeft">
                    <h2 class="text-xl font-semibold mb-4 text-green-700">Dropout Trends</h2>
                    <canvas id="dropoutTrendsChart"></canvas>
                </div>

                <div class="chart-container bg-white p-6 rounded-lg shadow animate-fadeInRight">
                    <h2 class="text-xl font-semibold mb-4 text-green-700">Department Analysis</h2>
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>

            <div class="chart-container mt-6 bg-white p-6 rounded-lg shadow animate-fadeInUp">
                <h2 class="text-xl font-semibold mb-4 text-green-700">Risk Analysis by GPA</h2>
                <canvas id="gpaChart"></canvas>
            </div>

            <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition-shadow cursor-pointer">
                    <h3 class="text-lg font-semibold mb-2">User Profile</h3>
                    <p>Manage your profile and settings.</p>
                    <a href="/student-dropout-analysis/auth/profile.php" class="text-green-600 hover:underline mt-2 inline-block">Go to Profile</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition-shadow cursor-pointer">
                    <h3 class="text-lg font-semibold mb-2">Notifications</h3>
                    <p>View recent alerts and updates.</p>
                    <a href="/student-dropout-analysis/notifications.php" class="text-green-600 hover:underline mt-2 inline-block">View Notifications</a>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-xl transition-shadow cursor-pointer">
                    <h3 class="text-lg font-semibold mb-2">Export Reports</h3>
                    <p>Download reports in CSV or PDF format.</p>
                    <a href="/student-dropout-analysis/reports.php" class="text-green-600 hover:underline mt-2 inline-block">Export Now</a>
                </div>
            </div>

            <div class="mt-10">
                <?php include "../components/alerts.php"; ?>
            </div>
        </main>
    </div>

    <?php include "../components/footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Dropout Trends Chart
        new Chart(document.getElementById('dropoutTrendsChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Dropouts',
                    data: [12, 19, 15, 17, 14, 15],
                    borderColor: '#58cc02',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(88, 204, 2, 0.1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Department Chart
        new Chart(document.getElementById('departmentChart'), {
            type: 'bar',
            data: {
                labels: ['CS', 'EE', 'ME', 'CE', 'BBA'],
                datasets: [{
                    label: 'Students at Risk',
                    data: [15, 12, 8, 9, 7],
                    backgroundColor: '#a3d977'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // GPA Chart
        new Chart(document.getElementById('gpaChart'), {
            type: 'scatter',
            data: {
                datasets: [{
                    label: 'Students',
                    data: [
                        {x: 2.1, y: 85},
                        {x: 2.5, y: 75},
                        {x: 3.2, y: 60},
                        {x: 3.8, y: 45},
                        {x: 4.0, y: 30}
                    ],
                    backgroundColor: '#58cc02'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'GPA'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Risk Score'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
