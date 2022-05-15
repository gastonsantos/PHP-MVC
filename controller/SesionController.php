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
    
        $email = $_POST["email"];
        $password =  $_POST["password"];
        $usuario =$this->sesionModel->getSesion($email,$password);
        
       if($usuario[0]["email"] == $email && $usuario[0]["password"] == $password){

            $_SESSION["rol"] = $usuario[0]["id_rol"];

            if($_SESSION["rol"] == 1){

            $data["nombre"] = $usuario[0]["nombre"];
            echo $this->printer->render("adminHomeView.html", $data);  
            }else{

                $data["nombre"] = $usuario[0]["nombre"];
                echo $this->printer->render("userHomeView.html", $data);
            }

        }
        else{
            header("location:index.php?controller=sesion&method=show");
        }
    }

    public function logout(){
        session_destroy();
        header("location:index.php?controller=sesion&method=show");
    }

}