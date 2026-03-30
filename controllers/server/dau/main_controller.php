<?php
session_start();
error_reporting(0);
require_once('../../../class/Connection.class.php'); 		$objCon      		= new Connection;  $objCon->db_connect();
require_once("../../../class/Util.class.php");       		$objUtil     		= new Util;
require_once("../../../class/Rce.class.php"); 				$objRce  			= new Rce();
require_once("../../../class/Movimiento.class.php");   		$objMovimiento  	= new Movimiento;
require_once('../../../class/Bitacora.class.php');  		$objBitacora    	= new Bitacora;
require_once('../../../class/Dau.class.php');  				$objDau    			= new Dau;
require_once('../../../class/MapaPiso.class.php');  		$objMapaPiso    	= new MapaPiso;
require_once('../../../class/RegistroClinico.class.php');  	$objRegistroClinico = new RegistroClinico;
require_once("../../../class/CMBD.class.php");       		$objCMBD     		= new CMBD;
require_once('../../../class/SqlDinamico.class.php');  		$objSqlDinamico 	= new SqlDinamico;
require_once("../../../class/SolicitudHospitalizacion.class.php");   $objSolHosp 		= new SolicitudHospitalizacion;
require_once("../../../class/Agenda.class.php");   			$objAgenda 			= new Agenda;
require_once("../../../class/Paciente.class.php");          $objPaciente        = new Paciente;
require_once("../../../class/AltaUrgencia.class.php");      $objAltaUrgencia    = new AltaUrgencia;
require_once("../../../class/Evolucion.class.php");      	$objEvolucion    	= new Evolucion;
require_once("../../../class/Admision.class.php"); 			$objAdmision 		= new Admision;
// require_once("../../../class/Agenda.php");           $objAgenda   = new Agenda;

switch ( $_POST['accion'] ) {

	case 'tiempoIndicacionEgreso':
		$parametros =  $objUtil->getFormulario($_POST);
		try {
			$objCon->beginTransaction();
			$respuestaConsulta = $objDau->tiempoIndicacionEgreso($objCon, $parametros['idDau']);
			$respuesta = array("status" => "error");
			if ( ! empty($respuestaConsulta['dau_indicacion_egreso_fecha']) && ! is_null($respuestaConsulta['dau_indicacion_egreso_fecha']) && $respuestaConsulta['dau_indicacion_egreso'] == 4) {
				$intervaloTiempo = strtotime(date("Y-m-d H:i:s")) - strtotime($respuestaConsulta['dau_indicacion_egreso_fecha']);
				$respuesta = array("status" => "success", "intervaloTiempo" => $intervaloTiempo);
			}
			echo json_encode($respuesta);
		} catch ( PDOException $e ) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case "registrarInicioAtencion":
		$parametros 									= $objUtil->getFormulario($_POST);
		$parametros['atencion'] 						= $_SESSION['MM_Username'.SessionName];
		$parametros["dau_viaje_epidemiologico"] 		= $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
		$parametros["dau_pais_epidemiologia"] 			= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
		$parametros["dau_observacion_epidemiologica"] 	= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;
		$parametros['frm_rce_motivoConsulta'] 			= $parametros['frm_rce_motivoConsultaSIA'];
		$parametros['frm_rce_hipotesisInicial'] 		= $parametros['frm_rce_hipotesisInicialSIA'];
		$parametros['frm_paciente_id'] 					= $parametros['frm_paciente_idSIA'];
		$parametros['frm_rce_id'] 						= $parametros['frm_rce_idSIA'];
		$parametros['frm_mot_con'] 						= $parametros['frm_mot_conSIA'];
		$parametros['frm_auge'] 						= $parametros['frm_augeSIA'];
		try{
			$objCon->beginTransaction();
			//DAU
			
			$ActualizarInicioAtencion 				= $objDau->ActualizarInicioAtencion($objCon,$parametros);
			$parametros['dau_mov_descripcion'] 		= 'inciar atencion';
			$parametros['dau_mov_tipo'] 			= "inc";
			$parametros['dau_mov_usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$objMovimiento->guardarMovimiento($objCon, $parametros);

			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_viaje_epidemiologico']))
				? " - ".$parametros['dau_viaje_epidemiologico']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_pais_epidemiologia']))
				? " - ".$parametros['dau_pais_epidemiologia']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_observacion_epidemiologica']))
				? " - ".$parametros['dau_observacion_epidemiologica']
				: NULL;
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			//RCE

			$parametros['frm_rcedetalle_rbalc'] 	= $parametros['frm_rcedetalle_rbalcSIA']; 
			$parametros['frm_rce_est_eti'] 			= $parametros['frm_rce_est_etiSIA']; 
			$parametros['frm_rce_alc_fech'] 		= $parametros['frm_rce_alc_fechSIA']; 
			$parametros['frm_rce_n_frasco'] 		= $parametros['frm_rce_n_frascoSIA']; 

			$parametros['dau_id'] 					= $parametros['dau_id'];
			$parametros['id_dau']					= $parametros['dau_id'];
			$parametros['frm_dau_id'] 				= $parametros['dau_id'];
			$parametros['usuario'] 					= $_SESSION['MM_Username'.SessionName];
			$resp 									= $objRegistroClinico->consultaRCE($objCon,$parametros);
			$parametros['rce_id'] 					= $resp[0]['regId'];
			$parametros['dau_mov_usuario'] 			= $_SESSION['MM_Username'.SessionName];
			if($parametros['frm_rce_alc_fech'] != ""){
				$parametros['frm_rce_alc_fech'] 		= date("Y-m-d H:i:s",strtotime($parametros['frm_rce_alc_fech']));
			}else{
				$parametros['frm_rce_alc_fech'] = "";
			}
			$parametros['rut'] 						= $_SESSION['usuarioActivo']['rut'];
			if ( $parametros['frm_rcedetalle_rbalc'] == 'No' ) {
				$parametros['frm_rce_est_eti'] 		= "";
				$parametros['frm_rce_alc_fech'] 	= "";
				$parametros['frm_rce_n_frasco'] 	= "";
			}
			$respuesta  							= $objRegistroClinico->actualizaRCE($objCon,$parametros);
			// print('<pre>'); print_r($resp); print('</pre>');
			$parametros['dau_mov_descripcion'] 		= 'modificar Rce';
			$parametros['dau_mov_tipo']				= 'mrc';
			$objMovimiento->guardarMovimiento($objCon,$parametros);

			if ( $parametros['chk'] == 1 ) {
				$respuesta2 						= $objRegistroClinico->actualizaAlcoh($objCon,$parametros);
				$parametros['dau_mov_descripcion']	= 'modificar alcoholemia';
				$objMovimiento->guardarMovimiento($objCon,$parametros);
			}
			$parametrosSIA['SIAidRCE'] 				= $parametros['rce_id'];
			$parametrosSIA['SIAidPaciente'] 		= $parametros['frm_paciente_id'];
			$parametrosSIA['SIAusuario']			= $_SESSION['MM_Username'.SessionName];

			$idSIA = $objDau->obtenerDatosSolicitudInicioAtencion($objCon, $parametrosSIA['SIAidRCE']);
			if ( ! is_null($idSIA[0]['SIAid']) || !empty($idSIA[0]['SIAid']) ) {
				$parametrosSIA['SIAid'] = $idSIA[0]['SIAid'];
				$objDau->actualizarSolicitudInicioAtencion($objCon, $parametrosSIA);

			} else {
				$subparametrosBitacora['BITid'] 				= $parametros['frm_dau_id'];
				$subparametrosBitacora['BITtipo_codigo'] 		= 2;
				$subparametrosBitacora['BITtipo_descripcion'] 	= "Inicio atención";
				$subparametrosBitacora['BITdatetime'] 			= "NOW()";
				$subparametrosBitacora['BITusuario'] 			= $parametrosSIA['SIAusuario'];
				$subparametrosBitacora['BITdescripcion'] 		.= " <b>MOTIVO DE CONSULTA</b> (".$parametros['frm_rce_motivoConsulta'].") <br> <b>HIPOTESIS DIAGNOSTICA</b> (".$parametros['frm_rce_hipotesisInicial'].") ";
				$objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
				$objDau->ingresarSolicitudInicioAtencion($objCon, $parametrosSIA);
			}
			$resultadoCama 	= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
			$nombreSala 	= $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];


			$rsHorarioServidor              	= $objUtil->getHorarioServidor($objCon);
	        $parametrosSEVO['SEVOfecha'] 		= $rsHorarioServidor[0]['fecha']." ".$rsHorarioServidor[0]['hora'];
	        $parametrosSEVO['SEVOidRCE'] 		= $parametros['frm_rce_id'];
	        $parametrosSEVO['SEVOidPaciente'] 	= $parametros['frm_paciente_id'];
	        $parametrosSEVO['SEVOevolucion'] 	= $parametros['frm_rce_motivoConsultaSIA'];
	        $parametrosSEVO['SEVOusuario'] 		= $_SESSION['MM_Username'.SessionName];
			// $idSEVO = $objEvolucion->ingresarSolicitudEvolucion($objCon, $parametrosSEVO);
            ////// AQUI PASO COCHITO //////
            $subparametrosBitacora['BITid']                =   $parametros['frm_dau_id'];
            $subparametrosBitacora['BITtipo_codigo']        =   8;
            $subparametrosBitacora['BITtipo_descripcion']   =   "Evolución";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSEVO['SEVOusuario'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>EVOLUCION:</b> <br> ".$parametros['frm_rce_motivoConsultaSIA']." ";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO //////


			//CMBD INICIO ATENCIÓN
			// $objCon->setDB("dau");
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 3);
			$objCon->commit();
			$response = array( "status" => "success", "id" => $parametros['dau_id'], "nombreSala" => $nombreSala );
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);

		}

	break;

	case "registrarIndicacionAplica":
		$parametros 							= $objUtil->getFormulario($_POST);
		$horarioServidor 						= $objUtil->getHorarioServidor($objCon);
		$parametros['estado_ind']               = 21;
		$parametros['estado_dau']               = 5;
		$parametros['id_dau']                   = 'null';
		$parametros['estadoCama']               = 10;
		$parametros['fecha_indicacion_aplica']  = date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);
		$parametros['dau_cierre_fecha_final']   = date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);
		$parametros['estado_evento']			= 3;
		$parametros['estado_rce']				= 3;
		$parametros['dau_mov_usuario']			= $_SESSION['MM_Username'.SessionName];
		$parametros['dau_mov_descripcion']		= 'cierre evento rce';
		$parametros['dau_mov_tipo']             = NULL;
		$parametros['cod_establecimiento']		= 101100;
		$parametros['establecimiento']			= 'HOSPITAL REGIONAL DE ARICA DR. JUAN NOÉ CREVANI';
		$parametros['prioridad']				= 5;
		$parametros['estado_inicial']			= 1;
		$parametros['otro_motivo']				= 5;
		$parametros['fecha_inicio']				= date($horarioServidor[0]['fecha']);
		$parametros['hora_inicio']				= date($horarioServidor[0]['hora']);
		$parametros['procedencia']				= 2;
		$parametros['profesional']				= $_SESSION['MM_Username'.SessionName];
		$parametros['rut_profesional']			= $_SESSION['MM_RUNUSU'.SessionName];
		$parametros['tipo_interconsulta']		= 'I';
		$parametros['frm_id_paciente']			= $parametros['paciente_id'];
		$parametros['ague_interconsulta']		= "N";
		if ( $parametros['frm_necesita_control'] == 1 ) {
			$parametros['frm_necesita_control'] = 'NECESITA CONTROL';
		}
		if ( $parametros['frm_destino_defuncion'] == 1 ) {
			$parametros['frm_destino_defuncion'] = 'ANATO.PATOLOGICA';
		} else if ( $parametros['frm_destino_defuncion'] == 2 ) {
			$parametros['frm_destino_defuncion'] = 'SERV.MED.LEGAL';
		} else {
			$parametros['frm_destino_defuncion'] = '';
		}
		$parametros['indAplica'] 				= $_SESSION['MM_Username'.SessionName];
		$resPaciente							= $objDau->obtenerPaciente($objCon,$parametros);
		$resBusqueda							= $objDau->getDatosEgreso($objCon,$parametros);
		$parametros['rut_paciente']				= $resPaciente[0]['rut'];
		$parametros['fecha_nacimiento']			= $resPaciente[0]['fechanac'];
		$parametros['nombre_completo']			= $resPaciente[0]['nombres']." ".$resPaciente[0]['apellidopat']." ".$resPaciente[0]['apellidomat'];
		$parametros['especialidad']				= $resBusqueda[0]['sub_egr_esp'];
		$parametros['nombreCie10']      		= '';
		$parametros['hipo_final']				= '';
		$parametros['frm_fecha_modificar'] 		= date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);
		$parametros['destino_dau'] 				= $resBusqueda[0]['des_id'];
		$parametros['derivacion_dau'] 			= $resBusqueda[0]['alt_der_id'];
		$parametros['derivacion_especialista'] 	= $resBusqueda[0]['dau_ind_especialidad'];
		$parametros['derivacion_aps'] 			= $resBusqueda[0]['dau_ind_aps'];
		$parametros['derivacion_otros'] 		= $resBusqueda[0]['dau_ind_otros'];
		try{
			$objCon->beginTransaction();
			$resp_estado 						= $objDau->datosDau($objCon,$parametros);
			$parametros['codigoCIE10'] 			= $resp_estado[0]['dau_cierre_cie10'];
			if ( $resp_estado[0]['est_id'] == 5 ) {
				$responseRetorno = array("status" => "warning", "id" => $parametros['dau_id'], "message" => 'Al paciente ya se le Aplico la Indicación de Egreso');
			} else {
				$parametros['id_dau'] 			= $parametros['dau_id'];
				$resultadoCama 					= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
				$nombreSala 					= $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];
				$numeroCama 					= $resultadoCama[0]['cam_id'];
				$ActualizarIndicacionAplica    	= $objDau->ActualizarIndicacionAplica($objCon,$parametros);
				$ActualizarIndicacionAplicaDau 	= $objDau->ActualizarIndicacionAplicaDau($objCon,$parametros);
				$objRce->cambiarEstadoAplicarEgresoSolicitudSIC($objCon, $parametros['dau_id']);
				ingresarSolicitudSICPrimordial($objCon,$objAgenda, $objRce, $parametros['dau_id'] , $objUtil);
				if ( $resBusqueda[0]['ind_egr_id'] != 8 && $resBusqueda[0]['ind_egr_id'] != 9 && $resBusqueda[0]['ind_egr_id'] != 10 ) {
					$respuestaQueryDatos 		= $objDau->actualizarDau($objCon,$parametros);
				}
				$message						= '';
				$typeMessage					= '';
				$paciente_SolHosp_Cama 			= false;
				$serv_estaCama;
				$sala_estaCama;
				$cama_estaCama;
				$idpaciente_estaCamasn;
				$idpaciente_estaTran;
				$resp_ind_egr = $objDau->getIndicacionEgreso($objCon, $parametros);
				if ($resp_ind_egr[0]['ind_egr_id'] == '4') {
					$parametros['id_paciente']                        = $resp_ind_egr[0]['id_paciente'];
					$parametros['ind_id']                             = $resp_ind_egr[0]['ind_id'];
					$parametros['dau_id']                             = $resp_ind_egr[0]['dau_id'];
					$parametros['est_id']                             = $resp_ind_egr[0]['est_id'];
					$parametros['ind_egr_id']                         = $resp_ind_egr[0]['ind_egr_id'];
					$parametros['dau_ind_servicio']                   = $resp_ind_egr[0]['dau_ind_servicio'];
					$parametros['dau_pre_diagnostico']                = $resp_ind_egr[0]['dau_pre_diagnostico'];
					$parametros['idctacte']                           = $resp_ind_egr[0]['idctacte'];
					$parametros['rut']                                = $resp_ind_egr[0]['rut'];
					$parametros['nombreFull']                         = $resp_ind_egr[0]['nombres']." ".$resp_ind_egr[0]['apellidopat']." ".$resp_ind_egr[0]['apellidomat'];
					$parametros['nroficha']                           = $resp_ind_egr[0]['nroficha'];
					$parametros['id_rau']                          	  = $resp_ind_egr[0]['id_rau'];
					$parametros['servicio']                           = $resp_ind_egr[0]['servicio'];
					$parametros['fechaHoraActual_Hospitalizacion'] 	  = $resp_ind_egr[0]['FechaHoraActual'];
					$parametros['FechaActual']                        = $resp_ind_egr[0]['FechaActual'];
					$parametros['HoraActual']                         = $resp_ind_egr[0]['HoraActual'];
					$resp_pac_hops = $objSolHosp->pacienteSeEncuentraHospitalizado($objCon, $parametros);

					if ( empty($resp_pac_hops) || is_null($resp_pac_hops) ) {
						$resp_pac_hops_sn = $objSolHosp->pacienteSeEncuentraHospitalizadoSN($objCon, $parametros);
						if ( empty($resp_pac_hops_sn) || is_null($resp_pac_hops_sn) ) {
							$resp_pac_transpac = $objSolHosp->pacienteSeEncuentraTransitoPac($objCon, $parametros);
							if ( empty($resp_pac_transpac) || is_null($resp_pac_transpac) ) {
								$paciente_SolHosp_Cama = false;
							} else {
								$idpaciente_estaTran 	= $resp_pac_transpac[0]['id_paciente'];
								$paciente_SolHosp_Cama 	= true;
								$message 				= "El paciente se encuentra en Transito Paciente.";
								$typeMessage			= "info";
							}
						} else {
							$idpaciente_estaCamasn 	= $resp_pac_hops_sn[0]['idPacienteSN'];
							$paciente_SolHosp_Cama 	= true;
							$message 				= "El paciente se encuentra Hospitalizado en una Cama Super Numeraria.";
							$typeMessage			= "info";
						}
					} else {
						$serv_estaCama 			= $resp_pac_hops[0]['servicio'];
						$sala_estaCama 			= $resp_pac_hops[0]['sala'];
						$cama_estaCama 			= $resp_pac_hops[0]['cama'];
						$paciente_SolHosp_Cama 	= true;
						$message 				= "El paciente se encuentra Hospitalizado en: <br>- Servicio: <strong>".$serv_estaCama."</strong><br>- Sala: <strong>".$sala_estaCama."</strong><br>- Cama: <strong>".$cama_estaCama."</strong>";
						$typeMessage			=	"info";
					}
					if ( $paciente_SolHosp_Cama == false ) {
						$servicioSolicitado = 7;
						if ( ! empty($parametros['frm_postIndicacionEgreso']) && ! is_null($parametros['frm_postIndicacionEgreso']) && $parametros['frm_postIndicacionEgreso'] != $servicioSolicitado ) {
							$message 		= "Aplicación de Egreso según Tipo Egreso Médico.";
							$typeMessage 	= "info";
							ingresarPostIndicacionEgreso($objCon, $objDau, $objUtil, $parametros);
							$objSolHosp->insertPacienteTransitoPac($objCon, $parametros);
						} else if ( empty($parametros['frm_postIndicacionEgreso']) || is_null($parametros['frm_postIndicacionEgreso']) || $parametros['frm_postIndicacionEgreso'] == $servicioSolicitado ) {
							$objSolHosp->insertPacienteTransitoPac($objCon, $parametros);
							$message 		= "El paciente se ha enviado a Tránsito Paciente.";
							$typeMessage	= "info";
						}
					}
				}
				$busqueda 			 = $objDau->getIndicacionEgreso($objCon, $parametros);
				$busquedaPacienteDau = $objDau->ListarPacientesDau($objCon,$parametros);

				if ( $busqueda[0]['ind_egr_id'] == 6 ) {

					$parametros['frm_fallecimiento_fecha'] = $busquedaPacienteDau[0]['dau_defuncion_fecha'];
					$parametros['frm_id_paciente']	 	   = $busquedaPacienteDau[0]['id_paciente'];
					$parametros['reg_usuario_insercion']   = $_SESSION['MM_Username'.SessionName];
					$parametros['frm_reporta']		 	   = "Urgencia";
					$parametros['frm_notificacion']	 	   = $horarioServidor[0]['fecha'];
					$parametros['frm_fechaDefuncion']	   = date("Y-m-d", strtotime($parametros['frm_fallecimiento_fecha']));
					$parametros['frm_hora']			 	   = date("H:i", strtotime($parametros['frm_fallecimiento_fecha']));
					$parametros['frm_fechaIngreso']	 	   = date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);
					$parametros['reg_usuario_defuncion']   = $_SESSION['MM_Username'.SessionName];
					$response	= $objPaciente->actualiza_estado_paciente($objCon,$parametros);
					$response 	= $objPaciente->grabar_estado($objCon, $parametros);
					$citas      = $objAgenda->contadorCitas($objCon,$parametros);
					$motivo     = 8;
					$usuario	= $_SESSION['MM_Username'.SessionName];
					for ( $j = 0; $j < count($citas) ; $j++ ) {
						$respuesta3 = $objAgenda->cancelarCita($objCon,$citas[$j]['CITcodigo'],$motivo,'Paciente Registrado como Fallecido desde Indice Pacientes, Usuario:'.$usuario.' Fecha: '.date("d-m-Y H:i:s"));
					}
					$interconsulta = $objAgenda->contadorInterconsultas($objCon,$parametros);
					$tipoEgreso    = 2;
					$motivoEgreso  = 2;
					for ( $i = 0; $i < count($interconsulta); $i++ ) {
						$respuesta4 = $objAgenda->egresarInterconsulta($objCon,$interconsulta[$i]['INTcodigo'],$tipoEgreso,$motivoEgreso,'Paciente Registrado como Fallecido desde Indice Pacientes, Usuario:'.$usuario.' Fecha: '.date("d-m-Y H:i:s"));
					}
				}

				$objDau->actualizarCama($objCon,$parametros);
				$parametros['dau_mov_descripcion'] 	= 'indicacion aplicada';
				$parametros['dau_mov_tipo'] 		= "ina";
				$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];

				$parametrosUIC 		= array("dau_id" => $_POST['dau_id'], "dau_mov_cama_usuario_egreso" =>  $parametros['dau_mov_usuario'], "dau_mov_cama_estado" => "egresadoCama");

				$resp_ultimoIdCama 					= $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
				$parametrosUIC['id_ultimoMovCam'] 	= $resp_ultimoIdCama[0]['id'];
				$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);
				ingresarSolicitudAPS($objCon, $objUtil, $parametros, $objRce, $objPaciente);

				$parametrosSAU['SAUusuarioAplica'] 	= $_SESSION['MM_Username'.SessionName];
				$parametrosSAU['SAUidDau']		   	= $parametros['dau_id'];
				$objAltaUrgencia->cambiarEstadoSolicitudAltaUrgencia($objCon, $parametrosSAU);
				// $resp_ind_egr[0]['ind_egr_id'] = 8;
				if ( $resp_ind_egr[0]['ind_egr_id'] == 8 || $resp_ind_egr[0]['ind_egr_id'] == 9 || $resp_ind_egr[0]['ind_egr_id'] == 10 ) {
					trasladoPaciente($objCon, $objUtil, $parametros['dau_id'], $objAdmision ,$objCMBD); 
				}
				$objMovimiento->guardarMovimiento($objCon, $parametros);
				//ENVIAR MSJ WS PYXIS
				require_once("../../../../integracion/grifols/pyxis/class/Grifols.class.php"); $objGrifols      = new Grifols;

				$rut_completo 								= $resPaciente[0]['rut'] . "-" . $objUtil->generaDigito($resPaciente[0]['rut']);
				$fechaNaciminto 							= date('Ymd', strtotime($resPaciente[0]['fechanac']));

				if($resp_ind_egr[0]['ind_egr_id'] == 4){
					$codigoMensaje 							= 'A02';
					$parametros['servicio'] 				= $parametros['servicio_destino'];
					$mensajeObservacion 					= 'PACIENTE TRASLADADO DE URGENCIA';
					$parametros_ws_pyxis['ServicioOrigen'] 	= 10322;
					$fechaAdmision							= date('YmdHis');
					$fechaAlta								= '';
				}else{
					$codigoMensaje 							= 'A03';
					$parametros['servicio'] 				= 10322;
					$mensajeObservacion 					= 'PACIENTE EGRESADO DE URGENCIA';
					$parametros_ws_pyxis['ServicioOrigen'] 	= 0;
					$fechaAdmision							= '';
					$fechaAlta 								= date('YmdHis');
				}
				// DATOS MSJ
				$parametros_ws_pyxis['log_wspy_sistemaEnviaMensaje'] 	= 2;
				$parametros_ws_pyxis['log_wspy_idDau'] 					= $parametros['dau_id'];
				// MSH
				$parametros_ws_pyxis['CodigoMensaje'] 	= $codigoMensaje;
				$parametros_ws_pyxis['FechaMensaje'] 	= date('YmdHis');
				$parametros_ws_pyxis['IdMensaje'] 		= '';
				$parametros_ws_pyxis['CodigoCentro'] 	= '';
				// PID
				$parametros_ws_pyxis['IdPaciente'] 		= $rut_completo;
				$parametros_ws_pyxis['IdAltPaciente'] 	= $parametros['paciente_id'];
				$parametros_ws_pyxis['ApPaterno'] 		= $resPaciente[0]['apellidopat'];
				$parametros_ws_pyxis['ApMaterno'] 		= $resPaciente[0]['apellidomat'];
				$parametros_ws_pyxis['Nombres'] 		= $resPaciente[0]['nombres'];
				$parametros_ws_pyxis['Sexo'] 			= $resPaciente[0]['sexo'];
				$parametros_ws_pyxis['FechaNacimeinto'] = $fechaNaciminto;
				// PV1
				$parametros_ws_pyxis['UnidadEnfermeria'] 	= $parametros['servicio'];
				$parametros_ws_pyxis['Sala'] 				= '';
				$parametros_ws_pyxis['Cama'] 				= '';
				$parametros_ws_pyxis['idCentro'] 			='';
				$parametros_ws_pyxis['IdMedico'] 			= '';
				$parametros_ws_pyxis['MedicoApPaterno'] 	= '';
				$parametros_ws_pyxis['MedicoApMaterno'] 	= '';
				$parametros_ws_pyxis['MedicoNombres'] 		= '';
				$parametros_ws_pyxis['IdEpisodio'] 			= $resp_estado[0]['idctacte'];
				$parametros_ws_pyxis['FechaAdmision'] 		= $fechaAdmision;
				$parametros_ws_pyxis['FechaAlta'] 			= $fechaAlta;
				// OBX
				$parametros_ws_pyxis['Observacion'] = $mensajeObservacion;

				$con = $objGrifols->requestWS_grifols($objCon, $parametros_ws_pyxis);

				if($con['status'] == 'success'){
					// echo "MENSAJE ENVIADO A PYXIS";
					$status_pyxis 	= $con['status'];
				}
				else{
					// echo "MENSAJE : " . $con['message'];
					$status_pyxis 	= $con['status'];
					$message_pyxis	= $con['message'];
				}
				// =======================================
				//CMBD INDIACIÓN APLICA

				$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 5);
				$objCon->commit();
				$responseRetorno = array("status" => "success", "id" => $parametros['dau_id'], "nombreSala" => $nombreSala, "numeroCama" => $numeroCama, "message" => $message, "typeMessage" => $typeMessage);
			}
			echo json_encode($responseRetorno);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}

	break;
	case 'pacienteTieneIndicacionesNoSuperfluas':
		try {
			$objCon->beginTransaction();
			$respuestaIdRCE 			= $objRce->obtenerIdRCESegunDAU($objCon, $_POST['idDau']);
			$respuestaSolicitudes		= $objRce->obtenerCantidadSolicitudesNoSuperfluas($objCon, $respuestaIdRCE['regId']);
			if( $respuestaSolicitudes['solicitudesImportantes'] === $respuestaSolicitudes['solicitudesAplicadas']) {
				$solicitudesAplicadas 	= 1;
			} else {
				$solicitudesAplicadas 	= 1;
			}
			$respuesta = array("status" => "success", "solicitudesAplicadas" => $solicitudesAplicadas);
			echo json_encode($respuesta);
		} catch ( PDOException $e ) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case "modificarInicioAtencion":
		$objCon->db_connect();
		$parametros 							= $objUtil->getFormulario($_POST);
		$parametros['atencion'] 				= $_SESSION['MM_Username'.SessionName];
		$parametros["dau_viaje_epidemiologico"] = $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
		$parametros["dau_pais_epidemiologia"] 	= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
		$parametros["dau_observacion_epidemiologica"] = ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;
		try{
			$objCon->beginTransaction();
			//DAU
			$ActualizarInicioAtencion 			= $objDau->ActualizarInicioAtencion($objCon,$parametros);
			$parametros['dau_mov_descripcion'] 	= 'modificar inciar atencion';
			$parametros['dau_mov_tipo'] 		= "inc";
			$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
			$parametros['id_dau'] 				= $parametros["frm_dau_idSIA"];
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_viaje_epidemiologico']))
				? " - ".$parametros['dau_viaje_epidemiologico']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_pais_epidemiologia']))
				? " - ".$parametros['dau_pais_epidemiologia']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_observacion_epidemiologica']))
				? " - ".$parametros['dau_observacion_epidemiologica']
				: NULL;
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			//RCE
			$parametros['dau_id'] 					= $parametros['frm_dau_idSIA'];
			$parametros['usuario'] 					= $_SESSION['MM_Username'.SessionName];
			$resp 									= $objRegistroClinico->consultaRCE($objCon,$parametros);
			$parametros['rce_id'] 					= $resp[0]['regId'];
			$parametros['dau_mov_usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametros['frm_rce_alc_fechSIA'] 		= date("Y-m-d H:i:s",strtotime($parametros['frm_rce_alc_fechSIA']));
			if ( $parametros['frm_rcedetalle_rbalcSIA'] == 'No' ) {
				$parametros['frm_rce_est_etiSIA'] 	= "";
				$parametros['frm_rce_alc_fechSIA'] 	= "";
				$parametros['frm_rce_n_frascoSIA'] 	= "";
			}
			$respuesta 								= $objRegistroClinico->actualizaRCESIA($objCon,$parametros);
			$parametros['dau_mov_descripcion']		= 'modificar Rce';
			$parametros['dau_mov_tipo']				= 'mrc';
			$objMovimiento->guardarMovimiento($objCon,$parametros);
			$resultadoAlcoholemia 					= $objDau->resultadoAlcoholemia($objCon, $parametros['dau_id']);
			$alcoholemiaIngresada 					= array(
				'dau_alcoholemia_estado_etilico' 	=> $parametros['frm_rce_est_etiSIA'],
				'dau_alcoholemia_fecha' 			=> $parametros['frm_rce_alc_fechSIA'],
				'dau_alcoholemia_numero_frasco' 	=> $parametros['frm_rce_n_frascoSIA']
			);
			$resultadoDiferencia 	= array_diff($resultadoAlcoholemia, $alcoholemiaIngresada);
			if ( count($resultadoDiferencia) > 0 ) {
				$respuesta2 = $objRegistroClinico->actualizaAlcohSIA($objCon,$parametros);
				$parametros['dau_mov_descripcion']	= 'modificar alcoholemia';
				$objMovimiento->guardarMovimiento($objCon,$parametros);
			}
			$parametrosSIA['SIAidRCE'] 				= $parametros['frm_rce_idSIA'];
			$parametrosSIA['SIAidPaciente'] 		= $parametros['frm_paciente_idSIA'];
			$idSIA 									= $objDau->obtenerDatosSolicitudInicioAtencion($objCon, $parametrosSIA['SIAidRCE']);
			if ( count($idSIA)>0 ) {
				$parametrosSIA['SIAid'] = $idSIA[0]['SIAid'];
				$parametrosSIA['SIAusuarioModifica'] = $_SESSION['MM_Username'.SessionName];
				$objDau->actualizarSolicitudInicioAtencion($objCon, $parametrosSIA);
				$subparametrosBitacora['BITid'] 				= $parametros['frm_dau_idSIA'];
				$subparametrosBitacora['BITtipo_codigo'] 		= 2;
				$subparametrosBitacora['BITtipo_descripcion']	= "Inicio atención";
				$subparametrosBitacora['BITdatetime'] 			= "NOW()";
				$subparametrosBitacora['BITusuario'] 			= $parametros['dau_mov_usuario'];
				$subparametrosBitacora['BITdescripcion'] 		.= " <b>MOTIVO DE CONSULTA</b> (".$parametros['frm_rce_motivoConsultaSIA'].") <br> <b>HIPOTESIS DIAGNOSTICA</b> (".$parametros['frm_rce_hipotesisInicialSIA'].") ";
				$objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
			} else {
				$parametrosSIA['SIAusuario'] 					= $_SESSION['MM_Username'.SessionName];
				$objDau->ingresarSolicitudInicioAtencion($objCon, $parametrosSIA);
				$subparametrosBitacora['BITid'] 				= $parametros['frm_dau_idSIA'];
				$subparametrosBitacora['BITtipo_codigo'] 		= 2;
				$subparametrosBitacora['BITtipo_descripcion']	= "Inicio atención";
				$subparametrosBitacora['BITdatetime'] 			= "NOW()";
				$subparametrosBitacora['BITusuario'] 			= $parametros['dau_mov_usuario'];
				$subparametrosBitacora['BITdescripcion'] 		.= " <b>MOTIVO DE CONSULTA</b> (".$parametros['frm_rce_motivoConsultaSIA'].") <br> <b>HIPOTESIS DIAGNOSTICA</b> (".$parametros['frm_rce_hipotesisInicialSIA'].") ";
				$objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
			}
			$resultadoCama 	= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
			$nombreSala 	= $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];
			//CMBD INICIO ATENCIÓN
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 3);


			$objCon->commit();
			$response = array(
				"status" 		=> "success",
				"id" 			=> $parametros['dau_id'],
				"nombreSala" 	=> $nombreSala
			);
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;

	/////////////////////////////////////////////////////////////////////////////
	case "actualizarAlcoholemia":

		require_once("../../../../class/Dau.class.php" ); $objDau= new Dau;
		require_once("../../../../class/Movimientos.class.php");   $objMovimiento   = new Movimientos;

		$objCon->db_connect();

		$parametros = $objUtil->$objUtil->getFormulario($_POST);

		$parametros['frm_alc_fecha']       				= date("Y-m-d H:i:s");
		$parametros['dau_alcoholemia_fecha']          	= $parametros["frm_alc_fecha"];
		$parametros['dau_alcoholemia_numero_frasco']  	= $parametros["frm_numero_frasco"];
		$parametros['dau_alcoholemia_apreciacion']    	= $parametros["frm_apreciacion"];
		$parametros['dau_alcoholemia_resultado']      	= $parametros["frm_resultado"];
		$parametros['dau_alcoholemia_estado_etilico'] 	= $parametros["frm_estado_etilico"];
		$parametros['dau_alcoholemia_medico']         	= $parametros["frm_alcoholemia_medico"];
		$parametros['dau_mov_descripcion'] 				= 'alcoholemia';
		$parametros['dau_mov_tipo'] 					= "alc";
		$parametros['dau_mov_usuario'] 					= $_SESSION['MM_Username'.SessionName];

		try {

			$objCon->beginTransaction();

			$actualizarAlcoholemia = $objDau->actualizarAlcoholemia($objCon,$parametros);

			$objMovimiento->guardarMovimiento($objCon, $parametros);

			$response = array("status" => "success", "id" => $parametros['dau_id']);

			$objCon->commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case "registrarIndicacionEgreso":

		require_once("../../../../class/Dau.class.php" ); 			$objDau= new Dau;
		require_once("../../../../class/Movimientos.class.php");   	$objMovimiento   = new Movimientos;

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);

		$parametros['frm_fecha_date'] 	= $parametros['frm_fecha_indicacionEgreso'];
		$parametros['estado_dau']		= 4;
		$parametros['estado_ind']		= 20;

		switch ($parametros['frm_Indicacion_Egreso']) {

			case 3:

				switch ($parametros['frm_alta_derivacion']) {

					case 1:
						$parametros['frm_sum_indicacion']	=1;
					break;

					case 2:
						$parametros['especialidad']			= $parametros['frm_especialidad'];
						$parametros['frm_sum_indicacion'] 	= 12;
					break;

					case 3:
						$parametros['aps']					= $parametros['frm_aps'];
						$parametros['frm_sum_indicacion'] 	= 14;
					break;

					case 4:
						$parametros['frm_sum_indicacion'] 	= 6;
					break;

					case 5:
						$parametros['frm_sum_indicacion'] 	= 10;
					break;
				}
			break;


			case 4:
				$parametros['frm_sum_indicacion'] = 2;
			break;


			case 5:
				$parametros['frm_sum_indicacion'] = 5;
			break;


			case 6:
				if ( $parametros['frm_destino_defuncion'] == 1 ) {
					$parametros['frm_sum_indicacion']	= 7;
				} else if ( $parametros['frm_destino_defuncion'] == 2 ) {
					$parametros['frm_sum_indicacion']	= 8;
				}
			break;


			case 7:
				$parametros['frm_sum_indicacion'] = 13;
			break;

		}

		$parametros['frm_fecha_modificar'] = date("Y-m-d");

		try {

			$objCon->beginTransaction();

			$resp_estado = $objDau->datosDau($objCon,$parametros);

			if ( $resp_estado[0]['est_id'] == 5 ) {

				$response = array("status" => "warning", "id" => $parametros['dau_id'], "message" => 'Al paciente ya se le Aplico la Indicación de Egreso');

			} else {

				if ( $parametros['frm_Indicacion_Egreso'] != 4 ) {

					$parametros['frm_servicio_destino']	= NULL;
					$parametros['dau_mov_descripcion'] 	= 'indicacion egreso';
					$parametros['dau_mov_tipo'] 		= "ind";
					$registrarIndicacionEgreso 			= $objDau->registrarIndicacionEgreso($objCon,$parametros);

				} else {

					$parametros['dau_mov_descripcion'] 	= 'indicacion egreso';
					$parametros['dau_mov_tipo'] 		= "ind";
					$registrarIndicacionEgreso 			= $objDau->registrarIndicacionEgreso($objCon,$parametros);

				}

				$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
				$objMovimiento->guardarMovimiento($objCon, $parametros);

				$parametros['usuario_defuncion_ingreso'] = $_SESSION['MM_Username'.SessionName];

				if ( $parametros['frm_Indicacion_Egreso'] == 6 ) {

				 	$ActualizarIndicacionEgresoDau = $objDau->ActualizarIndicacionEgresoDau($objCon,$parametros);

				} else {

				 	$anularFechas = $objDau->anularFechas($objCon,$parametros);

				}

				$objDau->insertarMovimientoIndicacion($objCon, $parametros);

				if ( $parametros['cie10_id'] != '' || $parametros['cie10_id'] != NULL ) {

					$parametros['dau_mov_descripcion'] 	= 'registro medico cie10 MPISO-IND ['.$parametros['cie10_id'].']';
					$parametros['dau_mov_tipo'] 		= "rmp";
					$objMovimiento->guardarMovimiento($objCon, $parametros);
				}

				$response = array("status" => "success", "id" => $parametros['dau_id'], "nombreSala" => $nombreSala);

				$objCon->commit();

				echo json_encode($response);

			}

		} catch (PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case "registrarInicioAtencion":

		require_once("../../../../class/Dau.class.php" );          	$objDau = new Dau;
		require_once("../../../../class/Movimientos.class.php");   	$objMovimiento = new Movimientos;
		require_once("../../../../class/RegistroClinico.class.php");$objRegistro = new RegistroClinico();
		require_once('../../../../class/Bitacora.class.php');  		 	$objBitacora = new Bitacora;
		require_once("../../../../class/MapaPiso.class.php" );      $objMapaPiso = new MapaPiso;

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);
		$parametros['atencion'] = $_SESSION['MM_Username'.SessionName];
		$parametros["dau_viaje_epidemiologico"] =
			$objUtil->existe($parametros["frm_viajeEpidemiologico"])
			? $parametros["frm_viajeEpidemiologico"]
			: "N";
		$parametros["dau_pais_epidemiologia"] =
			($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S")
			? $parametros["frm_paisEpidemiologia"]
			: NULL;
		$parametros["dau_observacion_epidemiologica"] =
			($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S")
			? $parametros["frm_observacionEpidemiologica"]
			: NULL;

		try{

			$objCon->beginTransaction();

			//DAU
			$ActualizarInicioAtencion = $objDau->ActualizarInicioAtencion($objCon,$parametros);

			$parametros['dau_mov_descripcion'] = 'inciar atencion';
			$parametros['dau_mov_tipo'] = "inc";
			$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
			$objMovimiento->guardarMovimiento($objCon, $parametros);

			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_viaje_epidemiologico']))
				? " - ".$parametros['dau_viaje_epidemiologico']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_pais_epidemiologia']))
				? " - ".$parametros['dau_pais_epidemiologia']
				: NULL;
			$parametros['dau_mov_descripcion'] 	.=
				($objUtil->existe($parametros['dau_observacion_epidemiologica']))
				? " - ".$parametros['dau_observacion_epidemiologica']
				: NULL;
			$objMovimiento->guardarMovimiento($objCon, $parametros);



			//RCE
			$parametros['dau_id'] = $parametros['frm_dau_id'];
			$parametros['id_dau']	= $parametros['frm_dau_id'];
			$parametros['usuario'] = $_SESSION['MM_Username'.SessionName];
			$resp = $objRegistro->consultaRCE($objCon,$parametros);
			$parametros['rce_id'] = $resp[0]['regId'];
			$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
			if($parametros['frm_rce_alc_fech'] != ""){
				$parametros['frm_rce_alc_fech'] = date("Y-m-d H:i:s",strtotime($parametros['frm_rce_alc_fech']));
			}
			$parametros['rut'] = $_SESSION['usuarioActivo']['rut'];

			if ( $parametros['frm_rcedetalle_rbalc'] == 'No' || $parametros['frm_rcedetalle_rbalc'] == null ) {
				$parametros['frm_rce_est_eti'] = "";
				$parametros['frm_rce_alc_fech'] = "";
				$parametros['frm_rce_n_frasco'] = "";
				$parametros['frm_rce_alc_fech'] = "";
			}

			$respuesta  	= $objRegistro->actualizaRCE($objCon,$parametros);

			$parametros['dau_mov_descripcion'] = 'modificar Rce';
			$parametros['dau_mov_tipo']	= 'mrc';
			$objMovimiento->guardarMovimiento($objCon,$parametros);

			if ( $parametros['chk'] == 1 ) {
				$respuesta2 = $objRegistro->actualizaAlcoh($objCon,$parametros);
				$parametros['dau_mov_descripcion']	= 'modificar alcoholemia';
				$objMovimiento->guardarMovimiento($objCon,$parametros);
			}

			if ($parametros['frm_rce_id'] != '') {
				$parametrosSIA['SIAidRCE'] = $parametros['frm_rce_id'];

			} else {
				$parametrosSIA['SIAidRCE'] = $parametros['rce_id'];
			}

			$parametrosSIA['SIAidPaciente'] = $parametros['frm_paciente_id'];
			$parametrosSIA['SIAusuario']	= $_SESSION['MM_Username'.SessionName];

			$idSIA = $objDau->obtenerDatosSolicitudInicioAtencion($objCon, $parametrosSIA['SIAidRCE']);
			if ( ! is_null($idSIA[0]['SIAid']) || !empty($idSIA[0]['SIAid']) ) {
				$parametrosSIA['SIAid'] = $idSIA[0]['SIAid'];
				$objDau->actualizarSolicitudInicioAtencion($objCon, $parametrosSIA);

			} else {
				$subparametrosBitacora['BITid'] = $parametros['frm_dau_id'];
				$subparametrosBitacora['BITtipo_codigo'] = 2;
				$subparametrosBitacora['BITtipo_descripcion'] = "Inicio atención";
				$subparametrosBitacora['BITdatetime'] = "NOW()";
				$subparametrosBitacora['BITusuario'] = $parametrosSIA['SIAusuario'];
				$subparametrosBitacora['BITdescripcion'] .= " <b>MOTIVO DE CONSULTA</b> (".$parametros['frm_rce_motivoConsulta'].") <br> <b>HIPOTESIS DIAGNOSTICA</b> (".$parametros['frm_rce_hipotesisInicial'].") ";
				$objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
				$objDau->ingresarSolicitudInicioAtencion($objCon, $parametrosSIA);
			}

			$resultadoCama 	= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
			print('<pre>'); print_r($resultadoCama); print('</pre>');
			$nombreSala 	= $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];
			//CMBD INICIO ATENCIÓN
			$objCon->setDB("dau");
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 3);
			$objCon->commit();

			$response = array(
				"status" => "success",
				"id" => $parametros['dau_id'],
				"nombreSala" => $nombreSala
			);

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;






	case 'aplicarNEAClinico':

		require_once("../../../../class/Cierre.class.php" ); 		$objCierre 		= new Cierre;
		require_once("../../../../class/Movimientos.class.php");    $objMovimiento  = new Movimientos;
		require_once("../../../../class/MapaPiso.class.php"); 		$objMapaPiso 	= new MapaPiso;

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);

		// print('<pre>'); print_r($parametros); print('</pre>');



		try{

			#region try

			$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];

			$objCon->beginTransaction();

			if ( !empty($parametros['banderaAdministrativo']) && ! is_null($parametros['banderaAdministrativo']) && $parametros['banderaAdministrativo'] == 'S' ) {

				$parametros['dau_cierre_administrativo'] = "S";

			} else {

				$parametros['dau_cierre_administrativo'] = "C";

			}

			$parametros['radio_egreso']              = 7;
			$parametros['frm_motivo_egreso']         = $parametros['frm_txt_apliNEA'];
			$parametros['reg_usuario_insercion']     = $parametros['dau_mov_usuario'];
			$parametros['frm_fecha_egreso_adm']      = $parametros['frm_fecha_aNEA'].' '.$parametros['frm_hora_aNEA'];
			$parametros['fecha_cierre_final']        = date("Y-m-d H:i:s");
			$parametros['Iddau']                     = $parametros['dau_id'];
			$parametros['dau_mov_descripcion'] 		 = "cierre dau";
			$parametros['dau_mov_tipo']              = "cie";
			$parametros['frm_est_id']          		 = $parametros['est_id'];
			$parametros['id_dau'] 					 = $parametros['dau_id'];

			$resultadoCama = $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);

			$nombreSala = $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];
			$numeroCama = $resultadoCama[0]['cam_id'];

			$resp = $objCierre->cierreAdministrativoDAU($objCon, $parametros);

			$parametrosMP['id_dau']   = $parametros['Iddau'];
			$respMP                   = $objMapaPiso->getLugarPaciente_clinico($objCon, $parametrosMP);

			if ( count($respMP) > 0 ) {

				$objCierre->vaciarCamaCierre($objCon, $parametros);

				$parametrosUIC                    = array("dau_id" => $parametros['dau_id'], "dau_mov_cama_usuario_egreso" =>  $parametros['dau_mov_usuario'], "dau_mov_cama_estado" => "egresadoCama");
				$resp_ultimoIdCama                = $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
				$parametrosUIC['id_ultimoMovCam'] = $resp_ultimoIdCama[0]['id'];
				$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);

				$response  = array("status" => "success", "id" =>$parametros['dau_id'], "idSalaCama" => $nombreSala, "numeroCama" => $numeroCama);

			} else {

				$response = array("status" => "success", "id" => $parametros['dau_id']);

			}

			#endregion

			/**
			 * ENVIO DE MENSAJE AL PYXIS
			 * [A11] : CANCELACION DE ADMISION
			 */

			// require_once("../../../../../integracion/grifols/pyxis/class/Grifols.class.php"); $objGrifols      = new Grifols;
			// require_once("../../../../class/Paciente.class.php");   $objPacienteDAU   = new PacienteDAU;

			// $rsp_paciente_dau = $objPacienteDAU -> obtenerDatosPacienteDau($objCon, $parametros['dau_id']);

			// $rsp_pacint = $objPacienteDAU->obtenerInformacionPaciente($objCon, $rsp_paciente_dau['idPaciente']);
			// $rut_completo = $rsp_paciente_dau['rut'] . "-" . $objUtil->generaDigito($rsp_paciente_dau['rut']);
			// $fechaNaciminto = date('Ymd', strtotime($rsp_paciente_dau['fechanac']));
			// $parametros['servicio'] = 10322;

			// print('<pre>'); print_r($rsp_paciente_dau); print('</pre>');

			// DATOS MSJ
			$parametros_ws_pyxis['log_wspy_sistemaEnviaMensaje'] = 2;
			$parametros_ws_pyxis['log_wspy_idDau'] = $rsp_paciente_dau['dau_id'];
			// MSH
			// $parametros_ws_pyxis['TipoMensaje'] = 'ADT';
			$parametros_ws_pyxis['CodigoMensaje'] = 'A11';
			$parametros_ws_pyxis['FechaMensaje'] = date('YmdHis');
			// $parametros_ws_pyxis['IdMensaje'] = '3';
			$parametros_ws_pyxis['IdMensaje'] = '';
			$parametros_ws_pyxis['CodigoCentro'] ='';
			// PID
			$parametros_ws_pyxis['IdPaciente'] = $rut_completo;
			$parametros_ws_pyxis['IdAltPaciente'] = $rsp_paciente_dau['id_paciente'];
			$parametros_ws_pyxis['ApPaterno'] = $rsp_paciente_dau['apellidopat'];
			$parametros_ws_pyxis['ApMaterno'] = $rsp_paciente_dau['apellidomat'];
			$parametros_ws_pyxis['Nombres'] = $rsp_paciente_dau['nombres'];
			$parametros_ws_pyxis['Sexo'] = $rsp_paciente_dau['sexo'];
			$parametros_ws_pyxis['FechaNacimeinto'] = $fechaNaciminto;
			// PV1
			// $UnidadEnfermeria = '1';
			// $parametros_ws_pyxis['UnidadEnfermeria'] = 'URGENCIA - 10322';
			$parametros_ws_pyxis['UnidadEnfermeria'] = $parametros['servicio'];
			// $Sala = '1';
			$parametros_ws_pyxis['Sala'] = '';
			// $Cama = '2';
			$parametros_ws_pyxis['Cama'] = '';
			$parametros_ws_pyxis['idCentro'] ='';
			// $IdMedico = '16225552-5';
			$parametros_ws_pyxis['IdMedico'] = '';
			// $MedicoApPaterno = 'altamirano';
			$parametros_ws_pyxis['MedicoApPaterno'] = '';
			// $MedicoApMaterno = 'altamirano';
			$parametros_ws_pyxis['MedicoApMaterno'] = '';
			// $MedicoNombres = 'rodrigo';
			$parametros_ws_pyxis['MedicoNombres'] = '';
			$parametros_ws_pyxis['IdEpisodio'] = $rsp_paciente_dau['idctacte'];
			$parametros_ws_pyxis['FechaAdmision'] = date('YmdHis', strtotime($rsp_paciente_dau['dau_admision_fecha']));
			$parametros_ws_pyxis['FechaAlta'] ='';
			// OBX
			$parametros_ws_pyxis['Observacion'] = 'CANCELACION DE ADMISION DEL PACIENTE EN URGENCIA';

			// $con = $objGrifols->requestWS_grifols($objCon, $parametros_ws_pyxis);

			// highlight_string(print_r($con, true));

			if($con['status'] == 'success'){
				// echo "MENSAJE ENVIADO A PYXIS";
				$status_pyxis = $con['status'];
			}
			else{
				// echo "MENSAJE : " . $con['message'];
				$status_pyxis = $con['status'];
				$message_pyxis = $con['message'];
			}

			//CMBD DAU ANULADO
			// $objCon->setDB("dau");
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 6);

			$objCon->commit();

			echo json_encode($response);

		} catch(PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'removerPacienteMapaPiso' :

		require_once("../../../../class/Dau.class.php" ); 			$objDau 		= new Dau;
		require_once("../../../../class/Movimientos.class.php");   	$objMovimiento 		= new Movimientos;
		require_once("../../../../class/Rce.class.php" );  			$objRCE				= new Rce;
		require_once("../../../../class/AltaUrgencia.class.php");  	$objAltaUrgencia	= new AltaUrgencia;

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);

		$parametrosAEnviar[] = array();

		$estadoSAU = $objRCE->obtenerEstadoSAU($objCon, $parametros['idDau']);

		if ( $estadoSAU['SAUestado'] != 1 && $estadoSAU['SAUestado'] != 4 ) {

			$respuesta = array("status" => "error");

			echo json_encode($respuesta);

			return;

		}

		$datosDau = $objDau->obtenerEstadoDauPaciente($objCon,$parametros['idDau']);

		if ( $datosDau['est_id'] == 5 ) {

			$respuesta = array("status" => "success");

			echo json_encode($respuesta);

			return;

		}

		$parametrosAEnviar['dau_id'] = $parametros['idDau'];
		$parametrosAEnviar['estado_dau'] = 5;
		$parametrosAEnviar['frm_fecha_modificar'] = date("Y-m-d H:i:s");
		$parametrosAEnviar['indAplica'] = "dauAutomatico";
		$objDau->ActualizarIndicacionAplicaDau($objCon,$parametrosAEnviar);

		$objRCE->cambiarEstadoAplicarEgresoSolicitudSIC($objCon, $parametros['idDau']);
		ingresarSolicitudSICPrimordial($objCon, $objAgenda, $objRCE, $parametros['idDau']);

		try {

			$objCon->beginTransaction();

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$parametrosAEnviar['estado_ind'] = 21;
			$parametrosAEnviar['frm_fecha_modificar'] = date("Y-m-d H:i:s");
			$parametrosAEnviar['indAplica']	= true;
			$parametrosAEnviar['dau_mov_usuario'] = "dauAutomatico";
			$objDau->ActualizarIndicacionAplica($objCon,$parametrosAEnviar);

			unset($parametrosAEnviar);

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$resultadoDatosPaciente = $objDau->getDatosEgreso($objCon, $parametrosAEnviar);
			$parametrosAEnviar['destino_dau'] = $resultadoDatosPaciente[0]['des_id'];
			$parametrosAEnviar['derivacion_dau']  = $resultadoDatosPaciente[0]['alt_der_id'];
			$parametrosAEnviar['derivacion_especialista']	= $resultadoDatosPaciente[0]['dau_ind_especialidad'];
			$parametrosAEnviar['derivacion_aps'] = $resultadoDatosPaciente[0]['dau_ind_aps'];
			$parametrosAEnviar['derivacion_otros'] = $resultadoDatosPaciente[0]['dau_ind_otros'];

			if ( $resultadoDatosPaciente['ind_egr_id'] != 8 && $resultadoDatosPaciente['ind_egr_id'] != 9  && $resultadoDatosPaciente['ind_egr_id'] != 10 ) {

				$objDau->actualizarDau($objCon,$parametrosAEnviar);

			}

			unset($parametrosAEnviar);

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$parametrosAEnviar['estadoCama'] = 10;
			$objDau->actualizarCama($objCon,$parametrosAEnviar);

			unset($parametrosAEnviar);

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$parametrosAEnviar['dau_mov_descripcion']	= 'Cierre Dau Automatico';
			$parametrosAEnviar['dau_mov_usuario']	= 'dauAutomatico';
			$parametrosAEnviar['dau_mov_tipo'] = 'cie';
			$objMovimiento->guardarMovimiento($objCon, $parametrosAEnviar);

			unset($parametrosAEnviar);

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$resultadoObtenerUltimoMovimientoCama = $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosAEnviar);
			$parametrosAEnviar['id_ultimoMovCam'] = $resultadoObtenerUltimoMovimientoCama[0]['id'];
			$parametrosAEnviar['dau_mov_cama_usuario_egreso'] = 'dauAutomatico';
			$parametrosAEnviar['dau_mov_cama_estado'] = 'egresoCama';
			$objMovimiento->actualizarMovimientoCama($objCon, $parametrosAEnviar);

			unset($parametrosAEnviar);

			$parametrosAEnviar['SAUusuarioAplica'] = 'dauAutomatico';
			$parametrosAEnviar['SAUidDau'] = $parametros['idDau'];
			$objAltaUrgencia->cambiarEstadoSolicitudAltaUrgencia($objCon, $parametrosAEnviar);

			unset($parametrosAEnviar);

			$parametrosAEnviar['dau_id'] = $parametros['idDau'];
			$resultadoDatosDAU = $objDau->datosDau($objCon, $parametrosAEnviar);
			$parametrosAEnviar['id_dau'] = $resultadoDatosDAU[0]['dau_id'];
			$parametrosAEnviar['paciente_id'] = $resultadoDatosDAU[0]['id_paciente'];
			$parametrosAEnviar['usuarioPideSolicitud'] = $_SESSION['MM_Username'.SessionName];
			$parametrosAEnviar['codigoCIE10'] = $resultadoDatosDAU[0]['dau_cierre_cie10'];

			ingresarSolicitudAPS($objCon, $objUtil, $parametrosAEnviar);

			unset($parametrosAEnviar);

			$objCon->commit();

			$respuesta = array("status" => "success");

			echo json_encode($respuesta);

		} catch ( PDOException $e ) {

			$objCon->rollback();

			//$objDau->ActualizarIndicacionAplicaDauRollback($objCon,$parametros);

			$respuesta = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($respuesta);

		}

	break;



	case 'ingresarLlamado' :

		require_once("../../../../class/Dau.class.php" ); 			$objDau = new Dau;

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);

		try {

			$objCon->beginTransaction();

			$parametrosAEnviar['idDau']			  = $parametros['idDau'];

			$parametrosAEnviar['numeroLlamado']   = $parametros['numeroLlamado'];

			$$parametrosAEnviar['usuario']		  = $_SESSION['MM_Username'.SessionName];

			$objDau->ingresarLlamado($objCon,$parametrosAEnviar);

			$objCon->commit();

			$respuesta = array("status" => "success", "fechaHora" => date("d-m-Y H:i"));

			echo json_encode($respuesta);

		} catch ( PDOException $e ) {

			$objCon->rollback();

			$respuesta = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($respuesta);

		}

	break;



	case 'pacienteTieneIndicacionesNoSuperfluas':

		require_once("../../../../class/Rce.class.php" );  $objRCE	= new Rce;

		$objCon->db_connect();

		$objCon->setDB("rce");

		try {

			$objCon->beginTransaction();

			$respuestaIdRCE = $objRCE->obtenerIdRCESegunDAU($objCon, $_POST['idDau']);

			$respuestaSolicitudes= $objRCE->obtenerCantidadSolicitudesNoSuperfluas($objCon, $respuestaIdRCE['regId']);

			if( $respuestaSolicitudes['solicitudesImportantes'] === $respuestaSolicitudes['solicitudesAplicadas']) {

				$solicitudesAplicadas = 1;

			} else {

				$solicitudesAplicadas = 0;

			}

			$respuesta = array("status" => "success", "solicitudesAplicadas" => $solicitudesAplicadas);

			echo json_encode($respuesta);

		} catch ( PDOException $e ) {

			$objCon->rollback();

			$respuesta = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($respuesta);

		}

	break;



	case 'seHaIniciadoAtencion' :

		

		$objCon->db_connect();

		$parametros =  $objUtil->getFormulario($_POST);

		try {

			$objCon->beginTransaction();

			$respuestaConsulta = $objDau->seHaIniciadoAtencion($objCon, $parametros['dau_id']);

			if ( empty($respuestaConsulta['dau_inicio_atencion_fecha']) || is_null($respuestaConsulta['dau_inicio_atencion_fecha']) ) {

				$respuesta = array("status" => "error");

			} else {

				$respuesta = array("status" => "success");

			}

			echo json_encode($respuesta);

		} catch ( PDOException $e ) {

			$objCon->rollback();

			$respuesta = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($respuesta);

		}

	break;



	case 'pacienteYaConNEA':

		require_once("../../../../class/Dau.class.php"); $objDau = new Dau();

		$objCon->db_connect();

		$response   = array();

		$parametros = $objUtil->getFormulario($_POST);

		try {

			$objCon -> beginTransaction();

			$estadoDau = $objDau->obtenerEstadoDauPaciente($objCon, $parametros['idDau']);

			$response = array("status" => "error");

			if ( $estadoDau['est_id'] == 7 ) {

				$response = array("status" => "success");

			}

			$objCon -> commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon -> rollback();

			$response = array("status" => "error", "message" => $e -> getMessage());

			echo json_encode($response);

		}

	break;



	case 'pacienteYaNulo':

		require_once("../../../../class/Dau.class.php"); $objDau = new Dau();

		$objCon->db_connect();

		$response   = array();

		$parametros = $objUtil->getFormulario($_POST);

		try {

			$objCon -> beginTransaction();

			$estadoDau = $objDau->obtenerEstadoDauPaciente($objCon, $parametros['idDau']);

			$response = array("status" => "error");

			if ( $estadoDau['est_id'] == 6 ) {

				$response = array("status" => "success");

			}

			$objCon -> commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon -> rollback();

			$response = array("status" => "error", "message" => $e -> getMessage());

			echo json_encode($response);

		}

	break;






	case "listarDAUEspecialidadGinecologica":

		$listado = $objDau->listarDAUEspecialidadGinecologica($objCon);

		echo json_encode($listado);

	break;

}



function ingresarSolicitudSICPrimordial ( $objCon,$objAgenda, $objRce, $idDau, $objUtil ) {
	$resultadoConsulta 		= $objRce->obtenerSolicitudesInterconsultaSegunDau($objCon, $idDau);
	$totalResultadoConsulta = count($resultadoConsulta);
	for ( $i = 0; $i < $totalResultadoConsulta; $i++ ) {
		$parametrosSIC = array();
		
		if ( $resultadoConsulta[$i]['dau_atencion'] == 3 ) {
			if (
				!is_null($resultadoConsulta[$i]["SICusuarioAplica"])
				&& !empty($resultadoConsulta[$i]["SICusuarioAplica"])
				&& (int)$resultadoConsulta[$i]["SICEstadoAplicarEgreso"] === 1
			) {
				continue;
			}
			$parametrosSIC['SIC_urgencia']            = $resultadoConsulta[$i]['SIC_urgencia'];
			$parametrosSIC['SICobservacionSolicitud'] = 'Aprobado automáticamente por Prioridad de Especialidad Ginecológica';
			$parametrosSIC['SICusuarioAplica']		  = 'dauAutomático_Gine_2';
			$objRce->aplicarSolicitudInterconsulta($objCon, $parametrosSIC);
			ingresarInterconsultaAAgenda_pri($resultadoConsulta[$i]['SIC_urgencia'], $objCon, $objRce,$objUtil,$objAgenda);
			continue;
		}
		$interconsultaPrimordial = $objRce->esInterconsultaPrimordial($objCon, $resultadoConsulta[$i]['SICespecialidadOrigen']);
		if ( $interconsultaPrimordial['ESPprimordial'] == 'N' ) {
			ingresarInterconsultaAAgendaNo_pri($resultadoConsulta[$i]['SIC_urgencia'], $objCon, $objRce,$objUtil,$objAgenda);
			continue;
		}
		$parametrosSIC['SIC_urgencia']            = $resultadoConsulta[$i]['SIC_urgencia'];
		$parametrosSIC['SICobservacionSolicitud'] = 'Aprobado automáticamente por Prioridad de Especialidad';
		$parametrosSIC['SICusuarioAplica']		  = 'dauAutomático_2';
		$objRce->aplicarSolicitudInterconsulta($objCon, $parametrosSIC);
		unset($parametrosSIC);
		ingresarInterconsultaAAgenda_pri($resultadoConsulta[$i]['SIC_urgencia'], $objCon, $objRce,$objUtil,$objAgenda);
	}
}


function ingresarInterconsultaAAgenda_pri ( $idSolicitudSic, $objCon, $objRce, $objUtil,$objAgenda ) {
	$respuesta = $objRce->obtenerDatosSolicitudInterconsulta($objCon, $idSolicitudSic);
	$respuesta = $objAgenda->insertarNuevaInterconsulta2($objUtil,
																$objCon,
																$respuesta['SICidPaciente'],
																'',
																$respuesta['SICfechaSolicitud'],
																$respuesta['SIChoraSolicitud'],
																$respuesta['SICestaOrigen'],
																$respuesta['SICestaDestino'],
																$respuesta['SICprocedencia'],
																$respuesta['SICespecialidadDestino'],
																$respuesta['SICespecialidadOrigen'],
																$respuesta['SICprioridad'],
																$respuesta['SICmotivoConsulta'],
																$respuesta['SICotroMotivo'],
																$respuesta['SIChipotesisDiagnostica'],
																$respuesta['SICauge'],
																$respuesta['SICproblemaAuge'],
																'',
																$respuesta['SICfundamentoDiagnostico'],
																$respuesta['SICexamenesRealizados'],
																$respuesta['SICprofesionalDescripcion'],
																$respuesta['SICrunProfesional'],
																$respuesta['SICdau'],
																1,
																'N'
															);
	if($respuesta > 0){
		$objRce->actualizarFolioSIC($objCon, $idSolicitudSic,$respuesta);
		$get_rce_dau = $objRce->get_rce_dau($objCon, $idDAU);
		if(count($get_rce_dau)>0){
			$regId_update 		= $get_rce_dau[0]['regId'];
			$INTcodigo_update 	= $respuesta;
			$objRce->actualizarRCE_Interconsulta ( $objCon, $regId_update,$INTcodigo_update );
		}
	}
}
function ingresarInterconsultaAAgendaNo_pri ( $idSolicitudSic, $objCon, $objRce, $objUtil,$objAgenda ) {
	$respuesta = $objRce->obtenerDatosSolicitudInterconsulta($objCon, $idSolicitudSic);
	$respuesta = $objAgenda->insertarNuevaInterconsulta2($objUtil,
																$objCon,
																$respuesta['SICidPaciente'],
																'',
																$respuesta['SICfechaSolicitud'],
																$respuesta['SIChoraSolicitud'],
																$respuesta['SICestaOrigen'],
																$respuesta['SICestaDestino'],
																$respuesta['SICprocedencia'],
																$respuesta['SICespecialidadDestino'],
																$respuesta['SICespecialidadOrigen'],
																$respuesta['SICprioridad'],
																$respuesta['SICmotivoConsulta'],
																$respuesta['SICotroMotivo'],
																$respuesta['SIChipotesisDiagnostica'],
																$respuesta['SICauge'],
																$respuesta['SICproblemaAuge'],
																'',
																$respuesta['SICfundamentoDiagnostico'],
																$respuesta['SICexamenesRealizados'],
																$respuesta['SICprofesionalDescripcion'],
																$respuesta['SICrunProfesional'],
																$respuesta['SICdau'],
																0,
																'S'
															);
	if($respuesta > 0){
		$objRce->actualizarFolioSIC($objCon, $idSolicitudSic,$respuesta);
		$get_rce_dau = $objRce->get_rce_dau($objCon, $idDAU);
		if(count($get_rce_dau)>0){
			$regId_update 		= $get_rce_dau[0]['regId'];
			$INTcodigo_update 	= $respuesta;
			$objRce->actualizarRCE_Interconsulta ( $objCon, $regId_update,$INTcodigo_update );
		}
	}
}
function ingresarInterconsultaAAgenda ( $idSolicitudSic, $objCon, $objRce, $objUtil,$objAgenda ) {
	$respuesta = $objRce->obtenerDatosSolicitudInterconsulta($objCon, $idSolicitudSic);
	$respuesta = $objAgenda->insertarNuevaInterconsulta2($objUtil,
																$objCon,
																$respuesta['SICidPaciente'],
																'',
																$respuesta['SICfechaSolicitud'],
																$respuesta['SIChoraSolicitud'],
																$respuesta['SICestaOrigen'],
																$respuesta['SICestaDestino'],
																$respuesta['SICprocedencia'],
																$respuesta['SICespecialidadDestino'],
																$respuesta['SICespecialidadOrigen'],
																$respuesta['SICprioridad'],
																$respuesta['SICmotivoConsulta'],
																$respuesta['SICotroMotivo'],
																$respuesta['SIChipotesisDiagnostica'],
																$respuesta['SICauge'],
																$respuesta['SICproblemaAuge'],
																'',
																$respuesta['SICfundamentoDiagnostico'],
																$respuesta['SICexamenesRealizados'],
																$respuesta['SICprofesionalDescripcion'],
																$respuesta['SICrunProfesional'],
																$respuesta['SICdau']
															);
}



function ingresarSolicitudAPS ( $objCon, $objUtil, $parametros , $objRce, $objPaciente ) {

	if (! pacienteCumpleCondicionesSolicitudAPS($objCon, $objRce, $parametros["id_dau"])) {
		return;
	}
	$resultadoConsulta = $objRce->obtenerCIE10FiltroAPS($objCon, $parametros['codigoCIE10']);

	if ( $resultadoConsulta['filtroSolicitudAPS'] == 'N' ) {
		return;
	}
	if ( pacienteConSolicitudAPS($objCon, $objRce, $parametros['id_dau']) ) {
		return;
	}
	$idCentroAtencionPrimaria 				   = $objPaciente->obtenerCentroAtencionPrimaria($objCon, $parametros['paciente_id']);
	$parametrosAEnviar                         = array();
	$parametrosAEnviar['idDau']                = $parametros['id_dau'];
	$parametrosAEnviar['idPaciente']           = $parametros['paciente_id'];
	$parametrosAEnviar['usuarioPideSolicitud'] = $_SESSION['MM_Username'.SessionName];
	$parametrosAEnviar['codigoCIE10'] 		   = $parametros['codigoCIE10'];
	$parametrosAEnviar['codigoConsultorio']    = $idCentroAtencionPrimaria['centroatencionprimaria'];
	$objRce->insertarSolicitudAPS($objCon, $parametrosAEnviar);
}



function ingresarPostIndicacionEgreso ( $objCon, $objDau, $objUtil, $parametros ) {
	$parametrosAEnviar 									= array();
	$parametrosAEnviar['idDau'] 						= $parametros['dau_id'];
	$parametrosAEnviar['tipoPostIndicacionEgreso'] 		= $parametros['frm_postIndicacionEgreso'];
	$parametrosAEnviar['usuarioAplicacionEgreso'] 		= $_SESSION['MM_Username'.SessionName];
	$objDau->insertarPostIndicacionEgreso($objCon, $parametrosAEnviar);

}



function pacienteCumpleCondicionesSolicitudAPS($objCon, $objRce, $idDau) {

	$pacienteCumpleCondiciones = $objRce->pacienteCumpleCondicionesSolicitudAPS($objCon, $idDau);
	if (! empty($pacienteCumpleCondiciones[0]) && ! is_null($pacienteCumpleCondiciones[0])) {
		return true;
	}

	return false;
}



function pacienteConSolicitudAPS ( $objCon, $objRce, $idDau ) {
	$pacienteConSolicitudAPS = $objRce->pacienteConSolicitudAPS($objCon, $idDau);
	if ( ! empty($pacienteConSolicitudAPS) && ! is_null($pacienteConSolicitudAPS) ) {
		return true;
	}
	return false;
}



function trasladoPaciente ( $objCon, $objUtil, $idDau, $objAdmision, $objCMBD ) {

	$parametros = readmisionarPaciente($objCon, $objUtil, $idDau, $objAdmision, $objCMBD);
	// echo "traslado";
	insertarCuentaCorriente($objCon, $objUtil, $parametros);

}



function readmisionarPaciente ( $objCon, $objUtil, $idDau, $objAdmision, $objCMBD ) {

	$datosDau                                        = $objAdmision->obtenerDatosDauCerrado($objCon, $idDau);
	$horarioServidor 								 = $objUtil->getHorarioServidor($objCon);
	$parametros                                      = array();
	$parametros['est_id']                            = 1;
	$parametros['id_paciente']                       = $datosDau['id_paciente'];
	$parametros['idctacte']                          = 0;
	$parametros['frm_fechaAdmision']                 = date($horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora']);
	$parametros['dau_admision_usuario']              = $_SESSION['MM_Username'.SessionName];
	$parametros['dau_paciente_domicilio']            = $datosDau['dau_paciente_domicilio'];
	$parametros['dau_paciente_domicilio_tipo']       = $datosDau['dau_paciente_domicilio_tipo'];
	$parametros['dau_paciente_edad']                 = $datosDau['dau_paciente_edad'];
	$parametros['dau_paciente_prevision']            = $datosDau['dau_paciente_prevision'];
	$parametros['dau_paciente_forma_pago']           = $datosDau['dau_paciente_forma_pago'];
	$parametros['dau_motivo_consulta']               = $datosDau['dau_motivo_consulta'];
	$parametros['dau_atencion']                      = cambiarTipoAtencion($datosDau['ind_egr_id']);
	$parametros['dau_forma_llegada']                 = $datosDau['dau_forma_llegada'];
	$parametros['dau_mordedura']                     = $datosDau['dau_mordedura'];
	$parametros['dau_intoxicacion']                  = $datosDau['dau_intoxicacion'];
	$parametros['dau_quemadura']                     = $datosDau['dau_quemadura'];
	$parametros['dau_imputado']                      = $datosDau['dau_imputado'];
	$parametros['dau_reanimacion']                   = $datosDau['dau_reanimacion'];
	$parametros['dau_conscripto']                    = $datosDau['dau_conscripto'];
	$parametros['dau_motivo_descripcion']            = $datosDau['dau_motivo_descripcion'];
	$parametros['dau_tipo_accidente']                = $datosDau['dau_tipo_accidente'];
	$parametros['dau_accidente_escolar_institucion'] = $datosDau['dau_accidente_escolar_institucion'];
	$parametros['dau_accidente_escolar_numero']      = $datosDau['dau_accidente_escolar_numero'];
	$parametros['dau_accidente_escolar_nombre']      = $datosDau['dau_accidente_escolar_nombre'];
	$parametros['dau_accidente_trabajo_mutualidad']  = $datosDau['dau_accidente_trabajo_mutualidad'];
	$parametros['dau_accidente_transito_tipo']       = $datosDau['dau_accidente_transito_tipo'];
	$parametros['dau_accidente_hogar_lugar']         = $datosDau['dau_accidente_hogar_lugar'];
	$parametros['dau_accidente_otro_lugar']          = $datosDau['dau_accidente_otro_lugar'];
	$parametros['dau_agresion_vif']                  = $datosDau['dau_agresion_vif'];
	$parametros['id_doc_documentoDau']               = $datosDau['id_doc_documentoDau'];
	$parametros['frm_centroAtencion']                = $datosDau['frm_centroAtencion'];
	$parametros['frm_rut']                           = $datosDau['frm_rut'];
	$parametros['frm_tipo_choque']                   = $datosDau['frm_tipo_choque'];
	$parametros['dau_tipo_mordedura']                = $datosDau['dau_tipo_mordedura'];
	$parametros['dau_cierre_condicion_ingreso_id']   = $datosDau['dau_cierre_condicion_ingreso_id'];
	$parametros['dau_tipo_admision']                 = $datosDau['dau_tipo_admision'];
	$parametros['dau_paciente_trasladado']           = 'S';
	$idDau                    		                 = $objAdmision->agregarAdmision($objCon, $parametros);
	$respuesta['dau_id']                             = $idDau;
	$respuesta['tipoAtencion']                       = $parametros['dau_atencion'];
	//CMBD ADMSIÓN
	$objCMBD->iniciarCMBD($objCon, $respuesta["dau_id"], 1);
	unset($parametros);
	return $respuesta;

}



function insertarCuentaCorriente ( $objCon, $objUtil, $parametros ) {

	// require_once("../../../class/Conectar.inc");		       	$objCon 			= new Conectar; $objCon->db_connect();
	// require_once("../../../../../RecNet_2.0/clases/CtaCte.inc"); 	$objCtaCte   	= new CtaCte;
	require_once("../../../class/Admision.class.php"); 			$objAdmision 		= new Admision;
	require_once("../../../class/Movimiento.class.php");		$objMovimiento  	= new Movimiento;
	require_once("../../../class/Dau.class.php");		   		$objDau 			= new Dau;
	require_once("../../../class/Paciente.class.php");          $objPaciente        = new Paciente;
	// require_once("../../../../../indice_paciente_2017/class/Paciente.class.php"); 		$objPac           	= new Paciente;

	$datos                    			= $objAdmision->listarDatosDau($objCon, $parametros);

    $numeroDocumento = $datos[0]["rut"];

	if ( $datos[0]["extranjero"] == "S" ) {

		$numeroDocumento = $datos[0]['rut_extranjero'];

	}

	$respuesta2               			= $objAdmision->nuevaCtaCte($objCon,$datos[0]["id_paciente"],$numeroDocumento,$datos[0]["dau_admision_fecha"],10322,$datos[0]["dau_paciente_prevision"],$datos[0]["dau_id"],1,$parametros['dau_paciente_forma_pago']);
	$parametros['ctaCte']     			= $respuesta2;
	$respuesta3               			= $objAdmision->ActualizarCtaCorriente($objCon, $parametros);
	$parametros['dau_mov_descripcion'] 	= 'admision dau';
	$parametros['dau_mov_tipo'] 		= 'adm';
	$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];

	// $objCon->setDB("recauda");

	$arrConv = [1 => '7', 2 => '8', 3 => '9', 4 => '10', 5 => '11'];
	$parametros3 = [];
	$parametros3['det_pre_cta_cte']      = $parametros['ctaCte'];
	$parametros3['det_pre_usuario']      = $parametros['dau_mov_usuario'];
	$parametros3['det_pre_rut_paciente'] = $datos[0]["id_paciente"];
	$parametros3['det_pre_cantidad']     = '1';
	$parametros3['det_pre_cod_sscc']     = '10322';
	$parametros3['det_pre_valor_unit']   = '0';

	
	$parametros3['det_pre_codigo'] = '0101103';
	

	$objAdmision->addPrestacionAdmisionPaciente($objCon, $parametros3);

	if ($parametros['frm_prevision'] < 4) {
		$subparametros['frm_id_paciente'] = $datos[0]["id_paciente"];
		$datosPaciente = $objPaciente->listarPaciente($objCon, $subparametros);
		$nrFichaPac = $datosPaciente[0]['nroficha'];
		$parametros['frm_prevision'] = $datosPaciente[0]['prevision'];
		$parametros['frm_formaPago'] = $datosPaciente[0]['conveniopago'];
		$resp_vp = $objDau->valorPrestacion($objCon);
		$resp_np = $objDau->nombrePrevision($objCon, $parametros);
		$resp_ni = $objDau->nombreInstitucion($objCon, $parametros);
		$parametros4 									= array();
		$parametros4['matrizCodPrestacion'] 			= '0101103';
		$parametros4['matrizTipoPrestacionValorada'] 	= 'I';
		$parametros4['matrizCodPrograma'] 				= 0;
		$parametros4['matrizCantPrestacion'] 			= 1;
		$parametros4['matrizNombrePrestacion'] 			= 'Consulta médica integral en servicio de urgencia (Hosp. tipo 1)';
		$parametros4['matrizTipoPrestacion']	 		= 'P';
		$parametros4['matrizCodPatologia'] 				= 0;
		$parametros4['matrizNombrePatologia'] 			= '';
		$parametros4['matrizValorPrestacion'] 			= $resp_vp[0]['preFacturacion'];
		$parametros4['matrizPacieCod'] 					= $datos[0]["id_paciente"];
		$parametros4['matrizRutPaciente'] 				= $numeroDocumento;
		$parametros4['matrizNombrePacie'] 				= $datosPaciente[0]['nombres']." ".$datosPaciente[0]['apellidopat']." ".$datosPaciente[0]['apellidomat'];
		$parametros4['matrizFichaPacie'] 				= $nrFichaPac;
		$parametros4['matrizSexoPacie'] 				= $datosPaciente[0]['sexo'];
		$parametros4['matrizFNacPacie'] 				= $datosPaciente[0]['fechanac'];
		$parametros4['matrizPreviCod'] 					= $datosPaciente[0]['prevision'];
		$parametros4['matrizConvenio'] 					= $datosPaciente[0]['conveniopago'];
		$parametros4['matrizCodServicio'] 				= 10322;
		$parametros4['matrizNomServicio'] 				= 'urgencia';
		$parametros4['matrizFRegPrestacion'] 			= date('Y-m-d');
		$parametros4['matrizFDigitacion'] 				= '';
		$parametros4['matrizOrigenAtPrestacion'] 		= 'Urgencia';
		$parametros4['matrizTablaOrigen'] 				= 'dau';
		$parametros4['matrizCantidadComprometida'] 		= 0;
		// $parametros4['matrizEdadPaciente'] 				= $objUtil->edadActual(date('Y-m-d'));
		$parametros4['matrizEdadPaciente'] 				= $objUtil->edadActual(date('Y-m-d', strtotime($parametros['frm_fechaAdmision'])));
		$parametros4['matrizPreviNombre'] 				= $resp_np[0]['prevision'];
		$parametros4['matrizConvenioNombre'] 			= $resp_ni[0]['instNombre'];
		$parametros4['matrizNombrePrograma'] 			= '';
		$parametros4['matrizNombreCompromiso'] 			= '';
		$parametros4['matrizCodCompromiso'] 			= 0;
		$parametros4['matrizCodSistema'] 				= $parametros['dau_id'];
		$parametros4['matrizTipoSistema'] 				= 'rau';
		$parametros4['matrizUsuario'] 					= $_SESSION['MM_Username'.SessionName];
		$parametros4['dau_tipo_admision'] 				= $parametros['tipoAtencion'];
		$objDau->registrarPacAdmisionadoMatrizEstadistica($objCon, $parametros4);

	}
	// $objCon->setDB("dau");
	$objMovimiento->guardarMovimiento($objCon, $parametros);

}



function cambiarTipoAtencion ( $tipoIndicacionEgreso ) {

	$tipoAtencion = 0;

	switch ( $tipoIndicacionEgreso ) {

		case 8:
			$tipoAtencion = 3;
		break;

		case 9:
			$tipoAtencion = 1;
		break;

		case 10:
			$tipoAtencion = 2;
		break;

	}

	return $tipoAtencion;

}

$objCon = null;
?>
