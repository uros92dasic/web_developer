<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'vendor/autoload.php';
/*
//send email containing link
function sendVerificationEmail($recipient, $token){
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 2525;
    $mail->Host       = "smtp.mailtrap.io";
    $mail->Username   = "5fd508c05375df";
    $mail->Password   = "de9031e063253d";

    $mail->IsHTML(true);
    $mail->AddAddress($recipient, "esteemed customer");
    $mail->SetFrom("mailtrap@io.com", "My website");
    //$mail->AddReplyTo("reply-to-email", "reply-to-name");
    //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
    $mail->Subject = "Email verification";

    $content = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify email</title>
    </head>
    <body>
        <div class="wrapper">
            <p>
                Thank you for signing up on our website.<br>
                Please click on the link below to verify your email:
            </p>
            <a href="http://localhost/user_verification/index.php?token=' . $token . '">
                Verify your email address
            </a>
        </div>
    </body>
    </html>';

    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
        //echo "Error while sending Email.";
        //echo "<script>alert('Error while sending Email.')</script>";
        $_SESSION['message'] = "Error while sending Email.";
        $_SESSION['alert-class']= "alert-danger";
        return false;
    }
    else {
        //echo "Email sent successfully.";
        //echo "<script>alert('Email sent successfully.')</script>";
        $_SESSION['message'] = "Email sent successfully.";
        $_SESSION['alert-class']= "alert-success";
        return true;
    }
}
*/

//send email containing code
function send_mail($recipient,$subject,$message){

    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 2525;
    $mail->Host       = "smtp.mailtrap.io";
    $mail->Username   = "5fd508c05375df";
    $mail->Password   = "de9031e063253d";


    $mail->IsHTML(true);
    $mail->AddAddress($recipient, "esteemed customer");
    $mail->SetFrom("mailtrap@io.com", "My website");
    $mail->Subject = $subject;
    $content = $message;

    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
        $_SESSION['message'] = "Error while sending Email.";
        $_SESSION['alert-class']= "alert-danger";
        return false;
    }
    else {
        $_SESSION['message'] = "Email sent successfully.";
        $_SESSION['alert-class']= "alert-success";
        return true;
    }

}

function sendPasswordResetLink($recipient, $token){
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPDebug  = 0;  
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 2525;
    $mail->Host       = "smtp.mailtrap.io";
    $mail->Username   = "5fd508c05375df";
    $mail->Password   = "de9031e063253d";

    $mail->IsHTML(true);
    $mail->AddAddress($recipient, "esteemed customer");
    $mail->SetFrom("mailtrap@io.com", "My website");
    $mail->Subject = "Reset your password";

    $content = '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify email</title>
    </head>
    <body>
        <div class="wrapper">
            <p>
                Hello there, <br>
                <br>
                Please click on the link below to reset your password:
            </p>
            <a href="http://localhost/webdeveloper/user_verification/index.php?password_token=' . $token . '">
                Reset your password
            </a>
        </div>
    </body>
    </html>';
    //Change the link of the Reset your password "a" tag if the path is not the same on your PC
    $mail->MsgHTML($content); 
    if(!$mail->Send()) {
        $_SESSION['message'] = "Error while sending Email.";
        $_SESSION['alert-class']= "alert-danger";
        return false;
    }
    else {
        $_SESSION['message'] = "Email sent successfully.";
        $_SESSION['alert-class']= "alert-success";
        return true;
    }
}
?>