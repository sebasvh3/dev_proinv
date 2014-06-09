<?php

require_once rutaModel.'AbstractFacade.php';

class TerceroFacade extends AbstractFacade {
    
    public $id;
    public static $allTerceros = "allTerceros";
    public static $descripcionTercero = "descripcionTercero";
    
    public function TerceroFacade() {
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='tercero';
    }
        
    public function getNamedQuery($nameQuery) {
        $querys['allTerceros'] = "SELECT id, descripcion FROM $this->schema.$this->entidad ";
        $querys['descripcionTercero'] = "SELECT descripcion FROM $this->schema.$this->entidad ";
        return $querys[$nameQuery];
    }
    
    public function getTercerosAct() {
        $filtros = array(" WHERE estado = 'ACT'");
        $terceros = $this->runNamedQuery(self::$allTerceros,$filtros);
        return $terceros;
    }
    
    public function getDescripcionTercero($id){
        $filtros = array("where estado='ACT' and id=$id");
        $request = $this->runNamedQuery(self::$descripcionTercero,$filtros);
        return $request[0]['descripcion'];
    }
}