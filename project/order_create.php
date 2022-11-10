<?php
include 'check.php';
?>

<!DOCTYPE html>
<html>

<head>

    <title>Order Form</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>



    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <div class="container my-3">
            <div class="page-header">
                <h1>Order Form</h1>
            </div>

            <?php
            if ($_POST) {
                // include database connection
                $user_name = $_POST['username'];
                $product_id = $_POST['product_id'];
                $quantity = $_POST['quantity'];

                $flag = 0;
                if ($user_name == "") {
                    echo "Please enter your username!";
                    $flag = 1;
                }
                $space = " ";
                $word = $_POST['username'];
                if (strpos($word, $space) !== false) {
                    echo "Username not space allow!";
                    $flag = 1;
                } elseif (strlen($user_name) < 6) {
                    echo "Username need at least 6 charecter!";
                    $flag = 1;
                }

                if ($flag == 0) {

                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO order_summary SET username=:username, order_date=:order_date";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':username', $username);
                        $order_date = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':order_date', $order_date);

                        // Execute the query
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Your order is created.</div>";
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
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Username</td>
                        <td colspan=3>
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
                                echo "<option selected>None</option>"; //start dropdown
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
                        <td>Product</td>
                        <td>
                            <select class=\"form-select form-select\" aria-label=\".form-select example\" name=\"product_id[]\">
                            <option>None</option>";
                    $query = "SELECT id, name, price FROM products ORDER BY id DESC";
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
                        <td>Quantity</td>
                        <td><select class=\"form-select form-select-lg mb-3\" name=\"quantity[]\" aria-label=\".form-select-lg example\">
                                <option value=0>0</option>
                                <option value=1>1</option>
                                <option value=2>2</option>
                                <option value=3>3</option>
                            </select>
                            </td>
                    </tr>";
                    ?>
                    <tr>
                        <td>
                            <input type="button" value="Add More Product" class="add_one btn btn-warning" />
                            <input type="button" value="Delete" class="delete_one btn btn-warning" />
                        </td>
                        <td colspan=4 class="text-end">
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='order_read.php' class='btn btn-danger'>Back to Order Details</a>
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
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.pRow').length;
                if (total > 1) {
                    var element = document.querySelector('.pRow');
                    element.remove(element);
                }
            }
            /*var total = document.querySelectorAll('.pRow').length;
            var row = document.grtElementById('order').rows
            for (var i = 1; i <= total; i++) {
                row[i].cells[0].innerHTML = "#" + i;
            }*/
        }, false);
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>