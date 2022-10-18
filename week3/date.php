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

    <div class="text-center m-5">
        <h1>What Is Your Date Of Birth?</h1>
    </div>

    <div class="m-5 d-flex justify-content-center">

        <select class="form-select form-select-lg bg-info mx-4" aria-label=".form-select-lg example">
            <option selected>Day</option>
            <?php
            for ($day = 1; $day <= 31; $day++) {
                echo "<option value=\"$day\">$day</option>";
            }
            ?>
        </select>

        <select class="form-select form-select-lg bg-warning mx-4" aria-label=".form-select-lg example">
            <option selected>Month</option>
            <?php
            for ($month = 1; $month <= 12; $month++) {
                echo "<option value=\"$month\">$month</option>";
            }
            ?>
        </select>

        <select class="form-select form-select-lg bg-danger mx-4" aria-label=".form-select-lg example">
            <option selected>Year</option>
            <?php
            for ($year = 1900; $year <= 2022; $year++) {
                echo "<option value=\"$year\">$year</option>";
            }
            ?>
        </select>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>

</html>