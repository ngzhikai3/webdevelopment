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

<body>

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
                $flag = 0;

                if ($user_name == "") {
                    echo "<div class='alert alert-danger'>Please enter your username</div>";
                    $flag = 1;
                }
                
                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    echo "<div class='alert alert-danger'>Username not space allow</div>";
                    $flag = 1;
                } elseif (strlen($user_name) < 6) {
                    echo "<div class='alert alert-danger'>Username need at least 6 charecter</div>";
                    $flag = 1;
                }

                if ($old_password != "" && $old_password != htmlspecialchars($password, ENT_QUOTES)) {
                    echo "<div class='alert alert-danger'>Password incorrect</div>";
                    $flag = 1;
                } elseif ($old_password != "" && $pass_word == "") {
                    echo "<div class='alert alert-danger'>Please enter your password</div>";
                    $flag = 1;
                } elseif ($old_password != "" && $pass_word == htmlspecialchars($password, ENT_QUOTES)) {
                    echo "<div class='alert alert-danger'>New password cannot same with old password</div>";
                    $flag = 1;
                } elseif (!preg_match('/[A-Z]/', $pass_word)) {
                    echo "<div class='alert alert-danger'>Password need include uppercase</div>";
                    $flag = 1;
                } elseif (!preg_match('/[a-z]/', $pass_word)) {
                    echo "<div class='alert alert-danger'>Password need include lowercase</div>";
                    $flag = 1;
                } elseif (!preg_match('/[0-9]/', $pass_word)) {
                    echo "<div class='alert alert-danger'>Password need include number</div>";
                    $flag = 1;
                } elseif (strlen($pass_word) < 8) {
                    echo "<div class='alert alert-danger'>Password need at least 8 charecter</div>";
                    $flag = 1;
                } elseif ($password != "" && $confirm_password == "") {
                    echo "<div class='alert alert-danger'>Please enter confirm password</div>";
                    $flag = 1;
                } elseif ($pass_word != $confirm_password) {
                    echo "<div class='alert alert-danger'>confirm password need to same with password</div>";
                    $flag = 1;
                }


                if ($first_name == "") {
                    echo "<div class='alert alert-danger'>Please enter your first name</div>";
                    $flag = 1;
                }

                if ($last_name == "") {
                    echo "<div class='alert alert-danger'>Please enter your last name</div>";
                    $flag = 1;
                }

                if ($gender == "") {
                    echo "<div class='alert alert-danger'>Please select your gender</div>";
                    $flag = 1;
                }

                if ($date_of_birth == "") {
                    echo "<div class='alert alert-danger'>Please select your date of birth</div>";
                    $flag = 1;
                }
                $day = $_POST['date_of_birth'];
                $today = date("Ymd");
                $date1 = date_create($day);
                $date2 = date_create($today);
                $diff = date_diff($date1, $date2);
                if ($diff->format("%y") <= "18") {
                    echo "<div class='alert alert-danger'>User need 18 years old and above</div>";
                    $flag = 1;
                }

                if ($flag == 0) {

                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        // posted values
                        $username = htmlspecialchars(strip_tags($_POST['username']));
                        $password = htmlspecialchars(strip_tags($_POST['password']));
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
                            echo "<div class='alert alert-success'>Record was updated.</div>";
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
                <table class='table table-hover table-responsive table-bordered'>
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
                        <td><input type='password' name='password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>confirm Password</td>
                        <td><input type='password' name='confirm_password' value="<?php echo htmlspecialchars($password, ENT_QUOTES);  ?>" class='form-control' /></td>
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