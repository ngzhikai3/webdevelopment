<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Update Customer Profile</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <div class="container">
            <div class="page-header">
                <h1>Update Customer Profile</h1>
            </div>
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT username, password, first_name, last_name, gender, date_of_birth FROM customers WHERE user_id = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $user_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $username = $row['username'];
                $password = $row['password'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $gender = $row['gender'];
                $date_of_birth = $row['date_of_birth'];
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {

                $user_name = $_POST['username'];
                $pass_word = $_POST['password'];
                $old_password = $_POST['old_password'];
                $confirm_password = $_POST['confirm_password'];
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $error_message = "";

                if ($user_name == "") {
                    $error_message .= "<div class='alert alert-danger'>Please enter your username</div>";
                }

                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    $error_message .= "<div class='alert alert-danger'>Username not space allow</div>";
                } elseif (strlen($user_name) < 6) {
                    $error_message .= "<div class='alert alert-danger'>Username need at least 6 charecter</div>";
                }

                $emptypass = false;
                if ($old_password == "" && $pass_word == "" && $confirm_password == "") {
                    $emptypass = true;
                } else {
                    if ($row['password'] == $old_password) {
                        if (!preg_match('/[A-Z]/', $pass_word)) {
                            $error_message .= "<div class='alert alert-danger'>Password need include uppercase</div>";
                        } elseif (!preg_match('/[a-z]/', $pass_word)) {
                            $error_message .= "<div class='alert alert-danger'>Password need include lowercase</div>";
                        } elseif (!preg_match('/[0-9]/', $pass_word)) {
                            $error_message .= "<div class='alert alert-danger'>Password need include number</div>";
                        } elseif (strlen($pass_word) < 8) {
                            $error_message .= "<div class='alert alert-danger'>Password need at least 8 charecter</div>";
                        }
                        if ($old_password == $pass_word) {
                            $error_message .= "<div class='alert alert-danger'>New password cannot same with old password</div>";
                        }
                        if ($old_password != "" && $password != "" && $confirm_password == "") {
                            $error_message .= "<div class='alert alert-danger'>Please enter confirm password</div>";
                        }
                        if ($old_password != "" && $password != "" && $confirm_password != "" && $pass_word != $confirm_password) {
                            $error_message .= "<div class='alert alert-danger'>confirm password need to same with password</div>";
                        }
                    } else {
                        $error_message .= "<div class='alert alert-danger'>Password incorrect</div>";
                    }
                }

                if ($first_name == "") {
                    $error_message .= "<div class='alert alert-danger'>Please enter your first name</div>";
                }

                if ($last_name == "") {
                    $error_message .= "<div class='alert alert-danger'>Please enter your last name</div>";
                }

                if ($gender == "") {
                    $error_message .= "<div class='alert alert-danger'>Please select your gender</div>";
                }

                if ($date_of_birth == "") {
                    $error_message .= "<div class='alert alert-danger'>Please select your date of birth</div>";
                }
                $day = $_POST['date_of_birth'];
                $today = date("Ymd");
                $date1 = date_create($day);
                $date2 = date_create($today);
                $diff = date_diff($date1, $date2);
                if ($diff->format("%y") <= "18") {
                    $error_message .= "<div class='alert alert-danger'>User need 18 years old and above</div>";
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        // posted values
                        $username = htmlspecialchars(strip_tags($_POST['username']));
                        if ($emptypass == true) {
                            $password = $row['password'];
                        } else {
                            $password = htmlspecialchars(strip_tags($_POST['password']));
                        }
                        $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                        $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                        $gender = htmlspecialchars(strip_tags($_POST['gender']));
                        $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));
                        // bind the parameters
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':first_name', $first_name);
                        $stmt->bindParam(':last_name', $last_name);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);
                        $stmt->bindParam(':user_id', $user_id);
                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: customer_read.php?update={$user_id}");
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    }
                    // show errors
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            } ?>

            <!--we have our html form here where new record information can be updated-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id={$user_id}"); ?>" method="post">
                <table class='table table-hover table-dark table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Old Password</td>
                        <td><input type='password' name='old_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>New Password</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>confirm Password</td>
                        <td><input type='password' name='confirm_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>gender</td>
                        <td><input type='text' name='gender' value="<?php echo htmlspecialchars($gender, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Date Of Birth</td>
                        <td><input type='date' name='date_of_birth' value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save Changes' class='btn btn-primary' />
                            <a href='customer_read.php' class='btn btn-danger'>Back to read customer profile</a>
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