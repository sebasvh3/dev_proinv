<?php

class jose{
    public $nombre;
    public $apellido;
    
    public $otro;
    
    public function getOtro() {
        return $this->otro;
    }

    public function setOtro($otro) {
        $this->otro = $otro;
    }

        
    function jose($nombre,$apellido){
        $this->apellido=$apellido;
        $this->nombre=$nombre;
    }
    
    function __construct() {
        ;
    }
    
    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }
    
   
    

}

 $obj1 = new jose("Jose","Cardenas henao");
 $obj2 = new jose("Sebastian","Vahos Herrera");
    var_dump($obj1);
    echo"<br>";
    var_dump($obj2);

?>