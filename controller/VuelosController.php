<?php

class VuelosController {
    private $printer;
    private $vuelosModel;
    private $centroMedicoModel;
   
    

    public function __construct($printer, $vuelosModel, $centroMedicoModel) {
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    public function buscarVuelos (){
        if (isset($_SESSION["esClient"])) {
            $data["esClient"] = true;
            $data["chequeo"] = $this->centroMedicoModel->getChequeoById($_SESSION["id"]);
            $data["usuario"] = $_SESSION["nombre"];
        }

        $origen = $_POST["origen"];
        $destino = $_POST["destino"];
        $fecha = $_POST["fecha"];
       
        if($origen == "" && $destino == "" && $fecha == ""){
            $data["vacio"] = true;//Esta vacio Cartelito
            
            echo $this->printer->render("HomeView.html", $data);
        }else{
            $viajes = $this->test($origen,$destino);
            if(empty($viajes) ){
                $data["error"] = "No se encontro resultado";//No se encontro resultado cartelito
                echo $this->printer->render("HomeView.html", $data);

            }else{
               $data["error"] = false;
                
               $data["suborbitales"]=$this->suborbitales($viajes);

               $data["circuito1"]=$this->circuito1($viajes);
               $data["circuito1ParadaBA"]=$this->paradasCircuito1($destino,$viajes);

               $data["circuito2"]=$this->circuito2($viajes);
               $data["circuito2ParadaBA"]=$this->paradasCircuito2($destino,$viajes);

                echo $this->printer->render("homeView.html", $data);

            }
        }
   
    }

    //OPCIONES DE VUELOS

    public function test($origen,$destino){
        $posiblesVuelos=[];
        $vuelos = $this->vuelosModel->getVuelosTest();
    
        foreach ($vuelos as $vuelo) {
            if($vuelo["lugar_partida"]== $origen && $vuelo["destino"] == $destino){
                array_push($posiblesVuelos,$vuelo);
            }else{
                if($vuelo["parada"] != null){
                    $posicion = strrpos($vuelo["parada"],$vuelo["destino"]);
                    $parada = substr($vuelo["parada"],0,$posicion);
                    if($vuelo["lugar_partida"] == $origen || strrpos($parada,$origen)){                      
                        if(strrpos($parada,$destino)){
                            array_push($posiblesVuelos,$vuelo);
                        }
                    }
                }    
            }
        }
        return $posiblesVuelos;
    }

    //FILTRO DE VIAJES
    public function suborbitales($viajes){
        $suborbitales=[];

        foreach($viajes as $viaje){
            if($viaje["nombre"] == "Suborbital"){
                array_push($suborbitales,$viaje);
            }
        }

        return $suborbitales;
    }

    public function circuito1($viajes){
        $circuito1=[];

        foreach($viajes as $viaje){
            if($viaje["nombre"] == "Circuito1" ){
                array_push($circuito1,$viaje);
            }
        }

        return $circuito1;
    }

    public function circuito2($viajes){
        $circuito2=[];

        foreach($viajes as $viaje){
            if($viaje["nombre"] == "Circuito2"){
                array_push($circuito2,$viaje);
            }
        }

        return $circuito2;
    }

    //SEPARANDO PARADAS
    public function paradasCircuito1($destino,$viajes){
        $paradas=[];
        $escala=[];

        $circuito1 = $this->circuito1($viajes);

        if($circuito1 != null){

        //obteniendo string de paradas
        $parada = $circuito1[0]["parada"];

        //separando string por coma y convirtiendo en nuevo array
        $paradas = explode( ',', $parada);

        foreach($paradas as $parada){
            if($parada != $destino){
                array_push($escala,$parada);
            }else{
                if($parada == $destino)
                array_push($escala,$parada);
                return $escala;
            }
        }
    }
    return null;

    }

    public function paradasCircuito2($destino,$viajes){
        $paradas=[];
        $escala=[];

        $circuito2 = $this->circuito2($viajes);

        if($circuito2 != null){

        //obteniendo string de paradas
        $parada = $circuito2[0]["parada"];

        $paradas = explode( ',', $parada);

        foreach($paradas as $parada){
            if($parada != $destino){
                array_push($escala,$parada);
            }else{
                if($parada == $destino)
                array_push($escala,$parada);
                return $escala;
            }
        }
    }

    return null;
}

}