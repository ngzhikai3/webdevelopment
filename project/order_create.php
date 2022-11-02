<!DOCTYPE html>
<html>

<head>

    <title>Order Form</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="container-fluid px-0">
        <nav class="navbar navbar-expand-lg bg-dark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse d-flex justify-content-center" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link text-white" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="product_create.php">Create Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="product_read.php">Product List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="customer_create.php">Create Customer</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="customer_read.php">Customer List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="order_summary.php">Order Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="order_details.php">Order Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="order_create.php">Order Form</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="contact_us.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container my-3">
            <div class="page-header">
                <h1>Order Form</h1>
            </div>

            <?php
            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $product_1 = $_POST['product_1'];
                $product_2 = $_POST['product_2'];
                $product_3 = $_POST['product_3'];
                $quantity_1 = $_POST['quantity_1'];
                $quantity_2 = $_POST['quantity_2'];
                $quantity_3 = $_POST['quantity_3'];
                $price_1 = 0;
                $price_2 = 0;
                $price_3 = 0;
                $total_price = 0;

                $flag = 0;
                if ($user_name == "") {
                    echo "Please enter your username!";
                    $flag = 1;
                }
                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    echo "Username not space allow!";
                    $flag = 1;
                } elseif (strlen($user_name) < 6) {
                    echo "Username need at least 6 charecter!";
                    $flag = 1;
                }
                if ($product_1 == "") {
                    echo "Please select your product!";
                    $flag = 1;
                }
                if ($quantity_1 == "") {
                    echo "Please enter how many you want!";
                    $flag = 1;
                }
                if ($quantity_2 == "") {
                    $quantity_2 = null;
                }
                if ($quantity_3 == "") {
                    $quantity_3 = null;
                }
                if ($product_1 == "Trash Can") {
                    $price_1 = 3.95;
                } else if ($product_1 == "Pillow") {
                    $price_1 = 8.99;
                } else if ($product_1 == "Mouse") {
                    $price_1 = 11.35;
                } else if ($product_1 == "Gatorade") {
                    $price_1 = 1.99;
                } else if ($product_1 == "Eye Glasses") {
                    $price_1 = 6;
                } else if ($product_1 == "Earphone") {
                    $price_1 = 7;
                } else if ($product_1 == "Basketball") {
                    $price_1 = 49.99;
                } else if ($product_1 == "BG4500") {
                    $price_1 = 250;
                }

                if ($product_2 == "Trash Can") {
                    $price_2 = 3.95;
                } else if ($product_2 == "Pillow") {
                    $price_2 = 8.99;
                } else if ($product_2 == "Mouse") {
                    $price_2 = 11.35;
                } else if ($product_2 == "Gatorade") {
                    $price_2 = 1.99;
                } else if ($product_2 == "Eye Glasses") {
                    $price_2 = 6;
                } else if ($product_2 == "Earphone") {
                    $price_2 = 7;
                } else if ($product_2 == "Basketball") {
                    $price_2 = 49.99;
                } else if ($product_2 == "BG4500") {
                    $price_2 = 250;
                } else if ($product_3 == "None") {
                    $product_3 = null;
                    $price_3 = 0;
                }

                if ($product_3 == "Trash Can") {
                    $price_3 = 3.95;
                } else if ($product_3 == "Pillow") {
                    $price_3 = 8.99;
                } else if ($product_3 == "Mouse") {
                    $price_3 = 11.35;
                } else if ($product_3 == "Gatorade") {
                    $price_3 = 1.99;
                } else if ($product_3 == "Eye Glasses") {
                    $price_3 = 6;
                } else if ($product_3 == "Earphone") {
                    $price_3 = 7;
                } else if ($product_3 == "Basketball") {
                    $price_3 = 49.99;
                } else if ($product_3 == "BG4500") {
                    $price_3 = 250;
                } else if ($product_3 == "None") {
                    $product_3 = null;
                    $price_3 = 0;
                }

                $total_price += (((int)$price_1 * (int)$quantity_1) + ((int)$price_2 * (int)$quantity_2) + ((int)$price_3 * (int)$quantity_3));

                if ($flag == 0) {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO order_summary SET order_date=:order_date, username=:username, product_1=:product_1, quantity_1=:quantity_1, product_2=:product_2, quantity_2=:quantity_2, product_3=:product_3, quantity_3=:quantity_3";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        //$stmt->bindParam(':order_id ', $order_id);
                        $order_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':order_date', $order_date);
                        $stmt->bindParam(':username', $user_name);
                        $stmt->bindParam(':product_1', $product_1);
                        $stmt->bindParam(':quantity_1', $quantity_1);
                        $stmt->bindParam(':product_2', $product_2);
                        $stmt->bindParam(':quantity_2', $quantity_2);
                        $stmt->bindParam(':product_3', $product_3);
                        $stmt->bindParam(':quantity_3', $quantity_3);
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Order Summary Record was saved.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                        }
                    }

                    // show error
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                    try {
                        // insert query
                        $query = "INSERT INTO order_details SET order_id=:order_id, product_1=:product_1, quantity_1=:quantity_1, price_1=:price_1, product_2=:product_2, quantity_2=:quantity_2, price_2=:price_2, product_3=:product_3, quantity_3=:quantity_3, price_3=:price_3, total_price=:total_price";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        //$stmt->bindParam(':order_id ', $order_id);
                        $order_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':order_id', $order_id);
                        $stmt->bindParam(':product_1', $product_1);
                        $stmt->bindParam(':quantity_1', $quantity_1);
                        $stmt->bindParam(':price_1', $price_1);
                        $stmt->bindParam(':product_2', $product_2);
                        $stmt->bindParam(':quantity_2', $quantity_2);
                        $stmt->bindParam(':price_2', $price_2);
                        $stmt->bindParam(':product_3', $product_3);
                        $stmt->bindParam(':quantity_3', $quantity_3);
                        $stmt->bindParam(':price_3', $price_3);
                        $stmt->bindParam(':total_price', $total_price);
                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Order Details Record was saved.</div>";
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
                        <td>Username</td>
                        <td colspan=3><input type='text' name='username' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product 1</td>
                        <td>
                            <select class="form-select form-select" aria-label=".form-select example" name="product_1">
                                <?php

                                include 'config/database.php';
                                // select all data
                                $query = "SELECT name FROM products ORDER BY name DESC";
                                $stmt = $con->prepare($query);
                                $stmt->execute();

                                // this is how to get number of rows returned
                                $num = $stmt->rowCount();

                                //check if more than 0 record found
                                if ($num > 0) {

                                    // data from database will be here

                                } else {
                                    echo "<div class='alert alert-danger'>No records found.</div>";
                                }

                                //new
                                echo "<option selected>None</option>"; //start dropdown

                                // table body will be here
                                // retrieve our table contents
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // extract row
                                    // this will make $row['firstname'] to just $firstname only
                                    extract($row);
                                    // creating new table row per record
                                    echo "<option value=\"$name\">$name</option>";
                                }

                                // end table
                                echo "</option>";
                                ?>
                            </select>
                        </td>
                        <td>Quantity</td>
                        <td><input type='number' name='quantity_1' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Product 2</td>
                        <td>
                            <select class="form-select form-select" aria-label=".form-select example" name="product_2">
                                <?php

                                include 'config/database.php';
                                // select all data
                                $query = "SELECT name FROM products ORDER BY name DESC";
                                $stmt = $con->prepare($query);
                                $stmt->execute();

                                // this is how to get number of rows returned
                                $num = $stmt->rowCount();

                                //check if more than 0 record found
                                if ($num > 0) {

                                    // data from database will be here

                                } else {
                                    echo "<div class='alert alert-danger'>No records found.</div>";
                                }

                                //new
                                echo "<option selected>None</option>"; //start dropdown

                                // table body will be here
                                // retrieve our table contents
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // extract row
                                    // this will make $row['firstname'] to just $firstname only
                                    extract($row);
                                    // creating new table row per record
                                    echo "<option value=\"$name\">$name</option>";
                                }

                                // end table
                                echo "</option>";
                                ?>
                            </select>
                        </td>
                        <td>Quantity</td>
                        <td><input type='number' name='quantity_2' class='form-control' /></td>
                    </tr>
                    </tr>
                    <tr>
                        <td>Product 3</td>
                        <td>
                            <select class="form-select form-select" aria-label=".form-select example" name="product_3">
                                <?php

                                include 'config/database.php';
                                // select all data
                                $query = "SELECT name FROM products ORDER BY name DESC";
                                $stmt = $con->prepare($query);
                                $stmt->execute();

                                // this is how to get number of rows returned
                                $num = $stmt->rowCount();

                                //check if more than 0 record found
                                if ($num > 0) {

                                    // data from database will be here

                                } else {
                                    echo "<div class='alert alert-danger'>No records found.</div>";
                                }

                                //new
                                echo "<option selected>None</option>"; //start dropdown

                                // table body will be here
                                // retrieve our table contents
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // extract row
                                    // this will make $row['firstname'] to just $firstname only
                                    extract($row);
                                    // creating new table row per record
                                    echo "<option value=\"$name\">$name</option>";
                                }

                                // end table
                                echo "</option>";
                                ?>
                            </select>
                        </td>
                        <td>Quantity</td>
                        <td><input type='number' name='quantity_3' class='form-control' /></td>
                    </tr>
                    <tr class="text-end">
                        <td colspan=4>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='order_details.php' class='btn btn-danger'>Back to Order Details</a>
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