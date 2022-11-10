<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Create Product</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <!-- container -->
        <div class="container my-3">
            <div class="page-header">
                <h1>Create Product</h1>
            </div>

            <?php

            if ($_POST) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                $date1 = date_create($manufacture_date);
                $date2 = date_create($expired_date);
                $diff = date_diff($date1, $date2);

                if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                    echo "Please make sure all fields are not empty";
                }
                $flag = 0;

                if ($price == "") {
                    echo "Please make sure price are not empty";
                    $flag = 1;
                } elseif (preg_match('/[A-Z]/', $price)) {
                    echo "Please make sure price are not contain capital A-Z";
                    $flag = 1;
                } elseif (preg_match('/[a-z]/', $price)) {
                    echo "Please make sure price are not contain capital a-z";
                    $flag = 1;
                } elseif ($price < 0) {
                    echo "Please make sure price are not negative";
                    $flag = 1;
                } elseif ($price > 1000) {
                    echo "Please make sure price are not more than RM1000";
                    $flag = 1;
                }

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                } elseif (preg_match('/[A-Z]/', $promotion_price)) {
                    echo "Please make sure price are not contain capital A-Z";
                    $flag = 1;
                } elseif (preg_match('/[a-z]/', $promotion_price)) {
                    echo "Please make sure price are not contain capital a-z";
                    $flag = 1;
                } elseif ($promotion_price < 0) {
                    echo "Please make sure price are not negative";
                    $flag = 1;
                } elseif ($promotion_price > 1000) {
                    echo "Please make sure price are not more than RM1000";
                    $flag = 1;
                }

                if ($promotion_price > $price) {
                    echo "Please make sure promotion price is not more than normal price";
                    $flag = 1;
                }

                if ($expired_date == "") {
                    $expired_date = NULL;
                }

                if ($diff->format("%R%a") < "0") {
                    echo "Expired date must be after the manufacture date";
                }

                if ($flag == 0) {

                    // include database connection
                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price,  manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $created = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':created', $created);
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
                        <td>Name</td>
                        <td><input type='text' name='name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea type='text' name='description' class='form-control'></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type='text' name='price' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Promotion price</td>
                        <td><input type='text' name='promotion_price' class='form-control' /></td>

                    </tr>
                    <tr>
                        <td>Manufacture Date</td>
                        <td><input type='date' name='manufacture_date' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Expired Date</td>
                        <td><input type='date' name='expired_date' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
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