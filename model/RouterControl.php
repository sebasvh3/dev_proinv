<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RouterControl
 *
 * @author cirsisjgp
 */

class RouterControl {
    private $control;
    private $accion;
    private $pathControl;
    private $documento;
    
    function RouterControl() {       
        $this->setRequest();        
        $this->ejecutarControl();
        $this->incluirArchivos();
   }
   
    function ejecutarControl(){
        
        $accion = $this->getAccion();
        $clase  = $this->getControl();

        $controlador = $this->pathControl."$clase.php";
        
        if(is_file($controlador)){
            require_once $controlador;
            
            $instancia = new $clase();
            
            $metodos = get_class_methods($instancia);

            if(in_array($accion, $metodos)){
                $instancia->$accion();
            } else {
                die("El metodo '".$this->accion."' no existe en '".$this->control);
            }
        } else {
            die("El controlador '".$this->control."' no existe - 404 not found");
        }
    }
    
    
    public function setRequest(){
        $parametros = $this->getParamametrosUrl();       
                      
        $this->setControl((!empty ($parametros[1])) ? $parametros[1]    : 'index');
        $this->setAccion((!empty ($parametros[2]) && $parametros[2] != "id" ) ? $parametros[2]   : 'listar'); 
        $this->setPathControl(_modJecatinControl);
        
        foreach($parametros as $indice=>$parametro){
            if(isset($parametros[$indice+1]) && is_dir($this->getPathControl()."$parametro/")){
                
                $dirControlTemp = $this->getPathControl().$parametro.'/'.ucfirst($parametros[$indice+1]).'Control.php';
                
                if(is_file($dirControlTemp)){
                    $this->setPathControl($this->getPathControl().$parametro.'/');
                    
                    $this->setControl($parametros[$indice+1]);
                    
                    $this->setAccion((isset($parametros[$indice+2]) && $parametros[$indice+2] != "id" ) ? $parametros[$indice+2]   : 'listar');
                }
            }
        }        
    }
    
    public function getParamametrosUrl() {
        $parametros = array();
        
        $url = str_replace(array("/index.php","/jecatin"), "", $_SERVER["SCRIPT_NAME"]);
        
        $url = str_replace(array($url,"/index.php", "/jecatin"), "", $_SERVER['REQUEST_URI']);
        
        $urlSplited = explode("/", $url);
        
        foreach ($urlSplited as $indice=>$valor){
            if ($valor != ''){  
                $arrayValorSplited   = explode("-", $valor);
                $parametros[$indice] = "";
                
                foreach($arrayValorSplited as $valorSplited){
                    $parametros[$indice] .= ucfirst(strtolower($valorSplited));
                }
                
                $parametros[$indice] = lcfirst($parametros[$indice]);
            }
        }
        
        return $parametros;
    }

    public function getPathControl() {
        return $this->pathControl;
    }

    public function setPathControl($pathControl) {
        $this->pathControl = $pathControl;
    }
    
    public function setControl($controlRequest){    
        $this->control = ucfirst($controlRequest)."Control";
   }
   
  public function getControl(){
       return $this->control;
   }
   
   public function setAccion($accionRequest){     
       $this->accion =  'get'.ucfirst($accionRequest);
   }
   
   public function getAccion(){
       return $this->accion;
   } 
   
   public function getDocumento() {
       return $this->documento;
   }

   public function setDocumento($documento) {
       $this->documento =& $documento;
   }
   
  
   public function incluirArchivos(){    
       $this->setDocumento(JFactory::getDocument());
       
       preg_match_all("/(jecatin\/.*$)/i", $_SERVER['REQUEST_URI'], $request);
       $request   = explode('/', str_replace("jecatin/","",$request[0][0]));
       $request   = array_reverse($request);
       $request[] = 'jecatin';
       
       $xml = simplexml_load_file('modules/mod_jecatin/jecatinTemplate.xml');
       $this->incluirArchivosRecursivo($xml->modulo, $request);
   }
   
   public function incluirArchivosRecursivo($objetos, $peticion){
       $name = array_pop($peticion);
       
       foreach($objetos as $objeto){
            if($objeto['name'] == $name){
                if(count($objeto->file) > 0){
                    foreach($objeto->file as $file)
                        $this->incluirArchivo($file);
                }
                
                if(count($objeto->controladores->control) > 0)
                    $this->incluirArchivosRecursivo($objeto->controladores->control, $peticion);
                break;
                
           }
       }
   }
   
   public function incluirArchivo($archivo){
       $fileSource = JURI::base().'templates/jecatin/jecatin/'.$archivo['source'];
       
       if(is_file(_modJecatinTemplate."/jecatin/".$archivo['source'])){               
           if($archivo['type'] == 'javascript')
               $this->getDocumento()->addScript($fileSource);
           else if($archivo['type'] == 'stylesheet')
               $this->getDocumento()->addStyleSheet($fileSource, 'text/css', null, array());
       }
   }
}
?>