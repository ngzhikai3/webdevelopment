<!DOCTYPE HTML>
<html>

<head>

    <title>Read Customer Profile</title>

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
                            <a class="nav-link text-white" href="order_create.php">Order Form</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="contact_us.php">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- container -->
        <div class="container my-3">
            <div class="page-header">
                <h1>Read Customer Profile</h1>
            </div>

            <?php
            // include database connection
            include 'config/database.php';

            // delete message prompt will be here

            // select all data
            $query = "SELECT user_id, username, gender FROM customers ORDER BY user_id DESC";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='customer_create.php' class='btn btn-primary m-b-1em my-3'>Create New Customer</a>";

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here

            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            //new
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>User ID</th>";
            echo "<th>Username</th>";
            echo "<th>Gender</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$user_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$gender}</td>";
                echo "<td>";
                // read one record
                echo "<a href='customer_read_one.php?user_id={$user_id}' class='btn btn-info m-r-1em mx-2'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?user_id={$user_id}' class='btn btn-primary m-r-1em mx-2'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_customer({$user_id});' class='btn btn-danger mx-2'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";

            ?>

        </div> <!-- end .container -->

        <!-- confirm delete record will be here -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>