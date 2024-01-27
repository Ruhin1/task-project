<?php
namespace app;

require "helper/helperfun.php";
use mysqli;
use Throwable;
use app\helper\helperfun;

class Database
{
    use helperfun;
    private $db_host = "localhost";
    private $db_user = "root";
    private $db_pass = "";
    private $db_name = "project";

    public $mysqli = "";
    private $result = [];
    public $conn = false;

    // construct method
    public function __construct()
    {
        if (!$this->conn) {
            $this->mysqli = new mysqli(
                $this->db_host,
                $this->db_user,
                $this->db_pass,
                $this->db_name
            );
            $this->conn = true;
            if ($this->mysqli->connect_error) {
                array_push($this->result, $this->mysqli->connect_error);
                return false;
            }
        }

        return true;
    }

    // email validation function its check email exist or no
    public function emailValidate($table, $sql)
    {
        if ($this->tablEexjist($table)) {
            $result = $this->mysqli->query($sql);
            if (mysqli_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    //username validation function its check username exist or no
    public function userNameValidate($table, $sql)
    {
        if ($this->tablEexjist($table)) {
            $result = $this->mysqli->query($sql);
            if (mysqli_num_rows($result) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    // register method
    public function register($table, $sqls)
    {
        if ($this->tablEexjist($table)) {
            if ($this->mysqli->query($sqls)) {
                return true;
            } else {
                return false; 
            }
        }
    }

    //login method 
    public function login($table, $sql, $password)
    {
        if ($this->tablEexjist($table)) {
            $result = $this->mysqli->query($sql);
            $user = mysqli_fetch_assoc($result);
            if ($user) {

                $password = $password;
                $passwordHash = $user['password'];
                if (password_verify($password,$passwordHash)) {
                    session_start();
                    $_SESSION["user"] = "yes";
                    $_SESSION["data"] = $user['email'];
                    header("Location: ../admin/deshbord.php");
                }else{
                    header("Location: ../auth/login.php");
                }
            }else{
                return false;
            }
        }
    }

    // logout method
    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: ../auth/login.php");
    }

    // auth method it return current login user credential
    public function auth($email)
    {
        $sql = "SELECT * FROM `users` WHERE email = '$email'";
        $result = $this->mysqli->query($sql);
        return $auth = mysqli_fetch_assoc($result);
    }

    //destruct method
    public function __destruct()
    {
        if ($this->conn) {
            if ($this->mysqli->close()) {
                $this->mysqli = false;
                return true;
            }
        } else {
            return false;
        }
    }
}
