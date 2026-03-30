<?php
session_start();
error_reporting(0);
require_once('../../../class/Connection.class.php'); $objCon      = new Connection;
require_once("../../../class/Util.class.php");       $objUtil     = new Util;


	switch($_POST['accion']){
		case "pyxis";
			$objCon->db_connect();
			require_once("../../../../integracion/grifols/pyxis/class/Grifols.class.php"); $objGrifols      = new Grifols;
			require_once("../../../class/Paciente.class.php");   $objPaciente   = new Paciente;
			
			$parametros                      = $objUtil->getFormulario($_POST);
			$parametros['rut']               = $parametros['rutPaciente'];
			$parametros['nombreCompleto']    = $parametros['nombrePaciente'].' '.$parametros['APpaciente'].' '.$parametros['AMpaciente'];
			$parametros['id_paciente']       = $parametros['idPaciente'];
			$parametros['servicio']          = 10322;
			$parametros['ctactePaciente']    = $parametros['ctactePaciente'];
			$parametros['dau_id']            = $parametros['dau_id'];

			// echo date('YmdHis');
			// print('<pre>'); print_r($parametros); print('</pre>');
			// highlight_string(printf($parametros, true));

			$rsp_pacint = $objPaciente->obtenerInformacionPaciente($objCon, $parametros['idPaciente']);
			$rut_completo = $rsp_pacint['rut'] . "-" . $objUtil->generaDigito($rsp_pacint['rut']);
			
			// print('<pre>'); print_r($rut_completo); print('</pre>');
			 
			$fechaNaciminto = date('Ymd', strtotime($rsp_pacint['fechanac']));

			$parametros['servicio'] = 10322;

			// print('<pre>'); print_r($rsp_pacint); print('</pre>');

			// DATOS MSJ
			$parametros_ws_pyxis['log_wspy_sistemaEnviaMensaje'] = 2;
			$parametros_ws_pyxis['log_wspy_idDau'] = $parametros['dau_id'];			
			// MSH
			// $parametros_ws_pyxis['TipoMensaje'] = 'ADT';
			$parametros_ws_pyxis['CodigoMensaje'] = 'A01';
			$parametros_ws_pyxis['FechaMensaje'] = date('YmdHis');
			// $parametros_ws_pyxis['IdMensaje'] = '3';
			$parametros_ws_pyxis['IdMensaje'] = '';
			$parametros_ws_pyxis['CodigoCentro'] ='';
			// PID
			$parametros_ws_pyxis['IdPaciente'] = $rut_completo;
			$parametros_ws_pyxis['IdAltPaciente'] = $parametros['idPaciente'];
			$parametros_ws_pyxis['ApPaterno'] = $parametros['APpaciente'];
			$parametros_ws_pyxis['ApMaterno'] = $parametros['AMpaciente'];
			$parametros_ws_pyxis['Nombres'] = $parametros['nombrePaciente'];
			$parametros_ws_pyxis['Sexo'] = $rsp_pacint['sexo'];
			$parametros_ws_pyxis['FechaNacimeinto'] = $fechaNaciminto;
			// PV1
			// $UnidadEnfermeria = '1';
			// $parametros_ws_pyxis['UnidadEnfermeria'] = 'URGENCIA - '.$parametros['servicio'];
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
			$parametros_ws_pyxis['IdEpisodio'] = $parametros['ctactePaciente'];
			$parametros_ws_pyxis['FechaAdmision'] = date('YmdHis');
			$parametros_ws_pyxis['FechaAlta'] ='';
			// OBX
			$parametros_ws_pyxis['Observacion'] = 'PACIENTE ADMISIONADO EN URGENCIA';



			// print('<pre>'); print_r($parametros_ws_pyxis); print('</pre>');

			$parametros['dau_mov_descripcion'] = 'pyxis';

			
			$parametros['dau_mov_usuario']  = $_SESSION['MM_Username'.SessionName];
			
			try{
				$objCon->beginTransaction();
				$con = $objGrifols->requestWS_grifols($objCon, $parametros_ws_pyxis);    
				if($con['status'] == 'success'){
					$status_pyxis = $con['status'];
				}
				else{
					$status_pyxis = $con['status'];
					$message_pyxis = $con['message'];
				}

				$response    			     = array("status" => "success", "status_pyxis" => $status_pyxis, "message_pyxis" => $message_pyxis);
				echo json_encode($response);
				$objCon->commit();
			}catch (PDOException $e){
				$objCon->rollback();
				$response = array("status" => "error", "message" => $e->getMessage());
				echo json_encode($response);
			}

		break;
	}
?>