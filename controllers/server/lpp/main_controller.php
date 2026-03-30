<?php
session_start();

require("../../../config/config.php");
require_once('../../../class/Connection.class.php');    $objCon         = new Connection;  $objCon->db_connect();
require_once("../../../class/Util.class.php");          $objUtil        = new Util;
require_once("../../../class/LPP.class.php");           $objLPP         = new LPP();


$objCon   = new Connection();
$objLPP   = new LPP();
$objUtil  = new Util();

$parametros = $objUtil->getFormulario($_POST);

$objCon->db_connect();

switch ($parametros['accion']) {
  case "ingresarLPP":
    try {
			$objCon->beginTransaction();

			$parametrosIngresarLPP = array(
				"idDau" => $objUtil->asignar($parametros["idDau"]),
        "idsValoracionPiel" => $objUtil->asignar($parametros["idsValoracionPiel"]),
        "descripcionesValoracionPiel" => $objUtil->asignar($parametros["descripcionesValoracionPiel"]),
        "zonaAfectada" => $objUtil->asignar($parametros["zonaAfectada"]),
        "puntajeEvaluacion" => $objUtil->asignar($parametros["puntajeEvaluacion"]),
        "idRiesgo" => $objUtil->asignar($parametros["idRiesgo"]),
        "idAplicacionSEMP" => $objUtil->asignar($parametros["idAplicacionSEMP"]),
        "idCambioPosicion" => $objUtil->asignar($parametros["idCambioPosicion"]),
        "registroEjecucion" => $objUtil->asignar($parametros["registroEjecucion"]),
        "usuario" => $_SESSION['MM_Username'.SessionName]
			);

			$idLPP = $objLPP->ingresarLPP($objCon, $parametrosIngresarLPP);

			$objCon -> commit();

			echo json_encode(
        array(
          "status" => "success",
          "idLPP" => $idLPP
        )
      );

		} catch (PDOException $e) {
			$objCon -> rollback();
			echo json_encode(
				array(
					"status" => "error",
					"message" => $e -> getMessage()
				)
			);
		}
    break;



  case "obtenerAplicacionesSEMP":
		echo json_encode($objLPP->obtenerAplicacionesSEMP($objCon));
    break;



  case "obtenerCambiosPosiciones":
    echo json_encode($objLPP->obtenerCambiosPosiciones($objCon));
    break;



  case "obtenerDatosSelectsYLPP":
    $datosSelects = array(
      "aplicacionesSEMP" => $objLPP->obtenerAplicacionesSEMP($objCon),
      "cambiosPosiciones" => $objLPP->obtenerCambiosPosiciones($objCon),
      "riesgos" => $objLPP->obtenerRiesgos($objCon),
      "valoracionesPiel" => $objLPP->obtenerValoracionesPiel($objCon),
      "LPP" => $objLPP->obtenerLPP($objCon, array("idDau" => $parametros["idDau"]))
    );
    echo json_encode($datosSelects);
    break;



  case "obtenerRiesgos":
		echo json_encode($objLPP->obtenerRiesgos($objCon));
    break;



  case "obtenerValoracionesPiel":
		echo json_encode($objLPP->obtenerValoracionesPiel($objCon));
    break;
}

$objCon = null;
$objLPP = null;
$objUtil = null;
