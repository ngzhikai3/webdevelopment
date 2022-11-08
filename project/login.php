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

        <div class="container mt-5">
            <?php
            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $pass_word = $_POST['password'];

                $flag = 0;
                if ($user_name == "") {
                    echo "Please enter your username";
                    $flag = 1;
                }
                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    echo "Username not space allow";
                    $flag = 1;
                } elseif (strlen($user_name) < 6) {
                    echo "Username need at least 6 charecter";
                    $flag = 1;
                }

                if ($pass_word == "") {
                    echo "Please enter your password";
                    $flag = 1;
                } elseif (!preg_match('/[A-Z]/', $pass_word)) {
                    echo "Password need include uppercase";
                    $flag = 1;
                } elseif (!preg_match('/[a-z]/', $pass_word)) {
                    echo "Password need include lowercase";
                    $flag = 1;
                } elseif (!preg_match('/[0-9]/', $pass_word)) {
                    echo "Password need include number";
                    $flag = 1;
                } elseif (strlen($pass_word) < 8) {
                    echo "Password need at least 8 charecter";
                    $flag = 1;
                }

                if ($flag == 0) {
                }
            }

            ?>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <h1 class="text-center">Please sign in</h1>
                <div class="my-3">
                    <h3>Username</h3>
                    <select class="form-select form-select" aria-label=".form-select example" name="username">
                        <?php

                        include 'config/database.php';
                        // select all data
                        $query = "SELECT username FROM customers ORDER BY username DESC";
                        $stmt = $con->prepare($query);
                        $stmt->execute();
                        // this is how to get number of rows returned
                        $num = $stmt->rowCount();
                        //check if more than 0 record found
                        if ($num > 0) {
                            // data from database will be here
                        } else {
                            echo "<div class='alert alert-danger'>No records found.</div>";
                        }
                        //new
                        echo "<option selected>Username</option>"; //start dropdown
                        // table body will be here
                        // retrieve our table contents
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // extract row
                            // this will make $row['firstname'] to just $firstname only
                            extract($row);
                            // creating new table row per record
                            echo "<option value=\"$username\">$username</option>";
                        }
                        // end table
                        echo "</option>";
                        ?>
                    </select>
                </div>

                <div class="my-3">
                    <form>
                        <div>
                            <h3>Password</h3>
                            <td><input type='password' name='password' class='form-control' /></td>
                        </div>
                    </form>
                </div>

                <div class="text-center my-3">
                    <a href="index.php" class='btn btn-primary w-50 m-1'>Submit</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>