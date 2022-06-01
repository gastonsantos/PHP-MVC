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
        return $this->database->query('SELECT * FROM vuelo where lugar_partida like "%' . $busqueda . '%" or trayecto like "%' . $busqueda . '%"');
    }

}

