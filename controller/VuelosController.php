<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
   
    

    public function __construct($printer, $vuelosModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    public function buscarVuelos (){
        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fecha = $_POST["fecha"];

        if($origen == "" && $destino == "" && $fecha == ""){
            $data["vacio"] = true;//Esta vacio Cartelito

            echo $this->printer->render("HomeView.html", $data);
        }else{
            $data["viajes"] = $this->vuelosModel->buscarVuelos($origen,$destino,$fecha);
            if(empty($data["viajes"]) ){
                $data["error"] = true;//No se encontro resultado cartelito
            }else{
                $data["error"] = false;
            }
    
            echo $this->printer->render("HomeView.html", $data);
        }
   
    }
}

