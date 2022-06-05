<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getVuelos() {

        return $this->database->query('SELECT * FROM vuelo');

    }

    public function buscarVuelos($origen, $destino, $fecha) {


        $sql = "SELECT  * FROM vuelo where lugar_partida = '$origen' and destino = '$destino' and fecha_partida = '$fecha'";

        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function updateCapacity($id, $quantity) {
        $sql = "UPDATE vuelo SET capacidad = capacidad - $quantity WHERE id = $id";

        $this->database->query($sql);
    }

    public function getVueloById($id) {
        $sql = "SELECT * FROM vuelo WHERE id = $id";


        return $this->database->query($sql);
    }

}

