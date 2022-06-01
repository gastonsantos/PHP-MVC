<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
   
    

    public function __construct($printer, $vuelosModel) {
        $this->printer = $printer;
        
        $this->vuelosModel = $vuelosModel;

    }

    public function show() {

        echo $this->printer->render("registroView.html");
    }
    public function buscarVuelos (){
        $buscar = $_POST["buscar"];

        if(!isset($_SESSION["logueado"]) || (isset($_SESSION["logueado"]) && !$_SESSION["logueado"] )){
            $data["esNada"] = "esNada";   
            $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
            
            echo $this->printer->render("homeView.html", $data);
            exit();
           } 
             else if (isset($_SESSION["esAdmin"])) {
    
                $data["esAdmin"] = $_SESSION["esAdmin"];
                $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
                echo $this->printer->render("homeView.html", $data);
                exit();
            }else if(isset($_SESSION["esClient"])){
            
                $data["esClient"] = $_SESSION["esClient"];
                $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
                echo $this->printer->render("homeView.html", $data);
                exit();
            }

    }

  


}

