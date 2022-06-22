<?php

class ReportesController {
    private $printer;
    private $reservatorModel;
    private $pdf;
   
    

    public function __construct($printer, $reservatorModel, $pdf)   {
        $this->printer = $printer;
        $this->reservatorModel = $reservatorModel;
        $this->pdf = $pdf;
       
        
    }

    public function show() {
        if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
            Navigation::redirectTo("/home");
        } 
        
        $data["nombre"] = $_SESSION["nombre"];
        $data["id"] = $_SESSION["id"];
        $data["esAdmin"] = true;

        $data["grafico"] = $this->reservatorModel->getCabinaMasVendida();
        $data["grafico2"] = $this->reservatorModel->getFacturacionMensual();
        $data["grafico3"] = $this->reservatorModel->getFacturacionByClient();
        $data["grafico4"] = $this->reservatorModel->getTasaDeOcupacionPorViaje();

        //$datoPDF= "<img src='$host/public/$id_reserva.png' href='#'></img>";
        //$this->pdf->crearPDF($dato);
        echo $this->printer->render("reportesView.mustache", $data);
        }

        public function pdfCabinaMasVendida(){
            if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
                Navigation::redirectTo("/home");
            } 
            
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["esAdmin"] = true;

            $datos = $this->reservatorModel->getCabinaMasVendida();

            $html= '<html><body>    
                 <table>
            <thead>
            <tr>
                <th>Cabina</th>
                <th>Cantidad</th>
            </tr>
            </thead>

            <tbody>'; foreach ($datos as $dato) { 
                $html.="<tr>
                <td>". $dato['Cabina'] . "</td>"; 
                $html.="<td>". $dato['Cantidad'] . "</td>"; 
                } 
                $html.= '
                </tr>
                </tbody>
                </table>
                </body></html>';
            
                
        
            $this->pdf->crearPDF($html);
        }

        public function pdfFacturacionMensual(){
            if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
                Navigation::redirectTo("/home");
            } 
            
            $data["nombre"] = $_SESSION["nombre"];
            $data["id"] = $_SESSION["id"];
            $data["esAdmin"] = true;

            $datos = $this->reservatorModel->getFacturacionMensual();

            $html= '<html><body>    
                 <table>
            <thead>
            <tr>
                <th>Mes</th>
                <th>Dinero</th>
            </tr>
            </thead>

            <tbody>'; foreach ($datos as $dato) { 
                $html.="<tr>
                <td>". $dato['MES'] . "</td>"; 
                $html.="<td>". $dato['DINERO'] . "</td>"; 
                } 
                $html.= '
                </tr>
                </tbody>
                </table>
                </body></html>';
            
                
        
            $this->pdf->crearPDF($html);


       
   
    
}

public function pdfFacturacionByClient(){
    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
        Navigation::redirectTo("/home");
    } 
    
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $datos = $this->reservatorModel->getFacturacionByClient();

    $html= '<html><body>    
         <table>
    <thead>
    <tr>
        <th>NOMBRE</th>
        <th>APELLIDO</th>
        <th>FACTURADO</th>
    </tr>
    </thead>

    <tbody>'; foreach ($datos as $dato) { 
        $html.="<tr>
        <td>". $dato['nombre'] . "</td>"; 
        $html.="<td>". $dato['apellido'] . "</td>"; 
        $html.="<td>". $dato['DINERO'] . "</td>"; 
        } 
        $html.= '
        </tr>
        </tbody>
        </table>
        </body></html>';
    
        

    $this->pdf->crearPDF($html);

}
public function pdfOcupacionPorVuelo(){
    if (!$_SESSION["esAdmin"] || !isset($_SESSION["esAdmin"]) || $_SESSION["esAdmin"]== "" ) {
        Navigation::redirectTo("/home");
    } 
    
    $data["nombre"] = $_SESSION["nombre"];
    $data["id"] = $_SESSION["id"];
    $data["esAdmin"] = true;

    $datos = $this->reservatorModel->getTasaDeOcupacionPorViaje();

    $html= '<html><body>    
         <table>
    <thead>
    <tr>
        <th>ID VUELO</th>
        <th>CAPACIDAD</th>
        <th>OCUPACION</th>
    </tr>
    </thead>

    <tbody>'; foreach ($datos as $dato) { 
        $html.="<tr>
        <td>". $dato['IdVuelo'] . "</td>"; 
        $html.="<td>". $dato['capacidad'] . "</td>"; 
        $html.="<td>". $dato['ocupacion'] . "</td>"; 
        } 
        $html.= '
        </tr>
        </tbody>
        </table>
        </body></html>';
    
       

    $this->pdf->crearPDF($html);



}
}
    
