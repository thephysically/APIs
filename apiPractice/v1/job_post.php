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
    $user_id = trim($data->user_id);
    $company_id = trim($data->company_id);
    $company_name = trim($data->company_name);
    $gst_number = trim($data->gst_number);
    $contact_detail_name = trim($data->contact_detail_name);
    $contact_detail_mobile_number = trim($data->contact_detail_mobile_number);
    $location_pin_code = trim($data->location_pin_code);
    $location_country = trim($data->location_country);
    $location_state = trim($data->location_state);
    $location_district = trim($data->location_district);
    $location_address_line_1 = trim($data->location_address_line_1);
    $location_address_line_2 = trim($data->location_address_line_2);
    $staff_ca_fresher = trim($data->staff_ca_fresher);
    $staff_ca_experience = trim($data->staff_ca_experience);
    $staff_graduate_fresher = trim($data->staff_graduate_fresher);
    $staff_graduate_experience = trim($data->staff_graduate_experience);
    $start_date = trim($data->start_date);
    $end_date = trim($data->end_date);
    $total_days = trim($data->total_days);
    $is_laptop_required = trim($data->is_laptop_required);
    $is_reimbursement_required = trim($data->is_reimbursement_required);
    $reimbursement_amount = trim($data->reimbursement_amount);
    $any_comment = trim($data->any_comment);
    $coupon_code = trim($data->coupon_code);
    $discount_amount = trim($data->discount_amount);
    $profession_fees = trim($data->profession_fees);
    $convenience_fee = trim($data->convenience_fee);
    $platform_fee = trim($data->platform_fee);
    $tax_percentage = trim($data->tax_percentage);
    $tax_amount = trim($data->tax_amount);
    $total = trim($data->total);
    $status = trim($data->status);
    $date_modify = trim($data->date_modify);


    $fields = array(
        $user_id,
        $company_id,
        $company_name,
        $gst_number,
        $contact_detail_name,
        $contact_detail_mobile_number,
        $location_pin_code,
        $location_country,
        $location_state,
        $location_district,
        $location_address_line_1,
        $start_date,
        $end_date,
        $total_days,
        $profession_fees,
        $convenience_fee,
        $platform_fee,
        $tax_percentage,
        $tax_amount,
        $total,
        $status,
        $date_modify
    );

    $fields_key = array(
        "user id",
        "company id",
        "company name",
        "gst number",
        "contact detail name",
        "contact detail mobile number",
        "location pin code",
        "location country",
        "location state",
        "location district",
        "location address line_1",
        "start date",
        "end date",
        "total days",
        "profession fees",
        "convenience fee",
        "platform fee",
        "tax percentage",
        "tax amount",
        "total",
        "status",
        "date modify"
    );

    if (json_last_error() != JSON_ERROR_NONE) {
        $student->constants->customErrorMessage("Not valid JSON Formate");
    } else if ($student->constants->checkFieldsNotEmpty($fields, $fields_key)) {
        if (!$student->constants->validateGSTNumber($gst_number)) {
            $student->constants->customErrorMessage($student->constants->msgInvalidGSTNumber);
        } else if (!$student->constants->validatePhoneNumber($contact_detail_mobile_number)) {
            $student->constants->customErrorMessage($student->constants->msgInvalidMobileNumber);
        } else if (!$student->constants->validatePinCode($location_pin_code)) {
            $student->constants->customErrorMessage($student->constants->msgInvalidPinCode);
        } else if (!$student->constants->checkFieldsGreaterThanZero(array($staff_ca_fresher, $staff_ca_experience, $staff_graduate_fresher, $staff_graduate_experience))) {
            $student->constants->customErrorMessage($student->constants->msgInvalidStaff);
        } else if (!$student->constants->isValidateDateFormate($start_date) || !$student->constants->isValidateDateFormate($end_date)) {
            $student->constants->customErrorMessage($student->constants->msgInvalidDateFormat);
        } else if (!$student->constants->validateDate(date($student->constants->dateFormat), $start_date, $end_date)) {
            $student->constants->customErrorMessage($student->constants->msgDateoutOfRange);
        } else if (boolval($is_reimbursement_required) == true) {
            if (empty($reimbursement_amount)) {
                $student->constants->fieldRequested("Reimbursement Amount");
            }
        } else if (!empty($coupon_code)) {
            if (empty($discount_amount)) {
                $student->constants->fieldRequested("Discount Amount");
            }
        } else {
            $student->setJobPost($data);
        }
    }
} else {
    $student->constants->serverInvalidRequest(1);
}
