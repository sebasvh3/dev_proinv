<?php

require_once rutaModel.'AbstractFacade.php';

class CategoriaFacade extends AbstractFacade{
    
    public $id;
    public static $editarCategoria = "editarCategoria";
    public static $allCategoriasAct = "allCategoriasAct";
    
    public function CategoriaFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='categoria';
    }
    
    public function guardarEdicionCategoria($parametros){
        $filtros = array("and id=".$parametros['id']);
        $this->updateEntities($parametros,$filtros,true);
        return true;
    }
    
    public function getNamedQuery($nameQuery) {
        $querys['editarCategoria'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.id=".$this->id;
        $querys['allCategoriasAct'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.estado='ACT'";
        return $querys[$nameQuery];
    }
    
    public function queryEditarCategoria($id){
        $this->id=$id;
        $categoriaEditable = $this->runNamedQuery(CategoriaFacade::$editarCategoria);
        return $categoriaEditable;
    }
    
    public function getCategoriasActivas(){
        return $this->runNamedQuery(CategoriaFacade::$allCategoriasAct);
    }
    
}