<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Update Order Summary</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <div class="container">
            <div class="page-header">
                <h1>Update Order Summary</h1>
            </div>

            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            include 'config/database.php';

            $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record ID not found.');

            if ($_POST) {

                $username = $_POST['username'];
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];
                $error_message = "";

                if ($username == "") {
                    $error_message .= "<div class='alert alert-danger'>Please select your username!</div>";
                }

                for ($i = 0; $i < count($product_id); $i++) {
                    if (empty($product_id[$i]) || $product_id[$i] == 'Select Product') {
                        $error_message .= "<div class='alert alert-danger'>Please select your product!</div>";
                    }
                    if (empty($quantity[$i]) || $quantity[$i] <= 0) {
                        $error_message .= "<div class='alert alert-danger'>Please enter a least one!</div>";
                    }
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    try {
                        // insert query
                        $query = "UPDATE order_summary SET username=:username, order_date=:order_date WHERE order_id=:order_id";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':username', $username);
                        $order_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':order_date', $order_date);
                        $stmt->bindParam(':order_id', $order_id);

                        // Execute the query
                        if ($stmt->execute()) {
                            $query = "DELETE FROM order_details WHERE order_id=:order_id";
                            $stmt = $con->prepare($query);
                            $stmt->bindParam(':order_id', $order_id);
                            if ($stmt->execute()) {
                                $purchased = 0;
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
                                        $record_number = $count + 1;
                                        if ($stmt->execute()) {
                                            $purchased++;
                                        } else {
                                            echo "<div class='alert alert-danger'>Unable to save record.</div>";
                                        }
                                    }
                                    // show errorproduct_id
                                    catch (PDOException $exception) {
                                        die('ERROR: ' . $exception->getMessage());
                                    }
                                }
                                if ($purchased == count($product_id)) {
                                    header("Location: order_summary.php?update={$order_id}");
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
            try {
                // prepare select query
                $query = "SELECT * FROM order_summary WHERE order_summary.order_id =:order_id";
                $stmt = $con->prepare($query);
                $stmt->bindParam(":order_id", $order_id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
            } catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }

            ?>

            <!--we have our html form here where new record information can be updated-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?order_id={$order_id}"); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-dark table-responsive table-bordered' id='delete_row'>
                    <tr>
                        <td class="text-center">Username</td>
                        <td colspan="4">
                            <select class="form-select form-select" name="username" aria-label=".form-select example">
                                <option><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></option>
                                <?php
                                $query = "SELECT username FROM customers ORDER BY username DESC";
                                $stmt = $con->prepare($query);
                                $stmt->execute();
                                $row = $stmt->rowCount();
                                if ($row > 0) {
                                    // table body will be here
                                    // retrieve our table contents
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        // extract row
                                        extract($row);
                                        // creating new table row per record
                                        echo "<option value=\"$username\">$username</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php

                    $query = "SELECT * FROM order_details INNER JOIN products ON products.id = order_details.product_id WHERE order_details.order_id=:order_id GROUP BY order_details.product_id;";
                    $stmt = $con->prepare($query);
                    $stmt->bindParam(":order_id", $order_id);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    if ($count > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<tr class='pRow'>
                            <td class='col-2 text-center'>Product</td>
                            <td class='col-6'><select class=\"form-select form-select\" aria-label=\".form-select example\" name=\"product_id[]\">
                            <option value=\"$id\">$name</option>";
                            $query = "SELECT id, name, price FROM products ORDER BY id ASC";
                            $stmt2 = $con->prepare($query);
                            $stmt2->execute();
                            $num = $stmt2->rowCount();
                            if ($num > 0) {
                                while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);
                                    echo "<option value='$id'>$name </option>";
                                }
                            }

                            echo "<td class='col-2 text-center'>Quantity</td>
                            <td class='col-1'>
                            <input type='number' name='quantity[]' value='$quantity' class='form-control text-center'/></td>
                            <td class='text-center col-1'><a class='btn btn-danger mx-2' name='delete' onclick='deleteRow(this)'/><i class='fa-solid fa-trash'></i></a></td></tr>";
                        }
                    }
                    ?>

                    <tr>
                        <td class="text-center">
                            <input type="button" value="Add More Product" class="add_one btn btn-info" onclick="myFunction()" />
                        </td>
                        <td colspan="4" class="text-end">
                            <button type='submit' class='btn btn-success' onclick="checkDuplicate(event)">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                            <a href='order_summary.php' class='btn btn-secondary'><i class="fa-sharp fa-solid fa-circle-arrow-left"></i> Back to Order Summary</a>
                            <?php echo "<a href='order_delete.php?order_id={$order_id}' class='btn btn-danger m-r-1em'><i class='fa-solid fa-trash'></i></a>"; ?>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <!-- end .container -->
    </div>

    <script>
        function checkDuplicate(event) {
            var newarray = [];
            var selects = document.getElementsByTagName('select');
            for (var i = 0; i < selects.length; i++) {
                newarray.push(selects[i].value);
            }
            if (newarray.length !== new Set(newarray).size) {
                alert("There are duplicate item in the your order, please select again!");
                event.preventDefault();
            }
        }

        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var rows = document.getElementsByClassName('pRow');
                // Get the last row in the table
                var lastRow = rows[rows.length - 1];
                // Clone the last row
                var clone = lastRow.cloneNode(true);
                // Insert the clone after the last row
                lastRow.insertAdjacentElement('afterend', clone);
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>