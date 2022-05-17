<?php

class SesionModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getSesion($email, $password) {

        return $this->database->query('SELECT * FROM gaucho_rocket.usuario WHERE email = "'.$email.'" AND password = "'.$password.'" and activo = 1');

    }

}
