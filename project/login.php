<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login Page</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <link href="css/button.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container w-50 bg-dark p-3 pt-1 my-5">

        <?php

        if (isset($_GET['update'])) {
            echo "<div class='alert alert-success mt-3'>User Registered.</div>";
        }

        if (isset($_GET["error"])) {
            echo "<div class=\"alert alert-danger my-5\" role=\"alert\">Please Login</div>";
        }
        ?>

        <div class="text-center mt-5"><img src="images/logo.png" height="50px"></div>

        <h1 class="text-center my-5 text-white">Please sign in</h1>

        <div class="container mt-5">

            <?php
            include 'config/database.php';

            if (isset($_POST['username']) && isset($_POST['password'])) {

                $username = ($_POST['username']);
                $password = md5($_POST['password']);

                $query = "SELECT * FROM customers WHERE username = '$username'";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $num = $stmt->rowCount();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($num == 1) {
                    if ($row['password'] != $password) {
                        echo "<h3 class='alert alert-danger'>Your password is incorrect.</h3>";
                    } elseif ($row['account_status'] != "active") {
                        echo "<h3 class='alert alert-danger'>Your account is suspended.</h3>";
                    } else {
                        $_SESSION["login"] = $username;
                        header("Location: index.php");
                    }
                } else {
                    echo "<h3 class='alert alert-danger'>User not found.</h3>";
                }
            };

            ?>

            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <table class='table table-hover table-responsive table-bordered '>
                    <div class="form-floating my-3">
                        <input type="text" class="form-control" id="floatingInput" name="username">
                        <label for="floatingInput">Username</label>
                    </div>

                    <div class="form-floating my-3">
                        <input type="password" class="form-control" id="floatingPassword" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="text-center my-4">
                        <button class="submitbtn w-100" role="button" type="submit"><span class="text">Sign In</span></button>
                    </div>
                    <div class="text-center my-4">
                        <a href="register.php"><i class="fa-solid fa-user-plus text-success fs-3"></i></a>
                    </div>
                </table>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>