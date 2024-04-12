<?php
session_start();

// Check if the user is not authenticated, redirect to login page
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: login.php");
    exit();
}

include "connection.php";

// Rest of your code...

?>
<!doctype html>
<html lang="en">

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>student data</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="Registration.php">STUDENT DATA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>
    </nav>

    <div class="container mt-4">
        <?php
        include "connection.php";

        // Set the number of records per page
        $recordsPerPage = 100;

        // Get the current page number
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // Calculate the offset for the SQL query
        $offset = ($page - 1) * $recordsPerPage;

        // Check if a search query is submitted
        if (isset($_GET['search'])) {
            $search = $_GET['search'];

            // Use a prepared statement to prevent SQL injection
            $sql = "SELECT * FROM data1 WHERE name LIKE ? LIMIT ?, ?";
            $stmt = $conn->prepare($sql);

            // Add wildcard '%' to search for partial matches
            $searchParam = "%$search%";
            $stmt->bind_param('sii', $searchParam, $offset, $recordsPerPage);
        } else {
            // If no search query, display all records with pagination
            $sql = "SELECT * FROM data1 LIMIT ?, ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ii', $offset, $recordsPerPage);
        }

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            echo "<table id='example' class='display' data-page='$page'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Sr. No.</th>";
            echo "<th>IMAGE</th>"; // Serial number column
            echo "<th>NAME <input type='text' class='form-control form-control-sm' id='name-filter'placeholder='Name'></th>";
            echo "<th>EMAIL <input type='text' class='form-control form-control-sm' id='email-filter'placeholder='Email'></th>";
            echo "<th>PHONE <input type='text' class='form-control form-control-sm' id='phone-filter'placeholder='Phone'></th>";
            echo "<th>ADMISSION DATE <input type='text' class='form-control form-control-sm' id='joiningdate-filter'placeholder='Addmissiondate'></th>";
            echo "<th>ACTIONS</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";


            $serialNumber = ($page - 1) * $recordsPerPage + 1; // Calculate starting serial number for the page

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$serialNumber}</td>";
                echo "<td><img src='{$row['image']}' height='80px' width='80px'></td>"; // Display serial number
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['phone']}</td>";

                // Check if the 'admissiondate' column exists before accessing it
                if (isset($row['admissiondate'])) {
                    echo "<td>{$row['admissiondate']}</td>";
                } else {
                    echo "<td>N/A</td>"; // Or any other placeholder text if 'admissiondate' is not present
                }

                echo "<td><a class='btn btn-danger' href='editstdata.php?id={$row['id']}'>Edit</a> <a class='btn btn-danger'  href='deletestdata.php?id={$row['id']}'>Delete</a></td>";
                echo "</tr>";
                $serialNumber++; // Increment serial number for the next record
            }

            echo "</tbody>";
            echo "</table>";

            $stmt->close();
        } else {
            // Handle query execution error
            echo "Error executing the query.";
        }

        $conn->close();
        ?>


        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Include DataTables and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                paging: true,
                pageLength: 5,
                lengthMenu: [5, 10, 15, 20],
                initComplete: function() {
                    // Apply the search
                    this.api().columns().every(function() {
                        var that = this;
                        $('input', this.header()).on('keyup change clear', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                }
            });
        });
    </script>
</body>


</html>