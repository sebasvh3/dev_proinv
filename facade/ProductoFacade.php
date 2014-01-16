<?php

require_once rutaModel.'AbstractFacade.php';
require_once rutaFacades.'CategoriaFacade.php';

class ProductoFacade extends AbstractFacade{
    
    public $id;
    public static $editarProducto = "editarProducto";
    public static $buscarPorCucod = "buscarPorCucod";
    
    public function ProductoFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='producto';
    }
    
    public function guardarEdicionProducto($parametros){
        $filtros = array("and id=".$parametros['id']);
        $this->updateEntities($parametros,$filtros,true);
        return true;
    }
    
    
    public function getNamedQuery($nameQuery) {
        $querys['editarProducto'] = "SELECT t.id, t.descripcion, t.codigo, t.cantidad_gr, t.id_categoria  FROM " . $this->schema . "." . $this->entidad . " t where t.id=".$this->id;
        return $querys[$nameQuery];
    }
    
    public function queryEditarProducto($id){
        $this->id=$id;
        $productoEditable = $this->runNamedQuery(ProductoFacade::$editarProducto);
        
        $categoriaFacade= new CategoriaFacade();
        $categorias = $categoriaFacade->getCategoriasActivas();
        
        $productoEditable[]=$categorias;
        
        return $productoEditable;
    }
    
}