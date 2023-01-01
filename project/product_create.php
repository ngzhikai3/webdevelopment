<?php
include 'check.php';
?>

<!DOCTYPE HTML>
<html>

<head>

    <title>Create Product</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body class="bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <!-- container -->
        <div class="container my-3">
            <div class="page-header text-center">
                <h1>Create Product</h1>
            </div>

            <?php

            $name = $description = $price = $promotion_price = $manufacture_date = $expired_date = "";

            if ($_POST) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promotion_price = $_POST['promotion_price'];
                $manufacture_date = $_POST['manufacture_date'];
                $expired_date = $_POST['expired_date'];
                $image = !empty($_FILES["image"]["name"])
                    ? sha1_file($_FILES['image']['tmp_name']) . "-" . basename($_FILES["image"]["name"])
                    : "";
                $image = (strip_tags($image));
                $error_message = "";

                if ($name == "" || $description == "" || $price == "" || $manufacture_date == "") {
                    $error_message .= "<div class='alert alert-danger'>Please make sure all fields are not empty</div>";
                }

                if ($price != "" && !is_numeric($price)) {
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
                if ($image) {

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
                } elseif (empty($image)) {
                    $image = "product.png";
                }

                if (!empty($error_message)) {
                    echo $error_message;
                } else {
                    // include database connection
                    include 'config/database.php';
                    try {
                        // insert query
                        $query = "INSERT INTO products SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, image=:image, manufacture_date=:manufacture_date, expired_date=:expired_date, created=:created";
                        // prepare query for execution
                        $stmt = $con->prepare($query);
                        // bind the parameters
                        $stmt->bindParam(':name', $name);
                        $stmt->bindParam(':description', $description);
                        $stmt->bindParam(':price', $price);
                        $stmt->bindParam(':promotion_price', $promotion_price);
                        $stmt->bindParam(':image', $image);
                        $stmt->bindParam(':manufacture_date', $manufacture_date);
                        $stmt->bindParam(':expired_date', $expired_date);
                        $created = date('Y-m-d H:i:s'); // get the current date and time
                        $stmt->bindParam(':created', $created);
                        // Execute the query
                        if ($stmt->execute()) {
                            header("Location: product_read.php?update={save}");
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
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
                <table class='table table-hover table-dark table-responsive table-bordered'>
                    <tr>
                        <td class="text-center">Name</td>
                        <td colspan="3"><input type='text' name='name' value='<?php echo $name ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td class="text-center">Description</td>
                        <td colspan="3"><textarea type='text' name='description' class='form-control'><?php echo $description ?></textarea></td>
                    </tr>
                    <tr>
                        <td class="text-center col-3">Price</td>
                        <td><input type='text' name='price' value='<?php echo $price ?>' class='form-control' /></td>
                        <td class="text-center col-3">Promotion price</td>
                        <td><input type='text' name='promotion_price' value='<?php echo $promotion_price ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td class="text-center">Manufacture Date</td>
                        <td><input type='date' name='manufacture_date' value='<?php echo $manufacture_date ?>' class='form-control' /></td>
                        <td class="text-center">Expired Date</td>
                        <td><input type='date' name='expired_date' value='<?php echo $expired_date ?>' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td class="text-center">Photo</td>
                        <td colspan="3"><input type="file" name="image" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-end">
                            <button type='submit' class='btn btn-success'>
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                            <a href='product_read.php' class='btn btn-secondary'>Go to Product List <i class="fa-sharp fa-solid fa-circle-arrow-right"></i></a>
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