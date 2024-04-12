<?php session_start();

// Replace these with database credentials
include "connection.php";

$errors = []; // Initialize an array to store validation errors

if (isset($_POST['submit'])) {
    // Retrieve form data
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "image/" . $filename;
    move_uploaded_file($tempname, $folder);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    // Assuming you have a way to determine the user type, for example, a dropdown in your registration form
    $userType = $_POST['usertype']; // Assuming this is how you retrieve the user type from the form
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['conform_pass']);

    // Validate input
    if (empty($filename)) {
        $errors['image'] = "Please upload an image";
    }

    if (empty($name)) {
        $errors['name'] = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors['name'] = "Only letters and white space allowed";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    }

    if (empty($phone)) {
        $errors['phone'] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors['phone'] = "Invalid phone number format";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Password must be at least 6 characters long";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        // SQL query to insert data into the database
        $tableName = ($userType == 'teacher') ? 'teachers' : 'data1';

        // SQL query to insert data into the appropriate table
        $q = "INSERT INTO `$tableName` (`image`, `name`, `email`, `phone`, `password`) VALUES ('$folder', '$name', '$email', '$phone', '$password')";
        $query = mysqli_query($conn, $q);

        // Check if the query was successful
        if ($query) {
            // Redirect to the login page
            echo "Login created successfully";
            header('location:/curdPHP/login.php');
            exit;
        } else {
            // Display error message if the query fails
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>








<!DOCTYPE html>
<html>

<head>
    <title>USER REGISTRATION</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Include your body content here -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="login.php"> WELOCME!!!</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="login.php">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="col-lg-6 m-auto">
        <form method="post" enctype="multipart/form-data">

            <br><br>
            <div class="card">

                <div class="card-header bg-primary">
                    <h1 class="text-white text-center">Create New Member</h1>
                </div><br>
                <label>IMAGE</label>
                <input type="file" name="uploadfile" class="form-control">
                <label> NAME: </label>
                <input type="text" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
                <?php if (isset($errors['name'])) echo '<p class="text-danger">' . $errors['name'] . '</p>'; ?> <br>

                <label> EMAIL: </label>
                <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <?php if (isset($errors['email'])) echo '<p class="text-danger">' . $errors['email'] . '</p>'; ?> <br>

                <label> PHONE: </label>
                <input type="number" name="phone" class="form-control" value="<?php echo isset($_POST['phone']) ? $_POST['phone'] : ''; ?>" required>
                <?php if (isset($errors['phone'])) echo '<p class="text-danger">' . $errors['phone'] . '</p>'; ?> <br>
                <label for="usertype">User Type:</label>
                <select id="usertype" name="usertype" class="form-control">
                    <option value="teacher" <?php if (isset($_POST['usertype']) && $_POST['usertype'] == 'teacher') echo 'selected'; ?>>Teacher</option>
                    <option value="student" <?php if (isset($_POST['usertype']) && $_POST['usertype'] == 'student') echo 'selected'; ?>>Student</option>
                </select>
                <?php if (isset($errors['usertype'])) echo '<p class="text-danger">' . $errors['usertype'] . '</p>'; ?><br>

                <label>PASSWORD:</label>
                <input type="text" name="password" class="form-control" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>" required>
                <?php if (isset($errors['password'])) echo '<p class="text-danger">' . $errors['password'] . '</p>'; ?>
                <label>CONFORM PASSWORD:</label>
                <input type="text" name="conform_pass" class="form-control" value="<?php echo isset($_POST['conform_pass']) ? $_POST['conform_pass'] : ''; ?>" required>
                <?php if (isset($errors['conform_pass'])) echo '<p class="text-danger">' . $errors['password'] . '</p>'; ?>
                <button class="btn btn-success" type="submit" name="submit" style="margin-top:20px;"> Submit </button><br>
                <a class="btn btn-info" href="login.php"> Cancel </a><br>

            </div>
        </form>
    </div>
</body>
<script>

</script>


</html>