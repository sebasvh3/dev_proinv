<?php



require_once rutaModel.'AbstractControl.php';
//require_once rutaFacades.'ProductoFacade.php';

class IndexControl extends AbstractControl{
    
    public function iniciar($datos = array()){
        if(isset($datos['nickname']) && isset($datos['contrasena'])){
            $usuario = $this->factoryModel('UsuarioFacade')->getAllUsuario($datos['nickname'],$datos['contrasena']);
            if($usuario){
                $atributos = array('id','nombre','nickname','rols'=>array('id','descripcion'));
                $_SESSION['usuario'] = $this->getEntitiesToJson($usuario,$atributos);
                return TRUE;
            } 
        }
        $this->setVistaAccion('login');
        return FALSE;
    }
    
    public function inicio(){
        $this->setVistaAccion('inicio');
        return $this;
    }
    
    public function entrada(){
        $this->setVistaAccion('entrada');
    }
    
    public function salida(){
        $this->setVistaAccion('salida');
    }
    
    public function cerrar(){
        unset($_SESSION['usuario']);
        unset($_SESSION['permisos']);
        $this->iniciar();
        // COMENTADO POR QUE ESTA INACTIVO EL HEADER
        //header("Location : $_SERVER[HTTP_HOST]/app.php");
    }
}
