<?php
class Configuration{

    private $config;
  


    //Para que se ve el home

    public function createHomeController(){
        require_once("controller/HomeController.php");
        return new HomeController($this->createPrinter());
    }




    private  function getDatabase(){
        require_once("helpers/MyDatabase.php");
        $config = $this->getConfig();
        return new MyDatabase($config["servername"], $config["username"], $config["password"], $config["dbname"]);
    }

    private  function getConfig(){
        if( is_null( $this->config ))
            $this->config = parse_ini_file("config/config.ini");

        return  $this->config;
    }

    /*
    private function getLogger(){
        require_once("helpers/Logger.php");
        return new Logger();
    }
    */

    public function createRouter($defaultController, $defaultAction){
        include_once("helpers/Router.php");
        return new Router($this,$defaultController,$defaultAction);
    }

    private function createPrinter(){
        require_once ('third-party/mustache/src/Mustache/Autoloader.php');
        require_once("helpers/MustachePrinter.php");
        return new MustachePrinter("view/partials");
    }
/*
    public static function getPHPMailer(){
        include_once("helpers/PHPMailerGmail.php");
        $email="mailEmpresa@gmail.com";
        $pass="Dni33022376";
        return new PHPMailerGmail($email, $pass);
    }
    */

}