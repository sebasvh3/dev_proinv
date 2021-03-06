<?php

//require_once 'EntidadAuditoria.php';
require_once rutaFacades.'CategoriaFacade.php';
require_once rutaFacades.'TerceroFacade.php';
require_once rutaFacades.'ProductoBodegaFacade.php';


Class Producto /*extends EntidadAuditoria*/ {

    /**
     * @var integer $id
     * @Columna(nombre="id", tipo="integer", nulo=false)
     * @Id
     * @autoIncremental()
     */
    public $id;

    /**
     * @var varchar $descripcion
     * @Columna(nombre="descripcion", tipo="string", nulo=false)
     */
    public $descripcion;

    /**
     * @var decimal $codigo
     * @Columna(nombre="codigo", tipo="integer", nulo=true)
     */
    public $codigo;

    /**
     * @var integer $cantidad_gr
     * @Columna(nombre="cantidad_gr", tipo="integer", nulo=false)
     */
    public $cantidad_gr;
    
    /**
     * @var decimal $existencia
     * @Columna(nombre="existencia", tipo="decimal", nulo=true)
     */
    public $existencia;

    /**
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $estado;

    /**
     * @var integer $id_categoria
     * @Columna(nombre="id_categoria", tipo="integer", nulo=true)
     */
    public $id_categoria;
    
    /**
     * @var integer $id_tercero
     * @Columna(nombre="id_tercero", tipo="integer", nulo=true)
     */
    public $id_tercero;

    /**
     * @var datetime $fecha_crea
     * @Columna(nombre="fecha_crea", tipo="datetime", nulo=true)
     */
    public $fecha_crea;

    /**
     * @var datetime $fecha_mod
     * @Columna(nombre="fecha_mod", tipo="datetime", nulo=true)
     */
    public $fecha_mod;

    /**
     * @var varchar $propietario
     * @Columna(nombre="propietario", tipo="varchar", nulo=true)
     */
    public $propietario;
    
    /**
     * @var varchar $usuario
     * @Columna(nombre="usuario", tipo="varchar", nulo=true)
     */
    public $usuario;
    
    private $categorias;
    
    private $productoBodegaCollection;
    
    
    function Producto($fieldsValues) {
        $this->mergeDatos($fieldsValues);
        //$this->setCategorias();
    }

    public function mergeDatos($fieldsValues) {
        $keySet = array_keys($fieldsValues);
        $myAttributes = get_object_vars($this);
        
        $objects = array();
        foreach ($keySet as $key) {
            if(array_key_exists(strtolower($key), $myAttributes)){
                $objects[strtolower($key)] = $fieldsValues[$key];
            }
        }
        foreach ($objects as $key => $value)
            $this->$key = $value;
        
        
        if(!isset($this->id_categoria) or $this->getId_categoria()=='') $this->setId_categoria(null);
        if(!isset($this->id_tercero) or $this->getId_categoria()=='') $this->setId_tercero(null);
        if(!isset($this->estado)) $this->setEstado ('ACT');
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getCantidad_gr() {
        return $this->cantidad_gr;
    }

    public function setCantidad_gr($cantidad_gr) {
        $this->cantidad_gr = $cantidad_gr;
    }

    public function getExistencia() {
        return $this->existencia;
    }

    public function setExistencia($existencia) {
        $this->existencia = $existencia;
    }

        
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    public function getId_tercero() {
        return $this->id_tercero;
    }

    public function setId_tercero($id_tercero) {
        $this->id_tercero = $id_tercero;
    }

    public function getFecha_crea() {
        return $this->fecha_crea;
    }

    public function setFecha_crea($fecha_crea) {
        $this->fecha_crea = $fecha_crea;
    }

    public function getFecha_mod() {
        return $this->fecha_mod;
    }

    public function setFecha_mod($fecha_mod) {
        $this->fecha_mod = $fecha_mod;
    }

    public function getPropietario() {
        return $this->propietario;
    }

    public function setPropietario($propietario) {
        $this->propietario = $propietario;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getCategorias() {
        return $this->categorias;
    }

    public function setCategorias() {
        $categoriaFacade= new CategoriaFacade();
        $categorias = $categoriaFacade->getCategoriasActivas();
        $this->categorias = $categorias;
    }
    
    public function getCategoriaDescripcion(){
        $categoriaFacade= new CategoriaFacade();
        if(isset($this->id_categoria) && $this->getId_categoria()!='')
            return $categoriaFacade->getDescripcionCategoria($this->getId_categoria());
        else
            return "";
    }
    
    public function getTerceroDescripcion(){
        $terceroFacade= new TerceroFacade();
        if(isset($this->id_tercero) && $this->getId_tercero()!='')
            return $terceroFacade->getDescripcionTercero($this->getId_tercero());
        else
            return "";
    }
    
    public function getProductoBodegaCollection() {
        if($this->productoBodegaCollection == null && $this->getId()){
            $productoBodegaFacade = new ProductoBodegaFacade();
            $this->productoBodegaCollection = $productoBodegaFacade->findByIdproducto($this->getId());
        }
        return $this->productoBodegaCollection;
    }
    
    public function getExistenciaEnBodega($idBodega){
        if($this->getId()){
            $productoBodegaFacade = new ProductoBodegaFacade();
            $cantidad = $productoBodegaFacade->getExistenciaByBodega($this->getId(),$idBodega);
            return $cantidad;
        }
        else return "";
    }
    
    public function getExistenciaBtercerizado(){
        
    }
}

