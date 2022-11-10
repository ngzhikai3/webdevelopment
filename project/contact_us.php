<?php
include 'check.php';
?>

<!DOCTYPE html>
<html>

<head>

    <title>Contact Us</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="container-fluid px-0">
        
        <?php include 'topnav.html'; ?>

        <div class="container my-5 bg-dark text-white w-50">
            <div>
                <p class="text-center p-3">CONTACT US</p>
            </div>

            <div class="form text-center pb-3">
                <div>
                    <form name="myForm" action="tq.html">

                        NAME :<br>
                        <input type="text" name="Name" class="w-75" required>
                        <br><br>

                        EMAIL :<br>
                        <input type="email" name="Email" class="w-75" required>
                        <br><br>

                        PHONE :<br>
                        <input type="phone" name="Phone" class="w-75" required>
                        <br><br>

                        MESSAGE :<br>
                        <textarea name="Message" class="w-75"></textarea>
                        <br><br>

                        <input type="submit" value="SEND" id="submit">

                    </form>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row d-flex justify-content-between">
                <div class="col bg-warning text-center mx-3">
                    <h1>EMAIL</h1>
                    <a href="mailto:ngzhikai0308@e.newera.edu.my" class="text-black text-decoration-none">ngzhikai0308@e.newera.edu.my</a>
                </div>

                <div class="col bg-warning text-center mx-3">
                    <h1>PHONE</h1>
                    <a href="tel:017-6557328" class="text-black text-decoration-none">017-6557328</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>