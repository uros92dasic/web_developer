<?php

namespace App;

use App\Config;
//use Mailgun\Mailgun;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require "../vendor/autoload.php";

/**
 * Mail
 *
 * PHP version 7.0
 */
class Mail
{
    /**
     * Send a message
     *
     * @param string $to/$recipient Recipient
     * @param string $subject Subject
     * @param string $text Text-only content of the message
     * @param string $html HTML content of the message
     *
     * @return mixed
     */
    public static function send($recipient, $subject, $text, $html)
    {
        /*
        $mg = new Mailgun(Config::MAILGUN_API_KEY);
        $domain = Config::MAILGUN_DOMAIN;

        $mg->sendMessage($domain, ['from'      => 'your-sender@your-domain.com',
                                   'to'      => $to,
                                   'subject' => $subject,
                                   'text'    => $text,
                                   'html'    => $html]);
        */

        $mail = new PHPMailer(true);
        //$mail->SMTPDebug  = SMTP::DEBUG_SERVER;  

        $mail->IsSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Host       = "smtp.mailtrap.io";
        $mail->Port       = 2525;
        $mail->Username   = "5fd508c05375df";
        $mail->Password   = "de9031e063253d";

        $mail->SetFrom("mailtrap@io.com", "My website");
        $mail->AddAddress($recipient, "esteemed customer");

        $mail->Subject = $subject;
        $mail->Body = $text;
        $mail->Body = $html;

        $mail->IsHTML(true);
        $mail->MsgHTML($html); 

        $mail->send();
    }
}
