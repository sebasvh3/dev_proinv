<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'MovimientoFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaFacades.'ProductoFacade.php';
//require_once rutaEntidades.'Categoria.php';

class MovimientoControl extends AbstractControl {
    
    public $categorias;
    
    function MovimientoControl() {
        $this->facade = new MovimientoFacade();
    }
    
    public function entrada(){
        $this->setCategorias();
        $this->setVistaAccion('movimiento/entrada');
    }
    
    public function salida(){
        $this->setVistaAccion('movimiento/salida');
    }
    
    public function setCategorias(){
        $categoriaFacade = new CategoriaFacade();
        $this->categorias = $categoriaFacade->getCategoriasActivas();
    }

    public function getCategorias(){
        return $this->categorias;
    }
    
    public function guardar(){
        //probar velocidad del servidor
        var_dump($_POST);
        $existencia=$_POST['existencia'];
        $idproducto=$_POST['id_producto'];
        
        $productoFacade = new ProductoFacade();
        $valorActual =  $productoFacade->consultarExistencia($idproducto);
        
        $parametros=array("existencia"=>$existencia+$valorActual);
        $filtros = array("and id=".$idproducto);
        
        $productoFacade->updateEntities($parametros,$filtros,false);
        var_dump($parametros['existencia']);
        echo"el producto ".$idproducto." Se ha actualizado <br>";
        
//        $this->setVistaAccion('movimiento/entrada');
    }
}

