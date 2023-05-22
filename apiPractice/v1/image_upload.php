<?php
// make sure API folder folder have access for create folder

//include database 
include_once("../config/Database_student.php");
//include student.php
include_once("../classes/student.php");
include_once("../v1/constant.php");
include_once("../v1/mail.php");

// show the error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$constants = Constant::getInstance();

//create object for database 
$db = Database::getInstance();
$connection = $db->getConnection();

//create student class
$student = Student::getInstance($connection);

$domain_name = $_SERVER['HTTP_HOST'];

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        echo $constants->commonServerErrorPrint("File is not an image.");
        exit;
    }
}
// Check if image file is an actual image or fake image
$id = $_POST["id"];
if(!isset($id)) {
    $student->constants->commonBadRequestDataPrint("id");
    exit;
}

$filename = "$id" . "_image." . strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

// Sanitize filename
$filename = preg_replace("/[^a-zA-Z0-9\._-]/", "", $filename);

$folder_name = "uploads";
$target_dir = __DIR__ . "/$folder_name/";
$target_file = $target_dir . $filename;

// Check if file already exists
if (file_exists($target_file)) {
    unlink($target_file);
}

// Check file size
if ($_FILES["image"]["size"] > 500000) {
    echo $constants->commonBadRequestDataPrint("Sorry, your file is too large.");
    exit;
}

// Allow certain file formats
$allowed_extensions = array("jpg", "jpeg", "png", "gif");
if(!in_array(strtolower(pathinfo($target_file, PATHINFO_EXTENSION)), $allowed_extensions)) {
    echo $constants->commonBadRequestDataPrint("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    exit;
}


// Move uploaded file to destination
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    // make it readable
    // header('Content-Type: ' . mime_content_type($target_file));
    // readfile($target_file);
    $image_url = "http://$domain_name/apiPractice/v1/$folder_name/" . basename($target_file);
    $datas = array(
        'id' => $id,
        'user_id' => $id,
        'image_url' => $image_url
    );
    $student->setUploadImages(json_decode(json_encode($datas)));
    // echo $constants->commonDataPrint("$image_url");
} else {
    //commonServerErrorPrint
    echo $constants->commonDataPrint("Sorry, there was an error uploading your file.");
}