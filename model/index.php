<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//error_reporting(E_ERROR);
define('baseUrl', "http://$_SERVER[SERVER_NAME]".preg_replace('/index(\.php)?\/?$/i',"",$_SERVER['SCRIPT_NAME']));
define('rutaDeLaAplicacion', __DIR__."/");
define('rutaModelos',rutaDeLaAplicacion."modelos/");
define('rutaControles',rutaDeLaAplicacion."controls/");
define('rutaVistas',rutaDeLaAplicacion."views/");
		

//require_once "MetodosUtiles.php";
//require_once "Autenticacion.php";
//require_once "Ambiente.php";

include_once rutaControles."RuteadorControl.php";
new RuteadorControl();


?>
