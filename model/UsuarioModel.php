<?php
include_once("exceptions/EntityFoundException.php");

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

    public function getUserByEmail($email) {
        return $this->database->query("SELECT * FROM gaucho_rocket.usuario where email = '$email'");
    }

    public function agregarUsuario($data) {
        $nombre = $data["nombre"];
        $apellido = $data["apellido"];
        $direccion = $data["direccion"];
        $email = $data["email"];
        $encryptedPassword = md5($data["password"]);
        $id_rol = 1;
        $activo = 1;

        $this->checkUserNotExists($email);

        $sql = "INSERT INTO gaucho_rocket.usuario (nombre, apellido, direccion,email, password, id_rol, activo) 
                VALUES ('$nombre', '$apellido', '$direccion', '$email', '$encryptedPassword', '$id_rol', '$activo')";

        $this->database->query($sql);
    }

    private function checkUserNotExists($email) {
        $userFound = $this->getUserByEmail($email);

        if (sizeof($userFound) > 0) {
            throw new EntityFoundException("El usuario ya existe");
        }
    }

}
