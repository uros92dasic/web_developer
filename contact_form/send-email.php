<?php

$email = $_POST["email"];
$subject = $_POST["subject"];
$content = $_POST["content"];
$headers = "From: ".$_POST['email'];

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

//$mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

$mail->setFrom($email, $headers);
$mail->addAddress("dave@example.com", "Dave");

$mail->Host       = "smtp.mailtrap.io";
$mail->Port       = 2525;
$mail->Username   = "5fd508c05375df";
$mail->Password   = "de9031e063253d";

$mail->Subject = $subject;
$mail->Body = $content;

$mail->send();

header("Location: sent.html");