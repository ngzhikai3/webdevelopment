<!DOCTYPE html>
<html>

<head>

    <title>Date</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

</head>

<body>

    <div class="text-center m-5">
        <h1>What Is Your Date Of Birth?</h1>
    </div>

    <div class="m-5 d-flex justify-content-center">

        <select class="form-select form-select-lg bg-info mx-4" aria-label=".form-select-lg example">
        <option selected>Day</option>
            <?php

            for ($day = 1; $day <= 31; $day++) {

                if ($day == date("j")) {
                    echo "<option value=$day selected>$day</option>";
                } else {
                    echo "<option value=$day>$day</option>";
                }
            }
            ?>
        </select>

        <select class="form-select form-select-lg bg-warning mx-4" aria-label=".form-select-lg example">
        <option selected>Month</option>
            <?php
            $months = array( "Month", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $curmon = date("m");

            for ($month = 1; $month <= 12; $month++) {
                $state = "";
                if ($curmon == $month) {
                    $state = "selected";
                }
                echo "<option value=$month $state>$months[$month]</option>";
            }
            ?>
        </select>

        <select class="form-select form-select-lg bg-danger mx-4" aria-label=".form-select-lg example">
        <option selected>Year</option>
            <?php
            $curyear = date("Y");

            for ($year = 1900; $year <= $curyear; $year++) {
                $state = "";
                if ($curyear == $year) {
                    $state = "selected";
                }
                echo "<option value=$year $state>$year</option>";
            }
            ?>
        </select>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>