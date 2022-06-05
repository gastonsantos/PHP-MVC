<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getVuelos() {

        return $this->database->query('SELECT v.id, v.capacidad, v.fecha_partida, v.hora, v.lugar_partida, v.destino, v.precio, te.nombre as equipo, v.id_tipo_viaje, v.id_tipo_cabina 
        from vuelo v join tipo_equipo te on v.id_tipo_equipo = te.id;');

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