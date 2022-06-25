
<?php

require_once 'third-party/dompdf/autoload.inc.php';
use Dompdf\Dompdf;






class PDF{
    private $dompdf; 


    public function __construct() {
   

                
                $this->dompdf = new Dompdf();

    }

    public function crearPDF($dato){
        
        $this->dompdf->loadHtml($dato);
        
        
// (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
        $this->dompdf->render();

// Output the generated PDF to Browser   
    $this->dompdf->stream("document.pdf", ['Attachment' => 0] );


    }

}



