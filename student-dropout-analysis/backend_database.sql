-- Create and use the database
CREATE DATABASE IF NOT EXISTS student_dropout_analysis;
USE student_dropout_analysis;

-- Users table for authentication and roles
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'faculty') NOT NULL DEFAULT 'faculty',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table to store student records
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    gpa DECIMAL(3,2) NOT NULL,
    attendance_rate DECIMAL(5,2) DEFAULT 100.00,
    status ENUM('Active', 'At Risk', 'Dropped Out') NOT NULL DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample admin user (password: admin123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$8tPz8F8UbqpKg9.7g3KfkOzgvKm5NwPuaB5Aq0.6UW6IKgMzBGy4q', 'admin');

-- Insert sample faculty user (password: faculty123)
INSERT INTO users (username, password, role) VALUES 
('faculty', '$2y$10$YF6tWQhOH7tSQGCw8.c8puTqE9wrtxdW2V7XZY2P1fYPIQC0FQ5Hy', 'faculty');

-- Insert sample student data
INSERT INTO students (name, department, year, gpa, attendance_rate, status) VALUES 
('John Doe', 'Computer Science', 3, 3.75, 95.50, 'Active'),
('Jane Smith', 'Electrical Engineering', 2, 2.80, 75.00, 'At Risk'),
('Mike Johnson', 'Mechanical Engineering', 4, 3.20, 88.50, 'Active'),
('Sarah Williams', 'Civil Engineering', 1, 3.90, 98.00, 'Active'),
('Tom Brown', 'Business Administration', 3, 2.10, 65.00, 'Dropped Out'),
('Emily Davis', 'Computer Science', 2, 3.45, 92.00, 'Active'),
('David Wilson', 'Electrical Engineering', 4, 2.95, 78.00, 'At Risk'),
('Lisa Anderson', 'Mechanical Engineering', 1, 3.60, 94.50, 'Active'),
('James Taylor', 'Civil Engineering', 3, 2.50, 70.00, 'At Risk'),
('Mary Martin', 'Business Administration', 2, 3.85, 96.00, 'Active');

-- Create indexes for better performance
CREATE INDEX idx_department ON students(department);
CREATE INDEX idx_status ON students(status);
CREATE INDEX idx_gpa ON students(gpa);
CREATE INDEX idx_attendance ON students(attendance_rate);

-- Create view for at-risk students
CREATE VIEW v_at_risk_students AS
SELECT * FROM students 
WHERE status = 'At Risk' 
OR (gpa < 3.0 AND attendance_rate < 80.0);

-- Create view for department statistics
CREATE VIEW v_department_stats AS
SELECT 
    department,
    COUNT(*) as total_students,
    COUNT(CASE WHEN status = 'Dropped Out' THEN 1 END) as dropouts,
    COUNT(CASE WHEN status = 'At Risk' THEN 1 END) as at_risk,
    AVG(gpa) as avg_gpa,
    AVG(attendance_rate) as avg_attendance
FROM students
GROUP BY department;
