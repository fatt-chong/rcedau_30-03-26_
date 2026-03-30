<?php
session_start();

require_once('../../../class/Connection.class.php'); 		$objCon      		= new Connection;
require_once("../../../class/Util.class.php");       		$objUtil     		= new Util;
require_once("../../../class/Identificacion.class.php"); 	$objIdentificador 	= new Identificacion;
switch ( $_POST['accion'] ) {
	case "validarPermiso";
		$objCon->db_connect();
		$parametros['codigoBarra']   							= $_POST['codigoBarra'];
		$parametros['accessRequest'] 							= $_POST['accessRequest'];
		$parametros['verificacionConPistola']  					= 'verdadero';
		$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = 0;
		switch ( $parametros['accessRequest'] ) {
			case "btn_ind":
				$permisosIdentificacion=816;
			break;
			case "btn_iniciarAtencion":
				$permisosIdentificacion=817;
			break;
			case "btn_indicacionAplica":
				$permisosIdentificacion=818;
			break;
			case "btn_pyxis":
				$permisosIdentificacion=819;
			break;
			case "btn_alcoholemia":
				$permisosIdentificacion=724;
			break;
			case "btn_Admision":
				$permisosIdentificacion=837;
			break;
			case "btn_updateAdmision":
				$permisosIdentificacion=812;
			break;
			case "btn_cierreDau":
				$permisosIdentificacion=813;
			break;
			case "btn_cierreDauVer":
				$permisosIdentificacion=814;
			break;
			case "btn_registroMedico":
				$permisosIdentificacion=815;
			break;
			case "btn_categorizacionNormalEdad_1_y_2":
				$permisosIdentificacion=823;
			break;
			case "btn_categorizacionNormalEdad_3_y_4":
				$permisosIdentificacion=799;
			break;
			case "btn_categorizacionSDD":
				$permisosIdentificacion=824;
			break;
			case "btn_mapaPisoCama_a_Cama":
				$permisosIdentificacion=820;
			break;
			case "btn_mapaPiso_cat_a_Cama":
				$permisosIdentificacion=821;
			break;
			case "btn_mapaPiso_esp_a_Cama":
				$permisosIdentificacion=822;
			break;
			case "btn_hipotesis":
				$permisosIdentificacion=825;
			break;
			case "btn_registrarTiempo":
				$permisosIdentificacion=826;
			break;

			case "btn_indicarEgresoGine":
				$permisosIdentificacion=827;
			break;

			case "btn_indGINE":
				$permisosIdentificacion=827;
			break;

			case "btn_hipotesisGine":
				$permisosIdentificacion=825;
			break;

			case "btn_iniciarAtencionGine":
				$permisosIdentificacion=826;
			break;

			case "btn_indicacionAplicaGine":
				$permisosIdentificacion=827;
			break;

			case "btn_pyxisGine":
				$permisosIdentificacion=819;
			break;

			case "btn_categorizacionSDD_Gine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPisoCama_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPiso_cat_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPiso_esp_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_detalleDAUNEA":
				$permisosIdentificacion=858;
			break;
			case "btn_rce_signosVitales":
				$permisosIdentificacion=859;
			break;

			case "btn_guardar_indicaciones":
				$permisosIdentificacion=860;
			break;

			case "btn_rce_antecedentes":
				$permisosIdentificacion=862;
			break;

			case "btn_rce_guardar":
				$permisosIdentificacion=863;
			break;

			case "btn_aplicar_indicaciones":
				$permisosIdentificacion=864;
			break;

			case "btn_eliminar_indicaciones":
				$permisosIdentificacion=865;
			break;

			case "btn_anular_indicaciones":
				$permisosIdentificacion=866;
			break;

			case "btn_tomaMuestra":
				$permisosIdentificacion=984;
			break;

			case "btn_inicioIndicacion":
				$permisosIdentificacion=985;
			break;

			case "btn_pacienteComplejo":
				$permisosIdentificacion=986;
			break;

			case "btn_cambioTurno":
				$permisosIdentificacion=993;
			break;

			case "seguimientoPaciente":
				$permisosIdentificacion=1640;
			break;
		}
		$parametros['permiso'] = $permisosIdentificacion;
		try {
			$objCon->beginTransaction();
			$datos     = $objIdentificador->identicarUsuario($objCon,$parametros);
			if ( isset($datos[0]['idusuario']) ) {
				$_SESSION['identificacion']           = 'true';
				$_SESSION['usuarioActivo']['rut']     = $datos[0]['rutusuario'];
				$_SESSION['usuarioActivo']['usuario'] = $datos[0]['idusuario'];
				$_SESSION['usuarioActivo']['nombre']  = $datos[0]['nombreusuario'];
				if ( $permisosIdentificacion === 993 ) {
					array_push($_SESSION['usuarioActivo']['cambioTurno']['rut'], $_SESSION['usuarioActivo']['rut']);
				}
				if ( $permisosIdentificacion === 820 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if ( isset($resultadoConsulta[0]['idusuario']) ) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if ( $permisosIdentificacion === 821 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if( $permisosIdentificacion === 822 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if( $permisosIdentificacion === 823 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];

					}
				}
				$response  = array("status" => "success", "nombre" => $_SESSION['usuarioActivo']['nombre'], "perfilUsuario" => $_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario']  );
			} else {
				$response  = array("status" => "errorPermiso");
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "variableSession";
		$_SESSION['identificacion'] = 'false';
		unset($_SESSION['usuarioActivo']);
		$response  = array("status" => "success", "usuarioLogueado" => $_SESSION['MM_UsernameName'.SessionName]);
		echo json_encode($response);
	break;
	case "verificarUsuario";
		$objCon->db_connect();
		$parametros['codigoBarra'] = $_POST['codigoBarra'];
		$datos = $objIdentificador->verificarExistenciaUsuario($objCon,$parametros);
		echo count($datos);
	break;
	case "verificarUsuarioConPistola":
		$objCon->db_connect();
		$parametros['codigoBarra'] = $_POST['codigoBarra'];
		try {
			$objCon->beginTransaction();
			$datos  = $objIdentificador->verificarExistenciaUsuario($objCon,$parametros);
			if ( isset($datos[0]['idusuario']) ) {
				$_SESSION['identificacion']           = 'true';
				$_SESSION['usuarioActivo']['rut']     = $datos[0]['rutusuario'];
				$_SESSION['usuarioActivo']['usuario'] = $datos[0]['idusuario'];
				$_SESSION['usuarioActivo']['nombre']  = $datos[0]['nombreusuario'];
				$response  = array("status" => "success", "nombre" => $_SESSION['usuarioActivo']['nombre'] , "perfilUsuario" => '');
			} else {
				$response  = array("status" => "errorUsuario");
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "verificarUsuarioSinPistola":
		$objCon->db_connect();
		$parametros['run'] = $_POST['run'];
		$parametros['password'] = $_POST['password'];
		try {
			$objCon->beginTransaction();
			$datos  = $objIdentificador->verificarExistenciaUsuarioSinPistola($objCon,$parametros);
			if ( isset($datos[0]['idusuario']) ) {
				$_SESSION['identificacion']           = 'true';
				$_SESSION['usuarioActivo']['rut']     = $datos[0]['rutusuario'];
				$_SESSION['usuarioActivo']['usuario'] = $datos[0]['idusuario'];
				$_SESSION['usuarioActivo']['nombre']  = $datos[0]['nombreusuario'];
				$response  = array("status" => "success", "nombre" => $_SESSION['usuarioActivo']['nombre'], "perfilUsuario" => '');
			} else {
				$response  = array("status" => "errorUsuario");
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "destruirSesionPistola":
		$_SESSION['identificacion'] = true;
		unset($_SESSION['usuarioActivo']);
	break;
	case "validarAccion":
		$objCon->db_connect();
		$parametros['accessRequest'] = $_POST['accessRequest'];
		switch ( $parametros['accessRequest'] ) {
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
		$parametros['userName'] = $objUtil->usuarioActivo();
		try {
			$objCon->beginTransaction();
			$datos     = $objIdentificador->validaPermisoUsuario($objCon,$parametros);
			if ( count($datos) > 0 ) {
				$response  = array("status" => "success");
			} else {
				$response  = array("status" => "errorDePermiso");
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;
	case "validarPermisoSinPistola":
		require_once("../../../class/Identificacion.class.php"); $objIdentificador = new Identificacion;
		$objCon->db_connect();
		$parametros['run'] 										= $_POST['run'];
		$parametros['password'] 								= $_POST['password'];
		$parametros['accessRequest'] 							= $_POST['accessRequest'];
		$parametros['verificacionConPistola'] 					= 'falso';
		$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = 0;

		switch ( $parametros['accessRequest'] ) {
			case "btn_ind":
				$permisosIdentificacion=816;
			break;

			case "btn_iniciarAtencion":
				$permisosIdentificacion=817;
			break;

			case "btn_indicacionAplica":
				$permisosIdentificacion=818;
			break;

			case "btn_pyxis":
				$permisosIdentificacion=819;
			break;

			case "btn_alcoholemia":
				$permisosIdentificacion=724;
			break;

			case "btn_Admision":
				$permisosIdentificacion=837;
			break;

			case "btn_updateAdmision":
				$permisosIdentificacion=812;
			break;

			case "btn_cierreDau":
				$permisosIdentificacion=813;
			break;

			case "btn_cierreDauVer":
				$permisosIdentificacion=814;
			break;

			case "btn_registroMedico":
				$permisosIdentificacion=815;
			break;

			case "btn_categorizacionNormalEdad_1_y_2":
				$permisosIdentificacion=823;
			break;

			case "btn_categorizacionNormalEdad_3_y_4":
				$permisosIdentificacion=799;
			break;

			case "btn_categorizacionSDD":
				$permisosIdentificacion=824;
			break;

			case "btn_mapaPisoCama_a_Cama":
				$permisosIdentificacion=820;
			break;

			case "btn_mapaPiso_cat_a_Cama":
				$permisosIdentificacion=821;
			break;

			case "btn_mapaPiso_esp_a_Cama":
				$permisosIdentificacion=822;
			break;

			case "btn_hipotesis":
				$permisosIdentificacion=825;
			break;

			case "btn_registrarTiempo":
				$permisosIdentificacion=826;
			break;

			case "btn_indicarEgresoGine":
				$permisosIdentificacion=827;
			break;

			case "btn_indGINE":
				$permisosIdentificacion=827;
			break;

			case "btn_hipotesisGine":
				$permisosIdentificacion=825;
			break;

			case "btn_iniciarAtencionGine":
				$permisosIdentificacion=826;
			break;

			case "btn_indicacionAplicaGine":
				$permisosIdentificacion=827;
			break;

			case "btn_pyxisGine":
				$permisosIdentificacion=819;
			break;

			case "btn_categorizacionSDD_Gine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPisoCama_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPiso_cat_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_mapaPiso_esp_a_CamaGine":
				$permisosIdentificacion=850;
			break;

			case "btn_detalleDAUNEA":
				$permisosIdentificacion=858;
			break;
			case "btn_rce_signosVitales":
				$permisosIdentificacion=859;
			break;

			case "btn_guardar_indicaciones":
				$permisosIdentificacion=860;
			break;

			case "btn_rce_antecedentes":
				$permisosIdentificacion=862;
			break;

			case "btn_rce_guardar":
				$permisosIdentificacion=863;
			break;

			case "btn_aplicar_indicaciones":
				$permisosIdentificacion=864;
			break;

			case "btn_eliminar_indicaciones":
				$permisosIdentificacion=865;
			break;

			case "btn_anular_indicaciones":
				$permisosIdentificacion=866;
			break;

			case "btn_tomaMuestra":
				$permisosIdentificacion=984;
			break;

			case "btn_inicioIndicacion":
				$permisosIdentificacion=985;
			break;

			case "btn_pacienteComplejo":
				$permisosIdentificacion=986;
			break;

			case "btn_cambioTurno":
				$permisosIdentificacion=993;
			break;

			case "seguimientoPaciente":
				$permisosIdentificacion=1640;
			break;
		}
		$parametros['permiso'] = $permisosIdentificacion;
		try{
			$objCon->beginTransaction();
			$datos  = $objIdentificador->identicarUsuarioSinPistola($objCon,$parametros);
			if ( isset($datos[0]['idusuario']) ) {
				$_SESSION['identificacion']           = 'true';
				$_SESSION['usuarioActivo']['rut']     = $datos[0]['rutusuario'];
				$_SESSION['usuarioActivo']['usuario'] = $datos[0]['idusuario'];
				$_SESSION['usuarioActivo']['nombre']  = $datos[0]['nombreusuario'];
				if ( $permisosIdentificacion === 993 ) {
					array_push($_SESSION['usuarioActivo']['cambioTurno']['rut'], $_SESSION['usuarioActivo']['rut']);
				}
				if ( $permisosIdentificacion === 820 ) { //btn_mapaPisoCama_a_Cama
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if ( $permisosIdentificacion === 821 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if ( $permisosIdentificacion === 822 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				if ( $permisosIdentificacion === 823 ) {
					$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon, $parametros);
					if (isset($resultadoConsulta[0]['idusuario'])) {
						$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];
					}
				}
				$response  = array("status" => "success", "nombre" => $_SESSION['usuarioActivo']['nombre'], "perfilUsuario" => $_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario']);
			} else {
				$response  = array("status" => "errorPermiso");
			}
			$objCon->commit();
			echo json_encode($response);
		} catch (PDOException $e ) {
			$objCon->rollback();
			$response  = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($response);
		}
	break;



	case 'obtenerPerfilUsuario' :

		require_once("../../../class/Identificacion.class.php"); $objIdentificador = new Identificacion;

		$objCon->db_connect();

		$objCon->setDB('acceso');

		$parametros['run']  	 				= $_POST['run'];

		$parametros['password']  				= $_POST['password'];

		$parametros['codigoBarra']				= $_POST['codigoBarra'];

		$parametros['verificacionConPistola']  	= $_POST['verificacionConPistola'];

		$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = 0;

		try	{

			$objCon->beginTransaction();

			$resultadoConsulta = $objIdentificador->obtenerPerfilUsuario($objCon,$parametros);

			if (isset($resultadoConsulta[0]['idusuario'])) {

				$_SESSION['identificacion']           					= 'true';

				$_SESSION['usuarioActivo']['rut']     					= $resultadoConsulta[0]['rutusuario'];

				$_SESSION['usuarioActivo']['usuario'] 					= $resultadoConsulta[0]['idusuario'];

				$_SESSION['usuarioActivo']['nombre']  					= $resultadoConsulta[0]['nombreusuario'];

				$_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] = $resultadoConsulta[0];


				$response  = array("status" => "success", "nombre" => $_SESSION['usuarioActivo']['nombre'], "perfilUsuario" => $_SESSION['usuarioActivo']['mapaPiso']['perfilUsuario'] );

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



	case "verificarUsuarioConPistola(TurnoRCUrgencia)":

		$objCon->db_connect();

		$parametros['codigoBarra'] = $_POST['codigoBarra'];

		try {

			$objCon->beginTransaction();

			$datos  = $objIdentificador->verificarExistenciaUsuario($objCon,$parametros);

			if ( isset($datos[0]['idusuario']) ) {

				$response  = array("status" => "successTurnoCRUrgencia", "nombre" => $datos[0]['nombreusuario'], "id" => $datos[0]['rutusuario']);

			} else {

				$response  = array("status" => "errorUsuario");

			}

			$objCon->commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;



	case "verificarUsuarioSinPistola(TurnoRCUrgencia)":

		$objCon->db_connect();

		$parametros['run'] = $_POST['run'];
		$parametros['password'] = $_POST['password'];

		try {

			$objCon->beginTransaction();

			$datos  = $objIdentificador->verificarExistenciaUsuarioSinPistola($objCon,$parametros);

			if ( isset($datos[0]['idusuario']) ) {

				$response  = array("status" => "successTurnoCRUrgencia", "nombre" => $datos[0]['nombreusuario'], "id" => $datos[0]['rutusuario']);

			} else {

				$response  = array("status" => "errorUsuario");

			}

			$objCon->commit();

			echo json_encode($response);

		} catch (PDOException $e) {

			$objCon->rollback();

			$response  = array("status" => "error", "message" => $e->getMessage());

			echo json_encode($response);

		}

	break;
}
?>
