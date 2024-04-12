<?php
include "connection.php";

if (isset($_GET["id"])) {
    $id = $_GET['id'];

    // Use prepared statement to prevent SQL injection
    $sql = "DELETE FROM teachers WHERE id=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Check if preparation failed
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param('i', $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Check if deletion was successful
        $stmt->close();
        $conn->close();
        header("Location: /curdPHP/index.php");
        exit;
    } else {
        // Handle case where no rows were affected (ID not found)
        $stmt->close();
        $conn->close();
        header("Location: /curdPHP/index.php?error=1");
        exit;
    }
} else {
    // Redirect to index.php if ID is not set
    header("Location: /curdPHP/index.php");
    exit;
}
