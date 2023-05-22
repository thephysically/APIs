<?php
// Author:- Jeetesh Surana
class Database
{

    // specify your own database credentials
    private $host = "localhost";            //Server
    private $db_name = "id20406881_physicalsolution";       //Database Name
    private $username = "root";             //UserName of Phpmyadmin
    private $password = "";                 //Password associated with username
    public $conn;

    private static $instance;

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo $this->errorCommon(500, $exception->getMessage());
            die;
        }
      }

    // get the database connection
    public function getConnection(){
        return $this->conn;
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function errorCommon($status, $message, $isSuccess = false)
    {
        return json_encode(
            array(
                "status" => $status,
                "message" => $message
            )
        );
    }
}
