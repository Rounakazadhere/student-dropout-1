<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $hashed_password, $username);
        if ($stmt->execute()) {
            $success = "Password reset successfully. You can now <a href='login.php'>login</a>.";
        } else {
            $error = "Failed to reset password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reset Password - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label class="block mb-2 font-semibold" for="username">Username</label>
            <input type="text" id="username" name="username" required class="w-full p-2 border border-gray-300 rounded mb-4" />
            <label class="block mb-2 font-semibold" for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required class="w-full p-2 border border-gray-300 rounded mb-4" />
            <label class="block mb-2 font-semibold" for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="w-full p-2 border border-gray-300 rounded mb-6" />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Reset Password</button>
        </form>
    </div>
</body>
</html>
