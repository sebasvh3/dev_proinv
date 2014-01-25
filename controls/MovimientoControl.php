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
        $this->setCategorias();
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
//        var_dump($_POST);
        $existencia=$_POST['existencia'];
        $idproducto=$_POST['id_producto'];
        
        if(!isset($existencia) || !isset($idproducto)){
            
        }
        
        $productoFacade = new ProductoFacade();
        $valorActual =  $productoFacade->consultarExistencia($idproducto);
        
        $parametros=array("existencia"=>$existencia+$valorActual); //Incrementa el valor en bodega
        $filtros = array("and id=".$idproducto);
        
        $productoFacade->updateEntities($parametros,$filtros,false);
        var_dump($parametros['existencia']);
        echo"el producto ".$idproducto." Se ha actualizado <br>";
        
//        $this->setVistaAccion('movimiento/entrada');
    }
    
    public function guardarSalidaProducto(){
        $this->layout=false;
        $salida=$_POST['salida'];
        $idproducto=$_POST['id_producto'];
        
        
        
        $productoFacade = new ProductoFacade();
        $valorActual =  $productoFacade->consultarExistencia($idproducto);
        
        if($valorActual>=$salida){
            $newExistencia=$valorActual-$salida;
            $parametros=array("existencia"=>$newExistencia);//Decrementa el valor en bodega
            $filtros = array("and id=".$idproducto);
        
            $productoFacade->updateEntities($parametros,$filtros,false);
            
            $Response['class'] = "success";
            $Response['msj'] = "Han salido de bodega $salida unidades";
            $Response['response'] = array("existencia"=>$newExistencia);
        }
        else{
            $Response['class'] = "warning";
            $Response['msj'] = "La cantidad es mayor a las existencias en bodega";
            $Response['response'] = null;
        }
        
        echo json_encode($Response);
    }
}

