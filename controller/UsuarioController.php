<?php

class UsuarioController{

    private $printer;
    private $usuarioModel;
    
    public function __construct($usuarioModel, $printer){
        $this->printer = $printer;
        $this->usuarioModel = $usuarioModel;
    }

   public function show(){

   echo $this->printer->render("registroView.html"); 
     
    }
 
   public  function procesarRegistro(){

        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $direccion = $_POST["direccion"];
        $email = $_POST["email"];
        $password =  $_POST["contraseña"];

        $id_rol = "1";
        $activo = 1;

        if( $this->usuarioModel->getMail ($email) == null){

        $this-> usuarioModel-> agregarUsuario ($nombre, $apellido, $direccion, $email, $password,$id_rol,$activo);

        echo $this->printer->render("homeView.html");
        }else{

            $data["mensaje"] =  "El usuario ya existe";
            

            echo $this->printer->render("registroView.html", $data);
    }
   }
    




}