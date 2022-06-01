<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once ("helper/Navigation.php");

include_once('controller/HomeController.php');
include_once('controller/UsuarioController.php');
include_once('controller/VuelosController.php');



include_once('model/UsuarioModel.php');
include_once('model/VuelosModel.php');


require_once('third-party/mustache/src/Mustache/Autoloader.php');

include_once("validators/UserValidator.php");

class Configuration {

    public function getVuelosController(){

        return new VuelosController( $this->getPrinter(),$this->getVuelosModel());
    }

    private function getVuelosModel(){
        return new VuelosModel($this->getDatabase());
    }


    public function getHomeController() {
        return new HomeController($this->getPrinter(), $this->getVuelosModel());
    }

    public function getUsuarioController() {
        return new UsuarioController($this->getUsuarioModel(), $this->getPrinter(), new UserValidator(), $this->getVuelosModel());

    }

    private function getUsuarioModel() {
        return new UsuarioModel($this->getDatabase());
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

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "show");
    }
}