<!DOCTYPE HTML>
<html>

<head>

    <title>Read One Product</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class="bg-warning">

    <?php
    include 'check.php';
    ?>

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <div class="container my-3">
            <div class="page-header">
                <h1>Read Product</h1>
            </div>

            <!-- PHP read one record will be here -->
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

            $action = isset($_GET['action']) ? $_GET['action'] : "";
            if ($action == 'deleted') {
                echo "<div class='alert alert-success'>Record was deleted.</div>";
            }
            if ($action == 'nodeleted') {
                echo "<div class='alert alert-danger'>This product has been ordered so cannot be delete.</div>";
            }

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM products WHERE id = :id ";
                $stmt = $con->prepare($query);

                // Bind the parameter
                $stmt->bindParam(":id", $id);

                // execute our query
                $stmt->execute();

                // store retrieved row to a variable
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // values to fill up our form
                $name = $row['name'];
                $description = $row['description'];
                $price = $row['price'];
                $promotion_price = $row['promotion_price'];
                $image = $row['image'];
                $manufacture_date = $row['manufacture_date'];
                $expired_date = $row['expired_date'];
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
                <tr data-aos='fade-right'>
                    <td class="text-center">Name</td>
                    <td colspan="3"><?php echo htmlspecialchars($name, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos='fade-left'>
                    <td class="text-center">Description</td>
                    <td colspan="3"><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos='fade-right'>
                    <td class="text-center col-3">Price</td>
                    <td class="text-center col-3"><?php
                                                    $price = number_format($price, 1) . "0";
                                                    echo "RM " . htmlspecialchars($price, ENT_QUOTES);  ?></td>
                    <td class="text-center col-3">Promotion Price</td>
                    <td class="text-center col-3"> <?php
                                                    if ($promotion_price == NULL) {
                                                        echo htmlspecialchars($promotion_price, ENT_QUOTES);
                                                    } else {
                                                        $promotion_price = number_format($promotion_price, 1) . "0";
                                                        echo "RM " . htmlspecialchars($promotion_price, ENT_QUOTES);
                                                    }  ?></td>
                </tr>
                <tr data-aos='fade-left'>
                    <td class="text-center col-3">Manufacture Date</td>
                    <td class="text-center col-3"><?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?></td>
                    <td class="text-center col-3">Expired Date</td>
                    <td class="text-center col-3"><?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?></td>
                </tr>
                <tr data-aos='fade-right'>
                    <td class="text-center">Photo</td>
                    <td colspan="3"><img src="uploads/<?php echo htmlspecialchars($image, ENT_QUOTES); ?>"></td>
                </tr>
                <tr data-aos='fade-left'>
                    <td></td>
                    <td class="text-end" colspan="3">
                        <?php echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'><i class='fa-solid fa-pen-to-square'></i></a>"; ?>
                        <a href='product_read.php' class='btn btn-secondary'><i class="fa-sharp fa-solid fa-circle-arrow-left"></i> Back to Product List</a>
                        <?php echo "<a href='product_delete.php?id={$id}' class='btn btn-danger m-r-1em'><i class='fa-solid fa-trash'></i></a>"; ?>
                    </td>
                </tr>
            </table>
        </div>
        <!-- end .container -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    
</body>

</html>