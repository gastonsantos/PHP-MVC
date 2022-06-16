<?php

class ChequeoController {
    private $printer;
    private $centroMedicoModel;
    
    

  

    public function __construct($printer, $centroMedicoModel) {
        $this->printer = $printer;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    public function show() {

        if (!$_SESSION["esClient"] || !isset($_SESSION["esClient"]) || $_SESSION["esClient"]== "" ) {
            Navigation::redirectTo("/home");
        } 
           
            
                
                $data["esClient"] = $_SESSION["esClient"];
                $data["nombre"] = $_SESSION["nombre"];
                $data["id"] = $_SESSION["id"];
                $data["centros"] = $this->centroMedicoModel->getCentrosMedico();  
               // $data["turnos"] = $this->centroMedicoModel->turnosRestantes($data["centros"][0]["id"]);
                $data["fecha"] = $this->centroMedicoModel->fechaHoy();
                echo $this->printer->render("centrosMedicosView.html", $data);
               
          
        

       
    }

    public function centroMedico(){
        if (!$_SESSION["esClient"] || !isset($_SESSION["esClient"]) || $_SESSION["esClient"]== "" ) {
            Navigation::redirectTo("/home");
        } 
            $id_centro = $_GET["id_Centro"];
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];
            $data["centro"] = $this->centroMedicoModel->getCentroMedico($id_centro); 
           // $data["turno"] = $this->centroMedicoModel->sinTurnos($id_centro); 
            $data["fecha"] = $this->centroMedicoModel->fechaHoy();
            echo $this->printer->render("centroMedicoView.html", $data);
            exit();
        
       

    }

    public function chequeoMedico(){

        if (!$_SESSION["esClient"] || !isset($_SESSION["esClient"]) || $_SESSION["esClient"]== "" ) {
            Navigation::redirectTo("/home");
        } 
                
            $idCentro = (int)$_GET["id_Centro"];
            $fecha = $_GET["fecha"];

            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["nombre"] = $_SESSION["nombre"];
           

            $data["chequeo"] = $this->centroMedicoModel->insertChequeo($idCentro, $_SESSION["id"], $fecha);

            echo $this->printer->render("resultadoChequeoMedicoView.html", $data);
            exit();
       

       


    }


    

}