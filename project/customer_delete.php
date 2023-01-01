<?php
session_start();
// include database connection
include 'config/database.php';

try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] :  die('ERROR: Record ID not found.');

    $select = "SELECT *, COUNT(*) AS order_count FROM customers INNER JOIN order_summary ON customers.username = order_summary.username WHERE customers.user_id=:user_id";
    $stmt = $con->prepare($select);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $order_count = $row['order_count'];
    $user_type = $row['user_type'];

    if ($_SESSION["user_type"] == "user") {
        header('Location: customer_read.php?action=user');
    } else {
        if ($order_count > 0) {
            header('Location: customer_read.php?action=nodeleted');
        } else {
            // delete query
            $query = "DELETE FROM customers WHERE user_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $user_id);

            if ($stmt->execute()) {
                // redirect to read records page and
                // tell the user record was deleted
                // rest of the code to delete the record goes here...

                if ($row['cus_image'] != 'user.png') {
                    unlink("cus_uploads/" . $row['cus_image']);
                }
                //unlink("cus_uploads/" . $row['cus_image']);
                header('Location: customer_read.php?action=deleted');
            } else {
                die('Unable to delete record.');
            }
        }
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
