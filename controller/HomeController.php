<?php

class HomeController{

    private $printer;
    private $vuelosModel;
    public function __construct( $printer, $vuelosModel){
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        
    }

    public  function show(){

        $data["viajes"] = $this->vuelosModel->getVuelos();
        echo $this->printer->render("homeView.html", $data);
    }

  


}