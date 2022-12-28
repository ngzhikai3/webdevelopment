<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $select = "SELECT id AS check_product, image FROM products WHERE id=:id";
    $stmt = $con->prepare($select);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $check = "SELECT product_id FROM order_details WHERE product_id=:product_id";
    $stmt = $con->prepare($check);
    $stmt->bindParam(":product_id", $check_product);
    $stmt->execute();
    $count = $stmt->rowCount();

    if ($count > 0) {
        header('Location: product_read.php?action=nodeleted');
        //echo "This product has been ordered so cannot be delete.";
    } else {
        // delete query
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);


        if ($stmt->execute()) {
            // redirect to read records page and
            // tell the user record was deleted
            unlink("uploads/" . $row['image']);
            header('Location: product_read.php?action=deleted');
        } else {
            die('Unable to delete record.');
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
