<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioFacade
 *
 * @author jduque
 */
require_once rutaModel.'AbstractFacade.php';

class RolFacade extends AbstractFacade{    
    public function RolFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='rol';
    }
    
    public function getNamedQuery($nameQuery) {
        $querys[] = array();
        return $querys[$nameQuery];
    }
}