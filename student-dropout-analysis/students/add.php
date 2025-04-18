<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $department = $_POST['department'] ?? '';
    $year = $_POST['year'] ?? '';
    $gpa = $_POST['gpa'] ?? '';
    $status = $_POST['status'] ?? 'Active';

    $stmt = $conn->prepare("INSERT INTO students (name, department, year, gpa, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssids", $name, $department, $year, $gpa, $status);
    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add student.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Add Student - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include "../components/navbar.php"; ?>

    <main class="p-6 max-w-lg mx-auto bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Add New Student</h1>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label class="block mb-2 font-semibold" for="name">Name</label>
            <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 rounded mb-4" />

            <label class="block mb-2 font-semibold" for="department">Department</label>
            <input type="text" id="department" name="department" required class="w-full p-2 border border-gray-300 rounded mb-4" />

            <label class="block mb-2 font-semibold" for="year">Year</label>
            <input type="number" id="year" name="year" min="1" max="5" required class="w-full p-2 border border-gray-300 rounded mb-4" />

            <label class="block mb-2 font-semibold" for="gpa">GPA</label>
            <input type="number" step="0.01" id="gpa" name="gpa" min="0" max="10" required class="w-full p-2 border border-gray-300 rounded mb-4" />

            <label class="block mb-2 font-semibold" for="status">Status</label>
            <select id="status" name="status" class="w-full p-2 border border-gray-300 rounded mb-6">
                <option value="Active">Active</option>
                <option value="At Risk">At Risk</option>
                <option value="Dropped Out">Dropped Out</option>
            </select>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Add Student</button>
        </form>
    </main>
</body>
</html>
