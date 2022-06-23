<?php

class CheckinController {
    private $printer;
    private $reservator;
    private $pdf;
    private $checkin;
    private $qr;
 
    
    


    public function __construct($printer, $reservator, $pdf, $checkin, $qr) {
        $this->printer = $printer;
        $this->reservator = $reservator;
        $this->pdf = $pdf;
        $this->checkin = $checkin;
        $this->qr = $qr;
        

    }

    public function show() {

        if (!$_SESSION["esClient"] || !isset($_SESSION["esClient"]) || $_SESSION["esClient"]== "" ) {
            Navigation::redirectTo("/home");
        } 
         
                $data["esClient"] = $_SESSION["esClient"];
                $data["nombre"] = $_SESSION["nombre"];
                $data["id"] = $_SESSION["id"];
                $id_reserva = $_GET["id_Reserva"];
                $data["reserva"] = $this->reservator->getRerservaByReserve($id_reserva);

                $data["podra"] = $this->checkin->fechaDePartidaCheck($id_reserva);

    
                echo $this->printer->render("checkinReservaView.html", $data);
            
    }
    public function checkinConfirm(){
        if (!$_SESSION["esClient"] || !isset($_SESSION["esClient"]) || $_SESSION["esClient"]== "" ) {
            Navigation::redirectTo("/home");
        }
                $data["esClient"] = $_SESSION["esClient"];
                $data["nombre"] = $_SESSION["nombre"];
                $data["id"] = $_SESSION["id"];

                $email = $_SESSION["email"];
                $id_reserva = $_POST["id_Reserva"];
                $fecha_reserva = $_POST["Fecha_reserva"];
                
                $newDate = date("d/m/Y", strtotime($fecha_reserva));
                $fecha_reserva = $newDate;

                $codigo = $_POST["codigo"];
                $precio = $_POST["precio"];
                $fecha_partida = $_POST["fecha_partida"];


                $hora = $_POST["hora"];
                $cabina = $_POST["cabina"];
                $servicio = $_POST["servicio"];

               
              
     
        
                

                 $dato = "
                    Fecha: ".$fecha_reserva."
                    Codigo : ".$codigo."
                    Precio: $".$precio."
                    Partida ".$fecha_partida."
                    Hora :  ".$hora."
                    Cabina: ".$cabina."
                    Servicio ".$servicio."
                    ";

        //$datoPDF= "<img src='$host/public/$id_reserva.png' href='#'></img>";
        //$this->pdf->crearPDF($datoPDF);
       $data["qr"] = $this->qr->createQR($dato, $id_reserva); // crea el qr, Id_reserva es el nombre del archivo
       $this->checkin->enviarEmailDeCheckin($email, $id_reserva); //envia email de checkin
       $this->reservator->updateReserva($id_reserva);//confirma la reserva
       echo $this->printer->render("qr.html", $data);

       
       
    }



    




    

}