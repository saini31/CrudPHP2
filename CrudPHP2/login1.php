<?php
session_start();

// Replace these with database credentials
include "connection.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $username = $_POST["email"];
    $password = $_POST["password"];

    // Use prepared statements to prevent SQL injection

    $stmt_teacher = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
    if (!$stmt_teacher) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt_teacher->bind_param('s', $username);
    $stmt_teacher->execute();
    $result_teacher = $stmt_teacher->get_result();

    $stmt_student = $conn->prepare("SELECT * FROM data1 WHERE email = ?");
    if (!$stmt_student) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt_student->bind_param('s', $username);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    // Check if a user with the given username exists in the teachers table
    // Check if a user with the given username exists in the teachers table
    if ($result_teacher->num_rows > 0) {
        $row = $result_teacher->fetch_assoc();
        // Verify password
        if ($password == $row['password']) {
            // Set session variable to mark user as authenticated
            $_SESSION["authenticated"] = true;
            // Redirect to teacher page
            header("Location: index.php");
            exit();
        }
    }

    // Check if a user with the given username exists in the students table
    elseif ($result_student->num_rows > 0) {
        $row = $result_student->fetch_assoc();
        // Verify password
        if ($password === $row['password']) {
            // Set session variable to mark user as authenticated
            $_SESSION["authenticated"] = true;
            // Redirect to student page
            header("Location: stdata.php");
            exit();
        }
    }

    // If user is not found in either table or password doesn't match, redirect back to the login page with an error message
    header("Location: login.php?error=1");
    exit();
}

// Close the database connection
$stmt_teacher->close();
$stmt_student->close();
$conn->close();
