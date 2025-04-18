<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Dropout Analysis - Duolingo Theme</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap');
    body {
      font-family: 'Nunito', sans-serif;
    }
    .gradient-overlay-duo {
      background: linear-gradient(135deg, rgba(88, 204, 2, 0.75), rgba(28, 176, 246, 0.65));
      background-size: 200% 200%;
      animation: gradientMoveDuo 8s ease-in-out infinite;
    }
    @keyframes gradientMoveDuo {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
  </style>
</head>
<body class="bg-gray-100 font-sans">

  <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="text-2xl font-extrabold text-[#58cc02]">🎓 DropoutAnalyzer</div>
      <nav class="space-x-6 text-[#4b4b4b] font-bold">
        <?php
          if (isset($_SESSION['user_name'])) {
            echo '<a href="#" class="hover:text-[#58cc02] transition">Welcome, ' . htmlspecialchars($_SESSION['user_name']) . '</a>';
            echo '<a href="auth/logout.php" class="hover:text-[#58cc02] transition">Logout</a>';
          } else {
            echo '<a href="#" class="hover:text-[#58cc02] transition" onclick="toggleLoginModal()">Login</a>';
            echo '<a href="#" class="hover:text-[#58cc02] transition" onclick="toggleSignupModal()">Signup</a>';
          }
        ?>
      </nav>
    </div>
  </header>

  <section class="relative">
    <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc" alt="Hero Image" class="w-full h-[90vh] object-cover">
    <div class="absolute inset-0 gradient-overlay-duo flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg animate-fadeInDown">Track & Analyze Student Dropouts</h1>
      <p class="text-lg md:text-2xl mb-8 drop-shadow-md animate-fadeInUp">Empowering Institutions with Data-Driven Insights</p>
      <button onclick="toggleLoginModal()" class="bg-[#58cc02] hover:bg-[#43c000] px-8 py-3 rounded-xl text-white text-lg font-bold transition transform hover:scale-110 shadow-lg hover:shadow-2xl uppercase tracking-wider animate-pulse">
        Get Started
      </button>
    </div>
  </section>

  <style>
    @keyframes fadeInDown {
      0% {
        opacity: 0;
        transform: translateY(-20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(20px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    .animate-fadeInDown {
      animation: fadeInDown 1s ease forwards;
    }
    .animate-fadeInUp {
      animation: fadeInUp 1s ease forwards;
    }
    .animate-pulse {
      animation: pulse 2s infinite;
    }
  </style>

  <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative">
       <button onclick="toggleLoginModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-3xl font-light">×</button>
      <h2 class="text-3xl font-extrabold mb-6 text-[#58cc02] text-center">Welcome Back!</h2>
      <form action="auth/login.php" method="POST">
        <input type="text" name="username" placeholder="Username" class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-4 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
        <input type="password" name="password" placeholder="Password" class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-6 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
        <button type="submit" class="w-full bg-[#58cc02] hover:bg-[#43c000] text-white py-3 rounded-xl font-bold text-lg uppercase tracking-wider shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Login</button>
      </form>
      <p class="mt-6 text-center text-sm text-[#777777]">Don't have an account? <a href="#" class="text-[#1cb0f6] hover:underline font-bold" onclick="toggleSignupModal(); toggleLoginModal()">Sign Up Here</a></p>
    </div>
  </div>

  <div id="signupModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden z-50 p-4">
     <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md relative">
       <button onclick="toggleSignupModal()" class="absolute top-3 right-4 text-gray-400 hover:text-gray-700 text-3xl font-light">×</button>
      <h2 class="text-3xl font-extrabold mb-6 text-[#58cc02] text-center">Create Account</h2>
      <form action="auth/register.php" method="POST">
        <input type="text" name="username" placeholder="Username" class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-4 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
        <input type="password" name="password" placeholder="Choose Password" class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-4 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
        <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full border-2 border-gray-200 bg-gray-50 px-4 py-3 rounded-xl mb-6 focus:outline-none focus:border-[#89e219] focus:ring-1 focus:ring-[#89e219]" />
        <button type="submit" class="w-full bg-[#58cc02] hover:bg-[#43c000] text-white py-3 rounded-xl font-bold text-lg uppercase tracking-wider shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">Sign Up</button>
      </form>
      <p class="mt-6 text-center text-sm text-[#777777]">Already have an account? <a href="#" class="text-[#1cb0f6] hover:underline font-bold" onclick="toggleLoginModal(); toggleSignupModal()">Login Here</a></p>
    </div>
  </div>

  <script>
    function toggleLoginModal() {
      document.getElementById("loginModal").classList.toggle("hidden");
    }

    function toggleSignupModal() {
      document.getElementById("signupModal").classList.toggle("hidden");
    }

    document.getElementById('loginModal').addEventListener('click', function(event) {
        if (event.target === this) {
            toggleLoginModal();
        }
    });
     document.getElementById('signupModal').addEventListener('click', function(event) {
        if (event.target === this) {
            toggleSignupModal();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            if (!document.getElementById('loginModal').classList.contains('hidden')) {
                toggleLoginModal();
            }
            if (!document.getElementById('signupModal').classList.contains('hidden')) {
                 toggleSignupModal();
            }
        }
    });
  </script>

</body>
</html>
