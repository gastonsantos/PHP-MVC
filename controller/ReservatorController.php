<?php

class ReservatorController {
    private $printer;
    private $userModel;
    private $reservatorModel;
    private $centroMedicoModel;
    private $checkin;

    public function __construct($printer, $reservatorModel, $userModel, $centroMedicoModel, $checkin) {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
        $this->userModel = $userModel;
        $this->centroMedicoModel = $centroMedicoModel;
        $this->checkin = $checkin;

    }


    public function volverReservator() {

        Navigation::redirectTo("/home");
    }


    public function showForm() {
        if (!$_SESSION["esClient"] || !isset($_GET["id_vuelo"])) {
            Navigation::redirectTo("/home");
        }


        $data["usuario"] = $_SESSION["nombre"];
        $id_user = $_SESSION["id"];
        $idVuelo = (int) $_GET["id_vuelo"];

        $valor = $this->centroMedicoModel->chequeoTipoEquipo($id_user, $idVuelo);


        $data["NoPuedeViajar"] = $valor;
        $data["esClient"] = $_SESSION["esClient"];
        $data["nombre"] = $_SESSION["nombre"];
        $data["id"] = $_SESSION["id"];
        $cabineTypes = $this->reservatorModel->getCabineTypes();
        $servicesTypes = $this->reservatorModel->getServiceTypes();

        $data["id_vuelo"] = $idVuelo;
        $data["cabineTypes"] = $cabineTypes;
        $data["servicesTypes"] = $servicesTypes;
        $data["esClient"] = $_SESSION["esClient"];

        echo $this->printer->render("reserva.mustache", $data);

    }

    public function showReservesByUser() {
        if (!$_SESSION["esClient"]) {
            Navigation::redirectTo("/home");
        }

        $reserves = $this->reservatorModel->getReservesByUser($_SESSION["id"]);

        $data["reserves"] = $reserves;
        $data["existsReserves"] = sizeof($reserves) > 0;
        $data["esClient"] = $_SESSION["esClient"];
        $data["nombre"] = $_SESSION["nombre"];

        echo $this->printer->render("misReservas.mustache", $data);
    }


    public function reserve() {
        try {
            if (!$_SESSION["esClient"]) {
                Navigation::redirectTo("/home");
            }
            $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);
            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];

            $idVuelo = (int)$_GET["id_vuelo"];

            $total = $this->reservatorModel->confirmReserve($_POST, $idVuelo);

            $data["mensaje"] = "Su reserva a sido realizada. El precio final es de: $total creditos";


            echo $this->printer->render("homeView.html", $data);
            exit();
        } catch (ValidationException $exception) {

            $data["error"] = $exception->getMessage();
            $data["usuario"] = $_SESSION["nombre"];
            $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);
            echo $this->printer->render("homeView.html", $data);
        }
    }

    public function cancelarReserva() {
        $id_reserva = $_POST["reserva"];
        $this->reservatorModel->deleteReserva($id_reserva);

        $reserves = $this->reservatorModel->getReservesByUser($_SESSION["id"]);

        $data["reserves"] = $reserves;
        $data["existsReserves"] = sizeof($reserves) > 0;
        $data["esClient"] = $_SESSION["esClient"];
        $data["nombre"] = $_SESSION["nombre"];

        echo $this->printer->render("misReservas.mustache", $data);
    }

    public function cotizarReserva() {
        if (!$_SESSION["esClient"]) {
            Navigation::redirectTo("/home");
        }

        $data["esClient"] = $_SESSION["esClient"];

        $reserveId = $_GET["id_Reserva"];
        $reserva = $this->reservatorModel->getRerservaByReserve($reserveId);
        $exchangeRate = $_POST["cotizar"];

        $data["podra"] = $this->checkin->fechaDePartidaCheck($reserveId);
        $data["reserva"] = $reserva;

        $price = $this->reservatorModel->getExchangeRate($reserva[0]["precio"], $exchangeRate);

        $data["cotizacion"] = "$ " . $exchangeRate . ' ' . $price;

        echo $this->printer->render("checkinReservaView.html", $data);
    }

    public function showPagarView() {
        if (!$_SESSION["esClient"]) {
            Navigation::redirectTo("/home");
        }

        $id_reserva = $_GET["id_Reserva"];
        $data["esClient"] = $_SESSION["esClient"];
        $data["reserva"] = $this->reservatorModel->getRerservaByReserve($id_reserva);

        echo $this->printer->render("pagarView.html", $data);
    }

    public function procesarPago() {
        try {
            if (!$_SESSION["esClient"]) Navigation::redirectTo("/home");

            TarjetaValidator::validateToPay($_POST);

            $data["esClient"] = $_SESSION["esClient"];

            $id_reserva = $_GET["id_Reserva"];

            //$this->reservatorModel->updateReserva($id_reserva);//confirma la reserva
            $data["pago"] = true;
            $data["reserva"] = $this->reservatorModel->getRerservaByReserve($id_reserva);
            $data["podra"] = $this->checkin->fechaDePartidaCheck($id_reserva);
            echo $this->printer->render("checkinReservaView.html", $data);
        } catch (ValidationException $exception) {
            $id_reserva = $_GET["id_Reserva"];
            $data["esClient"] = $_SESSION["esClient"];
            $data["reserva"] = $this->reservatorModel->getRerservaByReserve($id_reserva);
            $data["message"] = $exception->getMessage();
            echo $this->printer->render("pagarView.html", $data);
        }

    }
}
