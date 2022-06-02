<?php

class UsuarioController {
    private $printer;
    private $usuarioModel;
    private $userValidator;
    private $vuelosModel;

    public function __construct($usuarioModel, $printer, $userValidator, $vuelosModel) {
        $this->printer = $printer;
        $this->usuarioModel = $usuarioModel;
        $this->userValidator = $userValidator;
        $this->vuelosModel = $vuelosModel;
    }

    public function show() {

        
        echo $this->printer->render("registroView.html");
    }

    public function login() {
        try {
            // validaciones
            $this->userValidator->validateUserToLogin($_POST);

            // ejecucion
            $user = $this->usuarioModel->logUser($_POST);

            // presentacion
            $data["nombre"] = $user["nombre"];

            $_SESSION["rol"] = $user["id_rol"];

            if(isset($_SESSION["rol"]) && $_SESSION["rol"]==2){
                $data["esClient"] = true; 
                $data["viajes"] = $this->vuelosModel->getVuelos();
                echo $this->printer->render("homeView.html", $data);
            }
            
            if(isset($_SESSION["rol"]) && $_SESSION["rol"]==1){
                $data["esAdmin"] = true;
                $data["viajes"] = $this->vuelosModel->getVuelos();
                echo $this->printer->render("homeView.html", $data);
            }
        

        } catch (ValidationException|EntityNotFoundException $exception) {
            $data["error"] = "Usted no esta registrado";

            echo $this->printer->render("registroView.html", $data);

        }
    }

    public function logout() {
        session_destroy();
        Navigation::redirectTo("index.php?controller=home&method=show");
    }

    public function procesarRegistro() {
        try {
            // validaciones
            $this->userValidator->validateUserToRegister($_POST);

            // ejecucion
            $this->usuarioModel->agregarUsuario($_POST);

            $this->usuarioModel->enviarEmail($_POST["email"]);

            // presentacion
            $data["mensaje"] = "Ya puedes validar tu cuenta a traves de email";

            echo $this->printer->render("HomeView.html");


        } catch (ValidationException|EntityFoundException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("registroView.html", $data);
        }
    }

    public function activar() {
        $email = $_GET["email"];
        $this->usuarioModel->activarUsuario($email);
        $data["mensaje"] = "Tu cuenta ha sido verificada correctamente";

        echo $this->printer->render("HomeView.html");
    }


}

