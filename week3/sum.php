<!DOCTYPE html>
<html>

<head>

    <title>Exercise 3</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="d-flex flex-wrap">

        <?php
        $sum = 0;
        $plus = "+";

        for ($num = 1; $num <= 100; $num++) {
            if ($num == 100) {
                $plus = "";
            }
            if ($num % 2 == 0) {
                echo "<p class=\"px-1 m-0\"><strong>$num</strong></p>" . $plus;
            } else {
                echo "<p class=\"px-1 m-0\">$num</p>" . $plus;
            }
            $sum = $sum + $num;
        }
        echo "=" . "<strong><p class=\"px-1\">$sum</p></strong>";

        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>