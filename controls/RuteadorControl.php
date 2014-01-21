<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RuteadorControl
 *
 * @author crivasi
 */
class RuteadorControl {    
    public $vistaAccion;
    public $credenciales;
    public $requestUri;
    public $unsetPermiso;
  
    function RuteadorControl(){
        session_start();
        
        $this->setRequestUri();        
        
        list($control, $accion, $id) = $this->getParametros();
        
//        echo "<pre>";
//        var_dump($this->getParametros());

        if(!isset($_SESSION['permisos'])){
            $_SESSION['permisos'] = array();
            $this->setCredenciales(array('usuario'));
            $this->setPermisos(simplexml_load_file('enviroment/Permisos.xml'));
        }

//        if(in_array($this->getRequestUri(), $this->getPermisos())){
                if($objetoControl = $this->getControl($control)){
                $control      = ($control != "index") ? $control."control" : $control."control";
                if(in_array($accion,get_class_methods($control))){

//                    if(MetodosUtiles::estaAutenticado() || $control == "indexcontrol"){
                        if($control == "indexcontrol")
                            $this->setUnsetPermiso(TRUE);
                        
                        if($accion == 'editar'){
                            $objetoControl->$accion($id);
                        }
                        else{ $objetoControl->$accion();}
                        if(!isset ($objetoControl->layout) || $objetoControl->layout){ // CUANDO USE AJAX SETEAR CON FALSE
                            $objetoControl->getVista('layout');
                        } else {
                            $objetoControl->getVista();
                        }
//                    } else {
//                        header("Location:".baseUrl."index.php/");
//                    }
                } else{
                    echo "<font color='green'>Error 404 metodo $accion no encontrado</font></br>";
                }
            } else {
                echo "<font color='blue'>Error 404 controlador $control no encontrado</font></br>";
            } 
//        } else {
//            $denegado = true;
//            include_once rutaVistas.'errorBaucherVista.php';
//        }
    }

    public function getControl($control){  
        $control = ($control != "index" && $control != "login" ) ? ucfirst($control)."Control" : ucfirst($control)."Control";
        $archivoControl = rutaControles.$control.".php";

        if(is_file($archivoControl)){
            require_once $archivoControl;
            return new $control();
        }
        return false;
    }

    public function getParametros(){                
        $url = array_values(array_filter(explode('/', $this->getRequestUri())));
        
        $parametros[0] = (isset($url[0]) && $url[0] != "") ? $url[0] : 'index';
        
        $parametros[1] = (isset($url[1]) && $url[1] != "") ? $url[1] : 'iniciar';
        
        $parametros[2] = (isset($url[2]) && $url[2] != "") ? $url[2] : 0;
        
        return $parametros;
    } 
    
    public function getVistaAccion() {
        return $this->vistaAccion;
    }

    public function setVistaAccion($vistaAccion) {
        $this->vistaAccion = $vistaAccion;
    }
    
    public function setPermisos($xml, $base = ''){
        foreach ($xml->ruta as $ruta){
            if(count((array) $ruta->usuario) == 0){
                $_SESSION['permisos'][] = $base.$ruta['ruta'];
            } else if(count($this->getCredenciales())){
                foreach ($ruta->usuario as $usuario){
                    if(in_array($usuario['usuario'], $this->getCredenciales())){
                        $_SESSION['permisos'][] = $base.$ruta['ruta'];
                        break;
                    }
                }
            }
            
            if(count((array) $ruta->rutas)){
                $this->setPermisos($ruta->rutas, $base.$ruta['ruta']);
            }
        }
    }
    
    public function getCredenciales() {
        return $this->credenciales;
    }

    public function setCredenciales($credenciales = array()) {
        $this->credenciales = $credenciales != '' ? $credenciales : array();
    }
    
    public function getPermisos() {
        return $_SESSION['permisos'];
    }
   
    public function getRequestUri() {
        return $this->requestUri;
    }

    public function setRequestUri() {
        $requestUri = str_replace(array('/app.php','/'), array("","\/"), $_SERVER["SCRIPT_NAME"]);
        
        $requestUri = preg_replace("/$requestUri\/app(\.php)?\/?/" , "", $_SERVER['REQUEST_URI']);
        $requestUri = preg_replace("/(\/id\/\w*$|\/$|\/\?.+$)/", '', $requestUri);
        $this->requestUri = '/'.$requestUri;
    }
    
//    private function __destruct() {        
//        if(MetodosUtiles::getValorArrayLogueado('credenciales') == '' || $this->getUnsetPermiso()){
//            unset($_SESSION['permisos']);
//        }
//    }
    
    public function getUnsetPermiso() {
        return $this->unsetPermiso;
    }

    public function setUnsetPermiso($unsetPermiso) {
        $this->unsetPermiso = $unsetPermiso;
    }
}

