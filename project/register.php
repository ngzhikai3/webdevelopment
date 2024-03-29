<!DOCTYPE html>
<html>

<head>

    <title>Create Customer</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <div class="container my-3 py-2">
            <div class="page-header text-center">
                <h1>Register Account</h1>
            </div>

            <?php
            $user_name = $first_name = $last_name = $date_of_birth = $gender = $account_status = "";

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
                if (strpos($user_name, $space) !== false) {
                    $error_message .= "<div class='alert alert-danger'>Username not space allow</div>";
                } elseif (strlen($user_name) < 6) {
                    $error_message .= "<div class='alert alert-danger'>Username need at least 6 charecter</div>";
                } elseif (!preg_match('/[a-z]/', $user_name) && !preg_match('/[A-Z]/', $user_name)) {
                    $error_message .= "<div class='alert alert-danger'>Username cannot just number</div>";
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
                } elseif (empty($cus_image)) {
                    $cus_image = "user.png";
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO customers SET username=:username, password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, account_status=:account_status, user_type=:user_type, cus_image=:cus_image";
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
                        $user_type = "user";
                        $stmt->bindParam(':user_type', $user_type);
                        $stmt->bindParam(':cus_image', $cus_image);
                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: login.php?update={save}");
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
                <tr data-aos="fade-left">
                        <td class="text-center">Username</td>
                        <td colspan="3"><input type='text' name='username' value='<?php echo $user_name ?>' class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-right">
                        <td class="text-center col-3">Password</td>
                        <td><input type='password' name='password' class='form-control' /></td>
                        <td class="text-center col-3">Confirm Password</td>
                        <td><input type='password' name='confirm_password' class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-left">
                        <td class="text-center col-3">First Name</td>
                        <td><input type='text' name='first_name' value='<?php echo $first_name ?>' class='form-control' /></td>
                        <td class="text-center col-3">Last Name</td>
                        <td><input type='text' name='last_name' value='<?php echo $last_name ?>' class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-right">
                        <td class="text-center">Gender</td>
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
                        <td class="text-center">Account Status</td>
                        <td>
                            <div>
                                <input class="form-check-input" type="radio" name="account_status" value="active" checked>
                                <label class="form-check-label">
                                    Active
                                </label>
                            </div>
                            <div>
                                <input class="form-check-input" type="radio" name="account_status" value="inactive">
                                <label class="form-check-label">
                                    Inactive
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr data-aos="fade-left">
                        <td class="text-center">Date Of Birth</td>
                        <td colspan="3"><input type='date' name='date_of_birth' value='<?php echo $date_of_birth ?>' class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-right">
                        <td class="text-center">Photo</td>
                        <td colspan="3"><input type="file" name="cus_image" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-end">
                            <input type='submit' value='Register' class='btn btn-primary' />
                            <a href='login.php' class='btn btn-secondary'>Back to Login</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- end .container -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>

</html>