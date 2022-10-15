<!DOCTYPE html>
<html>

<head>

    <title>Homework 1</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="m-5 d-flex justify-content-center">

        <div class="btn-group mx-4">
            <button class="btn btn-info btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Day
            </button>
            <ul class="dropdown-menu text-center">
                <?php
                for ($day = 1; $day <= 31; $day++) {
                    echo $day . "<br>";
                }
                ?>
            </ul>
        </div>

        <div class="btn-group mx-5">
            <button class="btn btn-warning btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Month
            </button>
            <ul class="dropdown-menu text-center">
                <?php
                $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

                echo $month[0] . "<br>";
                echo $month[1] . "<br>";
                echo $month[2] . "<br>";
                echo $month[3] . "<br>";
                echo $month[4] . "<br>";
                echo $month[5] . "<br>";
                echo $month[6] . "<br>";
                echo $month[7] . "<br>";
                echo $month[8] . "<br>";
                echo $month[9] . "<br>";
                echo $month[10] . "<br>";
                echo $month[11] . "<br>";

                ?>
            </ul>
        </div>

        <div class="btn-group mx-4">
            <button class="btn btn-danger btn-lg dropdown-toggle text-black" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Year
            </button>
            <ul class="dropdown-menu text-center">
                <?php
                for ($year = 1900; $year <= 2022; $year++) {
                    echo $year . "<br>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>