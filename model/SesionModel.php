<?php
class SesionModel{
    private $database;
    
    public function __construct($database){
        $this->database = $database;
    }

    public function getSesion($email, $password){

        //$sql = "SELECT * FROM usuario WHERE email = '$mail' AND contraseña = '$contraseña' and activo = true";
     
        $usuario= $this->database->query('SELECT * FROM usuario WHERE email = "'.$email.'" AND password = "'.$password.'" and activo = 1');
        
        return $usuario;
    }
   
   
    

}


?>