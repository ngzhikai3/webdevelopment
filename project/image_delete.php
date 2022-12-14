<?php
// include database connection
include 'config/database.php';

if ($image = $_GET['item']) {
unlink("uploads/$image");
header("Location: product_read.php");
}
?>