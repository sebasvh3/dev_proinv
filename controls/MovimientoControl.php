<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'MovimientoFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaFacades.'ProductoFacade.php';
require_once rutaFacades.'BodegaFacade.php';
require_once rutaFacades.'ProductoBodegaFacade.php';
require_once rutaEntidades.'Producto_bodega.php';
require_once rutaEntidades.'Movimiento.php';
//require_once rutaEntidades.'Categoria.php';

class MovimientoControl extends AbstractControl {
    
    public $categorias;
    public $bodegas; 
    
    function MovimientoControl() {
        $this->facade = new MovimientoFacade();
    }
    
    public function entrada(){
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/entrada');
    }
    
    public function salida(){
        $this->setCategorias();
        $this->setVistaAccion('movimiento/salida');
    }
    
    public function productomov1(){
        $this->setCategorias();
        $this->setVistaAccion('movimiento/productomov1');
    }
    
    public function setCategorias(){
        $categoriaFacade = new CategoriaFacade();
        $this->categorias = $categoriaFacade->getCategoriasActivas();
    }

    public function getCategorias(){
        return $this->categorias;
    }
    
    public function setBodegas(){
        $bodegaFacade = new BodegaFacade();
        $this->bodegas = $bodegaFacade->getBodegasActivas();
    }
    
    public function getBodegas(){
        return $this->bodegas;
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
        print_r($parametros['existencia']);
        echo"el producto ".$idproducto." Se ha actualizado <br>";
        
//        $this->setVistaAccion('movimiento/entrada');
    }
    
    
    //** TODO: validar que llegue el id del producto
    public function guardarEntradaAx(){
        $idTransaccionEntrada = Ambiente::$Entrada;
        $values=$_POST;
        //$fechaTransaccion = $values['fecha_transaccion'];
        
        
        //** Guardar los datos de la entrada en la bodega
        $productoBodegaFacade = new ProductoBodegaFacade();
        $productoBodegaFacade->guardarProductoBodega($values);
        
        //** SE registra el movimiento realizado
        $movimiento = new Movimiento($values);
   
        echo "<pre>";
        print_r($movimiento);
        echo "</pre>";
        $this->setVistaAccion('movimiento/entrada');
        //**Verificar si exista ya una bodega_producto, si no la hay se crea
    }
    
    public function guardarSalidaProducto(){
        $this->layout=false;
        $salida=$_POST['salida'];
        $idproducto=$_POST['id_producto'];
        
        
        
        $productoFacade = new ProductoFacade();
        $valorActual =  $productoFacade->consultarExistencia($idproducto);
        
        if($valorActual>=$salida and $salida>0){
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
    
    public function detalle($id){
        echo "Pagina con detalle $id";
    }
}

