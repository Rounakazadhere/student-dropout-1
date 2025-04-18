<?php
session_start();
require_once "../includes/session.php";
requireLogin();

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once "../includes/db.php";

$title = $message = "";
$title_err = $message_err = $success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter a title.";
    } else {
        $title = trim($_POST["title"]);
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter a message.";
    } else {
        $message = trim($_POST["message"]);
    }

    // Insert into database if no errors
    if (empty($title_err) && empty($message_err)) {
        $stmt = $conn->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $message);
        if ($stmt->execute()) {
            $success_msg = "Notification posted successfully.";
            $title = $message = "";
        } else {
            $message_err = "Failed to post notification. Please try again.";
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
    <title>Post Notification - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include "../components/navbar.php"; ?>
    <div class="flex">
        <?php include "../components/sidebar.php"; ?>
        <main class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6">Post Notification</h1>
            <?php if ($success_msg): ?>
                <div class="bg-green-100 text-green-700 p-4 rounded mb-4"><?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>
            <form action="post_notification.php" method="post" class="bg-white p-6 rounded shadow max-w-lg">
                <div class="mb-4">
                    <label for="title" class="block font-semibold mb-1">Title</label>
                    <input type="text" name="title" id="title" value="<?= htmlspecialchars($title) ?>" class="w-full border border-gray-300 rounded px-3 py-2" />
                    <?php if ($title_err): ?>
                        <p class="text-red-600 mt-1"><?= htmlspecialchars($title_err) ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="message" class="block font-semibold mb-1">Message</label>
                    <textarea name="message" id="message" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"><?= htmlspecialchars($message) ?></textarea>
                    <?php if ($message_err): ?>
                        <p class="text-red-600 mt-1"><?= htmlspecialchars($message_err) ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">Post Notification</button>
            </form>
        </main>
    </div>
    <?php include "../components/footer.php"; ?>
</body>
</html>
