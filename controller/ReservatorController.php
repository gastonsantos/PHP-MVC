<?php

class ReservatorController {
    private $printer;
    private $userModel;
    private $reservatorModel;

    public function __construct($printer, $reservatorModel, $userModel) {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
        $this->userModel = $userModel;
    }

    public function showForm() {
        if (!$_SESSION["esClient"]) {
            Navigation::redirectTo("index.php?controller=home&method=show");
        }

        $idVuelo = (int)$_GET["id_vuelo"];
        $cabineTypes = $this->reservatorModel->getCabineTypes();
        $servicesTypes = $this->reservatorModel->getServiceTypes();

        $data["idVuelo"] = $idVuelo;
        $data["cabineTypes"] = $cabineTypes;
        $data["servicesTypes"] = $servicesTypes;
        $data["esClient"] = $_SESSION["esClient"];

        echo $this->printer->render("reserva.mustache", $data);
    }

    public function showReservesByUser() {
        if (!$_SESSION["esClient"]) {
            Navigation::redirectTo("index.php?controller=home&method=show");
        }



        $reserves = $this->reservatorModel->getReservesByUser($_SESSION["id"]);

        $data["reserves"] = $reserves;
        $data["esClient"] = $_SESSION["esClient"];

        echo  $this->printer->render("misReservas.mustache", $data);
    }


    public function reserve() {
        try {
            if (!$_SESSION["esClient"]) {
                Navigation::redirectTo("index.php?controller=home&method=show");
            }

            $idVuelo = (int)$_GET["idVuelo"];

            $total = $this->reservatorModel->confirmReserve($_POST, $idVuelo);

            $data["mensaje"] = "Su reserva a sido realizada. El precio final es de: $total creditos";
            $data["esClient"] = $_SESSION["esClient"];

            echo $this->printer->render("homeView.html", $data);
        } catch (ValidationException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("homeView.html", $data);
        }
    }
}