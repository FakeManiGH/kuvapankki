<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require 'vendor/autoload.php';
require "settings.php";

//Load email settings

// function to send registration verification email
function send_verification_email($username, $email, $email_token) {
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

    try {

        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                   //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $email_host;             //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $email_username;                       //SMTP username
        $mail->Password   = $email_password;                       //SMTP password
        $mail->SMTPSecure = 'PHPMailer::ENCRYPTION_SMTPS';          //Enable implicit TLS encryption
        $mail->CharSet    = $charset;                                // Set email charset        
        $mail->Port       = 587;     
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );                       

        //Recipients
        $mail->setFrom($SERVICEMAIL, 'Kuvapankki.fi');
        $mail->addAddress($email, $username);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Vahvista säköpostiosoitteesi Kuvapankki.fi -palvelussa';
        $email_template = "
            <h3>Kiitos rekisteröitymisestäsi Kuvapankki.fi -palveluun, $username!</h3>
            <p>Vahvista sähköpostiosoitteesi alla olevasta linkistä:</p>
            <br><br>
            <a href='http://$SERVER/$ROOT/vahvistukset.php?email_token=$email_token'>Vahvista sähköpostiosoitteesi</a><br><br>
        ";
        $mail->Body = $email_template;
        
        $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}