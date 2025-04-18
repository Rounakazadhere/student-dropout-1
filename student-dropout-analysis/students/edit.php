<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$success_message = '';
$error_message = '';

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    header("Location: view.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $year = $_POST['year'] ?? '';
    $gpa = $_POST['gpa'] ?? '';
    $attendance_rate = $_POST['attendance_rate'] ?? '';
    $status = $_POST['status'] ?? '';

    $stmt = $conn->prepare("UPDATE students SET name = ?, department = ?, year = ?, gpa = ?, attendance_rate = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssiidsi", $name, $department, $year, $gpa, $attendance_rate, $status, $id);
    
    if ($stmt->execute()) {
        $success_message = "Student record updated successfully!";
    } else {
        $error_message = "Failed to update student record.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Student - Student Dropout Analysis</title>
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
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold">Edit Student</h1>
                        <a href="view.php" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-2"></i> Back to List
                        </a>
                    </div>

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
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Full Name
                            </label>
                            <input type="text" id="name" name="name" 
                                   value="<?= htmlspecialchars($student['name']) ?>"
                                   class="form-input" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="department">
                                Department
                            </label>
                            <select id="department" name="department" class="form-input" required>
                                <option value="Computer Science" <?= $student['department'] === 'Computer Science' ? 'selected' : '' ?>>Computer Science</option>
                                <option value="Electrical Engineering" <?= $student['department'] === 'Electrical Engineering' ? 'selected' : '' ?>>Electrical Engineering</option>
                                <option value="Mechanical Engineering" <?= $student['department'] === 'Mechanical Engineering' ? 'selected' : '' ?>>Mechanical Engineering</option>
                                <option value="Civil Engineering" <?= $student['department'] === 'Civil Engineering' ? 'selected' : '' ?>>Civil Engineering</option>
                                <option value="Business Administration" <?= $student['department'] === 'Business Administration' ? 'selected' : '' ?>>Business Administration</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="year">
                                    Year
                                </label>
                                <input type="number" id="year" name="year" 
                                       value="<?= htmlspecialchars($student['year']) ?>"
                                       min="1" max="5" class="form-input" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="gpa">
                                    GPA
                                </label>
                                <input type="number" id="gpa" name="gpa" step="0.01" 
                                       value="<?= htmlspecialchars($student['gpa']) ?>"
                                       min="0" max="10" class="form-input" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="attendance_rate">
                                Attendance Rate (%)
                            </label>
                            <input type="number" id="attendance_rate" name="attendance_rate" step="0.01" 
                                   value="<?= htmlspecialchars($student['attendance_rate']) ?>"
                                   min="0" max="100" class="form-input" required>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                                Status
                            </label>
                            <select id="status" name="status" class="form-input" required>
                                <option value="Active" <?= $student['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                                <option value="At Risk" <?= $student['status'] === 'At Risk' ? 'selected' : '' ?>>At Risk</option>
                                <option value="Dropped Out" <?= $student['status'] === 'Dropped Out' ? 'selected' : '' ?>>Dropped Out</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Save Changes
                            </button>
                            <a href="view.php" class="btn-danger">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <?php include "../components/footer.php"; ?>
</body>
</html>
