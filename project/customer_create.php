<!DOCTYPE html>
<html>

<head>

    <title>Create Customer</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>
    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="product_create.php">Create Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="product_read.php">Product List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="customer_create.php">Create Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="customer_read.php">Customer List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="contact_us.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-3">
            <div class="page-header">
                <h1>Create profile</h1>
            </div>

            <?php
            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $pass_word = $_POST['password'];
                $comfirm_password = $_POST['comfirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = $_POST['account_status'];

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

                if ($comfirm_password == "") {
                    echo "Please enter comfirm password";
                    $flag = 1;
                } elseif ($pass_word != $comfirm_password) {
                    echo "Password need to same with comfirm password";
                    $flag = 1;
                }

                if ($first_name == "") {
                    echo "Please enter your first name";
                    $flag = 1;
                }

                if ($last_name == "") {
                    echo "Please enter your last name";
                    $flag = 1;
                }

                if ($gender == "") {
                    echo "Please select your gender";
                    $flag = 1;
                }

                if ($date_of_birth == "") {
                    echo "Please select your date of birth";
                    $flag = 1;
                }
                $day = $_POST['date_of_birth'];
                $today = date("Ymd");
                $date1 = date_create($day);
                $date2 = date_create($today);
                $diff = date_diff($date1, $date2);
                if ($diff->format("%y") <= "18") {
                    echo "User need 18 years old and above";
                    $flag = 1;
                }

                if ($account_status == "") {
                    echo "Please enter your account status";
                    $flag = 1;
                }

                if ($flag == 0) {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':username', $user_name);
                        $stmt->bindParam(':password', $pass_word);
                        $stmt->bindParam(':first_name', $first_name);
                        $stmt->bindParam(':last_name', $last_name);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);
                        $stmt->bindParam(':account_status', $account_status);
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            }

            ?>

            <!-- html form here where the product information will be entered -->
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td><input type='text' name='username' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Comfirm Password</td>
                        <td><input type='password' name='comfirm_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><input type='text' name='first_name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type='text' name='last_name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="male">
                                <label class="form-check-label" for="gender1">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="female">
                                <label class="form-check-label" for="gender2">
                                    Female
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Date Of Birth</td>
                        <td><input type='date' name='date_of_birth' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Account Status</td>
                        <td><textarea type='text' name='account_status' class='form-control'></textarea></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='customer_read.php' class='btn btn-danger'>Back to read customer</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- end .container -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>