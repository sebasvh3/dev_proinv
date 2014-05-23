<?php

require_once rutaModel.'AbstractFacade.php';
require_once rutaEntidades.'Producto_bodega.php';

class ProductoBodegaFacade extends AbstractFacade{
    
    public $id;
    public static $allBodegasAct = "allBodegasAct";
    public static $findByIdproducto = "findByIdproducto";
    public static $findExistenciaByBodega = "findExistenciaByBodega";
    
    public function ProductoBodegaFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='producto_bodega';
    }
    
    public function getNamedQuery($nameQuery) {
        $querys['allBodegasAct'] = "SELECT t.id, t.descripcion FROM " . $this->schema . "." . $this->entidad . " t where t.estado='ACT'";
        $querys['findByIdproducto'] = "SELECT t.* FROM " . $this->schema . "." . $this->entidad . " t where 1=1 ";
        $querys['findExistenciaByBodega'] = "SELECT t.existencia FROM " . $this->schema . "." . $this->entidad . " t where 1=1 ";
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
    
    public function getExistenciaByBodega($idProducto,$idBodega){
        $filtros = array("and id_producto = $idProducto and id_bodega=$idBodega");
        $result = $this->runNamedQuery(ProductoBodegaFacade::$findExistenciaByBodega,$filtros);
        if(count($result)){
            return number_format($result[0]['existencia'],0,".","");
        }
        else return "";
    }
    
    /*
     * Se busca, si existe ya un registro con el producto y bodega, si no lo hay
     * se crea el registro y se retorna el id del producto_bodega para marcarlo en 
     * el movimiento 
     */
    public function guardarEntradaProductoBodega($values){
        $idproducto     = $values['id_producto'];
        $idbodega       = $values['id_bodega'];
        $existencia     = $values['cant_trans'];
        $idTransaccion  = $values['id_transaccion'];
        
        $acciones = array(
            Ambiente::$Entrada => "registrarEntrada",
            Ambiente::$EntradaAveria => "registrarEntradaAveria",
            Ambiente::$EntradaDevolucion => "registrarEntradaDevolucion",
        );
        
        $accionTransaccion = $acciones[$idTransaccion];
        
        $entidades = $this->_getProductoBodega($idproducto, $idbodega);
        //** Producto existente
        if(count($entidades)>0){
            $productoBodegaEdit = $entidades[0];
            $productoBodegaEdit->$accionTransaccion($existencia);
            $this->doEdit($productoBodegaEdit);
            return $productoBodegaEdit->getId();
        }
        //** ProductoBodega Nuevo
        else{
            $productoBodega =  new Producto_bodega($values);
            $productoBodega->$accionTransaccion($existencia);
            $this->doEdit($productoBodega);
            $entidad=$this->_getProductoBodega($idproducto, $idbodega);
            return $entidad[0]->getId();
        }
    }
    
    /*
     * Se valida que la cantidad a sacar de la bodega sea menor o igual a las existencias
     * almacenadas en la bodega.
     */
    public function guardarSalidaProductoBodega($values){
        $idproducto = $values['id_producto'];
        $idbodega   = $values['id_bodega'];
        $cantidadSalida = $values['cant_trans'];
        $idTransaccion  = $values['id_transaccion'];
        //**
        $accionesTransaccion = array(
            Ambiente::$Salida => array("registrar"=>"registrarSalida","get"=>"getExistencia","msj"=>"existencias"),
            Ambiente::$SalidaAveria => array("registrar"=>"registrarSalidaAveria","get"=>"getAverias","msj"=>"averias"),
            Ambiente::$SalidaDevolucion => array("registrar"=>"registrarSalidaDevolucion","get"=>"getDevs","msj"=>"devoluciones"),
        );
        //**
        $transaccion = $accionesTransaccion[$idTransaccion];
        
        $entidades = $this->_getProductoBodega($idproducto, $idbodega);
        $respuesta = array();
        //** Producto existente
        if(count($entidades)>0){
            $productoBodega = $entidades[0];
            $cantidadEnBodega = $productoBodega->$transaccion['get']();
            
            //** Validacion de la existencia
            if($cantidadEnBodega<$cantidadSalida){
                $respuesta['stored'] = false;
                $respuesta['msjs'][] = array('class'=>'warning','msj'=>"La cantidad a sacar es mayor a las {$transaccion['msj']} en bodega.");
                return $respuesta;
            }
            if($cantidadSalida<0){
                $respuesta['stored'] = false;
                $respuesta['msjs'][] = array('class'=>'warning','msj'=>'La cantidad no puede ser un valor negativo.');
                return $respuesta;
            }
            
            $productoBodega->$transaccion['registrar']($cantidadSalida);
            $this->doEdit($productoBodega);
            $respuesta['stored'] = true;
            $respuesta['idProdBodega'] = $productoBodega->getId();
            $respuesta['existencia']= $productoBodega->getExistencia();
            return $respuesta;
        }
        //** ProductoBodega No encontrado
        else{
            $respuesta['stored'] = false;
            $respuesta['msjs'][] = array('class'=>'warning','msj'=>'Primero debe registrar este producto en la bodega.');
            return $respuesta;
        }
    }
    
    //** Necesaria para visualizar todo el movimiento por bodegas de un producto determinado.
    public function findByIdproducto($idproducto){
        $filtros = array("and id_producto='$idproducto'","and estado='ACT'","ORDER BY t.id_bodega ASC");
        
        $entidades = $this->runNamedQuery(ProductoBodegaFacade::$findByIdproducto,$filtros);
        $productoBodegas = array();
        foreach ($entidades as $valoresEntidad) {
            $idbodega = $valoresEntidad['id_bodega'];
            $productoBodegas[$idbodega] = new Producto_bodega($valoresEntidad);
        }
        //$entidades=$this->findEntitiesDos(array(),$filtros);
        
        return $productoBodegas;
    }
    
    //** En la vista de salidas es necesario saber cuanto hay en bodega, ya sea (Existencia, Averias o Devoluciones)
    //** para poder tener una referencia de cuanto se debe sacar de la bodega
    public function findExistencia($values){
        $idproducto = array_key_exists('id_producto', $values) ? $values['id_producto']:0;
        $idbodega   = array_key_exists('id_bodega', $values) ? $values['id_bodega']:0;
        $aProductoBodegas = $this->_getProductoBodega($idproducto, $idbodega);
        $idTransaccion  = $values['id_transaccion'];
        
        $accionesGet = array(
            Ambiente::$Salida => "getExistencia",
            Ambiente::$SalidaAveria => "getAverias",
            Ambiente::$SalidaDevolucion => "getDevs",
        );
        
        $get = $accionesGet[$idTransaccion];
        
        if(count($aProductoBodegas)>0){
            $productoBodega = $aProductoBodegas[0];
            return number_format($productoBodega->$get(),0,".","");
        }
        else return "";
    }
}