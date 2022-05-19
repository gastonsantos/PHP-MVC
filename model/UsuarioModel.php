<?php
include_once("exceptions/EntityFoundException.php");

class UsuarioModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function logUser($data) {
        $email = $data["email"];
        $password = md5($data["password"]);

        $userFound = $this->getUser($email, $password)[0];

        if (sizeof($userFound) === 0) {
            throw new EntityNotFoundException("El usuario no existe");
        }

        $_SESSION["rol"] = $userFound["id_rol"];

        return $userFound;
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


    public function getUserByEmail($email) {
        return $this->database->query("SELECT * FROM gaucho_rocket.usuario where email = '$email'");
    }

    public function getUser($email, $password) {
        return $this->database->query("SELECT * FROM usuario where email = '$email' AND password = '$password'");
    }

    public function getUsuarios() {
        return $this->database->query("SELECT * FROM usuario where rol = 'user' and activo = true");
    }

    private function checkUserNotExists($email) {
        $userFound = $this->getUserByEmail($email);

        if (sizeof($userFound) > 0) {
            throw new EntityFoundException("El usuario ya existe");
        }
    }

}
