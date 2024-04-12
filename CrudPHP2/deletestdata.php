<?php
include "connection.php";

// Check if the ID parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = $_GET['id'];
    $id = mysqli_real_escape_string($conn, $id);

    // Prepare and execute the SQL statement
    $sql = "DELETE FROM data1 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // Assuming id is an integer
    $stmt->execute();
    $stmt->close();
}

// Redirect to stdata.php after deletion
header('Location: /curdPHP/stdata.php');
exit;
