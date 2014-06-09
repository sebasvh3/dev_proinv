<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractControl
 *
 * @author crivasi
 */

//require_once rutaModelos.'Pojo.php';

class AbstractControl {
    
    public $seleccionado;
    public $vistaAccion;
    public $mensajeInformacion;
    public $listaEntidades;
    public $facade;
    public $vista;
    
    //** Permite Adjuntar en el layout archivos js y css propios de la vista
    public $rutasJs=array();
    public $rutasCss=array();
    
    
    function AbstractControl() {
        
    }
    
    public function iniciar($vista = "") {
        $this->setVistaAccion("login");
    }
    
    public function getVista($accion = null) {
        $accion = $accion ? $accion : $this->getVistaAccion();
        
        $archivoVista = rutaVistas.$accion.".php"; 
        
        if(is_file($archivoVista)) {
            require_once $archivoVista;
        }

        return false;
    }    

    public function getVistaAccion() {
        return $this->vistaAccion;
    }

    public function setVistaAccion($vistaAccion) {
        $this->vistaAccion = $vistaAccion;
    }
    
    public function setSeleccionado($seleccionado) {
        $this->seleccionado = $seleccionado;
    }
    
    public function getSeleccionado() {
        return $this->seleccionado;
    }
    
    public function getMensajeInformacion() {
        if(isset($_SESSION["mensajeInfo"])) {
            $this->mensajeInformacion = new Pojo($_SESSION["mensajeInfo"]);
            unset($_SESSION["mensajeInfo"]);
        }
        
        return $this->mensajeInformacion ? $this->mensajeInformacion : FALSE ;
    }
    
    public function setMensajeInformacion($mensaje,$clase = null) {
        $mensajeInformacion["pojo_mensaje"] = $mensaje;
        $mensajeInformacion["pojo_clase"] = ($clase) ? $clase : "claseCorrecto"; 
        $_SESSION["mensajeInfo"] = $mensajeInformacion;
        $this->mensajeInformacion = new Pojo($mensajeInformacion);
    }
    
    public function getFiltrosCr($clase,$atributo = "") {
        
        if(isset($_SESSION[$this->prepararParaFiltros($clase)])) {
            if($atributo && isset($_SESSION[$this->prepararParaFiltros($clase)][$atributo])) {
                return $_SESSION[$this->prepararParaFiltros($clase)][$atributo];
            } else{
                return $_SESSION[$this->prepararParaFiltros($clase)];
            }
            
        } 
        return false;
    }

    public function setFiltrosCr($clase, $filtrosCr) {
        
        $_SESSION[$this->prepararParaFiltros($clase)] = $filtrosCr;
    }
    
    public function prepararParaFiltros($nombreClase) {
        
        $nombreClase = preg_replace("/control|facade/","FiltrosCr",strtolower($nombreClase));
        
        return $nombreClase;
    }
    
    public static function getBase() {

        $url = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);

        return "http://".$_SERVER['SERVER_NAME'].$url;
    }

    public function getListaEntidades() {
        return $this->listaEntidades;
    }
    
    public function setListaEntidades($listaEntidades) {
        $this->listaEntidades = $listaEntidades;
    }
    
    public function getVer() {
        $this->setVistaAccion('ver');
    }
    
    public static function factoryModel() {
        $facadesArray = array();
        
        foreach(func_get_args() as $className) {
            $className = ucfirst($className);
            require_once rutaFacades."$className.php";
            $facadesArray[] = new $className(); 
        }
        
        return count($facadesArray) == 1 ? array_pop($facadesArray) : $facadesArray;
    }  
    
    
    public function getEntitiesToJson($entities, $atributos = null) {        
        return json_encode($this->getEntitiesForJson($entities, $atributos));
    }
    
    public function getEntitiesForJson($entities, $atributos = null) {
        $entitiesArray = array();        
        $isArray       = is_array($entities);
        
        if($entities) {
            $entities = $isArray ? $entities : array($entities);
            foreach ($entities as $entitie) {
                $entitieArray = array();
                foreach ($atributos as $key=>$value) {
                    $atributo = is_array($value) ? $key : $value;                    
                    $keyName  = preg_replace("/^tb/i", '', $atributo);
                    if(property_exists($entitie, $atributo)) {
                        $metodo = 'get'.ucfirst($atributo);
                        $entitieArray[$keyName] = is_array($value) ? $this->getEntitiesForJson($entitie->$metodo(), $value) : $entitie->$metodo();
                    }
                }            
                $entitiesArray[] = $entitieArray;
            }
        }
        return $isArray ? $entitiesArray : array_pop($entitiesArray);
    }
    
    
    public function verObj($obj) {
        echo"<pre>";
        var_dump($obj);
        echo "</pre>";
    }
    
    public function addRutaJs($ruta) {
        $this->rutasJs[]=rutaJs.$ruta;
    }
    public function addRutaCss($ruta) {
        $this->rutasCss[]=rutaCss.$ruta;
    }

    public function getRutasJs() {
        $sRutasJs="";
        foreach($this->rutasJs as $ruta) {
            $sRutasJs.="<script type='text/javascript' src='$ruta'></script> \n\t";
        }
        return $sRutasJs;
    }
    
    //** TODO: Las rutas js funcion bn, debido que los <script> estan al final del loyout
    //** Por lo tanto ya se ha llamado la vistaAccion. En los <link> estan al principio 
    //** no se muestran porque aun no se ha llamado vistaAccion
    public function getRutasCss() {
        $sRutasCss="";
        foreach($this->rutasCss as $ruta) {
            $sRutasCss.="<link rel='stylesheet' type='text/css' href='$ruta' media='screen'/>";
        }
        return $sRutasCss;
    }
}

