<?php
session_start();
error_reporting(0);
require("../../../config/config.php");
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

switch ($_POST['accion']) {
	case 'updateSalaCamaAddPaciente':
		$parametros = $objUtil->getFormulario($_POST);
		$response;
		try {
			$objCon->beginTransaction();
			$resp 		= $objMapaPiso->getEstadoPacienteDAU($objCon, $parametros);
			$tipoLista 	= '';
			$message 	= '';
			switch ( $parametros['lista'] ) {
				case 'listaEspera':
					$tipoLista = '1';
					$message = "El paciente ya no se encuentra en la lista de 'Espera'.";
				break;
				case 'listaCategorizados':
					$tipoLista = '2';
					$message = "El paciente ya no se encuentra en la lista de 'Categorizados'.";
				break;
				default:
					$message = "El paciente no se encuentra en la lista.";
				break;
			}
			if ( $tipoLista == $resp[0]['est_id'] ) {
				$parametros['num_salaDest'] = $parametros['salaDest_id'];
				$resp2 						= $objMapaPiso->getEstadoCamaDestino($objCon, $parametros);
				if ( $resp2[0]['est_id'] == '10' ) {
					$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];
					$objMapaPiso->updateDatasPacienteCatInDAU($objCon, $parametros);
					$objMapaPiso->updateSalaUEDestino($objCon, $parametros);
					$parametrosGM = array("dau_id" => $_POST['id_dau'], "dau_mov_descripcion" => "ingreso a cama", "dau_mov_usuario" => $parametros['dau_mov_usuario'], "dau_mov_tipo" => "ing");
					$objMovimiento->guardarMovimiento($objCon, $parametrosGM);
					$parametrosMPC 	= array("sal_id" => $parametros['num_salaDest'], "cam_descripcion" => $parametros['cama']);
					$resp_camid 	= $objMapaPiso->getIdCama($objCon, $parametrosMPC);
					$parametrosGMC 	= array("dau_id" => $_POST['id_dau'], "cam_id" => $resp_camid[0]['cam_id'], "sal_id" => $parametros['num_salaDest'], "sal_descripcion" => $parametros['sala'], "cam_descripcion" => $parametros['cama'], "dau_mov_cama_usuario_ingreso" =>  $dau_mov_usuario, "dau_mov_cama_estado" => "enCama");
					$objMovimiento->guardarMovimientoCama($objCon, $parametrosGMC);
					$response = array("status"=>"success");
				} else {
					$response = array("status"=>"error", "message"=>"La cama de destino se encuentra ocupada.");
				}
			} else {
				if ( $tipoLista == '1' ) {
					$response = array("status"=>"error", "message"=>$message);
				} else if ( $tipoLista == '2' ) {
					$response = array("status"=>"error", "message"=>$message);
				} else {
					$response = array("status"=>"error", "message"=>$message);
				}
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case 'updateSalaCamaMovPaciente':
		$parametros = $objUtil->getFormulario($_POST);
		$response;
		$message 	= '';
		try {
			$objCon->beginTransaction();
			$resp 							= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
			$parametros['num_salaOrig'] 	= $parametros['salaOrig_id'];
			if ( ($parametros['num_salaOrig'] == $resp[0]['sal_id']) && ($parametros['camaOrig'] == $resp[0]['cam_descripcion']) ) {
				$parametros['num_salaDest'] = $parametros['salaDest_id'];
				$resp2 						= $objMapaPiso->getEstadoCamaDestino($objCon, $parametros);
				if ( $resp2[0]['est_id'] == '10' ) {
					$objMapaPiso->updateSalaUEOrig($objCon, $parametros);
					$objMapaPiso->updateSalaUEDestino($objCon, $parametros);
					$dau_mov_usuario 		= $_SESSION['MM_Username'.SessionName];
					$parametrosGM 			= array("dau_id" => $_POST['id_dau'], "dau_mov_descripcion" => "traslado de cama", "dau_mov_usuario" => $dau_mov_usuario, "dau_mov_tipo" => "tra");
					$objMovimiento->guardarMovimiento($objCon, $parametrosGM);
					$parametrosUIC 			= array("dau_id" => $_POST['id_dau'], "dau_mov_cama_usuario_egreso" =>  $dau_mov_usuario, "dau_mov_cama_estado" => "egresadoCama");
					$resp_ultimoIdCama 		= $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
					$parametrosUIC['id_ultimoMovCam'] = $resp_ultimoIdCama[0]['id'];
					$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);
					$parametrosMPC 			= array("sal_id" => $parametros['num_salaDest'], "cam_descripcion" => $parametros['cama']);
					$resp_camid 			= $objMapaPiso->getIdCama($objCon, $parametrosMPC);
					$parametrosGMC 			= array("dau_id" => $_POST['id_dau'], "cam_id" => $resp_camid[0]['cam_id'], "sal_id" => $parametros['num_salaDest'], "sal_descripcion" => $parametros['sala'], "cam_descripcion" => $parametros['cama'], "dau_mov_cama_usuario_ingreso" =>  $dau_mov_usuario, "dau_mov_cama_estado" => "enCama");
					$objMovimiento->guardarMovimientoCama($objCon, $parametrosGMC);
					$response 				= array("status"=>"success");
				} else {
					$message 				= "<strong>La cama de destino se encuentra ocupada.</strong>";
					$response 				= array("status"=>"error", "message"=>$message);
				}
			} else {
				$message = "El paciente que usted esta moviendo, ya no se encuentra en: <br>- Sala: <strong>".$parametros['salaOrig']."</strong><br>- Cama: <strong>".$parametros['camaOrig']."</strong>";
				$response = array("status"=>"error", "message"=>$message);
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);

		}

	break;
	case 'aplicarNEAClinico':

		$objCon->db_connect();
		$parametros =  $objUtil->getFormulario($_POST);

		try{
			$objCon->beginTransaction();
			$parametros['dau_mov_usuario'] 					= $_SESSION['MM_Username'.SessionName];
			$rsFechaHora     								= $objUtil->getHorarioServidor($objCon);
			if ( !empty($parametros['banderaAdministrativo']) && ! is_null($parametros['banderaAdministrativo']) && $parametros['banderaAdministrativo'] == 'S' ) {
				$parametros['dau_cierre_administrativo'] 	= "S";
			} else {
				$parametros['dau_cierre_administrativo'] 	= "C";
			}
			$parametros['radio_egreso']              		= 7;
			$parametros['frm_motivo_egreso']         		= $parametros['frm_txt_apliNEA'];
			$parametros['reg_usuario_insercion']     		= $parametros['dau_mov_usuario'];
			$parametros['frm_fecha_egreso_adm']      		= $parametros['frm_fecha_aNEA'].' '.$parametros['frm_hora_aNEA'];
			$parametros['fecha_cierre_final']        		= $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora'];
			$parametros['Iddau']                     		= $parametros['idDau'];
			$parametros['dau_mov_descripcion'] 		 		= "cierre dau";
			$parametros['dau_mov_tipo']              		= "cie";
			$parametros['frm_est_id']          		 		= $parametros['est_id'];
			$parametros['id_dau'] 					 		= $parametros['idDau'];
			$resultadoCama 									= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
			$nombreSala 									= $resultadoCama[0]['sal_resumen'].'_'.$resultadoCama[0]['cam_descripcion'];
			$numeroCama 									= $resultadoCama[0]['cam_id'];
			$resp 											= $objDau->cierreAdministrativoDAU($objCon, $parametros);
			$parametrosMP['id_dau']   						= $parametros['Iddau'];
			$respMP                   						= $objMapaPiso->getLugarPaciente_clinico($objCon, $parametrosMP);
			$parametros['dau_id'] 							= $parametros['id_dau'];
			if ( count($respMP) > 0 ) {
				$objDau->vaciarCamaCierre($objCon, $parametros);
				$parametrosUIC                    			= array("dau_id" => $parametros['id_dau'], "dau_mov_cama_usuario_egreso" =>  $parametros['dau_mov_usuario'], "dau_mov_cama_estado" => "egresadoCama");
				$resp_ultimoIdCama                			= $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
				$parametrosUIC['id_ultimoMovCam'] 			= $resp_ultimoIdCama[0]['id'];
				$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);
				$response  	= array("status" => "success", "id" =>$parametros['id_dau'], "idSalaCama" => $nombreSala, "numeroCama" => $numeroCama);
			} else {
				$response 	= array("status" => "success", "id" =>$parametros['id_dau']);
			}

			#endregion

			/**
			 * ENVIO DE MENSAJE AL PYXIS
			 * [A11] : CANCELACION DE ADMISION
			 */

			require_once("../../../../integracion/grifols/pyxis/class/Grifols.class.php"); $objGrifols      = new Grifols;
			require_once("../../../class/PacienteDau.class.php");   $objPacienteDAU   = new PacienteDAU;

			$rsp_paciente_dau 		= $objPacienteDAU -> obtenerDatosPacienteDau($objCon, $parametros['dau_id']);
			$rsp_pacint 			= $objPacienteDAU -> obtenerInformacionPaciente($objCon, $rsp_paciente_dau['id_paciente']);
			$rut_completo 			= $rsp_paciente_dau['rut'] . "-" . $objUtil->generaDigito($rsp_paciente_dau['rut']);
			$fechaNaciminto 		= date('Ymd', strtotime($rsp_paciente_dau['fechanac']));
			$parametros['servicio'] = 10322;

			$parametros_ws_pyxis['log_wspy_sistemaEnviaMensaje'] 	= 2;
			$parametros_ws_pyxis['log_wspy_idDau'] 					= $rsp_paciente_dau['dau_id'];
			$parametros_ws_pyxis['CodigoMensaje'] 					= 'A11';
			$parametros_ws_pyxis['FechaMensaje'] 					= date('YmdHis');
			$parametros_ws_pyxis['IdMensaje'] 						= '';
			$parametros_ws_pyxis['CodigoCentro'] 					= '';
			$parametros_ws_pyxis['IdPaciente'] 						= $rut_completo;
			$parametros_ws_pyxis['IdAltPaciente'] 					= $rsp_paciente_dau['id_paciente'];
			$parametros_ws_pyxis['ApPaterno'] 						= $rsp_paciente_dau['apellidopat'];
			$parametros_ws_pyxis['ApMaterno'] 						= $rsp_paciente_dau['apellidomat'];
			$parametros_ws_pyxis['Nombres'] 						= $rsp_paciente_dau['nombres'];
			$parametros_ws_pyxis['Sexo'] 							= $rsp_paciente_dau['sexo'];
			$parametros_ws_pyxis['FechaNacimeinto'] 				= $fechaNaciminto;
			$parametros_ws_pyxis['UnidadEnfermeria'] 				= $parametros['servicio'];
			$parametros_ws_pyxis['Sala'] 							= '';
			$parametros_ws_pyxis['Cama'] 							= '';
			$parametros_ws_pyxis['idCentro'] 						= '';
			$parametros_ws_pyxis['IdMedico'] 						= '';
			$parametros_ws_pyxis['MedicoApPaterno'] 				= '';
			$parametros_ws_pyxis['MedicoApMaterno'] 				= '';
			$parametros_ws_pyxis['MedicoNombres'] 					= '';
			$parametros_ws_pyxis['IdEpisodio'] 						= $rsp_paciente_dau['idctacte'];
			$parametros_ws_pyxis['FechaAdmision'] 					= date('YmdHis', strtotime($rsp_paciente_dau['dau_admision_fecha']));
			$parametros_ws_pyxis['FechaAlta'] 						= '';
			$parametros_ws_pyxis['Observacion'] 					= 'CANCELACION DE ADMISION DEL PACIENTE EN URGENCIA';
			$con = $objGrifols->requestWS_grifols($objCon, $parametros_ws_pyxis);
			if($con['status'] == 'success'){
				$status_pyxis = $con['status'];
			}
			else{
				$status_pyxis = $con['status'];
				$message_pyxis = $con['message'];
			}
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 6);
			$objCon->commit();
			echo json_encode($response);

		} catch(PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}

	break;
	case 'pacienteTieneAltaUrgencia':
		// $idDau 							= $_POST['idDau'];
		// $existeSolicitudAltaUrgencia 	= $objDau->existeSolicitudAltaUrgencia($objCon, $idDau);
		// if ( ! is_null($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && ! empty($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) ) {
		// 	$respuesta = array("status" => "success");
		// } else {
			$respuesta = array("status" => "error");
		// }
		echo json_encode($respuesta);
	break;

	case 'pacienteYaConNEA':
		
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

	case 'ingresarLlamado' :
		
		$parametros =  $objUtil->getFormulario($_POST);
		try {
			$objCon->beginTransaction();
			$rsFechaHora                                = $objUtil->getHorarioServidor($objCon);
			$baseDatos                                  = "dau.tablallamadonea";

			$bdllamado 				= $_POST['bdllamado'];
			$usuarioLlamada 		= $_POST['usuarioLlamada'];
			$parametrosScript[''.$bdllamado.""] 		= $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora'];
			$parametrosScript[''.$usuarioLlamada.""]	= $_SESSION['MM_Username'.SessionName];
			if($bdllamado == "fechaPrimerLlamado"){
				$parametrosScript['idDau'] 					= $parametros['idDau'];
				$BITcodigo 									= $objSqlDinamico->construirInsert($objCon, $baseDatos, $parametrosScript);
			}else{
				$parametrosScriptCondiciones['idDau']  		= "idDau = ".$parametros['idDau'];
				$objSqlDinamico->construirUpdate($objCon, $baseDatos, $parametrosScript,$parametrosScriptCondiciones);
			}

			$objCon->commit();
			$respuesta = array("status" => "success", "fechaHora" => $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora']);
			echo json_encode($respuesta);
		} catch ( PDOException $e ) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;

	case 'registrarSVITAL':
		

		$parametros	= $objUtil->getFormulario($_POST);
		if($parametros['rce_idSV'] == null){
			$parametros['dau_id'] 						= $parametros['dau_idSV'];
			$resp 										= $objDau -> SelectDau($objCon,$parametros);
			$parametrosEvento['dau_id'] 				= $parametros['dau_idSV'];
			$parametrosEvento['paciente_id']			= $resp[0]['id_paciente'];
			$parametrosEvento['origen']		   			= 3;
			$parametrosEvento['estadoEve']       		= 1;
			$parametrosEvento['usuarioEve'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosEvento['intCodigo']	   			= '';
			$parametrosEvento['estadoRCE']	   			= 1;
			$parametrosEvento['tipoAtencionPaciente'] 	= '';
			if ( $resp[0]["dau_atencion"] == 1 ) {
				$parametrosEvento['tipoAtencionPaciente'] = 'DA';
			}
			if ( $resp[0]["dau_atencion"] == 2 ) {
				$parametrosEvento['tipoAtencionPaciente'] = 'DP';
			}
			if ( $resp[0]["dau_atencion"] == 3 ) {
				$parametrosEvento['tipoAtencionPaciente'] = 'DG';
			}

			$eve_id 						= $objDau -> consultaEvento($objCon,$parametros);
			$parametros['dau_mov_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			if ( count($eve_id) > 0 ) {
				$parametros['evento_id'] = $eve_id[0]['eveId'];
				$parametros['rce_idSV']	 = $objRegistroClinico->obtenerRCEIDSegunEvento($objCon, $parametros);
			} else {
				$eve_id 					= $objDau -> crearEvento($objCon,$parametrosEvento);
				$parametros['evento_id'] 	= $eve_id;
				$parametros['rce_idSV'] 	= $objRegistroClinico -> insertaRCE($objCon,$parametros);
			}

		}
		$subparametrosBitacoraIndicaciones['BITdescripcionTitulo']	= "<b>SIGNOS VITALES</b> : ";
		$parametros['usuario'] 			= $_SESSION['MM_Username'.SessionName];
		$parametros['idPaciente'] 		= $parametros['id_paciente'];

		$descripciones 					= array();
		if (!$parametros['sistolicaCiclo'] == "") {
		    $descripciones[] = "SISTOLICA (" . $parametros['sistolicaCiclo'] . ")";
		}
		if (!$parametros['diastolicaCiclo'] == "") {
		    $descripciones[] = "DISTOLICA (" . $parametros['diastolicaCiclo'] . ")";
		}
		if (!$parametros['pulsoCiclo'] == "") {
		    $descripciones[] = "PULSO (" . $parametros['pulsoCiclo'] . ")";
		}
		if (!$parametros['glasgowCiclo'] == "") {
		    $descripciones[] = "GLASGOW (" . $parametros['glasgowCiclo'] . ")";
		}
		if (!$parametros['temperaturaCiclo'] == "") {
		    $descripciones[] = "TEMPERATURA (" . $parametros['temperaturaCiclo'] . ")";
		}
		if (!$parametros['signosVitalesFetales'] == "") {
		    $descripciones[] = "LCF (" . $parametros['signosVitalesFetales'] . ")";
		}
		if (!$parametros['rbne'] == "" && $parametros['rbne'] != "null") {
		    $descripciones[] = "RBNE (" . $parametros['rbne'] . ")";
		}
		if (!$parametros['saturacionCiclo'] == "") {
			$descripciones[] = "SATURACION OXIGENO (" . $parametros['saturacionCiclo'] . ")";
		}
		if (!$parametros['frCiclo'] == "") {
		    $descripciones[] = "FR (" . $parametros['frCiclo'] . ")";
		}
		if (!$parametros['hemoglucoTest'] == "") {
		    $descripciones[] = "HEMOGLUCOTEST (" . $parametros['hemoglucoTest'] . ")";
		}
		if ( !$parametros['evaCiclo'] == "") {
		    $descripciones[] = "EVA (" . $parametros['evaCiclo'] . ")";
		}
		if (!$parametros['pesoCiclo'] == "") {
		    $descripciones[] = "PESO (" . $parametros['pesoCiclo'] . ")";
		}
		if (!$parametros['tallaCiclo'] == "") {
		    $descripciones[] = "TALLA (" . $parametros['tallaCiclo'] . ")";
		}
		if ( !$parametros['pamCiclo'] == "") {
		    $descripciones[] = "PAM (" . $parametros['pamCiclo'] . ")";
		}
		if ( !$parametros['Fio2'] == "") {
		    $descripciones[] = "Fio2 (" . $parametros['Fio2'] . ")";
		}
		$subparametrosBitacoraIndicaciones['BITdescripcion'] = implode(" , ", $descripciones);

		try {

			$objCon->beginTransaction();
			$rsFechaHora                                = $objUtil->getHorarioServidor($objCon);

			$baseDatos                                  = "rce.signo_vital";
			$parametrosScript1['SVITALfecha']           = $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora']; 
			$parametrosScript1['idRCE']                 = $parametros['rce_idSV']; 
			$parametrosScript1['idPaciente']            = $parametros['idPaciente']; 
			$parametrosScript1['SVITALpulso']         = isset($parametros['pulsoCiclo']) ? $parametros['pulsoCiclo'] : NULL; 
			$parametrosScript1['SVITALsistolica']     = isset($parametros['sistolicaCiclo']) ? $parametros['sistolicaCiclo'] : NULL; 
			$parametrosScript1['SVITALdiastolica']    = isset($parametros['diastolicaCiclo']) ? $parametros['diastolicaCiclo'] : NULL; 
			$parametrosScript1['SVITALPAM']           = isset($parametros['pamCiclo']) ? $parametros['pamCiclo'] : NULL; 
			$parametrosScript1['SVITALtemperatura']   = isset($parametros['temperaturaCiclo']) ? $parametros['temperaturaCiclo'] : NULL; 
			$parametrosScript1['SVITALsaturacion']    = isset($parametros['saturacionCiclo']) ? $parametros['saturacionCiclo'] : NULL; 
			$parametrosScript1['SVITALfr']            = isset($parametros['frCiclo']) ? $parametros['frCiclo'] : NULL; 
			$parametrosScript1['SVITALfc']            = isset($parametros['frm_svital_fc']) ? $parametros['frm_svital_fc'] : NULL; 
			$parametrosScript1['SVITALglasgow']       = isset($parametros['glasgowCiclo']) ? $parametros['glasgowCiclo'] : NULL; 
			$parametrosScript1['SVITALeva']           = isset($parametros['evaCiclo']) ? $parametros['evaCiclo'] : NULL;
			$parametrosScript1['Fio2']                = isset($parametros['Fio2']) ? $parametros['Fio2'] : NULL;
			$parametrosScript1['SVITALusuario']       = $parametros['usuario']; 
			$parametrosScript1['SVITALpeso']          = isset($parametros['pesoCiclo']) ? $parametros['pesoCiclo'] : $parametros['pesoCiclohidden']; 
			$parametrosScript1['SVITALtalla']         = isset($parametros['tallaCiclo']) ? $parametros['tallaCiclo'] : $parametros['tallaCiclohidden']; 
			$parametrosScript1['SVITALhemoglucoTest'] = isset($parametros['hemoglucoTest']) ? $parametros['hemoglucoTest'] : NULL; 
			$parametrosScript1['SVITALfeto']          = isset($parametros['signosVitalesFetales']) ? $parametros['signosVitalesFetales'] : NULL; 
			$parametrosScript1['SVITALrbne']          = isset($parametros['rbne']) ? $parametros['rbne'] : NULL;

			// print('<pre>'); print_r($parametrosScript1); print('</pre>');
			// parametrosScript1
			$SVITALid 									= $objSqlDinamico->construirInsert($objCon, $baseDatos, $parametrosScript1);

			$baseDatos                                  = "dau.dau_movimiento";			
			$parametrosScript2['dau_id'] 				= $parametros['dau_idSV'];
			$parametrosScript2['dau_mov_descripcion'] 	= 'registro signos vitales';
			$parametrosScript2['dau_mov_fecha'] 		= $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora'];
			$parametrosScript2['dau_mov_usuario'] 		= $parametros['usuario'];
			$parametrosScript2['dau_mov_tipo'] 			= 'rsv';
			$parametrosScript2['ip'] 					= $_SERVER['REMOTE_ADDR'];
			$dau_mov_id 								= $objSqlDinamico->construirInsert($objCon, $baseDatos, $parametrosScript2);

			$baseDatos                                  = "rce.bitacora";
			$parametrosScript3['BITid'] 				= $parametros['dau_idSV'];
			$parametrosScript3['BITtipo_codigo'] 		= 1;
			$parametrosScript3['BITtipo_descripcion'] 	= "Signos vitales";
			$parametrosScript3['BITdatetime'] 			= $rsFechaHora[0]['fecha']." ".$rsFechaHora[0]['hora'];
			$parametrosScript3['BITusuario'] 			= $parametros['usuario'];
			$parametrosScript3['BITdescripcion'] 		= $subparametrosBitacoraIndicaciones['BITdescripcionTitulo']." ".$subparametrosBitacoraIndicaciones['BITdescripcion'].".";
			$BITcodigo 									= $objSqlDinamico->construirInsert($objCon, $baseDatos, $parametrosScript3);


			$objCon->commit();
			$response = array("status" => "success", "id" => $parametros['dau_idSV']);
			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);

		}

	break;
	case 'guardarMovimientoQuienVioDAU':

		$objCon->db_connect();
		$parametros 								= $objUtil->getFormulario($_POST);
		$parametrosAEnviar['dau_id'] 				= $parametros['dau_id'];
		$parametrosAEnviar['dau_mov_descripcion'] 	= 'ver detalle dau';
		$parametrosAEnviar['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];;
		$parametrosAEnviar['dau_mov_tipo'] 			= 'vdd';
		$objMovimiento->guardarMovimiento($objCon, $parametrosAEnviar);

	break;
	case "eventoRCE":
		$parametros	= $objUtil -> getFormulario($_POST);

		$resp 								= $objDau -> SelectDau($objCon,$parametros);
		$parametros['paciente_id']			= $resp[0]['id_paciente'];
		$parametros['origen']		   		= 3;
		$parametros['estadoEve']       		= 1;
		$parametros['usuarioEve'] 			= $_SESSION['MM_Username'.SessionName];
		$parametros['intCodigo']	   		= '';
		$parametros['estadoRCE']	   		= 1;
		$parametros['tipoAtencionPaciente'] = '';
		if ( $resp[0]["dau_atencion"] == 1 ) {
			$parametros['tipoAtencionPaciente'] = 'DA';
		}
		if ( $resp[0]["dau_atencion"] == 2 ) {
			$parametros['tipoAtencionPaciente'] = 'DP';
		}
		if ( $resp[0]["dau_atencion"] == 3 ) {
			$parametros['tipoAtencionPaciente'] = 'DG';
		}

		try {
			$objCon->beginTransaction();
			$eve_id 						= $objDau -> consultaEvento($objCon,$parametros);
			$parametros['dau_mov_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			if ( count($eve_id) > 0 ) {
				$parametros['evento_id'] = $eve_id[0]['eveId'];
				$parametros['rce_idSV']	 = $objRegistroClinico->obtenerRCEIDSegunEvento($objCon, $parametros);
			} else {
				$eve_id 					= $objDau -> crearEvento($objCon,$parametrosEvento);
				$parametros['evento_id'] 	= $eve_id;
				$parametros['rce_idSV'] 	= $objRegistroClinico -> insertaRCE($objCon,$parametros);
			}


			$objCon->commit();
			$response = array("status" => "success", "id" => $parametros['dau_id'], "rce_id" => $parametros['rce_idSV']);
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case 'pacienteSigueCamaOrg':
		$parametros = $objUtil->getFormulario($_POST);
		$resp	 	= $objMapaPiso->getPacienteSigueCamaOrg($objCon, $parametros);
		// print('<pre>'); print_r($resp); print('</pre>');
		$response	= array();
		if ( ($parametros['num_salaOrig'] == $resp[0]['sal_id']) && ($parametros['camaOrig'] == $resp[0]['cam_descripcion']) ) {
			$response = array("status"=>"success");
		} else {
			$response = array("status"=>"error");
		}
		echo json_encode($response);
	break;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////





















	case 'crearDivPacienteEnCama':

		

		require_once("../../../class/MapaPiso.class.php");		  $objMapaPiso        = new MapaPiso;
		require_once('../../../class/Categorizacion.class.php');  $objCat 			  = new Categorizacion;
		require_once('../../../class/RegistroClinico.class.php'); $objRegistroClinico = new RegistroClinico;


		if ( $_POST['tipoPaciente'] == 'adulto' || $_POST['tipoPaciente'] == 1 ) {

			$tipoPaciente = 'A';

		} else if ( $_POST['tipoPaciente'] == 'pediatrico' || $_POST['tipoPaciente'] == 2 ) {

			$tipoPaciente = 'P';

		} else if ( $_POST['tipoPaciente'] == 'ginecologico' || $_POST['tipoPaciente'] == 3 ){

			$tipoPaciente = 'GO';

		}

		$resp 		  = $objMapaPiso->loadCamas($objCon, $_POST['id_dau']);
		$consultaAlta = $objCat->listar_alta_hogar($objCon,$_POST['id_dau']);



		$css_ti_aapli = colorTiempo($resp[0]['dau_indicacion_egreso_fecha'], $resp[0]['dau_inicio_atencion_fecha'], $resp[0]['dau_indicaciones_completas'], $resp[0]['FechaActual']);
		$cat 		  = categorizacion($resp[0]['cat_nombre_mostrar'], $resp[0]['cat_nivel']);
		$reloj 	      = tiempoEsperaDesdeCategorizacion($resp[0]['dau_id'], $resp[0]['dau_inicio_atencion_fecha'], $resp[0]['dau_categorizacion_actual_fecha'], $resp[0]['cat_nombre_mostrar'], $resp[0]['cat_tiempo_alerta']);
		$covid        = examenCovid($resp[0]['id_paciente']);


		$html .= '<div hidden id="contTECAM_'.$resp[0]['dau_id'].'" class="contadorTEspCamas"></div>';
		$html .= '<input class="css_border" type="hidden" value="'.$css_ti_aapli.'"/>';
		$html .= $cat.$reloj.$covid;
		$html .= '<span class="text-upleft-nombre" style="opacity: 0 !important;">'.$resp[0]["dau_id"].' '.$resp[0]["nombres"].' '.$resp[0]["apellidopat"].' '.$resp[0]["apellidomat"].'</span>';

		$html .= '<input class="hidden" type="hidden" value="'.$resp[0]['dau_id'].'"/>';
		$html .= '<input type="hidden" id="categorizacionActualHidden" value="'.$resp[0]['cat_nombre_mostrar'].'" />';
		$html .= '<input type="hidden" id="'.strtotime($resp[0]['cam_fecha_desocupada']).'" class="tiempoCamaDesocupadaHidden" value="'.$resp[0]['cam_fecha_desocupada'].'" />';
		$html .= '<input class="cama_id" type="hidden" id="'.$resp[0]['cam_id'].'" class="numeroCamaHidden" value="'.$resp[0]['cam_id'].'" />
				  <input class="sala_id" type="hidden" value="'.$resp[0]['sal_id'].'"/>
				  <input class="salaTipo" type="hidden" value="'.$resp[0]['sal_tipo'].'" />
				  <input class="dau_id" type="hidden" value="'.$resp[0]['dau_id'].'"/>
				  <input class="cama_descripcion" type="hidden" value="'.$resp[0]['cam_descripcion'].'" />
				  <input class="nombre_paciente" type="hidden" value="'.$resp[0]['nombres']." ".$resp[0]['apellidopat']." ".$resp[0]['apellidomat'].'" />
				  <input class="fecha_categorizacion" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($resp[0]['dau_categorizacion_actual_fecha'])).'" />
				  <input class="nombre_categorizacion" type="hidden" value="'.$resp[0]['cat_nombre_mostrar'].'" />
				  <input class="descripcion_consulta" type="hidden" value="'.$resp[0]['mot_descripcion'].' '.$resp[0]['sub_mot_descripcion'].' '.$resp[0]['dau_motivo_descripcion'].'" />
				  <input class="ingreso_sala" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($resp[0]['dau_ingreso_sala_fecha'])).'" />
				  <input class="fecha_atencion" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($resp[0]['dau_inicio_atencion_fecha'])).'" />
				  <input class="fecha_atencionDinamica" type="hidden" value="'.strtotime($resp[0]['dau_inicio_atencion_fecha']).'" />
				  <input class="fecha_egreso" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($resp[0]['dau_indicacion_egreso_fecha'])).'" />
				  <input class="fecha_egresoDinamica" type="hidden" value="'.strtotime($resp[0]['dau_indicacion_egreso_fecha']).'" />
				  <input class="motivo_egreso" type="hidden" value="'.$resp[0]['ind_egr_descripcion'].'" />
				  <input class="tipoPaciente" type="hidden" value="'.$resp[0]['dau_atencion'].'" />
				  <input class="servicioHospitalizacion" type="hidden" value="'.$resp[0]['servicio'].'" />
				  <input class="atencionIniciadaPor" type="hidden" value="'.$resp[0]['atencionIniciadaPor'].'" />
				  <input class="edadPaciente" type="hidden" value="'.$objUtil->edadActualCompleto($resp[0]['fechanac']).'" />';

		if ( existeExamenLaboratorioCancelado($resp[0]['dau_id']) ){

			$html .= '<input class="examenLaboratorioCancelado" type="hidden" value="S" />';

		}

		if ( existeSintomasRespiratorios($resp[0]['sintomasRespiratorios']) ){

			$html .= '<input class="sintomasRespiratorios" type="hidden" value="S" />';

		}


		if ( ! isset($resp[0]['dau_indicacion_egreso_fecha']) ) {
			if ( $resp[0]['dau_inicio_atencion_fecha'] ) {
				$html .= '<span class="text-downright"><i class="fa fa-play fa-shadow" aria-hidden="true"></i></span>';
			}
		} else if ($consultaAlta[0]['respuesta'] == 3 ) {
			$html .= '<span class="text-downright"><i class="fa fa-home fa-shadow" aria-hidden="true"></i></span>';
		} else if ($consultaAlta[0]['respuesta'] == 5 ) {
			$html .= '<span class="text-downright"><i class="fa fa-times fa-shadow" aria-hidden="true"></i></span>';
		} else if ($consultaAlta[0]['respuesta'] == 6 ) {
			$html .= '<span class="text-downright"><i class="fa fa-plus fa-shadow" aria-hidden="true"></i></span>';
		} else if ($consultaAlta[0]['respuesta'] == 7 ) {
			$html .= '<span class="text-downright"><i class="fa fa-ambulance fa-shadow" aria-hidden="true"></i></span>';
		}

		if ( $resp[0]['sexo'] == 'M' || $resp[0]['sexo'] == '1' ){
			$html .= '<img class="imagenPaciente" src="'.PATH.'/assets/img/pacienteM2.png">';
		} else if ( $resp[0]['sexo'] == 'F' || $resp[0]['sexo'] == '0' ) {
			$html .= '<img class="imagenPaciente" src="'.PATH.'/assets/img/pacienteF.png">';
		} else {
			$html .= '<img class="imagenPaciente" src="'.PATH.'/assets/img/indefinido.png">';
		}

		if ( $_POST['id_dau'] ) {
			$parametros['dau_id'] = $_POST['id_dau'];
			$consultaRCEid        = $objRegistroClinico -> consultaRCE($objCon,$parametros);
			$parametros['regId']  = $consultaRCEid[0]['regId'];
			$datosSol             = $objCat->listar_Solicitud_Total($objCon,$parametros);
		}


		if ( $resp[0]['dau_indicaciones_solicitadas'] != 0 && $resp[0]['dau_indicaciones_solicitadas'] != '' && $resp[0]['dau_indicaciones_solicitadas'] > $resp[0]['dau_indicaciones_realizadas'] ){
			$html .= '<span class="text-upleft-custom"><i class="fas fa-clock throb2"></i></span>';
		}

		echo $html;

	break;



	case "crearDivCamaDesocupadaFull":

		require_once("../../../class/MapaPiso.class.php"); $objMapaPiso = new MapaPiso;

		


		if ( $_POST['tipoPaciente'] == 'adulto' || $_POST['tipoPaciente'] == 1 ) {

			$tipoPaciente = 'A';

		} else if ( $_POST['tipoPaciente'] == 'pediatrico' || $_POST['tipoPaciente'] == 2 ) {

			$tipoPaciente = 'P';

		} else if ( $_POST['tipoPaciente'] == 'ginecologico' || $_POST['tipoPaciente'] == 3 ){

			$tipoPaciente = 'GO';

		}

		$respuesta = $objMapaPiso->loadCamasFull($objCon, null, $_POST['camaOrigen'], $tipoPaciente);

		$html = '<input class="cama_id" type="hidden" id="'.$respuesta[0]['cam_id'].'" class="numeroCamaHidden" value="'.$respuesta[0]['cam_id'].'" />
				 <input class="dau_id" type="hidden" value="'.$respuesta[0]['dau_id'].'"/>
				 <input class="salaTipo" type="hidden" value="'.$respuesta[0]['sal_tipo'].'" />
				 <input class="sala_id" type="hidden" value="'.$respuesta[0]['sal_id'].'" />
				 <input class="cama_descripcion" type="hidden" value="'.$_POST['salaCamaOrig'].'" />
				 <input type="hidden" id="'.strtotime($respuesta[0]['cam_fecha_desocupada']).'" class="tiempoCamaDesocupadaHidden" value="'.$respuesta[0]['cam_fecha_desocupada'].'" />';

		echo $html;

	break;



	case 'updateCamaDestino':

		

		require_once("../../../class/MapaPiso.class.php");		$objMapaPiso     = new MapaPiso;

		$parametros = $objUtil->getFormulario($_POST);

		try {

			$objCon->beginTransaction();
			$respuesta                = $objMapaPiso->updateSalaUEDestino($objCon, $parametros);
			$response    			  = array("status" => "success","id" => $parametros['id_dau']);
			echo json_encode($response);
			$objCon->commit();

		} catch (PDOException $e) {

			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);

		}

	break;



	case 'getCssTiempoEgreso':

		

		require_once("../../../class/MapaPiso.class.php");		$objMapaPiso     = new MapaPiso;

		// require("../../../config/config.php");

		$resp = $objMapaPiso->getDatasMovePacienteTblCatToSalaUE($objCon, $_POST['id_dau']);

		$css_ti_aapli;

		if ( $resp[0]['dau_indicacion_egreso_fecha'] ) {

			$ti_aapli = strtotime(date("Y-m-d H:i:s")) - strtotime($resp[0]['dau_indicacion_egreso_fecha']);
			if ($ti_aapli < 21600) {$css_ti_aapli = 'verde';}
			elseif (($ti_aapli >= 21600) && ($ti_aapli < 46800)) {$css_ti_aapli = 'amarillo';}
			elseif (($ti_aapli >= 46800) && ($ti_aapli < 90000)) {$css_ti_aapli = 'naranja';}
			else{$css_ti_aapli = 'rojo';}

		} else {

			$css_ti_aapli = 'default';

		}

		echo $css_ti_aapli;

	break;



	case 'datosPacienteListEsp':

		

		require_once("../../../class/MapaPiso.class.php");		$objMapaPiso  = new MapaPiso;
		// require("../../../config/config.php");
		require_once('../../../class/Util.class.php');      	$objUtil      = new Util;

		$version    = $objUtil->versionJS();
		$permisos   = $_SESSION['permisosDAU'.SessionName];
		$resp       = $objMapaPiso->getDatasMovePacienteTblCatToSalaUE($objCon, $_POST['id_dau']);
		$response   = array("status"=>"success", "sexo"=> $resp[0]['sexo'], "dau_categorizacion_actual"=> $resp[0]['dau_categorizacion_actual']);

		$draggable;
		$onclick;

		if ( array_search(822,$permisos) != null ) {
			$draggable = 'true';
			$class = 'tbl_esp tr_tblCat-default arrastre_espera';
		} else {
			$draggable = 'false';
			$class = 'tr_tblCat-default arrastre_espera';
		}

		$tiempo = '<div id="contTEA_'.$resp[0]['dau_id'].'" class="contadorTEspAdm"></div>';

		$cat='No';

		if ( substr(strtoupper($resp[0]['ate_descripcion']), 0, 1) == 'A' ) {
			$tipoPaciente = 'adulto';
		} else {
			$tipoPaciente = 'pediatrico';
		}


		$html.= "<tr id='".$resp[0]['dau_id']."' draggable='".$draggable."' style='cursor: pointer;background-color: #f8f8f8;' class='".$class." ".$tipoPaciente." sincategorizar pacienteEnEspera  ' ".$onclick."'>
					<td align='center' class='col-xs-1 contenido2' style='width: 15%;'>".$resp[0]['dau_id']." (".substr(strtoupper($resp[0]['ate_descripcion']), 0, 1).")</td>
					<td align='center' class='col-xs-3 contenido2' style='width: 23.5%; font-size:8px'>".strtoupper($resp[0]['nombres']." ".$resp[0]['apellidopat']." ".$resp[0]['apellidomat'])."</td>
	 				<td align='center' class='col-xs-1 contenido2' style='width: 12%;'>".substr(strtoupper($resp[0]['mot_descripcion']), 0, 5)."</td>
	 				<td align='center' class='col-xs-1 contenido2' style='width: 7.3%;'>".$cat."</td>
	 				<td align='center' class='col-xs-2 contenido2' style='width: 21.3%;'>".$tiempo."</td>
				 </tr>";

		echo $html;

	break;



	case 'cargarResultados':

		require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;
		require_once('../../../class/Util.class.php');      	$objUtil       = new Util;

		

		$version    = $objUtil->versionJS();

		$datos      = $objMapaPiso->cantidadesADMCAT($objCon, 'mp');


		$SC2  = 0;
		$CAT2 = 0;

		for ( $i = 0; $i < count($datos); $i++ ) {

			if($datos[$i]['est_id'] == '2'){
				$CAT2 = $CAT2+1;
			}

			if($datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == ''){
				$SC2 = $SC2+1;
			}

		}

		$html.= "<script type='text/javascript' src='".PATH."/controllers/client/mapa_piso/mapa_piso_2.js?v=".$version."'></script>
		<div id='pacienteTotal'>
			<div class='row'>
				<div class='col-md-6' id='divSeleccione'>
					<select id='frm_tipo_Atenciones' class='form-control' style='margin-top: 10px; width: 165px;'>
						<option value='1'>Todos</option>
						<option value='2'>Adulto</option>
						<option value='3'>Pediátrico</option>
					</select>
				</div>
				<div class='col-md-6' id='divBtnFiltro'>
					<button id='quitarFiltros' style='margin-top: 12px;' class='btn btn-danger btn-xs'>✖</button>
				</div>
			</div>
			<div id='pacienteTotal_'>
				<h4 style=' margin-top: 5px;'><small style='font-size: 11px !important; font-weight: bold; color:black'><strong>PACIENTES EN ESPERA <label id='lblResultPE' class='resultadoPacienteEspera' style='font-size: 15px !important; font-weight: bold; cursor: pointer;'>(".$SC2.")</label></strong></small></h4>
				<br>
				<h4 style='margin-top: -26px;'><small style='font-size: 11px !important; font-weight: bold; color:black'><strong>CATEGORIZADOS <label id='lblResultPC' class='resultadoPacienteCategorizados' style='font-size: 15px !important; font-weight: bold; cursor: pointer;'>(".$CAT2.")</label></strong></small></h4>
			</div>
		</div>";

		echo $html;

	break;



	case 'cargarResultados2':

		require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;
		require_once('../../../class/Util.class.php');      	$objUtil       = new Util;

		

		$version    = $objUtil->versionJS();

		$datos = $objMapaPiso->cantidadesADMCAT($objCon, 'mp');
		$C1  = 0;
		$C2  = 0;
		$C3  = 0;
		$C4  = 0;
		$C5  = 0;
		$SC  = 0;
		$CAT = 0;

		for ( $i = 0; $i < count($datos); $i++ ) {

			if($datos[$i]['est_id'] == '1'){
			}

			if($datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == ''){
				$SC = $SC+1;
			}

			if($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C1'){
				$C1 = $C1+1;
			}

			if($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C2'){
				$C2 = $C2+1;
			}

			if($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C3'){
				$C3 = $C3+1;
			}

			if($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C4'){
				$C4 = $C4+1;
			}

			if($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C5'){
				$C5 = $C5+1;
			}

			$CAT = $C1+$C2+$C3+$C4+$C5;
			$total = $SC+$C1+$C2+$C3+$C4+$C5;
			if($total == '' || $total == 0){
				$total= '0';
			}else{
				$total;
			}
		}

		$html.="<script type='text/javascript' src='".PATH."/controllers/client/mapa_piso/mapa_piso_2.js?v=".$version."'></script>
				<div class='row pac-list' id='pacienteTotal2'>
					<div class='col-md-12'>
						<h4 style=' margin-top: 2px;'><small style='font-size: 11px !important; font-weight: bold; color:black'><strong>RESUMEN DE CATEGORIZACIONES</strong></small></h4>
						<div class='thumbnail' style='margin-top: -8px;'>
							<table id='' class='display table-condensed table-hover'>
								<tr class='table-mapa-piso-encabezado'>
									<th class='col-xs-1 headers2'>C1</th>
									<th class='col-xs-1 headers2'>C2</th>
									<th class='col-xs-1 headers2'>C3</th>
									<th class='col-xs-1 headers2'>C4</th>
									<th class='col-xs-1 headers2'>C5</th>
									<th class='col-xs-1 headers2'>S/C</th>
									<th class='col-xs-1 headers2'>T. CAT</th>
									<th class='col-xs-1 headers2'>Total</th>
								</tr>
								<tr>
									<td class='tr_tblCat-ESI-1' align='center'><label id='td_c1'>".$C1."</label></td>
									<td class='tr_tblCat-ESI-2' align='center'><label id='td_c2'>".$C2."</label></td>
									<td class='tr_tblCat-ESI-3' align='center'><label id='td_c3'>".$C3."</label></td>
									<td class='tr_tblCat-ESI-4' align='center'><label id='td_c4'>".$C4."</label></td>
									<td class='tr_tblCat-ESI-5' align='center'><label id='td_c5'>".$C5."</label></td>
									<td align='center'><label id='td_sc'>".$SC."</label></td>
									<td align='center'><label id='td_cat'>".$CAT."</label></td>
									<td align='center' style='background-color: #1e73be;color: #ffffff;'>
										<label id='td_total' style='color: #ffffff;'>".$total."</label>
									</td>

								</tr>
							</table>
						</div>
					</div>
				</div>";

		echo $html;

	break;




	// case 'updateSalaCamaAddPaciente':

		

	// 	require_once("../../../class/MapaPiso.class.php");		$objMapaPiso     = new MapaPiso;
	// 	require_once("../../../class/Movimientos.class.php");	$objMovimientos  = new Movimientos;

	// 	$parametros = $objUtil->getFormulario($_POST);
	// 	$response;

	// 	try {

	// 		$objCon->beginTransaction();

	// 		$resp 		= $objMapaPiso->getEstadoPacienteDAU($objCon, $parametros);
	// 		$tipoLista 	= '';
	// 		$message 	= '';

	// 		switch ( $parametros['lista'] ) {

	// 			case 'listaEspera':
	// 				$tipoLista = '1';
	// 				$message = "El paciente ya no se encuentra en la lista de 'Espera'.";
	// 			break;

	// 			case 'listaCategorizados':
	// 				$tipoLista = '2';
	// 				$message = "El paciente ya no se encuentra en la lista de 'Categorizados'.";
	// 			break;

	// 			default:
	// 				$message = "El paciente no se encuentra en la lista.";
	// 			break;

	// 		}

	// 		if ( $tipoLista == $resp[0]['est_id'] ) {

	// 			$parametros['num_salaDest'] = $parametros['salaDest_id'];

	// 			$resp2 = $objMapaPiso->getEstadoCamaDestino($objCon, $parametros);

	// 			if ( $resp2[0]['est_id'] == '10' ) {

	// 				$parametros['dau_mov_usuario'] = $_SESSION['MM_Username'.SessionName];

	// 				$objMapaPiso->updateDatasPacienteCatInDAU($objCon, $parametros);
	// 				$objMapaPiso->updateSalaUEDestino($objCon, $parametros);

	// 				$parametrosGM = array("dau_id" => $_POST['id_dau'], "dau_mov_descripcion" => "ingreso a cama", "dau_mov_usuario" => $parametros['dau_mov_usuario'], "dau_mov_tipo" => "ing");
	// 				$objMovimientos->guardarMovimiento($objCon, $parametrosGM);

	// 				$parametrosMPC 	= array("sal_id" => $parametros['num_salaDest'], "cam_descripcion" => $parametros['cama']);
	// 				$resp_camid 	= $objMapaPiso->getIdCama($objCon, $parametrosMPC);
	// 				$parametrosGMC 	= array("dau_id" => $_POST['id_dau'], "cam_id" => $resp_camid[0]['cam_id'], "sal_id" => $parametros['num_salaDest'], "sal_descripcion" => $parametros['sala'], "cam_descripcion" => $parametros['cama'], "dau_mov_cama_usuario_ingreso" =>  $dau_mov_usuario, "dau_mov_cama_estado" => "enCama");
	// 				$objMovimientos->guardarMovimientoCama($objCon, $parametrosGMC);

	// 				$response = array("status"=>"success");

	// 			} else {

	// 				$response = array("status"=>"error", "message"=>"La cama de destino se encuentra ocupada.");

	// 			}

	// 		} else {

	// 			if ( $tipoLista == '1' ) {

	// 				$response = array("status"=>"error", "message"=>$message);

	// 			} else if ( $tipoLista == '2' ) {

	// 				$response = array("status"=>"error", "message"=>$message);

	// 			} else {

	// 				$response = array("status"=>"error", "message"=>$message);

	// 			}

	// 		}

	// 		$objCon->commit();

	// 		echo json_encode($response);

	// 	} catch (PDOException $e) {

	// 		$objCon->rollback();

	// 		$response = array("status" => "error", "message" => $e->getMessage());

	// 		echo json_encode($response);

	// 	}

	// break;



	case 'tiempoEsperaPaciente':

		require_once("../../../class/Dau.class.php" );  $objDetalleDau      = new Dau;
		require_once("../../../class/Rce.class.php" );  $objRCE      		= new Rce;

		
		$parametros                    = $objUtil->getFormulario($_POST);

		try {

			switch ( $parametros['opc'] ) {

				case 'admision':

					$resp = $objDetalleDau->tiemposDAUActFull($objCon, '1');
					$response = array();
					$response[0] = array("status" => "success");

					for ( $j = 0, $i = 1; $i <= count($resp); $j++, $i++ ) {

						$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($resp[$j]['dau_admision_fecha']);
						$fecha_ini_atencion = strtotime($resp[$j]['dau_admision_fecha']);

						if($segundos < 0){
							$segundos = 0;
						}

						$response[$i] = array(
												"id" => $resp[$j]['dau_id'],
											  	"est_id" => $resp[$j]['est_id'],
											  	"dau_admision_fecha" => $resp[$j]['dau_admision_fecha'],
											  	"FechaActual" => $resp[$j]['FechaActual'],
											  	"segundos" => $segundos,
											  	"fecha_ini_atencion" => $fecha_ini_atencion,
											  );
					}

					echo json_encode($response);

				break;


				case 'categorizacion':

					$resp = $objDetalleDau->tiemposDAUActFull($objCon, '2');
					$response = array();
					$response[0] = array("status" => "success");

					for ( $j = 0, $i = 1; $i <= count($resp); $j++, $i++ ) {

						$cat_tiempo_maximo_seg = ($resp[$j]['cat_tiempo_maximo']*60);
						$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($resp[$j]['dau_categorizacion_fecha']);

						if($segundos < 0){
							$segundos = 0;
						}

						$response[$i] = array(
												"id" => $resp[$j]['dau_id'],
												"est_id" => $resp[$j]['est_id'],
												"dau_categorizacion_fecha" => $resp[$j]['dau_categorizacion_fecha'],
												"FechaActual" => $resp[$j]['FechaActual'],
												"segundos" => $segundos,
												"cat_nivel" => $resp[$j]['cat_nivel'],
												"cat_tiempo_maximo" => $resp[$j]['cat_tiempo_maximo'],
												"cat_tipo" => $resp[$j]['cat_tipo'],
												"cat_tiempo_maximo_seg" => $cat_tiempo_maximo_seg
											);
					}

					echo json_encode($response);

				break;


				case 'camas':

					$resp = $objDetalleDau->tiemposDAUActFull($objCon, '3, 4, 8');
					$segundos = '';
					$response = array();
					$response[0] = array("status" => "success");

					for ( $j = 0, $i = 1; $i <= count($resp); $j++, $i++ ) {

						$solicitudesAplicadas = 0;

						$dauAbiertoMantenedor = true;

						if ( $resp[$j]['dau_inicio_atencion_fecha'])  {
							$segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($resp[$j]['dau_inicio_atencion_fecha']);

							if($segundos < 0){
								$segundos = 0;
							}

							$id_paciente   				= $resp[$j]['id_paciente'];
							$fecha_ini_atencion 	 	= strtotime($resp[$j]['dau_inicio_atencion_fecha']);
							$fecha_ind_egreso 			= strtotime($resp[$j]['dau_indicacion_egreso_fecha']);
							$motivo_ind_egreso         	= $resp[$j]['ind_egr_descripcion'];
							$dau_indicacion_terminada 	= $resp[$j]['dau_indicaciones_completas'];

							$objCon->setDB("rce");
							$respuestaIdRCE = $objRCE->obtenerIdRCESegunDAU($objCon, $resp[$j]['dau_id']);
							$respuestaSolicitudes= $objRCE->obtenerCantidadSolicitudesNoSuperfluas($objCon, $respuestaIdRCE['regId']);

							if( $respuestaSolicitudes['solicitudesImportantes'] === $respuestaSolicitudes['solicitudesAplicadas']) {
								$solicitudesAplicadas = 1;
							}

							if ( is_null($resp[$j]['dau_inicio_atencion_fecha'] ) || empty($resp[$j]['dau_inicio_atencion_fecha']) ) {
								$resp[$j]['dau_inicio_atencion_fecha'] = '';
							}

							if ( is_null($resp[$j]['dau_indicacion_egreso_fecha'] ) || empty($resp[$j]['dau_indicacion_egreso_fecha']) ) {
								$resp[$j]['dau_indicacion_egreso_fecha'] = '';
							}


							if( $fecha_ini_atencion < 0 ) {
								$fecha_ini_atencion = 0;
							}

							if($fecha_ind_egreso < 0){
								$fecha_ind_egreso = 0;
							}

							$tiempoCategorizacion = 0;
							$tiempoAlerta 		  = 0;

						} else if ( ( is_null($resp[$j]['dau_inicio_atencion_fecha'] ) || empty($resp[$j]['dau_inicio_atencion_fecha']) ) &&   (!is_null($resp[$j]['dau_categorizacion_actual_fecha']) && !empty($resp[$j]['dau_categorizacion_actual_fecha']) ) && $resp[$j]['cat_nombre_mostrar'] != 'C5' )  {

							$segundos 				= strtotime(date('Y-m-d H:i:s')) - strtotime($resp[$j]['dau_categorizacion_actual_fecha']);
							$tiempoCategorizacion 	= strtotime($resp[$j]['dau_categorizacion_actual_fecha']);
							$tiempoAlerta 			= $resp[$j]['cat_tiempo_alerta'] * 60;
							$fecha_ind_egreso       = 0;
							$dau_indicacion_terminada = 0;

						} else {

							$segundos    		  = 0;
							$tiempoCategorizacion = 0;
							$tiempoAlerta 		  = 0;
							$fecha_ini_atencion   = 0;
							$fecha_ind_egreso     = 0;
							$dau_indicacion_terminada = 0;

						}

						if ( empty($resp[$j]['dau_abierto_mantenedor']) || is_null($resp[$j]['dau_abierto_mantenedor']) || $resp[$j]['dau_abierto_mantenedor'] == 'N' || $resp[$j]['dau_abierto_mantenedor'] == 'M' ) {

							$dauAbiertoMantenedor = false;

						}

						$response[$i] = array("id"							=> $resp[$j]['dau_id'],
											"est_id" 						=> $resp[$j]['est_id'],
											"id_paciente"					=> $id_paciente,
											"dau_inicio_atencion_fecha" 	=> $resp[$j]['dau_inicio_atencion_fecha'],
											"fecha_ini_atencion" 			=> $fecha_ini_atencion,
											"dau_indicacion_egreso_fecha" => $resp[$j]['dau_indicacion_egreso_fecha'],
											"fecha_ind_egreso" 			=> $fecha_ind_egreso,
											"FechaActual" 				=> $resp[$j]['FechaActual'],
											"motivo_ind_egreso" 			=> $motivo_ind_egreso,
											"segundos" 					=> $segundos,
											"dau_indicacion_terminada" 	=> $dau_indicacion_terminada,
											"tiempoCategorizacion"		=> $tiempoCategorizacion,
											"tiempoAlerta" 				=> $tiempoAlerta,
											"solicitudesAplicadas"		=> $solicitudesAplicadas,
											"dauAbiertoMantenedor"		=>  $dauAbiertoMantenedor
											);

					}

					echo json_encode($response);

				break;



				default:

					$segundos = 0;
					$response = array("status" => "success", "id" => $parametros['dau_id'], "est_id" => $parametros['est_id'], "segundos" => $segundos);

					echo json_encode($response);

				break;
			}

		} catch (PDOException $e) {

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'tiempoEsperaPacienteIndividual':

		require_once("../../../class/Dau.class.php" );  $objDetalleDau = new Dau;

		

		$parametros = $objUtil->getFormulario($_POST);

		try{

			$resp                      = $objDetalleDau->tiemposDAU($objCon, $parametros);

			switch ( $parametros['opc'] ) {

				case 'admision':

					$segundos = strtotime($resp[0]['FechaActual']) - strtotime($resp[0]['dau_admision_fecha']);

					if($segundos < 0){
						$segundos = 0;
					}

					$response = array(
										"status" => "success",
										"id" => $parametros['dau_id'],
										"est_id" => $parametros['est_id'],
										"dau_admision_fecha" => $resp[0]['dau_admision_fecha'],
										"FechaActual" => $resp[0]['FechaActual'],
										"segundos" => $segundos
									);

					echo json_encode($response);

				break;



				case 'categorizacion':

					$cat_tiempo_maximo_seg = ($resp[0]['cat_tiempo_maximo']*60);
					$segundos = strtotime($resp[0]['FechaActual']) - strtotime($resp[0]['dau_categorizacion_fecha']);

					if($segundos < 0){
						$segundos = 0;
					}

					$response = array(
										"status" => "success",
										"id" => $parametros['dau_id'],
										"est_id" => $parametros['est_id'],
										"dau_categorizacion_fecha" => $resp[0]['dau_categorizacion_fecha'],
										"FechaActual" => $resp[0]['FechaActual'],
										"segundos" => $segundos,
										"cat_nivel" => $resp[0]['cat_nivel'],
										"cat_tiempo_maximo" => $resp[0]['cat_tiempo_maximo'],
										"cat_tipo" => $resp[0]['cat_tipo'],
										"cat_tiempo_maximo_seg" => $cat_tiempo_maximo_seg
									);

					echo json_encode($response);

				break;



				case 'camas':

					$segundos = '';

					if ( $resp[0]['dau_indicacion_egreso_fecha'] ) {

						$segundos = strtotime($resp[0]['FechaActual']) - strtotime($resp[0]['dau_indicacion_egreso_fecha']);

						if($segundos < 0){
							$segundos = 0;
						}

						$response = array(
											"status" => "success",
											"id" => $parametros['dau_id'],
											"est_id" => $parametros['est_id'],
											"dau_indicacion_egreso_fecha" => $resp[0]['dau_indicacion_egreso_fecha'],
											"FechaActual" => $resp[0]['FechaActual'],
											"segundos" => $segundos
										);

					} else {

						$segundos = 0;

						$response = array(
											"status" => "success",
											"id" => $parametros['dau_id'],
											"est_id" => $parametros['est_id'],
											"FechaActual" => $resp[0]['FechaActual'],
											"segundos" => $segundos
										);

					}

					echo json_encode($response);

				break;


				default:

					$segundos = 0;

					$response = array(
										"status" => "success",
										"id" => $parametros['dau_id'],
										"est_id" => $parametros['est_id'],
										"segundos" => $segundos
									);

					echo json_encode($response);

				break;

			}

		} catch (PDOException $e) {

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'soloPedia':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso      = new MapaPiso;

		

		$datos    = $objMapaPiso->soloPedia($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'soloGine':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso      = new MapaPiso;

		

		$datos    = $objMapaPiso->soloGine($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'soloPedia3':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso      = new MapaPiso;

		

		$datos    = $objMapaPiso->soloPedia3($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'soloAdul':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso      = new MapaPiso;

		

		$datos            = $objMapaPiso->soloAdul($objCon);

		$response 		  = array(
									"SC"=>  $datos[0]['SC'],
									"PC"=>  $datos[0]['PC'],
									"C1"=>  $datos[0]['C1'],
									"C2"=>  $datos[0]['C2'],
									"C3"=>  $datos[0]['C3'],
									"C4"=>  $datos[0]['C4'],
									"C5"=>  $datos[0]['C5']
									);

		echo json_encode($response);

	break;



	case 'soloAdul2':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos            = $objMapaPiso->soloAdulto3($objCon);

		$response 		  = array(
									"SC"=>  $datos[0]['SC'],
									"PC"=>  $datos[0]['PC'],
									"C1"=>  $datos[0]['C1'],
									"C2"=>  $datos[0]['C2'],
									"C3"=>  $datos[0]['C3'],
									"C4"=>  $datos[0]['C4'],
									"C5"=>  $datos[0]['C5']
								);

		echo json_encode($response);

	break;



	case 'todosAdultoPediatrico1':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos            = $objMapaPiso->todosAdultoPediatrico1($objCon);

		$response 		  = array(
									"SC"=>  $datos[0]['SC'],
									"PC"=>  $datos[0]['PC'],
									"C1"=>  $datos[0]['C1'],
									"C2"=>  $datos[0]['C2'],
									"C3"=>  $datos[0]['C3'],
									"C4"=>  $datos[0]['C4'],
									"C5"=>  $datos[0]['C5']
									);

		echo json_encode($response);

	break;



	case 'todosAdultoPediatrico2':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos            = $objMapaPiso->todosAdultoPediatrico2($objCon);

		$response 		  = array(
									"SC"=>  $datos[0]['SC'],
									"PC"=>  $datos[0]['PC'],
									"C1"=>  $datos[0]['C1'],
									"C2"=>  $datos[0]['C2'],
									"C3"=>  $datos[0]['C3'],
									"C4"=>  $datos[0]['C4'],
									"C5"=>  $datos[0]['C5']
									);

		echo json_encode($response);

	break;



	case 'todosAdultoPediatricoGeneral':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos            = $objMapaPiso->todosAdultoPediatricoGeneral($objCon);
		$datos            = $objMapaPiso->todosAdultoPediatricoGinecologicoGeneral($objCon);

		$response 		  = array(
									"SC"=>  $datos[0]['SC'],
									"PC"=>  $datos[0]['PC'],
									"C1"=>  $datos[0]['C1'],
									"C2"=>  $datos[0]['C2'],
									"C3"=>  $datos[0]['C3'],
									"C4"=>  $datos[0]['C4'],
									"C5"=>  $datos[0]['C5']
									);

		echo json_encode($response);

	break;



	case 'soloGine2':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos    = $objMapaPiso->soloGine2($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'todos':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos    = $objMapaPiso->todos($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'todos1':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos    = $objMapaPiso->todos1($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'todos2':

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso = new MapaPiso;

		

		$datos    = $objMapaPiso->todos2($objCon);

		$response = array(
							"SC"=>  $datos[0]['SC'],
							"PC"=>  $datos[0]['PC'],
							"C1"=>  $datos[0]['C1'],
							"C2"=>  $datos[0]['C2'],
							"C3"=>  $datos[0]['C3'],
							"C4"=>  $datos[0]['C4'],
							"C5"=>  $datos[0]['C5']
						);

		echo json_encode($response);

	break;



	case 'obtnerColorIndicaciones':

		require_once("../../../class/Dau.class.php" );  $objDetalleDau = new Dau;

		

		$parametros = $objUtil->getFormulario($_POST);

		$respColor  = $objDetalleDau->ListarPacientesDau($objCon, $parametros);

		if ( $respColor[0]['dau_indicaciones_completas'] == '1' ) {
			$color = 'verde';
			$color_ant = 'plomo';
		} else {
			$color = 'plomo';
			$color_ant = 'verde';
		}

		$response 		  = array(
									"id_dau"		=>  $respColor[0]['dau_id'],
									"ind_completa"	=>  $respColor[0]['dau_indicaciones_completas'],
									"ind_color"		=>  $color,
									"ind_color_ant"	=>  $color_ant
								);

		echo json_encode($response);

	break;



	case 'guardarTipoMapaUsuario' :

		require_once("../../../class/MapaPiso.class.php" );  $objMapaPiso   = new MapaPiso;

		

		$parametros = $objUtil->getFormulario($_POST);

		$parametros['idusuario'] = $_SESSION['MM_Username'.SessionName];

		try{

			$objCon->beginTransaction();

			$objMapaPiso->guardarTipoMapaUsuario($objCon, $parametros);

			$objCon->commit();

		} catch ( PDOException $e ) {

			$objCon->rollback();
		}

	break;


	default:
	# code...
	break;
}



function colorTiempo($dau_indicacion_egreso_fecha, $dau_inicio_atencion_fecha, $dau_indicacion_terminada, $FechaActual){
	$fecha_inicio_atencion   = strtotime($FechaActual) - strtotime($dau_inicio_atencion_fecha);
	$fecha_indicacion_egreso = strtotime($FechaActual) - strtotime($dau_indicacion_egreso_fecha);

	if ( !is_null($dau_inicio_atencion_fecha) && !empty($dau_inicio_atencion_fecha) ) {
		if ($fecha_inicio_atencion > 21599) {
			if(  !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha)  ){
				if ($fecha_indicacion_egreso < 43200) {
					return 'rojo';
				}else if ($fecha_indicacion_egreso >= 43200) {
					return 'fucsia';
				}
			}else{
				return 'amarillo';
			}
		}else{
			if ($dau_indicacion_terminada == 1) {
				if( !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha) ){
					if ($fecha_indicacion_egreso < 43200) {
						return 'rojo';
					}else if ($fecha_indicacion_egreso >= 43200) {
						return 'fucsia';
					}
				}else{
					return 'verde';
				}
			}else if ($dau_indicacion_terminada == 0) {
				if( !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha) ){
					if ($fecha_indicacion_egreso < 43200) {
						return 'rojo';
					}else if ($fecha_indicacion_egreso >= 43200) {
						return 'fucsia';
					}
				}else{
					return 'plomo';
				}
			}
		}
	}else{
		return 'plomo';
	}
}



function categorizacion($cat_nombre_mostrar, $cat_nivel){
	if(isset($cat_nombre_mostrar)){
		return '<span class="text-downleft-'.$cat_nivel.'">'.$cat_nombre_mostrar.'</span>';
	}else{
		return '<span class="text-downleft-default">--</span>';
	}
}



function noExisteInicioAtencion ( $inicioAtencion ) {

	return ( is_null($inicioAtencion) || empty($inicioAtencion) || ($inicioAtencion == '0000-00-00 00:00:00') || ($inicioAtencion == '31-12-1969 21:00:00') ) ? true : false;

}



function pacienteAunNoCategorizado ( $tiempoCategorizacion ) {

	return ( is_null($tiempoCategorizacion) || empty($tiempoCategorizacion) || ($tiempoCategorizacion == '0000-00-00 00:00:00') || ($inicioAtencion == '31-12-1969 21:00:00') ) ? false : true;

}



function tiempoAlertaCumplido( $tiempoActual, $tiempoAlerta ) {

	return ( $tiempoActual > $tiempoAlerta ) ? true : false;

}



function tipoCategorizacionSuperfluo ( $tipoCategorizacion ) {

	return ( $tipoCategorizacion != 'C5' ) ? true : false;

}



function tiempoEsperaDesdeCategorizacion($dauId, $inicioAtencion, $tiempoCategorizacion,  $tipoCategorizacion, $tiempoAlerta){

	if ( noExisteInicioAtencion($inicioAtencion) && pacienteAunNoCategorizado($tiempoCategorizacion) ) {

		$segundos 				= 60;
		$tiempoActual 			= strtotime(date('Y-m-d H:i:s'));
		$tiempoCategorizacion 	= strtotime($tiempoCategorizacion);
		$tiempoActual 			= $tiempoActual - $tiempoCategorizacion;
		$tiempoAlerta 			= $tiempoAlerta * $segundos;

		if ( tiempoAlertaCumplido($tiempoActual, $tiempoAlerta) && tipoCategorizacionSuperfluo($tipoCategorizacion) ) {
			//return "2";
			return '<span id="relojEsperaCategorizacion_'.$dauId.'" class="text-upleft-custom"><i class="fas fa-clock throb2"></i></span>';

		}

	}

}



function existeExamenLaboratorioCancelado ( $idDau ) {

	require_once('../../../class/Connection.class.php');	$objCon 		= new Connection; 

	require_once('../../../class/Laboratorio.class.php');  	$objLaboratorio = new Laboratorio;

	$resultadoConsulta = $objLaboratorio->consultarExamenesCanceladosDesdeMapaPiso($objCon, $idDau);

	return ( !empty($resultadoConsulta) && !is_null($resultadoConsulta) ) ? true : false;

}



function existeSintomasRespiratorios ( $sintomasRespiratorios ) {

	if ( is_null($sintomasRespiratorios) || empty($sintomasRespiratorios) ) {

		return false;

	}

	if ( strpos($sintomasRespiratorios, 'S') === false ) {

		return false;

	}

	return true;

}



function examenCovid ( $idPaciente ) {

	require_once('../../../class/Connection.class.php');				$objCon 	   = new Connection; 

	require_once("../../../class/FormularioSeguimiento.class.php");		$objFormulario = new FormularioSeguimiento;

	$examenCovid = $objFormulario->verificarExamenPositivo($objCon, $idPaciente);
	
	if ( is_null($examenCovid) || empty($examenCovid) ) {
	
		return;
		
	}
	
	$style = '';
	
	if ( $examenCovid['estadoFormulario'] == 3 ) {
	
		$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:red; font-size:6px;"';
		
	}
	
	if ( $examenCovid['estadoFormulario'] == 4 ) {
	
		$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:green; font-size:6px;"';
		
	}

	return '<span class="text-upright-custom"><i class="icon-cog fa fa-circle"'.$style.' aria-hidden="true"></i></span>';
}
?>