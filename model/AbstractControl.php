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
    
    
    function AbstractControl(){
        
    }
    
    public function iniciar($vista = ""){
        $this->setVistaAccion("login");
    }
    
    public function getVista($accion = null){
        
        $accion = $accion ? $accion : $this->getVistaAccion();
        
        $archivoVista = rutaVistas.$accion.".php"; 
        
        if(is_file($archivoVista)){
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
    
    public function setSeleccionado($seleccionado){
        $this->seleccionado = $seleccionado;
    }
    
    public function getSeleccionado(){
        return $this->seleccionado;
    }
    
    public function getMensajeInformacion(){
        if(isset($_SESSION["mensajeInfo"])){
            $this->mensajeInformacion = new Pojo($_SESSION["mensajeInfo"]);
            unset($_SESSION["mensajeInfo"]);
        }
        
        return $this->mensajeInformacion ? $this->mensajeInformacion : FALSE ;
    }
    
    public function setMensajeInformacion($mensaje,$clase = null){
        $mensajeInformacion["pojo_mensaje"] = $mensaje;
        $mensajeInformacion["pojo_clase"] = ($clase) ? $clase : "claseCorrecto"; 
        $_SESSION["mensajeInfo"] = $mensajeInformacion;
        $this->mensajeInformacion = new Pojo($mensajeInformacion);
    }
    
    public function getFiltrosCr($clase,$atributo = ""){
        
        if(isset($_SESSION[$this->prepararParaFiltros($clase)])){
            if($atributo && isset($_SESSION[$this->prepararParaFiltros($clase)][$atributo])){
                return $_SESSION[$this->prepararParaFiltros($clase)][$atributo];
            } else{
                return $_SESSION[$this->prepararParaFiltros($clase)];
            }
            
        } 
        return false;
    }

    public function setFiltrosCr($clase, $filtrosCr){
        
        $_SESSION[$this->prepararParaFiltros($clase)] = $filtrosCr;
    }
    
    public function prepararParaFiltros($nombreClase){
        
        $nombreClase = preg_replace("/control|facade/","FiltrosCr",strtolower($nombreClase));
        
        return $nombreClase;
    }
    
    public static function getBase(){

        $url = str_replace("index.php", "", $_SERVER["SCRIPT_NAME"]);

        return "http://".$_SERVER['SERVER_NAME'].$url;
    }

    public function getListaEntidades(){
        return $this->listaEntidades;
    }
    
    public function setListaEntidades($listaEntidades){
        $this->listaEntidades = $listaEntidades;
    }
    
    public function getVer(){
        $this->setVistaAccion('ver');
    }
    
    
    
    
}

