<?php

class HomeController{

    private $printer;
    
    public function __construct( $printer){
        $this->printer = $printer;
       
        
    }

    function show(){
        echo $this->printer->render("view/homeView.html");
    }
}