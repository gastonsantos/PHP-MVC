<?php

class ReservatorController {
    private $printer;
    private $vuelosModel;
    private $reservatorModel;

    public function __construct($printer, $vuelosModel, $reservatorModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        $this->reservatorModel = $reservatorModel;
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

        echo $this->printer->render("reserva.mustache", $data);
    }


    public function reserve() {
        try {
            if (!$_SESSION["esClient"]) {
                Navigation::redirectTo("index.php?controller=home&method=show");
            }
            
            $idVuelo = (int)$_GET["idVuelo"];

            $total = $this->reservatorModel->confirmReserve($_POST, $idVuelo);

            $data["mensaje"] = "Su reserva a sido confirmada. El precio final es de: $total creditos";

            echo $this->printer->render("homeView.html", $data);
        } catch (ValidationException $exception) {
            $data["error"] = $exception->getMessage();

            echo $this->printer->render("homeView.html", $data);
        }
    }
}