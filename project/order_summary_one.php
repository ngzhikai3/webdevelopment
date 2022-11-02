<!DOCTYPE HTML>
<html>

<head>

    <title>Read One Product</title>

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
                <h1>Read Order Details</h1>
            </div>

            <!-- PHP read one record will be here -->
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $order_sid = isset($_GET['order_sid ']) ? $_GET['order_sid '] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT order_sid, order_date, username, product_1, quantity_1, product_2, quantity_2, product_3, quantity_3 FROM order_summary WHERE order_sid = :order_sid  ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":order_id ", $order_sid);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $order_sid  = $row['order_sid '];
                $order_date  = $row['order_date '];
                $username  = $row['username '];
                $product_1 = $row['product_1'];
                $quantity_1 = $row['quantity_1'];
                $product_2 = $row['product_2'];
                $quantity_2 = $row['quantity_2'];
                $product_3 = $row['product_3'];
                $quantity_3 = $row['quantity_3'];
                // shorter way to do that is extract($row)
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <!-- HTML read one record table will be here -->
            <!--we have our html table here where the record will be displayed-->
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Order ID</td>
                    <td><?php echo htmlspecialchars($order_sid, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Order Date</td>
                    <td><?php echo htmlspecialchars($order_date, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product 1</td>
                    <td><?php echo htmlspecialchars($product_1, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Quantity 1</td>
                    <td><?php echo htmlspecialchars($quantity_1, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product 2</td>
                    <td><?php echo htmlspecialchars($product_2, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Quantity 2</td>
                    <td><?php echo htmlspecialchars($quantity_2, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Product 3</td>
                    <td><?php echo htmlspecialchars($product_3, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Quantity 3</td>
                    <td><?php echo htmlspecialchars($quantity_3, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <a href='order_summary.php' class='btn btn-danger'>Back to read order summary</a>
                    </td>
                </tr>
            </table>

        </div> <!-- end .container -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>