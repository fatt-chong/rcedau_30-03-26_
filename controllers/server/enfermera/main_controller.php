<?php
session_start();
error_reporting(0);
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 						 	$objCon      								= new Connection;  $objCon->db_connect();
require_once("../../../class/Util.class.php");       						 	$objUtil     								= new Util;
require_once('../../../class/HojaEnfermeria.class.php');        			 	$objHoja_enfermeria    						= new Hoja_enfermeria;
require_once('../../../class/Cabecera_indicaciones_enfermeria.class.php'); 		$objCabecera_indicaciones_enfermeria   		= new Cabecera_indicaciones_enfermeria;
require_once('../../../class/Indicaciones_enfermeria.class.php');        	 	$objIndicaciones_enfermeria   				= new Indicaciones_enfermeria;
require_once('../../../class/Trazabilidad_indicaciones_enfermeria.class.php');  $objTrazabilidad_indicaciones_enfermeria   	= new Trazabilidad_indicaciones_enfermeria;
require_once('../../../class/Formulario_1.class.php');  						$objFormulario_1   							= new Formulario_1;
require_once('../../../class/Formulario_2.class.php');  						$objFormulario_2   							= new Formulario_2;
require_once('../../../class/Formulario_2_Detalle.class.php');  				$objFormulario_2_Detalle   					= new Formulario_2_Detalle;
require_once('../../../class/Formulario_3.class.php');  						$objFormulario_3   							= new Formulario_3;
require_once('../../../class/Formulario_3_Dosis.class.php');  					$objFormulario_3_Dosis   					= new Formulario_3_Dosis;
require_once('../../../class/FormPacienteGes.class.php');  					$objFormPacienteGes   						= new FormPacienteGes;
require_once('../../../class/movimientosFormularios.class.php');  				$objmovimientosFormularios   				= new movimientosFormularios;
require_once('../../../class/Pizarra.class.php');  								$objPizarra   								= new Pizarra;


switch ($_POST['accion']) {
	case 'EliminarPizarra':
	try {

			$parametros 					  = $objUtil->getFormulario($_POST);
			$objCon->beginTransaction();

			
			$objPizarra->DeleteByPizarraId($objCon, $parametros['id_pizarra']);

			$objCon->commit();
			$respuesta = array("status" => "success");
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'buscarCie10GesPacienteActivo':
	try {
			$objCon->beginTransaction();

			$parametros 					  = $objUtil->getFormulario($_POST);
			$horarioServidor            	  = $objUtil->getHorarioServidor($objCon);
			$parametrosGes['cie10_Codigo']    = $parametros['cie10_Codigo'];
      		$parametrosGes['PACGESpaciente']  = $parametros['idPaciente'];
      		$rsSelectFormPacienteGes          = $objFormPacienteGes->SelectFormPacienteGes($objCon,$parametrosGes);
      		$GesActivo 						  = 'N';
      		if(count($rsSelectFormPacienteGes) > 0 ){
      			$GesActivo = 'S';
      			if($parametros['dau_id'] == $rsSelectFormPacienteGes[0]['dau_id']){
      				$GesActivo = 'N';
      			}
      		}
			$objCon->commit();
			$respuesta = array("status" => "success", "GesActivo" => $GesActivo);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'guardarFormulario3DosisController':
		try {
			$objCon->beginTransaction();
			$horarioServidor            		= $objUtil->getHorarioServidor($objCon);
			$parametros 						= $objUtil->getFormulario($_POST);
			$parametros['estado'] 				= 1;
			$parametros['fecha'] 				= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']		= 1;
			$parametros['hora'] 				= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 			= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_fecha']   	= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			$parametros['creado_usuario'] 	  	= $_SESSION['MM_Username'.SessionName];
			$checkboxes = [
			    'animal_provocado',
			    'animal_no_provocado',
			    'ubicable',
			    'no_ubicable'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if($parametros['id_formulario'] > 0){
				$parametros['modificado_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$objFormulario_3->UpdateFormulario_3($objCon, $parametros,$parametros['id_formulario']);
				$parametrosMov['tipo_accion'] 			= "Actualización de Protocolo Vacunación Antirrábica";
			}else{
				$parametros['creado_usuario'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['id_formulario'] 			= $objFormulario_3->InsertFormulario_3($objCon, $parametros);
				$parametrosMov['tipo_accion'] 			= "Ingreso de Protocolo Vacunación Antirrábica";
			}
			$parametrosMov['formulario_nombre'] = "Protocolo Vacunación Antirrábica";
			$parametrosMov['formulario_id'] 	= $parametros['id_formulario'] ;
			$parametrosMov['dau_id'] 			= $parametros['dau_id'];
			$parametrosMov['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosMov['campos_afectados'] 	= addslashes(json_encode($parametros, JSON_UNESCAPED_UNICODE));
			$parametrosMov['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$objmovimientosFormularios->InsertmovimientosFormularios($objCon, $parametrosMov);
			
			$json = $_POST['nuevosCampos'] ?? '[]';
			print('<pre>'); print_r($json); print('</pre>');
			$nuevosCampos = json_decode($json, true);
			foreach ($nuevosCampos as $fila) {
		        // 2) Tomar campos del objeto (con valores por defecto)
		        $numeroDosis     = isset($fila['numeroDosis']) ? trim($fila['numeroDosis']) : null;
		        $fechaAplicacion = isset($fila['fechaAplicacion']) ? trim($fila['fechaAplicacion']) : null;
		        $citacionVacuna  = isset($fila['citacionVacuna']) ? trim($fila['citacionVacuna']) : null;

		        // 3) Normalizar fecha a Y-m-d
		        $fechaYMD = $objUtil->cambiarFormatoFecha($fechaAplicacion);

		        // (Opcional) Si quieres guardar solo número de dosis, puedes limpiar "1°" a "1":
		        // $numeroDosisSoloNumero = preg_replace('/\D+/', '', $numeroDosis);

		        // 4) Construir params para Insert
		        $paramDosis = [
		            'formulario_3_id'  => $parametros['id_formulario'],
		            'numero_dosis'     => $numeroDosis,      // o $numeroDosisSoloNumero
		            'fecha_aplicacion' => $fechaYMD,         // null si inválida
		            'citacion_vacuna'  => $citacionVacuna,
		            'creado_usuario'   => $_SESSION['MM_Username'.SessionName],
		            'creado_en'        => date("Y-m-d H:i:s")
		        ];

		        // 5) Insertar
		        $objFormulario_3_Dosis->InsertDosis($objCon, $paramDosis);
		    }


			$nuevosCampos = json_decode($_POST['nuevosCampos'], true);
		    $dosis = [
			    'numeroDosis'       => $nuevosCampos[0],
			    'fechaAplicacion'   => $nuevosCampos[1],
			    'citacionVacuna'    => $nuevosCampos[2],
			];
		    $paramDosis = [
			    'formulario_3_id'        => $parametros['id_formulario'],
			    'numero_dosis'           => $dosis['numeroDosis'],
			    'fecha_aplicacion'       => $dosis['fechaAplicacion'],
			    'citacion_vacuna'        => $dosis['citacionVacuna'],
			    'creado_usuario'         => $_SESSION['MM_Username'.SessionName],
			    'creado_en'              => date("Y-m-d H:i:s")
			];

			$objFormulario_3_Dosis->InsertDosis($objCon, $paramDosis);

			$objCon->commit();
			$respuesta = array("status" => "success", "idFormulario" => $parametros['id_formulario']);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'guardarFormulario3Controller':
		try {
			$objCon->beginTransaction();
			$horarioServidor            		= $objUtil->getHorarioServidor($objCon);
			$parametros 						= $objUtil->getFormulario($_POST);
			$parametros['estado'] 				= 1;
			$parametros['fecha'] 				= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']		= 1;
			$parametros['hora'] 				= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 			= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_fecha']   	= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			$parametros['creado_usuario'] 	  	= $_SESSION['MM_Username'.SessionName];
			$checkboxes = [
			    'animal_provocado',
			    'animal_no_provocado',
			    'ubicable',
			    'no_ubicable'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if($parametros['id_formulario'] > 0){
				$parametros['modificado_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$objFormulario_3->UpdateFormulario_3($objCon, $parametros,$parametros['id_formulario']);
				$parametrosMov['tipo_accion'] 			= "Actualización de Protocolo Vacunación Antirrábica";
			}else{
				$parametros['creado_usuario'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['id_formulario'] 			= $objFormulario_3->InsertFormulario_3($objCon, $parametros);
				$parametrosMov['tipo_accion'] 			= "Ingreso de Protocolo Vacunación Antirrábica";
			}
			$parametrosMov['formulario_nombre'] = "Protocolo Vacunación Antirrábica";
			$parametrosMov['formulario_id'] 	= $parametros['id_formulario'] ;
			$parametrosMov['dau_id'] 			= $parametros['dau_id'];
			$parametrosMov['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosMov['campos_afectados'] 	= addslashes(json_encode($parametros, JSON_UNESCAPED_UNICODE));
			$parametrosMov['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$objmovimientosFormularios->InsertmovimientosFormularios($objCon, $parametrosMov);

			$objFormulario_3_Dosis->DeleteByFormulario3Id($objCon, $parametros['id_formulario']);
			$json = $_POST['nuevosCampos'] ?? '[]';
			$nuevosCampos = json_decode($json, true);
			foreach ($nuevosCampos as $fila) {
		        $numeroDosis     = isset($fila['numeroDosis']) ? trim($fila['numeroDosis']) : null;
		        $fechaAplicacion = isset($fila['fechaAplicacion']) ? trim($fila['fechaAplicacion']) : null;
		        $citacionVacuna  = isset($fila['citacionVacuna']) ? trim($fila['citacionVacuna']) : null;
		        $fechaYMD = $objUtil->cambiarFormatoFecha($fechaAplicacion);
		        $paramDosis = [
		            'formulario_3_id'  => $parametros['id_formulario'],
		            'numero_dosis'     => $numeroDosis,      // o $numeroDosisSoloNumero
		            'fecha_aplicacion' => $fechaYMD,         // null si inválida
		            'citacion_vacuna'  => $citacionVacuna,
		            'creado_usuario'   => $_SESSION['MM_Username'.SessionName],
		            'creado_en'        => date("Y-m-d H:i:s")
		        ];
		        $objFormulario_3_Dosis->InsertDosis($objCon, $paramDosis);
		    }

			$objCon->commit();
			$respuesta = array("status" => "success", "idFormulario" => $parametros['id_formulario']);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'guardarFormulario2DetalleController':
		try {
			$objCon->beginTransaction();
			$horarioServidor            		= $objUtil->getHorarioServidor($objCon);
			$parametros 						= $objUtil->getFormulario($_POST);
			$parametros['estado'] 				= 1;
			$parametros['fecha'] 				= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']		= 1;
			$parametros['hora'] 				= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 			= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_fecha']   	= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			$parametros['creado_usuario'] 	  	= $_SESSION['MM_Username'.SessionName];
			$checkboxes = [
			    'confirmacion_diagnostico',
			    'paciente_tratamiento'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if($parametros['id_formulario'] > 0){
				$parametros['modificado_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$objFormulario_2->UpdateFormulario_2($objCon, $parametros,$parametros['id_formulario']);
				$parametrosMov['tipo_accion'] 			= "Actualizacion de Ficha de notificacion intentos de suicidio";
			}else{
				$parametros['creado_usuario'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['id_formulario'] 			= $objFormulario_2->InsertFormulario_2($objCon, $parametros);
				$parametrosMov['tipo_accion'] 			= "Ingreso de Ficha de notificacion intentos de suicidio";
			}
			$parametrosMov['formulario_nombre'] = "Ficha de notificacion intentos de suicidio";
			$parametrosMov['formulario_id'] 	= $parametros['id_formulario'] ;
			$parametrosMov['dau_id'] 			= $parametros['dau_id'];
			$parametrosMov['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosMov['campos_afectados'] 	= addslashes(json_encode($parametros, JSON_UNESCAPED_UNICODE));
			$parametrosMov['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$objmovimientosFormularios->InsertmovimientosFormularios($objCon, $parametrosMov);

			$nuevosCampos = json_decode($_POST['nuevosCampos'], true);
		    $registro = [
			    'fechaHora'       => $nuevosCampos[0],
			    'estado'          => $nuevosCampos[1],
			    'extSuperior'     => $nuevosCampos[2],
			    'extInferior'     => $nuevosCampos[3],
			    'hidratacion'     => $nuevosCampos[4],
			    'eliminacion'     => $nuevosCampos[5],
			];
		    $paramDetalle = [
			    'formulario_2_id'        => $parametros['id_formulario'],
			    'fecha'                  => $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'], // formato: 'Y-m-d H:i:s'
			    'estado_paciente'        => $registro['estado'],
			    'extremidad_superior'    => $registro['extSuperior'],
			    'extremidad_inferior'    => $registro['extInferior'],
			    'hidratacion'            => $registro['hidratacion'],
			    'eliminacion'            => $registro['eliminacion'],
			    'usuario'            	 => $_SESSION['MM_Username'.SessionName],
			    'creado_en'              => date("Y-m-d H:i:s")
			];

			$objFormulario_2_Detalle->InsertDetalle($objCon, $paramDetalle);

			$objCon->commit();
			$respuesta = array("status" => "success", "idHojaEnfermeria" => $parametros['id_formulario']);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'guardarFormulario2Controller':
		try {
			$objCon->beginTransaction();
			$horarioServidor            		= $objUtil->getHorarioServidor($objCon);
			$parametros 						= $objUtil->getFormulario($_POST);
			$parametros['estado'] 				= 1;
			$parametros['fecha'] 				= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']		= 1;
			$parametros['hora'] 				= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 			= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_fecha']   	= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			$parametros['creado_usuario'] 	  	= $_SESSION['MM_Username'.SessionName];
			$checkboxes = [
			    'confirmacion_diagnostico',
			    'paciente_tratamiento'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if($parametros['id_formulario'] > 0){
				$parametros['modificado_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$objFormulario_2->UpdateFormulario_2($objCon, $parametros,$parametros['id_formulario']);
				$parametrosMov['tipo_accion'] 			= "Actualizacion de Ficha de notificacion intentos de suicidio";
			}else{
				$parametros['creado_usuario'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['id_formulario'] 			= $objFormulario_2->InsertFormulario_2($objCon, $parametros);
				$parametrosMov['tipo_accion'] 			= "Ingreso de Ficha de notificacion intentos de suicidio";
			}
			$parametrosMov['formulario_nombre'] = "Ficha de notificacion intentos de suicidio";
			$parametrosMov['formulario_id'] 	= $parametros['id_formulario'] ;
			$parametrosMov['dau_id'] 			= $parametros['dau_id'];
			$parametrosMov['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosMov['campos_afectados'] 	= addslashes(json_encode($parametros, JSON_UNESCAPED_UNICODE));
			$parametrosMov['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$objmovimientosFormularios->InsertmovimientosFormularios($objCon, $parametrosMov);

			$objCon->commit();
			$respuesta = array("status" => "success", "idHojaEnfermeria" => $parametros['id_formulario']);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'guardarFormularioController':
		try {
			$objCon->beginTransaction();
			$horarioServidor            		= $objUtil->getHorarioServidor($objCon);
			$parametros 						= $objUtil->getFormulario($_POST);
			$parametros['estado'] 				= 1;
			$parametros['fecha'] 				= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']		= 1;
			$parametros['hora'] 				= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 			= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_fecha']   	= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$parametros['modificado_usuario'] 	= $_SESSION['MM_Username'.SessionName];
			$parametros['creado_usuario'] 	  	= $_SESSION['MM_Username'.SessionName];
			$checkboxes = [
			    'confirmacion_diagnostico',
			    'paciente_tratamiento'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if($parametros['id_formulario'] > 0){
				$parametros['modificado_usuario'] 		= $_SESSION['MM_Username'.SessionName];
				$objFormulario_1->UpdateFormulario_1($objCon, $parametros,$parametros['id_formulario']);
				$parametrosMov['tipo_accion'] 			= "Actualizacion de Formulario de constancia información al paciente GES";

			}else{
				$parametros['creado_usuario'] 			= $_SESSION['MM_Username'.SessionName];
				$parametros['id_formulario'] 			= $objFormulario_1->InsertFormulario_1($objCon, $parametros);
				$parametrosMov['tipo_accion'] 			= "Ingreso de Formulario de constancia información al paciente GES";

			}
			$parametrosMov['formulario_nombre'] = "Formulario de constancia información al paciente GES";
			$parametrosMov['formulario_id'] 	= $parametros['id_formulario'] ;
			$parametrosMov['dau_id'] 			= $parametros['dau_id'];
			$parametrosMov['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametrosMov['campos_afectados'] 	= addslashes(json_encode($parametros, JSON_UNESCAPED_UNICODE));
			$parametrosMov['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
			$objmovimientosFormularios->InsertmovimientosFormularios($objCon, $parametrosMov);

			$parametrosGes = [
				'PACGESfecha' => $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'],
				'PACGESpaciente' => $parametros['id_paciente'],
				'dau_id' => $parametros['dau_id'],
				'PACGESdiagGes' => $parametros['cie10'],
				'PACGESconfDiagn' => $parametros['confirmacion_diagnostico'],
				'PACGEStratamiento' => $parametros['paciente_tratamiento'],
				'PACGESnomApoderado' => $parametros['nombre_representante'],
				'PACGESrunApoderado' => $parametros['rut_representante'],
				'PACGESmailApoderado' => $parametros['email_representante'],
				'PACGESfonoApoderado' => $parametros['telefono_representante'],
				'cie10_Codigo' => $parametros['cie10_Codigo'],
				'direccion_paciente' => $parametros['direccion_paciente'],
				'telefono_fijo' => $parametros['telefono_fijo'],
				'telefono_celular' => $parametros['telefono_celular'],
				'email' => $parametros['email'],
				'PACGESprofesional' => $parametros['rut_medico_hidden'],
				'celular_representante' => $parametros['celular_representante'],
				'tipo_atencion' => "Urgencia",
				'teleconsulta_conocimiento_nopac' => isset($parametros['teleconsulta_conocimiento_nopac']) ? $parametros['teleconsulta_conocimiento_nopac'] : '',
				'creado_usuario' => $_SESSION['MM_Username'.SessionName]
			];
			$rsFormGes = $objFormPacienteGes->SelectByRceFormPacienteGes($objCon, $parametros['dau_id']);
			
			if(count($rsFormGes) > 0){
				$parametrosGes['modificado_fecha'] = $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];
				$parametrosGes['modificado_usuario'] = $_SESSION['MM_Username'.SessionName];
				$objFormPacienteGes->UpdateFormPacienteGes($objCon, $parametrosGes, $rsFormGes[0]['PACGESid']);
			} else {
				$rsFormGes[0]['PACGESid'] = $objFormPacienteGes->InsertFormPacienteGes($objCon, $parametrosGes);
			}

			$objCon->commit();
			$respuesta = array("status" => "success", "PACGESid" => $rsFormGes[0]['PACGESid']);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'ingresarHojaHospitalizacion':
		try {
			$objCon->beginTransaction();

			$parametros 	= $objUtil->getFormulario($_POST);
			$entrega_fecha 	= $parametros['entrega_fecha'];
			$datetime 		= new DateTime($entrega_fecha);
			// Separar en variables
			$parametros['fecha_entrega'] = $datetime->format('Y-m-d'); // "2025-07-30"
			$parametros['hora_entrega']  = $datetime->format('H:i');   // "17:00"
			$dau_id = isset($parametros['dau_id']) ? intval($parametros['dau_id']) : 0;

			$rsHoja = $objHoja_enfermeria->SelectFormularioEnfermeriaById($objCon, $dau_id);

			$checkboxes = [
			    'frm_elementos_via',
			    'frm_elementos_sng',
			    'frm_elementos_sonda_foley',
			    'frm_elementos_tet',
			    'frm_hta',
				'frm_diabetes',
				'frm_ext_superiores',
				'frm_ext_inferiores',
				'jabon',
				'shampoo',
				'pasta',
				'desodorante',
				'confort',
				'pañal',
				'pijama',
				'pantuflas',
				'polera',
				'poleron',
				'pantalon',
				'almohada',
				'frazada',
				'sabana',
				'frm_via_telefonica',
				'frm_via_presencial'
			];

			foreach ($checkboxes as $chk) {
			    if (!isset($parametros[$chk])) {
			        $parametros[$chk] = 'No';
			    }
			}
			if (count($rsHoja) > 0) {
				$objHoja_enfermeria->UpdateFormularioEnfermeria($objCon, $parametros, $rsHoja[0]['id_hojaEnfermeria']);
				$idHojaEnfermeria = $rsHoja[0]['id_hojaEnfermeria'];
			} else {
				$idHojaEnfermeria = $objHoja_enfermeria->InsertFormularioEnfermeria($objCon, $parametros);
			}
			$objCon->commit();
			$respuesta = array("status" => "success", "idHojaEnfermeria" => $idHojaEnfermeria);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'IngresarProcedimientoEnfermera':
		try {
			$objCon->beginTransaction();
			$horarioServidor            	= $objUtil->getHorarioServidor($objCon);
			$parametros 					= $objUtil->getFormulario($_POST);
			$parametros['estado'] 			= 1;
			$parametros['usuario'] 			= $_SESSION['MM_Username'.SessionName];
			$parametros['fecha'] 			= $horarioServidor[0]['fecha'];
			$parametros['tipo_indicacion']	= 1;
			$parametros['hora'] 			= $horarioServidor[0]['hora'];
			$parametros['creado_en'] 		= $horarioServidor[0]['fecha']." ".$horarioServidor[0]['hora'];

			$IdCabecera_indicaciones_enfermeria = $objCabecera_indicaciones_enfermeria->Insert($objCon, $parametros);

        	$procedimientos = json_decode($_POST['procedimientos_json'], true);
        	foreach ($procedimientos as $proc) {
	            $detalle = [
	                'procedimiento_id'  => $proc['procedimiento_id'],
	                'subcategoria_id'   => $proc['subcategoria_id'],
	                'comentario'        => $proc['comentario'],
	                'usuario'           => $_SESSION['MM_Username'.SessionName],
	                'fecha'             => $horarioServidor[0]['fecha'],
	                'hora'              => $horarioServidor[0]['hora'],
	                'dau_id'            => $parametros['dau_id'],
	                'estado'            => 1,
	                'tipo_indicacion'   => 1,
	                'id_cabecera_indicaciones_enfermeria'           => $IdCabecera_indicaciones_enfermeria
	            ];
	            $parametros['id_indicacion_enfermeria']  = $objIndicaciones_enfermeria->Insert($objCon, $detalle);
	        	// $parametros['id_indicacion_enfermeria'] = $IdCabecera_indicaciones_enfermeria;
	        	$parametros['movimiento'] = "Solicitud de indicación";
				$objTrazabilidad_indicaciones_enfermeria->Insert($objCon, $parametros);
	        }
			$objCon->commit();
			$respuesta = array("status" => "success", "idHojaEnfermeria" => $idHojaEnfermeria);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
	case 'GestionarIndicacion':
		try {
			$objCon->beginTransaction();
			$horarioServidor            			= $objUtil->getHorarioServidor($objCon);
			$parametros 							= $objUtil->getFormulario($_POST);
			$parametrosIndicacion['estado'] 		= $parametros['estado'];
	        $objIndicaciones_enfermeria->Update($objCon, $parametrosIndicacion,$parametros['indicacion_id']);

	        $parametrosTraza['id_indicacion_enfermeria'] 	= $parametros['indicacion_id'];
	        $parametrosTraza['fecha'] 						= $horarioServidor[0]['fecha'];
	        $parametrosTraza['hora'] 						= $horarioServidor[0] ['hora'];
	        $parametrosTraza['usuario'] 					= $_SESSION['MM_Username'.SessionName];
	        $parametrosTraza['observacion'] 				= $parametros['frm_observacion_aplica'];
	        $parametrosTraza['estado'] 						= $parametros['estado'];
	        if($parametrosTraza['estado'] == 2){$parametrosTraza['movimiento'] = "Inicio indicación"; }
	        else if($parametrosTraza['estado'] == 3){$parametrosTraza['movimiento'] = "Indicación Aplicada"; }
	        else if($parametrosTraza['estado'] == 3){$parametrosTraza['movimiento'] = "Índicación Rechazada"; }


				$objTrazabilidad_indicaciones_enfermeria->Insert($objCon, $parametrosTraza);
	        // }
			$objCon->commit();
			$respuesta = array("status" => "success", "idHojaEnfermeria" => $idHojaEnfermeria);
			echo json_encode($respuesta);
		} catch (PDOException $e) {
			$objCon->rollback();
			$respuesta = array("status" => "error", "message" => $e->getMessage());
			echo json_encode($respuesta);
		}
	break;
}