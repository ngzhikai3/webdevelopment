<?php
include 'check.php';
?>

<!DOCTYPE html>
<html>

<head>

    <title>Contact Us</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/icon.png" />
    <link href="css/button.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

</head>

<body class=" bg-warning">

    <div class="container-fluid px-0">

        <?php include 'topnav.php'; ?>

        <div class="container my-3 mt-4 bg-dark text-white w-50" data-aos="fade-top">
            <div>
                <h3 class="text-center p-1 pt-3">CONTACT US</h3>
            </div>

            <div class="form text-center pb-3">
                <form class="" action="contact_us.php" method="post">
                    Subject :<br>
                    <input type="text" name="subject" value="" class="w-75" required data-aos="fade-right">
                    <br><br>

                    Name :<br>
                    <input type="text" name="name" class="w-75" required data-aos="fade-left">
                    <br><br>

                    Email :<br>
                    <input type="email" name="email" class="w-75" required data-aos="fade-right">
                    <br><br>

                    Phone :<br>
                    <input type="phone" name="phone" class="w-75" required data-aos="fade-left">
                    <br><br>

                    Message :<br>
                    <textarea name="message" class="w-75" data-aos="fade-right"></textarea>
                    <br><br>

                    <button type="submit" class="submitbtn w-100" role="button" name="send"><span class="text">Send</span></button>

                    <?php

                    use PHPMailer\PHPMailer\PHPMailer;
                    use PHPMailer\PHPMailer\Exception;

                    require 'phpmailer/src/Exception.php';
                    require 'phpmailer/src/PHPMailer.php';
                    require 'phpmailer/src/SMTP.php';

                    if (isset($_POST["send"])) {
                        $mail = new PHPMailer(true);

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'webtest30303@gmail.com';
                        $mail->Password = 'nvfwvrvlvdnzdmht';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = '465';

                        $mail->setFrom('webtest30303@gmail.com');

                        $mail->addAddress('webtest30303@gmail.com');

                        $mail->isHTML(true);

                        $mail->Subject = $_POST["subject"];
                        $mail->Body = $_POST["name"] . '<br>' . $_POST["email"] . '<br>' . $_POST["phone"] . '<br>' . $_POST['message'];

                        $mail->send();

                        echo
                        "<script>
                                alert('Send Sucessfully');
                                document.location.herf = 'contact_us.php'
                            </script>";
                    }
                    ?>
            </div>

            <div class="text-center pb-3">
                <!-- Facebook -->
                <a class="btn btn-outline-light btn-floating m-1" href="https://www.facebook.com/" role="button"><i class="fab fa-facebook-f"></i></a>
                <!-- Instagram -->
                <a class="btn btn-outline-light btn-floating m-1" href="https://www.instagram.com/kingjames" role="button"><i class="fab fa-instagram"></i></a>
                <!-- Github -->
                <a class="btn btn-outline-light btn-floating m-1" href="https://github.com/ngzhikai3" role="button"><i class="fab fa-github"></i></a>
                <!-- Mail -->
                <a class="btn btn-outline-light btn-floating m-1" href="mailto:ngzhikai0308@e.newera.edu.my" role="button"><i class="fa-solid fa-envelope"></i></a>
                <!-- Tel -->
                <a class="btn btn-outline-light btn-floating m-1" href="tel:017-6557328" role="button"><i class="fa-solid fa-phone"></i></a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    
</body>

</html>