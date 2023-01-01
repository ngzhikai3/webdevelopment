<!DOCTYPE HTML>
<html>

<head>

    <title>Read Product List</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <?php
    include 'check.php';
    ?>

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <!-- container -->
        <div class="container my-3">
            <div class="page-header">
                <h1>Read Product List</h1>
            </div>

            <?php
            if (isset($_GET['update'])) {
                echo "<div class='alert alert-success'>Record was saved.</div>";
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
                echo "<div class='alert alert-danger'>This product has been ordered so cannot be delete.</div>";
            }

            // select all data
            $query = "SELECT * FROM products ORDER BY id DESC";
            $stmt = $con->prepare($query);
            $stmt->execute();

            // this is how to get number of rows returned
            $num = $stmt->rowCount();

            // link to create record form
            echo "<a href='product_create.php' class='btn btn-success m-b-1em my-3'>Create New Product</a>";

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
            echo "<th class='text-center'>ID</th>";
            echo "<th class='text-center'>Name</th>";
            echo "<th>Description</th>";
            echo "<th class='text-end'>Price</th>";
            echo "<th class='text-center'>Photo</th>";
            echo "<th class='text-center'>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td class='col-1 text-center'>{$id}</td>";
                echo "<td class='col-2 text-center'>{$name}</td>";
                echo "<td class='col-2'>{$description}</td>";
                $price = number_format($price, 1) . "0";
                echo "<td class='text-end col-1'>RM $price</td>";
                echo "<td class='col-3 text-center'><img src='uploads/$image' class='w-25'></td>";
                echo "<td class='col-3'>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em mx-3'><i class='fa-solid fa-eye'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em mx-3'><i class='fa-solid fa-pen-to-square'></i></a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_product({$id});' class='btn btn-danger mx-3'><i class='fa-solid fa-trash'></i></a>";
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
        function delete_product(id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>