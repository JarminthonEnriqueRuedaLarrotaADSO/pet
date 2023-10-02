<?php

namespace adso\Mascotas\controller;

use adso\Mascotas\libs\Controller;
use adso\Mascotas\libs\Session;

class AdminController extends Controller
{
    protected $model;
    protected $model1;
    protected $model2;
    protected $model3;

    function __construct()
    {
        $session = new Session();

        if($session -> getLogin()){

            if($session -> getUser()["rol_id"] == 1 ){

                $this -> model = $this -> model("pet");
                $this -> model1 = $this -> model("race");
                $this -> model2 = $this -> model("genero");
                $this -> model3 = $this -> model("category");

            }else{
                header("Location:".URL."/user");
            }

        }else{
            header("Location:".URL."/login");
        }
    }

    function index(){
        $datos = $this -> model -> allSelect();

        $data = [
            "titulo" => "usuario",
            "subtitulo" => "vista de usuario",
            "mascotas" => $datos
        ];

        $this -> view("admin/index", $data);
    }

    function show($id){

        $datos = $this -> model -> showSelect($id);

        $data = [
            "titulo" => "usuario",
            "subtitulo" => "vista de usuario",
            "mascotas" => $datos
        ];

        $this -> view("admin/show", $data);

    }

    function new(){
        $race = $this -> model1 -> select();
        $genero = $this -> model2 -> select();
        $category = $this -> model3 -> select();
        $data = [
            "titulo" => "usuario",
            "subtitulo" => "vista de usuario",
            "race"=>$race,
            "genero"=>$genero,
            "category"=>$category
        ];

        $this -> view("admin/add", $data);
    }

    function savePets (){
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
            $errors=array();
            $name=$_POST["name"];
            $raza= $_POST[ "raza"];
            $category=$_POST["category"];
            $genero=$_POST["genero"];
            
            $file = $_FILES["imague"]["name"];
            $url_temp = $_FILES["imague"]["tmp_name"];
            $file_size = floor($_FILES["imague"]["size"] / 1024);
            $file_type = ($_FILES["imague"]["tmp_name"] != null) ? mime_content_type($_FILES["imague"]["tmp_name"]) : "";
            
            if($name == ""){
                $errors['name_errors'] = "el nombre es requerido";
            }
            if($raza == ""){
                $errors['raza_errors'] = "el campo raza es requerido";
            }
            if($category == ""){
                $errors['category_errors'] = "el campo categoria es requerido";
            }
            if($genero == ""){
                $errors['genero_errors'] = "el campo genero  es requerido";
            }
            if(strlen($name) > "50"){
                $errors['name_error'] = "el nombre excede el limite de caracteres";
            }
            
            if($file == null){
                $errores['error_file'] = "por favor seleccione un archivo";
            }
            
            if ( $file_size > 5120) {
                $errores['error_file'] = "el archivo es muy pesado";
            }
            
            if($file_type != "image/jpg" && $file_type != "image/jpeg" && $file_type != "image/png" ) {
                $errores['error_file'] = "el archivo tiene formato no permitido";
            }
            
            $url_insert = dirname(__FILE__,3)."\\public\\assets\\images\\";
            
            
            if(empty($errors)){

                if(!file_exists("assets/images")){
                    mkdir("assets/images", 0777, true);
                }
                
                chmod( $url_insert, 0777);
                
                if (move_uploaded_file($url_temp, $url_insert.$file)) {
                    
                    $url_insert = "assets/images/".$file;
                    
                    $this -> model-> insert($name, $raza, $category, $url_insert, $genero);
                    
                    header("Location:".URL."/admin");
                    
                } else {
                    $errores['error_file'] = "no pudo registrarse";
                    
                    $data = [
                        'Titulo' => 'Perfiles',
                        'subtitulos' => 'actualizar perfil',
                        "errores" => $errores
                    ];
                }
                
            }else{
                
                $data = [
                    "titulo" => "usuario",
                    "subtitulo" => "vista de usuario",
                    "errors" => $errors
                ];
                
                $this -> view("admin/add", $data);
            }
        }else{
            $data = [
                "titulo" => "usuario",
                "subtitulo" => "vista de usuario"
            ];
            
            $this -> view("admin/add", $data);
        }
    }

    function edit($id)
    {

        $race = $this -> model1 -> select();
        $genero = $this -> model2 -> select();
        $category = $this -> model3 -> select();

        $datos = $this -> model -> showSelect($id);
        
        $data = [
            "titulo" => "usuario",
            "subtitulo" => "vista de usuario",
            "race"=>$race,
            "genero"=>$genero,
            "category"=>$category,
            "datos" => $datos,
            "id" => $id
        ];

        $this -> view("admin/edit", $data);
    }

    function upload($id)
    {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            
            $errors = array();
            $ret = true;
            
            $name=$_POST["name"];
            $raza= $_POST[ "raza"];
            $category=$_POST["category"];
            $genero=$_POST["genero"];
            $photo = $_POST['imagen_actual'];
            
            $file = $_FILES["imague"]["name"];
            $url_temp = $_FILES["imague"]["tmp_name"];
            $file_size = floor($_FILES["imague"]["size"] / 1024);
            $file_type = ($_FILES["imague"]["tmp_name"] != null) ? mime_content_type($_FILES["imague"]["tmp_name"]) : "";
            
            if($name == ""){
                $errors['name_errors'] = "el nombre es requerido";
            }
            if($raza == ""){
                $errors['raza_errors'] = "el campo raza es requerido";
            }
            if($category == ""){
                $errors['category_errors'] = "el campo categoria es requerido";
            }
            if($genero == ""){
                $errors['genero_errors'] = "el campo genero  es requerido";
            }
            if(strlen($name) > "50"){
                $errors['name_error'] = "el nombre excede el limite de caracteres";
            }
            
            
            $url_insert = dirname(__FILE__,3)."\\public\\assets\\images\\";
            
            if($file == null){
                
                $url_temp = $photo;
                $url_insert = $photo;
                $ret = false;
                
            }else{
                
                if ( $file_size > 5120) {
                    $errores['error_file'] = "el archivo es muy pesado";
                }
                
                if($file_type != "image/jpg" && $file_type != "image/jpeg" && $file_type != "image/png" ) {
                    $errores['error_file'] = "el archivo tiene formato no permitido";
                }
                
            }
            
            
            
            if(empty($errors)){

                if($ret){

                    if(!file_exists("assets/images")){
                        mkdir("assets/images", 0777, true);
                    }
                    
                    if(is_file($photo)){
                        chmod($photo, 0777);
                        unlink($photo);
                    }
                    
                    chmod( $url_insert, 0777);
    
                    
                    if (move_uploaded_file($url_temp, $url_insert.$file)) {
                        
    
                        $url_insert = "assets/images/".$file;
                        
                        $this -> model-> update($name, $raza, $category, $url_insert, $genero, $id);
                        
                        header("Location:".URL."/admin");
                        
                    } else {
                        $errores['error_file'] = "no pudo registrarse";
                        
                        $data = [
                            'Titulo' => 'Perfiles',
                            'subtitulos' => 'actualizar perfil',
                            "errores" => $errores
                        ];
                    }

                }else{
                    
                    $this -> model-> update($name, $raza, $category, $url_insert, $genero, $id);
                    
                    header("Location:".URL."/admin");
                }
                
                

            }else{
                $data = [
                    "titulo" => "usuario",
                    "subtitulo" => "vista de usuario"
                ];
                
                $this -> view("admin/edit", $data);
            }
        }else{
            $data = [
                "titulo" => "usuario",
                "subtitulo" => "vista de usuario"
            ];
            
            $this -> view("admin/edit", $data);
        }
    }

    function delete($id)
    {
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {

            $photo = $_POST['photo'];

            if(!file_exists("assets/images")){
                mkdir("assets/images", 0777, true);
            }
            
            if(is_file($photo)){
                chmod($photo, 0777);
                unlink($photo);
            }
            $this -> model->delete($id);
            header("Location:".URL."/admin");

        }else{
            header("Location:".URL."/admin");
        }
            
    }
    function sessionClose()
    {
        $session = new Session();
    
        $session ->loginDestroy();
    
        header("Location: ".URL."/login");
    }
}