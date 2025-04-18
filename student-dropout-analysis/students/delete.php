<?php
require_once "../includes/session.php";
requireLogin();
require_once "../includes/db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // First check if student exists
    $stmt = $conn->prepare("SELECT id FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Delete the student
        $delete_stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        
        if ($delete_stmt->execute()) {
            $_SESSION['success_message'] = "Student record deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete student record.";
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['error_message'] = "Student not found.";
    }
    
    $stmt->close();
}

// Redirect back to the view page
header("Location: view.php");
exit();
?>
