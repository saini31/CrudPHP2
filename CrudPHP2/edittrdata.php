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
        header("Location:curdPHP/inedx.php");
        exit;
    }
    $id = $_GET['id'];
    $sql = "select*from teachers where id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    while (!$row) {
        header("Location:curdPHP/index.php");
        exit;
    }
    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $errors = [];
    if (empty($name)) {
        $errors[] = "enter name";
    } else {
        if (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
            $errors[] = "only alphabet is allow";
        }
    }
    if (empty($email)) {
        $errors[] = "enter email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "invalid email format";
    }
    if (empty($phone)) {
        $errors[] = "enter phone number";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "invalid phone number format";
    }
    if (count($errors) == 0) {
        $sql = "update teachers set name='$name',email='$email',phone='$phone' where id='$id'";
        $result = $conn->query($sql);
        header("Location:/curdPHP/index.php");
        exit;
        if ($result) {
            header("Location:/curdPHP/trdata.php");
            exit;
        } else {
            $error = "Error updating records" . $conn->error;
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>EDIT TEACHER DATA</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" class="fw-bold">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">CHANGE TEACHEER DATA</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Registration.php">Add New</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="col-lg-6 m-auto">

        <form method="post">

            <br><br>
            <div class="card">

                <div class="card-header bg-warning">
                    <h1 class="text-white text-center"> Update Member </h1>
                </div><br>

                <input type="hidden" name="id" value="<?php echo $id; ?>" class="form-control"> <br>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <label> NAME: </label>
                <input type="text" name="name" value="<?php echo $name; ?>" class="form-control"> <br>

                <label> EMAIL: </label>
                <input type="email" name="email" value="<?php echo $email; ?>" class="form-control"> <br>

                <label> PHONE: </label>
                <input type="text" name="phone" value="<?php echo $phone; ?>" class="form-control"> <br>

                <button class="btn btn-success" type="submit" name="submit"> Submit </button><br>
                <a class="btn btn-info" type="submit" name="cancel" href="index.php"> Cancel </a><br>

            </div>
        </form>
    </div>
</body>

</html>