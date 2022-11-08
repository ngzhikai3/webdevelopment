<!DOCTYPE html>
<html>

<head>

    <title>Database</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <?php
    // used to connect to the database
    $host = "localhost";
    $db_name = "eshop";
    $username = "eshop";
    $password = "eshop2250168";
    $mysqli = new mysqli($host, $username, $password,$db_name);

    try {
        $con = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // show error
    }
    // show error
    catch (PDOException $exception) {
        echo "Connection error: " . $exception->getMessage();
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>