<?php
class SesionModel{
    private $database;
    
    public function __construct($database){
        $this->database = $database;
    }

    public function getSesion($mail, $contraseña){

        //$sql = "SELECT * FROM usuario WHERE email = '$mail' AND contraseña = '$contraseña' and activo = true";
     
        $sesion= $this->database->query("SELECT * FROM usuario WHERE email = '$mail' AND contraseña = '$contraseña' and activo = true");
        
        

        $_SESSION["apodo"] = $sesion[0]["apodo"];
        $_SESSION["email"] = $sesion[0]["email"];
        $_SESSION["idUser"] = $sesion[0]["id"];
        $_SESSION["nombre"] = $sesion[0]["nombre"];
        $_SESSION["apellido"] = $sesion[0]["apellido"];
        $_SESSION["rol"] = $sesion[0]["rol"];
        $_SESSION["activo"] = $sesion[0]["activo"];
        $_SESSION["login"] = true;
        return $_SESSION;
        
    
        
        
    }
   
   
    

}


?>