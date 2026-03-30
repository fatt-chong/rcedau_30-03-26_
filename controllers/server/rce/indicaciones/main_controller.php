<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
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
require_once('../../../../class/Bitacora.class.php');               $objBitacora          = new Bitacora;
require_once('../../../../class/Especialista.class.php');           $objEspecialista      = new Especialista;
require_once('../../../../class/Admision.class.php');               $objAdmision          = new Admision;
require_once('../../../../class/Usuarios.class.php');               $objUsuarios          = new Usuarios;



if (isset($_POST["accion"])) {
  $accion = $_POST["accion"];
}

if (isset($_GET["accion"])) {
  $accion = $_GET["accion"];
}

switch ($accion) {
  case 'tomarMuestrasIndicacionesMultiplesFilas':
    try {
      $objCon->beginTransaction();
      $parametros = $objUtil->getFormulario($_POST);
      $parametros['arregloTabla']  = json_decode(stripslashes($parametros['arregloTablaRow']));
      for ($i = 0; $i < count($parametros['arregloTabla']); $i++) {
        $subparametros['rowMaster']          = $parametros['arregloTabla'][$i];
        $subparametros['rowMaster']          = explode('-', $subparametros['rowMaster']);
        $parametros['solicitud_id']        = $subparametros['rowMaster'][0];
        $resLab                                     = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
        $parametrosExamen['regId']                  = $resLab[0]['regId'];
        $parametrosExamen['tubo_id']                = $resLab[0]['tubo_id'];
        $parametrosExamen['sol_lab_fechaInserta']   = $resLab[0]['sol_lab_fechaInserta'];
        $listarIndicacionesporTubo                  = $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
        for ($q=0; $q<count($listarIndicacionesporTubo); $q++){

          if($listarIndicacionesporTubo[$q]['sol_lab_estado']  == 1) {
            $subparametros['solicitud_id']            = $listarIndicacionesporTubo[$q]['sol_lab_id'];
            $parametros['solicitud_id']               = $listarIndicacionesporTubo[$q]['sol_lab_id'];
            $parametros['usuario_muestraTomadas']     = $_SESSION['MM_Username'.SessionName];
            $parametros['tipo_id']                    = $subparametros['rowMaster'][1];
            $parametros['lab_est_id']                 =   1;
            $parametros['tipo_id_lab']                =   2;
            $parametros['usuario_Aplica']             = $parametros['usuario_muestraTomadas'];
            $parametros['estado_indicacion_rce']      = 1;
            $parametros['tipo']                       = $parametros['tipo_id'];
            $parametros['dau_mov_descripcion']        = 'toma_muestra_laboratorio';
            $parametros['indicacion_id']              = $parametros['solicitud_id'];
            $parametros['dau_mov_usuario']            = $parametros['usuario_muestraTomadas'];
            $parametros['observacion_rce']            = null;
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            $objRce->registrarTomaDeMuestra($objCon, $parametros);
            $resultadoConsulta = $objLaboratorio->buscarExamenLaboratorioCanceladoPreviamente($objCon, $parametros['solicitud_id']);
            if ($resultadoConsulta[0]['est_id'] != 6) {
              $insertarSolicitudLaboratorio = $objLaboratorio->insertarSolicitudLab($objCon, $parametros);
            } else {
              $objLaboratorio->cambiarEstadoSolicitudLab($objCon, $parametros['solicitud_id']);
            }
          }
        }
      }
      $objCon->commit();
      $response = array("status" => "success", "id" => $parametros['tomaMuestra_id']);
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'iniciarIndicacionesMultiplesFilas':
    try {
      $objCon->beginTransaction();
      $parametros                           = $objUtil->getFormulario($_POST);
      $parametros['arregloTabla']           = json_decode(stripslashes($parametros['arregloTablaRow']));
      for ($i = 0; $i < count($parametros['arregloTabla']); $i++) {
        $subparametros['rowMaster']         = $parametros['arregloTabla'][$i];
        $subparametros['rowMaster']         = explode('-', $subparametros['rowMaster']);
        if (isset($_SESSION['usuarioActivo']['usuario'])) {
          $parametros['usuario_IniciaAtencion']     = $_SESSION['usuarioActivo']['usuario'];
        } else {
          $parametros['usuario_IniciaAtencion']     = $_SESSION['MM_Username'.SessionName];
        }
        $parametros['solicitud_id']           = $subparametros['rowMaster'][0];
        $parametros['tipo_id']                = $subparametros['rowMaster'][1];
        $parametros['estado_indicacion_rce']  = 1;
        if ($parametros['tipo_id'] == 2) {
          $parametros['tipo']                 = 2;
          $parametros['dau_mov_descripcion']  = 'inicio_indicacion_tratamiento';
        } else if ($parametros['tipo_id'] == 4) {
          $parametros['tipo']                 = 4;
          $parametros['dau_mov_descripcion']  = 'inicio_indicacion_otro';
        } else if ($parametros['tipo_id'] == 6) {
          $parametros['tipo']                 = 6;
          $parametros['dau_mov_descripcion']  = 'inicio_indicacion_procedimiento';
        } else if ($parametros['tipo_id'] == 8) {
          $parametros['tipo']                 = 8;
          $parametros['dau_mov_descripcion']  = 'inicio_solicitud_transfusion';
        }
        $parametros['indicacion_id']          = $parametros['solicitud_id'];
        $parametros['dau_mov_usuario']        = $parametros['usuario_IniciaAtencion'];
        $parametros['observacion_rce']        = null;
        $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
        $objRce->registrarFechaInicioIndicacionENF($objCon, $parametros);
      }
      $objCon->commit();
      $response = array("status" => "success", "id" => $parametros['indicacion_id']);
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'anularMultiplesFilas':
    $tipoImagenologia                           = 1;
    $anularIndicacion                           = 6;
    $parametros                                 = $objUtil->getFormulario($_POST);
    $parametros['arregloTabla']                 = json_decode(stripslashes($parametros['arregloTablaRow']));
    $parametros['estado_indicacion_rce']        = $anularIndicacion;
    $subparametros['usuario_Aplica']            = $_SESSION['MM_Username'.SessionName];
    $horarioServidor                            = $objUtil->getHorarioServidor($objCon);
    try {
      $objCon->beginTransaction();
      for ($i = 0; $i < count($parametros['arregloTabla']); $i++) {
        $subparametros['estado_indicacion']     = $anularIndicacion;
        $subparametros['observacion_aplica']    = $parametros['frm_observacion_aplica'];
        $subparametros['rowMaster']             = $parametros['arregloTabla'][$i];
        $subparametros['rowMaster']             = explode('-', $subparametros['rowMaster']);
        $subparametros['solicitud_id']          = $subparametros['rowMaster'][0];
        $subparametros['tipo_id']               = $subparametros['rowMaster'][1];
        $subparametros['id_sic']                = $subparametros['rowMaster'][2];
        $subparametros['usuario_Aplica']        = $_SESSION['MM_Username'.SessionName];
        if ((int)$subparametros['tipo_id'] == $tipoImagenologia) {
          $objImagenologia->editarCabeceraImagenologia($objCon, $subparametros);
          $objImagenologia->editarDetalleCabecera($objCon, $subparametros);
          //Cambiar estado detalle integración dalca
          $parametrosDalca["solicitud_id"]      = $subparametros['solicitud_id'];
          $parametrosDalca['estado_indicacion'] = $anularIndicacion;
          $objImagenologia->editarEstadoDetalleSolicitudImagenologiaDalca($objCon, $parametrosDalca);
          $getIndicaciones                      = $objImagenologia->listarIndicacionesImagenologia($objCon, $subparametros);
          for ($z = 0; $z < count($getIndicaciones); $z++) {
            //Guardar movimiento
            $preparametros['id_sic']            = $getIndicaciones[$z]['SIC_id'];
            $preparametros['SIC_aplicado']      = 2;
            $objImagenologia->editarEstadoRayo($objCon, $preparametros);
            $parametros['indicacion_id']        = $getIndicaciones[$z]['det_ima_id'];
            $parametros['solicitud_id']         = $subparametros['solicitud_id'];
            $parametros['tipo']                 = $tipoImagenologia;
            $parametros['dau_mov_descripcion']  = 'anular_indicacion_imagenologia';
            $parametros['dau_mov_usuario']      = $subparametros['usuario_Aplica'];
            $parametros['SIC_id_rayos']         = $preparametros['id_sic'];
            $parametros['id_solicitud_dalca']   = $getIndicaciones[$z]['idSolicitudDalca'];
            $parametros['observacion_rce']      = $subparametros['observacion_aplica'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            //Anular indicación integración dalca
            if ($objUtil->existe($getIndicaciones[$z]["idSolicitudDalca"])) {
              $parametrosIntegracionDalca = array(
                "id_solicitud" => (int)$indicacionesImagenologia[$indice]["idSolicitudDalca"],
                "observacion" => "Anulación de examen de forma masiva por el usuario respectivo.",
                "usuario" => $_SESSION['MM_Username'.SessionName]
              );

              $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => IpDalca.'/apiHJNCDalca/cancelarSolicitud',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $parametrosIntegracionDalca,
                CURLOPT_HTTPHEADER => array(
                  'accept: application/json',
                  'Content-Type: multipart/form-data'
                ),
              ));
              $response = curl_exec($curl);
              curl_close($curl);

              if (json_decode($response)->status !== 200) {
                $objCon->rollback();
                $response = array("status" => "error", "message" => "Se ha producido un error al intentar anular la indicación en integración DALCA, favor intente denuevo.<br /><br />"."Respuesta de DALCA: ".$response);
                echo json_encode($response);
                exit(1);
              }
            }
          }
        } else if ($subparametros['tipo_id'] == 2 || $subparametros['tipo_id'] == 4 || $subparametros['tipo_id'] == 6 || $subparametros['tipo_id'] == 8) {
          $aplicarIndicacion                    = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $subparametros);
          if ($subparametros['tipo_id'] == 2) {
            $parametros['dau_mov_descripcion']  = 'terminar_indicacion_tratamiento';
            $parametros['tipo']                 = 2;
          } else if ($subparametros['tipo_id'] == 6) {
            $parametros['dau_mov_descripcion']  = 'terminar_indicacion_procedimiento';
            $parametros['tipo']                 = 6;
          } else if ($subparametros['tipo_id'] == 4) {
            $parametros['dau_mov_descripcion']  = 'terminar_indicacion_otro';
            $parametros['tipo']                 = 4;
          }else if ($subparametros['tipo_id'] == 8) {
            $parametros['dau_mov_descripcion']  = 'terminar_solicitud_transfusion';
            $parametros['tipo']                 = 8;
            $resIndTrans                                        = $objRegistroClinico->listarIndicaciones($objCon, $subparametros);
            $parametrosTransfusion['id_solicitud_transfusion']  = $resIndTrans[0]['id_solicitud_transfusion'];
            $parametrosTransfusion['toma_muestra_usuario']      = $_SESSION['MM_Username'.SessionName];
            $parametrosTransfusion['toma_muestra_fecha']        = $horarioServidor[0]['fecha'];
            $parametrosTransfusion['toma_muestra_hora']         = $horarioServidor[0]['hora'];
            $parametrosTransfusion['toma_muestra_observacion']  = "Anulado desde botón anulación multiples Indicacion Enfermeria."; 
            $parametrosTransfusion['estado']                    = 8;

            $objDau->UpdateSolicitud_transfusion($objCon, $parametrosTransfusion);

            $parametrosTransfusion['descripcion_log']  = "Anulada la toma de muestra desde URGENCIAS";
            $objDau->InsertSolicitudes_transfusion_movimiento($objCon, $parametrosTransfusion);

          }
          $parametros['solicitud_id']           = $subparametros['solicitud_id'];
          $parametros['indicacion_id']          = "null";
          $parametros['dau_mov_usuario']        = $subparametros['usuario_Aplica'];
          $parametros['observacion_rce']        = $subparametros['observacion_aplica'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
        } else if ($subparametros['tipo_id'] == 3) {
          $resLab                                     = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $subparametros);
          $parametrosExamen['regId']                  = $resLab[0]['regId'];
          $parametrosExamen['tubo_id']                = $resLab[0]['tubo_id'];
          $parametrosExamen['sol_lab_fechaInserta']   = $resLab[0]['sol_lab_fechaInserta'];
          $listarIndicacionesporTubo                  = $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
          for ($q=0; $q<count($listarIndicacionesporTubo); $q++){
            $subparametros['solicitud_id']              = $listarIndicacionesporTubo[$q]['sol_lab_id'];
            $subparametros['lab_est_id']                = 1;
            $subparametros['tipo_id_lab']               = 2;
            $subparametros['estado_indicacion_labnet']  = 5;
            $subparametros['usuarioAnula']              = $subparametros['usuario_Aplica'];
            $subparametros['observacion_anula']         = $subparametros['observacion_aplica'];
            $aplicarIndicacion                          = $objLaboratorio->editarSolicitudLaboratorio($objCon, $subparametros);
            $aplicarIndicacionDetalle                   = $objLaboratorio->editarDetalleLaboratorio($objCon, $subparametros);
            $aplicarIndicacion                          = $objLaboratorio->editarSolicitudLabnet($objCon, $subparametros);
            $parametros['dau_mov_descripcion']          = 'aplicar_indicacion_laboratorio';
            $parametros['tipo']                         = 3;
            $parametros['solicitud_id']                 = $subparametros['solicitud_id'];
            $parametros['indicacion_id']                = "null";
            $parametros['dau_mov_usuario']              = $subparametros['usuario_Aplica'];
            $parametros['observacion_rce']              = $subparametros['observacion_aplica'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          }
        }
        $parametros['frm_numero_dau'] = $parametros['dau_id'];
        // $datosIndicacion              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
        $parametros['regId']          = $parametros['rce_id'];
        $datosSol                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
        if ($datosSol[0]['aplicada'] == 0 && $datosSol[0]['total'] == 0) {
          $parametros['dau_indicacion_terminada']     = 0;
          $objDau->dau_indicacion($objCon, $parametros);
          $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
          $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
          $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
        } else {
          $parametros['dau_indicacion_terminada']     = ($datosSol[0]['aplicada'] == $datosSol[0]['total']) ? 1 : 0;
          $objDau->dau_indicacion($objCon, $parametros);
          $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
          $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
          $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
        }
      }
      $objCon->commit();
      $response = array("status" => "success", "id" => $parametros['indicacion_id']);
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'aplicarMultiplesFilas':
    $parametros                           = $objUtil->getFormulario($_POST);
    $parametros['arregloTabla']           = json_decode(stripslashes($parametros['arregloTablaRow']));
    $parametros['estado_indicacion_rce']  = 4;
    $banderaError                         = false;
    $subparametros['usuario_Aplica']      = $_SESSION['MM_Username'.SessionName];
    $horarioServidor                      = $objUtil->getHorarioServidor($objCon);
    try {
      $objCon->beginTransaction();
      for ($i = 0; $i < count($parametros['arregloTabla']); $i++) {
        $subparametros['estado_indicacion']   = 4;
        $subparametros['observacion_aplica']  = $parametros['frm_observacion_aplica'];
        $subparametros['rowMaster']           = $parametros['arregloTabla'][$i];
        $subparametros['rowMaster']           = explode('-', $subparametros['rowMaster']);
        $subparametros['solicitud_id']        = $subparametros['rowMaster'][0];
        $subparametros['tipo_id']             = $subparametros['rowMaster'][1];
        $subparametros['id_sic']              = $subparametros['rowMaster'][2];
        if ($subparametros['tipo_id'] == 1) {//
          $aplicarIndicacion  = $objImagenologia->editarCabeceraImagenologia($objCon, $subparametros);
          $editarDetalle      = $objImagenologia->editarDetalleCabecera($objCon, $subparametros);
          $objImagenologia->editarEstadoDetalleSolicitudImagenologiaDalca($objCon, $subparametros);
          $getIndicaciones    = $objImagenologia->listarIndicacionesImagenologia($objCon, $subparametros);
          for ($z = 0; $z < count($getIndicaciones); $z++) {
            $preparametros['id_sic']            = $getIndicaciones[$z]['SIC_id'];
            $preparametros['SIC_aplicado']      = 2;
            $objImagenologia->editarEstadoRayo($objCon, $preparametros);
            $parametros['indicacion_id']        = $getIndicaciones[$z]['det_ima_id'];
            $parametros['solicitud_id']         = $subparametros['solicitud_id'];
            $parametros['tipo']                 = 1;
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_imagenologia';
            $parametros['dau_mov_usuario']      = $subparametros['usuario_Aplica'];
            $parametros['SIC_id_rayos']         = $preparametros['id_sic'];
            $parametros["id_solicitud_dalca"]   = $getIndicaciones[$z]["idSolicitudDalca"];
            $parametros['observacion_rce']      = $subparametros['observacion_aplica'];
            $parametros['movimiento_enfermeria']    =  'S';
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          }
        } else if ($subparametros['tipo_id'] == 2 || $subparametros['tipo_id'] == 4 || $subparametros['tipo_id'] == 6 || $subparametros['tipo_id'] == 8) {
          $aplicarIndicacion                    = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $subparametros);
          if ($subparametros['tipo_id'] == 2) {
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_tratamiento';
            $parametros['tipo']                 = 2;
          } else if ($subparametros['tipo_id'] == 6) {
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_procedimiento';
            $parametros['tipo']                 = 6;
          } else if ($subparametros['tipo_id'] == 4) {
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_otro';
            $parametros['tipo']                 = 4;
          }else if ($subparametros['tipo_id'] == 8) {
            $parametros['dau_mov_descripcion']  = 'aplicar_solicitud_transfusion';
            $parametros['tipo']                 = 8;
            $resIndTrans                                        = $objRegistroClinico->listarIndicaciones($objCon, $subparametros);
            $parametrosTransfusion['id_solicitud_transfusion']  = $resIndTrans[0]['id_solicitud_transfusion'];
            $parametrosTransfusion['toma_muestra_usuario']      = $_SESSION['MM_Username'.SessionName];
            $parametrosTransfusion['toma_muestra_fecha']        = $horarioServidor[0]['fecha'];
            $parametrosTransfusion['toma_muestra_hora']         = $horarioServidor[0]['hora'];
            $parametrosTransfusion['toma_muestra_observacion']  = "Sin observación";
            $parametrosTransfusion['estado']                    = 2;

            $objDau->UpdateSolicitud_transfusion($objCon, $parametrosTransfusion);

            $parametrosTransfusion['descripcion_log']  = "Asignación de responsable de toma de muestra";
            $objDau->InsertSolicitudes_transfusion_movimiento($objCon, $parametrosTransfusion);

          }
          $parametros['solicitud_id']           = $subparametros['solicitud_id'];
          $parametros['indicacion_id']          = "null";
          $parametros['dau_mov_usuario']        = $subparametros['usuario_Aplica'];
          $parametros['observacion_rce']        = $subparametros['observacion_aplica'];
          $parametros['movimiento_enfermeria']    =  'S';
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
        } else if ($subparametros['tipo_id'] == 3) {
          $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => 'No se puede Aplicar Indicaciones de Tipo Laboratorio en Urgencias (sólo se aplica desde Laboratorio)');
          $objCon->rollback();
          echo json_encode($response);
          return;
        }
        $parametros['frm_numero_dau'] = $parametros['dau_id'];
        // $datosIndicacion              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
        $parametros['regId']          = $parametros['rce_id'];
        $datosSol                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
        if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
          $parametros['dau_indicacion_terminada'] = 1;
          $objDau->dau_indicacion($objCon, $parametros);
        } else {
          $parametros['dau_indicacion_terminada'] = 0;
          $objDau->dau_indicacion($objCon, $parametros);
        }
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      }
      $objCon->commit();
      if ($banderaError) {
        $message    = 'La solicitudes aún no han sido recepcionadas :<br>' . $solicitudesNoAplicadas;
        $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
      } else {
        $response   = array("status" => "success", "id" => $parametros['indicacion_id']);
      }
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'iniciarIndicacion':
    $parametros                             = $objUtil->getFormulario($_POST);
    $parametros['usuario_IniciaAtencion']   = $_SESSION['MM_Username'.SessionName];
    $parametros['arreglo_id_tipo']          = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']             = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                  = $parametros['arreglo_id_tipo'][1];
    $parametros['estado_indicacion_rce']    = 1;
    if ($parametros['tipo_id'] == 2) {
      $parametros['tipo']                   = 2;
      $parametros['dau_mov_descripcion']    = 'inicio_indicacion_tratamiento';
    } else if ($parametros['tipo_id'] == 4) {
      $parametros['tipo']                   = 4;
      $parametros['dau_mov_descripcion']    = 'inicio_indicacion_otro';
    } else if ($parametros['tipo_id'] == 6) {
      $parametros['tipo']                   = 6;
      $parametros['dau_mov_descripcion']    = 'inicio_indicacion_procediemito';
    } else if ($parametros['tipo_id'] == 8) {
      $parametros['tipo']                 = 8;
      $parametros['dau_mov_descripcion']  = 'inicio_solicitud_transfusion';
    }
    $parametros['indicacion_id']            = $parametros['solicitud_id'];
    $parametros['dau_mov_usuario']          = $parametros['usuario_IniciaAtencion'];
    $parametros['observacion_rce']          =  $parametros['frm_observacion_aplica'];
    $parametros['movimiento_enfermeria']    =  'S';
    try {
      $objCon->beginTransaction();
      $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
      $objRce->registrarFechaInicioIndicacionENF($objCon, $parametros);
      $response = array("status" => "success", "id" => $parametros['indicacion_id']);
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'tomaMuestra':
    $parametros                             = $objUtil->getFormulario($_POST);
    $parametros['usuario_muestraTomadas']   = $_SESSION['MM_Username'.SessionName];
    $parametros['arreglo_id_tipo']          = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']             = $parametros['arreglo_id_tipo'][0]; // ID DE LA SOLICITUD
    $parametros['tipo_id']                  = $parametros['arreglo_id_tipo'][1];
    $parametros['lab_est_id']               = 1;
    $parametros['tipo_id_lab']              = 2;
    $parametros['usuario_Aplica']           = $parametros['usuario_muestraTomadas'];
    $parametros['estado_indicacion_rce']    = 1;
    $parametros['tipo']                     = $parametros['tipo_id'];
    $parametros['dau_mov_descripcion']      = 'toma_muestra_laboratorio';
    $parametros['indicacion_id']            = $parametros['solicitud_id'];
    $parametros['dau_mov_usuario']          = $parametros['usuario_muestraTomadas'];
    $parametros['observacion_aplica']       = $parametros['frm_observacion_aplica'];
    $parametros['observacion_rce']          =  $parametros['frm_observacion_aplica'];

    $parametros['movimiento_enfermeria']    =  'S';
    try {
      $objCon->beginTransaction();
      $resLab                                     = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
      $parametrosExamen['regId']                  = $resLab[0]['regId'];
      $parametrosExamen['tubo_id']                = $resLab[0]['tubo_id'];
      $parametrosExamen['sol_lab_fechaInserta']   = $resLab[0]['sol_lab_fechaInserta'];
      $listarIndicacionesporTubo                  = $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);

      for ($i=0; $i<count($listarIndicacionesporTubo); $i++){
        if($listarIndicacionesporTubo[$i]['sol_lab_estado']  == 1) {
          $parametros['solicitud_id'] = $listarIndicacionesporTubo[$i]['sol_lab_id'];
          $parametros['solicitud_examen'] = $solicitud_examen;

          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          $objRce->registrarTomaDeMuestra($objCon, $parametros);

          $resultadoConsulta = $objLaboratorio->buscarExamenLaboratorioCanceladoPreviamente($objCon, $parametros['solicitud_id']);
          if ($resultadoConsulta[0]['est_id'] != 6) {
            $insertarSolicitudLaboratorio = $objLaboratorio->insertarSolicitudLab($objCon, $parametros);
          } else {
            $objLaboratorio->cambiarEstadoSolicitudLab($objCon, $parametros['solicitud_id']);
          }
        }
      }
      $response = array("status" => "success", "id" => $parametros['tomaMuestra_id']);
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'aplicarIndicacion':
    $parametros                 = $objUtil->getFormulario($_POST);
    $parametros['dau_id'];
    $parametros['estado_solicitud']       = 1;
    $parametros['estado_indicacion']      = 4;
    $parametros['arreglo_id_tipo']        = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']           = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                = $parametros['arreglo_id_tipo'][1];
    $parametros['id_sic']                 = $parametros['arreglo_id_tipo'][2];
    $parametros['observacion_aplica']     = $parametros['frm_observacion_aplica'];
    $parametros['estado_indicacion_rce']  = 4;
    $parametros['usuario_Aplica']         = $_SESSION['MM_Username'.SessionName];
    $parametros['movimiento_enfermeria']  = 'S';
    $horarioServidor                      = $objUtil->getHorarioServidor($objCon);
    try {
      $objCon->beginTransaction();
      if ($parametros['tipo_id'] == 1) {
        $resIma                 = $objImagenologia->listarIndicacionesImagenologia($objCon, $parametros);
        $parametros['ind_id']   = $resIma[0]['det_ima_id'];
        $ima_estado             = $resIma[0]['estadoCabecera'];

        if ($ima_estado == 1) {
          $objImagenologia->editarCabeceraImagenologia($objCon, $parametros);
          $objImagenologia->editarDetalleCabecera($objCon, $parametros);
          $objImagenologia->editarEstadoDetalleSolicitudImagenologiaDalca($objCon, $parametros);

          $parametros['id_sic']       = $resIma[0]['SIC_id'];
          $parametros['SIC_aplicado'] = 2;
          $det_ima_id                 = $resIma[0]['det_ima_id'];
          $objImagenologia->editarEstadoRayo($objCon, $parametros);

          $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_imagenologia';
          $parametros['tipo']                 = $parametros['tipo_id'];
          $parametros['indicacion_id']        = $det_ima_id;
          $parametros['dau_mov_usuario']      = $parametros['usuario_Aplica'];
          $parametros['SIC_id_rayos']         = $parametros['id_sic'];
          $parametros["id_solicitud_dalca"]   = $resIma[0]["idSolicitudDalca"];
          $parametros['observacion_rce']      = $parametros['observacion_aplica'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);

          $response = array("status" => "success", "id" => $parametros['indicacion_id']);
        } else if ($ima_estado == 3) {
          $message = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> esta en estado No Aplicado, debido a que el dau <b>' . $parametros['dau_id'] . '</b> ha sido cerrado.';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $message = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede volver a aplicar.';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        }
      } else if ($parametros['tipo_id'] == 2 || $parametros['tipo_id'] == 4 || $parametros['tipo_id'] == 6 || $parametros['tipo_id'] == 8) {
        $resInd       = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
        $ind_estado   = $resInd[0]['sol_ind_estado'];
        if ($ind_estado == 1) {
          $aplicarIndicacion = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $parametros);
          if ($parametros['tipo_id'] == 2) {
            $parametros['dau_mov_descripcion'] = 'aplicar_indicacion_tratamiento';
            $parametros['tipo'] = 2;
          } else if ($parametros['tipo_id'] == 6) {
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_procedimiento';
            $parametros['tipo'] = 6;
          } else if ($parametros['tipo_id'] == 4) {
            $parametros['dau_mov_descripcion']  = 'aplicar_indicacion_otro';
            $parametros['tipo']  = 4;
          } else if ($parametros['tipo_id'] == 8) {
            $parametros['dau_mov_descripcion']  = 'aplicar_solicitud_transfusion';
            $parametros['tipo']  = 8;

            $parametrosTransfusion['id_solicitud_transfusion']  = $resInd[0]['id_solicitud_transfusion'];
            $parametrosTransfusion['toma_muestra_usuario']      = $_SESSION['MM_Username'.SessionName];
            $parametrosTransfusion['toma_muestra_fecha']        = $horarioServidor[0]['fecha'];
            $parametrosTransfusion['toma_muestra_hora']         = $horarioServidor[0]['hora'];
            $parametrosTransfusion['toma_muestra_observacion']  = $parametros['frm_observacion_aplica'];
            $parametrosTransfusion['estado']                    = 2;

            $objDau->UpdateSolicitud_transfusion($objCon, $parametrosTransfusion);

            $parametrosTransfusion['descripcion_log']  = "Asignación de responsable de toma de muestra";
            $objDau->InsertSolicitudes_transfusion_movimiento($objCon, $parametrosTransfusion);


          }
          $parametros['indicacion_id']    = "null";
          $parametros['dau_mov_usuario']  = $parametros['usuario_Aplica'];
          $parametros['observacion_rce']  = $parametros['observacion_aplica'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          $response = array("status" => "success", "id" => $parametros['indicacion_id']);
        } else if ($ind_estado == 3) {
          $message = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> esta en estado No Aplicado, debido a que el dau <b>' . $parametros['dau_id'] . '</b> ha sido cerrado.';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $message = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede volver a aplicar.';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        }
      } else if ($parametros['tipo_id'] == 3) {
        $resLab = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
        $parametrosExamen['regId']                  = $resLab[0]['regId'];
        $parametrosExamen['tubo_id']                = $resLab[0]['tubo_id'];
        $parametrosExamen['sol_lab_fechaInserta']   = $resLab[0]['sol_lab_fechaInserta'];

        $listarIndicacionesporTubo          = $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
        for ($i=0; $i<count($listarIndicacionesporTubo); $i++){

          $lab_estado                 = $listarIndicacionesporTubo[$i]['sol_lab_estado'];
          $parametros['solicitud_id'] = $listarIndicacionesporTubo[$i]['sol_lab_id'];
          $exito                      = "";
          if ($lab_estado == 7) {
            $parametros['lab_est_id'] =   1;
            $parametros['tipo_id_lab'] =   2;
            $aplicarIndicacion = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
            $aplicarIndicacionDetalle = $objLaboratorio->editarDetalleLaboratorio($objCon, $parametros);
            $parametros['dau_mov_descripcion'] = 'aplicar_indicacion_laboratorio';
            $parametros['tipo'] = 3;
            $parametros['indicacion_id'] = "null";
            $parametros['dau_mov_usuario'] = $parametros['usuario_Aplica'];
            $parametros['observacion_rce'] = $parametros['observacion_aplica'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            $exito = 'S';
            $response = array("status" => "success", "id" => $parametros['indicacion_id']);
          } else if ($lab_estado == 3) {
            $message   = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> esta en estado No Aplicado, debido a que el dau <b>' . $parametros['dau_id'] . '</b> ha sido cerrado.';
            $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          } else if ($lab_estado == 1) {
            $message   = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> aún no ha sido recepcionada en Laboratorio';
            $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          } else {
            $message = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede volver a aplicar.';
            $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          }
          if($exito == 'S'){
            $response = array("status" => "success", "id" => $parametros['indicacion_id']);
          }
        }
      }

      $parametros['frm_numero_dau'] = $parametros['dau_id'];
      // $datosIndicacion              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']          = $parametros['rce_id'];
      $datosSol                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);

      if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
        $parametros['dau_indicacion_terminada'] = 1;
        $objDau->dau_indicacion($objCon, $parametros);
      } else {
        $parametros['dau_indicacion_terminada'] = 0;
        $objDau->dau_indicacion($objCon, $parametros);
      }

      $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
      $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
      $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);

      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($response);
    }

  break;
  case "gestionRealizada":
    $parametrosSESP                                     = array();
    $parametros                                         = $objUtil->getFormulario($_POST);
    $parametrosSESP['SESPid']                           = $parametros['idsolicitudEspecialista'];
    $parametrosSESP['SESPgestionRealizada']             = $objUtil->existe($parametros['frm_gestionRealizada']) ? $parametros['frm_gestionRealizada'] : "N";
    $parametrosSESP['SESPusuarioGestionRealizada']      = ($parametros['frm_gestionRealizada'] == "S") ? $_SESSION['MM_Username'.SessionName] : "";
    $parametrosSESP["SESPidProfesionalEspecialista"]    = $objUtil->asignar($parametros["frm_medicoEspecialista"]);
    $parametrosSESP['SESPobservacionGestionRealizada']  = $objUtil->asignar($parametros['frm_observacionGestionRealizada']);
    try {
      $objCon->beginTransaction();
      $objEspecialista->ingresarGestionRealizada($objCon, $parametrosSESP);
      $response = array("status" => "success", "idSESP" => $idSESP);
      $objCon->commit();
      unset($parametros);
      unset($parametrosSESP);
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'ObtenerCIE10GES':
    $parametros             = $objUtil->getFormulario($_POST);
    try {
      $objCon->beginTransaction();

      $respuestaConsulta = $objRegistroClinico->ObtenerCIE10GES($objCon, $parametros['id_cie10']);
      $rsCie10           = $objRegistroClinico->SelectCie10($objCon, $parametros['id_cie10']);
      $respuesta = array("respuestaConsulta" => $respuestaConsulta, "ges" =>$rsCie10[0]['ges']);
      echo json_encode($respuesta);
    }catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
  break;
  case 'UpdatePlantillaAltaUrgencia':
    // require_once("../../../../class/Rce.class.php");
    // $objRce = new Rce;
    // $objCon->db_connect();
    $parametros             = $objUtil->getFormulario($_POST);
    $banderaError           = false;
    $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      
      $objRce->UpdatePlantillaAltaUrgencia($objCon, $parametros);

      $respuesta = array("status" => "success");
      
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
  break;
  case 'EliminarPlantillaAltaUrgencia':
    // require_once("../../../../class/Rce.class.php");
    // $objRce = new Rce;
    // $objCon->db_connect();
    $parametros             = $objUtil->getFormulario($_POST);
    $banderaError           = false;
    $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      
      $objRce->DeletePlantillaAltaUrgencia($objCon, $parametros);

      $respuesta = array("status" => "success");
      
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
  break;
   case 'crearPlantillaAltaUrgencia':
    // require_once("../../../../class/Rce.class.php");
    // $objRce = new Rce;
    // $objCon->db_connect();
    $parametros = $objUtil->getFormulario($_POST);
    $banderaError = false;
    $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      $respuestaConsulta = $objRce->obtenerNombrePlantillasAltaUrgencia($objCon, $parametros['idMedico']);
      $totalRespuestaConsulta = count($respuestaConsulta);
      for ($i = 0; $i < $totalRespuestaConsulta; $i++) {
        if ($respuestaConsulta[$i]['nombrePlantilla'] == $parametros['nombrePlantilla']) {
          $banderaError = true;
        }
      }
      if ($banderaError) {
        $respuesta = array("status" => "error", "message" => "El nombre de la plantilla ya está utilizado");
      } else {
        $resultadoConsulta = $objRce->crearPlantillaAltaUrgencia($objCon, $parametros);
        $respuesta = array("status" => "success", "idPlantilla" => $resultadoConsulta);
      }
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
  break;
  case 'anularIndicacionAll':
    $parametros                           = $objUtil->getFormulario($_POST);
    $tipoImagenologia                     = 1;
    $anularIndicacion                     = 6;
    $parametros['arreglo_id_tipo']        = explode('_', $parametros['indicacion_id']);
    // $parametros['solicitud_id']           = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                = 3;
    // $parametros['id_sic']                 = $parametros['arreglo_id_tipo'][2];
    $parametros['estado_indicacion']      = $anularIndicacion;
    $parametros['usuarioAnula']           = $_SESSION['MM_Username'.SessionName];
    $parametros['observacion_detalle']    = $parametros['frm_observacion_aplica'];
    $parametros['estado_indicacion_rce']  = $anularIndicacion;

    try {
      $objCon->beginTransaction();
      $parametroslab['rce_id']                  = $parametros['rce_id']; 
      $parametroslab['sol_lab_fechaInserta']    = $parametros['arreglo_id_tipo'][0]; 
      $parametroslab['tubo_id']                 = $parametros['arreglo_id_tipo'][1];
      $listadoIndicacionesLab                   = $objRegistroClinico->listarIndicacionesRCELab($objCon,$parametroslab);
      for($w=0;$w<count($listadoIndicacionesLab);$w++){
        $parametrosLab['solicitud_id']          = $listadoIndicacionesLab[$w]['sol_id'];
        $parametros['solicitud_id']             = $listadoIndicacionesLab[$w]['sol_id'];         
        $resLab                                 = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametrosLab);
        $lab_estado                         = $resLab[0]['sol_lab_estado'];
        $usuario_soli                       = $resLab[0]['sol_lab_usuarioInserta'];
        if ($lab_estado == 6) {
        } else {
          $parametros['estado_indicacion_labnet'] = 5;
          $parametros['observacion_anula']        = $parametros['observacion_detalle'];
          $aplicarIndicacionDetalle               = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
          $aplicarIndicacionLabnet                = $objLaboratorio->editarSolicitudLabnet($objCon, $parametros);
          $parametros['dau_mov_descripcion']      = 'terminar_indicacion_laboratorio';
          $parametros['tipo']                     = 3;
          $parametros['indicacion_id']            = "null";
          $parametros['dau_mov_usuario']          = $parametros['usuarioAnula'];
          $parametros['observacion_rce']          = $parametros['observacion_detalle'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);;
          $response                               = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      }
      $parametros['frm_numero_dau']                 = $parametros['dau_id'];
      // $datosIndicacion                              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']                          = $parametros['rce_id'];
      $datosSol                                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
      if ($datosSol[0]['aplicada'] == 0 && $datosSol[0]['total'] == 0) {
        $parametros['dau_indicacion_terminada']     = 0;
        $objDau->dau_indicacion($objCon, $parametros);
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      } else {
        if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
          $parametros['dau_indicacion_terminada']   = 1;
          $objDau->dau_indicacion($objCon, $parametros);
        } else {
          $parametros['dau_indicacion_terminada']   = 0;
          $objDau->dau_indicacion($objCon, $parametros);
        }
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      }
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'borrarIndicacionAll':
    $parametros                                 = $objUtil->getFormulario($_POST);
    $parametros['arreglo_id_tipo']              = explode('_', $parametros['indicacion_id']);
    // $parametros['solicitud_id']                 = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                      = 3;
    $parametros['estado_indicacion']            = 8;
    $parametros['estado_indicacion_rce']        = 8;
    $parametros['observacion_elimina']          = $parametros['frm_observacion_aplica'];
    $parametros['usuario_Elimina']              = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      $parametroslab['rce_id']                  = $parametros['rce_id']; 
      $parametroslab['sol_lab_fechaInserta']    = $parametros['arreglo_id_tipo'][0]; 
      $parametroslab['tubo_id']                 = $parametros['arreglo_id_tipo'][1];
      $listadoIndicacionesLab                   = $objRegistroClinico->listarIndicacionesRCELab($objCon,$parametroslab);
      for($w=0;$w<count($listadoIndicacionesLab);$w++){
        $parametrosLab['solicitud_id']          = $listadoIndicacionesLab[$w]['sol_id'];
        $parametros['solicitud_id']             = $listadoIndicacionesLab[$w]['sol_id'];         
        $resLab                                 = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametrosLab);
        $lab_estado                             = $resLab[0]['sol_lab_estado'];
        $usuario_soli                           = $resLab[0]['sol_lab_usuarioInserta'];
        if ($parametros['usuario_Elimina'] == $usuario_soli) {
          if ($lab_estado == 1) {
            $indicarIndicacionLab               = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
            $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_laboratorio';
            $parametros['tipo']                 = 3;
            $parametros['indicacion_id']        = "null";
            $parametros['dau_mov_usuario']      = $parametros['usuario_Elimina'];
            $parametros['observacion_rce']      = $parametros['observacion_elimina'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          }
        }
      }
      $response                     = array("status" => "success");
      $parametros['frm_numero_dau'] = $parametros['dau_id'];
      // $datosIndicacion              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']          = $parametros['rce_id'];
      $datosSol                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
      if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
        $parametros['dau_indicacion_terminada'] = 1;
        $objDau->dau_indicacion($objCon, $parametros);
      } else {
        $parametros['dau_indicacion_terminada'] = 0;
        $objDau->dau_indicacion($objCon, $parametros);
      }
      $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
      $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
      $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'borrarIndicacion':
    $parametros = $objUtil->getFormulario($_POST);
    $parametros['arreglo_id_tipo']          = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']             = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                  = $parametros['arreglo_id_tipo'][1];
    $parametros['estado_indicacion']        = 8;
    $parametros['estado_indicacion_rce']    = 8;
    $parametros['observacion_elimina']      = $parametros['frm_observacion_aplica'];
    $parametros['usuario_Elimina']          = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      if ($parametros['tipo_id'] == 1) {
        $resIma             = $objImagenologia->listarIndicacionesImagenologia($objCon, $parametros);
        $ima_estado         = $resIma[0]['estadoCabecera'];
        $usuario_solicita   = $resIma[0]['usuarioAplica'];
        $det_ima_id         = $resIma[0]['det_ima_id'];
        if ($parametros['usuario_Elimina'] == $usuario_solicita) {
          if ($ima_estado == 1) {
            $eliminarIndicacionIma              = $objImagenologia->editarCabeceraImagenologia($objCon, $parametros);
            $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_imagenologia';
            $parametros['tipo']                 = $parametros['tipo_id'];
            $parametros['indicacion_id']        = $det_ima_id;
            $parametros['dau_mov_usuario']      = $parametros['usuario_Elimina'];
            $parametros['observacion_rce']      = $parametros['observacion_elimina'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            $response                           = array("status" => "success", "id" => $parametros['indicacion_id']);
          } else {
            $message    = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede eliminar.';
            $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          }
        } else {
          $message      = 'Esta indicacion solo puede ser eliminada por el usuario quién realizo la indicación (<b>' . $usuario_solicita . '</b>).';
          $response     = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        }
        $respSolIma     = $objImagenologia->obtenerSolicitudImagenologia($objCon, $parametros);
        $parametros['SIC_id'] = $respSolIma[0]['SIC_id'];
        $objImagenologia->eliminarSolicitudaImagenologia($objCon, $parametros);
      } else if ($parametros['tipo_id'] == 2 || $parametros['tipo_id'] == 4 || $parametros['tipo_id'] == 6) {
        $resInd         = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
        $ind_estado     = $resInd[0]['sol_ind_estado'];
        $usuario_sol    = $resInd[0]['sol_ind_usuarioInserta'];
        if ($parametros['usuario_Elimina'] == $usuario_sol) {
          if ($ind_estado == 1) {
            $indicarIndicacion = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $parametros);
            if ($parametros['tipo_id'] == 2) {
              $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_tratamiento';
              $parametros['tipo']          = 2;
            } else if ($parametros['tipo_id'] == 4) {
              $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_otro';
              $parametros['tipo']          = 4;
            } else if ($parametros['tipo_id'] == 6) {
              $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_procedimiento';
              $parametros['tipo']          = 6;
            }
            $parametros['indicacion_id']          = "null";
            $parametros['dau_mov_usuario']        = $parametros['usuario_Elimina'];
            $parametros['observacion_rce']        = $parametros['observacion_elimina'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            $response   = array("status" => "success", "id" => $parametros['indicacion_id']);
          } else {
            $message    = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede eliminar.';
            $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          }
        } else {
          $message      = 'Esta indicacion solo puede ser eliminada por el usuario quién realizo la indicación (<b>' . $usuario_sol . '</b>).';
          $response     = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        }
      } else if ($parametros['tipo_id'] == 3) {
        $resLab         = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
        $lab_estado     = $resLab[0]['sol_lab_estado'];
        $usuario_soli   = $resLab[0]['sol_lab_usuarioInserta'];
        if ($parametros['usuario_Elimina'] == $usuario_soli) {
          if ($lab_estado == 1) {
            $indicarIndicacionLab               = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
            $parametros['dau_mov_descripcion']  = 'eliminar_indicacion_laboratorio';
            $parametros['tipo']                 = 3;
            $parametros['indicacion_id']        = "null";
            $parametros['dau_mov_usuario']      = $parametros['usuario_Elimina'];
            $parametros['observacion_rce']      = $parametros['observacion_elimina'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);;
            $response                           = array("status" => "success", "id" => $parametros['indicacion_id']);
          } else {
            $message    = 'La solicitud <b>' . $parametros['solicitud_id'] . '</b> ya ha sido aplicada, por lo tanto, no se puede eliminar.';
            $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          }
        } else {
          $message      = 'Esta indicacion solo puede ser eliminada por el usuario quién realizo la indicación (<b>' . $usuario_soli . '</b>).';
          $response     = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        }
      } else if ($parametros['tipo_id'] == 5) {
        $objEspecialista->eliminarSolicitudEspecialidad($objCon, $parametros);
        $response       = array("status" => "success", "id" => $parametros['indicacion_id']);
      }
      $parametros['frm_numero_dau'] = $parametros['dau_id'];
      // $datosIndicacion              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']          = $parametros['rce_id'];
      $datosSol                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
      if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
        $parametros['dau_indicacion_terminada'] = 1;
        $objDau->dau_indicacion($objCon, $parametros);
      } else {
        $parametros['dau_indicacion_terminada'] = 0;
        $objDau->dau_indicacion($objCon, $parametros);
      }
      $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
      $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
      $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'anularIndicacionEnf':
    $parametros                           = $objUtil->getFormulario($_POST);
    $tipoImagenologia                     = 1;
    $anularIndicacion                     = 6;
    $parametros['arreglo_id_tipo']        = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']           = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                = $parametros['arreglo_id_tipo'][1];
    $parametros['id_sic']                 = $parametros['arreglo_id_tipo'][2];
    $parametros['estado_indicacion']      = $anularIndicacion;
    $parametros['usuarioAnula']           = $_SESSION['MM_Username'.SessionName];
    $parametros['observacion_detalle']    = $parametros['frm_observacion_aplica'];
    $parametros['estado_indicacion_rce']  = $anularIndicacion;
    $parametros['movimiento_enfermeria']  = 'S';
    $horarioServidor                      = $objUtil->getHorarioServidor($objCon);
    try {
      $objCon->beginTransaction();
      if ((int)$parametros['tipo_id'] === $tipoImagenologia) {
        $indicacionesImagenologia = $objImagenologia->listarIndicacionesImagenologia($objCon, $parametros);
        $estadoIndicacion         = $indicacionesImagenologia[0]['estadoCabecera'];
        $usuarioAplica            = $indicacionesImagenologia[0]['usuarioAplica'];
        if ($estadoIndicacion == $anularIndicacion) {
          $message = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuarioAplica . '</b>';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $objImagenologia->editarCabeceraImagenologia($objCon, $parametros);
          $parametrosSolicitudImagenologia["SIC_RCE_sol_ima_id"]  = $parametros["solicitud_id"];
          $solicitudImagenologia                                  = $objImagenologia->anularIndicacionSolicitudImagenologia($objCon, $parametrosSolicitudImagenologia);
          //Cambiar estado detalle integración dalca
          $parametrosDalca["solicitud_id"]                  = $parametros["solicitud_id"];
          $parametrosDalca['estado_indicacion']             = $anularIndicacion;
          $objImagenologia->editarEstadoDetalleSolicitudImagenologiaDalca($objCon, $parametrosDalca);
          for ($indice = 0; $indice < count($indicacionesImagenologia); $indice++) {
            //Guardar movimiento
            $parametrosMovimiento['dau_id']                 = $parametros['dau_id'];
            $parametrosMovimiento['rce_id']                 = $parametros['rce_id'];
            $parametrosMovimiento['solicitud_id']           = $parametros['solicitud_id'];
            $parametrosMovimiento['estado_indicacion_rce']  = $anularIndicacion;
            $parametrosMovimiento['dau_mov_descripcion']    = 'terminar_indicacion_imagenologia';
            $parametrosMovimiento['tipo']                   = $tipoImagenologia;
            $parametrosMovimiento['dau_mov_usuario']        = $parametros['usuarioAnula'];
            $parametrosMovimiento['indicacion_id']          = $indicacionesImagenologia[$indice]['det_ima_id'];
            $parametrosMovimiento['SIC_id_rayos']           = $indicacionesImagenologia[$indice]['SIC_id'];
            $parametrosMovimiento['id_solicitud_dalca']     = $indicacionesImagenologia[$indice]['idSolicitudDalca'];
            $parametrosMovimiento['observacion_rce']        = $parametros['observacion_detalle'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametrosMovimiento);
            //Anular indicación integración ingrad
            $parametrosIntegracion                          = array();
            $parametrosSolicitudCama                        = array();
            $parametrosSolicitudCama["SIC_id"]              = $indicacionesImagenologia[$indice]["SIC_id"];
            $parametrosIntegracion["LEX_id_solicitud_rce"]  = $objImagenologia->obtenerIdSolicitudCabeceraRegistro($objCon, $parametrosSolicitudCama);
            $datosIntegracion                               = $objImagenologia->obtenerDatosIntegracionIngrad($objCon, $parametrosIntegracion);
            if (!$objUtil->existe($datosIntegracion)) {
              $datosIntegracion                             = $objImagenologia->obtenerDatosIntegracionIngradHistorico($objCon, $parametrosIntegracion);
            }
            $datosIntegracion[0]["INTusuario_registro"]     = $_SESSION['MM_Username'.SessionName];
            $datosIntegracion[0]["INTmetodo"]               = "DeleteOrden";
            $datosIntegracion[0]["INTid_recurso"]           = $_SESSION['MM_RUNUSU'.SessionName];
            $datosIntegracion[0]["INTmedico_solicitante"]   = $_SESSION['MM_UsernameName'.SessionName];
            unset($datosIntegracion[0]["INTidtoken"]);
            unset($datosIntegracion[0]["INTfecha_proceso"]);
            unset($datosIntegracion[0]["INTresultado"]);
            $idIngrad                                       = $objImagenologia->obtenerIdIngradIntegracion($objCon, $parametrosIntegracion);
            $datosIntegracion[0]["INTid_imgrad"]            = $idIngrad[0]["INTid_imgrad"];
            $objImagenologia->ingresarIntegracionIngrad($objCon, $datosIntegracion[0]);
            // Anular indicación integración dalca
            if ($objUtil->existe($indicacionesImagenologia[$indice]["idSolicitudDalca"])) {
              $parametrosIntegracionDalca = array(
                "id_solicitud" => (int)$indicacionesImagenologia[$indice]["idSolicitudDalca"],
                "observacion" => $parametros['observacion_detalle'],
                "usuario" => $_SESSION['MM_Username'.SessionName]
              );
              $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => IpDalca.'/apiHJNCDalca/cancelarSolicitud',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $parametrosIntegracionDalca,
                CURLOPT_HTTPHEADER => array(
                  'accept: application/json',
                  'Content-Type: multipart/form-data'
                ),
              ));
              $response = curl_exec($curl);
              curl_close($curl);
              if (json_decode($response)->status !== 200) {
                $objCon->rollback();
                $response = array("status" => "error", "message" => "Se ha producido un error al intentar anular la indicación en integración DALCA, favor intente denuevo.<br /><br />"."Respuesta de DALCA: ".$response);
                echo json_encode($response);
                exit(1);
              }
            }
          }
          $response = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      } else if ($parametros['tipo_id'] == 2 || $parametros['tipo_id'] == 4 || $parametros['tipo_id'] == 6  || $parametros['tipo_id'] == 8) {
        $resInd                               = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
        $ind_estado                           = $resInd[0]['sol_ind_estado'];
        $usuario_sol                          = $resInd[0]['sol_ind_usuarioInserta'];
        if ($parametros['tipo_id'] == 2) {
          $parametros['tipo']                 = 2;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_tratamiento';
        } else if ($parametros['tipo_id'] == 4) {
          $parametros['tipo']                 = 4;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_otro';
        } else if ($parametros['tipo_id'] == 6) {
          $parametros['tipo']                 = 6;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_procediemito';
        }else if ($parametros['tipo_id'] == 8) {
          $parametros['dau_mov_descripcion']  = 'terminar_solicitud_transfusion';
          $parametros['tipo']                 = 8;
          $resIndTrans                                        = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
          $parametrosTransfusion['id_solicitud_transfusion']  = $resIndTrans[0]['id_solicitud_transfusion'];
          $parametrosTransfusion['toma_muestra_usuario']      = $_SESSION['MM_Username'.SessionName];
          $parametrosTransfusion['toma_muestra_fecha']        = $horarioServidor[0]['fecha'];
          $parametrosTransfusion['toma_muestra_hora']         = $horarioServidor[0]['hora'];
          $parametrosTransfusion['toma_muestra_observacion']  = $parametros['frm_observacion_aplica'];
          $parametrosTransfusion['estado']                    = 8;

          $objDau->UpdateSolicitud_transfusion($objCon, $parametrosTransfusion);
          
          $parametrosTransfusion['descripcion_log']  = "Anulada la toma de muestra desde URGENCIAS";
          $objDau->InsertSolicitudes_transfusion_movimiento($objCon, $parametrosTransfusion);

        }
        if ($ind_estado == 6) {
          $message    = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuario_sol . '</b>';
          $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $parametros['indicacion_id']      = "null";
          $parametros['dau_mov_usuario']    = $parametros['usuarioAnula'];
          $parametros['observacion_rce']    = $parametros['observacion_detalle'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          $aplicarIndicacion                = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $parametros);
          $response                         = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      } else if ($parametros['tipo_id'] == 3) {
        $resLab                                     = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
        $parametrosExamen['regId']                  = $resLab[0]['regId'];
        $parametrosExamen['tubo_id']                = $resLab[0]['tubo_id'];
        $parametrosExamen['sol_lab_fechaInserta']   = $resLab[0]['sol_lab_fechaInserta'];
        $exito                                      = "";
        $listarIndicacionesporTubo                  = $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
        for ($i=0; $i<count($listarIndicacionesporTubo); $i++){
          $lab_estado                         = $listarIndicacionesporTubo[$i]['sol_lab_estado'];
          $usuario_soli                       = $listarIndicacionesporTubo[$i]['sol_lab_usuarioInserta'];
          $parametros['solicitud_id']         = $listarIndicacionesporTubo[$i]['sol_lab_id'];
          if ($lab_estado == 6) {
            $message                          = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuario_soli . '</b>';
            $response                         = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
          } else {
            $parametros['estado_indicacion_labnet'] = 5;
            $parametros['observacion_anula']        = $parametros['observacion_detalle'];
            $aplicarIndicacionDetalle               = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
            $aplicarIndicacionLabnet                = $objLaboratorio->editarSolicitudLabnet($objCon, $parametros);
            $parametros['dau_mov_descripcion']      = 'terminar_indicacion_laboratorio';
            $parametros['tipo']                     = 3;
            $parametros['indicacion_id']            = "null";
            $parametros['dau_mov_usuario']          = $parametros['usuarioAnula'];
            $parametros['observacion_rce']          = $parametros['observacion_detalle'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
            $exito                                  = 1;
            $response                               = array("status" => "success", "id" => $parametros['indicacion_id']);
          }
          if($exito==1){
            $response                               = array("status" => "success", "id" => $parametros['indicacion_id']);
          }
        }
      } else if ($parametros['tipo_id'] == 5) {
        $objEspecialista->anularSolicitudEspecialidad($objCon, $parametros);
        $response       = array("status" => "success", "id" => $parametros['indicacion_id']);
      } else if ($parametros['tipo_id'] == 7) {
        $resp_estado    = $objDau->datosDau($objCon, $parametros);
        if ($resp_estado[0]['est_id'] == 5) {
          $response     = array("status" => "error", "id" => $parametros['dau_id'], "message" => 'Al paciente ya se le realizó el Cierre del DAU (Por usuario o DAU Automático)');
        } else {
          $objAltaUrgencia->anularSolicitudAltaUrgencia($objCon, $parametros);
          $objAltaUrgencia->eliminarIndicacionEgreso($objCon, $parametros['dau_id']);
          $objAltaUrgencia->actualizarRegistroClinicoAlAnularAltaUrgencia($objCon, $parametros['rce_id']);
          $objAltaUrgencia->actualizarDAUAlAnularAltaUrgencia($objCon, $parametros['dau_id']);
          $objRce->eliminarSolicitudSIC($objCon, $parametros['dau_id']);
          $objRce->eliminarSolicitudAPS($objCon, $parametros['dau_id']);
          $objRce->eliminarRegistroViolencia($objCon, $parametros['dau_id']);
          $parametrosSeguimiento = array(
            "idDau" => $parametros["dau_id"]
          );
          $objDau->eliminarSeguimientoPaciente($objCon, $parametrosSeguimiento);
          $response   = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      }
      $parametros['frm_numero_dau']                 = $parametros['dau_id'];
      // $datosIndicacion                              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']                          = $parametros['rce_id'];
      $datosSol                                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
      if ($datosSol[0]['aplicada'] == 0 && $datosSol[0]['total'] == 0) {
        $parametros['dau_indicacion_terminada']     = 0;
        $objDau->dau_indicacion($objCon, $parametros);
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      } else {
        if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
          $parametros['dau_indicacion_terminada']   = 1;
          $objDau->dau_indicacion($objCon, $parametros);
        } else {
          $parametros['dau_indicacion_terminada']   = 0;
          $objDau->dau_indicacion($objCon, $parametros);
        }
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      }
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'anularIndicacion':
    $parametros                           = $objUtil->getFormulario($_POST);
    $tipoImagenologia                     = 1;
    $anularIndicacion                     = 6;
    $parametros['arreglo_id_tipo']        = explode('-', $parametros['indicacion_id']);
    $parametros['solicitud_id']           = $parametros['arreglo_id_tipo'][0];
    $parametros['tipo_id']                = $parametros['arreglo_id_tipo'][1];
    $parametros['id_sic']                 = $parametros['arreglo_id_tipo'][2];
    $parametros['estado_indicacion']      = $anularIndicacion;
    $parametros['usuarioAnula']           = $_SESSION['MM_Username'.SessionName];
    $parametros['observacion_detalle']    = $parametros['frm_observacion_aplica'];
    $parametros['estado_indicacion_rce']  = $anularIndicacion;

    try {
      $objCon->beginTransaction();
      if ((int)$parametros['tipo_id'] === $tipoImagenologia) {
        $indicacionesImagenologia = $objImagenologia->listarIndicacionesImagenologia($objCon, $parametros);
        $estadoIndicacion         = $indicacionesImagenologia[0]['estadoCabecera'];
        $usuarioAplica            = $indicacionesImagenologia[0]['usuarioAplica'];
        if ($estadoIndicacion == $anularIndicacion) {
          $message = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuarioAplica . '</b>';
          $response = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $objImagenologia->editarCabeceraImagenologia($objCon, $parametros);
          $parametrosSolicitudImagenologia["SIC_RCE_sol_ima_id"]  = $parametros["solicitud_id"];
          $solicitudImagenologia                                  = $objImagenologia->anularIndicacionSolicitudImagenologia($objCon, $parametrosSolicitudImagenologia);
          //Cambiar estado detalle integración dalca
          $parametrosDalca["solicitud_id"]                  = $parametros["solicitud_id"];
          $parametrosDalca['estado_indicacion']             = $anularIndicacion;
          $objImagenologia->editarEstadoDetalleSolicitudImagenologiaDalca($objCon, $parametrosDalca);
          for ($indice = 0; $indice < count($indicacionesImagenologia); $indice++) {
            //Guardar movimiento
            $parametrosMovimiento['dau_id']                 = $parametros['dau_id'];
            $parametrosMovimiento['rce_id']                 = $parametros['rce_id'];
            $parametrosMovimiento['solicitud_id']           = $parametros['solicitud_id'];
            $parametrosMovimiento['estado_indicacion_rce']  = $anularIndicacion;
            $parametrosMovimiento['dau_mov_descripcion']    = 'terminar_indicacion_imagenologia';
            $parametrosMovimiento['tipo']                   = $tipoImagenologia;
            $parametrosMovimiento['dau_mov_usuario']        = $parametros['usuarioAnula'];
            $parametrosMovimiento['indicacion_id']          = $indicacionesImagenologia[$indice]['det_ima_id'];
            $parametrosMovimiento['SIC_id_rayos']           = $indicacionesImagenologia[$indice]['SIC_id'];
            $parametrosMovimiento['id_solicitud_dalca']     = $indicacionesImagenologia[$indice]['idSolicitudDalca'];
            $parametrosMovimiento['observacion_rce']        = $parametros['observacion_detalle'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametrosMovimiento);
            //Anular indicación integración ingrad
            $parametrosIntegracion                          = array();
            $parametrosSolicitudCama                        = array();
            $parametrosSolicitudCama["SIC_id"]              = $indicacionesImagenologia[$indice]["SIC_id"];
            $parametrosIntegracion["LEX_id_solicitud_rce"]  = $objImagenologia->obtenerIdSolicitudCabeceraRegistro($objCon, $parametrosSolicitudCama);
            $datosIntegracion                               = $objImagenologia->obtenerDatosIntegracionIngrad($objCon, $parametrosIntegracion);
            if (!$objUtil->existe($datosIntegracion)) {
              $datosIntegracion                             = $objImagenologia->obtenerDatosIntegracionIngradHistorico($objCon, $parametrosIntegracion);
            }
            $datosIntegracion[0]["INTusuario_registro"]     = $_SESSION['MM_Username'.SessionName];
            $datosIntegracion[0]["INTmetodo"]               = "DeleteOrden";
            $datosIntegracion[0]["INTid_recurso"]           = $_SESSION['MM_RUNUSU'.SessionName];
            $datosIntegracion[0]["INTmedico_solicitante"]   = $_SESSION['MM_UsernameName'.SessionName];
            unset($datosIntegracion[0]["INTidtoken"]);
            unset($datosIntegracion[0]["INTfecha_proceso"]);
            unset($datosIntegracion[0]["INTresultado"]);
            $idIngrad                                       = $objImagenologia->obtenerIdIngradIntegracion($objCon, $parametrosIntegracion);
            $datosIntegracion[0]["INTid_imgrad"]            = $idIngrad[0]["INTid_imgrad"];
            $objImagenologia->ingresarIntegracionIngrad($objCon, $datosIntegracion[0]);
            // Anular indicación integración dalca
            if ($objUtil->existe($indicacionesImagenologia[$indice]["idSolicitudDalca"])) {
              $parametrosIntegracionDalca = array(
                "id_solicitud" => (int)$indicacionesImagenologia[$indice]["idSolicitudDalca"],
                "observacion" => $parametros['observacion_detalle'],
                "usuario" => $_SESSION['MM_Username'.SessionName]
              );
              $curl = curl_init();
              curl_setopt_array($curl, array(
                CURLOPT_URL => IpDalca.'/apiHJNCDalca/cancelarSolicitud',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $parametrosIntegracionDalca,
                CURLOPT_HTTPHEADER => array(
                  'accept: application/json',
                  'Content-Type: multipart/form-data'
                ),
              ));
              $response = curl_exec($curl);
              curl_close($curl);
              if (json_decode($response)->status !== 200) {
                $objCon->rollback();
                $response = array("status" => "error", "message" => "Se ha producido un error al intentar anular la indicación en integración DALCA, favor intente denuevo.<br /><br />"."Respuesta de DALCA: ".$response);
                echo json_encode($response);
                exit(1);
              }
            }
          }
          $response = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      } else if ($parametros['tipo_id'] == 2 || $parametros['tipo_id'] == 4 || $parametros['tipo_id'] == 6 || $parametros['tipo_id'] == 8) {
        $resInd                               = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
        $ind_estado                           = $resInd[0]['sol_ind_estado'];
        $usuario_sol                          = $resInd[0]['sol_ind_usuarioInserta'];
        if ($parametros['tipo_id'] == 2) {
          $parametros['tipo']                 = 2;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_tratamiento';
        } else if ($parametros['tipo_id'] == 4) {
          $parametros['tipo']                 = 4;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_otro';
        } else if ($parametros['tipo_id'] == 6) {
          $parametros['tipo']                 = 6;
          $parametros['dau_mov_descripcion']  = 'terminar_indicacion_procediemito';
        }else if ($parametros['tipo_id'] == 8) {
          $parametros['dau_mov_descripcion']  = 'terminar_solicitud_transfusion';
          $parametros['tipo']                 = 8;
          $resIndTrans                                        = $objRegistroClinico->listarIndicaciones($objCon, $parametros);
          $parametrosTransfusion['id_solicitud_transfusion']  = $resIndTrans[0]['id_solicitud_transfusion'];
          $parametrosTransfusion['toma_muestra_usuario']      = $_SESSION['MM_Username'.SessionName];
          $parametrosTransfusion['toma_muestra_fecha']        = $horarioServidor[0]['fecha'];
          $parametrosTransfusion['toma_muestra_hora']         = $horarioServidor[0]['hora'];
          $parametrosTransfusion['toma_muestra_observacion']  = $parametros['frm_observacion_aplica'];
          $parametrosTransfusion['estado']                    = 8;

          $objDau->UpdateSolicitud_transfusion($objCon, $parametrosTransfusion);

          $parametrosTransfusion['descripcion_log']  = "Anulada la toma de muestra desde URGENCIAS";
          $objDau->InsertSolicitudes_transfusion_movimiento($objCon, $parametrosTransfusion);

        }
        if ($ind_estado == 6) {
          $message    = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuario_sol . '</b>';
          $response   = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $parametros['indicacion_id']      = "null";
          $parametros['dau_mov_usuario']    = $parametros['usuarioAnula'];
          $parametros['observacion_rce']    = $parametros['observacion_detalle'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          $aplicarIndicacion                = $objRegistroClinico->editarSolicitudIndicaciones($objCon, $parametros);
          $response                         = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      } else if ($parametros['tipo_id'] == 3) {
        $resLab                             = $objLaboratorio->listarIndicacionesLaboratorio($objCon, $parametros);
        $lab_estado                         = $resLab[0]['sol_lab_estado'];
        $usuario_soli                       = $resLab[0]['sol_lab_usuarioInserta'];
        if ($lab_estado == 6) {
          $message                          = 'Esta indicacion ya ha sido Terminada por el usuario <b>' . $usuario_soli . '</b>';
          $response                         = array("status" => "error", "id" => $parametros['indicacion_id'], "message" => $message);
        } else {
          $parametros['estado_indicacion_labnet'] = 5;
          $parametros['observacion_anula']        = $parametros['observacion_detalle'];
          $aplicarIndicacionDetalle               = $objLaboratorio->editarSolicitudLaboratorio($objCon, $parametros);
          $aplicarIndicacionLabnet                = $objLaboratorio->editarSolicitudLabnet($objCon, $parametros);
          $parametros['dau_mov_descripcion']      = 'terminar_indicacion_laboratorio';
          $parametros['tipo']                     = 3;
          $parametros['indicacion_id']            = "null";
          $parametros['dau_mov_usuario']          = $parametros['usuarioAnula'];
          $parametros['observacion_rce']          = $parametros['observacion_detalle'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);;
          $response                               = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      } else if ($parametros['tipo_id'] == 5) {
        $objEspecialista->anularSolicitudEspecialidad($objCon, $parametros);
        $response       = array("status" => "success", "id" => $parametros['indicacion_id']);
      } else if ($parametros['tipo_id'] == 7) {
        $resp_estado    = $objDau->datosDau($objCon, $parametros);
        if ($resp_estado[0]['est_id'] == 5) {
          $response     = array("status" => "error", "id" => $parametros['dau_id'], "message" => 'Al paciente ya se le realizó el Cierre del DAU (Por usuario o DAU Automático)');
        } else {
          $objAltaUrgencia->anularSolicitudAltaUrgencia($objCon, $parametros);
          $objAltaUrgencia->eliminarIndicacionEgreso($objCon, $parametros['dau_id']);
          $objAltaUrgencia->actualizarRegistroClinicoAlAnularAltaUrgencia($objCon, $parametros['rce_id']);
          $objAltaUrgencia->actualizarDAUAlAnularAltaUrgencia($objCon, $parametros['dau_id']);
          $objRce->eliminarSolicitudSIC($objCon, $parametros['dau_id']);
          $objRce->eliminarSolicitudAPS($objCon, $parametros['dau_id']);
          $objRce->eliminarRegistroViolencia($objCon, $parametros['dau_id']);
          $parametrosSeguimiento = array(
            "idDau" => $parametros["dau_id"]
          );
          $objDau->eliminarSeguimientoPaciente($objCon, $parametrosSeguimiento);
          $response   = array("status" => "success", "id" => $parametros['indicacion_id']);
        }
      }else if ($parametros['tipo_id'] == 8) {
        $objEspecialista->anularSolicitudEspecialidadOtros($objCon, $parametros);
        $response       = array("status" => "success", "id" => $parametros['indicacion_id']);
      }
      $parametros['frm_numero_dau']                 = $parametros['dau_id'];
      // $datosIndicacion                              = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);
      $parametros['regId']                          = $parametros['rce_id'];
      $datosSol                                     = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);
      if ($datosSol[0]['aplicada'] == 0 && $datosSol[0]['total'] == 0) {
        $parametros['dau_indicacion_terminada']     = 0;
        $objDau->dau_indicacion($objCon, $parametros);
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      } else {
        if ($datosSol[0]['aplicada'] == $datosSol[0]['total']) {
          $parametros['dau_indicacion_terminada']   = 1;
          $objDau->dau_indicacion($objCon, $parametros);
        } else {
          $parametros['dau_indicacion_terminada']   = 0;
          $objDau->dau_indicacion($objCon, $parametros);
        }
        $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];
        $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];
        $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);
      }
      $objCon->commit();
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  case 'eliminarSolicitudEvolucion':
        $parametros = $objUtil->getFormulario($_POST);
        try{
            $objCon->beginTransaction();
            $resultadoUsuario = $objEvolucion->obtenerUsuarioSolicitudEvolucion($objCon, $parametros['indicacion_id']);
            if ( $_SESSION['MM_Username'.SessionName] == $resultadoUsuario['SEVOusuario'] ) {
                $objEvolucion->eliminarSolicitudEvolucion($objCon, $parametros['indicacion_id']);
                $response = array("status" => "success");
            } else {
                $response = array("status" => "errorUsuario", "message" => "La indicación sólo puede ser eliminada por el mismo Usuario quien la realizó");
            }
            echo json_encode($response);
            $objCon->commit();
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }
  break;
  case 'insertarIndicaciones':

    $parametros                       = $objUtil->getFormulario($_POST);

    $horarioServidor                  = $objUtil->getHorarioServidor($objCon);

    $parametros['sol_lab_fechaInserta']  = date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);

    $parametros['arr_imagenologia']   = json_decode(stripslashes($parametros['carroIma']));
    $parametros['arr_tratamiento']    = json_decode(stripslashes($parametros['carroTra']));
    $parametros['arr_laboratorio']    = json_decode(stripslashes($parametros['carroLab']));
    $parametros['arr_laboratorio2']   = json_decode(stripslashes($parametros['carroLab2']));
    $parametros['arr_otro']           = json_decode(stripslashes($parametros['carroOtr']));
    $parametros['arr_tratamiento_nuevo'] = json_decode(stripslashes($parametros['carroTratamiento']));
    $parametros['arr_tratamientoTexto'] = json_decode(stripslashes($parametros['carroTraTexto']));

    $parametros['concat_imagenologia'] = "";
    $parametros['concat_tratamiento'] = "";
    $parametros['concat_laboratorio'] = "";
    $parametros['concat_laboratorio2'] = "";
    $parametros['concat_otro'] = "";
    $parametros['concat_tratamiento_nuevo'] = "";

    $datosDau = $objDau->ListarPacientesDau($objCon, $parametros);
    $parametros['frm_id_paciente'] = $datosDau[0]['id_paciente'];
    $datosCamaSala = $objDau->buscarCamaYsala($objCon, $parametros);
    $datosPaciente = $objDau->obtenerPaciente($objCon, $parametros);
    $parametros['id_paciente'] = $datosDau[0]['id_paciente'];
    $parametros['ctacte_paciente'] = $datosDau[0]['idctacte'] != '' ? $datosDau[0]['idctacte'] : "";
    $parametros['edad_paciente'] = $datosDau[0]['dau_paciente_edad'];
    $parametros['sexo_paciente'] = $datosPaciente[0]['sexo'];
    $parametros['procedencia'] = "Urgencia";
    $parametros['procedencia_cod'] = 1;
    $parametros['SIC_no_aplicado'] = 1;
    $parametros['estado_indicacion'] = 1;
    $subparametros['rce_id'] = $parametros['rce_id'];
    $parametros["regId"] = $parametros["rce_id"];
    $parametros['estado_indicacion_rce'] = 1;
    $parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
    $subparametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];

    $lateralidadesDalca = array(
      "izquierda" => "left",
      "derecha" => "right",
      "ambos" => "both",
      "sin lateralidad" => "no_laterality",
    );

    try {

      $objCon->beginTransaction();

      $parametrosCabera['fecha']      = $horarioServidor[0]['fecha'];
      $parametrosCabera['hora']       = $horarioServidor[0]['hora'];
      $parametrosCabera['usuario']    = $_SESSION['MM_Username'.SessionName];
      $parametrosCabera['dau_id']     = $parametros['dau_id'];
      $CabeceraIndicacionRec          = $objDau->insertarCabeceraIndicacionRec($objCon, $parametrosCabera);

      if (!empty($parametros['arr_imagenologia'])) {

        $parametros['concat_imagenologia'] = "<b>PRESTACION :</b>";

        $motivoEHipotesisInicial        = $objImagenologia->obtenerMotivoEHipotesisInicial($objCon, $parametros);

        $parametrosRCE                  = array();
        $parametrosDetalleRCE           = array();
        $parametrosIntegracionDalca     = array();
        $parametrosMovimiento           = array();
        $estadoSolicitado               = 1;
        $estadoIndicado                 = 2;
        $servicioImagenologia           = 1;

        $parametrosCabera['tipo']       = 1;
        $id_cabecera_indicaciones          = $objDau->insertarCabeceraIndicacionRec($objCon, $parametrosCabera);
        foreach ($parametros['arr_imagenologia'] as $k => $value) {
          $parametrosRCE = null;
          $parametrosDetalleRCE = null;
          $parametrosIntegracionDalca = null;
          $parametrosMovimiento = null;
          $valorExamen = $objUtil->asignar($value[0]);
          $valorTipoExamen = $objUtil->asignar($value[1]);
          $valorLateralidad = $objUtil->asignar($value[2]);
          $valorContrastes = $objUtil->asignar($value[3]);
          $valorObservacion = $objUtil->asignar($value[4]);
          $valorIdPrestacion  = $objUtil->asignar($value[5]);
          $valorPrestaciones  = $objUtil->asignar($value[6]);
          $infeccionOColonizacionMultirresistente = ($objUtil->existe($parametros["frm_multirresistente"])) ? "S" : "N";
          $asma = ($objUtil->existe($parametros["frm_Asma"])) ? "S" : "N";
          $embarazo = ($objUtil->existe($parametros["frm_Embarazo"])) ? "S" : "N";
          $hipertension = ($objUtil->existe($parametros["frm_Hipertension"])) ? "S" : "N";
          $diabetes = ($objUtil->existe($parametros["frm_Diabetes"])) ? "S" : "N";
          $otro = ($objUtil->existe($parametros["frm_Otro"])) ? "S" : "N";
          $otrosTexto = ($objUtil->existe($parametros["frm_Otro"])) ? str_replace("<br>", "\r\n", $objUtil->asignar($parametros["frm_otros_text"])) : "";



          //INGRESO RCE
          $parametrosRCE = array(
            "rce_id" => $parametros["regId"],
            "estado_indicacion" => $estadoSolicitado,
            "servicioImagenologia" => $servicioImagenologia,
            "dau_mov_usuario" => $_SESSION['MM_Username'.SessionName],
            "infeccionOColonizacionMultirresistente" => $infeccionOColonizacionMultirresistente,
            "asma" => $asma,
            "embarazo" => $embarazo,
            "hipertension" => $hipertension,
            "diabetes" => $diabetes,
            "otro" => $otro,
            "otrosTexto" => $otrosTexto,
            "id_cabecera_indicaciones" => $id_cabecera_indicaciones,
          );
          $sol_ind_id  = $objImagenologia->insertarSolicitudImagenologia($objCon, $parametrosRCE);
          //FIN

          //INTEGRACIÓN DALCA
          $parametrosIntegracionDalca = array(
            "fecha_registro" => date("Y-m-d"),
            "hora_registro" => date("H:i:s"),
            "status" => "arrived",
            "clinical_history" => str_replace("<br>", "\r\n", $objUtil->asignar($motivoEHipotesisInicial[0]["regHipotesisInicial"])),
            "surgical_history" => "",
            "patient_class" => "E",
            "id_paciente" => (int)$parametros['id_paciente'],
            "sub_procedency" => "CR Emergencia - ".$datosDau[0]["ate_descripcion"],
            "medical_center" => "101100",
            "estado" => "Solicitado",
            "id_tipo_solicitud" => 1,
            "valor_registro_solicitud" => (int)$parametros["dau_id"],
            "rut_medico_solicitante" => $_SESSION['MM_RUNUSU'.SessionName],
            "id_prestaciones" => $valorIdPrestacion,
            "consemiento_informado" => (strpos($valorContrastes, "coninf") !== false) ? "S" : "N",
            "premedicacion" => (strpos($valorContrastes, "preme") !== false) ? "S" : "N",
            "clearence_creatinina" => (strpos($valorContrastes, "clecre") !== false) ? "S" : "N",
            "proteccion_renal" => (strpos($valorContrastes, "proren") !== false) ? "S" : "N",
            "sedacion" => (strpos($valorContrastes, "sedacion") !== false) ? "S" : "N",
            "marcapasos" => (strpos($valorContrastes, "marca_paso") !== false) ? "S" : "N",

            "lateralidad" => ($objUtil->existe($lateralidadesDalca[strtolower($valorLateralidad)])) ? $lateralidadesDalca[strtolower($valorLateralidad)] : $lateralidadesDalca["default"],
            "contraste" => ($objUtil->existe($valorContrastes)) ? "S" : "N",
            "observacion_solicitud" => $valorObservacion,
            "embarazo" => $embarazo,
            "multires" => $infeccionOColonizacionMultirresistente,
            "diabetes" => $diabetes,
            "asma" => $asma,
            "hipertension" => $hipertension,
            "sintomas" => str_replace("<br>", "\r\n", $objUtil->asignar($parametros["frm_sintomasp"])),
            "otros_texto" => $otrosTexto,
            "usuario_solicita" => $_SESSION['MM_Username'.SessionName],
            "ingreso_manual" => "N",
            "procedencia" => "Urgencias"
          );

          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => IpDalca.'/apiHJNCDalca/crearSolicitud',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($parametrosIntegracionDalca),
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
            ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);

          $respuestaDalca = json_decode($response);
          if ($respuestaDalca->status !== 200) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => "Se ha producido un error al intentar ingresar la indicación en integración DALCA, favor intente denuevo.<br /><br />"."Respuesta de DALCA: ".$response);
            echo json_encode($response);
            exit(1);
          }

          $idSolicitudDalca = ($objUtil->existe($respuestaDalca) && $respuestaDalca->status === 200) ? $respuestaDalca->id_solicitud : 0;
          //FIN



          //INGRESO DETALLE IMAGENOLOGIA RCE DALCA
          $parametrosDetalleRCE = array(
            "idSolicitudImagenologia" => $sol_ind_id,
            "idSolicitudDalca" => $objUtil->asignar($idSolicitudDalca),
            "idPrestacionImagenologia" => $valorIdPrestacion,
            "idEstadoDetalleSolicitud" => $estadoIndicado,
            "contraste" => ($objUtil->existe($valorContraste)) ? "S" : "N",
            "consentimientoInformado" => (strpos($valorContrastes, "consentimientoInformado") !== false) ? "S" : "N",
            "clearenceDeCreatinina" => (strpos($valorContrastes, "clearenceDeCreatinina") !== false) ? "S" : "N",
            "premedicacion" => (strpos($valorContrastes, "premedicacion") !== false) ? "S" : "N",
            "proteccionRenal" => (strpos($valorContrastes, "proteccionRenal") !== false) ? "S" : "N",
            "sedacion" => (strpos($valorContrastes, "sedacion") !== false) ? "S" : "N",
            "marcapasos" => (strpos($valorContrastes, "marcapasos") !== false) ? "S" : "N",
            "observacionSolicitud" => $objUtil->asignar($valorObservacion)
          );
          // print('<pre>'); print_r($parametrosDetalleRCE); print('</pre>');
          $idDetalleSolicitudImagenologiaDalca = $objImagenologia->ingresarDetalleSolicitudImagenologiaDalca($objCon, $parametrosDetalleRCE);
          //FIN



          //INGRESO MOVIMIENTO
          $parametrosMovimiento = array(
            "dau_id" => $parametros["dau_id"],
            "rce_id" => $parametros["rce_id"],
            "solicitud_id" => $sol_ind_id,
            "indicacion_id" => $idDetalleSolicitudImagenologiaDalca,
            "tipo" => $servicioImagenologia,
            "estado_indicacion_rce" => $estadoIndicado,
            "dau_mov_descripcion" => "resgitro_indicacion_imagenologia",
            "dau_mov_usuario" => $_SESSION['MM_Username'.SessionName],
            "id_solicitud_dalca" => $idSolicitudDalca,
          );

          // print('<pre>'); print_r($parametrosMovimiento); print('</pre>');
          $objMovimiento->guardarMovimientoRCE($objCon, $parametrosMovimiento);
          //FIN
        }

        //INSERTAR EN BITACORA 29-07-2019
        $subparametrosBitacoraIndicaciones = array();
        $subparametrosBitacoraIndicaciones['BITid'] = $parametros['dau_id'];
        $subparametrosBitacoraIndicaciones['BITtipo_codigo'] = 3;
        $subparametrosBitacoraIndicaciones['BITtipo_descripcion'] = "Indicaciones (imagenología)";
        $subparametrosBitacoraIndicaciones['BITusuario'] = $parametros['dau_mov_usuario'];
        $subparametrosBitacoraIndicaciones['BITdescripcion'] .= "<b>PRESTACION :</b>";
        $subparametrosBitacoraIndicaciones['BITdescripcion'] .= "<br> - " . $valorExamen;
        $subparametrosBitacoraIndicaciones['BITdescripcion'] .= ($objUtil->existe($valorObservacion)) ? ", Observación: " . $valorObservacion : " ";
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacoraIndicaciones);

        $parDetalleInd['tipo']                  = 1;
        $parDetalleInd['tipo_descripcion']      = "PRESTACION ";
        $parDetalleInd['descripcion_detalle']   = $value[0];
        $parDetalleInd['solicitud_id']          = $sol_ind_id;
        $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
        $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
        $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
        $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
        $parDetalleInd['dau_id']                = $parametros['dau_id'];

        $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);


      }

      if (!empty($parametros['arr_tratamiento'])) {

        $parametrosCabera['tipo']          = 6;
        $id_cabecera_indicaciones          = $objDau->insertarCabeceraIndicacionRec($objCon, $parametrosCabera);
        $subparametrosBitacora['BITdescripcion'] = "<b>PROCEDIMIENTOS :</b>";
        foreach ($parametros['arr_tratamiento'] as $k => $value) {
          $parametrosBitacora                  = array();
          $parametrosBitacora['BITid']     = "";
          $subparametros['est_id']      = 1;
          $subparametros['servicio']      = 6;
          $subparametros['codigo']       = "";
          $subparametros['ima_tipo']      = "";
          $subparametros['codigo']        = $value[0];
          $subparametros['descripcion']    = $value[1];
          $subparametros['clasificacionTratamiento']   = 'NULL';
          $subparametros['id_cabecera_indicaciones']   = $id_cabecera_indicaciones;
          $parametros['sol_id']        = $objRegistroClinico->insertarSolicitudIndicaciones($objCon, $subparametros);
          $parametros['dau_mov_descripcion']  = 'registro_indicacion_procedimiento';
          $parametros['solicitud_id']      = $parametros['sol_id'];
          $parametros['indicacion_id']    = "null";
          $parametros['tipo']          = $subparametros['servicio'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          if (!empty($parametros['arr_tratamientoTexto'])) {
            foreach ($parametros['arr_tratamientoTexto'] as $kTexto => $valueTexto) {
              if ($valueTexto[0] == $subparametros['codigo']) {
                $parametrosActProce['sol_ind_estado']           = 4;
                $parametrosActProce['sol_ind_id']              = $parametros['sol_id'];
                $parametrosActProce['sol_ind_servicio']         = 6;
                $parametrosActProce['sol_ind_preCod']           = $subparametros['codigo'];
                $parametrosActProce['sol_observacion_procedimiento']   = $valueTexto[1];
                $parametrosActProce['sol_ind_usuarioIniciaIndicacion']  = $_SESSION['MM_Username'.SessionName];
                $parametrosActProce['sol_ind_fechaIniciaIndicacion']  = date('Y-m-d H:i:s');
                $parametrosActProce['sol_ind_usuarioAplica']      = $_SESSION['MM_Username'.SessionName];
                $parametrosActProce['sol_ind_fechaAplica']        = date('Y-m-d H:i:s');
                $objRegistroClinico->actualizarSolicitudIndicacionesProcedimiento($objCon, $parametrosActProce);
                $parametrosBitacora['BITid']               = $parametros['dau_id'];
                $subparametrosBitacora['BITdescripcion']         .= " <br> - " . $valueTexto[2] . "( " . $valueTexto[1] . " ). ";
              }
            }
          }
          if ($parametrosBitacora['BITid'] == "") {
            $subparametrosBitacora['BITdescripcion']     .= " <br> - " . $subparametros['descripcion'] . "( SIN OBSERVACION ). ";
          }
          $parDetalleInd['tipo']                  = 6;
          $parDetalleInd['tipo_descripcion']      = "PROCEDIMIENTOS";
          $parDetalleInd['descripcion_detalle']   = $value[1];
          $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
          $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
          $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
          $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
          $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
          $parDetalleInd['dau_id']                = $parametros['dau_id'];
          $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);
        }
        $subparametrosBitacora['BITid']          = $parametros['dau_id'];
        $subparametrosBitacora['BITtipo_codigo']      = 6;
        $subparametrosBitacora['BITtipo_descripcion']  = "Indicaciones (Procedimientos)";
        $subparametrosBitacora['BITdatetime']       = "NOW()";
        $subparametrosBitacora['BITusuario']       = $parametros['dau_mov_usuario'];
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacora);
      }

      if (!empty($parametros['arr_tratamiento_nuevo'])) {

        $parametrosCabera['tipo']          = 2;
        $id_cabecera_indicaciones          = $objDau->insertarCabeceraIndicacionRec($objCon, $parametrosCabera);
        $subparametrosBitacora['BITdescripcion'] = "<b>TRATAMIENTO : </b> ";
        $contadorTratamiento = 0;
        foreach ($parametros['arr_tratamiento_nuevo'] as $k => $value) {
          $subparametros['est_id']          = 1;
          $subparametros['servicio']          = 2;
          $subparametros['codigo']           = "";
          $subparametros['ima_tipo']          = "";
          $subparametros['descripcion']        = $value[0];
          $subparametros['clasificacionTratamiento']   = $value[1];
          $subparametros['id_cabecera_indicaciones']   = $id_cabecera_indicaciones;
          $parametros['sol_id']            = $objRegistroClinico->insertarSolicitudIndicaciones($objCon, $subparametros);
          $parametros['dau_mov_descripcion']      = 'registro_indicacion_tratamiento';
          $parametros['solicitud_id']          = $parametros['sol_id'];
          $parametros['indicacion_id']        = "null";
          $parametros['tipo']              = $subparametros['servicio'];
          if($contadorTratamiento > 0){
            $subparametrosBitacora['BITdescripcion']   .= ", ";
          }
          $contadorTratamiento++;
          $subparametrosBitacora['BITdescripcion']   .= "" . $value[0] . " (" . $value[2] . ")";
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);

          $parDetalleInd['tipo']                  = 2;
          $parDetalleInd['tipo_descripcion']      = "TRATAMIENTO";
          $parDetalleInd['descripcion_detalle']   = $value[0]. " (" . $value[2] . ")";
          $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
          $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
          $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
          $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
          $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
          $parDetalleInd['dau_id']                = $parametros['dau_id'];
          $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);

        }
        $subparametrosBitacora['BITdescripcion'] .= ".";
        $subparametrosBitacora['BITid']          = $parametros['dau_id'];
        $subparametrosBitacora['BITtipo_codigo']      = 4;
        $subparametrosBitacora['BITtipo_descripcion']  = "Indicaciones (Tratamiento)";
        $subparametrosBitacora['BITdatetime']       = "NOW()";
        $subparametrosBitacora['BITusuario']       = $parametros['dau_mov_usuario'];
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacora);

      }

      if (!empty($parametros['arr_otro'])) {

        $parametrosCabera['tipo']          = 4;
        $id_cabecera_indicaciones          = $CabeceraIndicacionRec;
        $subparametrosBitacora['BITdescripcion'] = "<b>OTRAS INDICACIONES :</b> ";
        foreach ($parametros['arr_otro'] as $k => $value) {
          $subparametros['est_id']      = 1;
          $subparametros['servicio']      = 4;
          $subparametros['codigo']       = "";
          $subparametros['ima_tipo']      = "";
          $subparametros['descripcion']    = $value[0];
          $subparametros['clasificacionTratamiento']   = 'NULL';
          $subparametros['id_cabecera_indicaciones']   = $id_cabecera_indicaciones;
          $parametros['sol_id']        = $objRegistroClinico->insertarSolicitudIndicaciones($objCon, $subparametros);
          $parametros['dau_mov_descripcion']  = 'registro_indicacion_otros';
          $parametros['solicitud_id']      = $parametros['sol_id'];
          $parametros['indicacion_id']    = "null";
          $parametros['tipo']          = $subparametros['servicio'];
          $subparametrosBitacora['BITdescripcion']         .= "<br> - " . $value[0] . ". ";
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);


          $parDetalleInd['tipo']                  = 4;
          $parDetalleInd['tipo_descripcion']      = "OTRAS INDICACIONES";
          $parDetalleInd['descripcion_detalle']   = $value[0];
          $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
          $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
          $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
          $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
          $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
          $parDetalleInd['dau_id']                = $parametros['dau_id'];
          $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);



        }
        $subparametrosBitacora['BITid']                 = $parametros['dau_id'];
        $subparametrosBitacora['BITtipo_codigo']        = 7;
        $subparametrosBitacora['BITtipo_descripcion']   = "Indicaciones (Otros)";
        $subparametrosBitacora['BITdatetime']           = "NOW()";
        $subparametrosBitacora['BITusuario']            = $parametros['dau_mov_usuario'];
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacora);
      }

      if (!empty($parametros['arr_laboratorio'])) {

        $parametrosCabera['tipo']          = 3;
        $id_cabecera_indicaciones          = $CabeceraIndicacionRec;
        $parametros['concat_laboratorio1'] = " <b>EXAMENES DE LABORATORIO </b>";
        foreach ($parametros['arr_laboratorio'] as $k => $value) {
          $parametros['servicio']          = 3;

          $parametros['id_cabecera_indicaciones']   = $id_cabecera_indicaciones;
          $sol_lab_id                =   $objLaboratorio->insertarSolicitudLaboratorio($objCon, $parametros);
          $subparametros['sol_lab_id']       =  $sol_lab_id;
          $parametros['dau_mov_descripcion']    = 'registro_indicacion_laboratorio';
          $parametros['solicitud_id']        = $subparametros['sol_lab_id'];
          $parametros['indicacion_id']      = "null";
          $parametros['tipo']            = $parametros['servicio'];
          $objMovimiento->guardarMovimientoRCE($objCon, $parametros);
          $subparametros['est_id']      = 2;
          $subparametros['codigo']      = $value[0];
          $subparametros['descripcion']     = $value[1];

          $det_lab_id             = $objLaboratorio->insertarDetalleIndicacionLaboratorio($objCon, $subparametros);
          $parametros['concat_laboratorio']   .= $value[1] . ", ";

          $parDetalleInd['tipo']                  = 3;
          $parDetalleInd['tipo_descripcion']      = "EXAMENES DE LABORATORIO";
          $parDetalleInd['descripcion_detalle']   = $value[1];
          $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
          $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
          $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
          $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
          $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
          $parDetalleInd['dau_id']                = $parametros['dau_id'];
          $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);
        }
        $subparametrosBitacoraIndicaciones                                = array();
        $subparametrosBitacoraIndicaciones['BITid']                      = $parametros['dau_id'];
        $subparametrosBitacoraIndicaciones['BITtipo_codigo']              = 5;
        $subparametrosBitacoraIndicaciones['BITtipo_descripcion']      = "Indicaciones (laboratorio)";
        $subparametrosBitacoraIndicaciones['BITusuario']                  = $parametros['dau_mov_usuario'];
        $subparametrosBitacoraIndicaciones['BITdescripcion']              = trim($parametros['concat_laboratorio'], ', ');
        $subparametrosBitacoraIndicaciones['BITdescripcion']        = $parametros['concat_laboratorio1'] . " (" . $subparametrosBitacoraIndicaciones['BITdescripcion'] . ")";
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacoraIndicaciones);
      } else {

        if (!empty($parametros['arr_laboratorio2'])) {

          $parametrosCabera['tipo']          = 3;
          $id_cabecera_indicaciones          = $CabeceraIndicacionRec;
          $parametros['concat_laboratorio1'] = " <b>EXAMENES DE LABORATORIO</b> ";
          foreach ($parametros['arr_laboratorio2'] as $k => $value) {

            $parametros['id_cabecera_indicaciones']   = $id_cabecera_indicaciones;

  

            $parametros['servicio']                 = 3;
            $sol_lab_id                             = $objLaboratorio->insertarSolicitudLaboratorio($objCon, $parametros);
            $subparametros['sol_lab_id']            = $sol_lab_id;
            $parametros['dau_mov_descripcion']      = 'registro_indicacion_laboratorio';
            $parametros['solicitud_id']             = $subparametros['sol_lab_id'];
            $parametros['indicacion_id']            = "null";
            $parametros['tipo']                     = $parametros['servicio'];
            $objMovimiento->guardarMovimientoRCE($objCon, $parametros);   
            
            $subparametros['est_id']        		= 2;
            $subparametros['codigo']        		= $value[0];
            $subparametros['descripcion']       	= $value[1];
            $subparametros['pre_codigo_lis']        = "";
            $subparametros['tip_id_lis']            = "";
            $det_lab_id               = $objLaboratorio->insertarDetalleIndicacionLaboratorio($objCon, $subparametros);
            //INSERTAR EN BITACORA 29-07-2019
            $parametros['concat_laboratorio2']     .= $value[1] . ", ";

            $parDetalleInd['tipo']                  = 3;
            $parDetalleInd['tipo_descripcion']      = "EXAMENES DE LABORATORIO";
            $parDetalleInd['descripcion_detalle']   = $value[1];
            $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
            $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
            $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
            $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
            $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
            $parDetalleInd['dau_id']                = $parametros['dau_id'];
            $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);
            //INSERTAR EN BITACORA 29-07-2019
          }
          $subparametrosBitacoraIndicaciones                                = array();
          $subparametrosBitacoraIndicaciones['BITid']                      = $parametros['dau_id'];
          $subparametrosBitacoraIndicaciones['BITtipo_codigo']              = 5;
          $subparametrosBitacoraIndicaciones['BITtipo_descripcion']      = "Indicaciones (laboratorio)";
          $subparametrosBitacoraIndicaciones['BITusuario']                  = $parametros['dau_mov_usuario'];
          $subparametrosBitacoraIndicaciones['BITdescripcion']              = trim($parametros['concat_laboratorio2'], ', ');
          $subparametrosBitacoraIndicaciones['BITdescripcion']        = $parametros['concat_laboratorio1'] . "(" . $subparametrosBitacoraIndicaciones['BITdescripcion'] . ")";
          $objBitacora->guardarBitacora($objCon, $subparametrosBitacoraIndicaciones);
        }
      }
      if ( $parametros['id_solicitudTransfusion'] > 0) {
        $datosSolicitudTransfusion = $objDau->ListarSolicitudIndicaciones($objCon, $parametros);

        $descripcion_detalle  = "";
        $detalles             = [];
        foreach ($datosSolicitudTransfusion as $value) {
            $detalles[]       = $value['producto_descripcion'] . " " . $value['cantidad'] . " ".$value['unidad_medida'];
        }
        $descripcion_detalle  = implode(', ', $detalles);

        if( $datosSolicitudTransfusion[0]['caracter_transfusion_id'] == 4 ){

          $descripcion_detalle .= " - ".$datosSolicitudTransfusion[0]['caracter_transfusion']." : ".$datosSolicitudTransfusion[0]['caracter_transfusion_otro'];
        }else{
          $descripcion_detalle .= " - ".$datosSolicitudTransfusion[0]['caracter_transfusion'];
        }
        // $datosSolicitudTransfusion[0]['caracter_transfusion']
        // $datosSolicitudTransfusion[0]['caracter_transfusion_otro']

        $parametrosCabera['tipo']                   = 8;
        $id_cabecera_indicaciones                   = $CabeceraIndicacionRec;
        $subparametrosBitacora['BITdescripcion']    = "<b>TRANSFUSIONES :</b> ";
        // foreach ($parametros['arr_otro'] as $k => $value) {
        $subparametros['est_id']                    = 1;
        $subparametros['servicio']                  = 8;
        $subparametros['codigo']                    = "";
        $subparametros['ima_tipo']                  = "";
        $subparametros['descripcion']               = $descripcion_detalle;
        $subparametros['clasificacionTratamiento']  = 'NULL';
        $subparametros['id_cabecera_indicaciones']  = $id_cabecera_indicaciones;
        $subparametros['id_solicitudTransfusion']   = $parametros['id_solicitudTransfusion'];
        $parametros['sol_id']                       = $objRegistroClinico->insertarSolicitudIndicaciones($objCon, $subparametros);
        $parametros['dau_mov_descripcion']          = 'registro_solicitud_transfusiones';
        $parametros['solicitud_id']                 = $parametros['sol_id'];
        $parametros['indicacion_id']                = "null";
        $parametros['tipo']                         = $subparametros['servicio'];
        $subparametrosBitacora['BITdescripcion']    .= $descripcion_detalle . ". ";
        $objMovimiento->guardarMovimientoRCE($objCon, $parametros);


        $parDetalleInd['tipo']                  = 7;
        $parDetalleInd['tipo_descripcion']      = "SOLICITUD TRANSFUSIONES";
        $parDetalleInd['descripcion_detalle']   = $descripcion_detalle;
        $parDetalleInd['solicitud_id']          = $parametros['solicitud_id'];
        $parDetalleInd['usuario']               = $_SESSION['MM_Username'.SessionName];
        $parDetalleInd['fecha']                 = $horarioServidor[0]['fecha'];
        $parDetalleInd['hora']                  = $horarioServidor[0]['hora'];
        $parDetalleInd['id_cabecera']           = $CabeceraIndicacionRec;
        $parDetalleInd['dau_id']                = $parametros['dau_id'];
        $objDau->insertardetalle_indicaciones_rce($objCon, $parDetalleInd);



        // }
        $subparametrosBitacora['BITid']                 = $parametros['dau_id'];
        $subparametrosBitacora['BITtipo_codigo']        = 33;
        $subparametrosBitacora['BITtipo_descripcion']   = "Solicitud Transfusiones";
        $subparametrosBitacora['BITdatetime']           = "NOW()";
        $subparametrosBitacora['BITusuario']            = $parametros['dau_mov_usuario'];
        $objBitacora->guardarBitacora($objCon, $subparametrosBitacora);
      }


      $parametros['dau_indicacion_terminada'] = 0;

      $objDau->dau_indicacion($objCon, $parametros);

      $parametros['frm_numero_dau'] = $parametros['dau_id'];

      // $datos               = $objCategorizacion->listarPacientes_IND_ENF($objCon, $parametros);

      $parametros['regId']  = $parametros['rce_id'];

      $datosSol             = $objCategorizacion->listar_Solicitud_Total($objCon, $parametros);

      $parametros['dau_indicaciones_solicitadas'] = $datosSol[0]['total'];

      $parametros['dau_indicaciones_realizadas']  = $datosSol[0]['aplicada'];

      $objDau->dau_indicaciones_solicitadas_realizadas($objCon, $parametros);

      $objCon->commit();

      $response = array("status" => "success", "id" => $parametros['dau_id']);

      echo json_encode($response);
    } catch (PDOException $e) {

      $objCon->rollback();

      $response = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($response);
    }

  break;
  case 'EliminarPlantillaIndicaciones':
    $parametros                                   = $objUtil->getFormulario($_POST);
    try {
      $objCon->beginTransaction();
      
      $idPlantilla = $parametros['idPlantillaHidden']; 
      $objRce->eliminarPlantillaIndicaciones($objCon, $idPlantilla);
      // echo $idPlantilla;
      $respuesta = array("status" => "success", "idPlantilla" => $idPlantilla);
      
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
  break;
  case 'ActualizarPlantillaIndicaciones':
    $parametros                                   = $objUtil->getFormulario($_POST);
    $banderaError                                 = false;
    $parametros['arr_imagenologia']               = json_decode(stripslashes($parametros['carroIma']));
    $parametros['arr_tratamiento']                = json_decode(stripslashes($parametros['carroTra']));
    $parametros['arr_laboratorio2']               = json_decode(stripslashes($parametros['carroLab2']));
    $parametros['arr_otro']                       = json_decode(stripslashes($parametros['carroOtr']));
    $parametros['arr_tratamiento_nuevo']          = json_decode(stripslashes($parametros['carroTratamiento']));
    $parametrosNuevaPlantilla['nombrePlantilla']  = $parametros['nombrePlantilla'];
    $parametrosNuevaPlantilla['idMedico']         = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      
      $idPlantilla = $parametros['idPlantillaHidden']; 
      $objRce->eliminarPlantillaIndicaciones($objCon, $idPlantilla);

      $idPlantilla = $objRce->crearPlantillaIndicaciones($objCon, $parametrosNuevaPlantilla);
      if (!empty($parametros['arr_imagenologia'])) {
        $parametrosAntecedentes = array (
          "infeccionMultirresistente" => ($objUtil->existe($parametros["frm_multirresistente"])) ? "S" : "N",
          "embarazo" => ($objUtil->existe($parametros["frm_Embarazo"])) ? "S" : "N",
          "diabetes" => ($objUtil->existe($parametros["frm_Diabetes"])) ? "S" : "N",
          "asma" => ($objUtil->existe($parametros["frm_Asma"])) ? "S" : "N",
          "hipertension" => ($objUtil->existe($parametros["frm_Hipertension"])) ? "S" : "N",
          "otro" => ($objUtil->existe($parametros["frm_Otro"])) ? "S" : "N",
          "otroDescripcion" => $objUtil->asignar($parametros["frm_otros_text"]),
          "idPlantilla" => $idPlantilla
        );
        $objRce->crearPlantillaIndicacionesAntecedentesClinicos($objCon, $parametrosAntecedentes);
        foreach ($parametros['arr_imagenologia'] as $k => $value) {
          $valorExamen = $objUtil->asignar($value[0]);
          $valorTipoExamen = $objUtil->asignar($value[1]);
          $valorLateralidad = $objUtil->asignar($value[2]);
          $valorContrastes = $objUtil->asignar($value[3]);
          $valorObservacion = $objUtil->asignar($value[4]);
          $valorIdPrestacion  = $objUtil->asignar($value[5]);
          $valorPrestaciones  = $objUtil->asignar($value[6]);
          $valorParteCuerpo  = $objUtil->asignar($value[7]);
          $parametrosImagenologia = array(
            "nombreExamen" => $valorExamen,
            "tipoExamen" => $valorTipoExamen,
            "lateralidad" => $valorLateralidad,
            "observacionExamen" => $valorObservacion,
            "codigoExamen" => $valorIdPrestacion,
            "prestaciones" => $valorPrestaciones,
            "parteCuerpo" => $valorParteCuerpo,
            "contrastes" => $valorContrastes,
            "idPlantilla" => $idPlantilla
          );
          $objRce->crearPlantillaIndicacionesImagenologia($objCon, $parametrosImagenologia);
        }
      }
      if (!empty($parametros['arr_tratamiento_nuevo'])) {
        foreach ($parametros['arr_tratamiento_nuevo'] as $k => $value) {
          $parametrosTratamiento['detalleTratamiento']       = $value[0];
          $parametrosTratamiento['idClasificacionTratamiento']   = $value[1];
          $parametrosTratamiento['idPlantilla']             = $idPlantilla;
          $objRce->crearPlantillaIndicacionesTratamiento($objCon, $parametrosTratamiento);
        }
      }
      if (!empty($parametros['arr_laboratorio2'])) {
        foreach ($parametros['arr_laboratorio2'] as $k => $value) {
          $parametrosLaboratorio['idPrestacionLaboratorio']  = $value[0];
          $parametrosLaboratorio['idPlantilla']          = $idPlantilla;
          $objRce->crearPlantillaIndicacionesLaboratorio($objCon, $parametrosLaboratorio);
        }
      }
      if (!empty($parametros['arr_tratamiento'])) {
        foreach ($parametros['arr_tratamiento'] as $k => $value) {
          $parametrosProcedimiento['idProcedimiento']  = $value[0];
          $parametrosProcedimiento['idPlantilla']    = $idPlantilla;
          $objRce->crearPlantillaIndicacionesProcedimiento($objCon, $parametrosProcedimiento);
        }
      }
      if (!empty($parametros['arr_otro'])) {
        foreach ($parametros['arr_otro'] as $k => $value) {
          $parametrosOtros['detalleOtros']  = $value[0];
          $parametrosOtros['idPlantilla']    = $idPlantilla;
          $objRce->crearPlantillaIndicacionesOtros($objCon, $parametrosOtros);
        }
      }
      // echo $idPlantilla;
      $respuesta = array("status" => "success", "idPlantilla" => $idPlantilla);
      
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
    break;
  case 'crearPlantillaIndicaciones':
    $parametros                                   = $objUtil->getFormulario($_POST);
    $banderaError                                 = false;
    $parametros['arr_imagenologia']               = json_decode(stripslashes($parametros['carroIma']));
    $parametros['arr_tratamiento']                = json_decode(stripslashes($parametros['carroTra']));
    $parametros['arr_laboratorio2']               = json_decode(stripslashes($parametros['carroLab2']));
    $parametros['arr_otro']                       = json_decode(stripslashes($parametros['carroOtr']));
    $parametros['arr_tratamiento_nuevo']          = json_decode(stripslashes($parametros['carroTratamiento']));
    $parametrosNuevaPlantilla['nombrePlantilla']  = $parametros['nombrePlantilla'];
    $parametrosNuevaPlantilla['idMedico']         = $_SESSION['MM_Username'.SessionName];
    try {
      $objCon->beginTransaction();
      $respuestaConsulta                          = $objRce->obtenerNombrePlantillasIndicaciones($objCon, $parametrosNuevaPlantilla['idMedico']);
      $totalRespuestaConsulta                     = count($respuestaConsulta);
      for ($i = 0; $i < $totalRespuestaConsulta; $i++) {
        if ($respuestaConsulta[$i]['nombrePlantilla'] == $parametrosNuevaPlantilla['nombrePlantilla']) {
          $banderaError = true;
        }
      }
      if ($banderaError) {
        $respuesta = array("status" => "error", "message" => "El nombre de la plantilla ya está utilizado");
      } else {
        $idPlantilla = $objRce->crearPlantillaIndicaciones($objCon, $parametrosNuevaPlantilla);
        if (!empty($parametros['arr_imagenologia'])) {
          $parametrosAntecedentes = array (
            "infeccionMultirresistente" => ($objUtil->existe($parametros["frm_multirresistente"])) ? "S" : "N",
            "embarazo" => ($objUtil->existe($parametros["frm_Embarazo"])) ? "S" : "N",
            "diabetes" => ($objUtil->existe($parametros["frm_Diabetes"])) ? "S" : "N",
            "asma" => ($objUtil->existe($parametros["frm_Asma"])) ? "S" : "N",
            "hipertension" => ($objUtil->existe($parametros["frm_Hipertension"])) ? "S" : "N",
            "otro" => ($objUtil->existe($parametros["frm_Otro"])) ? "S" : "N",
            "otroDescripcion" => $objUtil->asignar($parametros["frm_otros_text"]),
            "idPlantilla" => $idPlantilla
          );
          $objRce->crearPlantillaIndicacionesAntecedentesClinicos($objCon, $parametrosAntecedentes);
          foreach ($parametros['arr_imagenologia'] as $k => $value) {
            $valorExamen = $objUtil->asignar($value[0]);
            $valorTipoExamen = $objUtil->asignar($value[1]);
            $valorLateralidad = $objUtil->asignar($value[2]);
            $valorContrastes = $objUtil->asignar($value[3]);
            $valorObservacion = $objUtil->asignar($value[4]);
            $valorIdPrestacion  = $objUtil->asignar($value[5]);
            $valorPrestaciones  = $objUtil->asignar($value[6]);
            $valorParteCuerpo  = $objUtil->asignar($value[7]);
            $parametrosImagenologia = array(
              "nombreExamen" => $valorExamen,
              "tipoExamen" => $valorTipoExamen,
              "lateralidad" => $valorLateralidad,
              "observacionExamen" => $valorObservacion,
              "codigoExamen" => $valorIdPrestacion,
              "prestaciones" => $valorPrestaciones,
              "parteCuerpo" => $valorParteCuerpo,
              "contrastes" => $valorContrastes,
              "idPlantilla" => $idPlantilla
            );
            $objRce->crearPlantillaIndicacionesImagenologia($objCon, $parametrosImagenologia);
          }
        }
        if (!empty($parametros['arr_tratamiento_nuevo'])) {
          foreach ($parametros['arr_tratamiento_nuevo'] as $k => $value) {
            $parametrosTratamiento['detalleTratamiento']       = $value[0];
            $parametrosTratamiento['idClasificacionTratamiento']   = $value[1];
            $parametrosTratamiento['idPlantilla']             = $idPlantilla;
            $objRce->crearPlantillaIndicacionesTratamiento($objCon, $parametrosTratamiento);
          }
        }
        if (!empty($parametros['arr_laboratorio2'])) {
          foreach ($parametros['arr_laboratorio2'] as $k => $value) {
            $parametrosLaboratorio['idPrestacionLaboratorio']  = $value[0];
            $parametrosLaboratorio['idPlantilla']          = $idPlantilla;
            $objRce->crearPlantillaIndicacionesLaboratorio($objCon, $parametrosLaboratorio);
          }
        }
        if (!empty($parametros['arr_tratamiento'])) {
          foreach ($parametros['arr_tratamiento'] as $k => $value) {
            $parametrosProcedimiento['idProcedimiento']  = $value[0];
            $parametrosProcedimiento['idPlantilla']    = $idPlantilla;
            $objRce->crearPlantillaIndicacionesProcedimiento($objCon, $parametrosProcedimiento);
          }
        }
        if (!empty($parametros['arr_otro'])) {
          foreach ($parametros['arr_otro'] as $k => $value) {
            $parametrosOtros['detalleOtros']  = $value[0];
            $parametrosOtros['idPlantilla']    = $idPlantilla;
            $objRce->crearPlantillaIndicacionesOtros($objCon, $parametrosOtros);
          }
        }
        // echo $idPlantilla;
        $respuesta = array("status" => "success", "idPlantilla" => $idPlantilla);
      }
      $objCon->commit();
      echo json_encode($respuesta);
    } catch (PDOException $e) {
      $objCon->rollback();
      $respuesta = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($respuesta);
    }
    break;

  case 'pacienteComplejo2':
    $parametros = $objUtil->getFormulario($_POST);
    try {
      $objCon->beginTransaction();
      $parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
      $objDau->dau_PacienteComplejo2($objCon, $parametros);
      $objCon->commit();
      $response = array("status" => "success");
      echo json_encode($response);
    } catch (PDOException $e) {
      $objCon->rollback();
      $response = array("status" => "error", "message" => $e->getMessage());
      echo json_encode($response);
    }
  break;
  







 



 

  



  



  case 'cargarParametros':

    require_once("../../../../class/Imagenologia.class.php");
    $objImagenologia = new Imagenologia;

    $objCon->db_connect();

    try {

      $objCon->beginTransaction();

      switch ($_POST['dau_paciente_complejo']) {
        case 'S':
          $rsExamenes = $objImagenologia->getExamenes2($objCon, $_POST['tipoValor']);
          break;

        default:
          $rsExamenes = $objImagenologia->getExamenes3($objCon, $_POST['tipoValor']);
          break;
      }

      for ($u = 0; $u < count($rsExamenes); $u++) {
        $html .= "<option value='" . $rsExamenes[$u]['preCod'] . "-" . $rsExamenes[$u]['prePacienteUrgencia'] . "'>" . $rsExamenes[$u]['preNombre'] . "</option>";
      }

      $select .= "<option value='0' disabled selected>Seleccione...</option>";

      $select .= $html;

      echo json_encode($select);
    } catch (PDOException $e) {

      $objCon->rollback();

      $response = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($response);
    }

    break;



  case 'busquedaSensitivaTratamientos':

    require_once("../../../../class/RegistroClinico.class.php");
    $objRegistroClinico = new RegistroClinico;

    try {

      $objCon->db_connect();

      echo $objRegistroClinico->sensitivaTratamientos($objCon, $_POST['term']);
    } catch (PDOException $e) {

      $e->getMessage();
    }

    break;



  case 'vaciarSelect':

    $select .= "<select id='comboTipoExamenes' name='comboTipoExamenes' class='selectpicker' data-live-search='true'><option value='0'>Seleccione ...</option></select>";

    $select .= $html;

    echo json_encode($select);

    break;



  case 'pacienteComplejo':

    require_once("../../../../class/Dau.class.php");
    $objDau = new Dau;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    try {

      $objCon->beginTransaction();

      $parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];

      $objDau->dau_PacienteComplejo($objCon, $parametros);

      $objCon->commit();

      $response = array("status" => "success");

      echo json_encode($response);
    } catch (PDOException $e) {

      $objCon->rollback();

      $response = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($response);
    }

    break;







  case 'DestruirSesionSecciones':
    unset($_SESSION['indicaciones']['imagenologia']);
    unset($_SESSION['indicaciones']['laboratorio']);
    unset($_SESSION['indicaciones']['procedimiento']);
    unset($_SESSION['indicaciones']['tratamiento']);
    unset($_SESSION['indicaciones']['otros']);
    unset($_SESSION['indicaciones']['imagenologia']['parametros']);
    unset($_SESSION['indicaciones']['imageDatos']);
    unset($_SESSION['indicaciones']['post']['cargarIndicacionesModal']);
  break;



  case 'tipoPaciente':

    require_once("../../../../class/Dau.class.php");
    $objDau = new Dau;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    $datos      = $objDau->ListarPacientesDau($objCon, $parametros);

    $response = array("complejo" => $datos[0]['dau_paciente_complejo']);

    echo json_encode($response);

    break;



  


  



  



  case 'crearPlantillaInicioAtencion':

    require_once("../../../../class/Rce.class.php");
    $objRce = new Rce;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    $banderaError = false;

    $parametros['idMedico'] = $_SESSION['MM_Username'.SessionName];

    try {

      $objCon->beginTransaction();

      $respuestaConsulta = $objRce->obtenerNombrePlantillasInicioAtencion($objCon, $parametros['idMedico']);

      $totalRespuestaConsulta = count($respuestaConsulta);

      for ($i = 0; $i < $totalRespuestaConsulta; $i++) {

        if ($respuestaConsulta[$i]['nombrePlantilla'] == $parametros['nombrePlantilla']) {

          $banderaError = true;
        }
      }

      if ($banderaError) {

        $respuesta = array("status" => "error", "message" => "El nombre de la plantilla ya está utilizado");
      } else {

        $resultadoConsulta = $objRce->crearPlantillaInicioAtencion($objCon, $parametros);

        $respuesta = array("status" => "success", "idPlantilla" => $resultadoConsulta);
      }

      $objCon->commit();

      echo json_encode($respuesta);
    } catch (PDOException $e) {

      $objCon->rollback();

      $respuesta = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($respuesta);
    }

    break;



  case 'obtenerPlantillaInicioAtencion':

    require_once("../../../../class/Rce.class.php");
    $objRce = new Rce;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    try {

      $objCon->beginTransaction();

      $respuestaConsulta = $objRce->obtenerPlantillasInicioAtencion($objCon, $parametros['idPlantilla']);

      $objCon->commit();

      $respuesta = array("status" => "success", "motivoConsulta" => $respuestaConsulta['motivoConsulta'], "hipotesisDiagnosticaInicial" => $respuestaConsulta['hipotesisDiagnosticaInicial']);

      echo json_encode($respuesta);
    } catch (PDOException $e) {

      $objCon->rollback();

      $respuesta = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($respuesta);
    }

    break;



 



  case 'obtenerPlantillaAltaUrgencia':

    require_once("../../../../class/Rce.class.php");
    $objRce = new Rce;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    try {

      $objCon->beginTransaction();

      $respuestaConsulta = $objRce->obtenerPlantillasAltaUrgencia($objCon, $parametros['idPlantilla']);

      $objCon->commit();

      $respuesta = array("status" => "success", "descripcionCie10" => $respuestaConsulta['descripcionCie10'], "idCie10" => $respuestaConsulta['idCie10'], "cie10Abierto" => $respuestaConsulta['cie10Abierto'], "indicaciones" => $respuestaConsulta['indicaciones'], "idPronostico" => $respuestaConsulta['idPronostico'], "idIndicacionEgreso" => $respuestaConsulta['idIndicacionEgreso']);

      echo json_encode($respuesta);
    } catch (PDOException $e) {

      $objCon->rollback();

      $respuesta = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($respuesta);
    }

    break;



  



  case 'obtenerPlantillaIndicaciones':

    require_once("../../../../class/Rce.class.php");
    $objRce = new Rce;

    $objCon->db_connect();

    $parametros = $objUtil->getFormulario($_POST);

    try {

      $objCon->beginTransaction();

      $respuestaConsultaImagenologia = $objRce->obtenerPlantillasIndicacionesImagenologia($objCon, $parametros['idPlantilla']);

      $respuestaConsultaAntecedentesClinicos = $objRce->obtenerPlantillasIndicacionesAntecedentesClinicos($objCon, $parametros['idPlantilla']);

      $respuestaConsultaTratamiento = $objRce->obtenerPlantillasIndicacionesTratamiento($objCon, $parametros['idPlantilla']);

      $respuestaConsultaLaboratorio = $objRce->obtenerPlantillasIndicacionesLaboratorio($objCon, $parametros['idPlantilla']);

      $respuestaConsultaProcedimiento = $objRce->obtenerPlantillasIndicacionesProcedimiento($objCon, $parametros['idPlantilla']);

      $respuestaConsultaOtros = $objRce->obtenerPlantillasIndicacionesOtros($objCon, $parametros['idPlantilla']);

      $objCon->commit();

      $respuesta = array("status" => "success", "datosRespuestaConsultaImagenologia" => $respuestaConsultaImagenologia, "datosRespuestaConsultaAntecedentesClinicos" => $respuestaConsultaAntecedentesClinicos, "datosRespuestaConsultaTratamiento" => $respuestaConsultaTratamiento, "datosRespuestaConsultaLaboratorio" => $respuestaConsultaLaboratorio, "datosRespuestaConsultaProcedimiento" => $respuestaConsultaProcedimiento, "datosRespuestaConsultaOtros" => $respuestaConsultaOtros);

      echo json_encode($respuesta);
    } catch (PDOException $e) {

      $objCon->rollback();

      $respuesta = array("status" => "error", "message" => $e->getMessage());

      echo json_encode($respuesta);
    }

    break;






    //CASES integración ingra
  case "obtenerTiposExamenesIntegracionIngrad":
    require_once("../../../../class/Imagenologia.class.php");

    $parametros = $objUtil->getFormulario($_POST);

    $objCon->db_connect();
    $objImagenologia = new Imagenologia;
    $tiposExamenes = $objImagenologia->obtenerTiposExamenesIntegracionIngrad($objCon, $parametros);

    echo json_encode($tiposExamenes);

    break;



  case "obtenerPartesCuerpoIntegracionIngrad":
    require_once("../../../../class/Imagenologia.class.php");

    $parametros = $objUtil->getFormulario($_POST);

    $paramestrosAEnviar = array();
    $parametrosAEnviar["clasificacion"] = $parametros["tipoExamen"];
    $parametrosAEnviar["ID"] = $parametros["idPrestacion"];

    $objCon->db_connect();
    $objImagenologia = new Imagenologia;
    $partesCuerpo = $objImagenologia->obtenerPartesCuerpoIntegracionIngrad($objCon, $parametrosAEnviar);

    unset($paramestrosAEnviar);

    echo json_encode($partesCuerpo);

    break;



  case "obtenerPrestacionesIntegracionIngrad":
    require_once("../../../../class/Imagenologia.class.php");

    $parametros = $objUtil->getFormulario($_POST);

    $paramestrosAEnviar = array();
    $parametrosAEnviar["clasificacion"] = $parametros["tipoExamen"];
    $parametrosAEnviar["PARTE_CUERPO"] = $parametros["parteCuerpo"];
    $parametrosAEnviar["pacienteComplejo"] = $parametros["pacienteComplejo"];

    $objCon->db_connect();
    $objImagenologia = new Imagenologia;
    $prestaciones = $objImagenologia->obtenerPrestacionesIntegracionIngrad($objCon, $parametrosAEnviar);

    unset($paramestrosAEnviar);

    echo json_encode($prestaciones);

    break;



  case "obtenerExamenesIntegracionDALCA":
    require_once("../../../../class/Imagenologia.class.php");
    $objCon->db_connect();
    $objImagenologia = new Imagenologia;
    $examenes = $objImagenologia->obtenerExamenesIntegracionDALCA($objCon);
    echo json_encode($examenes);
    break;



  case "obtenerTiposExamenesIntegracionDALCA":
    require_once("../../../../class/Imagenologia.class.php");
    $objCon->db_connect();
    $objImagenologia = new Imagenologia;
    $tiposExamenes = $objImagenologia->obtenerTiposExamenesIntegracionDALCA($objCon);
    echo json_encode($tiposExamenes);
    break;



  case "obtenerInformeSolicitudDalca":
    $parametros = $objUtil->getFormulario($_POST);
    $idSolicitudDalca = $parametros["idSolicitudDalca"];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://10.6.21.29/apiHJNCDalca/consultarInforme/' . $idSolicitudDalca);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $headers = array();
    $headers[] = 'Accept: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
      exit;
    }

    curl_close($ch);
    $response = json_decode($result, true);
    $urlInformeDalca = ($httpStatusCode === 200) ? $response : "";
    echo json_encode($urlInformeDalca);

    break;



    case "obtenerImagenSolicitudDalca":
      $parametros = $objUtil->getFormulario($_POST);
      $idSolicitudDalca = $parametros["idSolicitudDalca"];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'http://10.6.21.29/apiHJNCDalca/consultarImagen/' . $idSolicitudDalca);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
      $headers = array();
      $headers[] = 'Accept: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);

      $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
        exit;
      }

      curl_close($ch);
      $response = json_decode($result, true);
      $urlInformeDalca = ($httpStatusCode === 200) ? $response : "";
      echo json_encode($urlInformeDalca);

      break;

      case "get_info_examen":
        require_once("../../../../class/Imagenologia.class.php");
        $objCon->db_connect();
        $objImagenologia = new Imagenologia;
        $parametros['id_prestaciones'] = $_POST['valor'];
        $RS_ = $objImagenologia->SELECT_TiposExamenes($objCon,$parametros);
        $response = array("tipo_examen" => $RS_[0]['tipo_examen']);
        echo json_encode($response);
      break;

      
}

$objCon = null;
