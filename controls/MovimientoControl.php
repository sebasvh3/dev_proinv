<?php
require_once rutaModel.'AbstractControl.php';
require_once rutaFacades.'MovimientoFacade.php';
require_once rutaFacades.'CategoriaFacade.php';
//require_once rutaEntidades.'Categoria.php';

class MovimientoControl extends AbstractControl {
    
    public $categorias;
    
    function MovimientoControl() {
        $this->facade = new MovimientoFacade();
    }
    
    public function entrada(){
        $this->setCategorias();
        $this->setVistaAccion('movimiento/entrada');
    }
    
    public function salida(){
        $this->setVistaAccion('movimiento/salida');
    }
    
    public function setCategorias(){
        $categoriaFacade = new CategoriaFacade();
        $this->categorias = $categoriaFacade->getCategoriasActivas();
    }

    public function getCategorias(){
        return $this->categorias;
    }
}

