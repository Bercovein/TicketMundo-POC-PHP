<?php

	/**
	 * Mostrar errores de PHP
	 */
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	/**
	 * Archivos necesarios de inicio
	 */
	require_once "Config/Config.php";
	require_once "Config/Autoload.php";
	include_once "QRcodeLib/qrlib.php";
	
	use Config\Autoload as Autoload;
	use Config\Router 	as Router;
	use Config\Request 	as Request;

	Autoload::start();
	session_start();

	include_once(VIEWS_PATH.'header.php');

	Router::reDirect(new Request());
		
	include_once(VIEWS_PATH."footer.php");

?>
