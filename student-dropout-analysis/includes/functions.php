<?php
// Helper functions for the Student Dropout Analysis Website

/**
 * Calculate risk level based on GPA and attendance
 * @param float $gpa Student's GPA
 * @param float $attendance Student's attendance rate
 * @return string Risk level (High, Medium, Low)
 */
function calculateRiskLevel($gpa, $attendance) {
    if ($gpa < 2.0 || $attendance < 60) {
        return 'High';
    } elseif ($gpa < 2.5 || $attendance < 75) {
        return 'Medium';
    } else {
        return 'Low';
    }
}

/**
 * Get status color class for Tailwind CSS
 * @param string $status Student status
 * @return string Color class
 */
function getStatusColorClass($status) {
    switch ($status) {
        case 'Active':
            return 'green';
        case 'At Risk':
            return 'yellow';
        case 'Dropped Out':
            return 'red';
        default:
            return 'gray';
    }
}

/**
 * Format GPA to 2 decimal places
 * @param float $gpa GPA value
 * @return string Formatted GPA
 */
function formatGPA($gpa) {
    return number_format($gpa, 2);
}

/**
 * Get department statistics
 * @param mysqli $conn Database connection
 * @param string $department Department name
 * @return array Department statistics
 */
function getDepartmentStats($conn, $department) {
    $stmt = $conn->prepare("
        SELECT 
            COUNT(*) as total_students,
            COUNT(CASE WHEN status = 'Dropped Out' THEN 1 END) as dropouts,
            COUNT(CASE WHEN status = 'At Risk' THEN 1 END) as at_risk,
            AVG(gpa) as avg_gpa,
            AVG(attendance_rate) as avg_attendance
        FROM students 
        WHERE department = ?
    ");
    $stmt->bind_param("s", $department);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * Get yearly dropout trends
 * @param mysqli $conn Database connection
 * @return array Yearly dropout counts
 */
function getYearlyDropoutTrends($conn) {
    $result = $conn->query("
        SELECT 
            YEAR(created_at) as year,
            COUNT(*) as count
        FROM students 
        WHERE status = 'Dropped Out'
        GROUP BY YEAR(created_at)
        ORDER BY year
    ");
    
    $trends = [];
    while ($row = $result->fetch_assoc()) {
        $trends[$row['year']] = $row['count'];
    }
    return $trends;
}

/**
 * Get GPA distribution
 * @param mysqli $conn Database connection
 * @return array GPA ranges and counts
 */
function getGPADistribution($conn) {
    return $conn->query("
        SELECT 
            CASE 
                WHEN gpa >= 3.5 THEN 'Excellent (3.5-4.0)'
                WHEN gpa >= 3.0 THEN 'Good (3.0-3.49)'
                WHEN gpa >= 2.5 THEN 'Fair (2.5-2.99)'
                ELSE 'Poor (<2.5)'
            END as gpa_range,
            COUNT(*) as count
        FROM students
        GROUP BY 
            CASE 
                WHEN gpa >= 3.5 THEN 'Excellent (3.5-4.0)'
                WHEN gpa >= 3.0 THEN 'Good (3.0-3.49)'
                WHEN gpa >= 2.5 THEN 'Fair (2.5-2.99)'
                ELSE 'Poor (<2.5)'
            END
        ORDER BY gpa_range
    ")->fetch_all(MYSQLI_ASSOC);
}

/**
 * Check if user has admin privileges
 * @return boolean True if user is admin
 */
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Generate CSV export of student data
 * @param mysqli $conn Database connection
 * @return string CSV content
 */
function generateStudentCSV($conn) {
    $result = $conn->query("SELECT * FROM students ORDER BY id");
    
    $output = fopen('php://temp', 'w');
    
    // Add headers
    fputcsv($output, ['ID', 'Name', 'Department', 'Year', 'GPA', 'Attendance Rate', 'Status', 'Created At']);
    
    // Add data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    
    rewind($output);
    $csv = stream_get_contents($output);
    fclose($output);
    
    return $csv;
}

/**
 * Format date for display
 * @param string $date Date string
 * @return string Formatted date
 */
function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

/**
 * Get attendance rate color class
 * @param float $rate Attendance rate
 * @return string Color class
 */
function getAttendanceColorClass($rate) {
    if ($rate >= 90) {
        return 'green';
    } elseif ($rate >= 75) {
        return 'yellow';
    } else {
        return 'red';
    }
}

/**
 * Sanitize and validate input
 * @param string $input Input string
 * @return string Sanitized input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

/**
 * Check if student exists
 * @param mysqli $conn Database connection
 * @param int $id Student ID
 * @return boolean True if student exists
 */
function studentExists($conn, $id) {
    $stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}
?>
