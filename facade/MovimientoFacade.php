<?php

require_once rutaModel.'AbstractFacade.php';

class MovimientoFacade extends AbstractFacade{
    
    public $id;
    public static $editarCategoria = "editarCategoria";
    public static $allCategoriasAct = "allCategoriasAct";
    public static $descripcionCategoria = "descripcionCategoria";
    
    public function MovimientoFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='movimiento';
    }
    
     public function getNamedQuery($nameQuery) {
        //$querys['allBodegasAct'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.estado='ACT'";
        //$querys['otra'] = "SELECT * FROM " . $this->schema . "." . $this->entidad ;
        
        //return $querys[$nameQuery];
    }
    
    
    
}