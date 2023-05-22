<?php

//include database 
include_once("../config/Database_student.php");
//include student.php
include_once("../classes/student.php");
include_once("../v1/constant.php");
include_once("../v1/mail.php");


//create object for database 
$db = Database::getInstance();
$connection = $db->getConnection();

//create student class
$student = Student::getInstance($connection);
$mail = Mail::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $email = trim($data->email);

    if (json_last_error() != JSON_ERROR_NONE) {
        $student->constants->customErrorMessage("Not valid JSON Formate");
    } else if (!isset($email)) {
        $student->constants->fieldRequested("email");
    } else if (empty($email)) {
        $student->constants->fieldMustNotEmptyRequested("email");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $student->constants->customErrorMessage("please enter a valid email Address");
    } else {
        $mail->prepare_sent_mail($data,"OTP Verification");
    }
} else {
    $student->constants->serverInvalidRequest(1);
}