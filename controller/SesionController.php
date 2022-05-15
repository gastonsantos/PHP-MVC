<?php

class SesionController{

    private $printer;
    private $sesionModel;
    
    public function __construct($sesionModel, $printer){
        $this->printer = $printer;
        $this->sesionModel = $sesionModel;
    }

   public  function show(){

   echo $this->printer->render("homeView.html"); 
        
           
    }
 
   public  function procesarSesion(){
    
        $mail = $_POST["email"];
        $contraseña =  $_POST["contraseña"];
        $_SESSION =$this->sesionModel->getSesion($mail,$contraseña);
          if($_SESSION["login"]==true && $_SESSION["rol"]=="admin" && $_SESSION["activo"]==true){
         
            
            $data["apodo"] = $_SESSION ["apodo"];
            echo $this->printer->render("adminHomeView.html", $data);

          }else if($_SESSION["login"]==true && $_SESSION["rol"]=="user" && $_SESSION["activo"]==true){
            
            $data["apodo"] = $_SESSION ["apodo"];
            echo $this->printer->render("userHomeView.html", $data);
       
        }else{
            header("location:/sesion");

        }


    

    }

    public function logout(){
        session_destroy();
        header("location:/sesion");
    }





}