# Student Dropout Analysis

## Project Overview
Student Dropout Analysis is a web application designed to manage and analyze student dropout data. The system provides functionalities for user authentication, student management, notifications, reporting, and feedback collection. It is built using PHP for the backend, with a modular structure for components and assets.

## Features
- User authentication (login, registration, profile management, password reset)
- Student management (add, edit, view, delete student records)
- Notifications system for sending and managing alerts
- Dashboard for overview and quick access to features
- Reporting module to generate and view reports
- Feedback system for collecting user feedback
- Admin panel for managing users and system settings
- Responsive UI components including navbar, sidebar, footer, and alerts
- Dark mode support via JavaScript

## Installation
1. Ensure you have a local server environment such as XAMPP installed with PHP and MySQL.
2. Clone or download the project files into your web server's root directory (e.g., `htdocs` in XAMPP).
3. Import the database schema from `backend_database.sql` using phpMyAdmin or MySQL CLI.
4. Configure database connection settings in `includes/db.php`.
5. Start your local server and navigate to the project URL (e.g., `http://localhost/student-dropout-analysis`).

## Usage
- Access the login page at `/auth/login.php` to sign in.
- Register a new user at `/auth/register.php`.
- Manage student records under the `/students/` directory.
- View and manage notifications via `notifications.php` and admin notification scripts.
- Access the dashboard at `/dashboard/index.php`.
- Submit and view feedback through `feedback.php`.
- Generate reports using `reports.php`.
- Admin users can manage users and settings in the `/admin/` directory.

## File Structure
```
/admin/                 # Admin related scripts and SQL files
/auth/                  # Authentication related pages
/assets/                # CSS and JavaScript assets
/components/            # Reusable UI components (navbar, sidebar, footer, alerts)
/dashboard/             # Dashboard page
/includes/              # Core includes like database connection, functions, session management
/students/              # Student management pages
backend_database.sql    # Database schema
README.md               # This file
index.php               # Main landing page
notifications.php       # Notifications page
reports.php             # Reports page
feedback.php            # Feedback page
```

## Technologies Used
- PHP
- MySQL
- HTML/CSS
- JavaScript

## Contributing
Contributions are welcome! Please fork the repository and submit a pull request for any enhancements or bug fixes.

## License
This project is licensed under the MIT License.
