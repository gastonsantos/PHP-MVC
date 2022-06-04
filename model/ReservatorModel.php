<?php

class ReservatorModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
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
        $reservesMade = (int)$data["cantidad"];

        $updateCapacityQuery = "UPDATE vuelo SET capacidad = capacidad - $reservesMade WHERE id = $idVuelo";
        $getPriceQuery = "SELECT precio FROM vuelo WHERE id = $idVuelo";

        $this->database->query($updateCapacityQuery);
        $vueloCost = $this->database->query($getPriceQuery)[0]["precio"];

        $data["vueloCost"] = $vueloCost;

        return $this->calculateReserveCost($data);
    }

    private function calculateReserveCost($data) {
        $totalReserves = (int) $data["cantidad"];
        $cabineCost = (int) $data["cabina"];
        $serviceCost = (int) $data["servicio"];
        $vueloCost = (int) $data["vueloCost"];

        return $vueloCost + $totalReserves * ($cabineCost + $serviceCost);
    }


}