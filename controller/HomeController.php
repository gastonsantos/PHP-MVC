<?php

class HomeController{

    private $printer;

    private $vuelosModel;
    public function __construct( $printer, $vuelosModel){
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    function show(){
         if (isset($_SESSION["esClient"])) {
            $data["esClient"] = true;
            
        }else if(isset($_SESSION["esAdmin"])){
            $data["esAdmin"] = true;
        }

        
        $data["viajes"] = $this->vuelosModel->getVuelos();
        
        echo $this->printer->render("homeView.html", $data);
      
      
    }
}


