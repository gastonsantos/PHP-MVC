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
                $codigo = $_POST["codigo"];
                $precio = $_POST["precio"];
                $fecha_partida = $_POST["fecha_partida"];
                $hora = $_POST["hora"];
                $cabina = $_POST["cabina"];
                $servicio = $_POST["servicio"];

               
              
     
        $dato = /*"<div>
                    <div>
                        <h1>PASE DE ABORDAJE</h2>
                    </div>
                    <div>
                        <h3>Gaucho Rocket</h2>
                    </div>
                        <div>
                            <p>
                             FECHA RESERVA:<strong>".$fecha_reserva."</strong>
                            <br>
                            CODIGO: <strong>".$codigo."</strong>
                            <br>
                            Precio: <strong>".$precio."</strong> 
                            <br>
                            FECHA PARTIDA <strong>".$fecha_partida."</strong> Hora :  <strong>".$hora."</strong>
                            <br>
                            CABINA: <strong>".$cabina."</strong>
                            <br>
                            SERVICIO <strong>".$servicio."</strong>
                            </p>
                        </div>
                 </div>";
                 */

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
       $data["qr"] = $this->qr->createQR($dato, $id_reserva);
       $this->checkin->enviarEmailDeCheckin($email, $id_reserva);
       $this->reservator->updateReserva($id_reserva);
       echo $this->printer->render("qr.html", $data);

       
       
    }



    




    

}