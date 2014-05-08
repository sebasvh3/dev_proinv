<?php

Class Movimiento /*extends EntidadAuditoria*/ {

    /**
     * @var integer $id
     * @Columna(nombre="id", tipo="integer", nulo=false)
     * @Id
     * @autoIncremental()
     */
    private $id;

    /*
     * @var varchar $detalle
     * @Columna(nombre="detalle", tipo="varchar", nulo=true)
     */
    public $id_prodbodega;

    /*
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $id_transaccion;
    
    /*
     * @var varchar $documento
     * @Columna(nombre="documento", tipo="varchar", nulo=false)
     */
    public $documento;
    
    
    /*
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $cant_trans;
    
    /*
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $fecha_trans;
    
    /*
     * @var varchar $estado
     * @Columna(nombre="estado", tipo="varchar", nulo=false)
     */
    public $estado;

    /*
     * @var datetime $fecha_crea
     * @Columna(nombre="fecha_crea", tipo="datetime", nulo=true)
     */
    public $fecha_crea;

    /*
     * @var datetime $fecha_mod
     * @Columna(nombre="fecha_mod", tipo="datetime", nulo=true)
     */
    public $fecha_mod;

    /*
     * @var varchar $propietario
     * @Columna(nombre="propietario", tipo="varchar", nulo=true)
     */
    public $propietario;
    
    /*
     * @var varchar $usuario
     * @Columna(nombre="usuario", tipo="varchar", nulo=true)
     */
    public $usuario;
    
    function Movimiento($fieldsValues) {
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

    public function getId_prodbodega() {
        return $this->id_prodbodega;
    }

    public function getId_transaccion() {
        return $this->id_transaccion;
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function getCant_trans() {
        return $this->cant_trans;
    }

    public function getFecha_trans() {
        return $this->fecha_trans;
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

    public function setId($id) {
        $this->id = $id;
    }

    public function setId_prodbodega($id_prodbodega) {
        $this->id_prodbodega = $id_prodbodega;
    }

    public function setId_transaccion($id_transaccion) {
        $this->id_transaccion = $id_transaccion;
    }

    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    public function setCant_trans($cant_trans) {
        $this->cant_trans = $cant_trans;
    }

    public function setFecha_trans($fecha_trans) {
        $this->fecha_trans = $fecha_trans;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFecha_crea($fecha_crea) {
        $this->fecha_crea = $fecha_crea;
    }

    public function setFecha_mod($fecha_mod) {
        $this->fecha_mod = $fecha_mod;
    }

    public function setPropietario($propietario) {
        $this->propietario = $propietario;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }




}

