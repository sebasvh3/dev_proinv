<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'ProductoFacade.php';
require_once rutaFacades.'ProductoBodegaFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaEntidades.'Producto.php';

class ProductoControl extends AbstractControl {
    
    public $categorias;
    public $terceros;
    
    function ProductoControl() {
        $this->facade = new ProductoFacade();
    }

    public function getListaEntidades($params=array()) {
        if($this->listaEntidades == null){
            $filtros = array("and estado='ACT'");
            $this->setListaEntidades($this->facade->findEntitiesDos(array(),$filtros));
        }
        return $this->listaEntidades;
    }

    public function listar() {
        $this->getListaEntidades();
        $this->setVistaAccion('producto/listar');
    }

    public function nuevo() {
        $categoriaFacade = new CategoriaFacade() ;
        $this->categorias = $categoriaFacade->getCategoriasActivas();
        $this->terceros = $this->factoryModel('TerceroFacade')->getTercerosAct();
        $this->setVistaAccion('producto/nuevo');
    }
    
    //** TODO: Validaciones
    public function guardar() {
        $entidad = new Producto($_POST);
        $this->facade->doEdit($entidad);
        $this->listar();
    }

    
    public function editar($id){
        $this->setSeleccionado($this->facade->findEntityById($id));
        $this->facade->showSql();
        $this->setVistaAccion('producto/editar');
        $this->verObj($this->getSeleccionado());
    }
    
    public function editarAjax(){
        $this->layout=false;
        $idproducto = $_POST['id'];
        $producto=$this->facade->queryEditarProducto($idproducto);
        $producto['categorias'] = $this->factoryModel('CategoriaFacade')->getCategoriasActivas();
        $producto['terceros']   = $this->factoryModel('TerceroFacade')->getTercerosAct();
        echo json_encode($producto);
    }
    
    public function guardarAjax(){
        $this->layout =false;
        $parametros = $_POST;
        $entidad = new Producto($parametros);
        $entidad->setEstado('ACT');
        $response = array();
        if ($this->facade->guardarEdicionProducto($parametros)) {
            $response['msj']= "El producto ".$parametros['id']." se guardó correctamente";
            $response['tipo']="success";
            $response['objEnt']= $this->facade->queryActualizarTable($parametros['id']);
        } else {
            $response['msj'] = "El producto ".$parametros['id']." no se guardó correctamente"; 
            $response['tipo'] = "danger";
            $response['objEnt'] = null;
        }
        echo json_encode($response);
    }
    
    public function inactivarRegistro(){
        $this->layout=false;//Consulta por Ajax
        $idproducto = $_POST['id'];
        $producto = array("estado"=>"DESC");
        $filtros = array("id"=>"and id=".$idproducto);
        $this->facade->updateEntities($producto,$filtros,false);
        $response['msj'] = "El producto ".$idproducto." se le cambio de estado"; 
        $response['tipo'] = "danger";
        $response['objEnt'] = null;
        echo json_encode($response);
    }
    
    public function consultar() {
        $this->layout=false;
        $id = $_POST['id'];
        $resultado=$this->facade->queryEditarProducto($id);
        echo json_encode($resultado);
    }
    
    public function findProductoByCategoria(){
        $this->layout=false;
        $idcategoria = $_POST['idcategoria'];
        if(isset($idcategoria) and $idcategoria!=''){
            $productos = $this->facade->findProductoByCategoria($idcategoria);
            echo json_encode($productos);
        }
        else echo json_encode(array());
    }
    
    //** Se consulta en productoBodegaFacade
    public function findExistencia(){
        $this->layout=false;
        //** Se recibe id_producto y tambien id_bodega
        $values = $_GET;
        
        //** Se instancia ProductoBodegaFacade
        $productoBodegaFacade = new ProductoBodegaFacade();
        $existencia = $productoBodegaFacade->findExistencia($values);
        
        echo json_encode(array("existencia"=>$existencia));
    }
    
    
    /** Metodo para ver la entidad seleccionada
     */
    public function ver() {
        $this->setVistaAccion('producto/ver');
    }


    
    
}

