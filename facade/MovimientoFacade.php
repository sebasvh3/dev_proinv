<?php

require_once rutaModel.'AbstractFacade.php';

class MovimientoFacade extends AbstractFacade{
    
    public $id;
    
    public static $getMovimiento;
    
    public function MovimientoFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='movimiento';
    }
    
     public function getNamedQuery($nameQuery) {
        $querys['allBodegasAct'] = "SELECT t.id, t.descripcion FROM $this->schema.$this->entidad "
                                 . " where t.estado='ACT'";
        
        
        //return $querys[$nameQuery];
    }
    
    public function findMovimientoByProducto($options){
        $offset = $options['offset'];
        $limit = $options['limit'];
        $dir = $options['dir'];
        $col = $options['col'];
        $producto = $options['producto'];
        $bodega = $options['bodega'];
        
    }
    
    
    
}