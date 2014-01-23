<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaEntidades.'Categoria.php';

class CategoriaControl extends AbstractControl {
    
    function CategoriaControl() {
        $this->facade = new CategoriaFacade();
    }

    public function getListaEntidades($params=array()) {
        if ($this->listaEntidades == null) {
      //      if (isset($params['filtrar'])) {
//                $this->setListaEntidades($this->facade->findEntitiesDos('', $params));
                $filtros = array("and estado='ACT'");
                $this->setListaEntidades($this->facade->findEntitiesDos(array(),$filtros));
                $this->facade->showSql();
           // }
        }
//        echo "<pre>";
//        var_dump($this->listaEntidades);
        return $this->listaEntidades;
    }

    public function listar() {
        $this->setVistaAccion('categoria/listar');
//        $this->getListaEntidades();
    }

    public function nuevo() {
        $this->setVistaAccion('categoria/nuevo');
//        $this->prepararNuevo();
//        require_once '../view/tipo_activotr.php';
    }
    

    

    public function guardar() {
        $entidad = new Categoria($_POST);
        
        $entidad->setEstado('ACT');
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
        $idcategoria= $_POST['id'];
        $categoria=$this->facade->queryEditarCategoria($idcategoria);
//        echo $producto->getId();
        echo json_encode($categoria);
    }
    
    public function guardarAjax(){
        $this->layout =false;
        $parametros = $_POST;
        $response = array();
        if ($this->facade->guardarEdicionCategoria($parametros)) {
            $response['msj']= "La categoria ".$parametros['id']." se guardó correctamente";
            $response['tipo']="success";
            $response['objEnt']= $this->facade->queryEditarCategoria($parametros['id']);
        } else {
            $response['msj'] = "La categoria ".$parametros['id']." no se guardó correctamente"; 
            $response['tipo'] = "danger";
            $response['objEnt'] = null;
        }
        echo json_encode($response);
    }
    
    public function inactivarRegistro(){
        $this->layout=false;
        $idcategoria = $_POST['id'];
        $campo = array("estado"=>"DESC");
        $filtros = array("id"=>"and id=".$idcategoria);
        $this->facade->updateEntities($campo,$filtros,false);
        $response['msj'] = "El producto ".$idcategoria." se le cambio de estado"; 
        $response['tipo'] = "danger";
        $response['objEnt'] = null;
        echo json_encode($response);
    }
    
    public function prepararNuevo() {
        $this->entidadSeleccionada = new Tipo_activo(array());
    }  
    
    public function verObj($obj){
        echo"<pre>";
        var_dump($obj);
        echo "</pre>";
    }
    
    public function probarGet(){
        $this->layout=false;
        $get = $_GET;
//        $this->verObj($get);
        echo json_encode($get);
    }
    

}

