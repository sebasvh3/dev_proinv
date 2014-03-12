<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'UsuarioFacade.php';
//require_once rutaEntidades.'Categoria.php';

class UsuarioControl extends AbstractControl {
    
    public $categorias;
    
    function UsuarioControl() {
        $this->facade = new UsuarioFacade();
    }
    
    function nuevo(){
        $this->setVistaAccion('usuario/nuevo');
    }
    
    function editar(){
        
    }
    
    
}

