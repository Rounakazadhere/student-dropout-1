<?php
session_start();
require_once "../includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple user authentication example (replace with real validation)
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $username;
            header("Location: ../dashboard/index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Student Dropout Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap');
      body {
        font-family: 'Nunito', sans-serif;
        background-image: url('https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=1470&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      .form-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        border-radius: 1rem;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
        max-width: 400px;
        margin: auto;
        padding: 2rem;
        width: 90%;
      }
      a.theme-link {
        color: #58cc02;
        font-weight: 700;
      }
      a.theme-link:hover {
        text-decoration: underline;
      }
    </style>
</head>
<body class="font-sans min-h-screen flex flex-col">

  <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="text-2xl font-extrabold text-[#58cc02]">🎓 DropoutAnalyzer</div>
      <nav class="space-x-6 text-[#4b4b4b] font-bold">
        <?php
          if (isset($_SESSION['user_name'])) {
            echo '<a href="#" class="hover:text-[#58cc02] transition">Welcome, ' . htmlspecialchars($_SESSION['user_name']) . '</a>';
            echo '<a href="logout.php" class="hover:text-[#58cc02] transition">Logout</a>';
          } else {
            echo '<a href="login.php" class="hover:text-[#58cc02] transition">Login</a>';
            echo '<a href="register.php" class="hover:text-[#58cc02] transition">Signup</a>';
          }
        ?>
      </nav>
    </div>
  </header>

  <main class="flex-grow flex items-center justify-center">
    <div class="form-container">
      <h2 class="text-3xl font-extrabold mb-6 text-[#58cc02] text-center">Login</h2>
      <?php if (!empty($error)): ?>
          <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST" action="">
          <label class="block mb-2 font-semibold" for="username">Username</label>
          <input type="text" id="username" name="username" required class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-4 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
          <label class="block mb-2 font-semibold" for="password">Password</label>
          <input type="password" id="password" name="password" required class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-6 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
          <button type="submit" class="w-full bg-[#58cc02] hover:bg-[#43c000] text-white py-3 rounded-xl font-bold text-lg uppercase tracking-wider shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Login</button>
      </form>
      <p class="mt-6 text-center text-sm text-[#777777]">Don't have an account? <a href="register.php" class="theme-link">Sign Up Here</a></p>
    </div>
  </main>

</body>
</html>
