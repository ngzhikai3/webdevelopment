<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Read One Customer</title>

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
                <h1>Read Customer Profile</h1>
            </div>

            <!-- PHP read one record will be here -->
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : die('ERROR: Record Customer not found.');

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
                $query = "SELECT * FROM customers WHERE user_id = :user_id ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":user_id", $user_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $username = $row['username'];
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $gender = $row['gender'];
                $date_of_birth = $row['date_of_birth'];
                $cus_image = $row['cus_image'];
                // shorter way to do that is extract($row)
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-dark table-responsive table-bordered'>
                <tr data-aos="fade-right">
                    <td class="text-center">Username</td>
                    <td colspan="3"><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos="fade-left">
                    <td class="text-center col-3">First Name</td>
                    <td class="text-center col-3"><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
                    <td class="text-center col-3">Last Name</td>
                    <td class="text-center col-3"><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos="fade-right">
                    <td class="text-center col-3">Gender</td>
                    <td class="text-center col-3"><?php if ($gender == "male") {
                            echo "<i class='fa-solid fa-person fs-1 text-primary ms-3'></i>";
                        } else {
                            echo "<i class='fa-solid fa-person-dress fs-1 text-danger ms-3'></i>";
                        } ?></td>
                    <td class="text-center col-3">Date of Birth</td>
                    <td class="text-center col-3"><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos="fade-left">
                    <td class="text-center">Photo</td>
                    <td colspan="3"><img src="cus_uploads/<?php echo htmlspecialchars($cus_image, ENT_QUOTES);  ?>" class="w-25"></td>
                </tr>
                <tr data-aos="fade-right">
                    <td></td>
                    <td class="text-end" colspan="3">
                        <?php echo "<a href='customer_update.php?user_id={$user_id}' class='btn btn-primary m-r-1em'><i class='fa-solid fa-pen-to-square'></i></a>"; ?>
                        <a href='customer_read.php' class='btn btn-secondary'><i class="fa-sharp fa-solid fa-circle-arrow-left"></i> Back to Customer Profile</a>
                        <?php echo "<a href='customer_delete.php?user_id={$user_id}' class='btn btn-danger'><i class='fa-solid fa-trash'></i></a>"; ?>
                    </td>
                </tr>
            </table>
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