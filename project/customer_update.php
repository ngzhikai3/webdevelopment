<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Update Customer Profile</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <div class="container my-3">
            <div class="page-header">
                <h1>Update Customer Profile</h1>
            </div>
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record ID not found.');

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            if ($action == 'nodeleted') {
                echo "<div class='alert alert-danger'>This customer had order placed so cannot be delete.</div>";
            }

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM customers WHERE user_id = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $user_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $pass_word = $row['password'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $gender = $row['gender'];
                $date_of_birth = $row['date_of_birth'];
                $cus_image = $row['cus_image'];
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {

                $pass_word = ($_POST['password']);
                $old_password = md5($_POST['old_password']);
                $confirm_password = ($_POST['confirm_password']);
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $gender = $_POST['gender'];
                $date_of_birth = $_POST['date_of_birth'];
                $cus_image = !empty($_FILES["cus_image"]["name"])
                    ? sha1_file($_FILES['cus_image']['tmp_name']) . "-" . basename($_FILES["cus_image"]["name"])
                    : htmlspecialchars($cus_image, ENT_QUOTES);
                $cus_image = (strip_tags($cus_image));
                $error_message = "";

                $emptypass = false;
                if ($old_password == md5("") && $pass_word == "" && $confirm_password == "") {
                    $emptypass = true;
                } else {
                    if ($row['password'] == $old_password) {
                        if (!preg_match('/[a-z]/', $pass_word)) {
                            $error_message .= "<div class='alert alert-danger'>Password need include lowercase</div>";
                        } elseif (!preg_match('/[0-9]/', $pass_word)) {
                            $error_message .= "<div class='alert alert-danger'>Password need include number</div>";
                        } elseif (strlen($pass_word) < 8) {
                            $error_message .= "<div class='alert alert-danger'>Password need at least 8 charecter</div>";
                        } elseif ($old_password == $pass_word) {
                            $error_message .= "<div class='alert alert-danger'>New password cannot same with old password</div>";
                        } elseif ($old_password != "" && $password != "" && $confirm_password == "") {
                            $error_message .= "<div class='alert alert-danger'>Please enter confirm password</div>";
                        } elseif ($old_password != "" && $password != "" && $confirm_password != "" && $pass_word != $confirm_password) {
                            $error_message .= "<div class='alert alert-danger'>confirm password need to same with password</div>";
                        }
                    } else {
                        $error_message .= "<div class='alert alert-danger'>Password incorrect</div>";
                    }
                }
                if ($emptypass == true) {
                    $password = $row['password'];
                } else {
                    $password = md5(strip_tags($_POST['password']));
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

                if ($_FILES["cus_image"]["name"]) {

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

                if (isset($_POST['delete'])) {
                    if ($cus_image == "user.png") {
                        $error_message .= "<div class='alert alert-danger'>This user did not have image.</div>";
                    } else {
                        $cus_image = (strip_tags($cus_image));

                        $cus_image = !empty($_FILES["cus_image"]["name"])
                            ? sha1_file($_FILES['cus_image']['tmp_name']) . "-" . basename($_FILES["cus_image"]["name"])
                            : "";
                        $target_directory = "cus_uploads/";
                        $target_file = $target_directory . $cus_image;
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                        unlink("cus_uploads/" . $row['cus_image']);
                        $_POST['cus_image'] = null;
                        $query = "UPDATE customers SET cus_image=:cus_image WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        $stmt->bindParam(':cus_image', $cus_image);
                        $stmt->bindParam(':user_id', $user_id);
                        // Execute the query
                        $stmt->execute();
                        $error_message .= "<div class='alert alert-success'>Image was deleted.</div>";
                    }
                }

                if (!empty($error_message)) {
                    echo $error_message;
                } else {

                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE customers SET password=:password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth, cus_image=:cus_image WHERE user_id = :user_id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        // posted values
                        $first_name = (strip_tags($_POST['first_name']));
                        $last_name = (strip_tags($_POST['last_name']));
                        $gender = (strip_tags($_POST['gender']));
                        $date_of_birth = (strip_tags($_POST['date_of_birth']));
                        $cus_image = (strip_tags($cus_image));
                        // bind the parameters
                        $stmt->bindParam(':password', $password);
                        $stmt->bindParam(':first_name', $first_name);
                        $stmt->bindParam(':last_name', $last_name);
                        $stmt->bindParam(':gender', $gender);
                        $stmt->bindParam(':date_of_birth', $date_of_birth);
                        $stmt->bindParam(':cus_image', $cus_image);
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id={$user_id}"); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-dark table-responsive table-bordered'>
                    <tr data-aos="fade-left">
                        <td class="text-center">Old Password</td>
                        <td colspan="3"><input type='password' name='old_password' class='form-control' placeholder="Leave blank if no password has been changed" /></td>
                    </tr>
                    <tr data-aos="fade-right">
                        <td class="text-center">New Password</td>
                        <td><input type='password' name='password' class='form-control' placeholder="Leave blank if no password has been changed" /></td>
                        <td class="text-center">Confirm Password</td>
                        <td><input type='password' name='confirm_password' class='form-control' placeholder="Leave blank if no password has been changed" /></td>
                    </tr>
                    <tr data-aos="fade-left">
                        <td class="text-center">First Name</td>
                        <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                        <td class="text-center">Last Name</td>
                        <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-right">
                        <td class="text-center">Gender</td>
                        <td>
                            <?php
                            if ($gender == "male") {
                                echo "<div class='form-check'>
                                    <input class='form-check-input' type='radio' name='gender' value='male' checked>
                                    <label class='form-check-label'>
                                    Male
                                    </label>
                                </div>
                                <div class='form-check'>
                                    <input class='form-check-input' type='radio' name='gender' value='female'>
                                    <label class='form-check-label'>
                                    Female
                                    </label>
                                </div>";
                            } else {
                                echo "<div class='form-check'>
                                        <input class='form-check-input' type='radio' name='gender' value='male'>
                                        <label class='form-check-label'>
                                            Male
                                        </label>
                                    </div>
                                    <div class='form-check'>
                                        <input class='form-check-input' type='radio' name='gender' value='female' checked>
                                        <label class='form-check-label'>
                                            Female
                                        </label>
                                    </div>";
                            }
                            ?>
                        </td>
                        <td class="text-center">Date Of Birth</td>
                        <td><input type='date' name='date_of_birth' value="<?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr data-aos="fade-left">
                        <td class="text-center">Photo</td>
                        <td colspan="3">
                            <div><img src="cus_uploads/<?php echo htmlspecialchars($cus_image, ENT_QUOTES);  ?>" class="w-25 mb-2"></div>
                            <div><input type="file" name="cus_image" /></div>
                            <div><?php echo "<button class='btn btn-danger mx-2 mt-2' name='delete'><i class='fa-solid fa-trash'></i></button>"; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-end">
                            <button type='submit' class='btn btn-success'>
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                            <a href='customer_read.php' class='btn btn-secondary'><i class="fa-sharp fa-solid fa-circle-arrow-left"></i> Back to Customer Profile</a>
                            <?php echo "<a href='customer_delete.php?user_id={$user_id}' class='btn btn-danger m-r-1em'><i class='fa-solid fa-trash'></i></a>"; ?>
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