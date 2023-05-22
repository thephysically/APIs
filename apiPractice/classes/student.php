<?php
class Student
{
    
    //declare variable
    public $name;
    public $email;
    public $otp;
    public $mobile;
    private $FIELD_NAME_ID = 'id';
    private $FIELD_NAME_NAME = 'name';
    private $FIELD_NAME_EMAIL = 'email';
    private $FIELD_NAME_MOBILE = 'mobile';
    private $FIELD_NAME_STATUS = 'status';
    private $TABLE_USERS = 'Users';
    private $TABLE_COMPANY_PROFILE = 'company_profile';
    private $TABLE_UPLOAD_IMAGES = 'upload_images';
    private $TABLE_JOB_POST = 'job_post';

    //message 
    public $msgCreateSuccessful = "Student has been created";
    public $msgCreateFailed = "Failed to insert data";
    private $msgEmailSent = "The OTP was sent successfully to your email address";
    private $msgMessageSent = "The message has been successfully sent.";
    private $msgVerifyEmail = "The email has been successfully verified.";
    private $msgEmailNotFound = "The provided email could not be found.";
    private $msgOTPNotMatch = "The provided OTP does not match.";
    private $msgMobileNotFound = "The provided mobile could not be found.";

    private $conn;
    public $constants;

    private static $instance;

    //constructor 
    public function __construct($db)
    {
        $this->conn = $db;
        $this->constants = Constant::getInstance();
    }

    public static function getInstance($db)
    {
        if (!isset(self::$instance)) {
            self::$instance = new Student($db);
        }
        return self::$instance;
    }

    //create data 
    public function create_data()
    {

        $data = json_decode(file_get_contents("php://input"));
        //sql query to insert data 
        $query = "INSERT INTO " . $this->TABLE_USERS . "SET name = ?, email = ?, mobile = ?";
        //prepare the sql
        $obj = $this->conn->prepare($query);

        if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {
            //submit data
            $this->name = $data->name;
            $this->email = $data->email;
            $this->mobile = $data->password;
        }
    }

    function enter_email_and_otp($email, $otp)
    {
        try {
            $check_email = "SELECT `id` FROM `$this->TABLE_USERS` WHERE `email`=:email";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();
            // don't deleted below data comment 
            // $result = $check_email_stmt->fetch();
            // print_r("id --> $result[0]");
            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_USERS` SET otp = :otp WHERE email = :email";
                $insert_stmt = $this->conn->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':otp', $otp, PDO::PARAM_STR);
                $insert_stmt->execute();
            else :
                $insert_query = "INSERT INTO `$this->TABLE_USERS`(`email`,`otp`) VALUES(:email,:otp)";
                $insert_stmt = $this->conn->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':email', $email, PDO::PARAM_STR);
                $insert_stmt->bindValue(':otp', $otp, PDO::PARAM_STR);
                $insert_stmt->execute();
            endif;
            echo $this->constants->commonDataPrint($this->msgEmailSent);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }


    function enter_mobile_and_otp($mobile, $otp)
    {
        try {
            $check_email = "SELECT `id` FROM `$this->TABLE_USERS` WHERE `mobile`=:mobile";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':mobile', $mobile, PDO::PARAM_STR);
            $check_email_stmt->execute();
            // don't deleted below data comment 
            // $result = $check_email_stmt->fetch();
            // print_r("id --> $result[0]");
            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_USERS` SET otp = :otp WHERE mobile = :mobile";
                $insert_stmt = $this->conn->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':mobile', $mobile, PDO::PARAM_STR);
                $insert_stmt->bindValue(':otp', $otp, PDO::PARAM_STR);
                $insert_stmt->execute();
            else :
                $insert_query = "INSERT INTO `$this->TABLE_USERS`(`mobile`,`otp`) VALUES(:mobile,:otp)";
                $insert_stmt = $this->conn->prepare($insert_query);
                // DATA BINDING
                $insert_stmt->bindValue(':mobile', $mobile, PDO::PARAM_STR);
                $insert_stmt->bindValue(':otp', $otp, PDO::PARAM_STR);
                $insert_stmt->execute();
            endif;
            echo $this->constants->commonDataPrint($this->msgMessageSent);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }

    function emailVerifyOTP($email, $otp)
    {
        try {
            $check_email = "SELECT `id`,`otp` FROM `$this->TABLE_USERS` WHERE `email`=:email";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();
            // don't deleted below data comment 
            $result = $check_email_stmt->fetch();
            $id = $result['id'];
            if ($check_email_stmt->rowCount()) {
                if ($result['otp'] == $otp) {
                    $insert_query = "UPDATE `$this->TABLE_USERS` SET isVerifiedEmail = :isVerifiedEmail WHERE id = :id";
                    $insert_stmt = $this->conn->prepare($insert_query);
                    // DATA BINDING
                    $insert_stmt->bindValue(':isVerifiedEmail', true, PDO::PARAM_STR);
                    $insert_stmt->bindValue(':id', $id, PDO::PARAM_STR);
                    $insert_stmt->execute();
                    $userDetails = $this->getUserDetails($id);
                    $companydetails =  $this->getCompanyProfile($id);
                    $finalData = array_merge($userDetails, array("company_details" => $companydetails));
                    echo $this->constants->commonDataPrint($finalData);
                } else {
                    echo $this->constants->customErrorMessage($this->msgOTPNotMatch);
                }
            } else {
                echo $this->constants->customErrorMessage($this->msgEmailNotFound);
            }
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }


    function mobileVerifyOTP($email, $otp)
    {
        try {
            $check_email = "SELECT `id`,`otp` FROM `$this->TABLE_USERS` WHERE `mobile`=:mobile";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':mobile', $email, PDO::PARAM_STR);
            $check_email_stmt->execute();
            // don't deleted below data comment 
            $result = $check_email_stmt->fetch();
            $id = $result['id'];
            if ($check_email_stmt->rowCount()) {
                if ($result['otp'] == $otp) {
                    $insert_query = "UPDATE `$this->TABLE_USERS` SET isVerifiedMobileNumber = :isVerifiedMobileNumber WHERE id = :id";
                    $insert_stmt = $this->conn->prepare($insert_query);
                    // DATA BINDING
                    $insert_stmt->bindValue(':isVerifiedMobileNumber', true, PDO::PARAM_STR);
                    $insert_stmt->bindValue(':id', $id, PDO::PARAM_STR);
                    $insert_stmt->execute();

                    $userDetails = $this->getUserDetails($id);
                    $companydetails =  $this->getCompanyProfile($id);
                    $finalData = array_merge($userDetails, array("company_details" => $companydetails));
                    echo $this->constants->commonDataPrint($finalData);
                } else {
                    echo $this->constants->customErrorMessage($this->msgOTPNotMatch);
                }
            } else {
                echo $this->constants->customErrorMessage($this->msgEmailNotFound);
            }
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }

    function getUserDetails($id)
    {
        // getting details from users
        $userDetails = "SELECT `id`, `name`, `email`, `mobile`, `status`, `isORG`, `isVerifiedMobileNumber`, `isVerifiedEmail`, `createdDate` FROM `Users` WHERE `id`=:id";
        $user_details_stmt = $this->conn->prepare($userDetails);
        $user_details_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $user_details_stmt->execute();
        return $user_details_stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getCompanyProfile($id)
    {
        // getting details from copmany_profile
        $companyDetails = "SELECT * FROM `$this->TABLE_COMPANY_PROFILE` WHERE `user_id`=:user_id";
        $company_details_stmt = $this->conn->prepare($companyDetails);
        $company_details_stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        $company_details_stmt->execute();
        $result = $company_details_stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $result;
    }

    function setCompanyProfile($data)
    {
        try {
            $this->isUserExist($data->user_id);
            $check_email = "SELECT `user_id` FROM $this->TABLE_COMPANY_PROFILE WHERE `user_id`=:user_id";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_COMPANY_PROFILE` SET company_name = :company_name,email_address = :email_address,mobile_number = :mobile_number,pan_number = :pan_number,gst_number = :gst_number,date_modify = :date_modify WHERE user_id = :user_id";
                $insert_stmt = $this->conn->prepare($insert_query);
            else :
                $insert_query = "INSERT INTO `$this->TABLE_COMPANY_PROFILE`(`user_id`, `company_name`, `email_address`, `mobile_number`, `pan_number`, `gst_number`, `date_modify`) VALUES(:user_id,:company_name,:email_address,:mobile_number,:pan_number,:gst_number,:date_modify)";
                $insert_stmt = $this->conn->prepare($insert_query);
            endif;

            // DATA BINDING
            $insert_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $insert_stmt->bindValue(':company_name', $data->company_name, PDO::PARAM_STR);
            $insert_stmt->bindValue(':email_address', $data->email_address, PDO::PARAM_STR);
            $insert_stmt->bindValue(':mobile_number', $data->mobile_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':pan_number', $data->pan_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':gst_number', $data->gst_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':date_modify', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $insert_stmt->execute();

            $companyData = $this->getCompanyProfile($data->user_id);
            echo $this->constants->commonDataPrint($companyData);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }
    
    function setCompanyProfileWithImageUrl($data)
    {
        try {
            $this->isUserExist($data->user_id);
            $check_email = "SELECT `user_id` FROM $this->TABLE_COMPANY_PROFILE WHERE `user_id`=:user_id";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_COMPANY_PROFILE` SET company_name = :company_name,email_address = :email_address,mobile_number = :mobile_number,pan_number = :pan_number,gst_number = :gst_number,image_url = :image_url,date_modify = :date_modify WHERE user_id = :user_id";
                $insert_stmt = $this->conn->prepare($insert_query);
            else :
                $insert_query = "INSERT INTO `$this->TABLE_COMPANY_PROFILE`(`user_id`, `company_name`, `email_address`, `mobile_number`, `pan_number`, `gst_number`, `image_url`, `date_modify`) VALUES(:user_id,:company_name,:email_address,:mobile_number,:pan_number,:gst_number,:image_url,:date_modify)";
                $insert_stmt = $this->conn->prepare($insert_query);
            endif;

            // DATA BINDING
            $insert_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $insert_stmt->bindValue(':company_name', $data->company_name, PDO::PARAM_STR);
            $insert_stmt->bindValue(':email_address', $data->email_address, PDO::PARAM_STR);
            $insert_stmt->bindValue(':mobile_number', $data->mobile_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':pan_number', $data->pan_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':gst_number', $data->gst_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':image_url', $data->image_url, PDO::PARAM_STR);
            $insert_stmt->bindValue(':date_modify', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $insert_stmt->execute();

            $companyData = $this->getCompanyProfile($data->user_id);
            echo $this->constants->commonDataPrint($companyData);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }
    

    function setJobPost($data)
    {
        try {
            $this->isUserExist($data->user_id);
            $check_email = "SELECT `id` FROM $this->TABLE_JOB_POST WHERE `user_id`=:user_id";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_JOB_POST` SET 
                user_id = :user_id,company_id = :company_id,company_name = :company_name,gst_number = :gst_number,contact_detail_name = :contact_detail_name,contact_detail_mobile_number = :contact_detail_mobile_number,location_pin_code = :location_pin_code,location_country = :location_country,location_state = :location_state,location_district = :location_district,location_address_line_1 = :location_address_line_1,location_address_line_2 = :location_address_line_2,staff_ca_fresher = :staff_ca_fresher,staff_ca_experience = :staff_ca_experience,staff_graduate_fresher = :staff_graduate_fresher,staff_graduate_experience = :staff_graduate_experience,start_date = :start_date,end_date = :end_date,total_days = :total_days,is_laptop_required = :is_laptop_required,is_reimbursement_required = :is_reimbursement_required,reimbursement_amount = :reimbursement_amount,any_comment = :any_comment,coupon_code = :coupon_code,discount_amount = :discount_amount,profession_fees = :profession_fees,convenience_fee = :convenience_fee,platform_fee = :platform_fee,tax_percentage = :tax_percentage,tax_amount = :tax_amount,total = :total,status = :status,date_modify = :date_modify WHERE user_id = :user_id";
                $insert_stmt = $this->conn->prepare($insert_query);
            else :
                $insert_query = "INSERT INTO `$this->TABLE_JOB_POST(`user_id`, `company_id`, `company_name`, `gst_number`, `contact_detail_name`, `contact_detail_mobile_number`, `location_pin_code`, `location_country`, `location_state`, `location_district`, `location_address_line_1`, `location_address_line_2`, `staff_ca_fresher`,`staff_ca_experience`, `staff_graduate_fresher`, `staff_graduate_experience`, `start_date`, `end_date`, `total_days`, `is_laptop_required`, `is_reimbursement_required`, `reimbursement_amount`, `any_comment`, `coupon_code`, `discount_amount`, `profession_fees`, `convenience_fee`, `platform_fee`, `tax_percentage`, `tax_amount`, `total`, `status`, `date_modify`) VALUES(:user_id, :company_id, :company_name, :gst_number, :contact_detail_name, :contact_detail_mobile_number, :location_pin_code, :location_country, :location_state, :location_district, :location_address_line_1, :location_address_line_2, :staff_ca_fresher, :staff_ca_experience, :staff_graduate_fresher, :staff_graduate_experience, :start_date, :end_date, :total_days, :is_laptop_required, :is_reimbursement_required, :reimbursement_amount, :any_comment, :coupon_code, :discount_amount, :profession_fees, :convenience_fee, :platform_fee, :tax_percentage, :tax_amount, :total, :status, :date_modify)";
                $insert_stmt = $this->conn->prepare($insert_query);
            endif;
            // DATA BINDING
            $insert_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $insert_stmt->bindValue(':company_id', $data->company_id, PDO::PARAM_STR);
            $insert_stmt->bindValue(':company_name', $data->company_name, PDO::PARAM_STR);
            $insert_stmt->bindValue(':gst_number', $data->gst_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':contact_detail_name', $data->contact_detail_name, PDO::PARAM_STR);
            $insert_stmt->bindValue(':contact_detail_mobile_number', $data->contact_detail_mobile_number, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_pin_code', $data->location_pin_code, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_country', $data->location_country, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_state', $data->location_state, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_district', $data->location_district, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_address_line_1', $data->location_address_line_1, PDO::PARAM_STR);
            $insert_stmt->bindValue(':location_address_line_2', $data->location_address_line_2, PDO::PARAM_STR);
            $insert_stmt->bindValue(':staff_ca_fresher', $data->staff_ca_fresher, PDO::PARAM_STR);
            $insert_stmt->bindValue(':staff_ca_experience', $data->staff_ca_experience, PDO::PARAM_STR);
            $insert_stmt->bindValue(':staff_graduate_fresher', $data->staff_graduate_fresher, PDO::PARAM_STR);
            $insert_stmt->bindValue(':staff_graduate_experience', $data->staff_graduate_experience, PDO::PARAM_STR);
            $insert_stmt->bindValue(':start_date', $data->start_date, PDO::PARAM_STR);
            $insert_stmt->bindValue(':end_date', $data->end_date, PDO::PARAM_STR);
            $insert_stmt->bindValue(':total_days', $data->total_days, PDO::PARAM_STR);
            $insert_stmt->bindValue(':is_laptop_required', $data->is_laptop_required, PDO::PARAM_STR);
            $insert_stmt->bindValue(':is_reimbursement_required', $data->is_reimbursement_required, PDO::PARAM_STR);
            $insert_stmt->bindValue(':reimbursement_amount', $data->reimbursement_amount, PDO::PARAM_STR);
            $insert_stmt->bindValue(':any_comment', $data->any_comment, PDO::PARAM_STR);
            $insert_stmt->bindValue(':coupon_code', $data->coupon_code, PDO::PARAM_STR);
            $insert_stmt->bindValue(':discount_amount', $data->discount_amount, PDO::PARAM_STR);
            $insert_stmt->bindValue(':profession_fees', $data->profession_fees, PDO::PARAM_STR);
            $insert_stmt->bindValue(':convenience_fee', $data->convenience_fee, PDO::PARAM_STR);
            $insert_stmt->bindValue(':platform_fee', $data->platform_fee, PDO::PARAM_STR);
            $insert_stmt->bindValue(':tax_percentage', $data->tax_percentage, PDO::PARAM_STR);
            $insert_stmt->bindValue(':tax_amount', $data->tax_amount, PDO::PARAM_STR);
            $insert_stmt->bindValue(':total', $data->total, PDO::PARAM_STR);
            $insert_stmt->bindValue(':status', $data->status, PDO::PARAM_STR);
            $insert_stmt->bindValue(':date_modify', date($this->constants->serverDateFormat), PDO::PARAM_STR);
            $insert_stmt->execute();
            $companyData = $this->getCompanyProfile($data->user_id);
            echo $this->constants->commonDataPrint($companyData);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }


    function getJobPostDetail($id)
    {
        // getting details from copmany_profile
        $companyDetails = "SELECT * FROM `$this->TABLE_JOB_POST` WHERE `id`=:id";
        $job_post_details_stmt = $this->conn->prepare($companyDetails);
        $job_post_details_stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $job_post_details_stmt->execute();
        $result = $job_post_details_stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $result;
    }

    public function isUserExist($id)
    {
        $isUserExist = "SELECT * FROM $this->TABLE_USERS WHERE `id`=:id";
        $isUserExist_stmt = $this->conn->prepare($isUserExist);
        $isUserExist_stmt->bindValue(':id', $id, PDO::PARAM_STR);
        $isUserExist_stmt->execute();
        if (!$isUserExist_stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $this->constants->commonBadRequestDataPrint("User doesn't exist");
            return;
            die;
        };
    }


    //fetch all data 
    public function fecth_all_data()
    {
        $query = "SELECT "
            . $this->FIELD_NAME_ID . ","
            . $this->FIELD_NAME_NAME . ","
            . $this->FIELD_NAME_EMAIL . ","
            . $this->FIELD_NAME_MOBILE . ","
            . $this->FIELD_NAME_STATUS .
            " FROM " . $this->TABLE_USERS;

        $statement = $this->conn->prepare($query);
        if ($statement->execute()) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            if (empty($data)) $data = array();
            echo $this->constants->commonDataPrint($data);
        } else {
            $this->constants->serverError(0);
        }
    }

    //fetch single data 
    public function fecth_single_data($id)
    {
        $query = "SELECT "
            . $this->FIELD_NAME_ID . ","
            . $this->FIELD_NAME_NAME . ","
            . $this->FIELD_NAME_EMAIL . ","
            . $this->FIELD_NAME_MOBILE . ","
            . $this->FIELD_NAME_STATUS .
            " FROM " . $this->TABLE_USERS . " WHERE " . $this->FIELD_NAME_ID . ' = ' . $id;

        $statement = $this->conn->prepare($query);
        if ($statement->execute()) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            printf("statement execute loop finished" + $data);
            if (empty($data)) $data = array();
            echo $this->constants->commonDataPrint($data);
        } else {
            $this->constants->serverError(0);
        }
    }
    
    function setUploadImages($data)
    {
        try {
            $this->isUserExist($data->user_id);
            $check_email = "SELECT `id` FROM $this->TABLE_UPLOAD_IMAGES WHERE `user_id`=:user_id";
            $check_email_stmt = $this->conn->prepare($check_email);
            $check_email_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $check_email_stmt->execute();

            if ($check_email_stmt->rowCount()) :
                $insert_query = "UPDATE `$this->TABLE_UPLOAD_IMAGES` SET image_url = :image_url WHERE user_id = :user_id";
                $insert_stmt = $this->conn->prepare($insert_query);
            else :
                $insert_query = "INSERT INTO `$this->TABLE_UPLOAD_IMAGES`(`user_id`, `image_url`) VALUES(:user_id,:image_url)";
                $insert_stmt = $this->conn->prepare($insert_query);
            endif;

            // DATA BINDING
            $insert_stmt->bindValue(':user_id', $data->user_id, PDO::PARAM_STR);
            $insert_stmt->bindValue(':image_url', $data->image_url, PDO::PARAM_STR);
            $insert_stmt->execute();

            $companyData = $this->getUploadImagesUrls($data->user_id);
            echo $this->constants->commonDataPrint($companyData);
        } catch (PDOException $e) {
            echo $this->constants->commonServerErrorPrint($e->getMessage());
        }
    }

    
    function getUploadImagesUrls($id)
    {
        $companyDetails = "SELECT * FROM `$this->TABLE_UPLOAD_IMAGES` WHERE `user_id`=:user_id";
        $company_details_stmt = $this->conn->prepare($companyDetails);
        $company_details_stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        $company_details_stmt->execute();
        $result = $company_details_stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $result;
    }
}
