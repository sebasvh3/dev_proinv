<?php

//require_once 'EntidadAuditoria.php';
//Esto ahora lo pillo en mi pc


//Pablo si pillo lo cuca!!!



Class Movimiento /*extends EntidadAuditoria*/ {

    /**
     * @var integer $id
     * @Columna(nombre="id", tipo="integer", nulo=false)
     * @Id
     * @autoIncremental()
     */
    private $id;

    /**
     * @var varchar $descripcion
     * @Columna(nombre="descripcion", tipo="varchar", nulo=false)
     */
    public $descripcion;

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
    
    function Movimiento($fieldsValues) {
        $this->mergeDatos($fieldsValues);
    }

    public function mergeDatos($fieldsValues) {
        $keySet = array_keys($fieldsValues);
        
//        var_dump($fieldsValues);
        $objects = array();
        foreach ($keySet as $key) {
            $objects[strtolower($key)] = $fieldsValues[$key];
        }
        foreach ($objects as $key => $value)
            $this->$key = $value;
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

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
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


}

?>
