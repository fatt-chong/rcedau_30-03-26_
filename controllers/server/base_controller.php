<?php   
session_start();
require_once('../../class/Connection.class.php');
require_once("../../class/Util.class.php"); $objUtil = new Util;
$objCon = new Connection; $link = $objCon->db_connect();
$datos = $objUtil->getFormulario($_POST);

switch($_POST['accion']){
	case "unsetSesion":
			unset($_SESSION['modulos']);
	break;

	case "unsetSesionDetalle":
			unset($_SESSION['modulos']["orden_compra"]["orden_compra_detalle"]);
			echo "unset";
	break;
}
/*$hoy = date('d-m-Y');
switch($hoy){
	case "17-02-2019":
		return true;
	break;
	case "18-02-2019":
		return true;
	break;
	case "19-02-2019":
		return true;
	break;
	default:
		return false;
	break;
}*/
?>