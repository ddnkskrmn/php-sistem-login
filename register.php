<?php

session_start();
include('config/config.php');

// Define variables
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = "Silahkan isi Username";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST['username']))) {
        $username_err = "Username hanya boleh berisi huruf, angka, dan garis bawah";
    } else {
        // SELECT statement
        $sql = "SELECT id FROM user WHERE username = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // set parameter
            $param_username = trim($_POST['username']);

            // attempt to exevute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "Username ini sudah diambil";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "Oops! Terjadi kesalahan. Silahkan coba lagi nanti";
            }

            // close staement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Silahkan isi Password";
    } elseif (strlen(trim($_POST['password'])) < 6) {
        $password_err = "Password harus memiliki minimal 6 karakter";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Silahkan konfirmasi password";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password tidak sesuai";
        }
    }

    // check input errors before input to database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

        // insert statement
        $sql = "INSERT INTO user (username, password) VALUES (? , ?)";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // set parameter
            $param_username = $username;
            $param_password = md5($password);

            if (mysqli_stmt_execute($stmt)) {
                header('location: index.php');
            } else {
                echo "Oops! Terjadi kesalahan. Silahkan coba lagi nanti";
            }

            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($connection);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
</head>

<body>
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <div class="card">
                    <div class="card-header">
                        Register
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                            <span class="invalid-feedback"><?php echo $username_err; ?></span>
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                            <span class="invalid-feedback"><?php echo $password_err; ?></span>
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                            <br />
                            <button class="btn btn-primary" type="submit">Register</button>
                            <button class="btn btn-secondary" type="reset">Reset</button>
                            <p> Sudah punya akun?
                                <a href="index.php">Login di sini</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>