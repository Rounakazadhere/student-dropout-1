<aside class="w-64 bg-white shadow-md min-h-screen p-4 animate-fadeInLeft">
    <nav class="space-y-4">
        <a href="/student-dropout-analysis/dashboard/index.php" class="block px-3 py-2 rounded hover:bg-green-100 text-[#58cc02] font-semibold transition-colors duration-300">Dashboard</a>
        <a href="/student-dropout-analysis/students/view.php" class="block px-3 py-2 rounded hover:bg-green-100 text-[#58cc02] font-semibold transition-colors duration-300">Students</a>
        <a href="/student-dropout-analysis/admin/manage_users.php" class="block px-3 py-2 rounded hover:bg-green-100 text-[#58cc02] font-semibold transition-colors duration-300">Admin Panel</a>
        <a href="/student-dropout-analysis/contact.php" class="block px-3 py-2 rounded hover:bg-green-100 text-[#58cc02] font-semibold transition-colors duration-300">Contact</a>
        <a href="/student-dropout-analysis/feedback.php" class="block px-3 py-2 rounded hover:bg-green-100 text-[#58cc02] font-semibold transition-colors duration-300">Feedback</a>
        <a href="/student-dropout-analysis/auth/logout.php" class="block px-3 py-2 rounded hover:bg-red-100 text-red-700 font-semibold transition-colors duration-300">Logout</a>
    </nav>
</aside>

<style>
@keyframes fadeInLeft {
  0% {
    opacity: 0;
    transform: translateX(-20px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}
.animate-fadeInLeft {
  animation: fadeInLeft 0.8s ease forwards;
}
</style>
