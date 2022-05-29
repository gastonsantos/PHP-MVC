<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getVuelos() {

        return $this->database->query('SELECT * FROM vuelo');

    }

    public function buscarVuelos($busqueda) {
        if ($busqueda == "") {
            return $this->database->query('SELECT * FROM vuelo');
        }

        $sql = "SELECT  * FROM vuelo where trayecto = '$busqueda' || equipo = '$busqueda' || lugar_partida = '$busqueda'";

        $resultado = $this->database->query($sql);
        return $resultado;
    }

}

