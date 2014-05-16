<?php



//           $config['DATABASE'] = $this->schema   ? $this->schema   : Ambiente::$DB ;
//           $config['PASSWORD'] = $this->password ? $this->password : Ambiente::$PASS ;
//           $config['SERVER']   = $this->server   ? $this->server   : Ambiente::$SERVIDOR ;
//           $config['USER']     = $this->user     ? $this->user     : Ambiente::$USER ;


class Ambiente{
    
    public static $SERVIDOR = 'localhost';
    public static $DB = 'dbinventario';
    public static $USER = 'root';
    public static $PASS = 'sql123';
    
    //*** Estados
    public static $EstadoActivo = 'ACT';
    public static $EstadoInactivo = 'DESC';
    
    //*** Transacciones
    public static $Entrada = 1;
    public static $Salida =  2;
    public static $EntradaAveria =  3;
    public static $SalidaAveria =  4;
    public static $EntradaDevolucion = 5;
    public static $SalidaDevolucion = 6;
    
    //*** Bodegas
    public static $BodegaPrincipal = 1;
    public static $BodegaTercerizado = 2;
    
   
}
