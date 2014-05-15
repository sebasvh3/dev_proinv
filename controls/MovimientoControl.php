<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'MovimientoFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaFacades.'ProductoFacade.php';
require_once rutaFacades.'BodegaFacade.php';
require_once rutaFacades.'ProductoBodegaFacade.php';
require_once rutaEntidades.'Producto.php';
require_once rutaEntidades.'Producto_bodega.php';
require_once rutaEntidades.'Movimiento.php';
//require_once rutaEntidades.'Categoria.php';

class MovimientoControl extends AbstractControl {
    
    public $categorias;
    public $bodegas;
    public $producto;
    //public $id;
    
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
    
    
    public function guardarEntradaAx(){
        $this->layout=false;
        $idTransaccionEntrada = Ambiente::$Entrada;
        $values=$_POST;
        //$fechaTransaccion = $values['fecha_transaccion'];
        
        $response=$this->validarEntradaAx($values);
        if($response){
            $response['stored']=false;
            echo json_encode($response);
            return;
        }
        
        //** Guardar los datos de la entrada en la bodega
        $productoBodegaFacade = new ProductoBodegaFacade();
        $idProdBodega=$productoBodegaFacade->guardarProductoBodega($values);
        
        //** SE registra el movimiento realizado
        $movimiento = new Movimiento($values);
        $movimiento->setId_prodbodega($idProdBodega);
        $movimiento->setId_transaccion($idTransaccionEntrada);
        $movimiento->setEstado(Ambiente::$EstadoActivo);
        $this->facade->doEdit($movimiento);
        //$this->facade->showSql();
        
        $response['stored']=true;
        $response['msjs'][] = array('class'=>'success','msj'=>'La transacción se ha guardado exitosamente.');
        echo json_encode($response);
        //$this->setVistaAccion('movimiento/entrada');
        //**Verificar si exista ya una bodega_producto, si no la hay se crea
    }
    
    public function validarEntradaAx($values){
        $fecha = $values['fecha_trans'];
        $idProducto = $values['id_producto'];
        $cantidad = $values['cant_trans'];
        $response=array();
        if(!isset($fecha) || $fecha==null){
        $response['msjs'][] = array('class'=>'danger','msj'=>'El campo fecha no puede estar vacio.');
        }
        if(!isset($idProducto) || $idProducto==null){
            $response['msjs'][] = array('class'=>'danger','msj'=>'Debe haber un producto seleccionado.');
        }
        if(!isset($cantidad) || $cantidad==null){
            $response['msjs'][] = array('class'=>'danger','msj'=>'El campo cantidad no puede ir vacio.');
        }
        else{
            if(!is_numeric($cantidad)) 
                $response['msjs'][] = array('class'=>'warning','msj'=>'La cantidad debe ser un valor numérico válido.');
        } 
        return $response;
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
    
    public function producto($id){
        //$this->query = $this->BPrincipal();
        $productoFacade = new ProductoFacade();
        $this->producto = $productoFacade->findProductoById($id);
        $this->setVistaAccion('movimiento/productomov1');
    }
    
    public function BPrincipal(){
        $options = $_GET;
        $this->layout=false;
        $response = $this->facade->findMovimientoByProducto($options);
        $output['aaData'] = $response;
        //$output['iTotalRecords'] = 5;
        echo json_encode($output);
    }
    
    public function detalle($id){
        echo "Pagina con detalle $id";
    }
}

