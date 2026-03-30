<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/RCEDAU/config/config.php");

require_once($_SERVER['DOCUMENT_ROOT']."/RCEDAU/class/Connection.class.php");

require_once($_SERVER['DOCUMENT_ROOT']."/RCEDAU/class/HojaHospitalizacion.class.php");

require_once($_SERVER['DOCUMENT_ROOT']."/RCEDAU/class/RegistroClinico.class.php");

require_once($_SERVER['DOCUMENT_ROOT']."/RCEDAU/class/Util.class.php");


$objCon                 = new Connection();

$objHojaHospitalizacion = new HojaHospitalizacion;

$objRegistroClinico 	= new RegistroClinico();

$objUtil                = new Util;

$parametros             = $objUtil->getFormulario($_POST);

$objCon->db_connect();

switch ( $parametros['accion'] ) {

    case "ingresarHojaHospitalizacion":

        try {

			$objCon->beginTransaction();

            $parametrosHosp['rce_id']                       = $parametros['idRCE'];
            $parametrosHosp['frm_rce_hipotesisInicialSIA']  = $parametros['frm_hipotesisDiagnostica'];
            $objRegistroClinico->actualizaRCESIA($objCon,$parametrosHosp);
            
            $idHojaHospitalizacion = ingresarHojaHospitalizacion($objCon, $objUtil, $objHojaHospitalizacion, $parametros);

            $objCon->commit();

            $response = array("status" => "success", "idHojaHospitalizacion" => $idHojaHospitalizacion);

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

    break;



    case "obtenerAntecedentesMorbidos":

        $antecedentesMorbidos = $objHojaHospitalizacion->obtenerAntecedentesMorbidos($objCon, $parametros);

        echo json_encode($antecedentesMorbidos);

    break;



    case "obtenerDatosHojaHospitalizacion":

        $datosHojaHospitalizacion = $objHojaHospitalizacion->obtenerDatosHojaHospitalizacion($objCon, $parametros);

        echo json_encode($datosHojaHospitalizacion);

    break;



    case "obtenerDatosPaciente":

        $datosPaciente = $objHojaHospitalizacion->obtenerDatosPaciente($objCon, $parametros);

        echo json_encode($datosPaciente);

    break;



    case "obtenerIdHojaHospitalizacion":

        $idHojaHospitalizacion = $objHojaHospitalizacion->obtenerIdHojaHospitalizacion($objCon, $parametros);

        echo json_encode($idHojaHospitalizacion[0]['idHojaHospitalizacion']);

    break;

    case "obtenerIndicaciones":

        $eventos = 1;

        $parametros['rce_id'] = $parametros['idRCE'];

        $indicaciones    = $objRegistroClinico->listarIndicacionesRCE($objCon, $parametros, $eventos);

        echo json_encode($indicaciones);

    break;



    case "obtenerSignosVitales":

        $signosVitales = $objHojaHospitalizacion->obtenerSignosVitales($objCon, $parametros);

        echo json_encode($signosVitales);

    break;

}



function ingresarHojaHospitalizacion ( $objCon, $objUtil, $objHojaHospitalizacion, $parametros ) {

    $parametrosAEnviar = array();

    $parametrosAEnviar['idDau']                    = $objUtil->asignar($parametros['idDau']);

    $parametrosAEnviar['motivoIngreso']            = $parametros['frm_motivoIngreso'];

    $parametrosAEnviar['antecedentesMorbidos']     = $parametros['frm_antecedentesMorbidos'];

    $parametrosAEnviar['examenGeneral']            = $objUtil->asignar($parametros['frm_examenGeneral']);

    $parametrosAEnviar['descripcionExamenGeneral'] = $objUtil->asignar($parametros['frm_describaExamenGeneral']);

    $parametrosAEnviar['conjuntivas']              = $objUtil->asignar($parametros['frm_cabezaConjuntivas']);

    $parametrosAEnviar['escleras']                 = $objUtil->asignar($parametros['frm_cabezaEscleras']);

    $parametrosAEnviar['otrosExamenFisico']        = $objUtil->asignar($parametros['frm_examenFisicoSegmentarioOtros']);

    $parametrosAEnviar['cuelloYColumna']           = $objUtil->asignar($parametros['frm_cuelloYColumna']);

    $parametrosAEnviar['rigidezNuca']              = $objUtil->asignar($parametros['frm_rigidezNuca']);

    $parametrosAEnviar['ganglios']                 = $objUtil->asignar($parametros['frm_ganglios']);

    $parametrosAEnviar['yugular']                  = $objUtil->asignar($parametros['frm_yugular']);

    $parametrosAEnviar['tiroides']                 = $objUtil->asignar($parametros['frm_tiroides']);

    $parametrosAEnviar['torax']                    = $objUtil->asignar($parametros['frm_torax']);

    $parametrosAEnviar['pulmones']                 = $objUtil->asignar($parametros['frm_pulmones']);

    $parametrosAEnviar['descripcionPulmones']      = $objUtil->asignar($parametros['frm_descripcionPulmones']);

    $parametrosAEnviar['rrEn2T']                   = $objUtil->asignar($parametros['frm_corazonRR']);

    $parametrosAEnviar['soplos']                   = $objUtil->asignar($parametros['frm_corazonSoplo']);

    $parametrosAEnviar['descripcionCorazon']       = $objUtil->asignar($parametros['frm_descripcionCorazon']);

    $parametrosAEnviar['inspeccion']               = $objUtil->asignar($parametros['frm_inspeccion']);

    $parametrosAEnviar['palpacion']                = $objUtil->asignar($parametros['frm_palpacion']);

    $parametrosAEnviar['higadoYVesicula']          = $objUtil->asignar($parametros['frm_higadoYVesicula']);

    $parametrosAEnviar['bazo']                     = $objUtil->asignar($parametros['frm_bazo']);

    $parametrosAEnviar['rinionesYPtosUretrales']   = $objUtil->asignar($parametros['frm_riniones']);

    $parametrosAEnviar['hernias']                  = $objUtil->asignar($parametros['frm_hernias']);

    $parametrosAEnviar['tactoRectal']              = $objUtil->asignar($parametros['frm_tactoRectal']);

    $parametrosAEnviar['genitales']                = $objUtil->asignar($parametros['frm_genitales']);

    $parametrosAEnviar['extremidades']             = $objUtil->asignar($parametros['frm_extremidades']);

    $parametrosAEnviar['varices']                  = $objUtil->asignar($parametros['frm_varices']);

    $parametrosAEnviar['edema']                    = $objUtil->asignar($parametros['frm_edema']);

    $parametrosAEnviar['pupilas']                  = $objUtil->asignar($parametros['frm_pupilas']);

    $parametrosAEnviar['reflejosOsteotendinosos']  = $objUtil->asignar($parametros['frm_reflejosOsteotendinosos']);

    $parametrosAEnviar['signosMeningeos']          = $objUtil->asignar($parametros['frm_signosMeningeos']);

    $parametrosAEnviar['focalizacion']             = $objUtil->asignar($parametros['frm_focalizacion']);

    $parametrosAEnviar['conciencia']               = $objUtil->asignar($parametros['frm_conciencia']);

    $parametrosAEnviar['glasgow']                  = $objUtil->asignar($parametros['frm_glasgow']);

    $parametrosAEnviar['ptos']                     = $objUtil->asignar($parametros['frm_ptos']);

    $parametrosAEnviar['hora']                     = $objUtil->asignar($parametros['frm_hora']);

    $parametrosAEnviar['glasgowO']                 = $objUtil->asignar($parametros['frm_glasgowO']);

    $parametrosAEnviar['glasgowV']                 = $objUtil->asignar($parametros['frm_glasgowV']);

    $parametrosAEnviar['glasgowM']                 = $objUtil->asignar($parametros['frm_glasgowM']);

    $parametrosAEnviar['hipotesisDiagnosticas']    = $parametros['frm_hipotesisDiagnostica'];

    $parametrosAEnviar['indicaciones']             = $parametros['frm_indicaciones'];

    $parametrosAEnviar['usuario']                  = $objUtil->usuarioActivo();

    $idHojaHospitalizacion                         = $objHojaHospitalizacion->ingresarHojaHospitalizacion($objCon, $parametrosAEnviar);

    unset($parametrosAEnviar);

    return $idHojaHospitalizacion;

}

$objCon = null;

?>