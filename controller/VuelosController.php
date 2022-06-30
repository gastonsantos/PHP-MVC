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
            $data["esClient"] = $_SESSION["esClient"];
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
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
            $viajes = $this->filtrarBusqueda($origen,$destino,$fecha);
            if(empty($viajes) ){
                $data["error"] = "No se encontro resultado";//No se encontro resultado cartelito

                echo $this->printer->render("HomeView.html", $data);

            }else{
               $data["error"] = false;
                
               //SUBORBITALES
               $data["suborbitales"]=$this->suborbitales($viajes);

               //CIRCUITO 1 BAJA ACELERACION
               $data["circuito1BA"]=$this->circuito1BA($viajes);
              
               $horario = $this->calculoHorasCircuito1BA($destino);
               $data["horarioC1BA"] = $this->formatoHoras($horario);

               //CIRCUITO 1 ALTA ACELERACION
               $data["circuito1AA"]=$this->circuito1AA($viajes);

               $horario = $this->calculoHorasCircuito1AA($destino);
               $data["horarioC1AA"] = $this->formatoHoras($horario);

               //PARADAS CIRCUITO 1
               $data["circuito1Paradas"]=$this->paradasCircuito1($destino);


               //CIRCUITO 2 BAJA ACELERACION
               $data["circuito2BA"]=$this->circuito2BA($viajes);

               $horario = $this->calculoHorasCircuito2BA($destino);
               $data["horarioC2BA"] = $this->formatoHoras($horario);

               //CIRCUITO 2 ALTA ACELERACION
               $horario = $this->calculoHorasCircuito2AA($destino);
               $data["horarioC2AA"] = $this->formatoHoras($horario);

               $data["circuito2Paradas"]=$this->paradasCircuito2($destino);

               $data["lugares"] = $this->vuelosModel->getLugares();

               $data["tu_destino"]=$destino;

               echo $this->printer->render("homeView.html", $data);

            }
        }
   
    }

    //OPCIONES DE VUELOS
    public function filtrarBusqueda($origen,$destino,$fecha){
        $posiblesVuelos=[];
        $vuelos = $this->vuelosModel->getVuelosTest();

        foreach ($vuelos as $vuelo) {
            $fecha_partida = date("Y-m-d", strtotime($vuelo["fecha_partida"]));
            //Preguntar si el vuelo cumple con origen, destino y fecha buscada
            if($vuelo["lugar_partida"]== $origen && $vuelo["destino"] == $destino && $fecha_partida == $fecha){
                array_push($posiblesVuelos,$vuelo);
            }else{
                //Si vuelo no coincide con origen, destino ingresado
                //Pregunto si vuelo tiene paradas
                if($vuelo["parada"] != null){
                    //Busco el destino del vuelo en el string de paradas
                    $posicion = strrpos($vuelo["parada"],$vuelo["destino"]);
                    //corto la cadena de paradas en la posicion donde esta el destino
                    $parada = substr($vuelo["parada"],0,$posicion);
                    //Pregunto si el origen del vuelo es igual a el buscado o si existe el origen en las paradas
                    if(($vuelo["lugar_partida"] == $origen || strrpos($parada,$origen) !== false) && $fecha_partida == $fecha){ 
                        //si en las paradas esta el destino                     
                        if(strrpos($parada,$destino)!== false){
                            //agrego posible vuelo
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
            if($viaje["id_tipo_viaje"] == 1){
                array_push($suborbitales,$viaje);
            }
        }

        return $suborbitales;
    }

    public function circuito1BA($viajes){
        $circuito1BA=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 2 && $viaje["id_tipo_equipo"] == 2){
                array_push($circuito1BA,$viaje);
            }
        }

        return $circuito1BA;
    }

    public function circuito1AA($viajes){
        $circuito1AA=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 2 && $viaje["id_tipo_equipo"] == 3){
                array_push($circuito1AA,$viaje);
            }
        }

        return $circuito1AA;
    }


    public function circuito2BA($viajes){
        $circuito2BA=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 3 && $viaje["id_tipo_equipo"] == 2){
                array_push($circuito2BA,$viaje);
            }
        }

        return $circuito2BA;
    }

    public function circuito2AA($viajes){
        $circuito2AA=[];

        foreach($viajes as $viaje){
            if($viaje["id_tipo_viaje"] == 3 && $viaje["id_tipo_equipo"] == 3){
                array_push($circuito2AA,$viaje);
            }
        }

        return $circuito2AA;
    }

    //SEPARANDO PARADAS Y CORTANDOLAS EN EL DESTINO
    public function paradasCircuito1($destino){
        $paradas=[];
        $escala=[];

        $circuito1 = $this->vuelosModel->getParadascircuito1()[0];

        //convierto el array de paradas en cadena de string
        $string = implode(",",$circuito1); 
        //pongo parada por parada en una posicion de array
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

    //SUMANDO HORAS DE LOS CIRCUITOS DEPENDIENDO SU TIPO DE EQUIPO Y CANTIDAD DE PARADAS
    public function calculoHorasCircuito1BA($destino){
        $horario=0;
        $paradas = $this->paradasCircuito1($destino);

        if($paradas != null){

        $horarios = $this->vuelosModel->getTransitoCircuito1BA()[0];

        
        $string = implode(",",$horarios); 
        $horariosArray = explode( ',', $string);

        for ($i=0; $i<count($paradas) ; $i++) { 
            $horario+=$horariosArray[$i];
        }

        return $horario;
        }

        return null;
    }

    public function calculoHorasCircuito1AA($destino){
        $horario=0;
        $paradas = $this->paradasCircuito1($destino);

        if($paradas != null){

        $horarios = $this->vuelosModel->getTransitoCircuito1AA()[0];

        
        $string = implode(",",$horarios); 
        $horariosArray = explode( ',', $string);

        for ($i=0; $i<count($paradas) ; $i++) { 
            $horario+=$horariosArray[$i];
        }

        return $horario;
        }

        return null;
    }

    public function calculoHorasCircuito2BA($destino){
        $horario=0;
        $paradas = $this->paradasCircuito2($destino);

        if($paradas != null){
        $horarios = $this->vuelosModel->getTransitoCircuito2BA()[0];

        
        $string = implode(",",$horarios); 
        $horariosArray = explode( ',', $string);

        for ($i=0; $i<count($paradas) ; $i++) { 
            $horario+=$horariosArray[$i];
        }

        return $horario;
        }

        return null;
    }

    public function calculoHorasCircuito2AA($destino){
        $horario=0;
        $paradas = $this->paradasCircuito2($destino);

        
        if($paradas != null){
        $horarios = $this->vuelosModel->getTransitoCircuito2AA()[0];

        
        $string = implode(",",$horarios); 
        $horariosArray = explode( ',', $string);

        for ($i=0; $i<count($paradas) ; $i++) { 
            $horario+=$horariosArray[$i];
        }

        return $horario;
        }
        
        return null;
    }

    //DANDO FORMATO AL HORARIO TOTAL DE HORAS
    public function formatoHoras($horario){

        $hora = $horario;
        $dias=0;
    
        while($hora > 24){
            $hora = $hora - 24;
            $dias++;
        }

        $cadena = (($dias>0)? $dias . " dias " : "" ). $hora . " horas ";

        return $cadena;
    }

    public function horaDeLlegadaPuntoOrigen($origen){



    }


//FUNCIONES DE ADMIN

public function filtrarVuelo(){
    if (!$_SESSION["esAdmin"]) {
        Navigation::redirectTo("/home");
    } 

    $origen = $_POST["origen"];
    $destino = $_POST["destino"];
    $fecha = $_POST["fecha"];

 
    $data["viajes"]=$this->vuelosModel->buscarVuelos($origen,$destino,$fecha);

    if(empty($data["viajes"])){
        $data["error"]="No se encontro resultado";
    }

    $data["esAdmin"] = true;
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];

    $data["lugares"] = $this->vuelosModel->getLugares();

    echo $this->printer->render("homeView.html", $data);

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


    if ($capacidad == "" || $fecha_partida == "" || $hora == "" || $lugar_partida == "" || $destino == "" || $precio == "" || $id_tipo_equipo == "" || $id_tipo_viaje == "" || $id_tipo_cabina == "" || $fecha_partida < date("Y-m-d") ) {
        
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

public function showModificar(){
    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"])) {
        Navigation::redirectTo("/home");
    }

    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $id=$_GET["id_vuelo"];
    $data["vueloModificar"]= $this->vuelosModel->getVueloById($id);
    $data["lugares"]= $this->vuelosModel->getLugares();
    $data["equipos"]= $this->vuelosModel->getTipoEquipo();
    $data["viajes"]= $this->vuelosModel->getTipoViaje();
    $data["cabinas"]= $this->vuelosModel->getTipoCabina();

    echo $this->printer->render("modificarView.html", $data);

}

public function modificarVuelo(){
    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"])) {
        Navigation::redirectTo("/home");
    }

    $id = $_GET["id_vuelo"];

    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $data["viajes"] = $this->vuelosModel->getVuelos();

    $capacidad = $_POST["actualizarCapacidad"];
    $fecha_partida= $_POST["actualizarFecha"];
    $hora = $_POST["actualizarHora"];
    $lugar_partida = $_POST["actualizarPartida"];
    $destino= $_POST["actualizarDestino"];
    $precio = $_POST["actualizarPrecio"];
    $id_tipo_equipo= $_POST["actualizarEquipo"];
    $id_tipo_viaje= $_POST["actualizarViaje"];
    $id_tipo_cabina=$_POST["actualizarCabina"];

    $this->vuelosModel->updateVuelo($id,$capacidad,$fecha_partida,$hora,$lugar_partida,$destino,$precio,$id_tipo_equipo,$id_tipo_viaje,$id_tipo_cabina);

    Navigation::redirectTo("/home");

}

}