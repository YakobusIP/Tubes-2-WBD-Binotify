<?php

namespace Server\Controllers;
include "Server/Controllers/Controller.php";
use PDO;

class UserController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function registerview(array $params = [])
    {
        $username = $params['username'] ?? 'Guest';
        include 'Client/pages/Register/Register.php';
    }

    public function loginview(array $params = [])
    {
        $username = $params['username'] ?? 'Guest';
        include 'Client/pages/Login/Login.php';
    }

    public function userlistview(array $params = []) {
        include 'Client/pages/UserList/UserList.php';
    }

    
    public function findByUsername($username){
        $stmt = "SELECT username, email FROM binotify_user WHERE username = ?";
        $username = $_POST['username'];
        $query = $this->database->prepare($stmt);
        $query->execute(array($username));
        $result = $query->fetchAll();
        print_r(json_encode(array(
            'result' => $result
        )));
        // print_r(json_encode(array($result)));
        return $result;
    }

    public function findByUsernameLogin($username){
        $stmt = "SELECT username, email FROM binotify_user WHERE username = ?";
        $username = $_POST['username'];
        $query = $this->database->prepare($stmt);
        $query->execute(array($username));
        $result = $query->fetchAll();
        // print_r(json_encode(array($result)));
        return $result;
    }

    public function findEmail($email){
        $stmt = "SELECT username, email FROM binotify_user WHERE email = ?";
        $email = $_POST['email'];
        $query = $this->database->prepare($stmt);
        $query->execute(array($email));
        $result = $query->fetchAll();
        var_dump($result);
        return $result;
    }

    public function getAllUsers() {
        $query = $this->database->prepare("SELECT email, full_name, username FROM binotify_user WHERE isAdmin = False");
        $query->execute();
        $result = $query->fetchAll();
        print_r(json_encode($result));
    }

    public function register(){

        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '' ;
        $passwordConfirm = isset($_POST['passwordConfirm']) ? $_POST['passwordConfirm'] : '' ;

        if ($username=='' || $full_name=='' || $email=='' || $password=='' || $passwordConfirm=='') {
            # code...
            http_response_code(400);
            print_r(json_encode(array(
                'status' => 400,
                'message' => "Empty field"
            )));
        }

        // print_r(json_encode($_POST));

        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO binotify_user(username, full_name, email, password) VALUES(:username, :full_name, :email, :password)";
        $query = $this->database->prepare($sql);
        $query->bindParam(":username", $username);
        $query->bindParam(":full_name", $full_name);
        $query->bindParam(":password", $hashed);
        $query->bindParam(":email", $email);
        $query->execute();

        http_response_code(201);
        print_r(json_encode(array(
            'status' => 201,
            'message' => "User created"
        )));
        
    }

    public function login(){

        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '' ;

        if ($username=='' || $password=='') {
            # code...
            http_response_code(400);
            print_r(json_encode(array(
                'status' => 400,
                'message' => "Empty field"
            )));
        }
        else{
            $result = $this->findByUsernameLogin($username);
            if(empty($result)){
                http_response_code(401);
                // header('http//1.1 401 not found');
                echo json_encode(array(
                    'status' => 401,
                    'message' => "Username doesn't exists"
                )); 
                exit;
            }
        }

        $sql = "SELECT user_id, username, full_name, isadmin, password FROM binotify_user WHERE username=:username";
        $query = $this->database->prepare($sql);
        $query->bindParam(":username", $username);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["full_name"] = $user["full_name"];
            $_SESSION["isadmin"] = $user["isadmin"];
            http_response_code(200);
            print_r(json_encode(array(
                'status' => 200,
                'message' => "Logged in"
            )));
        } else {
            # code...
            http_response_code(402);
            print_r(json_encode(array(
                'status' => 402,
                'message' => "Unauthorized"
            )));
        }
    }

}

?>