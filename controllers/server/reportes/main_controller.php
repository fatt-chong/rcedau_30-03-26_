<?php
session_start();

require('../../../class/Connection.class.php'); 	$objCon      = new Connection;
require('../../../class/Connection41.class.php'); 	$objCon41    = new Connection41;
require("../../../class/Util.class.php");       	$objUtil     = new Util;
require("../../../class/Reportes.class.php");   	$objReporte  = new Reportes;

$objCon->db_connect();

$objCon41->db_connect();

switch ( $_POST['accion'] ) {
	case "eliminarPDF":
		$parametros = $objUtil->getFormulario($_POST);
		echo $parametros['nombreArchivo'];
		unlink($_SERVER['DOCUMENT_ROOT'].$parametros['nombreArchivo']);
	break;
	case 'obtenerDauCerradosEnSemanas':
		$parametros           = $objUtil->getFormulario($_POST);
		$dauCerradosEnSemanas = $objReporte->obtenerDauCerradosEnSemanas($objCon41, $parametros['anioResumen']);
		$response             = array("status" => "success", "arrayDau" => $dauCerradosEnSemanas);
		if ( empty($dauCerradosEnSemanas) || is_null($dauCerradosEnSemanas) ) {
			$response = array("status"=>"error");
		}
		echo json_encode($response);
	break;
	case 'obtenerDauEnfermedadesEpidemiologicas':
		$parametros                     = $objUtil->getFormulario($_POST);
		$dauEnfermedadesEpidemiologicas = $objReporte->obtenerDauEnfermedadesEpidemiologicasEnSemanas($objCon41, $parametros['anioResumen']);
		$response                       = array("status" => "success", "arrayDau" => $dauEnfermedadesEpidemiologicas);
		if ( empty($dauEnfermedadesEpidemiologicas) || is_null($dauEnfermedadesEpidemiologicas) ) {
			$response = array("status"=>"error");
		}
		echo json_encode($response);
	break;
	case "obtenerReporteEnfermedadesEpidemiologicas":
		$parametros = $objUtil->getFormulario($_POST);
		$parametros['fechaInicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['fechaTermino'] = $objUtil->fechaInvertida($parametros['fechaTermino']);
		$objCon = $objUtil->cambiarServidorReporte($parametros['fechaInicio'], $parametros['fechaTermino']);
		$reporteEnfermedadesEpidemiologicas = $objReporte->obtenerReporteEnfermedadesEpidemiologicas($objCon, $parametros);
		echo json_encode($reporteEnfermedadesEpidemiologicas);
	break;
	case "obtenerReporteEndovenosoCat4":
		$parametros = $objUtil->getFormulario($_POST);
		$parametros['fechaInicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
		$parametros['fechaTermino'] = $objUtil->fechaInvertida($parametros['fechaTermino']);
		$objCon = $objUtil->cambiarServidorReporte($parametros['fechaInicio'], $parametros['fechaTermino']);
		$reporteEndovenosoCat4 = $objReporte->obtenerReporteEndovenosoCat4($objCon, $parametros);
		echo json_encode($reporteEndovenosoCat4);
	break;
	case "obtenerReportesDiariosDAURCE":
		$parametros = $objUtil->getFormulario($_POST);
		$conexion = ftp_connect("10.6.21.29");
		$login = ftp_login($conexion, "reportesdau", "123reportesdau");
		$archivos = ftp_nlist($conexion, "/dauRCE/reportesDiarioDAURCE/".$parametros["anio"]."/".$parametros["mes"]."/");
		$archivos = str_replace("/dauRCE/reportesDiarioDAURCE/".$parametros["anio"]."/".$parametros["mes"]."/", "", $archivos);
		echo json_encode($archivos);
	break;
}
$objCon = null;
?>