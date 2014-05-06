<?php

//require_once 'EntidadAuditoria.php';


Class Producto_bodega {

    /**
     * @var integer $id
     * @Columna(nombre="id", tipo="integer", nulo=false)
     * @Id
     * @autoIncremental()
     */
    public $id;

    /**
     * @var integer $id_producto;
     * @Columna(nombre="id_producto", tipo="integer", nulo=false)
     */
    public $id_producto;
    
    /**
     * @var integer $id_producto;
     * @Columna(nombre="id_bodega", tipo="integer", nulo=false)
     */
    public $id_bodega;
    
    
    /**
     * @var decimal $existencia
     * @Columna(nombre="existencia", tipo="decimal", nulo=true)
     */
    public $existencia;
    
    /**
     * @var decimal $averias
     * @Columna(nombre="averias", tipo="decimal", nulo=true)
     */
    public $averias;
    
    /**
     * @var decimal $devs
     * @Columna(nombre="devs", tipo="decimal", nulo=true)
     */
    public $devs;

    /**
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $estado;



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
    
   
    
    
    function Producto_bodega($fieldsValues) {
        $this->mergeDatos($fieldsValues);
        $this->setEstado("ACT");
    }
    
    function updateDatos($fieldsValues){
        $this->mergeDatos($fieldsValues);
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
    }
    
    
    public function getId() {
        return $this->id;
    }

    public function getId_producto() {
        return $this->id_producto;
    }

    public function getId_bodega() {
        return $this->id_bodega;
    }

    public function getExistencia() {
        return $this->existencia;
    }

    public function getAverias() {
        return $this->averias;
    }

    public function getDevs() {
        return $this->devs;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFecha_crea() {
        return $this->fecha_crea;
    }

    public function getFecha_mod() {
        return $this->fecha_mod;
    }

    public function getPropietario() {
        return $this->propietario;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getCategorias() {
        return $this->categorias;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setId_producto($id_producto) {
        $this->id_producto = $id_producto;
    }

    public function setId_bodega($id_bodega) {
        $this->id_bodega = $id_bodega;
    }

    public function setExistencia( $existencia) {
        $this->existencia = $existencia;
    }

    public function setAverias( $averias) {
        $this->averias = $averias;
    }

    public function setDevs( $devs) {
        $this->devs = $devs;
    }

    public function setEstado( $estado) {
        $this->estado = $estado;
    }

    public function setFecha_crea( $fecha_crea) {
        $this->fecha_crea = $fecha_crea;
    }

    public function setFecha_mod( $fecha_mod) {
        $this->fecha_mod = $fecha_mod;
    }

    public function setPropietario($propietario) {
        $this->propietario = $propietario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setCategorias($categorias) {
        $this->categorias = $categorias;
    }

    
    public function registrarEntrada($cantidad){
        $this->existencia+=$cantidad;
    }

    
}

