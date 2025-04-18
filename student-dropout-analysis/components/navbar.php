<?php
session_start();
?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;800&display=swap');
  nav {
    font-family: 'Nunito', sans-serif;
  }
</style>

<nav class="bg-white shadow-md p-4 flex justify-between items-center sticky top-0 z-50 animate-fadeInDown">
    <div class="text-xl font-extrabold text-[#58cc02] hover:text-[#43c000] transition-colors duration-300">
        <a href="/student-dropout-analysis/dashboard/index.php" class="flex items-center space-x-2">
            <span>🎓</span>
            <span>DropoutAnalyzer</span>
        </a>
    </div>
    <div class="space-x-6 font-bold text-[#4b4b4b]">
        <a href="/student-dropout-analysis/contact.php" class="hover:text-[#58cc02] transition-colors duration-300">Contact</a>
        <a href="/student-dropout-analysis/feedback.php" class="hover:text-[#58cc02] transition-colors duration-300">Feedback</a>
        <?php if (isset($_SESSION['user_name'])): ?>
            <a href="#" class="hover:text-[#58cc02] transition-colors duration-300">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></a>
            <a href="/student-dropout-analysis/auth/logout.php" class="hover:text-red-600 transition-colors duration-300">Logout</a>
        <?php else: ?>
            <a href="/student-dropout-analysis/auth/login.php" class="hover:text-[#58cc02] transition-colors duration-300">Login</a>
            <a href="/student-dropout-analysis/auth/register.php" class="hover:text-[#58cc02] transition-colors duration-300">Signup</a>
        <?php endif; ?>
    </div>
</nav>

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
.animate-fadeInDown {
  animation: fadeInDown 0.8s ease forwards;
}
</style>
