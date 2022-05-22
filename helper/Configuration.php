<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/HomeController.php');
include_once('controller/SesionController.php');
include_once('controller/UsuarioController.php');
include_once('controller/BuscadorController.php');
include_once('model/SesionModel.php');
include_once('model/UsuarioModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');
include_once("validators/UserValidator.php");
include_once('model/VuelosModel.php');

class Configuration {
    public function getHomeController() {
        return new HomeController($this->getPrinter(), $this->getVuelosModel());
        
    }

    public function getBuscadorController(){
        return new BuscadorController($this->getPrinter(), $this->getVuelosModel());

    }
    private function getVuelosModel(){
        return new VuelosModel($this->getDatabase());
    }

    public function getSesionController() {
        return new SesionController($this->getSesionModel(), $this->getPrinter(), $this->getVuelosModel());
    }

    private function getSesionModel() {
        return new SesionModel($this->getDatabase());
    }

    public function getUsuarioController() {
        return new UsuarioController($this->getUsuarioModel(), $this->getPrinter(),  new UserValidator());
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
           // $dbConfig["port"]
        );
    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "show");
    }
}