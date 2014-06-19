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

class UsuarioFacade extends AbstractFacade {
    public static $GETALLUSUARIO = "getAllUsuario";
    
    public function UsuarioFacade(){
        $this->idcolum='id';
        $this->motor=2;
        $this->schema=  Ambiente::$DB;
        $this->entidad='usuario';
    }
    
    public function getNamedQuery($nameQuery) {
        $querys[self::$GETALLUSUARIO] = 
            "SELECT * FROM {$this->getFullName()} A 
            LEFT JOIN $this->schema.rol B ON A.rol rlike CONCAT('[[,]',B.id,'[],]')
            WHERE 1=1";
        return $querys[$nameQuery];
    }
    
    public function getAllUsuario($nickname, $contrasena = NULL){
        if($contrasena !== NULL || isset($_SESSION['usuario'])){
            $datos   = $contrasena !== NULL ? array('nickname' => $nickname,'contrasena'=>$contrasena) : array('nickname' => $nickname);
            $filtros = $this->getFiltros($datos);
            
            $resultados = $this->runNamedQueryArray(self::$GETALLUSUARIO, $filtros, array(), true);
            if(!empty($resultados)){
                $usuario   = $this->newEntityInstance($resultados[0],'A');
                $rolFacade = $this->factoryModel('RolFacade');
                $rols      = array();
                foreach ($resultados as $resultado){
                    $rol = $rolFacade->newEntityInstance($resultado,'B');
                    $rols[$rol->getId()] = $rol;
                }
                $usuario->setRols($rols);
                return $usuario;
            } 
        }
        
        return FALSE;
    }
}