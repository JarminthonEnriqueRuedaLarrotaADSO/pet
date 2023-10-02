<?php

namespace adso\Mascotas\controller;

use adso\Mascotas\libs\Controller;
use adso\Mascotas\libs\Session;

class LoginController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this -> model = $this -> model("User");
        
    }

    public function index(){
        $data = [
            "titulo" => "login",
            "subtitulo" => "preparate a loguearte"
        ];

        $this -> view("login", $data);
    }

    public function validate(){
        
        if($_SERVER['REQUEST_METHOD'] == "POST"){

            $errors = array();

            $correo = $_POST['email'];
            $passwor = $_POST['clave'];

            if($correo == ""){
                $errors["name_error"] = "el correo es requerido";
            }
            if($passwor == ""){
                $errors["clave_error"] = "la contraseña es requerido";
            }
            if(strlen($correo) > 50){
                $errors["name_error"] = "el correo excede el limite de caracteres";
            }
            if(strlen($passwor) > 50){
                $errors["clave_error"] = "la contraseña excede el limite de caracteres";
            }

            if(empty($errors)){

                $data = $this -> model -> validate($correo, $passwor);

                if(empty($data)){
                    $errors["clave_error"] = "usuario o contraseña incorrecto";

                    $data = [
                        "titulo" => "login",
                        "subtitulo" => "preparate a loguearte",
                        "errors" => $errors
                    ];
                    
                    $this -> view("login", $data);
                }else{

                    $session = new Session();

                    $session -> loginStar($data);

                    header("Location:".URL."/admin");
                }

            }else{
                $data = [
                    "titulo" => "login",
                    "subtitulo" => "preparate a loguearte",
                    "errors" => $errors
                ];
                $this -> view("login", $data);
            }
        }else{
            header("Location:".URL."/login");
        }
    }
}