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
ini_set('display_errors', true);
        
class RuteadorControl {    
    public $vistaAccion;
    public $credenciales;
    public $requestUri;
  
    function RuteadorControl(){                
        if(isset($_SESSION['usuario'])){
            $this->setRequestUri();
            list($control, $accion, $id) = $this->getParametros();
            
            if(!isset($_SESSION['permisos'])){
                $_SESSION['permisos'] = array();
                $this->setCredenciales(json_decode($_SESSION['usuario']));
                $this->setPermisos(simplexml_load_file('enviroment/Permisos.xml'));
            }
//            var_dump($this->getRequestUri());
//            echo "-----";
//            var_dump($this->getRequestUriNoId());
//            echo "-----";
//            echo"<pre>hola Sesion:<br>";
//            var_dump($_SESSION);
//            echo "</pre>";
//            var_dump();
//            echo $this->getRequestUri();
//            var_dump($this->getPermisos());
            
            
            if(in_array($this->getRequestUriNoId(), $this->getPermisos()) && $control.$accion != "indexiniciar"){                
                if($objetoControl = $this->getControl($control)){
                    if(in_array($accion,get_class_methods(get_class($objetoControl)))){
//                        $objetoControl->$accion($accion == 'editar' ? $id : NULL);
                        if($id>0){
                            $objetoControl->$accion($id);
                        }
                        else{
                            $objetoControl->$accion();
                        }
                        // CUANDO USE AJAX SETEAR CON FALSE
                        $objetoControl->getVista(!isset ($objetoControl->layout) || $objetoControl->layout ? 'layout' : NULL);
                    } else {
                        echo "el metodo $accion existe en $control";
                    }
                } else{
                    echo "$control no encontrado";
                }
            } else {
                header("location : http://$_SERVER[SERVER_NAME]app.php/Index/inicio");
//                echo "<script type='text/javascript'>window.location.href='app.php/Index/inicio'</script>";
//                exit;
                $this->getControl('index')->inicio()->getVista('layout');
//                $this->getControl('index')->inicio();
//                exit;
                // COMENTADO POR QUE ESTA INACTIVO EL HEADER
                
            } 
        } else {
            $objetoControl = $this->getControl('index');
            if(!$objetoControl->iniciar($_POST)){
                $objetoControl->getVista('layout');
            } else {
                $this->RuteadorControl();
            }
        }
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
//        echo "<pre>"; print_r($xml);
        
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
    
    public function getCredenciales($indice = null) {
        if($indice !== null)
            return isset($this->credenciales[$indice]) ? $this->credenciales[$indice] : null;
        return $this->credenciales;
    }

    public function setCredenciales($usuario) {
        $this->credenciales = array();
        foreach ($usuario->rols as $rol){
            $this->credenciales[$rol->id] = $rol->descripcion;
        }
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
        $requestUri = preg_replace("/(\?.*$)/", '', $requestUri);//Peticion GET
        $this->requestUri = '/'.$requestUri;
    }
    
    public function getRequestUriNoId(){
        $requestUri = $this->getRequestUri();
        $url = preg_replace("/(\/\d+)?/","",$requestUri);
        $url = preg_replace("/(\?.*)?/","",$url);
        return $url;
    }
}
