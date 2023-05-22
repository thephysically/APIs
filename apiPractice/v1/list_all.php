<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-type: application/json; charset=UTF-8");


//include database 
include_once("../config/Database_student.php");
//include student.php
include_once("../classes/student.php");
include_once("../v1/constant.php");


//create object for database 
$db = Database::getInstance();
$connection = $db->getConnection();

//create student class
$student = new Student($connection);
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student->fecth_all_data();
} else {
    $student->constants->serverInvalidRequest(0);
}
