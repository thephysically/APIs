<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require '../v1/mail/src/PHPMailer.php';
require '../v1/mail/src/SMTP.php';

//include database 
include_once("../config/Database_student.php");
//include student.php
include_once("../classes/student.php");


class Mail
{

    private static $instance;
    private $constants;
    
    
    private function __construct() {
        $this->constants = Constant::getInstance();
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
          self::$instance = new Mail();
        }
        return self::$instance;
      }


    function prepare_sent_mail($data,$subject){
        //create object for database 
        $db = Database::getInstance();
        $connection = $db->getConnection();
        //create student class
        $student = Student::getInstance($connection);
        // Generate a random 6-digit OTP
        $otp = rand(100000, 999999);

        // Email parameters
        $to_email = $data->email;
        $from_email = 'thephysically@gmail.com';
        $from_name = 'Physical Solution';

        // SMTP configuration
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'thephysically@gmail.com';
        $mail->Password = 'eosrqemxgjwmfbsv';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email content
        $body = getBody($otp);
        // $body = "Your OTP for verification is: $otp";

        // Send the email
        try {
            $mail->setFrom($from_email, $from_name);
            $mail->addAddress($to_email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            // echo 'OTP email sent!';
            $student->enter_email_and_otp(trim($data->email), $otp);
        } catch (Exception $e) {
            echo $this->constants->commonServerErrorPrint('Error sending email: ' . $mail->ErrorInfo . $e->getMessage());
            
        }
    }
   
}

function getBody($otp){
    return "<!DOCTYPE html>
    <html>
    
    <head>
        <title>Glass Card with Logo and OTP</title>
        <style>
            body {
                background-color: #386586;
                /* set background color */
            }
            .card {
                margin: 50px auto;
                max-width: 400px;
                padding: 30px;
                text-align: center;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 16px;
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(5px);
                -webkit-backdrop-filter: blur(5px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
    
            .logo {
                display: block;
                margin: 0 auto 30px;
                width: 100px;
            }
    
            .otp {
                font-size: 3em;
                font-weight: bold;
                border-radius: 10px;
                border: 2px solid #f2f2f2;
                background-color: rgba(255, 255, 255, 0.5);
                color: #000000;
                padding: 10px;
                margin: 50px 0;
            }
        </style>
    </head>
    
    <body>
        <div class=\"card\">
            <img src=\"https://raw.githubusercontent.com/thephysically/images/main/appIcon_small_size.png\" alt=\"Logo\" class=\"logo\">
            <p>
            <h1>Physical Solution</h1>
            <h2>Your OTP for verification is</h2>
            </p>
            <div class=\"otp\">" . $otp . "</div>
        </div>
    </body>
    </html>";
}