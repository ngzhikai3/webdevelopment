<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Login Page</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container w-50 bg-dark p-3 pt-1 my-5">

        <?php
        if ($_GET) {
            echo "<div class=\"alert alert-danger my-5\" role=\"alert\">Please Login</div>";
        }
        ?>

        <h1 class="text-center my-5 text-white">Please sign in</h1>

        <div class="container mt-5">

            <?php
            include 'config/database.php';

            if (isset($_POST['username']) && isset($_POST['password'])) {

                $username = ($_POST['username']);
                $password = ($_POST['password']);

                $query = "SELECT username, password, account_status FROM customers WHERE username = '$username'";
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
                    <div class="form-floating  ">
                        <input type="text" class="form-control" id="floatingInput" name="username">
                        <label for="floatingInput">Username</label>
                    </div>

                    <div class="form-floating ">
                        <input type="password" class="form-control" id="floatingPassword" name="password">
                        <label for="floatingPassword">Password</label>
                    </div>

                    <div class="text-center my-3">
                        <button class="w-50 btn btn-lg btn-primary" type="submit">Sign in</button>
                    </div>
                </table>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>