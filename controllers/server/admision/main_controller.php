<?php
session_start();
error_reporting(0);
require_once('../../../class/Connection.class.php');   		    $objCon      		= new Connection;
require_once("../../../class/Util.class.php");       		    $objUtil     		= new Util;
require_once('../../../class/SqlDinamico.class.php');      		$objSqlDinamico     = new SqlDinamico;
require_once("../../../class/Admision.class.php");				$objAdmision        = new Admision;
require_once("../../../class/Paciente.class.php");				$objPaciente        = new Paciente;
require_once("../../../class/PacienteDau.class.php");				$objPacienteDau        = new PacienteDau;
require_once("../../../class/Dau.class.php");					$objDau        		= new Dau;
require_once("../../../class/Movimiento.class.php");			$objMovimiento      = new Movimiento;
require_once("../../../class/CMBD.class.php");       			$objCMBD     		= new CMBD;
switch ( $_POST['accion'] ) {
	case "eliminarPDF":
		$parametros = $objUtil->getFormulario($_POST);
		echo $parametros['nombreArchivo'];
		unlink($_SERVER['DOCUMENT_ROOT'].$parametros['nombreArchivo']);
	break;
	case "actualizarPacienteAdmision":
		$objCon->db_connect();
		$parametros                                       = $objUtil->getFormulario($_POST);
		$parametros['dau_id']                      		  = $parametros['id'];
		$parametros['dau_paciente_domicilio_tipo'] 		  = $parametros["frm_tipoDomicilio"];
		$parametros['dau_paciente_prevision']      		  = $parametros["frm_previson"];
		$parametros['dau_paciente_forma_pago']     		  = $parametros["frm_formaPago"];
		$parametros['dau_atencion']                		  = $parametros["frm_atencion"];
		$parametros['dau_motivo_consulta']         		  = $parametros["frm_motivoConsulta"];
		if($parametros['frm_motivo']){
			$parametros['dau_motivo_descripcion']  		  = $parametros['frm_motivo'];
		}
		$parametros['dau_forma_llegada']                  = $parametros["frm_formallegada"];
		$parametros['dau_mordedura']                      = $parametros["frm_mordedura"];
		$parametros['dau_intoxicacion']                   = $parametros["frm_intoxicacion"];
		$parametros['dau_quemadura']                      = $parametros["frm_quemadura"];
		$parametros['dau_imputado']                       = $parametros["frm_imputado"];
		$parametros['dau_reanimacion']			          = $parametros["frm_reanimacion"];
		$parametros['dau_conscripto']			          = $parametros["frm_conscripto"];
		$parametros['dau_tipo_accidente']                 = $parametros['frm_tipoAccidente'];
		$parametros['dau_accidente_escolar_institucion']  = $parametros['frm_institucion'];
		$parametros['dau_accidente_escolar_numero']       = $parametros['frm_numero'];
		$parametros['dau_accidente_escolar_nombre']       = $parametros['frm_nombre2'];
		$parametros['dau_accidente_trabajo_mutualidad']   = $parametros['frm_mutualidad'];
		$parametros['dau_accidente_transito_tipo']        = $parametros['frm_transitoTipo'];
		$parametros['dau_accidente_hogar_lugar']          = $parametros['frm_hogar'];
		$parametros['dau_accidente_otro_lugar']           = $parametros['frm_lugarPublico'];
		$parametros['dau_agresion_vif']                   = $parametros['frm_vif'];
		$parametros['dau_tipo_mordedura']                 = $parametros["frm_tipo_mordedura"];
		$parametros['dau_manifestaciones']      		 = ( is_null($parametros['frm_manifestaciones']) || empty($parametros['frm_manifestaciones']) ) ? 'N' : $parametros['frm_manifestaciones'];
		$parametros['dau_constatacion_lesiones']   		 = ( is_null($parametros['frm_constatacionLesiones']) || empty($parametros['frm_constatacionLesiones']) ) ? 'N' : $parametros['frm_constatacionLesiones'];
		$parametros['sintomasRespiratorios'] 			 = "";
		$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_dolorGarganta']) || empty($parametros['frm_dolorGarganta']) ) ? 'N' : 'S';
		$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_tos']) || empty($parametros['frm_tos']) ) ? 'N' : 'S';
		$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_dificultadRespiratoria']) || empty($parametros['frm_dificultadRespiratoria']) ) ? 'N' : 'S';
		try{
			$objCon->beginTransaction();
			$respuesta                			= $objAdmision->actualizarAdmision($objCon, $parametros);
			$respuesta2               			= $objAdmision->actualizarFechas($objCon, $parametros);
			$parametros['dau_mov_descripcion'] 	= "admision actulizacion dau";
			$parametros['dau_mov_tipo'] 		= "ada";
			$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			ingresarPacienteDerivado($objCon, $objUtil, $objAdmision, $parametros);
			$response = array("status" => "success","id" => $parametros['dau_id']);
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "cargarCiudades":
		$objCon->db_connect();
		$parametrosSelect['REG_Id'] = "REG_Id = '".$_POST["regId"]."'";
		$response                     = $objSqlDinamico->generarSelect($objCon,'paciente.ciudad' , $parametrosSelect, $order);
		echo json_encode($response);
	break;
		case "cargarComunas":
		$objCon->db_connect();
		$parametrosSelect['CIU_Id'] = "CIU_Id = '".$_POST["ciuId"]."'";
		$response                     = $objSqlDinamico->generarSelect($objCon,'paciente.comuna' , $parametrosSelect, $order);
		echo json_encode($response);
	break;
	case "cargarParametros":
		$objCon->db_connect();
		$parametrosSelect['mot_id'] 	= "mot_id = '".$_POST["codigoAccidente"]."'";
		$response                     	= $objSqlDinamico->generarSelect($objCon,'dau.sub_motivo_consulta' , $parametrosSelect, $order);
		echo json_encode($response);
	break;
	case "cargarParametros2":
		$objCon->db_connect();
		if ( $_POST["codigoTipoAccidente"] == 3 ) {
			echo $objAdmision->cargarParametrosAtropellado($objCon);
		} else {
			echo $objAdmision->cargarParametrosInstitucion($objCon, $_POST["codigoTipoAccidente"]);
		}
	break;
	case "verificaAdmision":
		$objCon->db_connect();
		$parametros                     = $objUtil->getFormulario($_POST);
		$parametros['id_paciente']      = $_POST["idPaciente"];
		$datos            				= $objAdmision->listarDatosBuscador_verificar($objCon, $parametros);
		switch ( $datos[0]['est_id'] ) {
			case '1': // ADMISIONADO
				echo count($datos);
			break;
			case '2': // CATEGORIZADO
				echo count($datos);
			break;
			case '8': // EN CAMA
				echo count($datos);
			break;
			case '3': // INICIO DE ATENCIÓN
				echo count($datos);
			break;
			case '4': // CON INDICACIÓN DE EGRESO (ALTA URGENCIA)
				echo count($datos);
			break;
			default:
				echo json_encode($datos);
			break;
		}
	break;
	case "buscarPaciente":
		$objCon->db_connect();
		$parametrosSelect['id'] 	= "id = '".$_POST["idPacienteDau"]."'";
		$response                     	= $objSqlDinamico->generarSelect($objCon,'paciente.paciente' , $parametrosSelect, $order);
		$response[0]['fechanacimiento'] = $objUtil->fechaInvertida($response[0]['fechanac']);
		$response[0]['calcularEdad'] = $objUtil->edad_paciente2($response[0]['fechanac']);
		if($response[0]['extranjero'] == 'S' ){
			if($response[0]['id_doc_extranjero'] == '1'){
				$response[0]['tipoDocumentoLabel'] = 'DNI';
			}else if($response[0]['id_doc_extranjero'] == '2'){
				$response[0]['tipoDocumentoLabel'] = 'PASAPORTE';
			}else if($response[0]['id_doc_extranjero'] == '3'){
				$response[0]['tipoDocumentoLabel'] = 'OTROS';
			}else {
				$response[0]['tipoDocumentoLabel'] = 'N/A';
			}
			$response[0]['run'] = $response[0]['rut_extranjero'];
		}else{
			$response[0]['id_doc_extranjero'] = 0;
			$response[0]['run'] = $objUtil->rutDigito($response[0]['rut']);
			$response[0]['run'] = $objUtil->rut($response[0]['run']);

		}
		if($response[0]['prais'] == null ){ $response[0]['prais'] = 0; }
		echo json_encode($response);
	break;
	case "grabarPacienteAdmision":
		$objCon->db_connect();
		// require_once("../../../class/Admision.class.php");		   						$objAdmision 		= new Admision;
		// require_once("../../../class/Movimientos.class.php");		   					$objMovimiento      = new Movimientos;
		// require_once("../../../class/Conectar.inc");		       						$objConectar        = new Conectar; 		$link = $objConectar->db_connect();
		// require_once("../../../../RecNet_2.0/clases/CtaCte.inc"); 	 					$objCtaCte          = new CtaCte;
		// require_once("../../../../indice_paciente_2017/class/Paciente.class.php"); 		$objPac           	= new Paciente;
		// require_once("../../../../indice_paciente_2017/class/Log_Paciente.class.php");  $objLog 			= new Log_Paciente;
		// require_once("../../../class/Dau.class.php");		   							$objDau 			= new Dau;

		$parametros                     = $objUtil->getFormulario($_POST);
		if($parametros["frm_transexual"] == 1 && $parametros["frm_nombre_legal"] == 1){
			//echo "no se requiere nombre social";
			$parametros["frm_nombreSocial"] = $parametros["frm_nombres_dau"];
		}

		$responsePacTrans = $objPacienteDau->getInfoPacienteTrans($objCon, $_POST["idPacienteDau"]);
		if($parametros["frm_transexual"] == 1){
			$parametros["frm_transexual"] = "S";
		}else{
			$parametros["frm_transexual"] = "N";
			$parametros["frm_transexual"] = "";
			$parametros["frm_identidadGenero"] = "";
			$parametros["frm_nombreSocial"] = "";
		}

		if($parametros["frm_nombre_legal"] == 1){
			$parametros["frm_nombre_legal"] = "S";
		}else{
			$parametros["frm_nombre_legal"] = "N";

		}

		if($responsePacTrans[0]["transexual"] != $parametros["frm_transexual"] || $responsePacTrans[0]["identidad_genero"] != $parametros["frm_identidadGenero"] || $responsePacTrans[0]["nombre_legal"] != $parametros["frm_nombre_legal"] || $responsePacTrans[0]["nombreSocial"] != $parametros["frm_nombreSocial"]){
			$parametros["paciente_idAux"] = $_POST["idPacienteDau"];
			$parametros["SistemaAux"] = "dauRCE";
			$parametros["accionAux"] = "Editar";
			$parametros["usuario_id"] = $_SESSION['MM_Username'.SessionName];

			$objPacienteDau->logInsertPacienteTrans($objCon, $parametros);

			$objPacienteDau->actualizarPacienteTrans($objCon, $parametros);

		}
		$fechaNac                                  		 = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['frm_Naciemito'])));
		$edad                                      		 = $objUtil->edadActual($fechaNac);
		$parametros["est_id"]                      		 = 1;
		$parametros["id_paciente"]                 		 = $_POST["idPacienteDau"];
		$parametros['dau_admision_usuario']		   		 = $_SESSION['MM_Username'.SessionName];
		$parametros['dau_paciente_domicilio']      		 = $parametros["frm_nombreCalle"].' #'.$parametros["frm_numeroDireccion"].' '.$parametros["frm_direccion"];
		$parametros['dau_paciente_domicilio_tipo'] 		 = $parametros["frm_tipoDomicilio"];
		$parametros['dau_paciente_edad']           		 = $edad;
		$parametros['dau_paciente_prevision']      		 = $parametros["frm_prevision"];
		$parametros['dau_paciente_forma_pago']     		 = $parametros["frm_formaPago"];
		$parametros['dau_motivo_consulta']         		 = $parametros["frm_motivoConsulta"];
		$parametros['dau_atencion']                		 = $parametros["frm_atencion_admision"];
		$parametros['dau_forma_llegada']           		 = $parametros["frm_formallegada"];
		$parametros['dau_mordedura']               		 = $parametros["frm_mordedura"];
		$parametros['dau_intoxicacion']            		 = $parametros["frm_intoxicacion"];
		$parametros['dau_quemadura']               		 = $parametros["frm_quemadura"];
		$parametros['dau_tipo_mordedura']          		 = $parametros["frm_tipo_mordedura"];
		$parametros['idctacte']                	   		 = 0;
		$parametros['dau_imputado']                		 = $parametros["frm_imputado"];
		$parametros['dau_reanimacion']			   		 = $parametros["frm_reanimacion"];
		$parametros['dau_conscripto']			   		 = $parametros["frm_conscripto"];
		if ( $parametros['frm_motivoText'] ) {
			$parametros['dau_motivo_descripcion']   	 = $parametros['frm_motivoText'];
		}
		if ( $parametros['frm_motivoText2'] ) {
			$parametros['dau_motivo_descripcion']   	 = $parametros['frm_motivoText2'];
		}
		if ( $parametros['frm_motivoAgresion'] ) {
			$parametros['dau_motivo_descripcion']  		 = $parametros['frm_motivoAgresion'];
		}
		if ( $parametros['frm_motivoLesiones'] ) {
			$parametros['dau_motivo_descripcion']  		 = $parametros['frm_motivoLesiones'];
		}
		if ( $parametros['frm_motivoAlcoholemia'] ) {
			$parametros['dau_motivo_descripcion']  		 = $parametros['frm_motivoAlcoholemia'];
		}
		$parametros['dau_tipo_accidente']                = $parametros['frm_tipoAccidente'];
		$parametros['dau_accidente_escolar_institucion'] = $parametros['frm_institucion'];
		$parametros['dau_accidente_escolar_numero']      = $parametros['frm_numero'];
		$parametros['dau_accidente_escolar_nombre']      = $parametros['frm_nombre2'];
		$parametros['dau_accidente_trabajo_mutualidad']  = $parametros['frm_mutualidad'];
		$parametros['dau_accidente_transito_tipo']       = $parametros['frm_transitoTipo'];
		$parametros['dau_accidente_hogar_lugar']         = $parametros['frm_hogar'];
		$parametros['dau_accidente_otro_lugar']          = $parametros['frm_lugarPublico'];
		$parametros['dau_agresion_vif']                  = $parametros['frm_vif'];
		$parametros['dau_manifestaciones']      		 = ( is_null($parametros['frm_manifestaciones']) || empty($parametros['frm_manifestaciones']) ) ? 'N' : $parametros['frm_manifestaciones'];
		$parametros['dau_constatacion_lesiones']   		 = ( is_null($parametros['frm_constatacionLesiones']) || empty($parametros['frm_constatacionLesiones']) ) ? 'N' : $parametros['frm_constatacionLesiones'];
		if ( $parametros['frm_fechaAdmision'] ) {
			$parametros['frm_fechaAdmision'] 			 = date('Y-m-d H:i:s', strtotime($parametros['frm_fechaAdmision']));
			$parametros['dau_tipo_admision'] 			 = 'M';
		}
		$parametros['frm_nombres'] 						 = $parametros['frm_nombres_dau'];
		$parametros['frm_AP'] 							 = $parametros['frm_AP_dau'];
		$parametros['frm_AM'] 							 = $parametros['frm_AM_dau'];
		$parametros["dau_viaje_epidemiologico"] 		 = $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
		$parametros["dau_pais_epidemiologia"] 			 = ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
		$parametros["dau_observacion_epidemiologica"] 	 = ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;
		try {
			$objCon->beginTransaction();
			$subparametros                           = array();
			$subparametros['frm_id_paciente']        = $_POST["idPacienteDau"];
			$objCon->setDB("paciente");
			if ( $parametros['pacienteFallDau'] == "S" ) {
				$response = array("status" => "fallecido");
			} else {
				$datos 		= $objPaciente->listarPaciente($objCon, $subparametros);
				$nrFichaPac = $datos[0]['nroficha'];
				if ( $parametros['frm_sexo'] == null || $parametros['frm_sexo'] == '' ) {
					$parametros['frm_sexo'] = "D";
				}
				if ( $parametros['frm_etnia'] == null || $parametros['frm_etnia'] == '' ) {
					$parametros['frm_etnia'] = "14";
				}
				if ( $parametros['frm_prais'] == null || $parametros['frm_prais'] == '' ) {
					$parametros['frm_prais'] = "0";
				}
				if ( $parametros['frm_Naciemito'] ) {
					$parametros['frm_Naciemito'] = date("Y-m-d", strtotime(str_replace("/", "-", $parametros['frm_Naciemito'])));
				}
				if ( $parametros['frm_centroAtencion'] == null || $parametros['frm_centroAtencion'] == '' ) {
					$parametros['frm_centroAtencion'] = "18";
				}
				if ( $parametros['frm_Nacionalidad'] == null || $parametros['frm_Nacionalidad'] == '' ) {
					$parametros['frm_Nacionalidad'] = "NOINF";
				}
				if ( $parametros['frm_pais_nacimiento'] == null || $parametros['frm_pais_nacimiento'] == '' ) {
					$parametros['frm_pais_nacimiento'] = "NOINF";
				}
				if ( $parametros['frm_region'] == null || $parametros['frm_region'] == '' ) {
					$parametros['frm_region'] = "99";
				}
				if ( $parametros['frm_ciudad'] == null || $parametros['frm_ciudad'] == '' ) {
					$parametros['frm_ciudad'] = "999";
				}
				if ( $parametros['frm_comuna'] == null || $parametros['frm_comuna'] == '' ) {
					$parametros['frm_comuna'] = 349;
				}
				if ( $parametros['frm_sectorDomicilio'] == null || $parametros['frm_sectorDomicilio'] == '' ) {
					$parametros['frm_sectorDomicilio'] = "4";
				}
				if ( $parametros['frm_condicionIngreso'] == null || $parametros['frm_condicionIngreso'] == '' ) {
					$parametros['dau_cierre_condicion_ingreso_id'] = "5";
				} else {
					$parametros['dau_cierre_condicion_ingreso_id'] = $parametros['frm_condicionIngreso'];
				}
				$parametros['sintomasRespiratorios'] = "";
				$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_dolorGarganta']) || empty($parametros['frm_dolorGarganta']) ) ? 'N' : 'S';
				$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_tos']) || empty($parametros['frm_tos']) ) ? 'N' : 'S';
				$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_dificultadRespiratoria']) || empty($parametros['frm_dificultadRespiratoria']) ) ? 'N' : 'S';
				$arrayEdit = array(
									"nombre" => $parametros['frm_nombres'],
									$parametros['frm_AP'],
									$parametros['frm_AM'],
									$parametros['frm_direccion'],
									$parametros['frm_centroAtencion'],
									$parametros['frm_Naciemito'],
									$parametros['frm_sexo'],
									$parametros['frm_correo'],
									$parametros['frm_telefonoCelular'],
									$parametros['frm_telefonoCelular2'],
									$parametros['frm_telefonoCelular3'],
									$parametros['frm_etnia'],
									$parametros['frm_Nacionalidad'],
									$parametros['frm_prevision'],
									$parametros['frm_formaPago'],
									$parametros['frm_afrodescendiente'],
									$parametros['frm_pais_nacimiento'],
									$parametros['frm_region'],
									$parametros['frm_ciudad'],
									$parametros['frm_comuna'],
									$parametros['frm_prais'],
									$parametros['frm_nombreCalle'],
									$parametros['frm_numeroDireccion'],
									$parametros['frm_sectorDomicilio'],
									$parametros['frm_tipoDomicilio'],
									$parametros['frm_correo']
								);
				$arrayBase = array(
									"nombre" => $datos[0]['nombres'],
									$datos[0]['apellidopat'],
									$datos[0]['apellidomat'],
									$datos[0]['restodedireccion'],
									$datos[0]['centroatencionprimaria'],
									$datos[0]['fechanac'],
									$datos[0]['sexo'],
									$datos[0]['email'],
									$datos[0]['fono1'],									
									$datos[0]['fono2'],
									$datos[0]['fono3'],
									$datos[0]['etnia'],
									$datos[0]['nacionalidad'],
									$datos[0]['prevision'],
									$datos[0]['conveniopago'],
									$datos[0]['PACafro'],
									$datos[0]['paisNacimiento'],
									$datos[0]['region'],
									$datos[0]['ciudad'],
									$datos[0]['idcomuna'],
									$datos[0]['prais'],
									$datos[0]['calle'],
									$datos[0]['numero'],
									$datos[0]['sector_domicilio'],
									$datos[0]['conruralidad'],
									$datos[0]['email']
								);
				$resultado = array_diff_assoc($arrayEdit, $arrayBase);
				if ( count($resultado) ) {
					$parametros2['reg_usuario_insercion']    = $_SESSION['MM_Username'.SessionName];
					$parametros2['frm_tipoLog']              = "S";
					$parametros2['frm_AP']                   = $datos[0]['apellidopat'];
					$parametros2['frm_AM']                   = $datos[0]['apellidomat'];
					$parametros2['frm_nombres']              = $datos[0]['nombres'];
					$parametros2['frm_rut_pac']              = $datos[0]['rut'];
					$parametros2['frm_id_paciente']          = $datos[0]['id'];
					$parametros2['frm_prevision']            = $datos[0]['prevision'];
					$parametros2['frm_convenio']             = $datos[0]['conveniopago'];
					$parametros2['frm_nroFicha']             = $datos[0]['nroficha'];
					$parametros2['frm_Naciemito']            = $datos[0]['fechanac'];
					$parametros2['fono1']         			 = $datos[0]['fono1'];
					$parametros2['fono2']  					 = $datos[0]['fono2'];
					$parametros2['fono3']     				 = $datos[0]['fono3'];
					$parametros2['frm_sexo']                 = $datos[0]["sexo"];
					$parametros2['frm_id_trakcare']          = $datos[0]["id_trakcare"];
					$parametros2['frm_sistemas']             = "DAU";
					$parametros2['frm_pais_nacimiento']      = $datos[0]["paisNacimiento"];
					$parametros2['frm_region']     			 = $datos[0]["region"];
					$parametros2['frm_ciudad']     			 = $datos[0]["ciudad"];
					$parametros2['frm_comuna']     			 = $datos[0]["idcomuna"];
					$parametros2['frm_prais']     			 = $datos[0]["prais"];
					$parametros2['frm_nombreCalle']     	 = $datos[0]["calle"];
					$parametros2['frm_numeroDireccion']      = $datos[0]["numero"];
					$parametros2['frm_direccion']            = $datos[0]["restodedireccion"];
					$parametros2['frm_sectorDomicilio']      = $datos[0]["sector_domicilio"];
					$parametros2['frm_tipoDomicilio']        = $datos[0]["conruralidad"];
					$parametros2['frm_etnia']      		     = $datos[0]["etnia"];
					$parametros2['frm_correo']    		     = $datos[0]["email"];

					// $respuesta2                              = $objLog->registrar_log($objCon, $parametros2);
					$parametros['reg_usuario_insercion'] 	 = $_SESSION['MM_Username'.SessionName];
					$parametros['frm_tipoLog']           	 = "U";
					$parametros['frm_rut_pac']               = $parametros['numeroDocumento'];
					$parametros['frm_id_paciente']           = $datos[0]['id'];
					$parametros['frm_nroFicha']			     = $datos[0]['nroficha'];
					$parametros['fono1']      				 = $parametros["frm_telefonoCelular"];
					$parametros['fono2']   					 = $parametros['frm_telefonoCelular2'];
					$parametros['fono3']   					 = $parametros['frm_telefonoCelular3'];
					$parametros['frm_id_trakcare']           = $datos[0]["id_trakcare"];
					$parametros['frm_convenio']              = $parametros['frm_formaPago'];
					$parametros['frm_sistemas']              = "DAU";
					$parametros['frm_afrodescendiente']      = $parametros['frm_afrodescendiente'];
					$parametros['frm_pais_nacimiento']       = $parametros['frm_pais_nacimiento'];
					$parametros['frm_region']      			 = $parametros['frm_region'];
					$parametros['frm_ciudad']      			 = $parametros['frm_ciudad'];
					$parametros['frm_comuna']      			 = $parametros['frm_comuna'];
					$parametros['frm_prais']     			 = $parametros['frm_prais'] ;
					$parametros['frm_nombreCalle']     	 	 = $parametros['frm_nombreCalle'];
					$parametros['frm_numeroDireccion']       = $parametros['frm_numeroDireccion'];
					$parametros['frm_direccion']             = $parametros['frm_direccion'] ;
					$parametros['frm_sectorDomicilio']       = $parametros['frm_sectorDomicilio'];
					$parametros['frm_tipoDomicilio']         = $parametros['frm_tipoDomicilio'] ;
					$parametros['frm_etnia']        		 = $parametros['frm_etnia'] ;
					$parametros['frm_correo']        		 = $parametros['frm_correo'] ;
					$parametros['frm_religion'] 			 = $parametros['frm_religion'];
					// $respuesta3                          	 = $objLog->registrar_log($objCon, $parametros);
					$parametros['frm_nombres']               = strtoupper($parametros['frm_nombres']);
					$parametros['frm_AP']                    = strtoupper($parametros['frm_AP']);
					$parametros['frm_AM']                    = strtoupper($parametros['frm_AM']);
					$parametros['frm_nroDocumento']          = $parametros['documento'];
					$parametros['dv']                        = $objUtil->generaDigito($parametros['numeroDocumento']);
					$objPaciente->actualizarPacienteDAU($objCon, $parametros);

				}
				if ( $parametros['id_doc_documentoDau'] == 0 ) {
					$parametros['frm_rut'] = $parametros['numeroDocumento'];
				} else {
					if ( $parametros['id_doc_documentoDau'] == 1 || $parametros['id_doc_documentoDau'] == 2 || $parametros['id_doc_documentoDau'] == 3 ) {
						$parametros['frm_rut'] = $parametros['numeroDocumento'];
					}
				}
				$respuesta                			= $objAdmision->agregarAdmision($objCon, $parametros);
				$parametros['dau_id']     			= $respuesta;
				if ( $parametros['slc_derivado'] == 'S' ) {
					$parametrosAEnviar                              = array();
					$parametrosAEnviar['idDau']                     = $parametros['dau_id'];
					$parametrosAEnviar['idEstablecimientoRedSalud'] = $parametros['frm_establecimientosRedSalud'];
					$parametrosAEnviar['nombreOtroEstablecimiento'] = $parametros['frm_nombreOtrosEstablecimientos'];
					$parametrosAEnviar['usuarioInserta'] 			= $_SESSION['MM_Username'.SessionName];
					$objAdmision->ingresarPacienteDerivado($objCon, $parametrosAEnviar);
				}
				$datos                    			= $objAdmision->listarDatosDau($objCon, $parametros);
				$respuesta2               			= $objAdmision->nuevaCtaCte($objCon,$datos[0]["id_paciente"],$_POST['numeroDocumento'],$datos[0]["dau_admision_fecha"],10322,$datos[0]["dau_paciente_prevision"],$datos[0]["dau_id"],1,$parametros['dau_paciente_forma_pago']);
				$parametros['ctaCte']     			= $respuesta2;
				$respuesta3               			= $objAdmision->ActualizarCtaCorriente($objCon, $parametros);
				$parametros['dau_mov_descripcion'] 	= 'admision dau';
				$parametros['dau_mov_tipo'] 		= 'adm';
				$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$parametros3 = array();
				$parametros3['det_pre_cta_cte']      = $parametros['ctaCte'];
				$parametros3['det_pre_usuario']      = $parametros['dau_mov_usuario'];
				$parametros3['det_pre_rut_paciente'] = $datos[0]["id_paciente"];
				$parametros3['det_pre_cantidad']     = '1';
				$parametros3['det_pre_cod_sscc']     = '10322';
				$parametros3['det_pre_valor_unit']   = '0';
				$parametros3['det_pre_codigo'] = '0101103';
				// print('<pre>'); print_r($parametros3); print('</pre>');
				$objAdmision->addPrestacionAdmisionPaciente($objCon, $parametros3);
				if ($parametros['frm_prevision'] < 4) {
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
					$parametros4['matrizRutPaciente'] 				= $parametros['numeroDocumento'];
					$parametros4['matrizNombrePacie'] 				= $parametros['frm_nombres']." ".$parametros['frm_AP']." ".$parametros['frm_AM'];
					$parametros4['matrizFichaPacie'] 				= $nrFichaPac;
					$parametros4['matrizSexoPacie'] 				= $parametros['frm_sexo'];
					$parametros4['matrizFNacPacie'] 				= $parametros['frm_Naciemito'];
					$parametros4['matrizPreviCod'] 					= $parametros['frm_prevision'];
					$parametros4['matrizConvenio'] 					= $parametros['frm_formaPago'];
					$parametros4['matrizCodServicio'] 				= 10322;
					$parametros4['matrizNomServicio'] 				= 'urgencia';
					$parametros4['matrizFRegPrestacion'] 			= date('Y-m-d', strtotime($parametros['frm_fechaAdmision']));
					$parametros4['matrizFDigitacion'] 				= '';
					$parametros4['matrizOrigenAtPrestacion'] 		= 'Urgencia';
					$parametros4['matrizTablaOrigen'] 				= 'dau';
					$parametros4['matrizCantidadComprometida'] 		= 0;
					$parametros4['matrizEdadPaciente'] 				= $objUtil->edadActual(date('Y-m-d', strtotime($parametros['frm_fechaAdmision'])));
					$parametros4['matrizPreviNombre'] 				= $resp_np[0]['prevision'];
					$parametros4['matrizConvenioNombre'] 			= $resp_ni[0]['instNombre'];
					$parametros4['matrizNombrePrograma'] 			= '';
					$parametros4['matrizNombreCompromiso'] 			= '';
					$parametros4['matrizCodCompromiso'] 			= 0;
					$parametros4['matrizCodSistema'] 				= $parametros['dau_id'];
					$parametros4['matrizTipoSistema'] 				= 'rau';
					$parametros4['matrizUsuario'] 					= $parametros['dau_mov_usuario'];
					$parametros4['dau_tipo_admision'] 				= $parametros['dau_tipo_admision'];
					$objDau->registrarPacAdmisionadoMatrizEstadistica($objCon, $parametros4);

				}
				$objMovimiento->guardarMovimiento($objCon, $parametros);
				$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['dau_viaje_epidemiologico'])) ? " - ".$parametros['dau_viaje_epidemiologico'] : NULL;
				$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['dau_pais_epidemiologia'])) ? " - ".$parametros['dau_pais_epidemiologia'] : NULL;
				$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['frm_observacionEpidemiologica'])) ? " - ".$parametros['frm_observacionEpidemiologica'] : NULL;
				$objMovimiento->guardarMovimiento($objCon, $parametros);

				if (
					$objUtil->existe($parametros["pacienteNN"])
					&& $parametros["pacienteNN"] === "S"
				) {
					$dauPacienteNN = array(
						"idDau" => $parametros['dau_id'],
						"ctaCte" => $parametros['ctaCte'],
						"idPacienteNN" => $_POST["idPacienteDau"],
						"usuarioCreacion" => $_SESSION['MM_Username'.SessionName],
						"nombrePacienteNN" => $parametros["frm_nombres"]." ".$parametros["dau_id"]." ".$parametros["ctaCte"]
					);
					$objPacienteDau->ingresarDauPacienteNN($objCon, $dauPacienteNN);
					$objPacienteDau->actualizarNombrePacienteNN($objCon, $dauPacienteNN);

				}

				//CMBD ADMISIÓN 1 ADIMISION 2 CATEGORIZACIÓN , 3 INICIO ATENCION 4 INDICACION EGRESO 5 APLICA 6 CERRAR DAU (EGRERSO - ANULADO)
				$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 1);
				$response  = array("status" => "success","ultimoID" => $parametros['dau_id'], "ultimaCtaCte" => $parametros['ctaCte']);
			}
			$objCon->commit();
			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;


}

	// case "cargarParametros_tipo_choque":

	// 	require_once("../../../class/Admision.class.php");		$objAdmision     = new Admision;

	// 	$objCon->db_connect();

	// 	echo $objAdmision->cargarTipoChoque($objCon, $_POST["codigoTipoChoque"]);

	// break;



	// case "cargarParametros2":

	// 	require_once("../../../class/Admision.class.php");		$objAdmision     = new Admision;

	// 	$objCon->db_connect();

	// 	if ( $_POST["codigoTipoAccidente"] == 3 ) {

	// 		echo $objAdmision->cargarParametrosAtropellado($objCon);

	// 	} else {

	// 		echo $objAdmision->cargarParametrosInstitucion($objCon, $_POST["codigoTipoAccidente"]);

	// 	}

	// break;



	// case "grabarPacienteAdmision":

	// 	$objCon->db_connect();

	// 	require_once("../../../class/Admision.class.php");		   						$objAdmision 		= new Admision;
	// 	require_once("../../../class/Movimientos.class.php");		   					$objMovimiento      = new Movimientos;
	// 	require_once("../../../class/Conectar.inc");		       						$objConectar        = new Conectar; 		$link = $objConectar->db_connect();
	// 	require_once("../../../../RecNet_2.0/clases/CtaCte.inc"); 	 					$objCtaCte          = new CtaCte;
	// 	require_once("../../../../indice_paciente_2017/class/Paciente.class.php"); 		$objPac           	= new Paciente;
	// 	require_once("../../../../indice_paciente_2017/class/Log_Paciente.class.php");  $objLog 			= new Log_Paciente;
	// 	require_once("../../../class/Dau.class.php");		   							$objDau 			= new Dau;

	// 	$parametros                                = $objUtil->getFormulario($_POST);

	// 	$fechaNac                                  = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['frm_Naciemito'])));
	// 	$edad                                      = $objUtil->edadActual($fechaNac);
	// 	$parametros["est_id"]                      = 1;
	// 	$parametros["id_paciente"]                 = $_POST["idPacienteDau"];
	// 	$parametros['dau_admision_usuario']		   = $_SESSION['MM_Username'.SessionName];
	// 	$parametros['dau_paciente_domicilio']      = $parametros["frm_nombreCalle"].' #'.$parametros["frm_numeroDireccion"].' '.$parametros["frm_direccion"];
	// 	$parametros['dau_paciente_domicilio_tipo'] = $parametros["frm_tipoDomicilio"];
	// 	$parametros['dau_paciente_edad']           = $edad;
	// 	$parametros['dau_paciente_prevision']      = $parametros["frm_prevision"];
	// 	$parametros['dau_paciente_forma_pago']     = $parametros["frm_formaPago"];
	// 	$parametros['dau_motivo_consulta']         = $parametros["frm_motivoConsulta"];
	// 	$parametros['dau_atencion']                = $parametros["frm_atencion"];
	// 	$parametros['dau_forma_llegada']           = $parametros["frm_formallegada"];
	// 	$parametros['dau_mordedura']               = $parametros["frm_mordedura"];
	// 	$parametros['dau_intoxicacion']            = $parametros["frm_intoxicacion"];
	// 	$parametros['dau_quemadura']               = $parametros["frm_quemadura"];
	// 	$parametros['dau_tipo_mordedura']          = $parametros["frm_tipo_mordedura"];
	// 	$parametros['idctacte']                	   = 0;
	// 	$parametros['dau_imputado']                = $parametros["frm_imputado"];
	// 	$parametros['dau_reanimacion']			   = $parametros["frm_reanimacion"];
	// 	$parametros['dau_conscripto']			   = $parametros["frm_conscripto"];

	// 	if ( $parametros['frm_motivo'] ) {

	// 		$parametros['dau_motivo_descripcion'] = $parametros['frm_motivo'];

	// 	}

	// 	if ( $parametros['frm_motivoAgresion'] ) {

	// 		$parametros['dau_motivo_descripcion'] = $parametros['frm_motivoAgresion'];

	// 	}

	// 	if ( $parametros['frm_motivoLesiones'] ) {

	// 		$parametros['dau_motivo_descripcion'] = $parametros['frm_motivoLesiones'];

	// 	}

	// 	if ( $parametros['frm_motivoAlcoholemia'] ) {

	// 		$parametros['dau_motivo_descripcion'] = $parametros['frm_motivoAlcoholemia'];

	// 	}

	// 	$parametros['dau_tipo_accidente']                = $parametros['frm_tipoAccidente'];
	// 	$parametros['dau_accidente_escolar_institucion'] = $parametros['frm_institucion'];
	// 	$parametros['dau_accidente_escolar_numero']      = $parametros['frm_numero'];
	// 	$parametros['dau_accidente_escolar_nombre']      = $parametros['frm_nombre2'];
	// 	$parametros['dau_accidente_trabajo_mutualidad']  = $parametros['frm_mutualidad'];
	// 	$parametros['dau_accidente_transito_tipo']       = $parametros['frm_transitoTipo'];
	// 	$parametros['dau_accidente_hogar_lugar']         = $parametros['frm_hogar'];
	// 	$parametros['dau_accidente_otro_lugar']          = $parametros['frm_lugarPublico'];
	// 	$parametros['dau_agresion_vif']                  = $parametros['frm_vif'];
	// 	$parametros['dau_manifestaciones']      		 = ( is_null($parametros['frm_manifestaciones']) || empty($parametros['frm_manifestaciones']) ) ? 'N' : $parametros['frm_manifestaciones'];
	// 	$parametros['dau_constatacion_lesiones']   		 = ( is_null($parametros['frm_constatacionLesiones']) || empty($parametros['frm_constatacionLesiones']) ) ? 'N' : $parametros['frm_constatacionLesiones'];

	// 	if ( $parametros['frm_fechaAdmision'] ) {

	// 		$parametros['frm_fechaAdmision'] = date('Y-m-d H:i:s', strtotime($parametros['frm_fechaAdmision']));

	// 		$parametros['dau_tipo_admision'] = 'M';

	// 	}

	// 	$parametros['frm_nombres'] = $parametros['frm_nombres_dau'];
	// 	$parametros['frm_AP'] = $parametros['frm_AP_dau'];
	// 	$parametros['frm_AM'] = $parametros['frm_AM_dau'];

	// 	$parametros["dau_viaje_epidemiologico"] = $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
	// 	$parametros["dau_pais_epidemiologia"] = ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
	// 	$parametros["dau_observacion_epidemiologica"] = ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;

	// 	try {

	// 		$objCon->beginTransaction();

	// 		$subparametros                           = array();
	// 		$subparametros['frm_id_paciente']        = $_POST["idPacienteDau"];

	// 		$objCon->setDB("paciente");

	// 		if ( $parametros['pacienteFallDau'] == "S" ) {

	// 			$response = array("status" => "fallecido");

	// 		} else {

	// 			$datos 		= $objPac->listarPaciente($objCon, $subparametros);
	// 			$nrFichaPac = $datos[0]['nroficha'];

	// 			if ( $parametros['frm_sexo'] == null || $parametros['frm_sexo'] == '' ) {
	// 				$parametros['frm_sexo'] = "D";
	// 			}

	// 			if ( $parametros['frm_etnia'] == null || $parametros['frm_etnia'] == '' ) {
	// 				$parametros['frm_etnia'] = "14";
	// 			}

	// 			if ( $parametros['frm_prais'] == null || $parametros['frm_prais'] == '' ) {
	// 				$parametros['frm_prais'] = "0";
	// 			}

	// 			if ( $parametros['frm_Naciemito'] ) {
	// 				$parametros['frm_Naciemito'] = date("Y-m-d", strtotime(str_replace("/", "-", $parametros['frm_Naciemito'])));
	// 			}

	// 			if ( $parametros['frm_centroAtencion'] == null || $parametros['frm_centroAtencion'] == '' ) {
	// 				$parametros['frm_centroAtencion'] = "18";
	// 			}

	// 			if ( $parametros['frm_Nacionalidad'] == null || $parametros['frm_Nacionalidad'] == '' ) {
	// 				$parametros['frm_Nacionalidad'] = "NOINF";
	// 			}

	// 			if ( $parametros['frm_pais_nacimiento'] == null || $parametros['frm_pais_nacimiento'] == '' ) {
	// 				$parametros['frm_pais_nacimiento'] = "NOINF";
	// 			}

	// 			if ( $parametros['frm_region'] == null || $parametros['frm_region'] == '' ) {
	// 				$parametros['frm_region'] = "99";
	// 			}

	// 			if ( $parametros['frm_ciudad'] == null || $parametros['frm_ciudad'] == '' ) {
	// 				$parametros['frm_ciudad'] = "999";
	// 			}

	// 			if ( $parametros['frm_comuna'] == null || $parametros['frm_comuna'] == '' ) {
	// 				$parametros['frm_comuna'] = 349;
	// 			}

	// 			if ( $parametros['frm_sectorDomicilio'] == null || $parametros['frm_sectorDomicilio'] == '' ) {
	// 				$parametros['frm_sectorDomicilio'] = "4";
	// 			}

	// 			if ( $parametros['frm_condicionIngreso'] == null || $parametros['frm_condicionIngreso'] == '' ) {
	// 				$parametros['dau_cierre_condicion_ingreso_id'] = "5";
	// 			} else {
	// 				$parametros['dau_cierre_condicion_ingreso_id'] = $parametros['frm_condicionIngreso'];
	// 			}

	// 			$parametros['sintomasRespiratorios'] = "";

	// 			$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_dolorGarganta']) || empty($parametros['frm_dolorGarganta']) ) ? 'N' : 'S';

	// 			$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_tos']) || empty($parametros['frm_tos']) ) ? 'N' : 'S';

	// 			$parametros['sintomasRespiratorios'] .= ( is_null($parametros['frm_dificultadRespiratoria']) || empty($parametros['frm_dificultadRespiratoria']) ) ? 'N' : 'S';



	// 			$arrayEdit = array(
	// 								"nombre" => $parametros['frm_nombres'],
	// 								$parametros['frm_AP'],
	// 								$parametros['frm_AM'],
	// 								$parametros['frm_direccion'],
	// 								$parametros['frm_centroAtencion'],
	// 								$parametros['frm_Naciemito'],
	// 								$parametros['frm_sexo'],
	// 								$parametros['frm_correo'],
	// 								$parametros['frm_telefonoFijo'],
	// 								$parametros['frm_telefonoCelular'],
	// 								$parametros['frm_etnia'],
	// 								$parametros['frm_Nacionalidad'],
	// 								$parametros['frm_prevision'],
	// 								$parametros['frm_formaPago'],
	// 								$parametros['frm_afrodescendiente'],
	// 								$parametros['frm_pais_nacimiento'],
	// 								$parametros['frm_region'],
	// 								$parametros['frm_ciudad'],
	// 								$parametros['frm_comuna'],
	// 								$parametros['frm_prais'],
	// 								$parametros['frm_nombreCalle'],
	// 								$parametros['frm_numeroDireccion'],
	// 								$parametros['frm_sectorDomicilio'],
	// 								$parametros['frm_tipoDomicilio'],
	// 								$parametros['frm_otrosTelefonos'],
	// 								$parametros['frm_correo']
	// 							);

	// 			$arrayBase = array(
	// 								"nombre" => $datos[0]['nombres'],
	// 								$datos[0]['apellidopat'],
	// 								$datos[0]['apellidomat'],
	// 								$datos[0]['restodedireccion'],
	// 								$datos[0]['centroatencionprimaria'],
	// 								$datos[0]['fechanac'],
	// 								$datos[0]['sexo'],
	// 								$datos[0]['email'],
	// 								$datos[0]['PACfono'],
	// 								$datos[0]['fono1'],
	// 								$datos[0]['etnia'],
	// 								$datos[0]['nacionalidad'],
	// 								$datos[0]['prevision'],
	// 								$datos[0]['conveniopago'],
	// 								$datos[0]['PACafro'],
	// 								$datos[0]['paisNacimiento'],
	// 								$datos[0]['region'],
	// 								$datos[0]['ciudad'],
	// 								$datos[0]['idcomuna'],
	// 								$datos[0]['prais'],
	// 								$datos[0]['calle'],
	// 								$datos[0]['numero'],
	// 								$datos[0]['sector_domicilio'],
	// 								$datos[0]['conruralidad'],
	// 								$datos[0]['PACfonoOtros'],
	// 								$datos[0]['email']
	// 							);

	// 			$resultado = array_diff_assoc($arrayEdit, $arrayBase);

	// 			if ( count($resultado) ) {

	// 				$parametros2['reg_usuario_insercion']    = $_SESSION['MM_Username'.SessionName];
	// 				$parametros2['frm_tipoLog']              = "S";
	// 				$parametros2['frm_AP']                   = $datos[0]['apellidopat'];
	// 				$parametros2['frm_AM']                   = $datos[0]['apellidomat'];
	// 				$parametros2['frm_nombres']              = $datos[0]['nombres'];
	// 				$parametros2['frm_rut_pac']              = $datos[0]['rut'];
	// 				$parametros2['frm_id_paciente']          = $datos[0]['id'];
	// 				$parametros2['frm_prevision']            = $datos[0]['prevision'];
	// 				$parametros2['frm_convenio']             = $datos[0]['conveniopago'];
	// 				$parametros2['frm_nroFicha']             = $datos[0]['nroficha'];
	// 				$parametros2['frm_Naciemito']            = $datos[0]['fechanac'];
	// 				$parametros2['frm_telefonoFijo']         = $datos[0]['fono1'];
	// 				$parametros2['frm_telefonoCelularAvis']  = $datos[0]['fono2'];
	// 				$parametros2['frm_telefono_laboral']     = $datos[0]['fono3'];
	// 				$parametros2['frm_sexo']                 = $datos[0]["sexo"];
	// 				$parametros2['frm_id_trakcare']          = $datos[0]["id_trakcare"];
	// 				$parametros2['frm_sistemas']             = "DAU";
	// 				$parametros2['frm_pais_nacimiento']      = $datos[0]["paisNacimiento"];
	// 				$parametros2['frm_region']     			 = $datos[0]["region"];
	// 				$parametros2['frm_ciudad']     			 = $datos[0]["ciudad"];
	// 				$parametros2['frm_comuna']     			 = $datos[0]["idcomuna"];
	// 				$parametros2['frm_prais']     			 = $datos[0]["prais"];
	// 				$parametros2['frm_nombreCalle']     	 = $datos[0]["calle"];
	// 				$parametros2['frm_numeroDireccion']      = $datos[0]["numero"];
	// 				$parametros2['frm_direccion']            = $datos[0]["restodedireccion"];
	// 				$parametros2['frm_sectorDomicilio']      = $datos[0]["sector_domicilio"];
	// 				$parametros2['frm_tipoDomicilio']        = $datos[0]["conruralidad"];
	// 				$parametros2['frm_otrosTelefonos']       = $datos[0]["PACfonoOtros"];
	// 				$parametros2['frm_etnia']      		     = $datos[0]["etnia"];
	// 				$parametros2['frm_correo']    		     = $datos[0]["email"];

	// 				$respuesta2                              = $objLog->registrar_log($objCon, $parametros2);

	// 				$parametros['reg_usuario_insercion'] 	 = $_SESSION['MM_Username'.SessionName];
	// 				$parametros['frm_tipoLog']           	 = "U";
	// 				$parametros['frm_rut_pac']               = $parametros['numeroDocumento'];
	// 				$parametros['frm_id_paciente']           = $datos[0]['id'];
	// 				$parametros['frm_nroFicha']			     = $datos[0]['nroficha'];
	// 				$parametros['frm_telefono_laboral']      = $parametros["frm_telefonoCelular"];
	// 				$parametros['frm_telefonoCelularAvis']   = $datos[0]['fono2'];
	// 				$parametros['frm_id_trakcare']           = $datos[0]["id_trakcare"];
	// 				$parametros['frm_convenio']              = $parametros['frm_formaPago'];
	// 				$parametros['frm_sistemas']              = "DAU";
	// 				$parametros['frm_afrodescendiente']      = $parametros['frm_afrodescendiente'];
	// 				$parametros['frm_pais_nacimiento']       = $parametros['frm_pais_nacimiento'];
	// 				$parametros['frm_region']      			 = $parametros['frm_region'];
	// 				$parametros['frm_ciudad']      			 = $parametros['frm_ciudad'];
	// 				$parametros['frm_comuna']      			 = $parametros['frm_comuna'];
	// 				$parametros['frm_prais']     			 = $parametros['frm_prais'] ;
	// 				$parametros['frm_nombreCalle']     	 	 = $parametros['frm_nombreCalle'];
	// 				$parametros['frm_numeroDireccion']       = $parametros['frm_numeroDireccion'];
	// 				$parametros['frm_direccion']             = $parametros['frm_direccion'] ;
	// 				$parametros['frm_sectorDomicilio']       = $parametros['frm_sectorDomicilio'];
	// 				$parametros['frm_tipoDomicilio']         = $parametros['frm_tipoDomicilio'] ;
	// 				$parametros['frm_otrosTelefonos']        = $parametros['frm_otrosTelefonos'] ;
	// 				$parametros['frm_etnia']        		 = $parametros['frm_etnia'] ;
	// 				$parametros['frm_correo']        		 = $parametros['frm_correo'] ;

	// 				$respuesta3                          	 = $objLog->registrar_log($objCon, $parametros);

	// 				$parametros['frm_nombres']               = strtoupper($parametros['frm_nombres']);
	// 				$parametros['frm_AP']                    = strtoupper($parametros['frm_AP']);
	// 				$parametros['frm_AM']                    = strtoupper($parametros['frm_AM']);
	// 				$parametros['frm_nroDocumento']          = $parametros['documento'];
	// 				$parametros['dv']                        = $objUtil->generaDigito($parametros['numeroDocumento']);

	// 				$objPac->actualizarPacienteDAU($objCon, $parametros);

	// 			}

	// 			$objCon->setDB("dau");

	// 			if ( $parametros['id_doc_documentoDau'] == 0 ) {
	// 				$parametros['frm_rut'] = $parametros['numeroDocumento'];
	// 			} else {
	// 				if ( $parametros['id_doc_documentoDau'] == 1 || $parametros['id_doc_documentoDau'] == 2 || $parametros['id_doc_documentoDau'] == 3 ) {
	// 					$parametros['frm_rut'] = $parametros['numeroDocumento'];
	// 				}
	// 			}

	// 			$respuesta                			= $objAdmision->agregarAdmision($objCon, $parametros);
	// 			$parametros['dau_id']     			= $respuesta;
	// 			ingresarPacienteDerivado($objCon, $objUtil, $objAdmision, $parametros);
	// 			$datos                    			= $objAdmision->listarDatosDau($objCon, $parametros);
	// 			$respuesta2               			= $objCtaCte->nuevaCtaCte($link,$datos[0]["id_paciente"],$_POST['numeroDocumento'],$datos[0]["dau_admision_fecha"],10322,$datos[0]["dau_paciente_prevision"],$datos[0]["dau_id"],1,$parametros['dau_paciente_forma_pago']);
	// 			$parametros['ctaCte']     			= $respuesta2;
	// 			$respuesta3               			= $objAdmision->ActualizarCtaCorriente($objCon, $parametros);
	// 			$parametros['dau_mov_descripcion'] 	= 'admision dau';
	// 			$parametros['dau_mov_tipo'] 		= 'adm';
	// 			$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];

	// 			$objCon->setDB("recauda");

	// 			$arrConv = array(1 => '7', 2 => '8', 3 => '9', 4 => '10', 5 => '11');
	// 			$parametros3 = array();
	// 			$parametros3['det_pre_cta_cte']      = $parametros['ctaCte'];
	// 			$parametros3['det_pre_usuario']      = $parametros['dau_mov_usuario'];
	// 			$parametros3['det_pre_rut_paciente'] = $datos[0]["id_paciente"];
	// 			$parametros3['det_pre_cantidad']     = '1';
	// 			$parametros3['det_pre_cod_sscc']     = '10322';
	// 			$parametros3['det_pre_valor_unit']   = '0';

	// 			if ( array_search(c, $arrConv) != null ) {
	// 				$parametros3['det_pre_codigo'] = '0101001';
	// 			} else {
	// 				$parametros3['det_pre_codigo'] = '0101103';
	// 			}

	// 			$objAdmision->addPrestacionAdmisionPaciente($objCon, $parametros3);

	// 			if ($parametros['frm_prevision'] < 4) {

	// 				$objCon->setDB("paciente");

	// 				$resp_vp = $objDau->valorPrestacion($objCon);
	// 				$resp_np = $objDau->nombrePrevision($objCon, $parametros);
	// 				$resp_ni = $objDau->nombreInstitucion($objCon, $parametros);

	// 				$parametros4 									= array();
	// 				$parametros4['matrizCodPrestacion'] 			= '0101103';
	// 				$parametros4['matrizTipoPrestacionValorada'] 	= 'I';
	// 				$parametros4['matrizCodPrograma'] 				= 0;
	// 				$parametros4['matrizCantPrestacion'] 			= 1;
	// 				$parametros4['matrizNombrePrestacion'] 			= 'Consulta médica integral en servicio de urgencia (Hosp. tipo 1)';
	// 				$parametros4['matrizTipoPrestacion']	 		= 'P';
	// 				$parametros4['matrizCodPatologia'] 				= 0;
	// 				$parametros4['matrizNombrePatologia'] 			= '';
	// 				$parametros4['matrizValorPrestacion'] 			= $resp_vp[0]['preFacturacion'];
	// 				$parametros4['matrizPacieCod'] 					= $datos[0]["id_paciente"];
	// 				$parametros4['matrizRutPaciente'] 				= $parametros['numeroDocumento'];
	// 				$parametros4['matrizNombrePacie'] 				= $parametros['frm_nombres']." ".$parametros['frm_AP']." ".$parametros['frm_AM'];
	// 				$parametros4['matrizFichaPacie'] 				= $nrFichaPac;
	// 				$parametros4['matrizSexoPacie'] 				= $parametros['frm_sexo'];
	// 				$parametros4['matrizFNacPacie'] 				= $parametros['frm_Naciemito'];
	// 				$parametros4['matrizPreviCod'] 					= $parametros['frm_prevision'];
	// 				$parametros4['matrizConvenio'] 					= $parametros['frm_formaPago'];
	// 				$parametros4['matrizCodServicio'] 				= 10322;
	// 				$parametros4['matrizNomServicio'] 				= 'urgencia';
	// 				$parametros4['matrizFRegPrestacion'] 			= date('Y-m-d', strtotime($parametros['frm_fechaAdmision']));
	// 				$parametros4['matrizFDigitacion'] 				= '';
	// 				$parametros4['matrizOrigenAtPrestacion'] 		= 'Urgencia';
	// 				$parametros4['matrizTablaOrigen'] 				= 'dau';
	// 				$parametros4['matrizCantidadComprometida'] 		= 0;
	// 				$parametros4['matrizEdadPaciente'] 				= $objUtil->edadActual(date('Y-m-d', strtotime($parametros['frm_fechaAdmision'])));
	// 				$parametros4['matrizPreviNombre'] 				= $resp_np[0]['prevision'];
	// 				$parametros4['matrizConvenioNombre'] 			= $resp_ni[0]['instNombre'];
	// 				$parametros4['matrizNombrePrograma'] 			= '';
	// 				$parametros4['matrizNombreCompromiso'] 			= '';
	// 				$parametros4['matrizCodCompromiso'] 			= 0;
	// 				$parametros4['matrizCodSistema'] 				= $parametros['dau_id'];
	// 				$parametros4['matrizTipoSistema'] 				= 'rau';
	// 				$parametros4['matrizUsuario'] 					= $parametros['dau_mov_usuario'];
	// 				$parametros4['dau_tipo_admision'] 				= $parametros['dau_tipo_admision'];

	// 				$objCon->setDB("estadistica");

	// 				$objDau->registrarPacAdmisionadoMatrizEstadistica($objCon, $parametros4);

	// 			}

	// 			$objCon->setDB("dau");

	// 			$objMovimiento->guardarMovimiento($objCon, $parametros);

	// 			$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['dau_viaje_epidemiologico'])) ? " - ".$parametros['dau_viaje_epidemiologico'] : NULL;

	// 			$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['dau_pais_epidemiologia'])) ? " - ".$parametros['dau_pais_epidemiologia'] : NULL;

	// 			$parametros['dau_mov_descripcion'] 	.= ($objUtil->existe($parametros['frm_observacionEpidemiologica'])) ? " - ".$parametros['frm_observacionEpidemiologica'] : NULL;

	// 			$objMovimiento->guardarMovimiento($objCon, $parametros);

	// 			//CMBD ADMISIÓN
	// 			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 1);

	// 			$response  = array("status" => "success","ultimoID" => $parametros['dau_id'], "ultimaCtaCte" => $parametros['ctaCte']);
	// 		}

	// 		$objCon->commit();

	// 		echo json_encode($response);

	// 	} catch (PDOException $e) {

	// 		$objCon->rollback();

	// 		$response = array("status" => "error", "message" => $e->getMessage());

	// 		echo json_encode($response);

	// 	}

	// break;



	// case "actualizarPacienteAdmision":

	// 	require_once("../../../class/Admision.class.php");		   $objAdmision     = new Admision;
	// 	require_once("../../../class/Movimientos.class.php");	   $objMovimiento   = new Movimientos;

	// 	$objCon->db_connect();


	// 	$parametros                                       = $objUtil->getFormulario($_POST);
	// 	$parametros['dau_id']                      		  = $parametros['id'];
	// 	$parametros['dau_paciente_domicilio_tipo'] 		  = $parametros["frm_tipoDomicilio"];
	// 	$parametros['dau_paciente_prevision']      		  = $parametros["frm_previson"];
	// 	$parametros['dau_paciente_forma_pago']     		  = $parametros["frm_formaPago"];
	// 	$parametros['dau_atencion']                		  = $parametros["frm_atencion"];
	// 	$parametros['dau_motivo_consulta']         		  = $parametros["frm_motivoConsulta"];

	// 	if($parametros['frm_motivo']){
	// 		$parametros['dau_motivo_descripcion']  		  = $parametros['frm_motivo'];
	// 	}

	// 	$parametros['dau_forma_llegada']                  = $parametros["frm_formallegada"];
	// 	$parametros['dau_mordedura']                      = $parametros["frm_mordedura"];
	// 	$parametros['dau_intoxicacion']                   = $parametros["frm_intoxicacion"];
	// 	$parametros['dau_quemadura']                      = $parametros["frm_quemadura"];
	// 	$parametros['dau_imputado']                       = $parametros["frm_imputado"];
	// 	$parametros['dau_reanimacion']			          = $parametros["frm_reanimacion"];
	// 	$parametros['dau_conscripto']			          = $parametros["frm_conscripto"];
	// 	$parametros['dau_tipo_accidente']                 = $parametros['frm_tipoAccidente'];
	// 	$parametros['dau_accidente_escolar_institucion']  = $parametros['frm_institucion'];
	// 	$parametros['dau_accidente_escolar_numero']       = $parametros['frm_numero'];
	// 	$parametros['dau_accidente_escolar_nombre']       = $parametros['frm_nombre2'];
	// 	$parametros['dau_accidente_trabajo_mutualidad']   = $parametros['frm_mutualidad'];
	// 	$parametros['dau_accidente_transito_tipo']        = $parametros['frm_transitoTipo'];
	// 	$parametros['dau_accidente_hogar_lugar']          = $parametros['frm_hogar'];
	// 	$parametros['dau_accidente_otro_lugar']           = $parametros['frm_lugarPublico'];
	// 	$parametros['dau_agresion_vif']                   = $parametros['frm_vif'];
	// 	$parametros['dau_tipo_mordedura']                 = $parametros["frm_tipo_mordedura"];
	// 	$parametros['dau_manifestaciones']      		 = ( is_null($parametros['frm_manifestaciones']) || empty($parametros['frm_manifestaciones']) ) ? 'N' : $parametros['frm_manifestaciones'];
	// 	$parametros['dau_constatacion_lesiones']   		 = ( is_null($parametros['frm_constatacionLesiones']) || empty($parametros['frm_constatacionLesiones']) ) ? 'N' : $parametros['frm_constatacionLesiones'];

	// 	$parametros['sintomasRespiratorios'] 			 = "";
	// 	$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_dolorGarganta']) || empty($parametros['frm_dolorGarganta']) ) ? 'N' : 'S';
	// 	$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_tos']) || empty($parametros['frm_tos']) ) ? 'N' : 'S';
	// 	$parametros['sintomasRespiratorios'] 			.= ( is_null($parametros['frm_dificultadRespiratoria']) || empty($parametros['frm_dificultadRespiratoria']) ) ? 'N' : 'S';

	// 	try{
	// 		$objCon->beginTransaction();
	// 		$respuesta                			= $objAdmision->actualizarAdmision($objCon, $parametros);
	// 		$respuesta2               			= $objAdmision->actualizarFechas($objCon, $parametros);
	// 		$parametros['dau_mov_descripcion'] 	= "admision actulizacion dau";
	// 		$parametros['dau_mov_tipo'] 		= "ada";
	// 		$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
	// 		$objMovimiento->guardarMovimiento($objCon, $parametros);
	// 		ingresarPacienteDerivado($objCon, $objUtil, $objAdmision, $parametros);

	// 		$response = array("status" => "success","id" => $parametros['dau_id']);

	// 		$objCon->commit();

	// 		echo json_encode($response);

	// 	} catch (PDOException $e) {

	// 		$objCon->rollback();

	// 		$response = array("status" => "error", "message" => $e->getMessage());

	// 		echo json_encode($response);

	// 	}

	// break;



	


	// case "cargarCiudades":

	// 	require_once("../../../class/Localidad.class.php");		$objLocalidad = new Localidad;

	// 	$objCon->db_connect();

	// 	$response = $objLocalidad->listarCiudadesPorRegion($objCon, $_POST["regId"]);

	// 	echo json_encode($response);

	// break;



// 	case "cargarComunas":

// 		require_once("../../../class/Localidad.class.php");		$objLocalidad = new Localidad;

// 		$objCon->db_connect();

// 		$response = $objLocalidad->listarComunasPorRegion($objCon, $_POST["ciuId"]);

// 		echo json_encode($response);

// 	break;



	



// 	case "obtenerCategorizacionesContingencia":

// 		require_once("../../../class/Categorizacion.class.php");

// 		$objCategorizacion = new Categorizacion;

// 		$objCon->db_connect();

// 		$parametros = $objUtil->getFormulario($_POST);

// 		$categorizaciones = $objCategorizacion->obtenerCategorizacionesContingencia($objCon);

// 		echo json_encode($categorizaciones);

// 	break;



// 	case "obtenerPronosticosAltaUrgenciaContingencia":

// 		require_once('../../../class/Pronostico.class.php');

// 		$objPronostico = new Pronostico;

// 		$objCon->db_connect();

// 		$pronosticos = $objPronostico->listarPronosticos($objCon);

// 		echo json_encode($pronosticos);

// 	break;



// 	case "obtenerIndicacionesEgresoAltaUrgenciaContingencia":

// 		require_once("../../../class/Dau.class.php" );

// 		$objDau = new Dau;

// 		$objCon->db_connect();

// 		$indicacionesEgreso = $objDau->ListarIndicacionEgreso($objCon, 'indicacionContingencia');

// 		echo json_encode($indicacionesEgreso);

// 	break;



// 	case "obtenerDestinosAltaUrgenciaContingencia":

// 		require_once("../../../class/Dau.class.php" );

// 		$objDau = new Dau;

// 		$objCon->db_connect();

// 		$destinos = $objDau->obtenerDestinosUrgenciaContingencia($objCon);

// 		echo json_encode($destinos);

// 	break;



// 	case "obtenerEspecialidadesAltaUrgenciaContingencia":

// 		require_once("../../../class/Agenda.class.php" );

// 		$objAgenda = new Agenda;

// 		$objCon->db_connect();

// 		$especialidades = $objAgenda->getEspecialidad($objCon);

// 		echo json_encode($especialidades);

// 	break;



// 	case "obtenerAPSAltaUrgenciaContingencia":

// 		require_once("../../../class/Dau.class.php" );

// 		$objDau = new Dau;

// 		$objCon->db_connect();

// 		$aps = $objDau->getAPS($objCon);

// 		echo json_encode($aps);

// 	break;



// 	case "obtenerServiciosDestinoAltaUrgenciaContingencia":

// 		require_once("../../../class/Servicios.class.php");

// 		$objServicio = new Servicios;

// 		$objCon->db_connect();

// 		$serviciosDestino = $objServicio->ListarServiciosDau($objCon);

// 		echo json_encode($serviciosDestino);

// 	break;



// 	case "obtenerPrioridadesEspecialistaAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$prioridades = $objRce->obtenerTiposPrioridad($objCon);

// 		echo json_encode($prioridades);

// 	break;



// 	case "obtenerMotivosEspecialistaAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$motivosConsulta = $objRce->obtenerTiposMotivoConsulta($objCon);

// 		echo json_encode($motivosConsulta);

// 	break;



// 	case "obtenerViolenciaAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$violencia = $objRce->obtenerTiposViolencias($objCon);

// 		echo json_encode($violencia);

// 	break;



// 	case "obtenerSospechaPenetracionAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$sospechaPenetracion = $objRce->obtenerSospechasPenetracion($objCon);

// 		echo json_encode($sospechaPenetracion);

// 	break;



// 	case "obtenerProfilaxisAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$profilaxis = $objRce->obtenerTipoProfilaxis($objCon);

// 		echo json_encode($profilaxis);

// 	break;



// 	case "obtenerTipoLesionAltaUrgenciaContingencia":

// 		require_once('../../../class/Rce.class.php');

// 		$objRce = new Rce;

// 		$objCon->db_connect();

// 		$tipoLesion = $objRce->obtenerTipoLesionesVictima($objCon);

// 		echo json_encode($tipoLesion);

// 	break;



// 	case "registrarContingencia":

// 		require_once("../../../class/Admision.class.php");

// 		$objAdmision     = new Admision;

// 		$objCon->db_connect();

// 		try{
// 			$objCon->beginTransaction();

// 			$parametros = $objUtil->getFormulario($_POST);

// 			$objAdmision->ingresarDatosContingencia($objCon, $parametros);

// 			$response = array("status" => "success");

// 			$objCon->commit();

// 			echo json_encode($response);

// 		} catch (PDOException $e) {

// 			$objCon->rollback();

// 			$response = array("status" => "error", "message" => $e->getMessage());

// 			echo json_encode($response);

// 		}

// 	break;



// 	case "cambiarEstadoDAUContingencia":

// 		require_once("../../../class/Admision.class.php");

// 		$objAdmision     = new Admision;

// 		$objCon->db_connect();

// 		$parametros = $objUtil->getFormulario($_POST);

// 		try{
// 			$objCon->beginTransaction();

// 			$objAdmision->cambiarEstadoDAUContingencia($objCon, $parametros);

// 			$response = array("status" => "success");

// 			$objCon->commit();

// 			echo json_encode($response);

// 		} catch (PDOException $e) {

// 			$objCon->rollback();

// 			$response = array("status" => "error", "message" => $e->getMessage());

// 			echo json_encode($response);

// 		}

// 	break;



// 	case "subirArchivo":

// 		require_once('../../../class/Formulario.class.php');

// 		$objFormulario = new Formulario;

// 		$files                        = $_FILES['userfile']['name'][0];

// 		$postFile['name']             = $_FILES["userfile"]['name'][0];

// 		$postFile['type']             = $_FILES["userfile"]['type'][0];

// 		$postFile['tmp_name']         = $_FILES["userfile"]['tmp_name'][0];

// 		$postFile['error']            = $_FILES["userfile"]['error'][0];

// 		$postFile['size']             = $_FILES["userfile"]['size'][0];

// 		$parametros['extension']	  = strtolower(pathinfo($_FILES['userfile']['name'][0], PATHINFO_EXTENSION));

// 		$parametros['directorio']     = "dauContingencia/";

// 		$parametros['nombre_archivo'] = $_POST['idDau']."_".$_POST['idPacienteDau'];

// 		$parametros['mode']           = FTP_BINARY;

// 		$extension                    = $parametros['extension'];

// 		$response = $objFormulario->subirArchivosFTP($objCon, $postFile, $parametros);

// 	break;

// }



function ingresarPacienteDerivado ( $objCon, $objUtil, $objAdmision, $parametros ) {
	$objAdmision->eliminarPacienteDerivadoSegunDau($objCon, $parametros['dau_id']);
	if ( $parametros['slc_derivado'] == 'N' ) {
		return;
	}
	$parametrosAEnviar                              = array();
	$parametrosAEnviar['idDau']                     = $parametros['dau_id'];
	$parametrosAEnviar['idEstablecimientoRedSalud'] = $parametros['frm_establecimientosRedSalud'];
	$parametrosAEnviar['nombreOtroEstablecimiento'] = $parametros['frm_nombreOtrosEstablecimientos'];
	$parametrosAEnviar['usuarioInserta'] 			= $_SESSION['MM_Username'.SessionName];
	$objAdmision->ingresarPacienteDerivado($objCon, $parametrosAEnviar);
	unset($parametrosAEnviar);

}



$objCon = null;
?>
