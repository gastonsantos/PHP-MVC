<?php

require 'third-party/phpqrcode/qrlib.php';

class QR{

	public function __construct(){

	}

   
	public function createQR($data, $name){

        $ruta = "public/QR/";
        $nombre = $name . ".png";
        $dir = $ruta . $nombre;
        QRcode::png($data, $dir);
        return $dir;


		
	}

	
}