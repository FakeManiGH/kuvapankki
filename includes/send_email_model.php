<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


//Load Composer's autoloader
require 'vendor/autoload.php';

// function to send registration verification email
function send_verification_email($username, $email, $email_token) {
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

    try {
        require "settings.php";

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
            <p>Vahvista vielä sähköpostiosoitteesi alla olevasta linkistä:</p>
            <a href='http://$SERVER/$ROOT/includes/email_verification.php?email_token=$email_token'>Vahvista sähköpostiosoitteesi</a><br><br>
            <p><strong>ÄLÄ VASTAA TÄHÄN VIESTIIN</strong></p>
        ";
        $mail->Body = $email_template;
        
        $mail->send();

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}



// function to send password reset email
function send_reset_email($username, $email, $pwd_token) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
        try {
            require "settings.php";

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
            $mail->Subject = 'Salasanan nollaus Kuvapankki.fi -palvelussa';
            $email_template = "
                <h3>Hei, $username!</h3>
                <p>Olet pyytänyt salasanasi nollaamista <strong>Kuvapankki.fi</strong> -pavelussa.</p> 
                <p>Jos et ole lähettänyt tätä pyyntöä itse, tämä viesti ei vaadi sinulta toimenpiteitä ja salasanasi pysyy ennallaan.</p>
                <p>Jos kuitenkin olet pyytänyt salasanasi nollaamista, voit tehdä sen alla olevasta linkistä:</p>
                <a href='http://$SERVER/$ROOT/uusi_salasana.php?pwd_token=$pwd_token'><strong>Nollaa Salasana</strong></a><br><br>
                <p><strong>ÄLÄ VASTAA TÄHÄN VIESTIIN</strong></p>
            ";
            $mail->Body = $email_template;
            
            $mail->send();
    
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }