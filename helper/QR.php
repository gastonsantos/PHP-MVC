<?php

require 'third-party/phpqrcode/qrlib.php';

class QR{
    private $qr;

	public function __construct(){

        $this->qr = new QRCode();
	}

   
	public function crearQR($data){
        // $ruta = "C:\xampp\htdocs\public\QR\qr.png"; //ruta donde guardaremos el archivo qr.png
		$this->qr->png($data,false,QR_ECLEVEL_L,8);
		
	}

	
}