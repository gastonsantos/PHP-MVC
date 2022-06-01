<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
   
    

    public function __construct($printer, $vuelosModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    public function buscarVuelos (){
        $buscar = $_POST["buscar"];
        if($buscar == ""){
            $data["vacio"] = true;//Esta vacio Cartelito

            echo $this->printer->render("HomeView.html", $data);
        }else{
            $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
            if(empty($data["viajes"]) ){
                $data["error"] = true;//No se encontro resultado cartelito
            }else{
                $data["error"] = false;
            }
    
            echo $this->printer->render("HomeView.html", $data);
        }
   
    }
}

