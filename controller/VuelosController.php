<?php

use Sabberworm\CSS\Value\Size;
use Sabberworm\CSS\Value\Value;

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
               $data["circuito1ParadaBA"]=$this->paradasCircuito1($destino);

               $data["circuito2"]=$this->circuito2($viajes);
               $data["circuito2ParadaBA"]=$this->paradasCircuito2($destino);


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
<<<<<<< HEAD
           
=======
>>>>>>> 726bb81f02f4b1ae7dc384f04ca2d764094cdb73
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
            if($viaje["nombre"] == "Circuito1" && $viajes["id_tipo_equipo"] = 2){
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
    public function paradasCircuito1($destino){
        $paradas=[];
        $escala=[];

        $circuito1 = $this->vuelosModel->getParadascircuito1()[0];

        $string = implode(",",$circuito1); 
        $paradas = explode( ',', $string);

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

    public function paradasCircuito2($destino){
        $paradas=[];
        $escala=[];

        $circuito2 = $this->vuelosModel->getParadasCircuito2()[0];

        $string = implode(",",$circuito2); 
        $paradas = explode( ',', $string);

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

    public function calculoHorasCircuito1BA($destino){
        $horario=0;
        $paradas = $this->paradasCircuito1($destino);

        $horarios = $this->vuelosModel->getTransitoCircuito1BA()[0];

        
        $string = implode(",",$horarios); 
        $horariosArray = explode( ',', $string);

        for ($i=0; $i<count($paradas) ; $i++) { 
            $horario+=$horariosArray[$i];
        }

        return $horario;
    }


public function formVuelos(){
    if (!$_SESSION["esAdmin"]) {
        Navigation::redirectTo("/home");
    } 
    $data["esAdmin"] = true;
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
  

    echo $this->printer->render("agregarVueloView.html", $data);

}

public function addVuelo(){
    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"])) {
        Navigation::redirectTo("/home");
    } 
    
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $capacidad = $_POST["capacidad"];
    $fecha_partida = $_POST["fecha_partida"];
    $hora = $_POST["hora"];
    $lugar_partida = $_POST["lugar_partida"];
    $destino = $_POST["destino"];
    $precio = $_POST["precio"];
    $id_tipo_equipo = $_POST["id_tipo_equipo"];
    $id_tipo_viaje = $_POST["id_tipo_viaje"];
    $id_tipo_cabina = $_POST["id_tipo_cabina"];


    if ($capacidad == "" || $fecha_partida == "" || $hora == "" || $lugar_partida == "" || $destino == "" || $precio == "" || $id_tipo_equipo == "" || $id_tipo_viaje == "" || $id_tipo_cabina == "" || $fecha_partida <= date("Y-m-d") ) {
        
        $data["error"] = "Todos los campos son obligatorios y la fecha debe ser mayor a la actual";
        echo $this->printer->render("agregarVueloView.html", $data);
        exit();
    } else {
        $this->vuelosModel->agregarVuelo($capacidad,$fecha_partida,$hora,$lugar_partida,$destino,$precio,$id_tipo_equipo,$id_tipo_viaje,$id_tipo_cabina);       
        
        $data["error"] = false;
        $data["viajes"] = $this->vuelosModel->getVuelos();
        $data["mensaje"] = "Vuelo Agregado Correctamente";

        echo $this->printer->render("homeView.html", $data);
    }
}

public function deleteVuelo(){

    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"])) {
        Navigation::redirectTo("/home");
    }

    $id = $_GET["id_Vuelo"];
    
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $this->vuelosModel->deleteVuelo($id);
    
    $data["viajes"] = $this->vuelosModel->getVuelos();

    $data["mensaje"] = "Se ha Eliminado Correctamente el Vuelo";

    echo $this->printer->render("homeView.html", $data);

}

}