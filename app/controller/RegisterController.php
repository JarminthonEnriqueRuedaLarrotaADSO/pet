<?php

namespace adso\Mascotas\controller;

use adso\Mascotas\libs\Controller;
use Error;

class RegisterController extends Controller
{

    protected $model;

    public function __construct()
    {
        $this -> model = $this -> model("User");
        
    }

    public function index(){
        $data = [
            "titulo" => "registro",
            "subtitulo" => "registrate"
        ];

        $this -> view("register", $data);
    }

    public function validate(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $error = array();

            $nombre = $_POST['name'];
            $correo = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password_confirmation'];

            if($nombre == ""){
                $error['name_error'] = "el nombre es requerido";
            }
            if($correo == ""){
                $error['mail_error'] = "el correo es requerido";
            }
            if($password == ""){
                $error['pass_error'] = "la contraseña es requerida";
            }
            if(strlen($nombre) > 50){
                $error['name_error'] = "el nombre excede el limite de caracteres";
            }
            if(strlen($correo) > 50){
                $error['mail_error'] = "el correo excede el limite de caracteres";
            }
            if(strlen($password) > 50){
                $error['pass_error'] = "la contraseña excede el limite de caracteres";
            }
            if($password != $password2){
                $error['confirm_error'] = "las contraseñas no coinciden";
            }

            if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){
                $error['mail_error'] = "el correo no es valido";
            }

            if(empty($error)){
                
                $data = $this -> model -> insert($nombre, $correo, $password);
                
                if(empty($data)){
                    $data = [
                        "titulo" => "registro",
                        "subtitulo" => "registrate",
                        "errors" =>  $error
                    ];
            
                    $this -> view("register", $data);
                }else{
                    header("Location:".URL."/login");
                }

            }else{
                $data = [
                    "titulo" => "registro",
                    "subtitulo" => "registrate",
                    "errors" =>  $error
                ];
        
                $this -> view("register", $data);
            }

        }else{
            header("Location:".URL."/register");
        }
    }
}