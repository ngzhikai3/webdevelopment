<!DOCTYPE HTML>
<html>

<head>
    <title>Read Order Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <?php include 'topnav.html'; ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Order Summary</h1>
        </div>

        <!-- PHP read one record will be here -->

        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        if (isset($_GET['order_id'])) {
            $order_id = $_GET['order_id'];
        } else {
            die('ERROR: Record ID not found.');
        }
        include 'config/database.php';

        $money = "SELECT sum(price*quantity) AS total_price FROM order_details INNER JOIN order_summary ON order_summary.order_id = order_details.order_id INNER JOIN products ON products.id = order_details.product_id WHERE order_summary.order_id =:order_id GROUP BY order_summary.order_id";
        $stmt = $con->prepare($money);
        $stmt->bindParam(":order_id", $order_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        extract($row);

        try {
            $query = "SELECT * FROM order_details INNER JOIN products ON products.id = order_details.product_id INNER JOIN order_summary ON order_summary.order_id = order_details.order_id WHERE order_details.order_id=:order_id";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count > 0) {
                echo "<table class='table table-hover table-responsive table-borderless w-50 border border-3'>";
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $plus = $price * $quantity;
                    echo "<tr>";
                    echo "<td class='col-4'>{$name}</td>";
                    $price = htmlspecialchars(number_format($price, 2, '.', ''));
                    echo "<td class='col-3 text-center'>RM {$price}</td>";
                    echo "<td class='col-2 text-center'><strong>X</strong> &nbsp&nbsp{$quantity}</td>";
                    $plus = htmlspecialchars(number_format($plus, 2, '.', ''));
                    echo "<td class='col-3 text-end'>RM {$plus}</td>";
                    echo "</tr>";
                }
            }
            echo "<tr class='border border-3'>";
            echo "<td class='col-2' >Total Price</td>";
            echo "<td colspan=4 class='text-end'>";
            $dprice = htmlspecialchars(round(number_format($total_price, 2, '.', '')));
            echo "RM $dprice";
            echo "</td></tr></table>";
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        // show error
        ?>

        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <div class="w-50 d-flex justify-content-end">
            <a href='order_summary.php' class='btn btn-danger'>Back to read Order Summary</a>
        </div>

    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>
</body>

</html>