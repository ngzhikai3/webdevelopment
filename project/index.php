<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Home Page</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png"/>
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="css/button.css" rel="stylesheet" />

</head>

<body>

    <div class="container-fluid px-0">
        <?php include 'topnav.php'; ?>

        <div class="container-fluid row m-0 pt-5 bg-warning">
            <div class="">
                <?php
                include 'config/database.php';

                $query = "SELECT * FROM customers";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $customer = $stmt->rowCount();

                $query = "SELECT * FROM products";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $products = $stmt->rowCount();

                $query = "SELECT * FROM order_summary";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $order = $stmt->rowCount();
                echo "<div class='row my-3'>
                    <div class='col-xl-4 col-lg-6 col-md-6 my-2'>
                        <div class='card bg-light'>
                            <div class='card-header'>
                                <h3 class='card-title'>Total <strong class='text-warning text-decoration-underline'>$products</strong> Products On Sale</h1>
                            </div>
                            <div class='card-body d-flex justify-content-end'>
                                <a href='product_read.php' class='btn submitbtn w-50 p-2'>View Product List</a>
                            </div>
                        </div>
                    </div>
                    <div class='col-xl-4 col-lg-6 col-md-6 my-2'>
                        <div class='card bg-light'>
                            <div class='card-header'>
                                <h3 class='card-title'>Total <strong class='text-warning text-decoration-underline'>$customer</strong> Customers Registered</h1>
                            </div>
                            <div class='card-body d-flex justify-content-end'>
                                <a href='customer_read.php' class='btn submitbtn w-50 p-2'>View Customer List</a>
                            </div>
                        </div>
                    </div>
                    <div class='col-xl-4 col-lg-6 col-md-6 my-2'>
                        <div class='card bg-light'>
                            <div class='card-header'>
                                <h3 class='card-title'>Total <strong class='text-warning text-decoration-underline'>$order</strong> Order</h1>
                            </div>
                            <div class='card-body d-flex justify-content-end'>
                                <a href='order_summary.php' class='btn submitbtn w-50 p-2'>View Order List</a>
                            </div>
                        </div>
                    </div>
                </div>";
                ?>
            </div>

            <div class="container my-3">
                <?php
                include 'config/database.php';

                $query = "SELECT MAX(order_id) as order_id FROM order_summary";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $order_id = $row['order_id'];
                isset($order_id);
                // read current record's data
                try {
                    // prepare select query
                    $query = "SELECT *, sum(price*quantity) AS total_price FROM order_details INNER JOIN order_summary ON order_summary.order_id = order_details.order_id INNER JOIN products ON products.id = order_details.product_id INNER JOIN customers ON customers.username = order_summary.username WHERE order_summary.order_id = ? ";
                    $stmt = $con->prepare($query);
                    // Bind the parameter
                    $stmt->bindParam(1, $order_id);
                    // execute our query
                    $stmt->execute();
                    // store retrieved row to a variable
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // values to fill up our form
                    extract($row);
                }
                // show error
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                ?>
                <h1 class="text-center">Latest Order</h1>
                <table class='table table-dark table-hover table-responsive table-bordered text-center'>
                    <tr class="table table-light">
                        <th class="col-1">Order ID</th>
                        <th class="col-3">Order Date</th>
                        <th class="col-2">First Name</th>
                        <th class="col-2">Last Name</th>
                        <th class="col-3">Total Price</th>
                        <th class="col-1">View Details</th>
                    </tr>
                    <tr class='table-dark'>
                        <?php
                        echo "<td class='col-1'>$order_id</td>";
                        echo "<td class='col-3'>$order_date</td>";
                        echo "<td class='col-2'>$first_name</td>";
                        echo "<td class='col-2'>$last_name</td>";
                        $total_price = number_format($total_price, 1) . "0";
                        echo "<td class='col-3'>RM $total_price</td>";
                        echo "<td class='col-1'><a href='order_summary_one.php?order_id={$order_id}' class='btn btn-light m-r-1em mx-3'><i class='fa-solid fa-circle-info'></i></a></td>";
                        ?>
                    </tr>
                </table>
            </div>

            <div class="container my-3">
                <?php
                $query = "SELECT *, sum(price*quantity) AS top_price FROM order_summary INNER JOIN order_details ON order_details.order_id = order_summary.order_id INNER JOIN products ON products.id = order_details.product_id INNER JOIN customers ON customers.username = order_summary.username GROUP BY order_summary.order_id ORDER BY top_price DESC LIMIT 1";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);


                echo "<h1 class='text-center'>Highest Purchased Amount Order</h1>
                <table class='table table-dark table-hover table-responsive table-bordered text-center'>
                    <tr class='table-light'>
                        <th class='col-1'>Order ID</th>
                        <th class='col-3'>Order Date</th>
                        <th class='col-2'>First Name</th>
                        <th class='col-2'>Last Name</th>
                        <th class='col-3'>Highest Amount</th>
                        <th class='col-1'>View Details</th>
                    </tr>
                    <tr class='table-dark'>";

                echo "<td class='col-1'>$order_id</td>";
                echo "<td class='col-3'>$order_date</td>";
                echo "<td class='col-2'>$first_name</td>";
                echo "<td class='col-2'>$last_name</td>";
                $top_price = number_format($top_price, 1) . "0";
                echo "<td class='col-3'>RM $top_price</td>";
                echo "<td class='col-1'><a href='order_summary_one.php?order_id={$order_id}' class='btn btn-light m-r-1em mx-3'><i class='fa-solid fa-circle-info'></i></a></td>";
                ?>
                </tr>
                </table>
            </div>

            <div class="container my-3">
                <?php
                $query = "SELECT *, sum(quantity) AS popular ,sum(price*quantity) AS total_sell FROM products INNER JOIN order_details ON order_details.product_id = products.id group by name order by sum(quantity) desc limit 5;";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count > 0) {
                    echo "<h1 class=\"text-center\">Top 5 Selling Product</h1>";
                    echo "<table class='table table-dark table-hover table-responsive table-bordered text-center'>";
                    echo "<tr class='table-light'><th>Product Name</th>
                        <th>Quantity</th>
                        <th class='text-end'>Price per unit</th>
                        <th class='text-end'>Total Selling Price</th>
                        <th>View Details</th></tr>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr class='table-dark'>";
                        echo "<td>{$name}</td>";
                        echo "<td>{$popular}</td>";
                        $price = number_format($price, 1) . "0";
                        echo "<td class='text-end'>RM $price</td>";
                        $total_sell = number_format($total_sell, 1) . "0";
                        echo "<td class='text-end'>RM $total_sell</td>";
                        echo "<td><a href='product_read_one.php?id={$id}' class='btn btn-light m-r-1em mx-3'><i class='fa-solid fa-circle-info'></i></a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } ?>
            </div>

            <div class="container my-3">
                <?php
                $query = "SELECT * FROM products left JOIN order_details ON order_details.product_id = products.id WHERE product_id is NULL limit 3";
                $stmt = $con->prepare($query);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count > 0) {
                    echo "<h1 class=\"text-center\">TOP 3 Products That Never Purchase</h1>";
                    echo "<table class='table table-dark table-hover table-responsive table-bordered text-center'>";
                    echo "<tr class='table-light'>
                            <th>Product Id</th>
                            <th>Product Name</th>
                            <th>Product Photo</th>
                            <th class='text-end'>Price per unit</th>
                            <th>View Details</th></tr>";
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr class='table-dark'>";
                        echo "<td>{$id}</td>";
                        echo "<td>{$name}</td>";
                        echo "<td><img src='uploads/$image' class='w-25'></td>";
                        $price = number_format((float)$price, 2, '.', '');
                        echo "<td class='text-end'>RM $price</td>";
                        echo "<td><a href='product_read_one.php?id={$id}' class='btn btn-light m-r-1em mx-3'><i class='fa-solid fa-circle-info'></i></a></td>";
                        echo "</tr>";
                    }
                    echo "
                    </table>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>