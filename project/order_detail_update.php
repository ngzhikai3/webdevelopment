<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Update Order Details</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <div class="container">
            <div class="page-header">
                <h1>Update Order Details</h1>
            </div>
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $details_id = isset($_GET['details_id']) ? $_GET['details_id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM order_details WHERE details_id = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $details_id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $order_id = $row['order_id'];
                $details_id = $row['details_id'];
                $product_id = $row['product_id'];
                $quantity = $row['quantity'];
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {

                $order_id = $_POST['order_id'];
                $details_id = $_POST['details_id'];
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];
                $error_message = "";

                try {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE order_details SET order_id=:order_id, details_id=:details_id, product_id=:product_id, quantity=:quantity WHERE details_id = :details_id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);
                    // posted values
                    $order_id = htmlspecialchars(strip_tags($_POST['order_id']));
                    $details_id = htmlspecialchars(strip_tags($_POST['details_id']));
                    $product_id = htmlspecialchars(strip_tags($_POST['product_id']));
                    $quantity = htmlspecialchars(strip_tags($_POST['quantity']));
                    // bind the parameters
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':details_id', $details_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $quantity);
                    // Execute the query
                    if ($stmt->execute()) {
                        header("Location: order_read.php?update={$details_id}");
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
                // show errors
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            }

            ?>

            <!--we have our html form here where new record information can be updated-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?details_id={$details_id}"); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-dark table-responsive table-bordered'>
                    <tr>
                        <td>Order ID</td>
                        <td><input type='text' name='order_id' value="<?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Details ID</td>
                        <td><input type='text' name='details_id' value="<?php echo htmlspecialchars($details_id, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product ID</td>
                        <td><input type='text' name='product_id' value="<?php echo htmlspecialchars($product_id, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td><input type='text' name='quantity' value="<?php echo htmlspecialchars($quantity, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save Changes' class='btn btn-primary' />
                            <a href='order_read.php' class='btn btn-danger'>Back to read order details</a>
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