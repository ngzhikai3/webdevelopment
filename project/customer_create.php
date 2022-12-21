<?php
include 'check.php';
?>

<!DOCTYPE html>
<html>

<head>

    <title>Create Customer</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <div class="container my-3 py-2">
            <div class="page-header text-center">
                <h1>Create profile</h1>
            </div>

            <?php
            $user_name = $first_name = $last_name = $date_of_birth = "";

            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $pass_word = md5($_POST['password']);
                $confirm_password = md5($_POST['confirm_password']);
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $account_status = $_POST['account_status'];
                $cus_image = !empty($_FILES["cus_image"]["name"])
                    ? sha1_file($_FILES['cus_image']['tmp_name']) . "-" . basename($_FILES["cus_image"]["name"])
                    : "";
                $cus_image = htmlspecialchars(strip_tags($cus_image));
                $error_message = "";

                if ($user_name == "" || $pass_word == md5("") || $confirm_password == md5("") || $first_name == "" || $last_name == "" || $gender == "" || $date_of_birth == "" || $account_status == "") {
                    $error_message .= "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                }

                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    $error_message .= "<div class='alert alert-danger'>Username not space allow</div>";
                } elseif (strlen($user_name) < 6) {
                    $error_message .= "<div class='alert alert-danger'>Username need at least 6 charecter</div>";
                }

                if (!preg_match('/[a-z]/', $pass_word)) {
                    $error_message .= "<div class='alert alert-danger'>Password need include lowercase</div>";
                } elseif (!preg_match('/[0-9]/', $pass_word)) {
                    $error_message .= "<div class='alert alert-danger'>Password need include number</div>";
                } elseif (strlen($pass_word) < 8) {
                    $error_message .= "<div class='alert alert-danger'>Password need at least 8 charecter</div>";
                }

                if ($pass_word != $confirm_password) {
                    $error_message .= "<div class='alert alert-danger'>Password need to same with confirm password</div>";
                }

                if ($date_of_birth != "") {
                    $day = $_POST['date_of_birth'];
                    $today = date("Ymd");
                    $date1 = date_create($day);
                    $date2 = date_create($today);
                    $diff = date_diff($date1, $date2);
                    if ($diff->format("%y") <= "18") {
                        $error_message .= "<div class='alert alert-danger'>User need 18 years old and above</div>";
                    }
                }

                if ($cus_image) {

                    // upload to file to folder
                    $target_directory = "cus_uploads/";
                    $target_file = $target_directory . $cus_image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    // make sure that file is a real image
                    $check = getimagesize($_FILES["cus_image"]["tmp_name"]);
                    if ($check === false) {
                        $error_message .= "<div class='alert alert-danger'>Submitted file is not an image.</div>";
                    }
                    // make sure certain file types are allowed
                    $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                    if (!in_array($file_type, $allowed_file_types)) {
                        $error_message .= "<div class='alert alert-danger'>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                    }
                    // make sure file does not exist
                    if (file_exists($target_file)) {
                        $error_message .= "<div class='alert alert-danger'>Image already exists. Try to change file name.</div>";
                    }
                    // make sure submitted file is not too large, can't be larger than 1 MB
                    if ($_FILES['cus_image']['size'] > (1024000)) {
                        $error_message .= "<div class='alert alert-danger'>Image must be less than 1 MB in size.</div>";
                    }
                    // make sure the 'uploads' folder exists
                    // if not, create it
                    if (!is_dir($target_directory)) {
                        mkdir($target_directory, 0777, true);
                    }
                    // if $file_upload_error_messages is still empty
                    if (empty($error_message)) {
                        // it means there are no errors, so try to upload the file
                        if (!move_uploaded_file($_FILES["cus_image"]["tmp_name"], $target_file)) {
                            $error_message .= "<div class='alert alert-danger>Unable to upload photo.</div>";
                            $error_message .= "<div class='alert alert-danger>Update the record to upload photo.</div>";
                        }
                    }
                }

                if ($cus_image == null){
                    $cus_image = "user.png";
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, cus_image=:cus_image";
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
                        $stmt->bindParam(':cus_image', $cus_image);
                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: customer_read.php?update={save}");
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
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <table class='table table-dark table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td><input type='text' name='username' value='<?php echo $user_name ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>confirm Password</td>
                        <td><input type='password' name='confirm_password' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><input type='text' name='first_name' value='<?php echo $first_name ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type='text' name='last_name' value='<?php echo $last_name ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="male" checked>
                                <label class="form-check-label">
                                    Male
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="female">
                                <label class="form-check-label">
                                    Female
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Date Of Birth</td>
                        <td><input type='date' name='date_of_birth' value='<?php echo $date_of_birth ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Account Status</td>
                        <td>
                            <input class="form-check-input" type="radio" name="account_status" value="active" checked>
                            <label class="form-check-label">
                                Active
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td><input type="file" name="cus_image" /></td>
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