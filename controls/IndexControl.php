<?php



require_once rutaModel.'AbstractControl.php';
//require_once rutaFacades.'ProductoFacade.php';

class IndexControl extends AbstractControl{
    
    public function iniciar(){
        $this->setVistaAccion('login');
        
    }
    
    public function inicio(){
        $this->setVistaAccion('inicio');
    }
    
    public function entrada(){
        $this->setVistaAccion('entrada');
    }
    
    public function salida(){
        $this->setVistaAccion('salida');
    }
}
