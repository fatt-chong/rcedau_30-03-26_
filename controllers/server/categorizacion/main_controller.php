<?php session_start();
error_reporting(0);

            // print('<pre>'); print_r($_SESSION); print('</pre>');
// print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
            require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 		$objCon      		= new Connection; $objCon->db_connect();
require_once("../../../class/Util.class.php"); 		 		$objUtil       		= new Util;
require_once("../../../class/Dau.class.php"); 	 	 		$objDau 			= new Dau();
require_once("../../../class/CMBD.class.php");       		$objCMBD       		= new CMBD;
require_once('../../../class/Categorizacion.class.php'); 	$objCat   			= new Categorizacion;
// require_once("../../../class/Movimientos.class.php");	 $objMovimiento = new Movimientos;
require_once("../../../class/Rce.class.php"); 	 	 	 	$objRce 			= new Rce();
require_once('../../../class/Bitacora.class.php');  		$objBitacora   		= new Bitacora;
require_once('../../../class/RegistroClinico.class.php'); 	$objRegistroClinico = new RegistroClinico;
require_once('../../../class/SqlDinamico.class.php');    	$objSqlDinamico    	= new SqlDinamico;
require_once('../../../class/Menu_colores.class.php');    	$objMenu_colores    = new Menu_colores;



switch ( $_POST['accion'] ) {
	case 'ObtenerSignosVitales': 
		require_once('../../../class/Categorizacion.class.php');     $objCate        	= new Categorizacion;
		$parametros = $objUtil->getFormulario($_POST);
		try {
			$objCon -> beginTransaction();
			$parametros['dau_id']  	= $parametros['idDau'];
			$datosRce 				= $objRegistroClinico->consultaRCE($objCon,$parametros);
			$datosU 				= $objCate -> searchPaciente($objCon, $parametros['idDau']);
    		$listaSignos 			= $objRce->listarSignosVitales($objCon, $datosU[0]['id_paciente'], $datosRce[0]['regId'] ?? null);
    		if( count($listaSignos) > 0 ){

				$response = array("status" => "success");
    		}else{

				$response = array("status" => "error");
    		}
			$objCon -> commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon -> rollback();
			$response = array("status" => "error", "message" => $e -> getMessage());
			echo json_encode($response);
		}
	break;
	case 'SDD':
		require_once("../../../class/Movimiento.class.php");		$objMovimiento  = new Movimiento;

		$parametros = $objUtil->getFormulario($_POST);
	    if ( ! isset($parametros['dau_cat_4_fr_1']) || $parametros['dau_cat_4_temp'] == "" ) {
	    	$parametros['dau_cat_4_fr_1'] = 'null';
		} else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " TEMPERATURA (".$parametros['dau_cat_4_temp'].") ";
		}
	    if ( ! isset($parametros['dau_cat_4_fr_2']) || $parametros['dau_cat_4_fr_2'] == "" ) {
	    	$parametros['dau_cat_4_fr_2'] = 'null';
		} else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FR (".$parametros['dau_cat_4_fr_2'].") ";
		}
	    if ( ! isset($parametros['dau_cat_4_fc']) || $parametros['dau_cat_4_fc'] == "" ) {
	    	$parametros['dau_cat_4_fc'] = 'null';
		} else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FC (".$parametros['dau_cat_4_fc'].") ";
		}
	    if ( ! isset($parametros['dau_cat_4_temp']) || $parametros['dau_cat_4_temp'] == "" ) {
	    	$parametros['dau_cat_4_temp'] = 'null';
		} else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FC (".$parametros['dau_cat_4_temp'].") ";
		}
	    if ( ! isset($parametros['dau_cat_4_satu']) || $parametros['dau_cat_4_satu'] == "" ) {
	    	$parametros['dau_cat_4_satu'] = 'null';
		} else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FC (".$parametros['dau_cat_4_satu'].") ";
		}
	    if ( ! isset($parametros['dau_cat_4_temp_rec']) || $parametros['dau_cat_4_temp_rec'] == "" ) {
	    	$parametros['dau_cat_4_temp_rec'] = 'null';
	    } else {
			if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
				$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
			}
			$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FC (".$parametros['dau_cat_4_temp_rec'].") ";
		}
		$parametros['catesi'] = $parametros['dau_cat_4_categ'];
	    if ( $parametros['banderacat'] != 'DETALLE' ) {
	    	$parametros['est_id'] = 2;
	    }
		$parametros["dau_viaje_epidemiologico"] 		= $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
		$parametros["dau_pais_epidemiologia"] 			= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
		$parametros["dau_observacion_epidemiologica"] 	= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;
		try {
			$objCon -> beginTransaction();
			$parametros['dau_mov_descripcion'] 		= 'categorizacion';
			$parametros['dau_mov_tipo']		 		= "cat";
			$parametros['dau_mov_usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametros['dau_cat_usuario_inserta'] 	= $_SESSION['MM_Username'.SessionName];
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			$parametros['dau_mov_descripcion'] 		.= ($objUtil->existe($parametros['dau_viaje_epidemiologico'])) ? " - ".$parametros['dau_viaje_epidemiologico'] : NULL;
			$parametros['dau_mov_descripcion'] 		.= ($objUtil->existe($parametros['dau_pais_epidemiologia'])) ? " - ".$parametros['dau_pais_epidemiologia'] : NULL;
			$parametros['dau_mov_descripcion'] 		.= ($objUtil->existe($parametros['dau_observacion_epidemiologica'])) ? " - ".$parametros['dau_observacion_epidemiologica'] : NULL;
			$objMovimiento->guardarMovimiento($objCon, $parametros);
			$resp 		= $objCat -> asignarSDD($objCon, $parametros);
			if ( $parametros['recategorizar'] == 'S' ) {
				$resp2 	= $objCat -> RecategorizarDau($objCon, $parametros);
			}else if ( $parametros['inp_recat'] == '' ) {
				$resp2 	= $objCat -> updEstadoCat($objCon, $parametros);
			} else {
				$resp2 	= $objCat -> updEstado($objCon, $parametros);
			}

			insertarNumeroAtencionDau($objCon, $objDau, $objUtil, $parametros["catesi"], $parametros['dau_id']);
			$subparametrosBitacoraIndicaciones['BITid']                      	= $parametros['dau_id'];
			$subparametrosBitacoraIndicaciones['BITtipo_codigo']              	= 1;
			$subparametrosBitacoraIndicaciones['BITtipo_descripcion']		  	= "Signos vitales";
			$subparametrosBitacoraIndicaciones['BITusuario']                  	= $_SESSION['MM_Username'.SessionName];
			$subparametrosBitacoraIndicaciones['BITdescripcion']  			  	= $subparametrosBitacoraIndicaciones['BITdescripcionTitulo']." ".$subparametrosBitacoraIndicaciones['BITdescripcion'].".";
			$objBitacora->guardarBitacora($objCon,$subparametrosBitacoraIndicaciones);
			//CMBD CATEGORIZACIÓN
			$objCMBD->iniciarCMBD($objCon, $parametros["dau_id"], 2);
			$objCon -> commit();
			$response = array("status" => "success", "id" => $parametros['dau_id']);
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon -> rollback();
			$response = array("status" => "error", "message" => $e -> getMessage());
			echo json_encode($response);
		}
	break;

	case "verificarPermisoUsuario":
		$objCon->db_connect();
		$parametros['boton'] = $_POST['boton'];
		switch ( $parametros['boton'] ) {
			case "btn_indGINE":
				$permisosIdentifiacion=827;
			break;
			case "btn_ind":  	
				$permisosIdentifiacion=816;					 
			break;
			case "btn_hipotesisGine":
				$permisosIdentifiacion=825;
			break;
			case "btn_hipotesis":
				$permisosIdentifiacion=825;
			break;
			case "btn_iniciarAtencionGine":
				$permisosIdentifiacion=826;
			break;
			case "btn_iniciarAtencion":
				$permisosIdentifiacion=817;
			break;
			case "btn_pyxisGine":
				$permisosIdentifiacion=819;
			break;
			case "btn_pyxis":
				$permisosIdentifiacion=819;
			break;
			case "btn_indicacionAplicaGine":
				$permisosIdentifiacion=827;
			break;
			case "btn_indicacionAplica":
				$permisosIdentifiacion=818;
			break;
			case "btn_detalleDAUNEA":
				$permisosIdentifiacion=858;
			break;
			case "btn_categorizacionSDD_Gine":
				$permisosIdentifiacion=850;
			break;
			case "btn_categorizacionSDD":
				$permisosIdentifiacion=824;
			break;
			case "btn_categorizacionNormalEdad_1_y_2":
				$permisosIdentifiacion=823;
			break;
			case "btn_mapaPisoCama_a_CamaGine":
				$permisosIdentifiacion=850;
			break;
			case "btn_mapaPiso_cat_a_CamaGine":
				$permisosIdentifiacion=850;
			break;
			case "btn_mapaPiso_esp_a_CamaGine":
				$permisosIdentifiacion=850;
			break;
			case "btn_mapaPiso_esp_a_Cama":
				$permisosIdentifiacion=850;
			break;
			case "btn_mapaPiso_esp_a_Cama":
				$permisosIdentifiacion=822;
			break;
			case "btn_mapaPisoCama_a_Cama":
				$permisosIdentifiacion=820;
			break;
			case "btn_mapaPiso_cat_a_Cama":
				$permisosIdentifiacion=821;
			break;
			case "btn_rce_signosVitales": 
				$permisosIdentifiacion=859;
			break;
			case "btn_guardar_indicaciones": 
				$permisosIdentifiacion=860;
			break;
			case "btn_rce_antecedentes": 
				$permisosIdentifiacion=862;
			break;
			case "btn_rce_guardar": 
				$permisosIdentifiacion=863;
			break;
			case "btn_aplicar_indicaciones": 
				$permisosIdentifiacion=864;
			break;
			case "btn_eliminar_indicaciones": 
				$permisosIdentifiacion=865;
			break;
			case "btn_anular_indicaciones": 
				$permisosIdentifiacion=866;
			break;
			case "btn_tomaMuestra": 
				$permisosIdentifiacion=984;
			break;
			case "btn_inicioIndicacion": 
				$permisosIdentifiacion=985;
			break;
			case "btn_pacienteComplejo": 
				$permisosIdentifiacion=986;
			break;
			case "btn_cambioTurno": 
				$permisosIdentifiacion=993;
			break;
		}
		$parametros['permiso']  = $permisosIdentifiacion;
		$parametros['userName'] = $_SESSION['MM_Username'.SessionName];
		try {
			$objCon->beginTransaction();
			$datos     = $objDau->validaPermisoUsuario($objCon,$parametros);
			if ( count($datos) > 0 ) {
				$response  = true;
			} else {
				$response  = false;
			}
			$objCon->commit();		
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		
		}
	
	break;
	case 'pacienteYaCategorizadoSSD':
		$parametros = $objUtil->getFormulario($_POST);
		try {
			$objCon -> beginTransaction();
			$estadoDau = $objDau->obtenerEstadoDauPaciente($objCon, $parametros['idDau']);
			$response = array("status" => "error");
			if ( $estadoDau['est_id'] == 2 ) {
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
	case 'pacienteYaCategorizado':
		$parametros 		= $objUtil->getFormulario($_POST);
		try {
			$objCon -> beginTransaction();
			$estadoDau 		= $objDau->obtenerEstadoDauPaciente($objCon, $parametros['idDau']);
			if ( $estadoDau['est_id'] == 2 ) {
				$textoError = "Paciente ya se encuentra categorizado por otra persona. Se recargó nuevamente el Mapa de Piso para que visualice los cambios.";
				$response = array("textoError" => $textoError);
			}else if ( $estadoDau['est_id'] == 6 ) {
				$textoError = "Paciente ya se encuentra con estado aplicado de NULO. Se recargó nuevamente el Mapa de Piso para que visualice los cambios.";
				$response = array("textoError" => $textoError);
			}else if ( $estadoDau['est_id'] == 7) {
				$textoError = "Paciente ya se encuentra con estado aplicado de N.E.A. (Posiblemente por Otra Persona). Se recargó nuevamente el Mapa de Piso para que visualice los cambios.";
				$response = array("textoError" => $textoError);
			}else{
				$parametros['dau_id'] 				= $parametros['idDau'];
				$parametrosSelect['dau_id']   		= ' dau_id = "'.$parametros['idDau'].'" ';
				$rsPacientesDau                    	= $objSqlDinamico->generarSelect($objCon,'dau.dau' , $parametrosSelect, " order by dau_id asc");
				 $parametros['paciente_id']			= $rsPacientesDau[0]['id_paciente'];
				$parametros['origen']		   		= 3;
				$parametros['estadoEve']       		= 1;
				$parametros['usuarioEve'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['intCodigo']	   		= '';
				$parametros['estadoRCE']	   		= 1;
				$parametros['tipoAtencionPaciente'] = '';
				if ( $rsPacientesDau[0]["dau_atencion"] == 1 ) {
					$parametros['tipoAtencionPaciente'] = 'DA';
				}
				if ( $rsPacientesDau[0]["dau_atencion"] == 2 ) {
					$parametros['tipoAtencionPaciente'] = 'DP';
				}
				if ( $rsPacientesDau[0]["dau_atencion"] == 3 ) {
					$parametros['tipoAtencionPaciente'] = 'DG';
				}

				$parametrosSelect['dau_id']   		= ' dau_id = "'.$parametros['idDau'].'" ';
				$eve_id                    			= $objSqlDinamico->generarSelect($objCon,'rce.evento' , $parametrosSelect, " order by eveId asc");
				$parametros['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				if ( empty($eve_id[0]['dau_id']) || is_null($eve_id[0]['dau_id']) ) {
					$eve_id 						= $objDau -> crearEvento($objCon,$parametros);
					$parametros['evento_id'] 		= $eve_id;
					$rce_id 				 		= $objRegistroClinico -> insertaRCE($objCon,$parametros);
				} else {
					$parametros['evento_id'] 		= $eve_id[0]['eveId'];
					$rce_id 				 		= $objRegistroClinico->obtenerRCEIDSegunEvento($objCon, $parametros);
				}
				$parametrosSignosVitales						= array();
				$parametrosSignosVitales['idPaciente']			= 'null';
				$parametrosSignosVitales['frm_svital_pulso']	= 'null';
				$parametrosSignosVitales['frm_svital_psis']		= 'null';
				$parametrosSignosVitales['frm_svital_pdias']	= 'null';
				$parametrosSignosVitales['frm_svital_temp']		= '';
				$parametrosSignosVitales['frm_svital_satu']		= 'null';
				$parametrosSignosVitales['frm_svital_fr']		= 'null';
				$parametrosSignosVitales['frm_svital_fc']		= 'null';
				$parametrosSignosVitales['frm_svital_glas']		= 'null';
				$parametrosSignosVitales['frm_svital_eva']		= 'null';
				$parametrosSignosVitales['usuario']				= 'null';
				$parametrosSignosVitales['frm_svital_peso']		= 'null';
				$parametrosSignosVitales['frm_svital_talla']	= 'null';
				$banderaSignosVitales                           = false;
				$subparametrosBitacoraIndicaciones['BITdescripcionTitulo']	= "<b>SIGNOS VITALES</b> : ";

				if( ! isset($parametros['dau_cat_4_fr']) ) {
					$parametros['dau_cat_4_fr'] 		= '';
				} else {
					$parametrosSignosVitales['frm_svital_fr'] = $parametros['dau_cat_4_fr'];
					$banderaSignosVitales                     = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FR (".$parametros['dau_cat_4_fr'].") ";
				}

				if ( ! isset($parametros['dau_cat_2_eva']) ) {
					$parametros['dau_cat_2_eva'] 		= 'null';
				} else {
					$parametrosSignosVitales['frm_svital_eva'] = $parametros['dau_cat_2_eva'];
					$banderaSignosVitales                      = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " EVA (".$parametros['dau_cat_2_eva'].") ";
				}

				if( ! isset($parametros['dau_cat_3_resp']) ) {
					$parametros['dau_cat_3_resp'] 	= 'null';
				} else {
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " RESP (".$parametros['dau_cat_3_resp'].") ";
				}

				if ( $parametros['dau_cat_4_satu'] != '' ) {
					$parametros['dau_cat_4_satu']               = $parametros['dau_cat_4_satu'];
					$parametrosSignosVitales['frm_svital_satu'] = $parametros['dau_cat_4_satu'];
					$banderaSignosVitales                       = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " SATURACION (".$parametros['dau_cat_4_satu'].") ";
				} else {
					$parametros['dau_cat_4_satu'] = "null";
				}

				if ( $parametros['dau_cat_4_fr'] != '' ) {
					$parametros['dau_cat_4_fr']				  = $parametros['dau_cat_4_fr'];
					$parametrosSignosVitales['frm_svital_fr'] = $parametros['dau_cat_4_fr'];
					$banderaSignosVitales                     = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FR (".$parametros['dau_cat_4_fr'].") ";
				} else {
					$parametros['dau_cat_4_fr'] = "";
				}

				if ( $parametros['dau_cat_4_fc'] != '' ) {
					$parametros['dau_cat_4_fc'] = $parametros['dau_cat_4_fc'];
					$parametrosSignosVitales['frm_svital_fc']    = $parametros['dau_cat_4_fc'];
					$parametrosSignosVitales['frm_svital_pulso'] = $parametros['dau_cat_4_fc'];
					$banderaSignosVitales                        = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " FC (".$parametros['dau_cat_4_fc'].") ";
				} else {
					$parametros['dau_cat_4_fc'] = "null";
				}

				if ( $parametros['dau_cat_4_temp'] == "" ) {
					$parametros['dau_cat_4_temp'] 	= 'null';
				} else {
					$parametrosSignosVitales['frm_svital_temp'] = $parametros['dau_cat_4_temp'];
					$banderaSignosVitales                       = true;
					if(!$subparametrosBitacoraIndicaciones['BITdescripcion'] ==""){
						$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " , ";
					}
					$subparametrosBitacoraIndicaciones['BITdescripcion'] .= " TEMPERATURA (".$parametros['dau_cat_4_temp'].") ";
				}

				if ( $parametrosSignosVitales['frm_svital_pam'] == "" ) {
					$parametrosSignosVitales['frm_svital_pam'] = 0;
				}

				if ( ! isset($parametros['dau_cat_4_inmu']) ) {
					$parametros['dau_cat_4_inmu'] 	= 'null';
				}

				if ( ! isset($parametros['dau_cat_4_fiebre']) ) {
					$parametros['dau_cat_4_fiebre'] = 'null';
				}

				if ( ! isset($parametros['dau_cat_5_esi']) ) {
					$parametros['dau_cat_5_esi'] = 'null';
				}

				if ( ! isset($parametros['dau_cat_5_obs']) ) {
					$parametros['dau_cat_5_obs'] = 'null';
				}

				if ( $parametros['catesi'] == "C1" ) {
					$parametros['catesi'] = "ESI-1";
				}

				if ( $parametros['catesi'] == "C2" ) {
					$parametros['catesi'] = "ESI-2";
				}

				if ( $parametros['catesi'] == "C3" ) {
					$parametros['catesi'] = "ESI-3";
				}

				if ( $parametros['catesi'] == "C4" ) {
					$parametros['catesi'] = "ESI-4";
				}

				if ( $parametros['catesi'] == "C5" ) {
					$parametros['catesi'] = "ESI-5";
				}

				if ( $parametros['banderacat'] != 'DETALLE' ) {
			    	$parametros['est_id'] = 2;
			    }

				if ( $parametros['dau_cat_considerada'] != '' ) {
			    	$parametros['dau_cat_considerada'] 			= $parametros['dau_cat_considerada'];
			    } else {
			    	$parametros['dau_cat_considerada'] 			= '';
				}
				$parametros['dau_cat_usuario_inserta'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros["dau_viaje_epidemiologico"] 		= $objUtil->existe($parametros["frm_viajeEpidemiologico"]) ? $parametros["frm_viajeEpidemiologico"] : "N";
				$parametros["dau_pais_epidemiologia"] 			= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_paisEpidemiologia"] : NULL;
				$parametros["dau_observacion_epidemiologica"] 	= ($objUtil->existe($parametros["frm_viajeEpidemiologico"]) && $parametros["frm_viajeEpidemiologico"] === "S") ? $parametros["frm_observacionEpidemiologica"] : NULL;

				$parametros['dau_mov_descripcion'] 				= 'categorizacion';
				$parametros['dau_mov_tipo'] 					= "cat";
				$parametros['dau_mov_usuario'] 					= $_SESSION['MM_Username'.SessionName];
				$objDau->guardarMovimiento($objCon, $parametros);

				$parametros['dau_mov_descripcion'] 				.= ($objUtil->existe($parametros['dau_viaje_epidemiologico'])) ? " - ".$parametros['dau_viaje_epidemiologico'] : NULL;
				$parametros['dau_mov_descripcion'] 				.= ($objUtil->existe($parametros['dau_pais_epidemiologia'])) ? " - ".$parametros['dau_pais_epidemiologia'] : NULL;
				$parametros['dau_mov_descripcion'] 				.= ($objUtil->existe($parametros['dau_observacion_epidemiologica'])) ? " - ".$parametros['dau_observacion_epidemiologica'] : NULL;
				$objDau->guardarMovimiento($objCon, $parametros);
				if ( ! isset($parametros['dau_indiferenciado']) ) {
					$parametros['dau_indiferenciado'] 	= '';
				}
				$resp 		= $objCat -> asignarEsi($objCon, $parametros);
				if ( $parametros['inp_recat'] == '' ) {
					$resp2 	= $objCat -> updEstadoCat($objCon, $parametros);
				} else {
					$resp2 	= $objCat -> updEstado($objCon, $parametros);
				}

				if ( $banderaSignosVitales ) {
					$parametrosSignosVitales['rce_id'] 		= $rce_id;
					$parametrosSignosVitales['idPaciente'] 	= $rsPacientesDau[0]['id_paciente'];
					$parametrosSignosVitales['usuario']		= $parametros['dau_cat_usuario_inserta'];
					$objRce->registrarSVITAL($objCon,$parametrosSignosVitales);

					$parametrosMovimiento['dau_id'] 				= $parametros['dau_id'];
					$parametrosMovimiento['dau_mov_descripcion'] 	= 'registro signos vitales';
					$parametrosMovimiento['dau_mov_usuario'] 		= $_SESSION['MM_Username'.SessionName];
					$parametrosMovimiento['dau_mov_tipo'] 			= 'rsv';
					$objDau->guardarMovimiento($objCon, $parametrosMovimiento);
				}
			}

			$response = array("status" => "success", "textoError" => $textoError);
			$objCon -> commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon -> rollback();
			$response = array("status" => "error", "message" => $e -> getMessage());
			echo json_encode($response);
		}
	break;
	case 'obtenerPerfilUsuario' :
		$parametros['usuario']  	 			= $_SESSION['MM_Username'.SessionName];
		try	{
			$objCon->beginTransaction();
			// print('<pre>'); print_r($_SESSION); print('</pre>');
			$resultadoConsulta = $objDau->obtenerPerfilUsuario($objCon,$parametros);
			if($resultadoConsulta[0]['contadorPerfilEnfermero'] != ""){
				$parametrosColor['PROcodigo'] =  $_SESSION['MM_RUNUSU'.SessionName];
				$rsSelectProfesional = $objMenu_colores->SelectProfesional($objCon,$parametrosColor);
				if($rsSelectProfesional[0]['TIPROcodigo'] == 3 ){
					$resultadoConsulta[0]['contadorPerfilMatrona'] = 1;
				}
			}

			// if ( $resultadoConsulta[0]['contadorPerfilMedico'] > 0 ) {
			// 	$_SESSION['tipo_color'] = '1';
			// }	else if ( $resultadoConsulta[0]['contadorPerfilMatrona'] > 0 ) {
			// 	$_SESSION['tipo_color'] = '2';
			// }	else if ( $resultadoConsulta[0]['contadorPerfilTens'] > 0 ) {
			// 	$_SESSION['tipo_color'] = '3';
			// }	else if ( $resultadoConsulta[0]['contadorPerfilEnfermero'] > 0  ) {
			// 	$_SESSION['tipo_color'] = '4';
			// }	else if ( $resultadoConsulta[0]['contadorPerfilAdministrativo'] > 0  ) {
			// 	$_SESSION['tipo_color'] = '5';
			// }	else if ( $resultadoConsulta[0]['contadorPerfilFull'] > 0  ) {
			// 	$_SESSION['tipo_color'] = '6';
			// }	else{
			// 	$_SESSION['tipo_color'] = '0';
			// }
			// print('<pre>'); print_r($resultadoConsulta); print('</pre>');
			// print('<pre>'); print_r($_SESSION['tipo_color']); print('</pre>');
			if (isset($resultadoConsulta[0]['idusuario'])) {
				$response  = array("status" => "success",  "perfilUsuario" => $resultadoConsulta[0] );
			} else {
				$response  = array("status" => "errorUsuario");	
			}		
			$objCon->commit();		
			echo json_encode($response);
		} catch ( PDOException $e ) {
			$objCon->rollback();
			$response  = array("status" => "errorPerfil", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;


//////////////////////////////////////////////////////////////////////////////////////////






	case 'obtenerCategPaciente':

		require_once('../../../class/Categorizacion.class.php'); $objCat   = new Categorizacion;

		$objCon->db_connect();

		$parametros = $objUtil->getFormulario($_POST);

		$resp = $objCat->getDatosDauCat($objCon, $parametros);

		if ( $resp[0]['dau_categorizacion_actual'] ) {
			$response = array("status" => "success", "id" => $parametros['id_dau'], "categorizacion" => $resp[0]['cat_nombre_mostrar'], "cat_nivel" => $resp[0]['cat_nivel']);
		}
		else{
			$response = array("status" => "error", "id" => $parametros['id_dau'], 'message' => 'No se encontró una categorizacion asociada al paciente.');
		}

		echo json_encode($response);

	break;



	


	// case 'pacienteYaCategorizado':

	// 	require_once("../../../class/Dau.class.php"); $objDau = new Dau();

	// 	$objCon->db_connect();

	// 	$response   = array();

	// 	$parametros = $objUtil->getFormulario($_POST);

	// 	try {

	// 		$objCon -> beginTransaction();

	// 		$estadoDau = $objDau->obtenerEstadoDauPaciente($objCon, $parametros['idDau']);

	// 		$response = array("status" => "error");

	// 		if ( $estadoDau['est_id'] == 2 ) {

	// 			$response = array("status" => "success");

	// 		}

	// 		$objCon -> commit();

	// 		echo json_encode($response);

	// 	} catch (PDOException $e) {

	// 		$objCon -> rollback();

	// 		$response = array("status" => "error", "message" => $e -> getMessage());

	// 		echo json_encode($response);

	// 	}

	// break;

}



function insertarNumeroAtencionDau ( $objCon, $objDau, $objUtil, $tipoCategorizacion, $idDau ) {
	$parametros 						= array();
	$parametros['tipoCategorizacion'] 	= $tipoCategorizacion;
	$parametros['idDau'] 				= $idDau;
	$infoAtencionActual 				= $objDau->obtenerInfoNumeroAtencion($objCon, $parametros);
	$numeroAGuardar 					= $objUtil->evaluarNumeroAtencionDau($infoAtencionActual['numeroTope'], $infoAtencionActual['numeroActual']);
	$parametros['numeroAtencion'] 		= $infoAtencionActual['letraNumeroAtencion'].'-'.$numeroAGuardar;
	$parametros['numeroAGuardar'] 		= $numeroAGuardar;
	if ( $parametros['tipoCategorizacion'] == 'ESI-1' || $parametros['tipoCategorizacion'] == 'C1' ) {
		$parametros['numeroAtencion'] 	= 1;
	}
	$objDau->insertarNumeroAtencionDau($objCon, $parametros);
	$objDau->actualizarNumeroAtencionDau($objCon, $parametros);
}
?>