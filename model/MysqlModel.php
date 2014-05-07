<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MysqlModel
 *
 * @author cirsisdgr
 */
class MysqlModel {
    public $conexion;
    public $server;
    public $baseDatos;
    public $usuario;
    public $password;
    public $lastId;

    public function MysqlModel($config) {                
        $this->server    = $config['SERVER'];
        $this->baseDatos = $config['DATABASE'];
        $this->usuario   = $config['USER'];
        $this->password  = $config['PASSWORD'];
        $this->setConexion();
    }    

    public function setConexion(){
        $this->conexion = mysql_connect($this->server, $this->usuario, $this->password);
        mysql_select_db($this->baseDatos, $this->conexion);
        mysql_query ("SET NAMES 'utf8'");
    }
    
    public function getConexion() {
        return $this->conexion;
    }

    function executeQuery($sqlStatement){
        $lista = array();
        
        $resultado = mysql_query($sqlStatement, $this->getConexion());
        
        while($entidad = mysql_fetch_array($resultado))
            $lista[] = $this->decodeHtml($entidad);

        return $lista;
    }

    
    function sentenciaSimple($sqlStatement, $toLock = false){
        
        if($toLock){
            mysql_query("LOCK TABLES $toLock WRITE",$this->getConexion());
            $resultado = mysql_query($sqlStatement, $this->getConexion());
            $this->setLastId();
            //** Show Error
            if(!$resultado){
                echo "<code>".mysql_errno($this->getConexion()).": ".mysql_error($this->getConexion())."</code><br>";
            }
            mysql_query("UNLOCK TABLES",$this->getConexion());
        } else {
            $resultado = mysql_query($sqlStatement, $this->getConexion());
            //** Show Error
            if(!$resultado){
                echo "<code>".mysql_errno($this->getConexion()).": ".mysql_error($this->getConexion())."</code><br>";
            }
        }
        return $resultado;
    }

    function querySimpleObject($sqlStatement){
        $resultado = mysql_query($sqlStatement, $this->getConexion());
        //** Show Error
        if(!$resultado){
            echo "<code>".mysql_errno($this->getConexion()).": ".mysql_error($this->getConexion())."</code><br>";
        }
        return $resultado;
    }

    function executeQueryObject($sqlStatement){
        $lista = array();
        $resultado = mysql_query($sqlStatement, $this->getConexion());
        //echo $sqlStatement;
        if(!$resultado){
            echo "<code>".mysql_errno($this->getConexion()).": ".mysql_error($this->getConexion())."</code><br>";
                 //. "<pre>$sqlStatement</pre>";
        }
        if(!is_bool($resultado))    
            while($entidad = mysql_fetch_object($resultado)){
                $lista[] = $this->decodeHtml($entidad);
            }
            return $lista;
    }

    function ejecutarConsulta($sql) {
        $resultado = array();
        if ($this->getConexion() != 'error') {
            $resultados = mysql_query($sql, $this->getConexion());
            if ($resultados){
                $resultado = $this->decodeHtml(mysql_fetch_array($resultados));
            }
        }
        return $resultado;
    }
    
    function setLastId(){
        $this->lastId = mysql_insert_id($this->getConexion());
    }
    
    function getLastId(){
        return $this->lastId;
    }
   
    function cerrarConexion() {
        mysql_close($this->conexion);
    }
    
    private function decodeHtml($dataEncode = array()){
        $dataDecode =  array();
        
        foreach($dataEncode as $key=>$value)
            $dataDecode[$key] = (!is_array($value)) ? html_entity_decode($value) : $this->decodeHtml($value);
        
        return $dataDecode;
    }
}
?>