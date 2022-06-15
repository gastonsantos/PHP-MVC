<?php

class ReportesController {
    private $printer;
    private $reservatorModel;
    private $pdf;
    

    public function __construct($printer, $reservatorModel, $pdf)   {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
        $this->pdf = $pdf;
        
    }

    public function show() {
        if (!$_SESSION["esAdmin"]) {
            Navigation::redirectTo("/home");
        } 

        $data["esAdmin"] = true;
        $data["grafico"] = $this->reservatorModel->getCabinaMasVendida();
        $data["grafico2"] = $this->reservatorModel->getFacturacionMensual();
        $data["grafico3"] = $this->reservatorModel->getFacturacionByClient();


        echo $this->printer->render("reportesView.mustache", $data);
        }

       
   
        

        
    
}
    
