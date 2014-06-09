<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * Description of AbstractFacade
 *
 * @author cirsisdgr
 */

include_once 'MysqlModel.php';

class AbstractFacade {

    public $toShow;
    
    public static $AS400 = 1;
    public static $MYSQL = 2;

    protected $schema = ''; 
    protected $entidad = '';
    protected $idcolum = '';
    protected $namedQueries;
    protected $motor=2;
    
    protected $password;
    protected $server;
    protected $user;
    
    private $error       = false;
    private $mensajeInfo = "";
    public  $paginacion;

    private $entityManager;
    
    protected  function getEntityManager(){
        if(!$this->entityManager)
            $this->setEntityManager();
        
        return $this->entityManager;
    }   
    
    public function setEntityManager() {
        if($this->motor == AbstractFacade::$AS400)
            $this->entityManager = new As400Model($this->schema);
        if($this->motor == AbstractFacade::$MYSQL)
            $this->entityManager = new MysqlModel($this->getConfigEntityManager());
    }
    
    private function getConfigEntityManager(){       
        $config = array();
        
        if($this->motor == AbstractFacade::$AS400){
            
        } elseif($this->motor == AbstractFacade::$MYSQL){
           $config['DATABASE'] = $this->schema   ? $this->schema   : Ambiente::$DB ;
           $config['PASSWORD'] = $this->password ? $this->password : Ambiente::$PASS ;
           $config['SERVER']   = $this->server   ? $this->server   : Ambiente::$SERVIDOR ;
           $config['USER']     = $this->user     ? $this->user     : Ambiente::$USER ;
        }
        
        return $config;
    }

    public function doEdit($objectInstance, $crearAuditoria=true){
        $arrayPop     = FALSE;
        $objectEdited = array();
        
        if(!is_array($objectInstance)){
            $objectInstance = array($objectInstance);
            $arrayPop = TRUE;
        }

        list($objectsToCreate, $objectsToUpdate) = $this->sortObjects($objectInstance);

        foreach(array_chunk($objectsToCreate, 1000) as $objectToCreate){
            $this->createArray($objectToCreate, $crearAuditoria);
            
            if(in_array('setId',get_class_methods(get_class($objectInstance[0])))){
                $lastId = $this->getEntityManager()->getLastId();
                while($objectCreated = array_pop($objectToCreate)){
                    $objectCreated->setId($lastId--);
                    $objectEdited[] = $objectCreated;
                }
            } else
                $objectEdited = array_merge($objectEdited, $objectToCreate);
        }

        foreach($objectsToUpdate as $objectToUpdate){                
            $objectToUpdate = $this->crearAuditoria($objectToUpdate, true);
            $this->update($objectToUpdate);
            
            $objectEdited[] = $objectToUpdate;
        }
        
        return ($arrayPop) ? array_pop($objectEdited) : $objectEdited;
    }
    
    private function sortObjects($objectsInstace){
        $toCreate     = array();
        $toUpdate     = array();
        if($this->idcolum){
            $toUpdateTemp = array();
            $statement  = "SELECT ".$this->idcolum." FROM ".$this->getFullName()." WHERE ".$this->idcolum." in ('";
            $comma      = "";
            foreach($objectsInstace as $objectInstace){
                if(in_array('setId',get_class_methods(get_class($objectInstace))) && $objectInstace->getId() != ''){
                    $toUpdateTemp[$objectInstace->getId()] = $objectInstace;
                    $statement .= $comma.$objectInstace->getId();
                    $comma      = "','";
                } else {
                    $toCreate[] = $objectInstace;
                }
            }
            $statement .= "')";

            $results = $this->executeQuery($statement);

            if(count($results) > 0 && $results != ''){
                foreach($results as $result){
                    $result = array_pop($result);
                    $toUpdate[] = $toUpdateTemp[$result];
                    unset ($toUpdateTemp[$result]);
                }
            }

            foreach($toUpdateTemp as $entity)
                $toCreate[] = $entity;
        } else{
         $toCreate = $objectsInstace;   
        }     
        return array($toCreate,$toUpdate);
    }

    public function crearAuditoria($objectInstance, $existe = false){
        $now = new DateTime();
        
        if(!$existe){
            $objectInstance->setPropietario("PropietarioGenerico");
            $objectInstance->setFecha_crea($now->format('Y-m-d H:i:s'));
        }    
        $objectInstance->setFecha_mod($now->format('Y-m-d H:i:s'));
        $objectInstance->setUsuario("UserGenerico");
        
//        if(!$existe){
//            if(property_exists($objectInstance, 'aufeccreac'))
//                $objectInstance->setAufeccreac(date("Ymd", time()));
//            if(property_exists($objectInstance, 'uspropieta'))
//                $objectInstance->setUspropieta(MetodosUtiles::getValorArrayLogueado("login"));
//            if(property_exists($objectInstance, 'auhorcreac'))
//                $objectInstance->setAuhorcreac(date("His", time()));
//        }
            
        return $objectInstance;
    }

    public function create($objectInstance, $validar = true){
        $statement = $this->sqlBuild($objectInstance, 'INSERT', $validar); 
        
        $result = $this->sentenciaSimple($statement, $this->entidad);
        
        $this->setMessage($result, "almacenado");
    }

    public function createArray($objectsInstance, $crearAuditoria=true){
        if(!empty ($objectsInstance)){
            $auditoria = array();

            if($crearAuditoria){
                $auditoria = $this->newEntityInstance();
                $auditoria = $this->crearAuditoria($auditoria,false);
            }

            $statement = $this->buildInsertArray($objectsInstance, $auditoria);
            
            $result = $this->sentenciaSimple($statement, TRUE);

            $this->setMessage($result, "almacenado");
        }
    }
    
    public function update($objectInstance){
        $entity = $objectInstance;
        
        $statement = $this->sqlBuild($entity, 'UPDATE');
        $result = $this->sentenciaSimple($statement, $this->entidad);

        $this->setMessage($result, "almacenado");
    }

    
    public function executeQuery($query){
        $this->toShow = $query;

        return $this->getEntityManager()->executeQuery($query);
    }
    
    private function sentenciaSimple($query, $toLock = false ){
        $this->toShow = $query;

        return $this->getEntityManager()->sentenciaSimple($query, $toLock);
    }    
    
    public function findById($id, $select = array()){
        $consulta =  "SELECT ";
        
        $consulta .= (empty ($select)) ? " * " : implode(", ", $select);      
        
        $consulta .=  " FROM ".$this->getFullName()." where ".$this->idcolum." = '". $this->validarValueSQL($id)."'";

        $entidades = $this->executeQuery($consulta);
        //***
        $entidad = (!empty($entidades)) ? $this->newEntityInstance($entidades[0]) : '';
        //***
        return $entidad;
   }
   
    public function findEntityById($id, $select = array()){
        $consulta = "SELECT ";
        
        $consulta .= (empty ($select)) ? " * " : implode(", ", $select);
        
        $consulta .= " FROM ".$this->getFullName(). " where ".$this->idcolum." = ".$this->validarValueSQL($id);
        
        $row = $this->getEntityManager()->ejecutarConsulta($consulta);
        
        $this->toShow = $consulta;

        return $this->newEntityInstance($row);
    }


   public function findEntitiesDos($select = array(), $filtros = array()){
        $consulta = "SELECT ";
        //***
        $consulta .= (empty ($select)) ? " * " : implode(", ", $select);      
        //***
        $consulta .= " FROM ".$this->getFullName();
        //***
        $consulta .= " where 1 = 1 ";
        //***
        $consulta .= implode(" ", $filtros);
        //***        
        $entidades = $this->executeQuery($consulta);
        //***
        $lista = array();
        //***
        if(!empty ($entidades)){
            foreach ($entidades as $entidad) {
                $lista[] = $this->newEntityInstance($entidad);
            }
        }
        //***
        return $lista;
   }

    public function getCount($joins=array(), $filtros=array(), $nameQuery='', $params=array()) {
        // ***
        if($nameQuery == ''){
            $query =  "SELECT count(*) AS CANTIDAD FROM ".$this->getFullName(). " A ";
        }else{
            $consulta = $this->getNamedQuery($nameQuery);
            $query = $consulta;            
        }
        // ***
        if (!empty ($joins)){
            foreach ($joins as $value) {
                $query .= $value;
            }
        }
        // ***
        if($nameQuery == '')
            $query .= " where 1 = 1 ";
        // ***
        $query .= implode(" ", $filtros);
        // ***
        $params = $this->validarSQL($params);
        // ***
        if (!empty ($params)){
            foreach ($params as $key => $value) {
                $query = str_replace(":".$key, $value, $query);
            }
        }
        // ***
        $result = $this->getEntityManager()->querySimpleObject($query);
        
        $this->toShow = $query;
        // ***
        $cantidad = mysql_fetch_array ($result);
        // ***
        return $cantidad['CANTIDAD'];
    }    

   /**
    * @param <type> $namequery
    * @param <type> $p parametros
    * @param <type> $f filtros en la consulta
    * @param <type> $item-- Retorna la lista con un item de todos
    * @return <type>
    */
    public function runNamedQuery($namequery, $filtros=array()) {
        $consulta = $this->getNamedQuery($namequery);
        //***
        $consulta .= implode(" ", $filtros);
        //echo $consulta;die;
        //***
        $entidades = $this->getEntityManager()->executeQueryObject($consulta);
        //***
        return $entidades;
    }

    public function runNamedQueryArray($namequery, $filtros=array(), $params=array(), $replaceFields = false) {
        //***
        $consulta = $this->getNamedQuery($namequery);
        // ***
        if($replaceFields)
            $consulta = $this->replaceFields($consulta);
        
        $consulta .= implode(" ", $filtros);
        // ***
        $params = $this->validarSQL($params);
        // ***
        foreach ($params as $key => $value) {
            $consulta = str_replace(":".$key, $value, $consulta);            
        }
        // ***             
        $entidades = $this->executeQuery($consulta);      
        //***
        return $entidades;
    }

    public function runNamedQueryMerge($namequery, $filtros=array()) {
        $consulta = $this->getNamedQuery($namequery);
        $consulta .= implode(" ", $filtros);
        $entidades = $this->executeQuery($consulta);
        //***
        $lista = array();
        //***
        if(!empty ($entidades)){
            foreach ($entidades as $entidad) {
                $lista[] = $this->newEntityInstance($entidad);
            }
        }
        //***
        return $lista;
    }
    
    public function runSimpleNamedQuery($namequery, $filtros=array(), $params=array()) {
        //***
        $consulta = $this->getNamedQuery($namequery);
        //***
        $consulta .= implode(" ", $filtros);
        //***
        $params = $this->validarSQL($params);
        // ***
        foreach ($params as $key => $value) {
            $consulta = str_replace(":".$key, $value, $consulta);
        }
        $this->sentenciaSimple($consulta);
    }

   protected function getFullName(){
       return $this->schema.".".$this->entidad;
   }

   public function sqlBuild($objectInstance, $tipo, $validar = true){
        if($tipo == 'UPDATE')
            return $this->buildUpdate ($objectInstance);
        if($tipo == 'INSERT')
            return $this->buildInsert ($objectInstance, $validar);
        return null;
    }

    public function buildInsert($objectInstance, $validar = true){
        $insert = 'INSERT INTO '.$this->getFullName();
        //***    
        $insert .= ' ('.implode(",", $this->getPublicEntityProperties()).') ';        
        //***
        $insert .= 'values(';
        //***
        $comma = "";        
        foreach($this->getPublicEntityProperties() as $field){
            $method = 'get'.ucfirst(strtolower($field));
            
            $insert .= $comma;
            if($objectInstance->$method() === null)
                $insert .= "null";
            else {
                if($validar){
                    $insert .= "'".$this->validarValueSQL($objectInstance->$method())."'";
                } else {
                    $insert .= "'".$objectInstance->$method()."'";
                }
            }         
            $comma = ",";
        }
        
        $insert .= ') '; 
        return $insert;
    }
    
    public function buildInsertArray($objectsInstance, $auditoria = array()){
        $insert = 'INSERT INTO '.$this->getFullName()." ";        
        $fields = $this->getPublicEntityProperties();        
        $insert .= ' ('.implode(",", $fields).') ';        
        $insert .= 'values';        
        $commaFields = "";        
        $commaRows   = "";             
        
        foreach($objectsInstance as $objectInstance){
            $insert .= $commaRows." (";            
            
            foreach($fields as $field){    
                $method = 'get'.ucfirst($field);
                $insert .= $commaFields;
                if($objectInstance->$method()  !== null){
                    $insert .= "'".$objectInstance->$method()."'";
                } elseif($auditoria && $auditoria->$method() !== null){
                    $insert .= "'".$auditoria->$method()."'";
                } else {
                    $insert .= "null";
                }     
                $commaFields = ",";
            }
            
            $insert     .= ") ";            
            $commaRows   = ",";
            $commaFields = "";
        }        
        return $insert;
    }

    public function buildUpdate($objectInstance){
        $update = 'UPDATE '.$this->getFullName().' SET ';
        $fields = $this->getPublicEntityProperties();
        
        $comma = '';
        foreach ($fields as $field){
            $method = 'get'.ucfirst($field);
            
            if($objectInstance->$method() === null){
                $update .= $comma.$field.'='."null";
                $comma   = ',';
            } else {
                $update .= $comma.$field.'='."'".$this->validarValueSQL($objectInstance->$method())."'";
                $comma   = ',';
            }
        }
        
        $update .= ' where '.$this->idcolum.' = '."'".$objectInstance->getId()."'";

        return $update;
    }

    public function getMax($fieldName, $filtros = array()){
        return $this->getMaxMinValue($fieldName, "MAX", $filtros);
    }
    
    public function getMin($fieldName, $filtros = array()){
        return $this->getMaxMinValue($fieldName, "MIN", $filtros);
    }
    
    public function getMaxMinValue($fieldName, $tipo, $filtros = array()){
        $statement  = 'SELECT '.$tipo.'('.$fieldName.') AS '.$tipo.' FROM '.$this->getFullName().' WHERE 1=1 ';
        
        $statement .= (!empty ($filtros)) ? implode('', $filtros) : '';

        $value      = array_pop($this->executeQuery($statement));    
        
        return (!empty ($value) && $value[$tipo] != null) ? $value[$tipo] : 0;
    }

    public function exists($objectInstance){
        $existe = false;
        if($objectInstance->getid() != null && $objectInstance->getid() > 0){
            $statement  = 'select '.$this->idcolum .' from '.$this->getFullName();
            $statement .= ' where '. $this->idcolum .' = '.$this->validarValueSQL($objectInstance->getid());            
            $entidades = $this->executeQuery($statement);
            $lista=null;
            if(!empty ($entidades)){
                foreach ($entidades as $entidad) {
                    $lista[] = $entidad;
                }
            }
            if(count($lista) > 0)
                $existe = true;
        }
        return $existe;
    }

    public function getPublicEntityProperties($table = null){
        if($table){
            $entityFacade = ucfirst(strtolower($table)).'Facade';

            require_once rutaFacades.$entityFacade.'.php';
            $entityFacade = new $entityFacade();
            
        } else {
            $entityFacade = $this;
        }

        $entidad = $entityFacade->newEntityInstance();
        $keys = array_keys(get_object_vars($entidad));
        
        if(!$table && $this->idcolum !== ''){
            $ind = array_search($this->idcolum,$keys);
            if(!is_bool($ind))
                unset($keys[$ind]);
        }
        return $keys;
    }

    function validarSQL($parametros){
        if(is_array($parametros) || is_object($parametros)){
            $parametros = (array)$parametros;
            $arrayDatos = array();
            // ***
            if(!empty ($parametros)){
                foreach ($parametros as $key => $value) {
                     $metodo           = (is_array($value) || is_object($value)) ? "validarSQL" : "validarValueSQL" ;
                     $arrayDatos[$key] = $this->$metodo($value);
                }        
            }
            return $arrayDatos;
        } else {
            return $this->validarValueSQL($parametros);
        }
    }

    function validarValueSQL($value){
        $isMagicEnable = false;
        // ***
        $temp = null;
        // ***
        if(get_magic_quotes_gpc() != 0) {
            $isMagicEnable = true;
        }
        // ***        
        $temp = htmlentities($value,ENT_QUOTES,"UTF-8");
            // ***
        if($isMagicEnable)
            $temp = stripslashes($temp);
        // ***
        if ($this->motor == AbstractFacade::$MYSQL)
            mysql_escape_string($temp);
        // ***
        return $temp;
    }
    
    public function remove($id){       
        $statement = 'delete from '.$this->getFullName().' where '. $this->idcolum .' = '. $id;            
        
        $result = $this->sentenciaSimple($statement);
        //***
        $this->setMessage($result, "eliminado");
    }
    
    public function removeEntities($filtros = array()){
        $consulta = "DELETE FROM ".$this->getFullName()." WHERE 1 = 1 ";
        //***
        $consulta .= implode(" ", $filtros);      
        
        //***
        $result = $this->sentenciaSimple($consulta);        
        //***
        $this->setMessage($result, "eliminado");
    } 
    
    public function getVars($objectInstance){
        $stdClass = new stdClass();
        $vars     = get_object_vars($objectInstance);
        
        foreach($vars as $key=>$value){
            $stdClass->$key = $value;
        }
        
        return $stdClass;
    }
    
    public function updateEntities($params = array(), $filtros = array(), $auditoria = true){        
        if($auditoria){
            $objetoAuditoria = $this->getVars($this->crearAuditoria($this->newEntityInstance(), false));
            $params = array_merge($params, array_filter(get_object_vars($objetoAuditoria)));
            
        }
        
        $consulta = "UPDATE ".$this->getFullName()." SET ";
        //***        
        $comma = "";
        
        $atributos = $this->getPublicEntityProperties();
//        print_r($atributos);
        
        $params = array_change_key_case($params);
               
        foreach($atributos as $atributo){
            if(isset($params[$this->getAlias().$atributo])){
                $consulta .= $comma.$atributo."='".$params[$this->getAlias().$atributo]."'";
                $comma = ",";
            } else if(isset($params[$atributo])){
                $consulta .= $comma.$atributo."='".$params[$atributo]."'";
                $comma = ",";
                }
//                else{
//                    $consulta .= $params[$atributo] == null ? $comma.$atributo."=null": "";
//                    $comma = ",";
//                }
        }
        //***
        $consulta .= " WHERE 1=1 ";
        //***              
        $consulta .= implode('', $filtros); 
        //***
        
        $result = $this->sentenciaSimple($consulta);        
        //***
        $this->setMessage($result, "actualizado");
    }     
    
    private function setMessage($result, $descripcion){
        if(!$this->error){
            $this->mensajeInfo = "Se han $descripcion los datos correctamente";

            if(!$result){
                $this->error       = true;
                $this->mensajeInfo = "Se ha presentado un error. No se han $descripcion los datos correctamente";
            }
        }
    }
    
    private function getTablesSQL($SQL){
        $expReg  = "/(\w+)\.(\w+|\*)\s*(\w+)?/";
        $results = array();
        $tables  = array();
        preg_match_all($expReg, $SQL, $results, PREG_SET_ORDER);

        $length = count($results);
        for($i = 0; $length > $i; $i++){
            if(isset($results[$i]) && isset ($results[$i][3])){
                foreach($results as $result){
                    if($results[$i][3] == $result[1]){
                        $tables[$results[$i][3]] = $results[$i][2];
                        unset($results[array_search($result, $results)]);
                    }
                }
            }
        }
        return $tables;
    }
    
    private function getAllFields($table, $alias = ''){
        $keys = $this->getPublicEntityProperties($table);
        
        $aliasPunto     = ($alias != '') ? $alias.'.' : '';
        $aliasGuion     = ($alias != '') ? $alias.'_' : '';
        $comma     = '';
        $statement = '';
        foreach($keys as $key){
            $statement .= $comma.$aliasPunto.$key." AS ".$aliasGuion.$key;
            $comma      = ',';
        }
        return $statement;
    }
    
    private function replaceFields($SQL){
        $SQL     = preg_replace("/  /"," ", preg_replace("/\n/", " ", $SQL));
        $tables  = $this->getTablesSQL($SQL);
            if(!empty($tables)){
            $expReg  = "/select\s+([\s\S]+)\s+from/i";
            preg_match_all($expReg, $SQL, $selects, PREG_SET_ORDER);

            foreach($selects as $select){         
                $pattern = "/(^|,)?\s*([\w\.\*]*((\(((?>[^()]+)|(?R))*\)))?[\w\s]*)\s*($|,)?/";
                preg_match_all($pattern, $select[1], $matchesarray, PREG_SET_ORDER);
                $fields = array();
                foreach($matchesarray as $matches){
                    if($matches[2] != '')
                        $fields[] = $matches[2];
                }          
                for($i = 0; count($fields) > $i; $i++){
                    if($fields[$i] == "*"){
                        $fields[$i] = '';
                        $comma     = '';
                        foreach($tables as $alias=>$table){
                            $fields[$i] .= $comma.$this->getAllFields($table, $alias);
                            $comma      = ",";
                        }
                    } elseif(!preg_match("/\sas\s/i", $fields[$i])){
                        preg_match_all("/(\w+\.[\*\w]*)/", $fields[$i], $field, PREG_SET_ORDER);
                        $explode =  explode('.', str_replace(" ", "", $field[0][1]));
                        $alias   =  $explode[0];
                        $field   =  (isset ($explode[1])) ? $explode[1] : '' ;
                        if($field == "*")
                            $fields[$i] = $this->getAllFields($tables[$alias], $alias);
                        else
                            $fields[$i] .= " AS ".$tables[$alias]."_".$field;
                    }
                }             
                $SQL = str_replace($select[1],implode(",", $fields),$SQL);
            }
        }
        return $SQL;    
    }
      
    public function newEntityInstance($fieldsValues = array(), $alias = ''){
        //$className =  ucfirst(strtolower($this->entidad));
        $className =  ucfirst($this->entidad);
        require_once rutaEntidades.$className.'.php';
        $objectInstance = new $className(array());
        
        $this->mergeDatos($objectInstance, $fieldsValues, $alias);
        
        return $objectInstance;        
    }
    
    
    public function mergeDatos(&$objectInstance, $fieldsValues = array(), $alias = ''){            
        $expresiones  = array();
        $expresiones[]= "/^".(($alias != '') ? strtolower($alias).'_' : '')."/";     
        $expresiones[]= "/^".$this->getAlias()."/";
        
	$fieldsValues = array_change_key_case((array)$fieldsValues,CASE_LOWER);
        
        foreach($fieldsValues as $key=>$value){
            $keyReplaced = preg_replace($expresiones, array('',''), $key);
            if(property_exists($objectInstance, $keyReplaced)){
                $method = 'set'.ucfirst($keyReplaced);
                $objectInstance->$method(utf8_encode($value));
            }
        }
    } 
    
    public function getFiltros($valores, $alias = array()){
        $filtros = array();
        
        foreach ($valores as $key=>$value){
            if($value != ''){
                $value  = $this->validarSQL($value);
                
                $filtro  = " AND $key ";
                
                if(!empty($alias)){
                    if(isset($alias[$key])){
                        $filtro = " AND $alias[$key] ";
                    } else {
                        continue;
                    }
                }
                
                $filtros[] = $filtro.(is_array($value)  ? " IN ('".implode("','", $value)."')" : " = '$value'");
            }
        }
        
        return $filtros;
    }
    
    public static function factoryModel(){
        $facadesArray = array();
        
        foreach(func_get_args() as $className){
            $className = ucfirst($className);
            require_once rutaFacades."$className.php";
            $facadesArray[] = new $className(); 
        }
        
        return count($facadesArray) == 1 ? array_pop($facadesArray) : $facadesArray;
    }
    
    public function showSql(){
        echo "<code>".$this->toShow."</code>";
    }
    
    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function getMensajeInfo() {
        return $this->mensajeInfo;
    }
    
    public function showMensajeInfo(){
        echo "<code><font color='red'>".$this->getMensajeInfo()."</font></code>";
    }

    public function setMensajeInfo($mensajeInfo) {
        $this->mensajeInfo = $mensajeInfo;
    }   
    
    public function getAlias() {
        return strtolower(substr($this->entidad, 2).'_');
    }
    
    public function getPaginacion() {
        return $this->paginacion;
    }

    public function setPaginacion($paginacion) {
        $this->paginacion = $paginacion;
    }
}
?>