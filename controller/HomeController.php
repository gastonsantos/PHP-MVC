<?php

class HomeController{

    private $printer;

    private $vuelosModel;
    public function __construct( $printer, $vuelosModel){
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    function show(){


        if(!isset($_SESSION["logueado"]) || (isset($_SESSION["logueado"]) && !$_SESSION["logueado"] )){
        
            $data["viajes"] = $this->vuelosModel->getVuelos();
            
            echo $this->printer->render("homeView.html", $data);
            exit();
           } 
             else if (isset($_SESSION["esAdmin"])) {
    
                $data["esAdmin"] = $_SESSION["esAdmin"];
                $data["viajes"] = $this->vuelosModel->getVuelos();
                echo $this->printer->render("homeView.html", $data);
                exit();
            }else if(isset($_SESSION["esClient"])){
            
                $data["esClient"] = $_SESSION["esClient"];
                $data["viajes"] = $this->vuelosModel->getVuelos();
                echo $this->printer->render("homeView.html", $data);
                exit();
            } 
        }
        
        
      
    }


