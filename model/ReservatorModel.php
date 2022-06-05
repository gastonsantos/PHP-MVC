<?php

class ReservatorModel {
    private $database;
    private $vuelosModel;

    public function __construct($database, $vuelosModel) {
        $this->database = $database;
        $this->vuelosModel = $vuelosModel;
    }

    public function getReservesByUser($userId) {
        $sql = "SELECT * FROM reserva WHERE id_usuario = $userId";

        return $this->database->query($sql);
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

        $reserveCost = $this->calculateReserveCost($data);

        for ($i = 1; $i <= $reservesMade; $i++) {
            $this->createReserve($data["cabina"], $data["servicio"], $idVuelo, $reserveCost);
        }

        return $reserveCost;
    }

    private function checkVueloExists($idVuelo) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo);

        if (!isset($vuelo) || sizeof($vuelo) == 0) {
            throw new ValidationException("El vuelo que quiere reservar no existe");
        }
    }

    private function checkVueloCapacity($idVuelo, $reservesWanted) {
        $vuelo = $this->vuelosModel->getVueloById($idVuelo)[0];

        $capacity = $vuelo["capacidad"];
        $destino = $vuelo["destino"];


        if ($capacity == 0 || $reservesWanted > $capacity) {
            throw new ValidationException("El vuelo con destino a $destino no tiene mas lugares disponibles");
        }
    }

    private function calculateReserveCost($data) {
        $totalReserves = (int)$data["cantidad"];
        $cabineCost = $this->getCabineByName($data["cabina"])["recargo"];
        $serviceCost = $this->getServiceByName($data["servicio"])["recargo"];
        $vueloCost = (int)$data["vueloCost"];

        return $vueloCost + $totalReserves * ($cabineCost + $serviceCost);
    }

    private function createReserve($cabineName, $serviceName, $idVuelo, $price) {
        $code = uniqid();
        $cabineId = $this->getCabineByName($cabineName)["id"];
        $serviceId = $this->getServiceByName($serviceName)["id"];
        $userId = $_SESSION["id"];

        $sql = "INSERT INTO reserva(codigo, precio, id_vuelo, id_cabina, id_servicio, id_usuario) 
                VALUES ('$code',$price,$idVuelo,$cabineId,$serviceId,$userId)";

        $this->database->query($sql);
    }

    private function getServiceByName($name) {
        $sql = "SELECT * FROM tipo_servicio WHERE nombre = '$name'";

        return $this->database->query($sql)[0];
    }

    private function getCabineByName($name) {
        $sql = "SELECT * FROM tipo_cabina WHERE nombre = '$name'";

        return $this->database->query($sql)[0];
    }

}