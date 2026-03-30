<?php
session_start();

error_reporting(0);
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");    $objCon      = new Connection();
require_once("../../../class/Util.class.php"); 		    $objUtil     = new Util;
require_once("../../../class/Rce.class.php" );          $objRce      = new Rce;

$objCon->db_connect();

switch ( $_POST['accion'] ) {

    case 'agendarSolicitudAPS':

        $parametros = $objUtil->getFormulario($_POST);

        try {

            $objCon->beginTransaction();

            $parametrosAEnviar                                 = array();        

            $parametrosAEnviar['idSolicitudAPS']               = $parametros['idSolicitudAPS'];

            $parametrosAEnviar['usuarioAgendamientoSolicitud'] = $_SESSION['MM_Username'.SessionName];

            $parametrosAEnviar['estadoSolicitud']              = $parametros['slc_estadoAgendamientoSolicitud'];

            $parametrosAEnviar['prioridadSolicitud']           = $parametros['slc_prioridadAgendamientoSolicitud'];

            $parametrosAEnviar['programaSolicitud']            = $parametros['slc_programaAgendamientoSolicitud'];

            $parametrosAEnviar['observacionSolicitud']         = $parametros['txt_observacionSolicitudAPS'];
          
            $objRce->agendarSolicitudAPS($objCon, $parametrosAEnviar);

            $objRce->insertarCambioEstadoSolicitudAPS($objCon, $parametrosAEnviar);

            $objCon->commit();

            $response = array("status" => "success");

            unset($parametrosAEnviar);

            echo json_encode($response);

        } catch ( PDOException $e ) {

            $objCon->rollback();
            
            $response = array("status" => "error", "message" => $e->getMessage());
            
            echo json_encode($response);
        }

    break;



    case 'cambiarConsultorio':  
        require_once("../../../class/PacienteDau.class.php");              $objPacienteDau        = new PacienteDAU;
        $parametros = $objUtil->getFormulario($_POST);

        try {

            $objCon->beginTransaction();

            $parametrosAEnviar                              = array();

            $respuestaConsulta                              = $objRce->obtenerInfoSolicitudAPS($objCon, $parametros['idSolicitudAPS']);            

            $parametrosAEnviar['idSolicitudAPS']            = $parametros['idSolicitudAPS'];

            $parametrosAEnviar['codigoConsultorioPrevio']   = $respuestaConsulta['codigoConsultorio'];

            $parametrosAEnviar['codigoConsultorioActual']   = $parametros['codigoConsultorio'];

            $parametrosAEnviar['usuarioCambioConsultorio']  = $_SESSION['MM_Username'.SessionName];

            $parametrosAEnviar['idPaciente']                = $respuestaConsulta['idPaciente'];

            $objRce->ingresarCambioConsultorio($objCon, $parametrosAEnviar);

            $objPacienteDau->cambiarConsultorioPaciente($objCon, $parametrosAEnviar);

            $objCon->commit();

            $response = array("status" => "success");

            unset($parametrosAEnviar);

            echo json_encode($response);

        } catch ( PDOException $e ) {

            $objCon->rollback();
            
            $response = array("status" => "error", "message" => $e->getMessage());
            
            echo json_encode($response);
        }

    break;



    case 'obtenerDatosSolicitudAPS':

        $parametros = $objUtil->getFormulario($_POST);

        $datosSolicitudAPS = $objRce->obtenerInfoSolicitudAPS($objCon, $parametros['idSolicitudAPS']);

        echo json_encode($datosSolicitudAPS);

    break;



    case 'obtenerEstadosSolicitudAPS':

        $datosEstadosAPS = $objRce->obtenerEstadosSolicitudAPS($objCon);

        echo json_encode($datosEstadosAPS);

    break;



    case 'obtenerPrioridadesSolicitudAPS':

        $datosPrioridadesAPS = $objRce->obtenerPrioridadesSolicitudAPS($objCon);

        echo json_encode($datosPrioridadesAPS);

    break;



    case 'obtenerProgramasSolicitudAPS':

        $datosProgramasAPS = $objRce->obtenerProgramasSolicitudAPS($objCon);

        echo json_encode($datosProgramasAPS);

    break;

}

$objCon = null;

?>

