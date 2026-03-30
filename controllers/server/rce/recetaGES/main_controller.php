<?php
session_start();
// error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');             $objCon               = new Connection;  $objCon->db_connect();
require_once("../../../../class/Util.class.php");                   $objUtil              = new Util;
require_once("../../../../class/RecetaGES.class.php");              $objRecetaGES         = new RecetaGES();

$parametros             = $objUtil->getFormulario($_POST);
$objCon->db_connect();

switch ($parametros['accion']) {
  case "ingresarRecetaGES":
    try {
      $objCon->beginTransaction();
      $idRecetaGES = ingresarRecetaGES($objCon, $objUtil, $objRecetaGES, $parametros);
      ingresarDetalleRecetaGES($objCon, $objUtil, $objRecetaGES, $idRecetaGES, $parametros["detalleRecetaGES"]);
      $objCon->commit();
      $response = array("status" => "success", "idRecetaGES" => $idRecetaGES);
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
    break;
  case "obtenerMedicamentos":
    $medicamentos = $objRecetaGES->obtenerMedicamentos($objCon);
    echo json_encode($medicamentos);
    break;
  case "obtenerDetalleRecetaGES":
    $recetaGES = $objRecetaGES->obtenerDetalleRecetaGES($objCon, $parametros);
    echo json_encode($recetaGES);
    break;
}

function ingresarRecetaGES($objCon, $objUtil, $objRecetaGES, $parametros) {
  $parametrosAEnviar = array(
    "idDau" => $objUtil->asignar($parametros["idDau"]),
    "usuarioIngresa" => $objUtil->usuarioActivo()
  );
  return $objRecetaGES->ingresarRecetaGES($objCon, $parametrosAEnviar);
}
function ingresarDetalleRecetaGES($objCon, $objUtil, $objRecetaGES, $idRecetaGES, $detalleReceta) {
  $parametrosAEnviar = array();
  foreach ($detalleReceta AS $detalle) {
    $parametrosAEnviar = array(
      "idRecetaGES" => $idRecetaGES,
      "idMedicamentoRecetaGES" => $objUtil->asignar($detalle["idMedicamentoRecetaGES"]),
      "dosis" => $objUtil->asignar($detalle["dosis"]),
      "dias" => $objUtil->asignar($detalle["dias"])
    );
    $objRecetaGES->ingresarDetalleRecetaGES($objCon, $parametrosAEnviar);
    $parametrosAEnviar = null;
  }
}
$objCon = null;