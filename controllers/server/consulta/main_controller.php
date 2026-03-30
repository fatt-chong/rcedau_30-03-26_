<?php
session_start();
error_reporting(0);
require_once('../../../class/Connection.class.php'); 		$objCon      	  	= new Connection; $objCon->db_connect();
require_once("../../../class/Movimiento.class.php"); 		$objMovimiento		= new Movimiento;
require_once("../../../class/Util.class.php");       		$objUtil        	= new Util;
require_once("../../../class/CMBD.class.php");       		$objCMBD    		= new CMBD;
require_once("../../../class/Paciente.class.php");    		$objPac        		= new Paciente;
require_once("../../../class/Cierre.class.php"); 			$objCierre 			= new Cierre;
require_once("../../../class/Dau.class.php" );   			$objDetalleDau 		= new Dau;
require_once('../../../class/Consulta.class.php');   		$objConsulta 		= new Consulta;
require_once("../../../class/RegistroMedico.class.php"); 	$objRegistroMedico  = new RegistroMedico;
require_once("../../../class/Fallecido.class.php");         $objFallecido       = new Fallecido;
require_once("../../../class/FuncionesAgenda.class.php");   $objFunAgenda       = new FuncionesAgenda;
require_once("../../../class/Agenda.class.php");   			$objAgenda 			= new Agenda;
require_once("../../../class/MapaPiso.class.php"); 			$objMapaPiso 		= new MapaPiso;


switch ( $_POST['accion'] ) {
	case "registroMedicoCie":
		$parametros                              = getFormulario($_POST);
		$parametros['item_producto_final']       = json_decode(stripslashes($parametros['item_producto_final']));
		try {
			$objCon->beginTransaction();
			$objRegistroMedico->registarCierreMedico($objCon, $parametros);
			if ( $parametros['dau_mov_descripcion']=="registroMedico" ) {
				$parametros['dau_id']				= $parametros['Iddau'];
				$parametros['dau_mov_descripcion']	= "registro medico cie10";
				$parametros['dau_mov_tipo'] 		= "rem";
				$parametros['dau_mov_usuario'] 		= $_SESSION["MM_UsernameName".SessionName];
				$parametros['dau_mov_fecha']		= date("Y-m-d H:i:s");
				$objMovimiento->guardarMovimiento($objCon,$parametros);
			}
			$response  = array("status" => "success", "id" =>$parametros['Iddau']);
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "busquedaSensitivaUsuarios":
		try {
			$objCon->beginTransaction();
			if($_POST['tipo'] == 'A'){
				$resultadoBusquedaSensitiva =  $objRegistroMedico->SelectUsuarioSensitiva($objCon,$_POST['tipo'],$_POST['term'],$objUtil);
			}else{
				if($_POST['tipo'] == 1){
					$_POST['tipo'] = "1,15";
				}
				$resultadoBusquedaSensitiva =  $objRegistroMedico->SelectParametrosClinicosSensitivaTodos($objCon,$_POST['tipo'],$_POST['term'],$objUtil);
			}
			// $resultadoBusquedaSensitiva =  $objRegistroMedico->SelectParametrosClinicosSensitivaTodos($objCon,$_POST['tipo'],$_POST['term'],$objUtil);
			if ( ! is_null($resultadoBusquedaSensitiva) && ! empty($resultadoBusquedaSensitiva) ){
                echo $resultadoBusquedaSensitiva;
			}
		} catch (PDOException $e) {
			$e->getMessage();
		}
	break;
	case "busquedaSensitivaMedicos":
		try {
			$objCon->beginTransaction();
			$resultadoBusquedaSensitiva =  $objRegistroMedico->SelectParametrosClinicosSensitivaMedicos($objCon,$_POST['term'],$objUtil);
			if ( ! is_null($resultadoBusquedaSensitiva) && ! empty($resultadoBusquedaSensitiva) ){
                echo $resultadoBusquedaSensitiva;
			}
		} catch (PDOException $e) {
			$e->getMessage();
		}
	break;
	case "busquedaSensitivaEnfermeros":
		try {
			$objCon->beginTransaction();
			$resultadoBusquedaSensitiva =  $objRegistroMedico->SelectParametrosClinicosSensitiva($objCon,$_POST['term']);
			if ( ! is_null($resultadoBusquedaSensitiva) && ! empty($resultadoBusquedaSensitiva) ){
                echo $resultadoBusquedaSensitiva;
			}
		} catch (PDOException $e) {
			$e->getMessage();
		}
	break;
	case "busquedaSensitivaUrgencia":
		try {
			$objCon->beginTransaction();
			$resultadoBusquedaSensitiva =  $objRegistroMedico->listaCie10Urgencia($objCon,$_POST['term']);
			if ( ! is_null($resultadoBusquedaSensitiva) && ! empty($resultadoBusquedaSensitiva) ){
                echo $resultadoBusquedaSensitiva;
			}
		} catch (PDOException $e) {
			$e->getMessage();
		}
	break;
	case 'consultaCIE10DAU':
		$parametros 				= getFormulario($_POST);
		$parametros['dau_mov_tipo'] = 'rem';
		try {
			$objCon->beginTransaction();
			$resp 					= $objConsulta->consultarCIE10paciente($objCon,$parametros);
			$resp2 					= $objConsulta->consultarTipoMovimiento($objCon,$parametros);
			if ( $resp[0]['dau_cierre_cie10'] ) {
				$response  			= array("status" => "encontrado", "id" =>$parametros['dau_id'], "cie" => $resp[0]['dau_cierre_cie10'], "tipo_mov" => $resp2[0]['dau_mov_tipo']);
			} else {
				$response  			= array("status" => "noencontrado", "id" =>$parametros['dau_id'], "cie" => 'no');
			}
			echo json_encode($response);
		} catch(PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "actualizarDatosDauCerrado":
		$parametros 									= getFormulario($_POST);
		$parametrosGuardarMovimiento['dau_id'] 			= $parametros['Iddau'];
		$parametrosGuardarMovimiento['dau_mov_usuario'] = $_SESSION["MM_UsernameName".SessionName];
		try {
			$objCon->beginTransaction();
			if ( $parametros['horaAcoholemia'] ) {
				$parametros['horaAcoholemia'] 			= date("Y-m-d H:i:s", strtotime($parametros['horaAcoholemia']));
				$datos 		 							= $objDetalleDau->getDatosAlcoholemia($objCon, $parametrosGuardarMovimiento);
				$datosVacios 							= array_filter($datos[0]);
				if ( ! empty($datosVacios) ) {
					$datos[0]['dau_alcoholemia_fecha'] 	= date("Y-m-d H:i:s", strtotime($datos[0]['dau_alcoholemia_fecha']));
					$arrayEdit = array(
										"edit" => $datos[0]['dau_alcoholemia_fecha'],
										$datos[0]['dau_alcoholemia_apreciacion'],
										$datos[0]['dau_alcoholemia_numero_frasco'],
										$datos[0]['dau_alcoholemia_resultado'],
										$datos[0]['dau_alcoholemia_estado_etilico'],
										$datos[0]['dau_alcoholemia_medico']
									);

					$arrayBase = array(
										"base" => $parametros['horaAcoholemia'],
										$parametros['frm_observacion_alcoholemia'],
										$parametros['frm_nro'],
										$parametros['resultado'],
										$parametros['frm_etilico'],
										$parametros['frm_profesional_alcoholemia']
									);
					$resultado = array_diff_assoc($arrayEdit, $arrayBase);
					if (!empty($resultado)) {
						$parametrosGuardarMovimiento['dau_mov_descripcion'] = "actualización alcoholemia";
						$parametrosGuardarMovimiento['dau_mov_tipo'] 		= "aca";
						$objMovimiento->guardarMovimiento($objCon,$parametrosGuardarMovimiento);
					}
				} else {
					$parametrosGuardarMovimiento['dau_mov_descripcion'] 	= "alcoholemia";
					$parametrosGuardarMovimiento['dau_mov_tipo'] 			= "alc";
					$objMovimiento->guardarMovimiento($objCon,$parametrosGuardarMovimiento);
				}
			}
			if ( $parametros['frm_fecha_egreso_adm'] ) {
				$parametros['frm_fecha_egreso_adm'] 				= date("Y-m-d H:i:s", strtotime($parametros['frm_fecha_egreso_adm']));
			}
			$objCierre->cierreAdministrativoDAU($objCon, $parametros);
			$parametrosGuardarMovimiento['dau_mov_descripcion'] 	= "actualización cierre dau";
			$parametrosGuardarMovimiento['dau_mov_tipo'] 			= "acc";
			$objMovimiento->guardarMovimiento($objCon,$parametrosGuardarMovimiento);
			$objCon->commit();
			$response  = array("status" => "success", "id" =>$parametros['Iddau']);
			echo json_encode($response);
		} catch (PDOException $e) {
			$e->getMessage();
		}
	break;

	case "cerrarDAU":
		$parametros                              	 = getFormulario($_POST);
		if ( $parametros['frm_indicacion_egreso'] ) {
			$parametros['frm_indicacion_egreso_h']	 = $parametros['frm_indicacion_egreso'];
		}
		if ( $parametros['frm_alta_derivacion'] ) {
			$parametros['frm_destionos_h']			 = $parametros['frm_alta_derivacion'];
		}
		if ( $parametros['frm_especialidad'] ) {
			$parametros['frm_especialidad_h']		 = $parametros['frm_especialidad'];
		}
		if ( $parametros['frm_aps'] ) {
			$parametros['frm_aps_h']			     = $parametros['frm_aps'];
		}
		if ( $parametros['frm_otros'] ) {
			$parametros['frm_otros_h']			     = $parametros['frm_otros'];
		}
		switch ( $parametros['frm_indicacion_egreso_h'] ) {
			case 3:
				switch ($parametros['frm_destionos_h']) {
					case 1:
						$parametros['frm_sum_indicacion']	= 1;
					break;

					case 2:
						$parametros['especialidad']			= $parametros['frm_especialidad_h'];
						$parametros['frm_sum_indicacion'] 	= 12;
					break;

					case 3:
						$parametros['aps']					= $parametros['frm_aps_h'];
						$parametros['frm_sum_indicacion'] 	= 14;
					break;

					case 4:
						$parametros['frm_sum_indicacion'] 	= 6;
					break;

					case 5:
						$parametros['frm_otros']			= $parametros['frm_otros_h'];
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
				$parametros['frm_sum_indicacion'] = $parametros['frm_radio_destino'];
			break;
			case 7:
				$parametros['frm_sum_indicacion'] = 13;
			break;

		}
		$parametros['frm_fecha_egreso_adm']			= date("Y-m-d H:i:s", strtotime($parametros['frm_fecha_egreso_adm']));
		$parametros['profesional']					= $_SESSION['MM_UsernameName'.SessionName];
		$parametros['rut_profesional']				= $_SESSION['MM_RUNUSU'.SessionName];
		$parametros['tipo_interconsulta']			= 'I';
		$parametros['indAplica']  					= $_SESSION["MM_UsernameName".SessionName];
		$parametros['reg_usuario_insercion'] 		= $_SESSION["MM_UsernameName".SessionName];
		$parametros['dau_cierre_administrativo'] 	= "S";
		if ( $parametros['frm_especialidad_h'] != '' ) {
			$parametros['especialidad']				= $parametros['frm_especialidad_h'];
		} else {
			$parametros['especialidad']				= $parametros['frm_especialidad_h'];
		}
		if ( $parametros['frm_auge'] == 'S' ) {
			$parametros['ague_interconsulta']		= "S";
		} else {
			$parametros['ague_interconsulta']		= "N";
		}
		if ( $parametros['frm_necesita_control'] == 1 ){
			$parametros['frm_necesita_control'] = 'NECESITA CONTROL';
		}
		if ( $parametros['frm_radio_defuncion'] == 1 ){
			$parametros['frm_radio_defuncion'] = 'ANATO.PATOLOGICA';
		} else if ($parametros['frm_radio_defuncion'] == 2 ) {
			$parametros['frm_radio_defuncion'] = 'SERV.MED.LEGAL';
		} else {
			$parametros['frm_radio_defuncion'] = '';
		}
		if ( $parametros['frm_estado_cierre'] == 5 ) {
			$parametros['reg_fecha_insercion']="";
		} else {
			$parametros['reg_fecha_insercion']      = date("Y-m-d H:i:s");
			$parametros['fecha_cierre_final']       = date("Y-m-d H:i:s");
		}
		if ( $parametros['horaAcoholemia'] ) {
			$parametros['horaAcoholemia'] 			= date("Y-m-d H:i:s", strtotime($parametros['horaAcoholemia']));
		}
		try {
			$objCon->beginTransaction();
			$pass = false;
			if ( $parametros['frm_fallecimiento_fecha'] && $pass == true ) {
				$parametros['frm_fallecimiento_fecha']	= date("Y-m-d H:i:s", strtotime($parametros['frm_fallecimiento_fecha']));
				$parametros['frm_id_paciente'];
				$parametros['frm_reporta']				= "Urgencia";
				$parametros['frm_notificacion']			= date("Y-m-d");
				$parametros['frm_fechaDefuncion']		= date("Y-m-d", strtotime($parametros['frm_fallecimiento_fecha']));
				$parametros['frm_hora']					= date("H:i", strtotime($parametros['frm_fallecimiento_fecha']));
				$parametros['frm_fechaIngreso']			= date("Y-m-d H:i:s");
				$usuario 								= $_SESSION["MM_UsernameName".SessionName];
				$parametros['reg_usuario_defuncion']	= $_SESSION["MM_UsernameName".SessionName];
				$response 								= $objPac->actualiza_estado_paciente($objCon,$parametros);
				$response 								= $objFallecido->grabar_estado($objCon, $parametros);
				$citas      							= $objFunAgenda->contadorCitas($objCon,$parametros);
				$motivo     							= 8;
				for ( $j = 0; $j < count($citas) ; $j++ ) {
					$respuesta3 = $objAgenda->cancelarCita($objCon,$citas[$j]['CITcodigo'],$motivo,'Paciente Registrado como Fallecido desde Indice Pacientes, Usuario:'.$usuario.' Fecha: '.date("d-m-Y H:i:s"));
				}
				$interconsulta = $objFunAgenda->contadorInterconsultas($objCon,$parametros);
				$tipoEgreso    = 2;
				$motivoEgreso  = 2;
				for( $i = 0; $i < count($interconsulta); $i++ ) {
					$respuesta4 = $objAgenda->egresarInterconsulta($objCon,$interconsulta[$i]['INTcodigo'],$tipoEgreso,$motivoEgreso,'Paciente Registrado como Fallecido desde Indice Pacientes, Usuario:'.$usuario.' Fecha: '.date("d-m-Y H:i:s"));
				}
			}
			if ($parametros['frm_fallecimiento_fecha_h'] != '') {
				$parametros['frm_fallecimiento_fecha_h']		= date("Y-m-d H:i:s", strtotime($parametros['frm_fallecimiento_fecha_h']));
			}
			$objCierre->cierreAdministrativoDAU($objCon, $parametros);
			if ( $parametros['dau_mov_descripcion'] == "actualizarDau" ) {
				$parametros['dau_id']				= $parametros['Iddau'];
				$parametros['dau_mov_descripcion']	= "actualizacion cierre dau";
				$parametros['dau_mov_tipo'] 		= "acc";
			} else {
				$parametros['dau_id']				=	$parametros['Iddau'];
				$parametros['dau_mov_descripcion']	= "cierre dau";
				$parametros['dau_mov_tipo'] 		= "cie";
			}
			$parametros['dau_mov_usuario'] 		= $_SESSION["MM_UsernameName".SessionName];
			$parametros['dau_mov_fecha']		= date("Y-m-d H:i:s");
			$objMovimiento->guardarMovimiento($objCon,$parametros);
			if ( isset($parametros['frm_indicacion_egreso']) ) {
				$tipo_Egreso = $parametros['frm_indicacion_egreso'];
			} else {
				$tipo_Egreso = $parametros['frm_indicacion_egreso_h'];
			}
			if ( $parametros['frm_dau_atencion'] == 1 || $parametros['frm_dau_atencion'] == 2 || $parametros['frm_dau_atencion'] == 3 ) {
				if ( $parametros['frm_est_id'] == 3 || $parametros['frm_est_id'] == 4 || $parametros['frm_est_id'] == 8 ){
					if ( $parametros['radio_egreso'] == 6 || $parametros['radio_egreso'] == 7 ) {
						$parametrosMP['id_dau'] = $parametros['Iddau'];
						$respMP 				= $objMapaPiso->getLugarPaciente($objCon, $parametrosMP);
						if ( count($respMP) > 0 ) {
							switch ($respMP[0]['sal_id']) {
								case '8':
									$respMP[0]['sal_desc'] = 'BOX1GO';
								break;
								case '9':
									$respMP[0]['sal_desc'] = 'BOX2GO';
								break;
								case '10':
									$respMP[0]['sal_desc'] = 'ECOGRAFO';
								break;
								case '11':
									$respMP[0]['sal_desc'] = 'BOXPERMEDLE';
								break;
								case '12':
									$respMP[0]['sal_desc'] = 'HIDRATACIONGO';
								break;
								case '13':
									$respMP[0]['sal_desc'] = 'ENTREVISTA';
								break;
								case '14':
									$respMP[0]['sal_desc'] = 'ESPERAGO';
								break;
								case '15':
									$respMP[0]['sal_desc'] = 'BOX1MP';
								break;
								case '16':
									$respMP[0]['sal_desc'] = 'BOX2MP';
								break;
								case '17':
									$respMP[0]['sal_desc'] = 'BOX3MP';
								break;
								case '18':
									$respMP[0]['sal_desc'] = 'BOX4MP';
								break;
								case '19':
									$respMP[0]['sal_desc'] = 'BOX5MP';
								break;
								case '20':
									$respMP[0]['sal_desc'] = 'BOX6MP';
								break;
								case '21':
									$respMP[0]['sal_desc'] = 'BOX7MP';
								break;
								case '22':
									$respMP[0]['sal_desc'] = 'BOX8MP';
								break;
								case '23':
									$respMP[0]['sal_desc'] = 'BOXCATMP';
								break;
								case '24':
									$respMP[0]['sal_desc'] = 'BOXLESMP';
								break;
								case '25':
									$respMP[0]['sal_desc'] = 'BOXREAMP';
								break;
								case '26':
									$respMP[0]['sal_desc'] = 'BOXOBSMP';
								break;
								case '27':
									$respMP[0]['sal_desc'] = 'BOXHIDMP';
								break;
								case '28':
									$respMP[0]['sal_desc'] = 'BOXP1MP';
								break;
								case '29':
									$respMP[0]['sal_desc'] = 'BOXP2MP';
								break;
								case '30':
									$respMP[0]['sal_desc'] = 'BOXP3MP';
								break;
								case '31':
									$respMP[0]['sal_desc'] = 'BOXPASPEDMP';
								break;
								case '32':
									$respMP[0]['sal_desc'] = 'BOXCAMLIBMP';
								break;
								default:
								break;
							}
							$objCierre->vaciarCamaCierre($objCon, $parametros);
							$response  			= array(
												"status" 		=> "success",
												"id" 			=> $parametros['Iddau'],
												"tipo_Egreso" 	=> $tipo_Egreso,
												"atencion" 		=> $parametros['frm_dau_atencion'],
												"estado" 		=> $parametros['frm_est_id'],
												"idSalaCama" 	=> $respMP[0]['sal_desc']."_".$respMP[0]['cam_descripcion']
											);
							$parametrosUIC 		= array(
													"dau_id" 						=> $parametros['Iddau'],
													"dau_mov_cama_usuario_egreso" 	=>  $parametros['dau_mov_usuario'],
													"dau_mov_cama_estado" 			=> "egresadoCama"
												);
							$resp_ultimoIdCama 					= $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
							$parametrosUIC['id_ultimoMovCam'] 	= $resp_ultimoIdCama[0]['id'];
							$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);
						} else {
							$response  			= array(
												"status" 		=> "success",
												"id" 			=>$parametros['Iddau'],
												"tipo_Egreso" 	=> $tipo_Egreso,
												"atencion" 		=> $parametros['frm_dau_atencion'],
												"estado" 		=> $parametros['frm_est_id']);
						}
					} else {
						$response  				= array(
											"status" 		=> "success",
											"id" 			=>$parametros['Iddau'],
											"tipo_Egreso" 	=> $tipo_Egreso,
											"atencion" 		=> $parametros['frm_dau_atencion'],
											"estado" 		=> $parametros['frm_est_id']
										);
					}
				} else {
					$response  					= array(
										"status" 		=> "success",
										"id" 			=> $parametros['Iddau'],
										"tipo_Egreso" 	=> $tipo_Egreso,
										"atencion" 		=> $parametros['frm_dau_atencion'],
										"estado" 		=> $parametros['frm_est_id']
									);
				}
			}
			$objCMBD->iniciarCMBD($objCon, $parametros["Iddau"], 6);
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;



	




	



	



	case "crearInterconsulta":

		require_once("../../../class/Cierre.class.php"); 	$objCierre 		= new Cierre;
		require_once("../../../class/Agenda.class.php");  	$objAgenda		= new Agenda;
		require_once("../../../class/Dau.class.php" ); 		$objDetalleDau	= new Dau;

		$objCon->db_connect();

		$parametros  = getFormulario($_POST);

		$parametros['cod_establecimiento']		= 101100;
		$parametros['establecimiento']			= 'HOSPITAL REGIONAL DE ARICA DR. JUAN NOÉ CREVANI';
		$parametros['prioridad']				= 5;
		$parametros['estado_inicial']			= 1;
		$parametros['otro_motivo']				= 5;
		$parametros['fecha_inicio']				= date("Y-m-d");
		$parametros['hora_inicio']				= date("H:i:s");
		$parametros['procedencia']				= 2;
		$parametros['profesional']				= $_SESSION['MM_UsernameName'.SessionName];
		$parametros['rut_profesional']			= $_SESSION['MM_RUNUSU'.SessionName];
		$parametros['tipo_interconsulta']		= 'I';
		$parametros['indAplica'] 				= $_SESSION["MM_UsernameName".SessionName];
		$parametros['nombreCie10']      		= '';
		$parametros['hipo_final']				= '';
		$parametros['dau_cierre_administrativo'] = "S";
		$parametros['reg_usuario_insercion']	 = $_SESSION["MM_UsernameName".SessionName];

		try {

			$objCon->beginTransaction();

			$rsDatosDauInterconsulta = $objDetalleDau->getDatosIngresosInterconsulta($objCon, $parametros);

			if ( $rsDatosDauInterconsulta[0]['dau_cierre_ind_especialidad'] != '' ) {
				$parametros['especialidad']			= $rsDatosDauInterconsulta[0]['dau_cierre_ind_especialidad'];
			}

			if ( $rsDatosDauInterconsulta[0]['dau_cierre_auge'] == 'S' ){
				$parametros['ague_interconsulta']		= "S";
			} else {
				$parametros['ague_interconsulta']		= "N";
			}

			$parametros['frm_id_paciente']	= $rsDatosDauInterconsulta[0]['id_paciente'];
			$resPaciente					= $objDetalleDau->obtenerPaciente($objCon,$parametros);
			$parametros['rut_paciente']		= $resPaciente[0]['rut'];
			$parametros['fecha_nacimiento']	= $resPaciente[0]['fechanac'];
			$parametros['nombre_completo']	= $resPaciente[0]['nombres']." ".$resPaciente[0]['apellidopat']." ".$resPaciente[0]['apellidomat'];

			if ( $rsDatosDauInterconsulta[0]['dau_cierre_atl_der_id'] == 2 && $rsDatosDauInterconsulta[0]['dau_cierre_ind_especialidad'] != '' ) {

				$interconsulta 			   = $objAgenda->insertarNuevaInterconsulta($objCon,$parametros);
				if ( $interconsulta != '' ) {
						$message= "Paciente ha sido dado de alta, Se le ha creado una interconsulta <strong>"."N°: ".$interconsulta."<strong>";
				}

			} else {

				$message = 'El paciente no cumple con los requisitos para crear interconsulta.';

			}

			$response  = array("status" => "success", "id" =>$parametros['dau_id'], "message" => $message);

			$objCon->commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case "cd_enviarPacienteATransito":

		require_once("../../../class/Dau.class.php" ); 						$objDetalleDau 	= new Dau;
		require_once("../../../class/SolicitudHospitalizacion.class.php");  $objSolHosp		= new SolicitudHospitalizacion;

		$objCon->db_connect();

		$parametros = getFormulario($_POST);

		try {

			$objCon->beginTransaction();

			$message = '';
			$paciente_SolHosp_Cama;
			$serv_estaCama;
			$sala_estaCama;
			$cama_estaCama;
			$idpaciente_estaCamasn;
			$idpaciente_estaTran;

			$resp_ind_egr = $objDetalleDau->getEgresoCierreAdministrativo($objCon, $parametros);

			if ( $resp_ind_egr[0]['dau_indicacion_egreso'] == '4' ) {

				$parametros['idctacte']                          = $resp_ind_egr[0]['idctacte'];
				$parametros['id_rau']                            = $resp_ind_egr[0]['id_rau'];
				$parametros['servicio']                          = $resp_ind_egr[0]['servicio'];
				$parametros['id_paciente']                       = $resp_ind_egr[0]['id_paciente'];
				$parametros['rut']                               = $resp_ind_egr[0]['rut'];
				$parametros['nroficha']                          = $resp_ind_egr[0]['nroficha'];
				$parametros['nombreFull']                        = $resp_ind_egr[0]['nombres']." ".$resp_ind_egr[0]['apellidopat']." ".$resp_ind_egr[0]['apellidomat'];
				$parametros['fechaHoraActual_Hospitalizacion']   = $resp_ind_egr[0]['FechaHoraActual'];
				$parametros['FechaActual']                       = $resp_ind_egr[0]['FechaActual'];
				$parametros['HoraActual']                        = $resp_ind_egr[0]['HoraActual'];
				$parametros['dau_hipotesis_diagnostica_inicial'] = $resp_ind_egr[0]['dau_hipotesis_diagnostica_inicial'];
				$parametros['dau_id']                            = $resp_ind_egr[0]['dau_id'];
				$parametros['dau_indicacion_egreso']             = $resp_ind_egr[0]['dau_indicacion_egreso'];
				$parametros['dau_cierre_servicio']               = $resp_ind_egr[0]['dau_cierre_servicio'];

				$resp_pac_hops = $objSolHosp->pacienteSeEncuentraHospitalizado($objCon, $parametros);

				if ( count($resp_pac_hops) < 1 ) {

					$resp_pac_hops_sn = $objSolHosp->pacienteSeEncuentraHospitalizadoSN($objCon, $parametros);

					if ( count($resp_pac_hops_sn) < 1 ) {

						$resp_pac_transpac = $objSolHosp->pacienteSeEncuentraTransitoPac($objCon, $parametros);

						if ( count($resp_pac_transpac) < 1 ) {

							$paciente_SolHosp_Cama = false;

						} else {

							$idpaciente_estaTran 	= $resp_pac_transpac[0]['id_paciente'];
							$paciente_SolHosp_Cama 	= true;
							$message 				= "El paciente ya se encuentra en Transito Paciente.";

						}

					} else {

						$idpaciente_estaCamasn = $resp_pac_hops_sn[0]['idPacienteSN'];
						$paciente_SolHosp_Cama = true;
						$message = "El paciente ya se encuentra Hospitalizado en una Cama Super Numeraria.";

					}

				} else {

					$serv_estaCama =  $resp_pac_hops[0]['servicio'];
					$sala_estaCama =  $resp_pac_hops[0]['sala'];
					$cama_estaCama =  $resp_pac_hops[0]['cama'];
					$paciente_SolHosp_Cama = true;
					$message = "El paciente ya se encuentra Hospitalizado en: <br>- Servicio: <strong>".$serv_estaCama."</strong><br>- Sala: <strong>".$sala_estaCama."</strong><br>- Cama: <strong>".$cama_estaCama."</strong>";

				}

				if ( $paciente_SolHosp_Cama == false ) {

					$objSolHosp->insertPacienteTransitoPac($objCon, $parametros);
					$message = "El paciente ya se ha enviado a Transito Paciente.";

				}

			} else {

				$message = 'Al paciente no se le ha indicado un egreso hacia Hospitalización.';

			}

			$response  = array("status" => "success", "id" =>$parametros['dau_id'], "message" => $message);

			$objCon->commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'addCategorizacionDAU':

		require_once("../../../class/Categorizacion.class.php" ); 	$objCategorizacion 	= new Categorizacion();
		require_once("../../../class/Dau.class.php" ); 				$objDetalleDau 		= new Dau();

		$objCon->db_connect();

		$parametros                              = getFormulario($_POST);

		try {

			$objCon->beginTransaction();

			$parametros["categorizacion"] 			= $parametros['frm_addCatDAU_categorizacion'];
			$parametros["dau_cat_posterior"] 		= 1;
			$parametros["dau_cat_posterior_motivo"] = $parametros['frm_addCatDAU_txtMotivo'];
			$parametros["dau_cat_usuario_inserta"] 	= $parametros['inp_idUsuario'];

			$objCategorizacion->agregarCategorizacionPosteriorDAU($objCon, $parametros);
			$objCategorizacion->updateDAUCategorizacionPosterior($objCon, $parametros);

			$resp 			= $objDetalleDau->nombrePacienteDAU($objCon,$parametros);
			$nombre = $resp[0]['nombres']." ".$resp[0]['apellidomat']." ".$resp[0]['apellidopat'];

			$message = "El usuario <strong>".$parametros["dau_cat_usuario_inserta"]."</strong>, ha categorizado correctamente a el/la paciente: <br><br><strong>".$nombre."</strong><br>";

			$response  = array("status" => "success", "id" =>$parametros['dau_id'], "message" => $message);

			$objCon->commit();

			echo json_encode($response);

		} catch(PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'obtenerDatosPaciente':

		require_once("../../../class/Dau.class.php" ); $objDetalleDau = new Dau();

		$objCon->db_connect();

		$parametros = getFormulario($_POST);

		try {

			$objCon->beginTransaction();

			$resp 		= $objDetalleDau->nombrePacienteDAU($objCon,$parametros);

			$nombre 	= $resp[0]['nombres']." ".$resp[0]['apellidomat']." ".$resp[0]['apellidopat'];

			$response  	= array("status" => "success", "id" =>$parametros['dau_id'], "nombre" => $nombre);

			$objCon->commit();

			echo json_encode($response);

		} catch(PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	



	case 'pacienteNulo':

		require_once("../../../class/Cierre.class.php");  		 $objCierre	     = new Cierre;
		require_once("../../../class/Movimientos.class.php");    $objMovimiento  = new Movimientos;

		$objCon->db_connect();

		$parametros = getFormulario($_POST);

		$parametros['radio_egreso']      			= 6;
		$parametros['Iddau']             			= $parametros['dau_id'];
		$parametros['frm_motivo_egreso'] 			= $parametros['frm_observacionPacienteNulo'];
		$parametros['dau_cierre_administrativo'] 	= 'S';
		$parametros['dau_mov_usuario'] 				= $_SESSION["MM_UsernameName".SessionName];
		$parametros['dau_mov_descripcion'] 		 	= "Nulo dau";
		$parametros['dau_mov_tipo']              	= "nulo";

		try {

			$objCon->beginTransaction();

			$objCierre->cierreAdministrativoDAU($objCon,$parametros);

			$objMovimiento->guardarMovimiento($objCon,$parametros);

			if ( $parametros['frm_dau_atencion'] == 1 || $parametros['frm_dau_atencion'] == 2 || $parametros['frm_dau_atencion'] == 3 ) {

				if ( $parametros['frm_est_id'] == 3 || $parametros['frm_est_id'] == 4 || $parametros['frm_est_id'] == 8 ) {

					if ( $parametros['radio_egreso'] == 6 ) {

						require_once("../../../class/MapaPiso.class.php"); $objMapaPiso = new MapaPiso;

						$parametrosMP['id_dau'] = $parametros['Iddau'];

						$respMP                 = $objMapaPiso->getLugarPaciente($objCon, $parametrosMP);

						$objCierre->vaciarCamaCierre($objCon, $parametros);

						$response  = array(
											"status" 		=> "success",
											"id" 			=> $parametros['Iddau'],
											"tipo_Egreso" 	=> $tipo_Egreso,
											"atencion" 		=> $parametros['frm_dau_atencion'],
											"estado" 		=> $parametros['frm_est_id'],
											"idSalaCama" 	=> $respMP[0]['sal_desc']."_".$respMP[0]['cam_descripcion']
										);

						$parametrosUIC  = array(
												"dau_id" 						=> $parametros['Iddau'],
												"dau_mov_cama_usuario_egreso" 	=>  $parametros['dau_mov_usuario'],
												"dau_mov_cama_estado" 			=> "egresadoCama"
											);

						$resp_ultimoIdCama                = $objMovimiento->getIdDauMovimientoCama($objCon, $parametrosUIC);
						$parametrosUIC['id_ultimoMovCam'] = $resp_ultimoIdCama[0]['id'];

						$objMovimiento->actualizarMovimientoCama($objCon, $parametrosUIC);

					} else {

						$response  = array(
											"status" 		=> "success",
											"id" 			=>$parametros['Iddau'],
											"tipo_Egreso" 	=> $tipo_Egreso,
											"atencion" 		=> $parametros['frm_dau_atencion'],
											"estado" 		=> $parametros['frm_est_id']
										);
					}

				} else {

					$response  = array(
										"status" 		=> "success",
										"id" 			=> $parametros['Iddau'],
										"tipo_Egreso" 	=> $tipo_Egreso,
										"atencion" 		=> $parametros['frm_dau_atencion'],
										"estado" 		=> $parametros['frm_est_id']
									);

				}

			}

			//DAU ANULADO
			$objCon->setDB("dau");
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 6);

			$objCon->commit();

			echo json_encode($response);

		} catch(PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case 'pacienteNEA':

		require_once("../../../class/Dau.class.php" );  $objDetalleDau      = new Dau;

		$objCon->db_connect();

		$parametros = getFormulario($_POST);

		$datosDAU   = $objDetalleDau->ListarPacientesDau($objCon, $parametros);

		$response   = array(
								"est_id"       =>  $datosDAU[0]['est_id'],
								"dau_atencion" =>  $datosDAU[0]['dau_atencion']
							);
		echo json_encode($response);

	break;



	case "obtenerDatosPacienteParaReemplazo":
	  $parametros = $objUtil->getFormulario($_POST);
		$datosPaciente = array();
		if ($objUtil->existe($parametros["rut"])) {
			$datosPaciente["rut"] = $objUtil->formatearRutASoloNumero($parametros["rut"]);
		}

		if ($objUtil->existe($parametros["rut_extranjero"])) {
			$datosPaciente["rut_extranjero"] = $objUtil->asignar($parametros["rut_extranjero"]);
		}

		$objCon->db_connect();
		echo json_encode($objPac->obtenerDatosPacienteParaReemplazo($objCon, $datosPaciente));
		break;



	case "reemplazarDatosPacienteNN":
		require_once("../../../class/Dau.class.php" );
		require_once("../../../class/Rce.class.php" );
		require_once("../../../class/Movimientos.class.php" );

		$objCon->db_connect();
		$objDetalleDau = new Dau;
		$objRCE = new Rce;
		$objMovimientos = new Movimientos;

		$parametros = $objUtil->getFormulario($_POST);
		$idDau = $objUtil->asignar($parametros["idDau"]);
		$idPacienteNN = $objUtil->asignar($parametros["idPacienteNN"]);
		$idPaciente = $objUtil->asignar($parametros["idPaciente"]);
		$ctaCte = $objUtil->asignar($parametros["ctaCte"]);

		$datosPaciente = $objPac->obtenerInformacionPaciente($objCon, $idPaciente);
		$rutPaciente = $objUtil->asignar($datosPaciente["rut"]);
		$rutExtranjeroPaciente = $objUtil->asignar($datosPaciente["rut_extranjero"]);
		$nombrePaciente = $objUtil->asignar($datosPaciente['nombres'])
			. " " . $objUtil->asignar($datosPaciente['apellidopat'])
			. " " . $objUtil->asignar($datosPaciente['apellidomat']);
		$numeroFicha = $objUtil->asignar($datosPaciente["nroficha"]);
		$sexoPaciente = $objUtil->asignar($datosPaciente["sexo"]);
		$fechaNacimientoPaciente = $datosPaciente["fechanac"];
		$previsionPaciente = $objUtil->asignar($datosPaciente["prevision"]);

		$datosRCE = $objRCE->obtenerIdRCESegunDAU($objCon, $idDau);
		$idRCE = $objUtil->asignar($datosRCE["regId"]);

  	$datosReemplazo = array(
			"idDau" => $idDau,
			"idRCE" => $idRCE,
			"idPacienteNN" => $idPacienteNN,
			"idPaciente" => $idPaciente,
			"rutPaciente" => ($objUtil->existe($rutExtranjeroPaciente))
				? $rutExtranjeroPaciente
				: $rutPaciente,
			"nombrePaciente" => $nombrePaciente,
			"numeroFicha" => $numeroFicha,
			"sexoPaciente" => $sexoPaciente,
			"fechaNacimientoPaciente" => $fechaNacimientoPaciente,
			"previsionPaciente" => $previsionPaciente,
			"ctaCte" => $ctaCte,
			"usuarioReemplazo" => $_SESSION['MM_Username'.SessionName]
		);

		$datosMovimiento = array(
			"dau_id" => $idDau,
			"dau_mov_descripcion" => "Reemplazo paciente nn",
			"dau_mov_usuario" => $_SESSION["MM_UsernameName".SessionName],
			"dau_mov_tipo" => "reempl"
		);

		try {
			$objCon->beginTransaction();

			$objPac->modificarIdPacienteNNEnDauPacienteNN($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnDau($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnRegistroViolencia($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudAPS($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudEspecialista($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudEvolucion($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudInicioAtencion($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudSIC($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnSolicitudLaboratorio($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnCtaCte($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnDetallePrestacion($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnMatriz($objCon, $datosReemplazo);
			$objPac->modificarIdPacienteNNEnMatriz($objCon, $datosReemplazo);
			$objPac->eliminarPacienteEnPaciente($objCon, $datosReemplazo);
			$objMovimiento->guardarMovimiento($objCon, $datosMovimiento);

			$objCon->commit();

			$response  = array("status" => "success");
			echo json_encode($response);

		} catch(PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
		break;
}



function getFormulario($parametros){
	$array = array();
	foreach($parametros as $nombre_campo => $valor){
		$key = str_replace("$", "", $nombre_campo);
		$array[$key] = $valor;
	}
	return $array;
}
?>
