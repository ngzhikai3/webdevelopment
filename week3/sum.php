<!DOCTYPE html>
<html>

<head>

    <title>Exercise 3</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="container m-3 row">

    <?php
        $sum=0 ;

        for ($num = 1; $num <= 100; $num++) { if($num % 2==0) { echo "<strong>" . "<p class=\"d-flex flex-column text-center\">$num</p>" . "</strong>";
            }
            else
            {
            echo "<p class=\"d-flex flex-column text-center\">$num</p>";
            }
            $sum=$sum+$num ;
            }
            echo "<strong><p class=\"d-flex flex-column text-center\">$sum</p></strong>";

            ?>
            
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>