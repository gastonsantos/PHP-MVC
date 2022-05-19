<?php

class UsuarioController {
    private $printer;
    private $usuarioModel;
    private $userValidator;

    public function __construct($usuarioModel, $printer, $userValidator) {
        $this->printer = $printer;
        $this->usuarioModel = $usuarioModel;
        $this->userValidator = $userValidator;
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

            if ($_SESSION["rol"] === 1) {
                echo $this->printer->render("adminHomeView.html", $data);
            } else {
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

            // presentacion
            $data["mensaje"] = "Usted ha sido registrado correctamente";

            echo $this->printer->render("HomeView.html", $data);
        } catch (ValidationException|EntityFoundException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("registroView.html", $data);
        }
    }
}

