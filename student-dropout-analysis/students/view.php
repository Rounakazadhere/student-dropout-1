<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department_filter = isset($_GET['department']) ? $_GET['department'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query with filters
$where_conditions = [];
$params = [];
$param_types = '';

if ($search) {
    $where_conditions[] = "name LIKE ?";
    $params[] = "%$search%";
    $param_types .= 's';
}

if ($department_filter) {
    $where_conditions[] = "department = ?";
    $params[] = $department_filter;
    $param_types .= 's';
}

if ($status_filter) {
    $where_conditions[] = "status = ?";
    $params[] = $status_filter;
    $param_types .= 's';
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = "WHERE " . implode(" AND ", $where_conditions);
}

// Get total records for pagination
$count_query = "SELECT COUNT(*) as total FROM students $where_clause";
$stmt = $conn->prepare($count_query);
if (!empty($params)) {
    $stmt->bind_param($param_types, ...$params);
}
$stmt->execute();
$total_records = $stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

// Get students with pagination and filters
$query = "SELECT * FROM students $where_clause ORDER BY id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$param_types .= 'ii';
$params[] = $records_per_page;
$params[] = $offset;
$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Get unique departments for filter
$departments = $conn->query("SELECT DISTINCT department FROM students ORDER BY department")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>View Students - Student Dropout Analysis</title>
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
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold">Student Records</h1>
                <a href="add.php" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i> Add New Student
                </a>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Name</label>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                               class="form-input" placeholder="Search students...">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select name="department" class="form-input">
                            <option value="">All Departments</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?= htmlspecialchars($dept['department']) ?>" 
                                        <?= $department_filter === $dept['department'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dept['department']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="form-input">
                            <option value="">All Status</option>
                            <option value="Active" <?= $status_filter === 'Active' ? 'selected' : '' ?>>Active</option>
                            <option value="At Risk" <?= $status_filter === 'At Risk' ? 'selected' : '' ?>>At Risk</option>
                            <option value="Dropped Out" <?= $status_filter === 'Dropped Out' ? 'selected' : '' ?>>Dropped Out</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </form>
            </div>

            <!-- Students Table -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="data-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4"><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($row['department']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($row['year']) ?></td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($row['gpa']) ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        $status_color = 'gray';
                                        if ($row['status'] === 'Dropped Out') $status_color = 'red';
                                        elseif ($row['status'] === 'At Risk') $status_color = 'yellow';
                                        elseif ($row['status'] === 'Active') $status_color = 'green';
                                        ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $status_color ?>-100 text-<?= $status_color ?>-800">
                                            <?= htmlspecialchars($row['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="edit.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $row['id'] ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this student?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($department_filter) ?>&status=<?= urlencode($status_filter) ?>" 
                                   class="btn-primary">Previous</a>
                            <?php endif; ?>
                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($department_filter) ?>&status=<?= urlencode($status_filter) ?>" 
                                   class="btn-primary">Next</a>
                            <?php endif; ?>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium"><?= ($offset + 1) ?></span> to 
                                    <span class="font-medium"><?= min($offset + $records_per_page, $total_records) ?></span> of 
                                    <span class="font-medium"><?= $total_records ?></span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&department=<?= urlencode($department_filter) ?>&status=<?= urlencode($status_filter) ?>" 
                                           class="<?= $i === $page ? 'bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <?php include "../components/footer.php"; ?>
</body>
</html>
