<?php

class SesionController {

    private $printer;
    private $sesionModel;
    private $vuelosModel;

    public function __construct($sesionModel, $printer, $vuelosModel) {
        $this->printer = $printer;
        $this->sesionModel = $sesionModel;
        $this->vuelosModel = $vuelosModel;
    }

    public function show() {

        
        $data["viajes"] = $this->vuelosModel->getVuelos();
        echo $this->printer->render("homeView.html", $data);


    }

    public function procesarSesion() {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $encryptedPassword = $password;
        //$encryptedPassword = md5($password);
        $usuario = $this->sesionModel->getSesion($email, $encryptedPassword);

        if ($usuario[0]["email"] == $email && $usuario[0]["password"] == $encryptedPassword) {

            $_SESSION["rol"] = $usuario[0]["id_rol"];
            $_SESSION["login"] = true;
            $_SESSION["nombre"] = $usuario[0]["nombre"];
            $data["nombre"] = $usuario[0]["nombre"];

            if ($_SESSION["rol"] == 1) {


                echo $this->printer->render("adminHomeView.html", $data);
            } else {


    
            
                $data["viajes"] = $this->vuelosModel->getVuelos();
                echo $this->printer->render("userHomeView.html", $data);
            }
        } else {
            header("location:index.php?controller=sesion&method=show");
        }
    }

    public function logout() {
        session_destroy();
        header("location:index.php?controller=sesion&method=show");
    }

}