<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Read One Customer</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

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
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><?php echo htmlspecialchars($date_of_birth, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td><img src="cus_uploads/<?php echo htmlspecialchars($cus_image, ENT_QUOTES);  ?>" class="w-25"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <?php echo "<a href='customer_update.php?user_id={$user_id}' class='btn btn-primary m-r-1em mx-2'>Edit</a>"; ?>
                        <a href='customer_read.php' class='btn btn-danger'>Back to read customer profile</a>
                        <?php echo "<a href='customer_delete.php?user_id={$user_id}' onclick='delete_customer({$user_id});' class='btn btn-danger'>Delete</a>"; ?>
                    </td>
                </tr>
            </table>

        </div>
        <!-- end .container -->

        <script type='text/javascript'>
            // confirm record deletion
            function delete_customer(user_id) {

                if (confirm('Are you sure?')) {
                    // if user clicked ok,
                    // pass the id to delete.php and execute the delete query
                    window.location = 'customer_delete.php?user_id=' + user_id;
                }
            }
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>