<?php
include 'check.php';
?>

<!DOCTYPE html>

<html>

<head>

    <title>Update Product</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.html'; ?>

        <div class="container">
            <div class="page-header">
                <h1>Update Product</h1>
            </div>
            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

            // read current record's data
            try {
                // prepare select query
                $query = "SELECT * FROM products WHERE id = ? LIMIT 0,1";
                $stmt = $con->prepare($query);

                // this is the first question mark
                $stmt->bindParam(1, $id);

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
            }

            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
            ?>

            <?php
            // check if form was submitted
            if ($_POST) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                $image = !empty($_FILES["image"]["name"])
                    ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                    : htmlspecialchars($image, ENT_QUOTES);
                $error_message = "";

                if ($price == "") {
                    $error_message .= "<div class='alert alert-danger'>Please make sure price are not empty</div>";
                } elseif (!is_numeric($price)) {
                    $error_message .= "<div class='alert alert-danger'>Please make sure price only have number</div>";
                }

                if ($promotion_price == "") {
                    $promotion_price = NULL;
                } elseif (!is_numeric($promotion_price)) {
                    $error_message .= "<div class='alert alert-danger'>Please make sure promotion price only have number</div>";
                } elseif ($promotion_price > $price) {
                    $error_message .= "<div class='alert alert-danger'>Please make sure promotion price is not more than normal price</div>";
                }

                if ($expired_date == "") {
                    $expired_date = NULL;
                } else {
                    $date1 = date_create($manufacture_date);
                    $date2 = date_create($expired_date);
                    $diff = date_diff($date1, $date2);
                    if ($diff->format("%R%a") < "0") {
                        $error_message .= "<div class='alert alert-danger'>Expired date must be after the manufacture date</div>";
                    }
                }

                // now, if image is not empty, try to upload the image
                if ($_FILES["image"]["name"]) {

                    // upload to file to folder
                    $target_directory = "uploads/";
                    $target_file = $target_directory . $image;
                    $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                    // make sure that file is a real image
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check === false) {
                        $error_message .= "<div class='alert alert-danger'>Submitted file is not an image.</div>";
                    }
                    // make sure certain file types are allowed
                    $allowed_file_types = array("jpg", "jpeg", "png", "gif");
                    if (!in_array($file_type, $allowed_file_types)) {
                        $error_message .= "<div class='alert alert-danger'>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                    }
                    // make sure file does not exist
                    if (file_exists($target_file)) {
                        $error_message .= "<div class='alert alert-danger'>Image already exists. Try to change file name.</div>";
                    }
                    // make sure submitted file is not too large, can't be larger than 1 MB
                    if ($_FILES['image']['size'] > (1024000)) {
                        $error_message .= "<div class='alert alert-danger'>Image must be less than 1 MB in size.</div>";
                    }
                    // make sure the 'uploads' folder exists
                    // if not, create it
                    if (!is_dir($target_directory)) {
                        mkdir($target_directory, 0777, true);
                    }
                    // if $file_upload_error_messages is still empty
                    if (empty($error_message)) {
                        // it means there are no errors, so try to upload the file
                        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $error_message .= "<div class='alert alert-danger>Unable to upload photo.</div>";
                            $error_message .= "<div class='alert alert-danger>Update the record to upload photo.</div>";
                        }
                    }
                }

                if (!empty($error_message)) {
                    echo "<div class='alert alert-danger'>{$error_message}</div>";
                } else {

                    try {
                        // write update query
                        // in this case, it seemed like we have so many fields to pass and
                        // it is better to label them and not use question marks
                        $query = "UPDATE products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, image=:image, manufacture_date=:manufacture_date, expired_date=:expired_date WHERE id = :id";
                        // prepare query for excecution
                        $stmt = $con->prepare($query);
                        // posted values
                        $name = htmlspecialchars(strip_tags($_POST['name']));
                        $description = htmlspecialchars(strip_tags($_POST['description']));
                        $price = htmlspecialchars(strip_tags($_POST['price']));
                        $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
                        $image = htmlspecialchars(strip_tags($image));
                        $manufacture_date = htmlspecialchars(strip_tags($_POST['manufacture_date']));
                        $expired_date = htmlspecialchars(strip_tags($_POST['expired_date']));
                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $stmt->bindParam(':id', $id);
                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: product_read.php?update={$id}");
                        } else {
                            echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                        }
                    }
                    // show errors
                    catch (PDOException $exception) {
                        die('ERROR: ' . $exception->getMessage());
                    }
                }
            }
            ?>

            <!--we have our html form here where new record information can be updated-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-dark table-responsive table-bordered'>
                    <tr>
                        <td>Name</td>
                        <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Promotion Price</td>
                        <td><input type='text' name='promotion_price' value="<?php echo htmlspecialchars($promotion_price, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td>
                            <div><img src="uploads/<?php echo htmlspecialchars($image, ENT_QUOTES);  ?>" class="w-25 mb-2"></div>
                            <div><input type="file" name="image" /></div>
                        </td>
                    </tr>
                    <tr>
                        <td>Manufacture Date</td>
                        <td><input type='date' name='manufacture_date' value="<?php echo htmlspecialchars($manufacture_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Expired Date</td>
                        <td><input type='date' name='expired_date' value="<?php echo htmlspecialchars($expired_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save Changes' class='btn btn-primary' />
                            <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                        </td>
                    </tr>
                </table>
            </form>

        </div>
        <!-- end .container -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>