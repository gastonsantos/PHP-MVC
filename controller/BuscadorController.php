<?php

class BuscadorController{

    private $printer;
    private $vuelosModel;
   
    public function __construct( $printer, $vuelosModel){
        $this->printer = $printer;
        $this->vuelosModel = $vuelosModel;
        
        
    }
  
    public function buscar (){
        if(isset($_SESSION["rol"])){
            
        $data["nombre"] = $_SESSION["nombre"];  

        $buscar = $_GET["buscar"];
        if($buscar == ""){
        $data["viajes"] = $this->vuelosModel->getVuelos();
        $data["vacio"] = true;//Esta vacio Cartelito

        $data["nombre"] = $_SESSION["nombre"];

        
         echo $this->printer->render("userHomeView.html", $data);
    }
    $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
    if( empty($data["viajes"]) ){
        $data["error"] = true;//No se encontro resultado cartelito
        $data["viajes"] = $this->vuelosModel->getVuelos();
    }else{
        $data["error"] = false;
    }
     
      echo $this->printer->render("userHomeView.html", $data);

}else{

    $buscar = $_GET["buscar"];
    if($buscar == ""){
        $data["viajes"] = $this->vuelosModel->getVuelos();
        $data["vacio"] = true;//Esta vacio Cartelito
        
         echo $this->printer->render("homeView.html", $data);
    }
    $data["viajes"] = $this->vuelosModel->buscarVuelos($buscar);
    if( empty($data["viajes"]) ){
        $data["error"] = true;//No se encontro resultado cartelito
        $data["viajes"] = $this->vuelosModel->getVuelos();
    }else{
        $data["error"] = false;
    }
     
     $this->printer->render("homeView.html", $data);
}
    


}
}