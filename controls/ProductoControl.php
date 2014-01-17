<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'ProductoFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaEntidades.'Producto.php';

class ProductoControl extends AbstractControl {
    
    public $categorias;
    
    function ProductoControl() {
        $this->facade = new ProductoFacade();
    }

    public function getListaEntidades($params=array()) {
        if ($this->listaEntidades == null) {
      //      if (isset($params['filtrar'])) {
//                $this->setListaEntidades($this->facade->findEntitiesDos('', $params));
                $filtros = array("and estado='ACT'");
                $this->setListaEntidades($this->facade->findEntitiesDos(array(),$filtros));
//                $this->facade->showSql();
           // }
        }
//        echo "<pre>";
//        var_dump($this->listaEntidades);
        return $this->listaEntidades;
    }

    public function listar() {
//        $params = $_POST;
//////***A�adir Filtros
////        if (isset($_GET['aucodestad'])) {
//////$params['mocodmotiv'] = Ambiente::$motivoGuardada;
////            $params['aucodestad'] = 'A';
////        }
//////***Consultar
////        $this->getListaEntidades($params);
////        include_once '../view/tipo_activocr.php';
//        $this->getListaEntidades($params);
//        echo "Si señor esta listando";
//        
        $this->getListaEntidades();
        $this->setVistaAccion('producto/listar');
    }

    public function nuevo() {
        $categoriaFacade = new CategoriaFacade() ;
        $this->categorias = $categoriaFacade->getCategoriasActivas();
        $this->setVistaAccion('producto/nuevo');
    }
    

    

    public function guardar() {
        $entidad = new Producto($_POST);
        
        $entidad->setEstado('ACT');
        echo "<pre>";var_dump($entidad);
        echo "<pre>";var_dump($_POST);
//***
//        if ($entidad->getId() == null || trim($entidad->getId()) == '') {
//            $entidad->setMocodmotiv('01');
//            $entidad->setAucodestad('A');
//        }
//***
        if ($this->facade->doEdit($entidad)) {
//            echo "si... guardo";
//            $this->mensaje = Ambiente::$mensajeGuardarCorrecto;
            //***cargue la entidad que se acaba de crear
    //            if($entidad->getId() == null){
    //                $entidad = $this->facade->buscarUltimoCreadoPorElUspropieta($entidad->getUspropieta());
    //            }
        } else {
//            echo "No... guardo";
//            $this->mensaje = Ambiente::$mensajeGuardarIncorrecto;
        }
        //***cargue la entidad seleccionada
        $this->entidadSeleccionada = $entidad;
        $this->facade->showSql();
        $this->facade->showMensajeInfo();
        
    }

    
    public function editar($id){
//        echo "editando ....  ".$id;
//        $this->layout =false;
//        $this->facade->idcolum='id';
//        $this->layout=false;
        $this->setSeleccionado($this->facade->findEntityById($id));
        $this->facade->showSql();
        $this->setVistaAccion('producto/editar');
        $this->verObj($this->getSeleccionado());
    }
    
    public function editarAjax(){
        $this->layout=false;
        $idproducto = $_POST['id'];
//        $producto=$this->facade->findEntityById($idproducto);
        $producto=$this->facade->queryEditarProducto($idproducto);
//        echo $producto->getId();
        echo json_encode($producto);
    }
    
    public function guardarAjax(){
        $this->layout =false;
        $parametros = $_POST;
        $entidad = new Producto($parametros);
        $entidad->setEstado('ACT');
        $response = array();
        if ($this->facade->guardarEdicionProducto($parametros)) {
//            $this->facade->showSql();
            $response['msj']= "El producto ".$parametros['id']." se guardó correctamente";
            $response['tipo']="success";
            $response['objEnt']= $this->facade->queryActualizarTable($parametros['id']);
//            $this->facade->showSql();
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
//        $select=array("descripcion","estado","cantidad_gr");
//        $filtros=array("and id=".$id);
//        $resultado=$this->facade->findEntitiesDos($select,$filtros);
        $resultado=$this->facade->queryEditarProducto($id);
        echo json_encode($resultado);
    }
    
    public function prepararNuevo() {
        $this->entidadSeleccionada = new Tipo_activo(array());
    }
    
    /** Metodo para ver la entidad seleccionada
     */
    public function ver() {
        $this->setVistaAccion('producto/ver');
    }

    public function algo(){
        echo "popo";
    }

    public function prepararVer() {
        $id = $_GET['id'];
        $this->entidadSeleccionada = $this->facade->findEntityById($id);
    }

    /** Metodo para editar la entidad seleccionada
     */
//    public function editar() {
//        $this->prepararEditar();
//        require_once '../view/tipo_activotr.php';
//    }

    public function prepararEditar() {
        $id = $_GET['id'];
        $this->entidadSeleccionada = $this->facade->findEntityById($id);
    }
    
    public function verObj($obj){
        echo"<pre>";
        var_dump($obj);
        echo "</pre>";
    }
    

}

