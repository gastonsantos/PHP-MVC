<?php

class ReservatorModel {
    private $database;
    private $vuelosModel;

    public function __construct($database, $vuelosModel) {
        $this->database = $database;
        $this->vuelosModel = $vuelosModel;
    }

    public function getCabineTypes() {
        $sql = "SELECT * FROM tipo_cabina";

        return $this->database->query($sql);
    }

    public function getServiceTypes() {
        $sql = "SELECT * FROM tipo_servicio";

        return $this->database->query($sql);
    }

    public function confirmReserve($data, $idVuelo) {
        $this->checkVueloExists($idVuelo);

        $reservesMade = (int)$data["cantidad"];

        $this->checkVueloCapacity($idVuelo, $reservesMade);

        $this->vuelosModel->updateCapacity($idVuelo, $reservesMade);

        $getPriceQuery = "SELECT precio FROM vuelo WHERE id = $idVuelo";

        $vueloCost = $this->database->query($getPriceQuery)[0]["precio"];

        $data["vueloCost"] = $vueloCost;

        return $this->calculateReserveCost($data);
    }

    private function checkVueloCapacity($idVuelo, $reservesWanted) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo)[0];

        $capacity = $vuelo["capacidad"];
        $destino = $vuelo["destino"];


        if ($capacity == 0 || $reservesWanted > $capacity) {
            throw new ValidationException("El vuelo con destino a $destino no tiene mas lugares disponibles");
        }
    }

    private function checkVueloExists($idVuelo) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo);

        if (!isset($vuelo) || sizeof($vuelo) == 0) {
            throw new ValidationException("El vuelo que quiere reservar no existe");
        }
    }

    private function calculateReserveCost($data) {
        $totalReserves = (int)$data["cantidad"];
        $cabineCost = (int)$data["cabina"];
        $serviceCost = (int)$data["servicio"];
        $vueloCost = (int)$data["vueloCost"];

        return $vueloCost + $totalReserves * ($cabineCost + $serviceCost);
    }

}