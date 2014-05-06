<?php

require_once rutaModel.'AbstractFacade.php';
require_once rutaEntidades.'Producto_bodega.php';

class ProductoBodegaFacade extends AbstractFacade{
    
    public $id;
    public static $allBodegasAct = "allBodegasAct";
    
    public function ProductoBodegaFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='producto_bodega';
    }
    
    public function getNamedQuery($nameQuery) {
        $querys['allBodegasAct'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.estado='ACT'";
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
     * se crea el registro y se retorna la instancia 
     */
    public function getProductoBodega($values){
        $idproducto = $values['id_producto'];
        $idbodega = $values['id_bodega'];
        
        $entidades = $this->_getProductoBodega($idproducto, $idbodega);
        if(count($entidades)>0){
            $productoBodegaEdit = $entidades[0];
            $productoBodegaEdit->updateDatos($values);
            //** Con segundo parametro = false, se indica que la auditoria solo es para
            //** los datos de las acutualizaciones y no completa en la creacion
            $this->doEdit($productoBodegaEdit);
            $this->showSql();
            return array("nuevo"=>false,"productoBodega"=>$productoBodegaEdit);
        }
        else{
            $productoBodega =  new Producto_bodega($values);
            $this->doEdit($productoBodega);
            $this->showSql();
            $productoBodegaCreado = $this->_getProductoBodega($idproducto, $idbodega);
            return array("nuevo"=>true,"productoBodega"=>$productoBodegaCreado[0]);
        }
    }
    
    public function otra(){
        $query=$this->runNamedQuery("otra",array(" where id_producto='29'"));
        $this->showSql();
        echo"***";
        var_dump($query);
    }
}