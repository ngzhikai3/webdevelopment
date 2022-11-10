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
                $query = "SELECT username, first_name, last_name, gender, date_of_birth FROM customers WHERE user_id = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $user_id);

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
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {
                try {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE customers SET username=:username, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE user_id = :user_id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);
                    // posted values
                    $username = htmlspecialchars(strip_tags($_POST['username']));
                    $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
                    $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
                    $gender = htmlspecialchars(strip_tags($_POST['gender']));
                    $date_of_birth = htmlspecialchars(strip_tags($_POST['date_of_birth']));
                    // bind the parameters
                    $stmt->bindParam(':username', $username);
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
            } ?>

            <!--we have our html form here where new record information can be updated-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?user_id={$user_id}"); ?>" method="post">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><textarea name='first_name' class='form-control'><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></textarea></td>
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
                            <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
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