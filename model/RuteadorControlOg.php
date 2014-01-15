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
class RuteadorControlOG {    
    public $vistaAccion;
    public $credenciales;
    public $requestUri;
    public $unsetPermiso;
  
    function RuteadorControl(){
        session_start();
        
        $this->setRequestUri();        
        
        list($control, $accion) = $this->getParametros();
        
        if(!isset($_SESSION['permisos'])){
            $_SESSION['permisos'] = array();
            $this->setCredenciales();
            
            $this->setPermisos(simplexml_load_file('permisosRutas.xml'));
        }

//        if(in_array($this->getRequestUri(), $this->getPermisos())){
            if($objetoControl = $this->getControl($control)){
                $control      = ($control != "index") ? "tb".$control."control" : $control."control";

                if(in_array($accion,get_class_methods($control))){

                    if(MetodosUtiles::estaAutenticado() || $control == "indexcontrol"){
                        if($control == "indexcontrol")
                            $this->setUnsetPermiso(TRUE);

                        $objetoControl->$accion();

                        if(!isset ($objetoControl->layout) || $objetoControl->layout){
                            $objetoControl->getVista('layout');
                        } else {
                            $objetoControl->getVista();
                        }
                    } else {
                        header("Location:".baseUrl."index.php/");
                    }
                } else{
                    echo "Error 404 metodo $accion no encontrado";
                }
            } else {
                echo "Error 404 controlador $control no encontrado";
            } 
//        } else {
//            $denegado = true;
//            include_once rutaVistas.'errorBaucherVista.php';
//        }
    }

    public function getControl($control){
        $control = ($control != "index" && $control != "login" ) ? "Tb".$control."Control" : ucfirst($control)."Control";
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
                $_SESSION['permisos'][] = $base.$ruta['path'];
            } else if(count($this->getCredenciales())){
                foreach ($ruta->usuario as $usuario){
                    if(in_array($usuario['username'], $this->getCredenciales())){
                        $_SESSION['permisos'][] = $base.$ruta['path'];
                        break;
                    }
                }
            }
            
            if(count((array) $ruta->rutas)){
                $this->setPermisos($ruta->rutas, $base.$ruta['path']);
            }
        }
    }
    
    public function getCredenciales() {
        return $this->credenciales;
    }

//    public function setCredenciales() {
//        $credenciales       = MetodosUtiles::getValorArrayLogueado('credenciales');
//        $this->credenciales = $credenciales != '' ? $credenciales : array();
//    }
    
    public function getPermisos() {
        return $_SESSION['permisos'];
    }
   
    public function getRequestUri() {
        return $this->requestUri;
    }

    public function setRequestUri() {
        $requestUri = str_replace(array('/index.php','/'), array("","\/"), $_SERVER["SCRIPT_NAME"]);
        
        $requestUri = preg_replace("/$requestUri\/index(\.php)?\/?/" , "", $_SERVER['REQUEST_URI']);
        $requestUri = preg_replace("/(\/id\/\w*$|\/$|\/\?.+$)/", '', $requestUri);
        $this->requestUri = '/'.$requestUri;
    }
    
    private function __destruct() {        
        if(MetodosUtiles::getValorArrayLogueado('credenciales') == '' || $this->getUnsetPermiso()){
            unset($_SESSION['permisos']);
        }
    }
    
    public function getUnsetPermiso() {
        return $this->unsetPermiso;
    }

    public function setUnsetPermiso($unsetPermiso) {
        $this->unsetPermiso = $unsetPermiso;
    }
}
?>

