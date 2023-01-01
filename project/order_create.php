<?php
include 'check.php';
?>

<!DOCTYPE html>
<html>

<head>

    <title>Order Form</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <div class="container my-3">
            <div class="page-header text-center">
                <h1>Order Form</h1>
            </div>

            <?php

            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];
                $error_message = "";

                if ($user_name == "Select Username") {
                    $error_message .= "<div class='alert alert-danger'>Please select your username!</div>";
                }

                for ($count = 0; $count < count($product_id); $count++) {
                    if (empty($product_id[$count]) || $product_id[$count] == 'Select Product') {
                        $error_message .= "<div class='alert alert-danger'>Please select your product!</div>";
                    }
                    if (empty($quantity[$count]) || $quantity[$count] <= 0) {
                        $error_message .= "<div class='alert alert-danger'>Please enter a least one!</div>";
                    }
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO order_summary SET username=:username, order_date=:order_date";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':username', $user_name);
                        $order_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':order_date', $order_date);

                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: order_summary.php?update={save}");
                            $query = "SELECT MAX(order_id) as order_id FROM order_summary";
                            $stmt = $con->prepare($query);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $order_id = $row['order_id'];
                            for ($count = 0; $count < count($product_id); $count++) {
                                try {
                                    // insert query
                                    $query = "INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity";
                                    // prepare query for execution
                                    $stmt = $con->prepare($query);
                                    // bind the parameters
                                    $stmt->bindParam(':order_id', $order_id);
                                    $stmt->bindParam(':product_id', $product_id[$count]);
                                    $stmt->bindParam(':quantity', $quantity[$count]);

                                    //echo $product_id[$count];
                                    // Execute the query
                                    if ($stmt->execute()) {
                                    } else {
                                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                    }
                                }
                                // show errorproduct_id
                                catch (PDOException $exception) {
                                    die('ERROR: ' . $exception->getMessage());
                                }
                            }
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
                <table class='table table-hover table-dark table-responsive table-bordered' id='delete_row'>
                    <tr>
                        <td class="text-center">Username</td>
                        <td colspan=5>
                            <select class="form-select form-select" aria-label=".form-select example" name="username">
                                <?php

                                include 'config/database.php';
                                // select all data
                                $query = "SELECT username FROM customers ORDER BY username DESC";
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
                                echo "<option value='Select Username' selected>Select Username</option>"; //start dropdown
                                // table body will be here
                                // retrieve our table contents
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // extract row
                                    // this will make $row['firstname'] to just $firstname only
                                    extract($row);
                                    // creating new table row per record
                                    echo "<option value=\"$username\">$username</option>";
                                }
                                // end table
                                echo "</option>";
                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php
                    echo "  <tr class=\"pRow\"> 
                        <td class='col-2 text-center'>Product</td>
                        <td class='col-6'>
                        <select class=\"form-select form-select\" aria-label=\".form-select example\" name=\"product_id[]\">
                        <option value='Select Product' selected>Select Product</option>";
                    $query = "SELECT * FROM products ORDER BY id DESC";
                    $stmt = $con->prepare($query);
                    $stmt->execute();
                    $num = $stmt->rowCount();
                    if ($num > 0) {
                    } else {
                        echo "<div class='alert alert-danger'>No records found.</div>";
                    }
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<option value=\"$id\">$name</option>";
                    }

                    echo "</select>
                        </td>
                        <td class='col-2 text-center'>Quantity</td>
                        <td class='col-1'><input type='number' name='quantity[]' value='1' class='form-control text-center' /> </td>
                        <td class='text-center col-1'><a class='btn btn-danger mx-2' name='delete' onclick='deleteRow(this)'/><i class='fa-solid fa-trash'></i></a></td>
                    </tr>";
                    ?>
                    <tr>
                        <td>
                            <input type="button" value="Add More Product" class="add_one btn btn-info" />
                        </td>
                        <td colspan=4 class="text-end">
                            <button type='submit' class='btn btn-success' onclick="checkDuplicate(event)">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                            <a href='order_summary.php' class='btn btn-secondary'>Go to Order Summary <i class="fa-sharp fa-solid fa-circle-arrow-right"></i></a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- end .container -->
    </div>

    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.pRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
        }, false);

        function deleteRow(r) {
            var total = document.querySelectorAll('.pRow').length;
            if (total > 1) {
                var i = r.parentNode.parentNode.rowIndex;
                document.getElementById("delete_row").deleteRow(i);
            } else {
                alert("You need at at least one products");
            }
        }

        function checkDuplicate(event) {
            var newarray = [];
            var selects = document.getElementsByTagName('select');
            for (var i = 0; i < selects.length; i++) {
                newarray.push(selects[i].value);
            }
            if (newarray.length !== new Set(newarray).size) {
                alert("There are duplicate item in the your order! Please select again!");
                event.preventDefault();
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>