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
    public $tipoTransaccion;
    
    function MovimientoControl() {
        $this->facade = new MovimientoFacade();
    }
    
    
    //** Vistas principales del movimiento
    public function entrada(){
        $this->setTipoTransaccion(Ambiente::$Entrada);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/entrada');
    }
    
    public function salida(){
        $this->setTipoTransaccion(Ambiente::$Salida);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/salida');
    }
    
    public function entradaAveria(){
        $this->setTipoTransaccion(Ambiente::$EntradaAveria);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/entradaAveria');
    }
    
    public function salidaAveria(){
        $this->setTipoTransaccion(Ambiente::$SalidaAveria);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/salidaAveria');
    }
    
    public function entradaDevolucion(){
        $this->setTipoTransaccion(Ambiente::$EntradaDevolucion);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/entradaDevolucion');
    }
    
    public function salidaDevolucion(){
        $this->setTipoTransaccion(Ambiente::$SalidaDevolucion);
        $this->setCategorias();
        $this->setBodegas();
        $this->setVistaAccion('movimiento/salidaDevolucion');
    }
    
    public function producto($id){
        //$this->query = $this->BPrincipal();
        $productoFacade = new ProductoFacade();
        $this->producto = $productoFacade->findProductoById($id);
        $this->setVistaAccion('movimiento/verMovimiento');
    }
    //***
    
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
    
    public function getTipoTransaccion() {
        return $this->tipoTransaccion;
    }

    public function setTipoTransaccion($tipoTransaccion) {
        $this->tipoTransaccion = $tipoTransaccion;
    }

    
    //** Recibe las transacciones 2,4,6
    public function guardarEntradaAx(){
        $this->layout=false;
        $values=$_POST;
        
        $response=$this->validarCampos($values);
        if($response){
            $response['stored']=false;
            echo json_encode($response);
            return;
        }
        
        //** Guardar los datos de la entrada en la bodega
        $productoBodegaFacade = new ProductoBodegaFacade();
        $idProdBodega=$productoBodegaFacade->guardarEntradaProductoBodega($values);
        
        //** Se registra el movimiento realizado
        $movimiento = new Movimiento($values);
        $movimiento->setId_prodbodega($idProdBodega);
        $this->facade->doEdit($movimiento);
        
        $response['stored']=true;
        $response['msjs'][] = array('class'=>'success','msj'=>'La transacción se ha guardado exitosamente.');
        echo json_encode($response);
    }
    
    //** Recibe las transacciones 1,3,5
    public function guardarSalidaAx(){
        $this->layout=false;
        //$idTransaccionSalida = Ambiente::$Salida;
        $values=$_POST;
        
        $response=$this->validarCampos($values);
        if($response){
            $response['stored']=false;
            echo json_encode($response);
            return;
        }
        
        //** Se verifica si sacar lo solicitado de la bodega
        $productoBodegaFacade = new ProductoBodegaFacade();
        $aRespuesta=$productoBodegaFacade->guardarSalidaProductoBodega($values);
        if(!$aRespuesta['stored']){
            echo json_encode($aRespuesta);
            return;
        }
        $idProdBodega = $aRespuesta['idProdBodega'];
        
        //** Se registra el movimiento realizado
        $movimiento = new Movimiento($values);
        $movimiento->setId_prodbodega($idProdBodega);
        $this->facade->doEdit($movimiento);
        
        $response['stored']=true;
        $response['existencia']=$aRespuesta['existencia'];
        $response['msjs'][] = array('class'=>'success','msj'=>'La transacción se ha guardado exitosamente.');
        echo json_encode($response);
    }
    
    public function validarCampos($values){
        $fecha      = array_key_exists('fecha_trans',$values) ? $values['fecha_trans']:null;
        $idProducto = array_key_exists('id_producto',$values) ? $values['id_producto']:null;
        $cantidad   = array_key_exists('cant_trans' ,$values) ? $values['cant_trans']:null;
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
    
    
    
    public function movimiento(){
        $options = $_GET;
        $this->layout=false;
        $response = $this->facade->findMovimientoByProducto($options);
        $output['aaData'] = $response;
        //$output['iTotalRecords'] = 5;
        echo json_encode($output);
    }
    
}

