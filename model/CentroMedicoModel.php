<?php


class CentroMedicoModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getCentrosMedico(){
        $sql = "SELECT * FROM centro_medico";
        $result = $this->database->query($sql);
        return $result;
        
    }
    public function resultadoChequeo(){

        $result = rand(1,3);
        return $result;

    }

    public function getCentroMedico($id_centro){
        $sql = "SELECT * FROM centro_medico WHERE id = $id_centro";
        $result = $this->database->query($sql);
        return $result;
    }

    public function insertChequeo($id_centro, $id_usuario, $fecha){
        if ($this->sinTurnos($id_centro, $fecha) == true){
            $result = $this->resultadoChequeo();
            //$fecha = $this->fechaHoy();
            $sql = "INSERT INTO chequeo (id_centro_medico, codigo,fecha, id_usuario) VALUES ('$id_centro', '$result','$fecha', '$id_usuario')";
            $query = $this->database->query($sql);
            return $result;
        
        }else{

            return null;
            


        }
       

        
    }

    public function countTurnos($id_centro, $fecha){
        
        //$fecha_hoy = date('Y-m-d');

        $sql = "SELECT count(id_centro_medico) as turnos from chequeo where id_centro_medico = '$id_centro' and fecha = '$fecha'";
        $result = $this->database->query($sql);
        return $result;
    }
    public function getCantidadTurnos($id_centro){
        $sql = "SELECT turnos_diarios as turnos from centro_medico where id = '$id_centro'";
        $result = $this->database->query($sql);
        return $result;
    
    }

    public function sinTurnos($id_centro, $fecha){
        $contador  = $this->countTurnos($id_centro, $fecha);
        $cantidad = $this->getCantidadTurnos($id_centro);
        if($cantidad > $contador){
            return true;
        }else{
            return false;
        }
    }



    public function setearTurnos($id_centro){
       if($this->fechaHoy() != date("d-m-Y")){
       }
    }

    public function fechaHoy(){
            $fecha_hoy = date("d-m-Y");
            return $fecha_hoy;
        }

    
    public function getChequeo($id_centro, $id_user){
        $sql = "SELECT * FROM chequeo WHERE id_centro_medico = '$id_centro' and id_usuario = $id_user";
        $result = $this->database->query($sql);
        return $result;
    }

    public function getChequeoById($id_user){
        $sql = "SELECT codigo FROM chequeo WHERE id_usuario = '$id_user'";
        $result = $this->database->query($sql);
        if($result != null){
            return $result;
        } else{
            return null;
        }
     
    }


        
    





    }