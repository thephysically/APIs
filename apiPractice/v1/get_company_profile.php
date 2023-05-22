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
    $id = trim($data->id);

    if (json_last_error() != JSON_ERROR_NONE) {
        $student->constants->customErrorMessage("Not valid JSON Formate");
    } else if (!isset($id)) {
        $student->constants->fieldRequested("id");
    } else if (empty($id)) {
        $student->constants->fieldMustNotEmptyRequested("id");
    } else {
       echo $student->constants->commonDataPrint($student->getCompanyProfile($id));
    }
} else {
    $student->constants->serverInvalidRequest(1);
}