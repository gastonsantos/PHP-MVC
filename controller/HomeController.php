<?php

class HomeController {
    private $printer;
    private $vuelosModel;
    private $centroMedicoModel;

    public function __construct($printer, $vuelosModel,$centroMedicoModel ) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    function show() {
        if (isset($_SESSION["esClient"])) {
            $data["esClient"] = true;
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);

            
        }
    else if(isset($_SESSION["esAdmin"])){
        $data["esAdmin"] = true;
       
    }

        $data["viajes"] = $this->vuelosModel->getVuelos();
        $data["lugares"] = $this->vuelosModel->getLugares();
        

        echo $this->printer->render("homeView.html", $data);
    }
}
    


