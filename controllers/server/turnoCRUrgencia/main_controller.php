<?php
session_start();
error_reporting(0);
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 		$objCon              = new Connection; $objCon->db_connect();
require_once("../../../class/Util.class.php");              $objUtil            = new Util;
require_once("../../../class/TurnoCRUrgencia.class.php");   $objTurno           = new TurnoCRUrgencia();
require_once("../../../class/AltaUrgencia.class.php"); 	    $objAltaUrgencia    = new AltaUrgencia();
require_once("../../../class/Pizarra.class.php");      $objPizarra    = new Pizarra();
switch ( $_POST['accion'] ) {
    case 'ObtenerDatosPizarra':
        $parametros     = $objUtil->getFormulario($_POST);
        try {
            $objCon->beginTransaction(); 
            $parametrosPizarra['id_pizarra']    = $_POST['id_pizarra'];
            $arrayMedico                        = [];
            $arrayTens                          = [];
            $arrayEnfermero                     = [];
            $arrayCirujano                      = [];
            $rsPizarraDetalle                   = $objPizarra->SelectPizarraDetalle($objCon,$parametrosPizarra);
            foreach ($rsPizarraDetalle as $fila) {
                $fila['rut_profesional_digito'] = $fila['rut_profesional']."-".$objUtil->generaDigito($fila['rut_profesional']);
                switch ($fila['rol']) {
                case 'MEDICO':
                  $arrayMedico[] = $fila;
                  break;
                case 'TENS':
                  $arrayTens[] = $fila;
                  break;
                case 'EU':
                  $arrayEnfermero[] = $fila;
                  break;
                case 'CIRUJANO':
                  $arrayCirujano[] = $fila;
                  break;
              }
            }
            $response            = array("status" => "success","arrayMedico" => $arrayMedico, "arrayTens" => $arrayTens, "arrayEnfermero" => $arrayEnfermero, "arrayCirujano" => $arrayCirujano);
            $objCon->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "mensaje" => $e->getMessage());
            echo json_encode($response);
        }
    break;
    case 'guardarPizarra':
        $parametros     = $objUtil->getFormulario($_POST);
        try {
            $objCon->beginTransaction(); 
            $turnoJson  = $_POST['turno'] ?? '';
            $turno      = json_decode($turnoJson, true);

            $horarioServidor                            = $objUtil->getHorarioServidor($objCon);
            $parametrosPizarra['usuario_crea']          = $_SESSION['MM_Username'.SessionName];
            $parametrosPizarra['fecha_crea']            = $parametros['frm_fecha_pizarra'];
            $parametrosPizarra['hora_crea']             = $horarioServidor[0]['hora'];
            $parametrosPizarra['idTipoHorarioTurno']    = $parametros['horarioPizarra'];
            $id_pizarra                                 = $objPizarra->InsertPizarra($objCon, $parametrosPizarra);

            foreach ($turno as $i => $t) {
                $parametrosPizarraDetalle['id_pizarra']         = $id_pizarra;
                $parametrosPizarraDetalle['seccion_id']         = (int)($t['seccion'] ?? 0);
                $parametrosPizarraDetalle['seccion_nombre']     = trim($t['seccion_nombre'] ?? '');
                $parametrosPizarraDetalle['rol']                = trim($t['rol'] ?? '');
                $parametrosPizarraDetalle['nombre_profesional'] = trim($t['nombre'] ?? '');
                $parametrosPizarraDetalle['rut_profesional']    = isset($t['id_usuario']) && $t['id_usuario'] !== '' ? (int)$t['id_usuario'] : null;
                $objPizarra->InsertPizarradetalle($objCon, $parametrosPizarraDetalle);
            }
            $response            = array("status" => "success","id_pizarra" => $id_pizarra);
            $objCon->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "mensaje" => $e->getMessage());
            echo json_encode($response);
        }
    break;

    case 'verificarProfesionalTurnoUrgencia':
        $parametros         = $objUtil->getFormulario($_POST);
        $respuestaConsulta  = $objTurno->verificarProfesionalTieneTurnoUrgencia($objCon, $parametros['idProfesional']);
        $response           = array("status" => "success");
        if ( empty($respuestaConsulta) || is_null($respuestaConsulta) ) {
            $response       = array("status" => "error");
        }
        echo json_encode($response);
    break;
    case 'guardarDatosTurno':
        $parametros = $objUtil->getFormulario($_POST);
        
        try {
			$objCon->beginTransaction();
            $idTurnoCRUrgencia  = insertarTurnoCRUgencia($objCon, $objTurno, $parametros);
            insertarNumeroHospitalizaciones($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia);
            insertarNumeroHospitalizacionesUrgencia($objCon, $objTurno, $idTurnoCRUrgencia);
            insertarPacientesEspera($objCon, $objTurno, $idTurnoCRUrgencia);
            insertarSolicitudesEspecialistas($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia);
            insertarCirugiasRealizadas($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia);
            insertarTiemposAtencion($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia);
            insertarTiemposPromedioCategorizacion($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia);
            insertarHospitalizacionDetalle($objCon, $objUtil, $objTurno, $idTurnoCRUrgencia,$objAltaUrgencia);
			$response            = array("status" => "success","idTurnoCRUrgencia" => $idTurnoCRUrgencia);
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "mensaje" => $e->getMessage());
			echo json_encode($response);
		}
    break;
}
$objCon = NULL;
function insertarHospitalizacionDetalle ( $objCon, $objUtil, $objTurno, $idTurnoCRUrgencia, $objAltaUrgencia ) {
    $rsverificarPacientesHospitalizados    = $objTurno->verificarPacientesHospitalizados($objCon);
    for ( $i = 0; $i < count($rsverificarPacientesHospitalizados); $i++ ) {
        $observaciones                                              = "";
        $observacionesCont                                          = 0;
        $parametrosHospitalizaciones['idTurnoCRUrgencia']           =  $idTurnoCRUrgencia;
        $parametrosHospitalizaciones['sala']                        = $rsverificarPacientesHospitalizados[$i]['sal_descripcion'];
        $parametrosHospitalizaciones['cama']                        = $rsverificarPacientesHospitalizados[$i]['cam_descripcion'];
        $parametrosHospitalizaciones['nombre_paciente']             = $rsverificarPacientesHospitalizados[$i]['nombre'];
        $parametrosHospitalizaciones['dau']                         = $rsverificarPacientesHospitalizados[$i]['dau_id'];
        $parametrosHospitalizaciones['diagnostico']                 = $rsverificarPacientesHospitalizados[$i]['regHipotesisInicial'];

        $respuestaConsulta                                          = $objAltaUrgencia->obtenerDatosIndicacionAltaUrgenciarRCE($objCon, $rsverificarPacientesHospitalizados[$i]['regId']);
        $parametrosHospitalizaciones['destino']                     = $respuestaConsulta[0]['descripcionIndicacionEgreso'];


        if($rsverificarPacientesHospitalizados[$i]['Procedimiento'] > 0){
            $observacionesCont++;
            $observaciones  .= "Procedimientos pendientes : ".$rsverificarPacientesHospitalizados[$i]['Procedimiento'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Imagenologia'] > 0){
            $observacionesCont++;
            $observaciones  .= "Imagenologia pendientes : ".$rsverificarPacientesHospitalizados[$i]['Imagenologia'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Tratamiento'] > 0){
            $observacionesCont++;
            $observaciones  .= "Tratamientos pendientes : ".$rsverificarPacientesHospitalizados[$i]['Tratamiento'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Laboratorio'] > 0){
            $observacionesCont++;
            $observaciones  .= "Laboratorio pendientes : ".$rsverificarPacientesHospitalizados[$i]['Laboratorio'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Otros'] > 0){
            $observacionesCont++;
            $observaciones  .= "Otros pendientes : ".$rsverificarPacientesHospitalizados[$i]['Otros'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Especialidad'] > 0){
            $observacionesCont++;
            $observaciones  .= "Especialidad pendientes : ".$rsverificarPacientesHospitalizados[$i]['Especialidad'].". ";
        }
        if($observacionesCont == 0){
          $observaciones    = " - ";  
        }
        $parametrosHospitalizaciones['observaciones']               = $observaciones ;
        $parametrosHospitalizaciones['tipo']                        = 2;
        $parametrosHospitalizaciones['categorizacion']              = $rsverificarPacientesHospitalizados[$i]['dau_categorizacion_actual'];

        $objTurno->insertarHospitalizacionesDetalle($objCon,$parametrosHospitalizaciones);
    }

    $rsverificarPacientesHospitalizados    = $objTurno->verificarPacientesREA($objCon);
    for ( $i = 0; $i < count($rsverificarPacientesHospitalizados); $i++ ) {
        $observaciones                                              = "";
        $observacionesCont                                          = 0;
        $parametrosHospitalizaciones['idTurnoCRUrgencia']           =  $idTurnoCRUrgencia;
        $parametrosHospitalizaciones['sala']                        = $rsverificarPacientesHospitalizados[$i]['sal_descripcion'];
        $parametrosHospitalizaciones['cama']                        = $rsverificarPacientesHospitalizados[$i]['cam_descripcion'];
        $parametrosHospitalizaciones['nombre_paciente']             = $rsverificarPacientesHospitalizados[$i]['nombre'];
        $parametrosHospitalizaciones['dau']                         = $rsverificarPacientesHospitalizados[$i]['dau_id'];
        $parametrosHospitalizaciones['diagnostico']                 = $rsverificarPacientesHospitalizados[$i]['regHipotesisInicial'];


        if($rsverificarPacientesHospitalizados[$i]['Procedimiento'] > 0){
            $observacionesCont++;
            $observaciones  .= "Procedimientos pendientes : ".$rsverificarPacientesHospitalizados[$i]['Procedimiento'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Imagenologia'] > 0){
            $observacionesCont++;
            $observaciones  .= "Imagenologia pendientes : ".$rsverificarPacientesHospitalizados[$i]['Imagenologia'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Tratamiento'] > 0){
            $observacionesCont++;
            $observaciones  .= "Tratamientos pendientes : ".$rsverificarPacientesHospitalizados[$i]['Tratamiento'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Laboratorio'] > 0){
            $observacionesCont++;
            $observaciones  .= "Laboratorio pendientes : ".$rsverificarPacientesHospitalizados[$i]['Laboratorio'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Otros'] > 0){
            $observacionesCont++;
            $observaciones  .= "Otros pendientes : ".$rsverificarPacientesHospitalizados[$i]['Otros'].". ";
        }if($rsverificarPacientesHospitalizados[$i]['Especialidad'] > 0){
            $observacionesCont++;
            $observaciones  .= "Especialidad pendientes : ".$rsverificarPacientesHospitalizados[$i]['Especialidad'].". ";
        }
        if($observacionesCont == 0){
          $observaciones    = " - ";  
        }
        $parametrosHospitalizaciones['observaciones']               = $observaciones ;
        $parametrosHospitalizaciones['tipo']                        = 1;
        $parametrosHospitalizaciones['categorizacion']              = $rsverificarPacientesHospitalizados[$i]['dau_categorizacion_actual'];
        if($rsverificarPacientesHospitalizados[$i]['dau_id'] > 0){
            $respuestaConsulta                                          = $objAltaUrgencia->obtenerDatosIndicacionAltaUrgenciarRCE($objCon, $rsverificarPacientesHospitalizados[$i]['regId']);
            $parametrosHospitalizaciones['destino']                     = $respuestaConsulta[0]['descripcionIndicacionEgreso'];
        }else{
            $parametrosHospitalizaciones['dau']                     = "";
            $parametrosHospitalizaciones['destino']                 = "";
            $parametrosHospitalizaciones['diagnostico']             = "";
            $parametrosHospitalizaciones['observaciones']           = "Sin Paciente";
        }
        $objTurno->insertarHospitalizacionesDetalle($objCon,$parametrosHospitalizaciones);
    }

}
function insertarTurnoCRUgencia ( $objCon, $objTurno, $parametros ) {
    $parametrosAEnviar[]                                        = array();
    $parametrosAEnviar['idTipoHorarioTurno']                    = $parametros['frm_tipoHorarioTurno'];
    $infoProfesional                                            = $objTurno->obtenerInfoProfesionalPorRun($objCon, $parametros['frm_idProfesionalEntregaTurno']);
    $parametrosAEnviar['profesionalEntregaTurno']               = $infoProfesional['idusuario'];
    $infoProfesional                                            = $objTurno->obtenerInfoProfesionalPorRun($objCon, $parametros['frm_idProfesionalRecibeTurno']);
    $parametrosAEnviar['profesionalRecibeTurno']                = $infoProfesional['idusuario'];
    if($parametros['novedades_turno_si_no'] == "N"){
        $parametrosAEnviar['novedadesTurno']    =  "No";
    }else{
        $parametrosAEnviar['novedadesTurno']    =  $parametros['novedades_general'];
    }
    $parametrosAEnviar['novedades_general']         =  $parametros['novedades_general'];
    $parametrosAEnviar['novedades_adm']             =  $parametros['novedades_adm'];
    $parametrosAEnviar['novedades_infra']           =  $parametros['novedades_infra'];
    $parametrosAEnviar['novedades_equip']           =  $parametros['novedades_equip'];
    $parametrosAEnviar['novedades_eventos']         =  $parametros['novedades_eventos'];
    $parametrosAEnviar['novedades_turno_si_no']     =  $parametros['novedades_turno_si_no'];    
    $parametrosAEnviar['med_jef_turno_rut']         =  $parametros['medico_jef_turno_rut'];  
    $parametrosAEnviar['med_jef_turno_nombre']      =  $parametros['medico_jef_turno'];  
    $parametrosAEnviar['frm_fechaActualTurno']      =  $parametros['frm_fechaActualTurno']." ".$parametros['frm_horaActualTurno'];  
    // Jefe de Turno de Enfermería (opcional)
    $parametrosAEnviar['enf_jef_turno_rut']         =  isset($parametros['enf_jef_turno_rut']) ? $parametros['enf_jef_turno_rut'] : null;  
    $parametrosAEnviar['enf_jef_turno_nombre']      =  isset($parametros['enf_jef_turno']) ? $parametros['enf_jef_turno'] : null;  
    
    if($parametros['chk_enfermeria'] == 'S'){
        $parametrosAEnviar['tipo'] = 2;
    }else{
        $parametrosAEnviar['tipo'] = 1;
    }

    // Nuevos campos Entrega y Recursos
    $parametrosAEnviar['entrega_conforme']          = isset($parametros['entrega_conforme']) ? $parametros['entrega_conforme'] : 'S';
    $parametrosAEnviar['entrega_no_motivo']         = isset($parametros['entrega_no_motivo']) ? trim($parametros['entrega_no_motivo']) : null;
    $parametrosAEnviar['bic_cantidad']              = isset($parametros['bic_cantidad']) ? (int)$parametros['bic_cantidad'] : 0;
    $parametrosAEnviar['ecografo_disponible']       = isset($parametros['ecografo_disponible']) ? $parametros['ecografo_disponible'] : 'S';
    $parametrosAEnviar['ecografo_no_motivo']        = isset($parametros['ecografo_no_motivo']) ? trim($parametros['ecografo_no_motivo']) : null;
    $parametrosAEnviar['celulares_cantidad']        = isset($parametros['celulares_cantidad']) ? (int)$parametros['celulares_cantidad'] : 0;

    $idTurnoCRUrgencia                              =  $objTurno->insertarTurnoCRUrgencia($objCon, $parametrosAEnviar);

    $tablaMedicos           = json_decode($parametros['residentes'], true);
    $n                      = is_array($tablaMedicos) ? count($tablaMedicos) : 0;
    for ($i = 0; $i < $n; $i++) {
        $r      = $tablaMedicos[$i];
        $parametrosMedicos['idTurnoCRUrgencia'] = $idTurnoCRUrgencia;
        $parametrosMedicos['rut']               = $r['id'];
        $parametrosMedicos['nombre']            = $r['nombre'];
        $parametrosMedicos['tipo']              = 1;
        $parametrosMedicos['nombre_tipo']       = "Medicos";
        $objTurno->insertarTurnoMedicos($objCon, $parametrosMedicos);
    }

    $tablaMedicos           = json_decode($parametros['cirujanos'], true);
    $n                      = is_array($tablaMedicos) ? count($tablaMedicos) : 0;
    for ($i = 0; $i < $n; $i++) {
        $r      = $tablaMedicos[$i];
        $parametrosMedicos['idTurnoCRUrgencia'] = $idTurnoCRUrgencia;
        $parametrosMedicos['rut']               = $r['id'];
        $parametrosMedicos['nombre']            = $r['nombre'];
        $parametrosMedicos['tipo']              = 2;
        $parametrosMedicos['nombre_tipo']       = "Cirujanos";
        $objTurno->insertarTurnoMedicos($objCon, $parametrosMedicos);
    }

    // TENS
    if(isset($parametros['tens'])){
        $tablaMedicos           = json_decode($parametros['tens'], true);
        $n                      = is_array($tablaMedicos) ? count($tablaMedicos) : 0;
        for ($i = 0; $i < $n; $i++) {
            $r      = $tablaMedicos[$i];
            $parametrosMedicos['idTurnoCRUrgencia'] = $idTurnoCRUrgencia;
            $parametrosMedicos['rut']               = $r['id'];
            $parametrosMedicos['nombre']            = $r['nombre'];
            $parametrosMedicos['tipo']              = 3;
            $parametrosMedicos['nombre_tipo']       = "TENS";
            $objTurno->insertarTurnoMedicos($objCon, $parametrosMedicos);
        }
    }

    // Enfermeros
    if(isset($parametros['enfermeros'])){
        $tablaMedicos           = json_decode($parametros['enfermeros'], true);
        $n                      = is_array($tablaMedicos) ? count($tablaMedicos) : 0;
        for ($i = 0; $i < $n; $i++) {
            $r      = $tablaMedicos[$i];
            $parametrosMedicos['idTurnoCRUrgencia'] = $idTurnoCRUrgencia;
            $parametrosMedicos['rut']               = $r['id'];
            $parametrosMedicos['nombre']            = $r['nombre'];
            $parametrosMedicos['tipo']              = 4;
            $parametrosMedicos['nombre_tipo']       = "Enfermeros";
            $objTurno->insertarTurnoMedicos($objCon, $parametrosMedicos);
        }
    }

    return $idTurnoCRUrgencia;

}
function insertarNumeroHospitalizaciones ( $objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia ) {
    $parametrosAEnviar[]                                        = array();
    $parametrosAEnviar['fechaAnterior']                         = $objUtil->fechaAnteriorSegunTurno($parametros['frm_tipoHorarioTurno']);
    $numeroHospitalizaciones                                    = $objTurno->obtenerNumeroHospitalizaciones($objCon, $parametrosAEnviar);
    $parametrosAEnviar['idTurnoCRUrgencia']                     = $idTurnoCRUrgencia;
    $parametrosAEnviar['numeroHospitalizacionesAdulto']         = $numeroHospitalizaciones['cantidadAdultoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesPediatrico']     = $numeroHospitalizaciones['cantidadPediatricoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesGinecologico']   = $numeroHospitalizaciones['cantidadGinecologicoTotal'];
    $objTurno->insertarNumeroHospitalizaciones($objCon, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function insertarNumeroHospitalizacionesUrgencia ( $objCon, $objTurno, $idTurnoCRUrgencia ) {
    $parametrosAEnviar[]                                        = array();
    $numeroHospitalizaciones                                    = $objTurno->obtenerNumeroHospitalizacionesUrgencia($objCon);
    $numeroHospitalizaciones12                                  = $objTurno->obtenerNumeroHospitalizacionesUrgencia12($objCon);
    $numeroHospitalizaciones24                                  = $objTurno->obtenerNumeroHospitalizacionesUrgencia24($objCon);
    $parametrosAEnviar['idTurnoCRUrgencia']                     = $idTurnoCRUrgencia;
    $parametrosAEnviar['numeroHospitalizacionesAdulto']         = $numeroHospitalizaciones['cantidadAdultoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesPediatrico']     = $numeroHospitalizaciones['cantidadPediatricoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesAdulto12']       = $numeroHospitalizaciones12['cantidadAdultoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesPediatrico12']   = $numeroHospitalizaciones12['cantidadPediatricoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesAdulto24']       = $numeroHospitalizaciones24['cantidadAdultoTotal'];
    $parametrosAEnviar['numeroHospitalizacionesPediatrico24']   = $numeroHospitalizaciones24['cantidadPediatricoTotal'];
    $objTurno->insertarNumeroHospitalizacionesUrgencia($objCon, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function insertarPacientesEspera ( $objCon, $objTurno, $idTurnoCRUrgencia ) {
    $categorizaciones                                           = array('', 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');
    $idTurnoEsperaAtencion = $objTurno->insertarPacientesEspera($objCon, $idTurnoCRUrgencia);
    for ( $i = 0; $i < count($categorizaciones); $i++ ) {
        insertarPacientesEsperaDetalle($objCon, $objTurno, $idTurnoEsperaAtencion, $categorizaciones[$i], 'adulto');
    }
    for ( $i = 0; $i < count($categorizaciones); $i++ ) {
        insertarPacientesEsperaDetalle($objCon, $objTurno, $idTurnoEsperaAtencion, $categorizaciones[$i], 'pediatrico');
    }
}
function insertarPacientesEsperaDetalle ( $objCon, $objTurno, $idTurnoEsperaAtencion, $tipoCategorizacion, $tipoPaciente ) {
    $parametrosAEnviar[]                                        = array();
    $pacientesEsperaAtencion                                    = $objTurno->obtenerPacientesEnEsperaAtencion($objCon, $tipoCategorizacion, $tipoPaciente);
    $parametrosAEnviar[0]['idTurnoEsperaAtencion']              = $idTurnoEsperaAtencion;
    $parametrosAEnviar[0]['totalPacientes']                     = count($pacientesEsperaAtencion);
    $parametrosAEnviar[0]['tipoCategorizacion']                 = $tipoCategorizacion;
    $parametrosAEnviar[0]['tipoPaciente']                       = $tipoPaciente;
    $parametrosAEnviar[1]['pacientesEsperaAtencion']            = $pacientesEsperaAtencion;
    $objTurno->actualizarTurnoEsperaAtencion($objCon, $parametrosAEnviar[0]);
    insertarTurnoEsperaAtencionDetalle($objCon, $objTurno, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function insertarTurnoEsperaAtencionDetalle ( $objCon, $objTurno, $parametros ) {
    for ( $i = 0; $i < $parametros[0]['totalPacientes']; $i++ ) {
        $parametrosAEnviar[]                                    = array();
        $parametrosAEnviar['idTurnoEsperaAtencion']             = $parametros[0]['idTurnoEsperaAtencion'];
        $parametrosAEnviar['tipoPaciente']                      = $parametros[0]['tipoPaciente'];
        $parametrosAEnviar['tipoCategorizacion']                = tipoCategorizacion($parametros[0]['tipoCategorizacion']);
        $parametrosAEnviar['numeroDau']                         = $parametros[1]['pacientesEsperaAtencion'][$i]['dau_id'];
        $parametrosAEnviar['nombrePaciente']                    = $parametros[1]['pacientesEsperaAtencion'][$i]['nombrePaciente'];
        $parametrosAEnviar['fechaNacimientoPaciente']           = $parametros[1]['pacientesEsperaAtencion'][$i]['fechanac'];
        $parametrosAEnviar['tiempoEsperaPaciente']              = tiempoEsperaPaciente($parametros[1]['pacientesEsperaAtencion'][$i]['tiempoEsperaSinCategorizacion'], $parametros[1]['pacientesEsperaAtencion'][$i]['tiempoEsperaConCategorizacion']);
        $objTurno->insertarTurnoEsperaAtencionDetalle($objCon, $parametrosAEnviar);
        unset($parametrosAEnviar);
    }
}
function insertarCirugiasRealizadas ( $objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia ) {
    $parametrosAEnviar[]                                        = array();
    $parametrosAEnviar['fechaAnterior']                         = $objUtil->fechaAnteriorSegunTurno($parametros['frm_tipoHorarioTurno']);
    $parametrosAEnviar['idProfesional']                         = $parametros['frm_idProfesionalEntregaTurno'];
    $cirugiasRealizadas                                         = $objTurno->obtenerCirugiasRealizadas($objCon, $parametrosAEnviar);
    $totalCirugiasRealizadas                                    = count($cirugiasRealizadas);
    for ( $i = 0, $desplazamiento = 0; $i < $totalCirugiasRealizadas; $i = $i + $desplazamiento ) {
        $desplazamiento                                         = 0;
        $parametrosAEnviar[]                                    = array();
        $parametrosAEnviar['idTurnoCRUrgencia']                 = $idTurnoCRUrgencia;
        $parametrosAEnviar['codigoCirugia']                     = $cirugiasRealizadas[$i]['idSolicitud'];
        $parametrosAEnviar['nombrePaciente']                    = $cirugiasRealizadas[$i]['nombrePaciente'];
        $parametrosAEnviar['runPaciente']                       = $cirugiasRealizadas[$i]['runPaciente'];
        $parametrosAEnviar['numeroCirujano']                    = 'Cirujano 1';
        if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Urgencia' ) {
            $parametrosAEnviar['tipoCirugia']                   = 'U';
        }
        if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Normal' ) {
            $parametrosAEnviar['tipoCirugia']                   = 'N';
        }
        if ( $cirugiasRealizadas[$i]['descripcionTipoSolicitud'] == 'Procedimiento' ) {
            $parametrosAEnviar['tipoCirugia']                   = 'P';
        }
        $idTurnoCirugiasRealizadas                              = $objTurno->insertarTurnoCirugiasRealizadas($objCon, $parametrosAEnviar);
        $idTablaQuirurgica                                      = $cirugiasRealizadas[$i]['idTablaQuirurgica'] ;
        for ($j = $i; $idTablaQuirurgica == $cirugiasRealizadas[$j]['idTablaQuirurgica']; $j++ ) {
            insertarTurnoCirugiasRealizadasDetalle($objCon, $objTurno, $idTurnoCirugiasRealizadas, $cirugiasRealizadas[$i]);
            $idTablaQuirurgica                                  = $cirugiasRealizadas[$j]['idTablaQuirurgica'];
            $desplazamiento++;
        }
        unset($parametrosAEnviar);
    }
}
function insertarSolicitudesEspecialistas ($objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia ) {
    $parametrosAEnviar[]                                        = array();
    $parametrosAEnviar['fechaAnterior']                         = $objUtil->fechaAnteriorSegunTurno($parametros['frm_tipoHorarioTurno']);
    $solicitudesEspecialistas                                   = $objTurno->obtenerSolicitudesEspecialistas($objCon, $parametrosAEnviar);
    $totalSolicitudesEspecialistas                              = count($solicitudesEspecialistas);
    for ( $i = 0; $i < $totalSolicitudesEspecialistas; $i++ ) {
        $parametrosAEnviar['idTurnoCRUrgencia']                 = $idTurnoCRUrgencia;
        $parametrosAEnviar['idDau']                             = $solicitudesEspecialistas[$i]['idDau'];
        $parametrosAEnviar['nombrePaciente']                    = $solicitudesEspecialistas[$i]['nombrePaciente'];
        $parametrosAEnviar['fechaSolicitudEspecialista']        = $solicitudesEspecialistas[$i]['fechaSolicitudEspecialista'];
        $parametrosAEnviar['gestionRealizada']                  = ( $objUtil->existe($solicitudesEspecialistas[$i]['gestionRealizada']) ) ? "Si" : "No";
        $parametrosAEnviar['descripcionProfesionalEspecialista']= $solicitudesEspecialistas[$i]['descripcionProfesionalEspecialista'];
        $parametrosAEnviar['descripcionEstadoSolicitud']        = $solicitudesEspecialistas[$i]['descripcionEstadoSolicitud'];
        $objTurno->insertarTurnoSolicitudesEspecialista($objCon, $parametrosAEnviar);
    }
    unset($parametrosAEnviar);
}
function insertarTurnoCirugiasRealizadasDetalle ( $objCon, $objTurno, $idTurnoCirugiasRealizadas, $parametros ) {
    $parametrosAEnviar[]                                        = array();
    $parametrosAEnviar['idTurnoCirugiasRealizadas']             = $idTurnoCirugiasRealizadas;
    $parametrosAEnviar['glosaCirugia']                          = $parametros['nombreIntervencion'];
    $parametrosAEnviar['codigoPrestacion']                      = $parametros['idIntervencion'];
    $objTurno->insertarTurnoCirugiasRealizadasDetalle($objCon, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function insertarTiemposAtencion ( $objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia ) {
    $parametrosAEnviar[]                                        = array();
    $infoProfesional                                            = $objTurno->obtenerInfoProfesionalPorRun($objCon, $parametros['frm_idProfesionalEntregaTurno']);
    $parametrosAEnviar['idProfesional']                         = $infoProfesional['idusuario'];
    $parametrosAEnviar['fechaAnterior']                         = $objUtil->fechaAnteriorSegunTurno($parametros['frm_tipoHorarioTurno']);
    $tiemposAtencion                                            = $objTurno->obtenerTiemposAtencion($objCon, $parametrosAEnviar);
    $parametrosAEnviar['idTurnoCRUrgencia']                     = $idTurnoCRUrgencia;
    $parametrosAEnviar['cantidadPacientesAtendidos']            = $tiemposAtencion['totalFilas'];
    $parametrosAEnviar['tiempoPromedioAtencion']                = $objUtil->promedioTiempos($tiemposAtencion);
    $parametrosAEnviar['tiempoMinimoAtencion']                  = $tiemposAtencion['tiempoMinimoAtencion'];
    $parametrosAEnviar['tiempoMaximoAtencion']                  = $tiemposAtencion['tiempoMaximoAtencion'];
    if ( empty($tiemposAtencion['totalFilas']) || is_null($tiemposAtencion['totalFilas']) ) {
        unset($parametrosAEnviar);
        return;
    }
    $objTurno->insertarTiemposAtencion($objCon, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function insertarTiemposPromedioCategorizacion ( $objCon, $objUtil, $objTurno, $parametros, $idTurnoCRUrgencia ) {
    $categorizaciones                                           = array('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5');
    for ( $i = 0; $i < count($categorizaciones); $i++ ) {
        $parametrosAEnviar[]                                    = array();
        $parametrosAEnviar['idTurnoCRUrgencia']                 = $idTurnoCRUrgencia;
        $parametrosAEnviar['tipoCategorizacion']                = $categorizaciones[$i];
        $parametrosAEnviar['fechaAnterior']                     = $objUtil->fechaAnteriorSegunTurno($parametros['frm_tipoHorarioTurno']);
        $parametrosAEnviar['idTurnoTiemposPromedioCategorizacion']  = $objTurno->insertarTurnoTiemposPromedioCategorizacion($objCon, $parametrosAEnviar);
        insertarTurnoTiemposPromedioCategorizacionDetalle($objCon, $objUtil, $objTurno, $parametrosAEnviar);
        unset($parametrosAEnviar);
    }
}
function insertarTurnoTiemposPromedioCategorizacionDetalle ( $objCon, $objUtil, $objTurno, $parametros ) {
    $parametrosAEnviar[] = array();
    $parametrosAEnviar['idTurnoTiemposPromedioCategorizacion'] = $parametros['idTurnoTiemposPromedioCategorizacion'];
    $parametrosAEnviar['tipoCategorizacion'] = $parametros['tipoCategorizacion'];
    $tiempoPromedioCategorizacionInicioAtencion = $objTurno->obtenerTiemposPromedioCategorizacionInicioAtencion($objCon, $parametros);
    $parametrosAEnviar['totalPacientes_CategorizacionInicioAtencion'] = $tiempoPromedioCategorizacionInicioAtencion['totalFilas'];
    $parametrosAEnviar['tiempoPromedio_CategorizacionInicioAtencion'] = $objUtil->promedioTiempos($tiempoPromedioCategorizacionInicioAtencion);
    $tiempoPromedioInicioAtencionCierreAtencion = $objTurno->obtenerTiemposPromedioInicioAtencionCierreAtencion($objCon, $parametros);
    $parametrosAEnviar['totalPacientes_InicioAtencionCierreAtencion'] = $tiempoPromedioInicioAtencionCierreAtencion['totalFilas'];
    $parametrosAEnviar['tiempoPromedio_InicioAtencionCierreAtencion'] = $objUtil->promedioTiempos($tiempoPromedioInicioAtencionCierreAtencion);
    $tiempoCierreAtencionAplicacionCierre = $objTurno->obtenerTiemposPromedioCierreAtencionAplicacionCierre($objCon, $parametros);
    $parametrosAEnviar['totalPacientes_CierreAtencionAplicacionCierre'] = $tiempoCierreAtencionAplicacionCierre['totalFilas'];
    $parametrosAEnviar['tiempoPromedio_CierreAtencionAplicacionCierre'] = $objUtil->promedioTiempos($tiempoCierreAtencionAplicacionCierre);
    $objTurno->actualizarTurnoTiemposPromedioCategorizacion($objCon, $parametrosAEnviar);
    unset($parametrosAEnviar);
}
function tipoCategorizacion ( $tipoCategorizacion ) {
    return ( $tipoCategorizacion === '' ) ? 'SC' : $tipoCategorizacion;
}
function tiempoEsperaPaciente ( $tiempoSinCategorizacion, $tiempoConCategorizacion ) {
    return ( empty($tiempoSinCategorizacion) && is_null($tiempoSinCategorizacion) ) ? $tiempoConCategorizacion : $tiempoSinCategorizacion;
}
?>