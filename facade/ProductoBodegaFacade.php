<?php

require_once rutaModel.'AbstractFacade.php';
require_once rutaEntidades.'Producto_bodega.php';

class ProductoBodegaFacade extends AbstractFacade{
    
    public $id;
    public static $allBodegasAct = "allBodegasAct";
    public static $findByIdproducto = "findByIdproducto";
    
    public function ProductoBodegaFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='producto_bodega';
    }
    
    public function getNamedQuery($nameQuery) {
        $querys['allBodegasAct'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.estado='ACT'";
        $querys['findByIdproducto'] = "SELECT t.* FROM " . $this->schema . "." . $this->entidad . " t where 1=1 ";
        $querys['otra'] = "SELECT * FROM " . $this->schema . "." . $this->entidad ;
        
        return $querys[$nameQuery];
    }
     
    public function getBodegasActivas(){
        return $this->runNamedQuery(BodegaFacade::$allBodegasAct);
    }
    
    public function _getProductoBodega($idproducto,$idbodega){
        $filtros = array("and id_producto='$idproducto'", "and id_bodega='$idbodega'","and estado='ACT'");
        $entidades=$this->findEntitiesDos(array(),$filtros);
        return $entidades;
    }
    
    /*
     * Se busca, si existe ya un registro con el producto y bodega, si no lo hay
     * se crea el registro y se retorna el id del producto_bodega para marcarlo en 
     * el movimiento 
     */
    public function guardarProductoBodega($values){
        $idproducto = $values['id_producto'];
        $idbodega   = $values['id_bodega'];
        $existencia = $values['cant_trans'];    
                
        $entidades = $this->_getProductoBodega($idproducto, $idbodega);
        //** Producto existente
        if(count($entidades)>0){
            $productoBodegaEdit = $entidades[0];
            $productoBodegaEdit->registrarEntrada($existencia);
            $this->doEdit($productoBodegaEdit);
            return $productoBodegaEdit->getId();
        }
        //** ProductoBodega Nuevo
        else{
            $productoBodega =  new Producto_bodega($values);
            $productoBodega->setExistencia($existencia);
            $this->doEdit($productoBodega);
            $entidad=$this->_getProductoBodega($idproducto, $idbodega);
            return $entidad[0]->getId();
        }
    }
    
    public function otra(){
        $query=$this->runNamedQuery("otra",array(" where id_producto='29'"));
        $this->showSql();
        echo"***";
        var_dump($query);
    }
    
    public function findByIdproducto($idproducto){
        $filtros = array("and id_producto='$idproducto'","and estado='ACT'","ORDER BY t.id_bodega ASC");
        
        $entidades = $this->runNamedQuery(ProductoBodegaFacade::$findByIdproducto,$filtros);
        $productoBodegas = array();
        foreach ($entidades as $valoresEntidad) {
            $idbogega = $valoresEntidad['id_bodega'];
            $productoBodegas[$idbogega] = new Producto_bodega($valoresEntidad);
        }
        //$entidades=$this->findEntitiesDos(array(),$filtros);
        
        return $productoBodegas;
    }
}