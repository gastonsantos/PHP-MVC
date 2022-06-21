<?php

class ReportesController {
    private $printer;
    private $reservatorModel;
   
    

    public function __construct($printer, $reservatorModel)   {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
       
        
    }

    public function show() {
        if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
            Navigation::redirectTo("/home");
        } 
        
        $data["nombre"] = $_SESSION["nombre"];
        $data["id"] = $_SESSION["id"];


        $data["esAdmin"] = true;
        $data["grafico"] = $this->reservatorModel->getCabinaMasVendida();
        $data["grafico2"] = $this->reservatorModel->getFacturacionMensual();
        $data["grafico3"] = $this->reservatorModel->getFacturacionByClient();
        $data["grafico4"] = $this->reservatorModel->getTasaDeOcupacionPorViaje();



        echo $this->printer->render("reportesView.mustache", $data);
        }

       
   
        
 
}
    
