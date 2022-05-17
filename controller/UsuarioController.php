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

    public function login() {}

    public function procesarRegistro() {
        try {
            // validaciones
            $this->userValidator->validateUserToRegister($_POST);

            // ejecucion
            $this->usuarioModel->agregarUsuario($_POST);

            // presentacion
            $data["nombre"] = $_POST['nombre'];
            $data["mensaje"]="Usted ha sido registrado corretamente";

            echo $this->printer->render("HomeView.html", $data);
        } catch (ValidationException|EntityFoundException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("registroView.html", $data);
        }
    }

    private function userExists($email): bool {
        return sizeof($this->usuarioModel->getMail($email)) > 0;
    }
}

