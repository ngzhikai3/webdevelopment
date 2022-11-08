<!DOCTYPE html>
<html>

<head>

    <title>Login Page</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="signin.css" rel="stylesheet">

</head>

<body>

    <div class="container-fluid px-0">

        <h1 class="text-center my-5">Please sign in</h1>

        <div class="container mt-5">
            <?php

            include 'config/database.php';
            session_start();
            if (isset($_POST['username']) && isset($_POST['password'])) {

                $username = ($_POST['username']);
                $password = ($_POST['password']);

                $select = " SELECT username, password, account_status FROM customers WHERE username = '$username' && password = '$password'";
                $result = mysqli_query($mysqli, $select);
                $row = mysqli_fetch_assoc($result);

                if (mysqli_num_rows($result) == 1) {
                    if ($row['username'] === $username && $row['password'] === $password && $row['account_status'] == "active") {
                        header("Location: index.php");
                    } elseif ($row['username'] === $username && $row['password'] === $password && $row['account_status'] != "active") {
                        echo "<h3 class='alert alert-danger'>Your account is inactive.</h3>";
                    }
                }
                $check_username = " SELECT username FROM customers WHERE username = '$username'";
                $result2 = mysqli_query($mysqli, $check_username);
                $row = mysqli_fetch_assoc($result2);
                if (mysqli_num_rows($result2) == 0) {
                    echo "<h3 class='alert alert-danger'>Your username is incorrect.</h3>";
                } else {
                    $check_password = " SELECT password FROM customers WHERE password = '$password'";
                    $result3 = mysqli_query($mysqli, $check_password);
                    $row = mysqli_fetch_assoc($result3);
                    if (mysqli_num_rows($result3) == 0) {
                        echo "<h3 class='alert alert-danger'>Your password is incorrect.</h3>";
                    }
                }
            };
            ?>

            <?php
            // include database connection
            /*include 'config/database.php';
            session_start();
            if (isset($_POST['username']) && isset($_POST['password'])) {

                $username = ($_POST['username']);
                $password = ($_POST['password']);

                $select = " SELECT username, password, account_status FROM customers WHERE username = '$username' && password = '$password' ";
                $result = mysqli_query($mysqli, $select);
                $row = mysqli_fetch_assoc($result);

                if (mysqli_num_rows($result) == 1) {
                    if ($row['username'] === $username && $row['password'] === $password) {
                        if ($row['account_status'] != "active") {
                            echo "<div class='alert alert-danger'>Your account is closed.</div>wrong password";
                        } else {
                            header("location:index.php");
                        }
                    }
                }
                $findname = " SELECT username FROM customers WHERE username = '$username'";
                $result2 = mysqli_query($mysqli, $findname);
                $row = mysqli_fetch_assoc($result2);
                if (mysqli_num_rows($result2) == 0) {
                    echo "<div class='alert alert-danger'>Wrong Username.</div>wrong password";
                } else {
                    $findpass = " SELECT password FROM customers WHERE password = '$password'";
                    $result3 = mysqli_query($mysqli, $findpass);
                    $row = mysqli_fetch_assoc($result3);
                    if (mysqli_num_rows($result3) == 0) {

                        echo "<div class='alert alert-danger'>Wrong Password.</div>wrong password";
                    }
                }
            }*/
            ?>

            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <table class='table table-hover table-responsive table-bordered '>
                    <div class="form-floating  ">
                        <input type="text" class="form-control" id="floatingInput" name="username">
                        <label for="floatingInput">Username</label>
                    </div>

                    <div class="form-floating ">
                        <input type="password" class="form-control" id="floatingPassword" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>

                    <div class="text-center my-3">
                        <button class="w-50 btn btn-lg btn-primary" type="submit">Sign in</button>
                    </div>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>