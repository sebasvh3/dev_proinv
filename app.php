<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ERROR)
//ob_start();
session_start();
define('baseUrl', "http://$_SERVER[SERVER_NAME]".preg_replace('/index(\.php)?\/?$/i',"",$_SERVER['SCRIPT_NAME']));


define('rutaDeLaAplicacion', __DIR__."/");

define('rutaModel',rutaDeLaAplicacion."model/");

define('rutaControles',rutaDeLaAplicacion."controls/");

define('rutaVistas',rutaDeLaAplicacion."views/");
define('rutaEntidades',rutaDeLaAplicacion."entity/");
define('rutaFacades',rutaDeLaAplicacion."facade/");
define('rutaEnviroment',rutaDeLaAplicacion."enviroment/");
		
//echo baseUrl;
//require_once "MetodosUtiles.php";
//require_once "Autenticacion.php";
require_once rutaEnviroment."Ambiente.php";
require_once rutaControles.'RuteadorControl.php';

new RuteadorControl(); 
//var_dump($a);
//var_dump($a->getRequestUri());
//var_dump($a->getParametros());
//echo  "hola mundo";

//session_destroy();

require_once rutaVistas.'footer.php';
