<?php

class UsuarioModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getUsuarios() {
        return $this->database->query("SELECT * FROM usuario where rol = 'user' and activo = true");
    }

    public function getUsuario($id) {
        return $this->database->query("SELECT * FROM usuario where id = '$id'");
    }

    public function getMail($mail) {
        return $this->database->query("SELECT email FROM gaucho_rocket.usuario where email = '$mail'");
    }

    public function agregarUsuario($nombre, $apellido, $direccion, $email, $password, $id_rol, $activo) {
        $encryptedPassword = md5($password);

        $sql = "INSERT INTO gaucho_rocket.usuario (nombre, apellido, direccion,email, password, id_rol, activo) 
                VALUES ('$nombre', '$apellido', '$direccion', '$email', '$encryptedPassword', '$id_rol', '$activo')";


        return $this->database->query($sql);
    }

}
