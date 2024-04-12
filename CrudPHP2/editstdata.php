<?php
include "connection.php";

$id = "";
$name = "";
$email = "";
$phone = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == 'GET') {
    if (!isset($_GET['id'])) {
        header("Location: /curdPHP/stdata.php");
        exit;
    }
    $id = $_GET['id'];
    $sql = "SELECT * FROM data1 WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row["name"];
        $email = $row["email"];
        $phone = $row["phone"];
    } else {
        header("Location: /curdPHP/stdata.php");
        exit;
    }
    $stmt->close();
} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $errors = [];

    if (empty($name)) {
        $errors[] = "Enter name";
    } elseif (!preg_match("/^[a-z,A-Z\s]+$/", $name)) {
        $errors[] = "Only alphabets are allowed for name";
    }

    if (empty($email)) {
        $errors[] = "Enter email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($phone)) {
        $errors[] = "Enter phone";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "Invalid phone number format";
    }

    if (count($errors) == 0) {
        $sql = "UPDATE data1 SET name=?, email=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        if ($stmt->execute()) {
            header("Location: /curdPHP/stdata.php");
            exit;
        } else {
            $error = "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = implode("<br>", $errors);
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>EDIT STUDENT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="stdata.php">CHANGE STUDENT DATA</a>
        </div>
    </nav>
    <div class="col-lg-6 m-auto">
        <form method="post">
            <br><br>
            <div class="card">
                <div class="card-header bg-warning">
                    <h1 class="text-white text-center"> Update Member </h1>
                </div><br>
                <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control" required> <br>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <label> NAME: </label>
                <input type="text" name="name" value="<?php echo $name; ?>" class="form-control" required> <br>
                <label> EMAIL: </label>
                <input type="email" name="email" value="<?php echo $email; ?>" class="form-control" required> <br>
                <label> PHONE: </label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" class="form-control" required> <br>
                <button class="btn btn-success" type="submit" name="submit"> Submit </button><br>
                <a class="btn btn-info" href="stdata.php"> Cancel </a><br>
            </div>
        </form>
    </div>
</body>

</html>