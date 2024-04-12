<!-- login.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="stdata.php">SCHOOL</a>
        </div>
    </nav>
    <div class="container mt-5">
        <form action="login1.php" method="post" class="col-md-4 offset-md-4">
            <h2 class="mb-4">Login</h2>
            <div class="mb-3">
                <label for="email" class="form-label">Username</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="Registration.php" class="btn btn-link">New User??</a>
        </form>



    </div>

</body>

</html>