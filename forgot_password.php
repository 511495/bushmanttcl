<?php

include_once('db_config.php');




session_start();
include("db.php");

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_message = "";
$success_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error_message = 'Invalid email address. Please enter a valid email.';
    } else {
        $query = "SELECT * FROM form WHERE email = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $resetToken = bin2hex(random_bytes(16));

            $updateQuery = "UPDATE form SET reset_token = ? WHERE email = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ss", $resetToken, $email);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            if (sendResetEmail($email, $resetToken)) {
                $success_message = 'Instructions for password reset have been sent to your email.';
            } else {
                $error_message = 'Error sending reset email. Please try again later.';
            }
        } else {
            $error_message = 'Email not found. Please enter a valid email address.';
        }

        mysqli_stmt_close($stmt);
    }
}

function sendResetEmail($email, $resetToken) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPDebug  = 1;
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = "Bushmanttcl@gmail.com";
        $mail->Password   = "btrj gjqq erik tupy";

        $mail->setFrom('Bushmanttcl@gmail.com', 'Bushmanttcl.com');
        $mail->addAddress($email);

        $mail->Subject = 'Password Reset';
        $mail->Body = 'Click the following link to reset your password: http://Bushmanttcl.com/reset_password.php?token=' . $resetToken;

        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log('Message could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        return false;
        
    }
    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/styles.css">
    <link rel="stylesheet" href="/styles/user.css">

    <title>Forgot Password - BMT</title>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    

    <div class="container">


            <br>

            <div class="content-box">

            <form class="form" id="forgotPassword" method="post" action="">
            <h1 class="form__title">Forgot Password</h1>
            <div class="form__message form__message--error">
                <?php
                if (!empty($error_message)) {
                    echo $error_message;
                }
                ?>
            </div>
            <div class="form__message form__message--success">
                <?php
                if (!empty($success_message)) {
                    echo $success_message;
                }
                ?>
            </div>
            <div class="form__input-group">
                <input type="text" class="form__input" name="email" autofocus placeholder="Email Address">
                <div class="form__input-error-message"></div>
            </div>
            <button class="form__button" type="submit">Reset Password</button>
            <br>
            <p class="form__text">
                <a href="login.php" class="form__link">Remembered your password? Log in</a>
            </p>
        </form>
        </div>
        <br>
        <br>


    <?php include 'footer.php'; ?>
    <script src="js/user.js"></script>

</body>
</html>
