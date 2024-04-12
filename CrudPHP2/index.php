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

    <title>Teachers data</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="Registration.php">TEACHERS DATA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="stdata.php">StudentData</a>
                    </li>
                </ul>
            </div>
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
            $sql = "SELECT * FROM teachers WHERE name LIKE ? LIMIT ?, ?";
            $stmt = $conn->prepare($sql);

            // Add wildcard '%' to search for partial matches
            $searchParam = "%$search%";
            $stmt->bind_param('sii', $searchParam, $offset, $recordsPerPage);
        } else {
            // If no search query, display all records with pagination
            $sql = "SELECT * FROM teachers LIMIT ?, ?";
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
            echo "<th>JOINING DATE <input type='text' class='form-control form-control-sm' id='joiningdate-filter'placeholder='JoinDate'></th>";
            echo "<th>ACTIONS</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";


            $serialNumber = ($page - 1) * $recordsPerPage + 1; // Calculate starting serial number for the page

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$serialNumber}</td>";
                echo "<td><img src='{$row['image']}' height='80px' width='80px'></td>"; // Display image
                echo "<td>{$row['name']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['phone']}</td>";
                echo "<td>{$row['joiningdate']}</td>";
                echo "<td><a class='btn btn-danger' href='edittrdata.php?id={$row['id']}'>Edit</a> <a class='btn btn-danger'  href='deletetrdata.php?id={$row['id']}'>Delete</a></td>";
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