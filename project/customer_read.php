<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Read Customer Profile</title>

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
                <h1>Read Customer Profile</h1>
            </div>

            <?php
            if (isset($_GET['update'])) {
                echo "<div class='alert alert-success'>Record was save.</div>";
            }
            // include database connection
            include 'config/database.php';

            // delete message prompt will be here
            $action = isset($_GET['action']) ? $_GET['action'] : "";

            // if it was redirected from delete.php
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            if ($action == 'nodeleted') {
                echo "<div class='alert alert-danger'>This customer had order placed so cannot be delete.</div>";
            }
            if ($action == 'self') {
                echo "<div class='alert alert-danger'>Cannot delete yourself.</div>";
            }

            // select all data
            $query = "SELECT * FROM customers ORDER BY user_id DESC";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='customer_create.php' class='btn btn-success m-b-1em my-3'>Create New Customer</a>";

            //check if more than 0 record found
            if ($num > 0) {

                // data from database will be here

            } else {
                echo "<div class='alert alert-danger'>No records found.</div>";
            }

            //new
            echo "<table class='table table-hover table-dark table-responsive table-bordered' data-aos='fade-top'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th class='text-center col-1'>User ID</th>";
            echo "<th class='text-center col-2'>First Name</th>";
            echo "<th class='text-center col-2'>Last Name</th>";
            echo "<th class='text-center col-2'>Gender</th>";
            echo "<th class='text-center col-2'>Photo</th>";
            echo "<th class='text-center col-3'>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td class='col-1 text-center'>{$user_id}</td>";
                echo "<td class='col-2 text-center text-break'>{$first_name}</td>";
                echo "<td class='col-2 text-center text-break'>{$last_name}</td>";
                if ($gender == "male") {
                    echo "<td class='col-2 text-center'><i class='fa-solid fa-person fs-1 text-primary'></i></td>";
                } else {
                    echo "<td class='col-2 text-center'><i class='fa-solid fa-person-dress fs-1 text-danger'></i></td>";
                }
                echo "<td class='col-2 text-center'><img src='cus_uploads/$cus_image' class='w-25'></td>";
                echo "<td class='col-3'>";
                // read one record
                echo "<a href='customer_read_one.php?user_id={$user_id}' class='btn btn-info m-r-1em mx-3'><i class='fa-solid fa-eye'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='customer_update.php?user_id={$user_id}' class='btn btn-primary m-r-1em mx-3'><i class='fa-solid fa-pen-to-square'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_customer({$user_id});' class='btn btn-danger mx-3'><i class='fa-solid fa-trash'></a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";

            ?>

        </div>
        <!-- end .container -->
    </div>

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_customer(user_id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'customer_delete.php?user_id=' + user_id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

</body>

</html>