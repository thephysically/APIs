<?php
class Constant
{
    public $msgSuccessCreate = "data successfully created";
    public $msgSuccessUpdate = "data successfully updated";
    public $msgSuccessDelete = "data successfully deleted";
    public $msgFailedDelete = "Failed to delete data";
    public $msgFailedUpdate = "Failed to update data";
    public $msgFailedCreate = "Failed to insert data";
    public $msgServerPOST = "Invalid POST Request";
    public $msgServerGET = "Invalid GET Request";
    public $msgServerPATCH = "Invalid PATCH Request";
    public $msgServerDELETE = "Invalid DELETE Request";
    public $msgServerError = "500 server error";
    public $msgRequiredField = "required field";
    
    public $msgNotValidJson = "required field";

    // email validation message
    public $msgMustNotEmptyField = "should not empty or zero";
    
    //project common message
    public $msgInvalidGSTNumber = "Invalid GST number";
    public $msgInvalidEmailAddress = "Invalid Email Address";
    public $msgInvalidMobileNumber = "Invalid Mobile Number";
    public $msgInvalidPinCode = "Invalid Pincode Number";
    public $msgInvalidStaff = "You must to add a Staff";
    public $msgInvalidDateFormat = "invalid date format";
    public $msgDateoutOfRange = "invalid date range";

    //Date formate
    public $dateFormat = "d-m-Y";
    public $serverDateFormat = "d-m-Y H:i:s";


    private static $instance;


    public static function getInstance() {
        if (!isset(self::$instance)) {
          self::$instance = new Constant();
        }
        return self::$instance;
      }

    public function serverError()
    {
        // set response code - 500 server error
        http_response_code(500);
        echo $this->errorCommon(500, $this->msgServerError);
    }

    public function checkEmptyFields($arrayOfFields)
    {
        $isEmptyField = false;

        // for ($x = 0; $x <= $arrayOfFields; $x++) {
        //     echo $arrayOfFields[$x];
        // }
        print_r($arrayOfFields);
        /* foreach ($arrayOfFields as $item => $values) {
            if (empty($values)) {
                $isEmptyField = true;
                $this -> fieldRequested($values);
                break;
            }
        } */
        return $isEmptyField;
    }

    public function fieldRequested($FeildName)
    {
        // set response code - 200 response 
        http_response_code(400);
        echo $this->errorCommon(400, $FeildName . " " . $this->msgRequiredField);
    }
    public function fieldMustNotEmptyRequested($FeildName)
    {
        // set response code - 200 response 
        http_response_code(400);
        echo $this->errorCommon(400, $FeildName . " " . $this->msgMustNotEmptyField);
    }

    public function customErrorMessage($message)
    {
        // set response code - 200 response 
        http_response_code(400);
        echo $this->errorCommon(400, $message);
    }

    public function serverInvalidRequest($requestMessageCode)
    {
        // set response code - 503 invalid server request
        http_response_code(503);
        switch ($requestMessageCode) {
            case 0:
                echo $this->errorCommon(503, $this->msgServerGET);
                break;
            case 1:
                echo $this->errorCommon(503, $this->msgServerPOST);
                break;
            case 2:
                echo $this->errorCommon(503, $this->msgServerPATCH);
                break;
            case 3:
                echo $this->errorCommon(503, $this->msgServerDELETE);
                break;
        }
    }

    public function errorCommon($code, $message, $isSuccess = false)
    {
        return json_encode(
            array(
                "code" => $code,
                "status" => "failed",
                "message" => $message
            )
        );
    }

    public function validatePhoneNumber($mobileNumber, $isInterNational = false)
    {
        // Allow +, - and . in phone number
        $filtered_phone_number = filter_var($mobileNumber, FILTER_SANITIZE_NUMBER_INT);
        // Remove "-" from number
        $phone_to_check = str_replace("-", "", $filtered_phone_number);
        $phone_to_check = str_replace(".", "", $phone_to_check);

        $minimumLength = 10;
        $maxLength = 14;
        if ($isInterNational) {
            $minimumLength = 7;
            $maxLength = 15;
        }
        if (strlen($phone_to_check) < $minimumLength || strlen($phone_to_check) > $maxLength) {
            return false;
        } else {
            return true;
        }
    }

    public function validatePanNumber($panNumber)
    {
        $pattern = '/^([A-Z]{5}[0-9]{4}[A-Z]{1})$/';
        return preg_match($pattern, $panNumber);
    }

    public function validateGSTNumber($gstNumber) {
        $pattern = '/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1})$/';
        return preg_match($pattern, $gstNumber);
    }
    public function validatePinCode($pincode) {
        $pattern = '/^[\d]{6}$/';
        return preg_match($pattern, $pincode);
    }

    //check use echo print?  
    public function commonDataPrint($data)
    {
        http_response_code(200);
        return json_encode(
            array(
                "code" => 200,
                "status" => "success",
                "data" => $data
            )
        );
    }

    //check use echo print?  
    public function commonBadRequestDataPrint($data)
    {
        http_response_code(400);
        return json_encode(
            array(
                "code" => 400,
                "status" => "success",
                "data" => $data
            )
        );
    }

    //check use echo print?  
    public function commonCompanyProfile($data)
    {
        return json_encode(
            array(
                "company_profile" => $data
            )
        );
    }
    

    //check use echo print?  
    public function commonServerErrorPrint($data)
    {
        http_response_code(500);
        return json_encode(
            array(
                "code" => 200,
                "status" => "success",
                "data" => $data
            )
        );
    }

    //Check all fields should not be empty (zero can be allow)
    function checkFieldsNotEmpty($fields, $fields_key)
    {
        $field_count = count($fields_key);
        for ($i = 0; $i < $field_count; $i++) {
            $field = $fields[$i];
            $key = $fields_key[$i];
            if (!isset($field)) {
                $this->fieldRequested($key);
                return false;
            } else if (empty($field)) {
                if ($field === 0 || $field === "0") {} else {
                    $this->fieldMustNotEmptyRequested($key);
                    return false;
                }
            }
        }
        return true;
    }

    // check one of feild must be greather than zero in the list
    function checkFieldsGreaterThanZero($fields) {
        foreach ($fields as $field) {
            if ($field > 0) {
                return true;
            }
        }
        return false;
    }

    function validateDate($date, $startDate, $endDate) {
        $dateObj = DateTime::createFromFormat($this->dateFormat, $date);
        $startObj = DateTime::createFromFormat($this->dateFormat, $startDate);
        $endObj = DateTime::createFromFormat($this->dateFormat, $endDate);
        
        // Compare the timestamps
        $dateTimestamp = $dateObj->getTimestamp();
        $startTimestamp = $startObj->getTimestamp();
        $endTimestamp = $endObj->getTimestamp();
        
        return $dateTimestamp >= $startTimestamp && $dateTimestamp <= $endTimestamp;
    }

    function isValidateDateFormate($date) {
        $dateObj = DateTime::createFromFormat($this->dateFormat, $date);
        return ($dateObj && $dateObj->format($this->dateFormat) == $date);
    }

}
