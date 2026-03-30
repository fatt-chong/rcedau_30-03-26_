<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon             = new Connection;
require_once('../../../../class/Util.class.php');           $objUtil            = new Util;
require_once('../../../../class/Especialista.class.php');   $objEspecialista    = new Especialista;
// require_once('../../../../class/Movimiento.class.php');    $objMovimiento     = new Movimiento;
require_once('../../../../class/Bitacora.class.php');       $objBitacora        = new Bitacora;

$parametros = $objUtil->getFormulario($_POST);

switch($parametros['accion']){
    case 'ingresarSolicitudEspecialistaOtro':
        $parametrosSESP['idRCE']                        = $parametros['idRCE'];
        $parametrosSESP['sol_otro_paciente']            = $parametros['idPaciente'];
        $parametrosSESP['id_otro']                      = $parametros['ftm_especialista_otro'];
        $parametrosSESP['sol_otro_observacion']         = $parametros['frm_observacion'];
        $parametrosSESP['idPaciente']         = $parametros['idPaciente'];
        $parametrosSESP['sol_otro_usuario']             = $_SESSION['MM_Username'.SessionName];
        try {

            $objCon->db_connect();
            $objCon->beginTransaction();
            $idSESP = $objEspecialista->ingresarsolicitud_otros_especialidad($objCon, $parametrosSESP);
            ////// AQUI PASO COCHITO    //////
            $subparametrosBitacora['BITid']                 =   $parametros['idDau'];
            $subparametrosBitacora['BITtipo_codigo']        =   9;
            $subparametrosBitacora['BITtipo_descripcion']   =   "LLamado especialista";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSESP['SESPusuario'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>SOLICITUD ESPECIALISTA</b> (".$parametros['NombreEspecialista'].") Observación : ".$parametros['frm_observacion'].".";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO  //////
            $response = array("status" => "success", "idSESP" => $idSESP);
            $objCon->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }

    break;

    case 'aprobarSolicitudEspecialistaOtros':
        $objCon->db_connect();
        $datosSolicitudEspecialista                                 = $objEspecialista->obtenerDatosSolicitudEspecialistaOtros($objCon, $parametros['idsolicitudEspecialista']);
        $parametrosSESP['id_sol_otro']                              = $parametros['idsolicitudEspecialista'];
        $parametrosSESP['sol_otro_usuarioAplica_observacion']       = $parametros['frm_observacionEspecialista'];
        $parametrosSESP['estado_sol_otro']                          = 4;
        $parametrosSESP['sol_otro_usuarioAplica']                   = $_SESSION['MM_Username'.SessionName];

        try {
            $objCon->beginTransaction();
            $idSESP = $objEspecialista->actualizarEstadoSolicitudEspecialidadOtros($objCon, $parametrosSESP);
            ////// AQUI PASO COCHITO //////
            $subparametrosBitacora['BITid']                 =   $parametros['idDau'];
            $subparametrosBitacora['BITtipo_codigo']        =   10;
            $subparametrosBitacora['BITtipo_descripcion']   =   "Evolución Especialista Otros";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSESP['sol_otro_usuarioAplica'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>EVOLUCION ESPECIALISTA</b> (".$parametros['NombreEspecialista'].") Evolución: ".$parametros['frm_observacionEspecialista']." ";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO //////
            $response = array("status" => "success");
            $objCon->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }
    break;

    case 'aprobarSolicitudEspecialista':
        $objCon->db_connect();
        $datosSolicitudEspecialista                         = $objEspecialista->obtenerDatosSolicitudEspecialista($objCon, $parametros['idsolicitudEspecialista']);
        $parametrosSESP['SESPid']                           = $parametros['idsolicitudEspecialista'];
        $parametrosSESP['SESPobservacionEspecialista']      = $parametros['frm_observacionEspecialista'];
        $parametrosSESP['SESPestado']                       = 4;
        $parametrosSESP['SESPusuarioAplica']                = $_SESSION['MM_Username'.SessionName];
        $parametrosSESP['SESPgestionRealizada']             = ( $objUtil->existe($datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado']) && $datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado'] == "S" ) ? "S" : "N";
        $parametrosSESP['SESPusuarioGestionRealizada']      = ( $objUtil->existe($datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado']) && $datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado'] == "S" ) ? $_SESSION['MM_Username'.SessionName] : NULL;
        $parametrosSESP["SESPidProfesionalEspecialista"]    = ( $objUtil->existe($datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado']) && $datosSolicitudEspecialista[0]['SESPespecialistaDeLlamado'] == "S" && $datosSolicitudEspecialista[0]['SESPidProfesionalEspecialista'] == 0 ) ? $objUtil->asignar($parametros["frm_medicoEspecialista"]) : $datosSolicitudEspecialista[0]['SESPidProfesionalEspecialista'];
        $parametrosSESP['SESPobservacionGestionRealizada']  = ( $objUtil->existe($datosSolicitudEspecialista[0]['SESPobservacionGestionRealizada']) ) ? $datosSolicitudEspecialista[0]['SESPobservacionGestionRealizada'] : $objUtil->asignar($parametros['frm_observacionGestionRealizada']);
        try {
            $objCon->beginTransaction();
            $idSESP = $objEspecialista->actualizarEstadoSolicitudEspecialidad($objCon, $parametrosSESP);
            ////// AQUI PASO COCHITO //////
            $subparametrosBitacora['BITid']                 =   $parametros['idDau'];
            $subparametrosBitacora['BITtipo_codigo']        =   10;
            $subparametrosBitacora['BITtipo_descripcion']   =   "Evolución Especialista";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSESP['SESPusuarioAplica'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>EVOLUCION ESPECIALISTA</b> (".$parametros['NombreEspecialista'].") Evolución: ".$parametros['frm_observacionEspecialista']." ";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO //////
            $response = array("status" => "success");
            $objCon->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }
    break;



    case 'buscarMedicosEspecialistas':

        $objCon->db_connect();

        $response = array("status" => "success");

        $medicosEspecialistas = $objEspecialista->buscarMedicosEspecialistas($objCon, $parametros);

        echo json_encode($medicosEspecialistas);

    break;



    case 'busquedaSensitivaEspecialista':

        try {

            $objCon->db_connect();

            $resultadoBusquedaSensitiva = $objEspecialista->sensitivaEspecialidad($objCon, $_POST['term']);

            if ( ! is_null($resultadoBusquedaSensitiva) && ! empty($resultadoBusquedaSensitiva) ){

                echo json_encode($resultadoBusquedaSensitiva);

            }

        } catch (PDOException $e) {

            $e->getMessage();

        }

    break;



    case 'ingresarSolicitudEspecialista':

        $parametrosSESP['SESPfecha'] = date("Y-m-d H:i:s");

        $parametrosSESP['SESPidRCE'] = $parametros['idRCE'];

        $parametrosSESP['SESPidPaciente'] = $parametros['idPaciente'];

        $parametrosSESP['SESPidEspecialidad'] = $parametros['frm_idEspecialidad'];

        $parametrosSESP['SESPobservacion'] = $parametros['frm_observacion'];

        $parametrosSESP['SESPusuario'] = $_SESSION['MM_Username'.SessionName];

        $parametrosSESP['SESPespecialistaDeLlamado'] = ( $objUtil->existe($parametros['frm_especialistaDeLlamado']) ) ? "S" : "N";

        $parametrosSESP['SESPusuarioEspecialistaDeLlamado'] = ( $parametros['frm_especialistaDeLlamado'] == "S" ) ? $_SESSION['MM_Username'.SessionName] : "";

        $parametrosSESP['SESPgestionRealizada'] = ( $objUtil->existe($parametros['frm_gestionRealizada']) ) ?  "S" : "N";

        $parametrosSESP['SESPusuarioGestionRealizada'] = ( $parametros['frm_gestionRealizada'] == "S" ) ? $_SESSION['MM_Username'.SessionName] : "";

        $parametrosSESP["SESPidProfesionalEspecialista"] = $objUtil->asignar($parametros["frm_medicoEspecialista"]);

        $parametrosSESP['SESPobservacionGestionRealizada'] = $objUtil->asignar($parametros['frm_observacionGestionRealizada']);

        $parametrosSESP['SESPidEspecialidadOtro'] = null;
        
        $parametrosSESP['SESPfuente'] = $parametros['SESPfuente'];

        try {

            $objCon->db_connect();
            $objCon->beginTransaction();
            $idSESP = $objEspecialista->ingresarSolicitudEspecialidad($objCon, $parametrosSESP);

            ////// AQUI PASO COCHITO    //////
            $subparametrosBitacora['BITid']                =   $parametros['idDau'];
            $subparametrosBitacora['BITtipo_codigo']        =   9;
            $subparametrosBitacora['BITtipo_descripcion']   =   "LLamado especialista";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSESP['SESPusuario'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>SOLICITUD ESPECIALISTA</b> (".$parametros['frm_especialidad'].") Observación : ".$parametros['frm_observacion'].".";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO  //////

            $response = array("status" => "success", "idSESP" => $idSESP);

            $objCon->commit();

            echo json_encode($response);

        } catch (PDOException $e) {

            $objCon->rollback();

            $response = array("status" => "error", "message" => $e->getMessage());

            echo json_encode($response);

        }

    break;



    case "obtenerPronosticosAltaUrgencia":

		require_once('../../../../class/Pronostico.class.php');

		$objPronostico = new Pronostico;

		$objCon->db_connect();

		$pronosticos = $objPronostico->listarPronosticos($objCon);

		echo json_encode($pronosticos);

	break;

}
?>