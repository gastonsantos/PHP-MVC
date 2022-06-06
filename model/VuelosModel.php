<?php

class VuelosModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getVuelos() {

        return $this->database->query('SELECT * FROM vuelo');

    }

    public function buscarVuelos($origen,$destino,$fecha) {
        $sql = "SELECT  *,DATE_FORMAT(fecha_partida, '%d-%m-%Y') fecha_partida,DATE_FORMAT(hora, '%H:%i') hora FROM vuelo join tipo_viaje on vuelo.id_tipo_viaje = tipo_viaje.id where lugar_partida = '$origen' and destino = '$destino' and fecha_partida = '$fecha'";

        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getParadasCircuito1(){
        $sql = "SELECT parada FROM circuito1 join tipo_equipo on circuito1.id_tipo_equipo = tipo_equipo.id where tipo_equipo.nombre = 'BA'";
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getParadasCircuito2(){
        $sql = "SELECT parada FROM circuito2 join tipo_equipo on circuito2.id_tipo_equipo = tipo_equipo.id where tipo_equipo.nombre = 'BA'";
        $resultado = $this->database->query($sql);
        return $resultado;
    }

    public function getLugares(){
        $sql = "SELECT * FROM lugares";
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

