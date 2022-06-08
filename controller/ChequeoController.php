<?php

class ChequeoController {
    private $printer;
    private $centroMedicoModel;
    
    

  

    public function __construct($printer, $centroMedicoModel) {
        $this->printer = $printer;
        $this->centroMedicoModel = $centroMedicoModel;

    }

    public function show() {
           
            if(isset($_SESSION["esClient"])){
                
                $data["esClient"] = $_SESSION["esClient"];
                $data["usuario"] = $_SESSION["nombre"];
                $data["centros"] = $this->centroMedicoModel->getCentrosMedico();  
               // $data["turnos"] = $this->centroMedicoModel->turnosRestantes($data["centros"][0]["id"]);
                $data["fecha"] = $this->centroMedicoModel->fechaHoy();
                echo $this->printer->render("centrosMedicosView.html", $data);
                exit();
            } else{
                header("Location: /home");
            exit();

            }
        

       
    }

    public function centroMedico(){
        if(isset($_SESSION["esClient"])){
            $id_centro = $_GET["id_Centro"];
            

            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];
            $data["centro"] = $this->centroMedicoModel->getCentroMedico($id_centro); 
           // $data["turno"] = $this->centroMedicoModel->sinTurnos($id_centro); 
            $data["fecha"] = $this->centroMedicoModel->fechaHoy();
            echo $this->printer->render("centroMedicoView.html", $data);
            exit();
        } else{
            header("Location: /home");
        exit();

        }


    }

    public function chequeoMedico(){

        if(isset($_SESSION["esClient"])){
                
            $idCentro = (int)$_GET["id_Centro"];
            $fecha = $_GET["fecha"];

            $data["esClient"] = $_SESSION["esClient"];
            $data["usuario"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];


            $data["chequeo"] = $this->centroMedicoModel->insertChequeo($idCentro, $_SESSION["id"], $fecha);

            
      
            


            echo $this->printer->render("resultadoChequeoMedicoView.html", $data);
            exit();
        } else{
            header("Location: /home");
        exit();

        }


    }


    

}