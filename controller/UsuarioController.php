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

            $_SESSION["nombre"] = $user["nombre"];

            if ($_SESSION["rol"] == 1) {
                echo $this->printer->render("adminHomeView.html", $data);
            } else {

                $data["viajes"] = $this->vuelosModel->getVuelos();

                echo $this->printer->render("userHomeView.html", $data);
            }

        } catch (ValidationException|EntityNotFoundException $exception) {
            Navigation::redirectTo("index.php");
        }
    }

    public function logout() {
        session_destroy();
        Navigation::redirectTo("index.php?controller=usuario&method=show");
    }

    public function procesarRegistro() {
        try {
            // validaciones
            $this->userValidator->validateUserToRegister($_POST);

            // ejecucion
            $this->usuarioModel->agregarUsuario($_POST);

            $this->usuarioModel->enviarEmail($_POST["email"]);

            // presentacion
            $data["mensaje"]="Ya puedes validar tu cuenta a traves de email";

            $data["viajes"] = $this->vuelosModel->getVuelos();

            echo $this->printer->render("HomeView.html", $data);


        } catch (ValidationException|EntityFoundException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("registroView.html", $data);
        }
    }

    public function activar(){
        $email = $_GET["email"];
        $this->usuarioModel->activarUsuario($email);
        $data["mensaje"]="Tu cuenta ha sido verificada correctamente";

        $data["viajes"] = $this->vuelosModel->getVuelos();

        echo $this->printer->render("HomeView.html", $data);
    }

    
}

