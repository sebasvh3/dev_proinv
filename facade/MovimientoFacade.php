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
        $querys['getMovimiento'] = "SELECT t.id, t.descripcion FROM $this->schema.$this->entidad "
                                 . " where t.estado='ACT'";
        
        
        //return $querys[$nameQuery];
    }
    
    public function findMovimientoByProducto($options){
        $offset = $options['offset'];
        $limit = $options['limit'];
        $dir = $options['dir'];
        $col = $options['col'];
        $productoBodega = $options['prodBodega'];

        $aColumns = array("m.fecha_trans","m.documento","t.descripcion","m.cant_trans","m.usuario");
        
        $query = "SELECT m.fecha_trans as fecha,m.documento,t.descripcion as transaccion,m.cant_trans as cantidad,m.usuario "
                . "FROM movimiento m "
                . "INNER JOIN producto_bodega pd ON m.id_prodbodega = pd.id "
                . "INNER JOIN transaccion t ON m.id_transaccion = t.id "
                . "WHERE m.id_prodbodega = $productoBodega ";
        $order = "ORDER BY $aColumns[$col] $dir ";
        $limitqr = "LIMIT $offset,$limit ";
        
        $result = $this->executeQuery($query.$order.$limitqr);
        return $result;
    }
    
    
    
}