<?php

class ReservatorController {
    private $printer;
    private $userModel;
    private $reservatorModel;
    private $centroMedicoModel;

    public function __construct($printer, $reservatorModel, $userModel, $centroMedicoModel) {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
        $this->userModel = $userModel;
        $this->centroMedicoModel = $centroMedicoModel;
    }


    public function volverReservator(){
       
            Navigation::redirectTo("/home");
    }



    
    public function showForm() {
        if (!$_SESSION["esClient"]  ) {
            Navigation::redirectTo("/home");
        }

        
        $data["usuario"] = $_SESSION["nombre"];
        $id_user = $_SESSION["id"];
        $idVuelo = (int)$_GET["id_vuelo"];

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

        echo  $this->printer->render("misReservas.mustache", $data);
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

    public function cancelarReserva(){
        $id_reserva = $_POST["reserva"];
        $this->reservatorModel->deleteReserva($id_reserva);

        $reserves = $this->reservatorModel->getReservesByUser($_SESSION["id"]);
        
        $data["reserves"] = $reserves;
        $data["existsReserves"] = sizeof($reserves) > 0;
        $data["esClient"] = $_SESSION["esClient"];
        $data["nombre"] = $_SESSION["nombre"];

        echo  $this->printer->render("misReservas.mustache", $data);
    }
}
