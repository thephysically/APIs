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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $id = trim($data->user_id);
    $company_name = trim($data->company_name);
    $email_address = trim($data->email_address);
    $mobile_number = trim($data->mobile_number);
    $pan_number = trim($data->pan_number);
    $gst_number = trim($data->gst_number);


    if (json_last_error() != JSON_ERROR_NONE) {
        $student->constants->customErrorMessage("Not valid JSON Formate");
    } else if (!isset($id)) {
        $student->constants->fieldRequested("user id");
    } else if (empty($id)) {
        $student->constants->fieldMustNotEmptyRequested("user id");
    } else if (!isset($company_name)) {
        $student->constants->fieldRequested("company name");
    } else if (empty($company_name)) {
        $student->constants->fieldMustNotEmptyRequested("company name");
    } else if (!isset($email_address)) {
        $student->constants->fieldRequested("email address");
    } else if (empty($email_address)) {
        $student->constants->fieldMustNotEmptyRequested("email address");
    } else if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
        $student->constants->customErrorMessage("please enter a valid email Address");
    } else if (!isset($mobile_number)) {
        $student->constants->fieldRequested("mobile number");
    } else if (empty($mobile_number)) {
        $student->constants->fieldMustNotEmptyRequested("mobile number");
    } else if (!$student->constants->validatePhoneNumber($mobile_number)) {
        $student->constants->customErrorMessage("please enter a valid mobile number");
        // }  else if (!isset($pan_number)) {
        //     $student->constants->fieldRequested("pan number");
        // } else if (empty($pan_number)) {
        //     $student->constants->fieldMustNotEmptyRequested("pan number");
        // } else if (!$student->constants->validatePanNumber($pan_number)) {
        //     $student->constants->customErrorMessage("please enter a valid pan number");
        // } else if (!isset($gst_number)) {
        //     $student->constants->fieldRequested("GST number");
        // } else if (empty($gst_number)) {
        //     $student->constants->fieldMustNotEmptyRequested("GST number");
        // } else if (!$student->constants->validatePanNumber($gst_number)) {
        //     $student->constants->customErrorMessage("please enter a valid GST number");
    } else {
        if (empty(trim($data->image_url))) {
            $student->setCompanyProfile($data);
        } else {
            $student->setCompanyProfileWithImageUrl($data);
        }
    }
} else {
    $student->constants->serverInvalidRequest(1);
}
