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
require_once('../../../class/Diagnosticos.class.php');  	$objDiagnosticos 	= new Diagnosticos;
require_once('../../../class/AltaUrgencia.class.php');  	$objAltaUrgencia 	= new AltaUrgencia;
require_once('../../../class/Evolucion.class.php');  		$objEvolucion 		= new Evolucion;
require_once('../../../class/Paciente.class.php');  		$objPaciente 		= new Paciente;
require_once('../../../class/Pronostico.class.php');  		$objPronostico 		= new Pronostico;
require_once('../../../class/FormularioSeguimiento.class.php'); $objFormulario 		= new FormularioSeguimiento;
require_once('../../../class/HospitalAmigo.class.php');        $objHospitalAmigo    = new HospitalAmigo;
require_once('../../../class/Imagenologia.class.php');        $objImagenologia    = new Imagenologia;

switch ($_POST['accion']) {
	case 'busquedaSensitiva_examenes':

		$parametros                   = $objUtil->getFormulario($_POST);
		try{
			$objCon->beginTransaction();
			echo $objImagenologia -> buscar_examenes($objCon,$_POST['term'],$_POST['tipoExamen']);
		}catch(PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}

	break;

	case 'cargarParametros':
		try{
			$objCon->beginTransaction();	
			$parametros                         = $objUtil->getFormulario($_POST);

			$rsExamenes = $objImagenologia->getExamenes3($objCon, $_POST['valor_combo']);

			foreach ($rsExamenes as $rsExamenes_clave => $rsExamenes_valor) {
				$html.="<option value='".$rsExamenes_valor['preCod']."-".$rsExamenes_valor['prePacienteUrgencia']."'>".$rsExamenes_valor['preNombre']."</option>";
			}

			$select.="<option value='0' disabled selected>Seleccione...</option>";
			$select.=$html;
			echo json_encode($select);			
			$objCon->commit();			
		}catch(PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
		
	break;
	case 'pacienteTieneIndicacionesNoSuperfluas':
		try {
			$objCon->beginTransaction();
			$respuestaIdRCE 			= $objRce->obtenerIdRCESegunDAU($objCon, $_POST['idDau']);
			$respuestaSolicitudes 		= $objRce->obtenerCantidadSolicitudesNoSuperfluas($objCon, $respuestaIdRCE['regId']);
			// echo $respuestaSolicitudes;
			// print('<pre>'); print_r($respuestaSolicitudes); print('</pre>');
			if( $respuestaSolicitudes['solicitudesImportantes'] === $respuestaSolicitudes['solicitudesAplicadas']) {
				$solicitudesAplicadas 	= 1;
			} else {
				$solicitudesAplicadas 	= 0;
			}
			// $solicitudesAplicadas 	= 0;
			$respuesta = array("status" => "success", "solicitudesAplicadas" => $solicitudesAplicadas);
			echo json_encode($respuesta);
		} catch ( PDOException $e ) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case "registrarIndicacionEgreso":
		$rsHorarioServidor         = $objUtil->getHorarioServidor($objCon);
		$parametros          = $objUtil->getFormulario($_POST);
		// print('<pre>'); print_r($parametros); print('</pre>');
		if( $parametros['frm_especialidad'] ){
			$codigosEspecialidad = obtenerCodigosEspecialidad($parametros['frm_especialidad']);
			$descripcionesEspecialidad = obtenerDescripcionEspecialidad($objCon, $parametros['frm_especialidad']);
			$parametros['descripcionAltaEspecialidad'] = $descripcionesEspecialidad;	
		}
		// $codigosEspecialidad = obtenerCodigosEspecialidad($parametros['frm_especialidad']);
		// $descripcionesEspecialidad = obtenerDescripcionEspecialidad($objCon, $parametros['frm_especialidad']);
		// $parametros['descripcionAltaEspecialidad'] = $descripcionesEspecialidad;
		$rsPronostico         		= $objPronostico->listarPronosticos($objCon);
		$parametros['estado_dau'] 		= 4;
		$parametros['estado_ind'] 		= 20;
		$parametros['indEgreso']  		= ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
		$parametros['especialidad'] 	=  NULL;
		$parametros['aps']		 	 	=  NULL;
		switch ( $parametros['frm_Indicacion_Egreso'] ) {
			case 3:
				switch ($parametros['frm_alta_derivacion']) {
					case 1:
						$parametros['frm_sum_indicacion']	=1;
						$parametros['especialidad']			="";
						$parametros['aps']					="";
						$parametros['frm_otros']			="";
					break;
					case 2:
						$parametros['especialidad']			= $codigosEspecialidad;
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
		$parametros['fecha_indicacion_egreso'] 	= date("Y-m-d H:i:s");
		$parametros['fecha_defuncion']			= date("Y-m-d H:i:s",strtotime($parametros['frm_fecha_defuncion']));
		$parametros['frm_fecha_modificar']		= $parametros['frm_fecha_date'].' '.$parametros['frm_hora_date'];
		try {
			$objCon->beginTransaction();
			$resp_estado = $objDau->obtenerEstadoDauPaciente($objCon,$parametros['dau_id']);
			$parametros['dauAbiertoMantenedor'] = $resp_estado['dau_abierto_mantenedor'];
			if ( $parametros['dauAbiertoMantenedor'] == 'S' ) {
				$parametros['dauAbiertoMantenedor'] = 'M';
			}
			if ( $resp_estado['est_id'] == 5 ) {
				$response = array("status" => "warning", "id" => $parametros['dau_id'], "message" => 'Al paciente ya se le Aplicó la Indicación de Egreso');
			} else {
				if ( $parametros['frm_Indicacion_Egreso'] != 4 ) {
					$parametros['frm_servicio_destino'] = NULL;
					$parametros['dau_mov_descripcion']  = 'indicacion egreso';
					$parametros['dau_mov_usuario'] 		= ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
					$registrarIndicacionEgreso 			= $objDau->registrarIndicacionEgreso($objCon,$parametros);
					$parametros['dau_mov_tipo'] 		= 'ind';
					$objMovimiento->guardarMovimiento($objCon, $parametros);
					$busquedaRce 			   = $objRegistroClinico->consultaRCE($objCon,$parametros);
					$parametros['rce_id']	   = $busquedaRce[0]['regId'];
					$actualizarRce 			   = $objRegistroClinico->actualizaRCE($objCon,$parametros);
					$parametros['dau_mov_descripcion'] = 'modificar alta urgencia (RCE)';
					$parametros['dau_mov_tipo'] = 'mau';
					$objMovimiento->guardarMovimiento($objCon, $parametros);
				} else {
					$parametros['dau_mov_descripcion'] = 'indicacion egreso';
					$parametros['dau_mov_usuario'] 	   = ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
					$registrarIndicacionEgreso = $objDau->registrarIndicacionEgreso($objCon,$parametros);
					$parametros['dau_mov_tipo'] = 'ind';
					$objMovimiento->guardarMovimiento($objCon, $parametros);
					$busquedaRce 			   = $objRegistroClinico->consultaRCE($objCon,$parametros);
					$parametros['rce_id']	   = $busquedaRce[0]['regId'];
					$actualizarRce 			   = $objRegistroClinico->actualizaRCE($objCon,$parametros);

					ingresarSeguimientoPaciente($objCon, $objUtil, $objDau, $parametros);

					$parametros['dau_mov_descripcion'] = 'modificar alta urgencia (RCE)';
					$parametros['dau_mov_tipo'] 	   = 'mau';
					$objMovimiento->guardarMovimiento($objCon, $parametros);

				}
				$parametros['usuario_defuncion_ingreso'] = ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
				if ( $parametros['frm_Indicacion_Egreso'] != 6 ) {
				 	$ActualizarIndicacionEgresoDau = $objDau->ActualizarIndicacionEgresoDau($objCon,$parametros);
				} else {
					$objDau->registrarFechaDefuncion($objCon, $parametros);
				}
				$objDau->insertarMovimientoIndicacion($objCon, $parametros);
				if ( $parametros['cie10_id'] != '' || $parametros['cie10_id'] != NULL ) {
					$parametros['dau_mov_descripcion'] 	= 'registro medico cie10 MPISO-IND ['.$parametros['cie10_id'].']';
					$parametros['dau_mov_tipo'] 		= "rmp";
					$parametros['dau_mov_usuario'] 		= ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
					$objMovimiento->guardarMovimiento($objCon, $parametros);

				}
				$parametrosActualizarAU['SAUfecha'] 		= date("Y-m-d H:i:s");
				$parametrosActualizarAU['SAUidRCE'] 		= $parametros['rce_id'];
				$parametrosActualizarAU['SAUidDau'] 		= $parametros['dau_id'];
				$parametrosActualizarAU['SAUidPaciente'] 	= $parametros['paciente_id'];
				$parametrosActualizarAU['SAUidCie10'] 		= $parametros['frm_codigoCIE10'];
				$parametrosActualizarAU['SAUcie10Abierto'] 	= $parametros['frm_cie10Abierto'];
				$parametrosActualizarAU['SAUindicaciones'] 	= $parametros['frm_indicaciones_alta'];
				$parametrosActualizarAU['SAUauge'] 			= $parametros['frm_auge'];
				$parametrosActualizarAU['SAUpertinencia'] 	= $parametros['frm_pertinencia'];
				$parametrosActualizarAU['SAUpostinor'] 		= $parametros['frm_postinor'];
				$parametrosActualizarAU['SAUusuario'] 		= ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];
				$respuestaConsulta = $objAltaUrgencia->ingresarSolicitudAltaUrgencia($objCon, $parametrosActualizarAU);
				$objRce->eliminarSolicitudSIC($objCon, $parametros['dau_id']);
				if ( $parametros['frm_alta_derivacion'] == 2 && ! empty($codigosEspecialidad) && ! is_null($codigosEspecialidad) ) {
					$parametrosSolicitudSic['SICdau'] 		 	 			= $parametros['dau_id'];
					$parametrosSolicitudSic['SICidPaciente'] 	 			= $parametros['paciente_id'];
					$parametrosSolicitudSic['SICfechaSolicitud'] 			= date('Y-m-d');
					$parametrosSolicitudSic['SIChoraSolicitud']  			= date('H:i:s');
					$parametrosSolicitudSic['SICprioridad']  				= $parametros['slc_prioridad'];
					$parametrosSolicitudSic['SICmotivoConsulta']			= $parametros['slc_motivoConsulta'];
					$parametrosSolicitudSic['SICotroMotivo']				= $parametros['frm_otrosMotivoConsulta'];
					$parametrosSolicitudSic['SIChipotesisDiagnostica']		= $parametros['frm_hipotesis_final'];
					if ( $parametros['frm_auge'] == '' ) {
						$parametrosSolicitudSic['SICauge']	 = 'N';
					} else {
						$parametrosSolicitudSic['SICauge']	 = 'S';
					}
					$parametrosSolicitudSic['SICfundamentoDiagnostico']		= $parametros['frm_cie10Abierto']."\r\n".$parametros['frm_indicaciones_alta'];
					$parametrosSolicitudSic['SICprofesionalDescripcion']	= $_SESSION['MM_UsernameName'.SessionName];
					$parametrosSolicitudSic['SICrunProfesional']			= $_SESSION['MM_RUNUSU'.SessionName];
					$arreglocodigosEspecialidad 						    = explode(', ', $codigosEspecialidad);

					for ( $i = 0; $i < count($arreglocodigosEspecialidad); $i++ ) {

						$parametrosSolicitudSic['SICespecialidadOrigen'] = $arreglocodigosEspecialidad[$i];

						$objRce->ingresarSolicitudSIC($objCon, $parametrosSolicitudSic);

					}
				}
				$parametrosAltaUrgencia['SAUidDau'] 			= $parametros['dau_id'];
				$parametrosAltaUrgencia['SAUid']   				= $respuestaConsulta;
				$parametrosAltaUrgencia['SAUusuarioAnula'] 		= $parametros['indEgreso'];
				$parametrosAltaUrgencia['SAUobservacionAnula'] 	= 'anulación por registro de nueva indicación de egreso';
				$objAltaUrgencia->anularSolicitudesUrgenciasPrevias($objCon, $parametrosAltaUrgencia);

				ingresarRegistroViolencia($objCon, $objUtil, $objRce, $parametros);

				//print('<pre>');print_r($parametros);print('</pre>');
				for ($q=0; $q<count($rsPronostico) ; $q++) {
					if($parametros['frm_pronostico'] == $rsPronostico[$q]['PRONcodigo']){
						$parametros['frm_pronostico_descripcion'] = $rsPronostico[$q]['PRONdescripcion'];
					}
				}
				$subparametros                                = array();
				$subparametros['BITid']                      = $parametros['dau_id'];
				$subparametros['BITtipo_codigo']              = 12;
				$subparametros['BITtipo_descripcion']		  = "Alta urgencia";
				$subparametros['BITusuario']                  = $parametros['dau_mov_usuario'];
				$subparametros['BITdescripcion']              = "<b>HIPÓTESIS FINAL CIE-10:</b> (".$parametros['frm_hipotesis_final'].")<br><b>DIAGNÓSTICO:</b> (".$parametros['frm_cie10Abierto'].")<br><b>INDICACIONES AL ALTA:</b><br>".$parametros['frm_indicaciones_alta']."<br><b>PRONÓSTICO:</b> (".$parametros['frm_pronostico_descripcion'].")<br><b>INDICACIÓN DE EGRESO:</b> (".$parametros['descripcionIndicacionEgreso']."-".$parametros['descripcionAltaDestinos'].")";
				$objBitacora->guardarBitacora($objCon,$subparametros);


				$response = array("status" => "success", "id" => $parametros['dau_id']);

			}


			$datosAcompaniante = array(
		        "idDau" => $objUtil->asignar($parametros["dau_id"]),
		        "entregaInformacion" => $objUtil->asignar($parametros["frm_entregaInformacion"]),
		        "motivo" => $objUtil->asignar($parametros["frm_motivo"]),
		        "nombreAcompaniante" => $objUtil->asignar($parametros["frm_nombreFamiliarOAcompaniante"]),
		        "horaEntregaInformacionMedica" => $rsHorarioServidor[0]['hora'],
		        "idUsuarioMedico" => $_SESSION['MM_Username'.SessionName],
		        "nombreMedico" => $_SESSION['MM_UsernameName'.SessionName]
		    );
		    $rsobtenerAcompaniante = $objHospitalAmigo->obtenerAcompaniante($objCon, $datosAcompaniante);
		    if(count($rsobtenerAcompaniante) > 0 ){
		    	$objHospitalAmigo->UpdateFamiliarOAcompaniante($objCon, $datosAcompaniante);
		    }else{
		    	$idDauAcompaniante = $objHospitalAmigo->ingresarFamiliarOAcompaniante($objCon, $datosAcompaniante);
		    }

			//CMBD INDICACIÓN EGRESO
			// $objCon->setDB("dau");
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 4);
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);

		}

	break;

	case 'agresorSegunTipoViolencia':
		$parametros 	= $objUtil->getFormulario($_POST);
		$tipoAgresores 	= $objRce->obtenerTipoAgresorSegunViolencias($objCon, $parametros['idTipoViolencia']);
		if ( ! empty($tipoAgresores) && ! is_null($tipoAgresores) ) {
			echo json_encode($tipoAgresores);
		}
	break;
	case 'pacienteEgresado':
		$idDau 				= $_POST['idDau'];
		$pacientreEgresado	= $objAltaUrgencia->pacienteEgresado($objCon, $idDau);
		if ( ! is_null($pacientreEgresado[0]['estadoDau']) && ! empty($pacientreEgresado[0]['estadoDau']) && $pacientreEgresado[0]['estadoDau'] == 5 ) {
			$respuesta = array("status" => "success");
		} else {
			$respuesta = array("status" => "error");
		}

			// $respuesta = array("status" => "error");
			// $respuesta = array("status" => "success");
		echo json_encode($respuesta);
	break;
	case 'buscarIngresoSignosVitalesPrioritarios':
		$parametros = $objUtil->getFormulario($_POST);
		$resultado = $objRce->buscarIngresoSignosVitalesPrioritarios($objCon, $parametros['idRCE']);
		echo json_encode($resultado);
	break;
	case 'obtenerDestinos':
		$destinos = $objFormulario->obtenerDestinos($objCon);
		echo json_encode($destinos);
	break;
	case 'obtenerAntecedentesEpidemiologicos':
		$antecedentesEpidemiologicos = $objFormulario->obtenerAntecedentesEpidemiologicos($objCon);
		echo json_encode($antecedentesEpidemiologicos);
	break;
	case 'obtenerEstadosIngreso':
		$estadosIngreso = $objFormulario->obtenerEstadosIngreso($objCon);
		echo json_encode($estadosIngreso);
	break;
	case 'guardarFormularioSeguimiento':
		$parametros = $objUtil->getFormulario($_POST);
		try {
			$objCon->beginTransaction();
			guardarFormulario($objCon, $objUtil, $objFormulario, $parametros);
			$objCon->commit();
			$response = array("status" => "success");
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case 'obtenerResultadosMuestrasAnteriores':
		$parametros 					= $objUtil->getFormulario($_POST);
		$resultadosMuestrasAnteriores 	= $objFormulario->obtenerResultadosMuestrasAnteriores($objCon, $parametros['idPaciente']);
		echo json_encode($resultadosMuestrasAnteriores);
	break;
	case 'obtenerInformacionTomaMuestra':
		$parametros = $objUtil->getFormulario($_POST);
		if($parametros['idFormulario'] != 0){
			$informacionTomaMuestra = $objFormulario->obtenerInformacionTomaMuestra($objCon, $parametros['idFormulario']);
		}
		echo json_encode($informacionTomaMuestra);
	break;
	case 'obtenerInformacionPaciente':
		$parametros				= $objUtil->getFormulario($_POST);
		$informacionPaciente 	= $objPaciente->obtenerInformacionPaciente($objCon, $parametros['idPaciente']);
		echo json_encode($informacionPaciente);
	break;
	case 'actualizarFormularioSeguimiento':
		$parametros = $objUtil->getFormulario($_POST);
		try {
			$objCon->beginTransaction();
			$parametros['form_int_estado']   = $parametros['estadoFormulario'];
			$parametros['form_int_cant_int'] = $parametros['cantidadFormulario'];
			if ( ($parametros['estadoFormulario'] != 1 ) && ($parametros['estadoMuestra'] != 8 && $parametros['estadoMuestra'] != 9 ) ) {
				$parametros['form_int_estado']   		= 1;
				$parametros['form_int_cant_int'] 		= $parametros['cantidadFormulario'] + 1;
				$parametros['estado_muestra']    		= 10;
				$parametros['seg_usuario']    	 		= $_SESSION['MM_Username'.SessionName];
				$parametros['form_id']    				= $parametros['idFormulario'];
				$parametros['seg_motivo']  				= 11;
				$parametros['seg_observacion_general']  = "Nueva muestra COVID-19 en espera de confirmación.";
				guardarTomaMuestra($objCon, $objUtil, $objFormulario, $parametros);
				insertarSeguimiento($objCon, $objUtil, $objFormulario, $parametros);
			}
			actualizarFormulario($objCon, $objUtil, $objFormulario, $parametros);
			$objCon->commit();
			$response = array("status" => "success");
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}

	break;
	case 'verificarExamenPositivo':
		$parametros = $objUtil->getFormulario($_POST);
		$examenPositivo = $objFormulario->verificarExamenPositivo($objCon, $parametros['idPaciente']);
		echo json_encode($examenPositivo);
	break;
	case 'verificarSeguimientoEnfermedadRespiratoria':
		$parametros = $objUtil->getFormulario($_POST);
		$respuesta = $objFormulario->obtenerInformacionSeguimiento($objCon, $parametros['idPaciente']);
		echo json_encode($respuesta);
	break;
	case 'obtenerPlantillaInicioAtencion':
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

	case 'crearPlantillaInicioAtencion':
    $parametros 				= $objUtil->getFormulario($_POST);
    $banderaError 				= false;
    $parametros['idMedico'] 	= $_SESSION['MM_Username'.SessionName];
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

	case 'ingresarSolicitudEvolucion':
        $parametros 						= $objUtil->getFormulario($_POST);
		$rsHorarioServidor              	= $objUtil->getHorarioServidor($objCon);
        $parametrosSEVO['SEVOfecha'] 		= $rsHorarioServidor[0]['fecha']." ".$rsHorarioServidor[0]['hora'];
        $parametrosSEVO['SEVOidRCE'] 		= $parametros['rce_id'];
        $parametrosSEVO['SEVOidPaciente'] 	= $parametros['id_paciente'];
        $parametrosSEVO['SEVOevolucion'] 	= $parametros['frm_historia_clinica'];
        $parametrosSEVO['SEVOusuario'] 		= $_SESSION['MM_Username'.SessionName];
        $subparametrosBitacora['BITdescripcion']="";
        try {
            $objCon->beginTransaction();
            $idSEVO = $objEvolucion->ingresarSolicitudEvolucion($objCon, $parametrosSEVO);
            ////// AQUI PASO COCHITO //////
            $subparametrosBitacora['BITid']                =   $parametros['dau_id'];
            $subparametrosBitacora['BITtipo_codigo']        =   8;
            $subparametrosBitacora['BITtipo_descripcion']   =   "Evolución";
            $subparametrosBitacora['BITdatetime']           =   "NOW()";
            $subparametrosBitacora['BITusuario']            =   $parametrosSEVO['SEVOusuario'];
            $subparametrosBitacora['BITdescripcion']        .=  "<b>EVOLUCION:</b> <br> ".$parametros['frm_historia_clinica']." ";
            $objBitacora->guardarBitacora($objCon,$subparametrosBitacora);
            ////// AQUI SE FUE COCHITO //////
            $parametrosDau['dau_id'] 					= $parametros['dau_id'];
            $parametrosDau['dau_usuario_ultima_evo'] 	= $_SESSION['MM_Username'.SessionName];
            $objDau->ActualizarDauEvo($objCon,$parametrosDau);
            

            $response = array("status" => "success");
            echo json_encode($response);
            $objCon->commit();
        } catch (PDOException $e) {
            $objCon->rollback();
            $response = array("status" => "error", "message" => $e->getMessage());
            echo json_encode($response);
        }
    break;

	case "verificarEstadoPaciente":
		$parametros                     = $objUtil->getFormulario($_POST);
		$datos['usuario']           	= $_SESSION['MM_Username'.SessionName];
		try{
			$objCon->beginTransaction();
			$estadoDau 		= $objDau->obtenerEstadoDauPaciente($objCon, $parametros['dau_id']);
			if ( $estadoDau['est_id'] == 2 ) {
				$textoError = "Paciente ya se encuentra categorizado por otra persona. Se recargó el Registro Clínico para que visualice los cambios.";
				$response = array("status" => "error", "textoError" => $textoError);
			}else if ( $estadoDau['est_id'] == 6 ) {
				$textoError = "Paciente ya se encuentra con estado aplicado de NULO. Se recargó el Registro Clínico para que visualice los cambios.";
				$response = array("status" => "error", "textoError" => $textoError);
			}else if ( $estadoDau['est_id'] == 7) {
				$textoError = "Paciente ya se encuentra con estado aplicado de N.E.A. (Posiblemente por Otra Persona). Se recargó el Registro Clínico para que visualice los cambios.";
				$response = array("status" => "error", "textoError" => $textoError);
			}else{
				$response = array("status" => "success");
			}
			$existeSolicitudAltaUrgencia = $objAltaUrgencia->existeSolicitudAltaUrgencia($objCon, $parametros['dau_id']);
			if ( count($existeSolicitudAltaUrgencia) > 0 && $existeSolicitudAltaUrgencia[0]['tipoSolicitud'] != 4) {
				$textoError = "Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona), no se puede agregar más registros.";
				$response = array("status" => "error", "textoError" => $textoError);
			}

			$objCon -> commit();
			echo json_encode($response);
		}catch (PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "pacienteYaConNEA":
		$parametros                     = $objUtil->getFormulario($_POST);
		$datos['usuario']           	= $_SESSION['MM_Username'.SessionName];
		try{
			$objCon->beginTransaction();
			$estadoDau 		= $objDau->obtenerEstadoDauPaciente($objCon, $parametros['dau_id']);
			 if ( $estadoDau['est_id'] == 7) {
				$textoError = "Paciente ya se encuentra con estado aplicado de N.E.A. (Posiblemente por Otra Persona). Se recargó el Registro Clínico para que visualice los cambios.";
				$response = array("status" => "error", "textoError" => $textoError);
			}else{
				$response = array("status" => "success");
			}
			$objCon -> commit();
			echo json_encode($response);
		}catch (PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "eliminarDiagnosticoRCE":
			$parametros                     = $objUtil->getFormulario($_POST);
			$datos['usuario']           	= $_SESSION['MM_Username'.SessionName];
			try{
				$objCon->beginTransaction();
				$rsHorarioServidor                                  = $objUtil->getHorarioServidor($objCon);

				$parametros['id_compartido']                        = $parametros['id'];
				$rsRce_diagnostico                                  = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
				$parametrosLog['rce_diagnostico_cie10']             = $rsRce_diagnostico[0]['id_cie10'];
				$parametrosLog['rce_diagnostico_fecha_agregado']    = $rsRce_diagnostico[0]['fecha'];
				$parametrosLog['rce_diagnostico_hora_agregado']     = $rsRce_diagnostico[0]['hora'];
				$parametrosLog['rce_diagnostico_usuario_agregado']  = $rsRce_diagnostico[0]['usuario'];
				$parametrosLog['rce_evolucion_id']                  = $parametros['rce_evolucion_id'];
				$parametrosLog['rce_id']                            = $parametros['rce_id'];
				$parametrosLog['rce_diagnostico_cie10_descrip']     = $rsRce_diagnostico[0]['diagnistico_descripcion_text'];
				$parametrosLog['rce_diagnistico_descripcion_text']  = $rsRce_diagnostico[0]['diagnistico_descripcion_text_comentario'];
				$parametrosLog['rce_diagnostico_fecha_eliminado']   = $rsHorarioServidor[0]['fecha'];
				$parametrosLog['rce_diagnostico_hora_eliminado']    = $rsHorarioServidor[0]['hora'];
				$parametrosLog['rce_diagnostico_usuario_eliminado'] = $datos['usuario'];
				$parametrosLog['cta_cte']                           = $rsRce_diagnostico[0]['cta_cte'];
				$parametrosLog['origen']                            = $rsRce_diagnostico[0]['origen'];
				
				$objDiagnosticos->InsertLog_diagnosticos_camas($objCon,$parametrosLog);

				$objDiagnosticos->DeletetRce_diagnosticoCompartido($objCon,$parametros);
				$objCon->commit();
				$response = array("status" => "success");
				echo json_encode($response);
			}catch (PDOException $e){
				$objCon->rollback();
				$response = array("status" => "error", "message" => $e->getMessage());
				echo json_encode($response);
			}
		break;
	case "actualizarDiagnosticoRCE":
			$objCon->db_connect();
			$parametros                     = $objUtil->getFormulario($_POST);
			// print('<pre>');  print_r($parametros);  print('</pre>');
			// break;
			$datos['usuario']           	= $_SESSION['MM_Username'.SessionName];
			try{
				$objCon->beginTransaction();
				$parametros['diagnistico_descripcion_text_comentario']   	= $parametros['frm_diagnostico_descrip'];
				$parametros['id_compartido'] 								= $parametros['rce_diagnostico_id'];
				$objDiagnosticos->UpdateRce_diagnosticoCompartido($objCon,$parametros);

				$objCon->commit();
				$response = array("status" => "success");
				echo json_encode($response);
			}catch (PDOException $e){
				$objCon->rollback();
				$response = array("status" => "error", "message" => $e->getMessage());
				echo json_encode($response);
			}
		break;
	case "guardarDiagnostico";
		$parametros                     = $objUtil->getFormulario($_POST);
		$datos['usuario']           	= $_SESSION['MM_Username'.SessionName];
		try{
			$objCon->beginTransaction();
			$rsHorarioServidor                           = $objUtil->getHorarioServidor($objCon);
			$parametros['rce_diagnostico_cie10']         = $parametros['hidden_frm_diagnostico'];
			$parametros['rce_diagnostico_cie10_descrip'] = $parametros['hidden_frm_diagnostico_descrip'];
			$parametros['rce_diagnostico_fecha']         = $rsHorarioServidor[0]['fecha'];
			$parametros['rce_diagnostico_hora']          = $rsHorarioServidor[0]['hora'];
			$parametros['rce_diagnostico_usuario']       = $datos['usuario'];
			// $parametros['rce_evolucion_id']              = $parametros['rce_evolucion_id'];
			$parametros['rce_id']                        = $parametros['rce_id'];
			// $rce_diagnostico->InsertRce_diagnostico($objCon,$parametros);
			$parametros['id_cie10']                      =  $parametros['hidden_frm_diagnostico'];
			$parametros['fecha']                         =  $rsHorarioServidor[0]['fecha'];
			$parametros['hora']                          =  $rsHorarioServidor[0]['hora'];
			$parametros['usuario']                       =  $datos['usuario'];
			$parametros['cta_cte']                       =  $parametros['cta_cte'];
			$parametros['diagnistico_descripcion_text']  =  $parametros['hidden_frm_diagnostico_descrip'];
			$parametros['origen']                        =  3;
			$objDiagnosticos->InsertRce_diagnosticoCompartido($objCon,$parametros);

			$objCon->commit();
			$response = array("status" => "success");
			echo json_encode($response);
		}catch (PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "busquedaSensitivaDiagnostico";
		try{
			$objCon->beginTransaction();	
			echo $objDiagnosticos -> sensitivaDiagnostico($objCon,$_POST['term']);
		}catch(PDOException $e){
            $e->getMessage();			
		}	
	break;

	case 'guardarAntecedentes':
		$datos['idTipoAntecedente'] 	= $_POST['idAntecedente'];
		$datos['paciente_id']        	= $_POST['paciente_id'];
		$datos['usuario']           	=  $_SESSION['MM_Username'.SessionName];
		$datos['frm_ctacte'] 			= $_POST['idctacte'];
		$datos['obsAntecedente'] 		= isset($_POST['obsAntecedente']) ? $_POST['obsAntecedente'] : '';
		$rsHorarioServidor 			 	= $objUtil->getHorarioServidor($objCon);
		switch($datos['idTipoAntecedente']){
			case '10':
				$datos['tipoAntecedente'] 		= 44;
				$datos['detalleAntecedente'] 	= $_POST['hidden_diagCie10'];
				$datos['frm_fecha_inicio'] 		= $objUtil->fechaInvertida($_POST['frm_fecha_inicio']);
				$datos['frm_fecha_termino'] 	= $objUtil->fechaInvertida($_POST['frm_fecha_termino']);
			break;
			case '11':
				$datos['tipoAntecedente'] 		= $_POST['tipoAntecedente'];
				$datos['detalleAntecedente'] 	= $_POST['detalleAntecedente'];
				$datos['detalle'] = isset($_POST['obsAntecedente']) ? $_POST['obsAntecedente'] : null;
				$datos['frm_fecha_inicio'] 		= $rsHorarioServidor[0]['fecha'];
				$datos['frm_fecha_termino'] 	= "0000-00-00";
			break;
			default:
				$datos['tipoAntecedente'] 		= $_POST['tipoAntecedente'];
				$datos['detalleAntecedente']	= isset($_POST['obsAntecedente']) ? $_POST['obsAntecedente'] : null;
				$datos['frm_fecha_inicio'] 		= $objUtil->fechaInvertida($_POST['frm_fecha_inicio']);
				$datos['frm_fecha_termino'] 	= $objUtil->fechaInvertida($_POST['frm_fecha_termino']);
			break;
		}
		try{
			$objCon->beginTransaction();
			$objRce->ingresarAntecedentes($objCon,$datos);
			$objCon->commit();
			$response = array("status" => "success");
			echo json_encode($response);
		}catch (PDOException $e){
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;

	
}
function ingresarSeguimientoPaciente($objCon, $objUtil, $objDau, $parametros) {
	$idDau 						= $objUtil->asignar($parametros["dau_id"]);
	$estadoSeguimientoPaciente 	= $objUtil->existe($parametros["frm_seguimientoPaciente"])
		? $objUtil->asignar($parametros["frm_seguimientoPaciente"])
		: "N";
	$usuarioIngresoSeguimiento = $_SESSION['MM_Username'.SessionName];

	$parametrosAEnviar = array(
		"idDau" => $idDau,
		"seguimientoPaciente" => $estadoSeguimientoPaciente,
		"usuarioIngresoSeguimiento" => $usuarioIngresoSeguimiento
	);

	$objDau->ingresarSeguimientoPaciente($objCon, $parametrosAEnviar);
}
function ingresarRegistroViolencia ( $objCon, $objUtil, $objRce, $parametros ) {

	$objRce->eliminarRegistroViolencia($objCon, $parametros['dau_id']);

	if ( $parametros['slc_existeViolencia'] == 'N' ) {

		return;

	}

	$parametrosAEnviar                              = array();

	$parametrosAEnviar['idDau']                     = $parametros['dau_id'];

	$parametrosAEnviar['idRCE']                     = $parametros['rce_id'];

	$parametrosAEnviar['idPaciente']                = $parametros['paciente_id'];

	$parametrosAEnviar['idTipoViolencia']           = $parametros['frm_tipoViolencia'];

	$parametrosAEnviar['idTipoAgresor']             = $parametros['frm_tipoAgresor'];

	$parametrosAEnviar['idTipoLesionVictima']       = $parametros['frm_tipoLesionVictima'];

	$parametrosAEnviar['idTipoSospechaPenetracion'] = $parametros['frm_tipoSospechaPenetracion'];

	$parametrosAEnviar['idTipoProfilaxis']          = $parametros['frm_profilaxis'];

	$parametrosAEnviar['victimaEmbarazada']         = $parametros['frm_victimaEmbarazada'];

	$parametrosAEnviar['peritoSexual']         	    = $parametros['frm_peritoSexual'];

	$parametrosAEnviar['usuarioRegistraViolencia']  = ( ! is_null($parametros['contingencia']) && ! empty($parametros['contingencia']) ) ? 'dauContingencia' : $_SESSION['MM_Username'.SessionName];

	$objRce->ingresarRegistroViolencia($objCon, $parametrosAEnviar);

}
function obtenerCodigosEspecialidad ( $parametros ) {

	$arregloCodigosEspecialidad = json_decode(stripslashes($parametros));

	$codigosEspecialidad = '';

	for ( $i = 0; $i < count($arregloCodigosEspecialidad); $i++ ) {

		if ( empty($codigosEspecialidad) || is_null($codigosEspecialidad) ) {

			$codigosEspecialidad  = $arregloCodigosEspecialidad[$i];

			continue;

		}

		$codigosEspecialidad  = $codigosEspecialidad.', '.$arregloCodigosEspecialidad[$i];

	}

	return $codigosEspecialidad;

}
function obtenerDescripcionEspecialidad ( $objCon, $parametros ) {

	require_once("../../../class/Agenda.class.php"); 		$objAgenda 	= new Agenda();

	$arregloCodigosEspecialidad = json_decode(stripslashes($parametros));

	$descripcionEspecialidad = '';

	for ( $i = 0; $i < count($arregloCodigosEspecialidad); $i++ ) {

		$resultado = $objAgenda->getDescripcionEspecialidad($objCon, $arregloCodigosEspecialidad[$i]);

		if ( empty($descripcionEspecialidad) || is_null($descripcionEspecialidad) ) {

			$descripcionEspecialidad  = $resultado[0]['ESPdescripcion'];

			continue;

		}

		$descripcionEspecialidad  = $descripcionEspecialidad.', '.$resultado[0]['ESPdescripcion'];

	}

	return $descripcionEspecialidad;

}
function guardarFormulario ( $objCon, $objUtil, $objFormulario, $parametros ) {

	$parametrosAEnviar                             = array();

	$parametrosAEnviar['form_int_idpaciente']      = $parametros['idPaciente'];

	$parametrosAEnviar['form_int_celular']         = $objUtil->asignar($parametros['frm_seguimientoCelular']);

	$parametrosAEnviar['form_int_telefono']        = $objUtil->asignar($parametros['frm_seguimientoTelefono']);

	$parametrosAEnviar['form_int_email']           = $objUtil->asignar($parametros['frm_seguimientoCorreo']);

	$parametrosAEnviar['form_int_observacion']     = $objUtil->asignar($parametros['frm_seguimientoObservaciones']);

	$parametrosAEnviar['form_int_direccion_pac']   = $objUtil->asignar($parametros['frm_seguimientoDireccion']);

	$parametrosAEnviar['form_int_cantpersonas']    = $objUtil->asignar($parametros['frm_seguimientoCantidadViven']);

	$parametrosAEnviar['form_int_motivosospecha']  = $objUtil->asignar($parametros['frm_seguimientoMotivoSospecha']);

	$parametrosAEnviar['form_int_iniciosintimas']  = ( is_null($parametros['frm_seguimientoInicioSintomas']) || empty($parametros['frm_seguimientoInicioSintomas']) ) ? '' : date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_seguimientoInicioSintomas'])));

	$parametrosAEnviar['form_int_estadoingreso']   = $objUtil->asignar($parametros['frm_seguimientoEstadoIngreso']);

	$parametrosAEnviar['form_int_ant_epi']         = $objUtil->asignar($parametros['frm_seguimientoAntecedentesEpidemiologicos']);

	$parametrosAEnviar['form_int_destino']         = $objUtil->asignar($parametros['frm_seguimientoDestino']);

	$parametrosAEnviar['form_int_dau']             = $objUtil->asignar($parametros['idDau']);

	$parametrosAEnviar['form_int_lugar_trabajo']   = $objUtil->asignar($parametros['frm_seguimientoLugarTrabajo']);

	$parametrosAEnviar['form_int_pais_residencia'] = $objUtil->asignar($parametros['frm_seguimientoPaisResidencia']);

	$parametrosAEnviar['form_int_nacionalidad']    = $objUtil->asignar($parametros['frm_seguimientoNacionalidad']);

	$parametrosAEnviar['seg_usuario'] 			   = $_SESSION['MM_Username'.SessionName];

	$parametrosAEnviar['tomada_por_muestra']       = $objUtil->asignar($parametros['frm_muestraTomadaPor']);

	$parametrosAEnviar['fecha_toma_muestra']       = date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_fechaMuestra'])));

	$parametrosAEnviar['covid_solicita_muestra']   = ( is_null($parametros['frm_examenCovid19']) || empty($parametros['frm_examenCovid19']) || $parametros['frm_examenCovid19'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['ifi_solicita_muestra']     = ( is_null($parametros['frm_examenCovid19IFI']) || empty($parametros['frm_examenCovid19IFI']) || $parametros['frm_examenCovid19IFI'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['usuario_ingreso_muestra']  = $_SESSION['MM_Username'.SessionName];

	$parametrosAEnviar['lugar_toma_muestra']       = $objUtil->asignar($parametros['frm_lugarTomaMuestra']);

	$parametrosAEnviar['Broncoalveolar']           = ( is_null($parametros['frm_muestraLavadoBroncoalveolar']) || empty($parametros['frm_muestraLavadoBroncoalveolar']) || $parametros['frm_muestraLavadoBroncoalveolar'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Esputo']                   = ( is_null($parametros['frm_muestraEsputo']) || empty($parametros['frm_muestraEsputo']) || $parametros['frm_muestraEsputo'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Aspirado_Traqueal']        = ( is_null($parametros['frm_muestraAspiradoTraqueal']) || empty($parametros['frm_muestraAspiradoTraqueal']) || $parametros['frm_muestraAspiradoTraqueal'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Aspirado_Nasofaringeo']    = ( is_null($parametros['frm_muestraAspiradoNasofaringeo']) || empty($parametros['frm_muestraAspiradoNasofaringeo']) || $parametros['frm_muestraAspiradoNasofaringeo'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['torulas_nasofaringeas']    = ( is_null($parametros['frm_muestraTorulasNasofaringeas']) || empty($parametros['frm_muestraTorulasNasofaringeas']) || $parametros['frm_muestraTorulasNasofaringeas'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['tejido_pulmonar']          = ( is_null($parametros['frm_muestraTejidoPulmonar']) || empty($parametros['frm_muestraTejidoPulmonar']) || $parametros['frm_muestraTejidoPulmonar'] == 'N' ) ? 'N' : 'S';

	$parametrosAEnviar['muestra_iniciosintomas']   = date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_seguimientoInicioSintomas'])));

	$parametrosAEnviar['muestra_lugar_trabajo']    = $objUtil->asignar($parametros['frm_seguimientoLugarTrabajo']);

	$parametrosAEnviar['muestra_cantpersonas']     = $objUtil->asignar($parametros['frm_seguimientoCantidadViven']);

	$parametrosAEnviar['muestra_destino']          = $objUtil->asignar($parametros['frm_seguimientoDestino']);

	$parametrosAEnviar['muestra_ant_epi']          = $objUtil->asignar($parametros['frm_seguimientoAntecedentesEpidemiologicos']);

	$parametrosAEnviar['muestra_estadoingreso']    = $objUtil->asignar($parametros['frm_seguimientoEstadoIngreso']);

	$parametrosAEnviar['muestra_embarazada']       = $objUtil->asignar($parametros['frm_seguimientoEmbarazada']);

	$objFormulario->guardarFormulario($objCon, $parametrosAEnviar);

	unset($parametrosAEnviar);

}

function guardarTomaMuestra ( $objCon, $objUtil, $objFormulario, $parametros ) {

	$parametrosAEnviar 							   = array();

	$parametrosAEnviar['form_id']              	   = $parametros['idFormulario'];

	$parametrosAEnviar['tomada_por_muestra']       = $objUtil->asignar($parametros['frm_muestraTomadaPor']);

	$parametrosAEnviar['fecha_toma_muestra']       = date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_fechaMuestra'])));

	$parametrosAEnviar['covid_solicita_muestra']   = ( is_null($parametros['frm_examenCovid19']) || empty($parametros['frm_examenCovid19']) || $parametros['frm_examenCovid19'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['ifi_solicita_muestra']     = ( is_null($parametros['frm_examenCovid19IFI']) || empty($parametros['frm_examenCovid19IFI']) || $parametros['frm_examenCovid19IFI'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['usuario_ingreso_muestra']  = $_SESSION['MM_Username'.SessionName];

	$parametrosAEnviar['lugar_toma_muestra']       = $objUtil->asignar($parametros['frm_lugarTomaMuestra']);

	$parametrosAEnviar['Broncoalveolar']           = ( is_null($parametros['frm_muestraLavadoBroncoalveolar']) || empty($parametros['frm_muestraLavadoBroncoalveolar']) || $parametros['frm_muestraLavadoBroncoalveolar'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Esputo']                   = ( is_null($parametros['frm_muestraEsputo']) || empty($parametros['frm_muestraEsputo']) || $parametros['frm_muestraEsputo'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Aspirado_Traqueal']        = ( is_null($parametros['frm_muestraAspiradoTraqueal']) || empty($parametros['frm_muestraAspiradoTraqueal']) || $parametros['frm_muestraAspiradoTraqueal'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['Aspirado_Nasofaringeo']    = ( is_null($parametros['frm_muestraAspiradoNasofaringeo']) || empty($parametros['frm_muestraAspiradoNasofaringeo']) || $parametros['frm_muestraAspiradoNasofaringeo'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['torulas_nasofaringeas']    = ( is_null($parametros['frm_muestraTorulasNasofaringeas']) || empty($parametros['frm_muestraTorulasNasofaringeas']) || $parametros['frm_muestraTorulasNasofaringeas'] == 'N' ) ? 'N': 'S';

	$parametrosAEnviar['tejido_pulmonar']          = ( is_null($parametros['frm_muestraTejidoPulmonar']) || empty($parametros['frm_muestraTejidoPulmonar']) || $parametros['frm_muestraTejidoPulmonar'] == 'N' ) ? 'N' : 'S';

	$parametrosAEnviar['muestra_iniciosintomas']   = date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_seguimientoInicioSintomas'])));

	$parametrosAEnviar['muestra_lugar_trabajo']    = $objUtil->asignar($parametros['frm_seguimientoLugarTrabajo']);

	$parametrosAEnviar['muestra_cantpersonas']     = $objUtil->asignar($parametros['frm_seguimientoCantidadViven']);

	$parametrosAEnviar['muestra_destino']          = $objUtil->asignar($parametros['frm_seguimientoDestino']);

	$parametrosAEnviar['muestra_ant_epi']          = $objUtil->asignar($parametros['frm_seguimientoAntecedentesEpidemiologicos']);

	$parametrosAEnviar['muestra_estadoingreso']    = $objUtil->asignar($parametros['frm_seguimientoEstadoIngreso']);

	$parametrosAEnviar['muestra_embarazada']       = $objUtil->asignar($parametros['frm_seguimientoEmbarazada']);

	$objFormulario->guardarTomaMuestra($objCon, $parametrosAEnviar);

	unset($parametrosAEnviar);

}

function insertarSeguimiento ( $objCon, $objUtil, $objFormulario, $parametros ) {

	$parametrosAEnviar 							    = array();

	$parametrosAEnviar['seg_usuario']    	 		= $parametros['seg_usuario'];

	$parametrosAEnviar['form_id']    				= $parametros['form_id'];

	$parametrosAEnviar['seg_motivo']  				= $parametros['seg_motivo'];

	$parametrosAEnviar['seg_observacion_general']   = $parametros['seg_observacion_general'];

	$objFormulario->insertarSeguimiento($objCon, $parametrosAEnviar);

	unset($parametrosAEnviar);

}

function actualizarFormulario ( $objCon, $objUtil, $objFormulario, $parametros ) {

	$parametrosAEnviar = array();

	$parametrosAEnviar['form_int_idpaciente']      = $parametros['idPaciente'];

	$parametrosAEnviar['form_int_celular']         = $objUtil->asignar($parametros['frm_seguimientoCelular']);

	$parametrosAEnviar['form_int_telefono']        = $objUtil->asignar($parametros['frm_seguimientoTelefono']);

	$parametrosAEnviar['form_int_email']           = $objUtil->asignar($parametros['frm_seguimientoCorreo']);

	$parametrosAEnviar['form_int_observacion']     = $objUtil->asignar($parametros['frm_seguimientoObservaciones']);

	$parametrosAEnviar['form_int_direccion_pac']   = $objUtil->asignar($parametros['frm_seguimientoDireccion']);

	$parametrosAEnviar['form_int_cantpersonas']    = $objUtil->asignar($parametros['frm_seguimientoCantidadViven']);

	$parametrosAEnviar['form_int_motivosospecha']  = $objUtil->asignar($parametros['frm_seguimientoMotivoSospecha']);

	$parametrosAEnviar['form_int_iniciosintimas']  = ( is_null($parametros['frm_seguimientoInicioSintomas']) || empty($parametros['frm_seguimientoInicioSintomas']) ) ? '' : date("Y-m-d", strtotime($objUtil->asignar($parametros['frm_seguimientoInicioSintomas'])));

	$parametrosAEnviar['form_int_estadoingreso']   = $objUtil->asignar($parametros['frm_seguimientoEstadoIngreso']);

	$parametrosAEnviar['form_int_ant_epi']         = $objUtil->asignar($parametros['frm_seguimientoAntecedentesEpidemiologicos']);

	$parametrosAEnviar['form_int_destino']         = $objUtil->asignar($parametros['frm_seguimientoDestino']);

	$parametrosAEnviar['form_int_dau']             = $objUtil->asignar($parametros['idDau']);

	$parametrosAEnviar['form_int_lugar_trabajo']   = $objUtil->asignar($parametros['frm_seguimientoLugarTrabajo']);

	$parametrosAEnviar['form_int_pais_residencia'] = $objUtil->asignar($parametros['frm_seguimientoPaisResidencia']);

	$parametrosAEnviar['form_int_nacionalidad']    = $objUtil->asignar($parametros['frm_seguimientoNacionalidad']);

	$parametrosAEnviar['form_int_estado']    	   = $objUtil->asignar($parametros['form_int_estado']);

	$parametrosAEnviar['form_int_cant_int']    	   = $objUtil->asignar($parametros['form_int_cant_int']);

	$objFormulario->actualizarFormulario($objCon, $parametrosAEnviar);

	unset($parametrosAEnviar);

}