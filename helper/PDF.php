<?php

require_once 'third-party/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;


class PDF{

	private $pdf;
	
	public function __construct(){

		$options = new Options();
        $options->setIsRemoteEnabled(true);
		$this->pdf = new Dompdf($options);
	}

   
	public function createPDF($data, $namePdf){

		$this->pdf->loadHtml($data);
		$this->pdf->setPaper('A4', 'landscape'); //size: A4 , orientation: landscape or portrait
		$this->pdf->render();
	    $this->downloadPDF($this->pdf, $namePdf);

	}


	public function downloadPDF($pdf, $name){

		$pdf->stream($name.'.pdf');
	}


   
	
}
