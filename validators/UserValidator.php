<?php
include_once("exceptions/ValidationException.php");

class UserValidator {
    private $emailRegex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
    private $passwordRegex = '/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/';

    public function validateUserToRegister($data) {
        $this->validateText($data['nombre'], "El nombre no puede estar vacio");
        $this->validateText($data['apellido'], "El apellido no puede estar vacio");
        $this->validateText($data['direccion'], "La direccion no puede estar vacia");
        $this->validateEmail($data["email"]);
        $this->validatePassword($data["password"]);
    }

    public function validateUserToLogin($data) {
        $this->validateEmail($data["email"]);
        $this->validatePassword($data["password"]);
    }

    private function validateText($text, $message) {
        if (strlen(trim($text)) < 1)
            throw new ValidationException($message);
    }

    private function validateEmail($email) {
        if (!preg_match($this->emailRegex, $email))
            throw new ValidationException("El email tiene un formato incorrecto");
    }

    private function validatePassword($password) {
        if (!preg_match($this->passwordRegex, $password))
            throw new ValidationException(
                "La contraseña debe tener 8 caracteres, una mayuscula y una minuscula por lo menos"
            );
    }

}