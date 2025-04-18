<?php
session_start();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Here you can add code to save the message to database or send email
        $success = "Thank you for contacting us. We will get back to you soon.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Contact Us - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f9fafb;
        }
        .form-container {
            max-width: 600px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .btn-green {
            background-color: #58cc02;
            color: white;
        }
        .btn-green:hover {
            background-color: #43c000;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <?php include "components/navbar.php"; ?>

    <main class="form-container">
        <h1 class="text-3xl font-bold mb-6 text-[#58cc02]">Contact Us</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name" class="block mb-2 font-semibold">Name</label>
            <input type="text" id="name" name="name" required class="w-full border border-gray-300 rounded px-4 py-2 mb-4" />

            <label for="email" class="block mb-2 font-semibold">Email</label>
            <input type="email" id="email" name="email" required class="w-full border border-gray-300 rounded px-4 py-2 mb-4" />

            <label for="message" class="block mb-2 font-semibold">Message</label>
            <textarea id="message" name="message" rows="5" required class="w-full border border-gray-300 rounded px-4 py-2 mb-4"></textarea>

            <button type="submit" class="btn-green px-6 py-3 rounded font-bold uppercase tracking-wide shadow hover:shadow-lg transition">Send Message</button>
        </form>
    </main>

    <?php include "components/footer.php"; ?>
</body>
</html>
