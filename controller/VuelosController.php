<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
   
    

    public function __construct($printer, $vuelosModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;

    }

    public function buscarVuelos (){
        if (isset($_SESSION["esClient"])) {
            $data["esClient"] = true;
        }

        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fecha = $_POST["fecha"];

        if($origen == "" && $destino == "" && $fecha == ""){
            $data["vacio"] = true;//Esta vacio Cartelito

            echo $this->printer->render("HomeView.html", $data);
        }else{
            $viajes = $this->vuelosModel->buscarVuelos($origen,$destino,$fecha);
            $data["viajes"] = $viajes;
            if(empty($data["viajes"]) ){
                $data["error"] = "No se encontro resultado";//No se encontro resultado cartelito
                echo $this->printer->render("HomeView.html", $data);

            }else{
                $data["error"] = false;
                
                $data["suborbitales"]=$this->suborbitales($viajes);

                $data["circuito1"]=$this->circuito1($viajes);
                $data["circuito1ParadaBA"]=$this->circuito1Paradas($destino);

                $data["circuito2"]=$this->circuito2($viajes);
                $data["circuito2ParadaBA"]=$this->circuito2Paradas($destino);

                echo $this->printer->render("homeView.html", $data);

            }
        }
   
    }

    //FILTRO DE VIAJES
    public function suborbitales($viajes){
        $suborbitales=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 1){
                array_push($suborbitales,$viaje);
            }
        }

        return $suborbitales;
    }

    public function circuito1($viajes){
        $circuito1=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 2){
                array_push($circuito1,$viaje);
            }
        }

        return $circuito1;
    }

    public function circuito2($viajes){
        $circuito2=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 3){
                array_push($circuito2,$viaje);
            }
        }

        return $circuito2;
    }

    public function circuito1Paradas($destino){
        $paradas=[];

        //obteniendo array con texto
        $parada = $this->vuelosModel->getParadasCircuito1();

        //convirtiendo array en string
        $string = implode(",", $parada[0]);
       
        //separando string por coma y convirtiendo en nuevo array
        $paradas = explode( ',', $string);

        $escala=[];

        foreach($paradas as $parada){
            if($parada != $destino){
             array_push($escala,$parada);
            }else{
                return $escala;
            }
        }




    }

    public function circuito2Paradas($destino){
        $paradas=[];

        $parada = $this->vuelosModel->getParadasCircuito2();

        $string = implode(",", $parada[0]);
       
        $paradas = explode( ',', $string);

        $escala=[];

        foreach($paradas as $parada){
            if($parada != $destino){
                array_push($escala,$parada);
            }else{
                return $escala;
            }
        }
    }

}


