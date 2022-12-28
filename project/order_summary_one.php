<!DOCTYPE HTML>
<html>

<head>
    <title>Read Order Summary</title>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <?php include 'topnav.php'; ?>

    <!-- container -->
    <div class="container-fluid">

        <div class="container-fluid row mt-3">

            <!-- PHP read one record will be here -->
            <div class="col-6">
                <div class="page-header">
                    <h1 class="text-center">Order Summary</h1>
                </div>
                <?php
                // get passed parameter value, in this case, the record ID
                // isset() is a PHP function used to verify if a value is there or not
                if (isset($_GET['order_id'])) {
                    $order_id = $_GET['order_id'];
                } else {
                    die('ERROR: Record ID not found.');
                }
                include 'config/database.php';

                $sum_price = "SELECT sum(price*quantity) AS total_price FROM order_details INNER JOIN order_summary ON order_summary.order_id = order_details.order_id INNER JOIN products ON products.id = order_details.product_id WHERE order_summary.order_id =:order_id GROUP BY order_summary.order_id";
                $stmt = $con->prepare($sum_price);
                $stmt->bindParam(":order_id", $order_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);

                try {
                    $query = "SELECT * FROM order_details INNER JOIN products ON products.id = order_details.product_id INNER JOIN order_summary ON order_summary.order_id = order_details.order_id WHERE order_details.order_id=:order_id ORDER BY id ASC";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":order_id", $order_id);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if ($count > 0) {
                        echo "<table class='table table-hover table-dark table-responsive table-borderless border border-3'>";

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            $tprice = $price * $quantity;
                            echo "<tr>";
                            echo "<td class='col-4'>{$name}</td>";
                            $price = htmlspecialchars(number_format($price, 2, '.', ''));
                            echo "<td class='col-3 text-center'>RM {$price}</td>";
                            echo "<td class='col-2 text-center'><strong>X</strong> &nbsp&nbsp{$quantity}</td>";
                            $tprice = htmlspecialchars(number_format($tprice, 2, '.', ''));
                            echo "<td class='col-3 text-end'>RM {$tprice}</td>";
                            echo "</tr>";
                        }
                    }
                    echo "<tr class='border-top'>";
                    echo "<td class='col-2'>Subtotal</td>";
                    echo "<td colspan=3 class='text-end'>";
                    $total_price = number_format((float)$total_price, 2, '.', '');
                    echo "RM $total_price";
                    echo "</td></tr>";

                    echo "<tr>";
                    echo "<td class='col-2' >Total</td>";
                    echo "<td colspan=3 class='text-end'>";
                    $total_price = number_format($total_price, 1) . "0";
                    echo "RM $total_price";
                    echo "</td></tr></table>";
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
                // show error
                ?>
            </div>

            <div class="col-6">
                <div class="page-header">
                    <h1 class="text-center">User Info</h1>
                </div>
                <?php
                include 'config/database.php';
                try {
                    $query = "SELECT * FROM customers INNER JOIN order_summary on order_summary.username = customers.username WHERE order_id = :order_id";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":order_id", $order_id);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $first_name = $row['first_name'];
                    $last_name = $row['last_name'];
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
                        <td>First Name</td>
                        <td><?php echo htmlspecialchars($first_name, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><?php echo htmlspecialchars($last_name, ENT_QUOTES);  ?></td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td><img src="cus_uploads/<?php echo htmlspecialchars($cus_image, ENT_QUOTES);  ?>" class="w-25 m-3"></td>
                    </tr>
                </table>
                <div class="text-end">
                    <a href='order_summary.php' class='btn btn-secondary'>Back to read Order Summary</a>
                </div>
            </div>
        </div>
    </div>
    <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
</body>

</html>