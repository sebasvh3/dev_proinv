<?php

require_once rutaModel.'AbstractFacade.php';
require_once rutaFacades.'CategoriaFacade.php';

class ProductoFacade extends AbstractFacade{
    
    public $id;
    public $idcategoria;
    public static $editarProducto = "editarProducto";
    public static $sinCategoria = "sinCategoria";
    public static $ActualizarProductoTable = "ActualizarProductoTable";
    public static $ProductoByCategoria = "ProductoByCategoria";
    public static $consultarExistencia = "consultarExistencia";
    
    public function ProductoFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='producto';
    }
    
    public function guardarEdicionProducto($parametros){
        $this->id=$parametros['id'];
        if(!isset($parametros['id_categoria']) or $parametros['id_categoria']==''){
             $this->runNamedQuery(ProductoFacade::$sinCategoria);
             unset($parametros['id_categoria']);
        }
//        var_dump($parametros);
        $filtros = array("and id=".$parametros['id']);
        $this->updateEntities($parametros,$filtros,true);
        return true;
    }
    
    
    public function getNamedQuery($nameQuery) {
        $querys['editarProducto'] = "SELECT t.id, t.descripcion, t.codigo, t.cantidad_gr, t.id_categoria  FROM " . $this->schema . "." . $this->entidad . " t where t.id=".$this->id;
        $querys['sinCategoria'] = "UPDATE " . $this->schema . "." . $this->entidad . " SET id_categoria=null WHERE id=".$this->id;
        $querys['ActualizarProductoTable'] = "SELECT p.id, p.descripcion, p.codigo, p.cantidad_gr, c.descripcion as categoria "
                                            ."FROM producto p LEFT JOIN categoria c ON p.id_categoria = c.id WHERE p.id=".$this->id;
        $querys['ProductoByCategoria'] = "SELECT p.id, p.descripcion  FROM " . $this->schema . "." . $this->entidad . " p where p.id_categoria=".$this->idcategoria;
        $querys['consultarExistencia'] = "SELECT p.existencia  FROM " . $this->schema . "." . $this->entidad . " p where p.id=".$this->id;
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
    
    public function queryActualizarTable($id){
        $this->id=$id;
        $productoResponse = $this->runNamedQuery(ProductoFacade::$ActualizarProductoTable);
        return $productoResponse;
    }
    
    public function findProductoByCategoria($idcategoria){
        $this->idcategoria = $idcategoria;
        $productoResponse = $this->runNamedQuery(ProductoFacade::$ProductoByCategoria);
        return $productoResponse;
    }
    
    public function consultarExistencia($id){
        $this->id=$id;
        $response = $this->runNamedQuery(ProductoFacade::$consultarExistencia);
        if(isset($response[0]['existencia']) && $response[0]['existencia']!='')
            return $response[0]['existencia'];
        else return 0;
    }
    
    public function findProductoById($id){
        $filtros = array("and id =$id","and estado='ACT'");
        $entidades=$this->findEntitiesDos(array(),$filtros);
        if(count($entidades))
            return $entidades[0];
        return array();
    }
    
}