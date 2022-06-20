<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once ("helper/Navigation.php");
require_once ('helper/PDF.php');
require_once ('helper/QR.php');



include_once('controller/HomeController.php');
include_once('controller/UsuarioController.php');
include_once('controller/VuelosController.php');
include_once ("controller/ReservatorController.php");
include_once('controller/ChequeoController.php');
include_once('controller/ReportesController.php');
include_once('controller/CheckinController.php');

include_once('model/UsuarioModel.php');
include_once('model/VuelosModel.php');
include_once ("model/ReservatorModel.php");
include_once('model/CentroMedicoModel.php');
include_once('model/CheckinModel.php');

require_once('third-party/mustache/src/Mustache/Autoloader.php');
require_once('third-party/dompdf/autoload.inc.php');
require_once('third-party/phpqrcode/qrlib.php');

include_once("validators/UserValidator.php");

class Configuration {


    public function getCheckinController(){
        return new CheckinController($this->getPrinter(),$this->getReservatorModel(), $this->getPDF(), $this->getCheckinModel(), $this->getQR());
    }
    public function getCentroMedicoModel(){
        return new CentroMedicoModel($this->getDatabase());
    }
    public function getChequeoController(){
        return new ChequeoController($this->getPrinter(),$this->getCentroMedicoModel());
    }

    public function getVuelosController(){

        return new VuelosController( $this->getPrinter(),$this->getVuelosModel(), $this->getCentroMedicoModel());
    }

    public function getReportesController(){

        return new ReportesController($this->getPrinter(),$this->getReservatorModel());
    }

    private function getVuelosModel(){
        return new VuelosModel($this->getDatabase());
    }
   

    public function getHomeController() {
        return new HomeController($this->getPrinter(), $this->getVuelosModel(),  $this->getCentroMedicoModel());
    }

    public function getUsuarioController() {
        return new UsuarioController($this->getUsuarioModel(), $this->getPrinter(), new UserValidator(), $this->getVuelosModel(), $this->getCentroMedicoModel());

    }

    public function getReservatorController() {
        return new ReservatorController($this->getPrinter(), $this->getReservatorModel(), $this->getUsuarioModel(), $this->getCentroMedicoModel());
    }

    private function getCheckinModel(){
        return new CheckinModel();
    }
    private function getUsuarioModel() {
        return new UsuarioModel($this->getDatabase());
    }

    private function getReservatorModel() {
        return new ReservatorModel($this->getDatabase(), $this->getVuelosModel());
    }

    private function getDatabase() {
        $dbConfig = parse_ini_file("config.ini");

        return new MySqlDatabase(
            $dbConfig["host"],
            $dbConfig["usuario"],
            $dbConfig["clave"],
            $dbConfig["base"],
            $dbConfig["port"]
        );
    }

    public function getQR(){
        return new QRcode();
    }

    public function getPDF(){
        
        return new PDF();
    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "show");
    }
}