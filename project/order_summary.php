<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Read Order List</title>

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

        <!-- container -->
        <div class="container my-3">
            <div class="page-header">
                <h1>Read Order Summary</h1>
            </div>

            <?php
            if (isset($_GET['update'])) {
                echo "<div class='alert alert-success'>Record was updated.</div>";
            }
            // include database connection
            include 'config/database.php';

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }

            // select all data
            $query = "SELECT * , sum(price*quantity) AS total_price FROM order_details INNER JOIN order_summary ON order_summary.order_id = order_details.order_id INNER JOIN products ON products.id = order_details.product_id INNER JOIN customers ON customers.username = order_summary.username GROUP BY order_summary.order_id";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='order_create.php' class='btn btn-success m-b-1em my-3 me-3'>Create New Order</a>";

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here

            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            //new
            echo "<table class='table table-hover table-dark table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th class='text-center' data-aos='fade-left'>Order ID</th>";
            echo "<th class='text-center' data-aos='fade-left'>Order Date</th>";
            echo "<th class='text-center' data-aos='fade-left'>First Name</th>";
            echo "<th class='text-center' data-aos='fade-right'>Last Name</th>";
            echo "<th class='text-end' data-aos='fade-right'>Total Price</th>";
            echo "<th class='text-center' data-aos='fade-right'>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);

                // creating new table row per record
                echo "<tr>";
                echo "<td class='text-center' data-aos='fade-left'>{$order_id}</td>";
                echo "<td class='text-center' data-aos='fade-left'>{$order_date}</td>";
                echo "<td class='text-center' data-aos='fade-left'>{$first_name}</td>";
                echo "<td class='text-center' data-aos='fade-right'>{$last_name}</td>";
                $total_price = number_format($total_price, 1) . "0";
                echo "<td class='text-end' data-aos='fade-right'>RM $total_price</td>";
                echo "<td class='text-center' data-aos='fade-right'>";
                // read one record
                echo "<a href='order_summary_one.php?order_id={$order_id}' class='btn btn-info m-r-1em mx-3'><i class='fa-solid fa-eye'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='order_summary_update.php?order_id={$order_id}' class='btn btn-primary m-r-1em mx-3'><i class='fa-solid fa-pen-to-square'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_summary({$order_id});' class='btn btn-danger mx-3'><i class='fa-solid fa-trash'></i></a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";

            ?>

        </div>
        <!-- end .container -->

        <!-- confirm delete record will be here -->
        <script type='text/javascript'>
            // confirm record deletion
            function delete_summary(order_id) {

                if (confirm('Are you sure?')) {
                    // if user clicked ok,
                    // pass the id to delete.php and execute the delete query
                    window.location = 'order_delete.php?order_id=' + order_id;
                }
            }
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    
</body>

</html>