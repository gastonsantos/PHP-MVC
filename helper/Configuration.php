<?php
include_once('helper/MySqlDatabase.php');
include_once('helper/Router.php');
require_once('helper/MustachePrinter.php');
include_once('controller/HomeController.php');
include_once('controller/SesionController.php');
include_once('controller/UsuarioController.php');
include_once('model/SesionModel.php');
include_once('model/UsuarioModel.php');
require_once('third-party/mustache/src/Mustache/Autoloader.php');

class Configuration {
    public function getHomeController() {
        return new HomeController($this->getPrinter());
    }

    public function getSesionController() {
        return new SesionController($this->getSesionModel(), $this->getPrinter());
    }

    private function getSesionModel(){
        return new SesionModel($this->getDatabase());
    }

    public function getUsuarioController() {
        return new UsuarioController($this->getUsuarioModel(),$this->getPrinter());
    }

    private function getUsuarioModel() {
        return new UsuarioModel($this->getDatabase());
    }

    private function getDatabase() {
       return new MySqlDatabase(
            'localhost',
            'root',
            'Dni33022376',
            'gauchoRocket');

    }

    private function getPrinter() {
        return new MustachePrinter("view");
    }

    public function getRouter() {
        return new Router($this, "getHomeController", "show");
    }
}