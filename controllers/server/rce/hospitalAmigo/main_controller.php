<?php
session_start();

// require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');             $objCon               = new Connection;  $objCon->db_connect();
require_once("../../../../class/Util.class.php");                   $objUtil              = new Util;
require_once("../../../../class/Rce.class.php");                    $objRce               = new Rce();
require_once("../../../../class/Movimiento.class.php");             $objMovimiento        = new Movimiento;
require_once('../../../../class/Dau.class.php');                    $objDau               = new Dau;
require_once('../../../../class/MapaPiso.class.php');               $objMapaPiso          = new MapaPiso;
require_once('../../../../class/RegistroClinico.class.php');        $objRegistroClinico   = new RegistroClinico;
require_once("../../../../class/CMBD.class.php");                   $objCMBD              = new CMBD;
require_once('../../../../class/SqlDinamico.class.php');            $objSqlDinamico       = new SqlDinamico;
require_once('../../../../class/Diagnosticos.class.php');           $objDiagnosticos      = new Diagnosticos;
require_once('../../../../class/AltaUrgencia.class.php');           $objAltaUrgencia      = new AltaUrgencia;
require_once('../../../../class/Evolucion.class.php');              $objEvolucion         = new Evolucion;
require_once('../../../../class/Paciente.class.php');               $objPaciente          = new Paciente;
require_once('../../../../class/FormularioSeguimiento.class.php');  $objFormulario        = new FormularioSeguimiento;
require_once('../../../../class/Imagenologia.class.php');           $objImagenologia      = new Imagenologia;
require_once('../../../../class/Laboratorio.class.php');            $objLaboratorio       = new Laboratorio;
require_once('../../../../class/Categorizacion.class.php');         $objCategorizacion    = new Categorizacion;
require_once('../../../../class/HospitalAmigo.class.php');               $objHospitalAmigo          = new HospitalAmigo;



$parametros = $objUtil->getFormulario($_POST);



switch ($parametros['accion']) {
  case "ingresarFamiliarOAcompaniante":
    try {
      $objCon->beginTransaction();

      $rsHorarioServidor                = $objUtil->getHorarioServidor($objCon);
      $datosAcompaniante = array(
          "idDau" => $objUtil->asignar($parametros["idDau"]),
          "entregaInformacion" => $objUtil->asignar($parametros["entregaInformacion"]),
          "motivo" => $objUtil->asignar($parametros["motivo"]),
          "nombreAcompaniante" => $objUtil->asignar($parametros["nombreAcompaniante"]),
          "horaEntregaInformacionMedica" => $rsHorarioServidor[0]['hora'],
          "idUsuarioMedico" => $_SESSION['MM_Username'.SessionName],
          "nombreMedico" => $_SESSION['MM_UsernameName'.SessionName]
      );
      $rsobtenerAcompaniante = $objHospitalAmigo->obtenerAcompaniante($objCon, $datosAcompaniante);
      // print('<pre>'); print_r($rsobtenerAcompaniante); print('</pre>');
      if(count($rsobtenerAcompaniante) > 0 ){
        $objHospitalAmigo->UpdateFamiliarOAcompaniante($objCon, $datosAcompaniante);
        $idDauAcompaniante = $rsobtenerAcompaniante[0]['idDauAcompaniante'];

      }else{
        $idDauAcompaniante = $objHospitalAmigo->ingresarFamiliarOAcompaniante($objCon, $datosAcompaniante);
      }

      $objCon->commit();

      echo json_encode(array(
        "status" => "success",
        "idDauAcompaniante" => $idDauAcompaniante
      ));

    } catch (PDOException $e) {
      $objCon->rollback();

      echo json_encode(array(
        "status" => "error",
        "message" => $e->getMessage()
      ));
    }
    break;



  case "obtenerAcompaniante":
    $idDau = $objUtil->asignar($parametros["idDau"]);

    echo json_encode($objHospitalAmigo->obtenerAcompaniante($objCon,array("idDau" => $idDau)));
    break;
    break;



  case "obtenerHoraServidor":
    echo json_encode(date("H:i:s"));
    break;



  case "obtenerMedicoTratante":
    $idDau = $objUtil->asignar($parametros["idDau"]);

    echo json_encode($objHospitalAmigo->obtenerMedicoTratante($objCon,
      array("idDau" => $idDau)
    ));
    break;
}

$objCon = null;
$objHospitalAmigo = null;
$objUtil = null;
