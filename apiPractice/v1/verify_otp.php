<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
// header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

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
    $otp = trim($data->otp);

    if (json_last_error() != JSON_ERROR_NONE) {
        $student->constants->customErrorMessage("Not valid JSON Formate");
    } else if (!isset($email)) {
        $student->constants->fieldRequested("email");
    } else if (!isset($otp)) {
        $student->constants->fieldRequested("OTP");
    } else if (empty($email)) {
        $student->constants->fieldMustNotEmptyRequested("email");
    } else if (empty($otp)) {
        $student->constants->fieldMustNotEmptyRequested("otp");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $student->constants->customErrorMessage("please enter a valid email Address");
    } else if (strlen($data->otp) !== 6) {
        $student->constants->customErrorMessage("The length of the OTP must be six.");
    } else {
        $student->emailVerifyOTP($email, $otp);
    }
} else {
    $student->constants->serverInvalidRequest(1);
}