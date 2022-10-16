<?php

session_start();
include('config/config.php');

if (isset($_POST['username'])) {
    $sql = mysqli_query($connection, "SELECT * FROM user WHERE username = '" . $_POST['username'] . "' AND password = '" . md5($_POST['password']) . "'");
    $row = mysqli_fetch_assoc($sql);

    if (empty($row)) {
        $message = "Username atau password salah";
    } else {
        $_SESSION['username'] = $row['username'];
        $_SESSION['id'] = $row['id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>

<body>
    <div class="container" style="margin-top: 50px;">
        <?php if (isset($_SESSION['username'])) : ?>
            <?php
            $page = isset($_GET['page']) ? explode("?", $_GET['page']) : null;
            if (!empty($page) && is_file("page/" . $page[0])) {
                include("page/" . $page[0]);
            } else {
                include("home.php");
            }
            ?>
        <?php else : ?>
            <div class="row">
                <div class="col-md-4 offset-md-4">
                    <div class="card">
                        <div class="card-header">
                            Login
                        </div>
                        <div class="card-body">
                            <?php if (isset($message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $message ?>
                                </div>
                            <?php endif; ?>
                            <form method="POST">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" id="username" class="form-control">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control">
                                <br />
                                <button class="btn btn-primary" type="submit">Login</button>
                                <button class="btn btn-secondary" type="reset">Reset</button>
                                <p> Belum punya akun?
                                    <a href="register.php">Daftar di sini</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>