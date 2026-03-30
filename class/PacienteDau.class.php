<?php
	class PacienteDAU{


		function pacientes_dau($objCon, $parametros){

			$list_dau="";
			$DAUS = $parametros['dau_id'];
			for($a=0;$a<sizeof($DAUS);$a++){
				if($a!=0){ 
					$list_dau.=','; 
				}
					$list_dau.= "'".$DAUS[$a]."'";
			} 
				
			$sql="SELECT
						dau.dau_id, 
						dau.dau_indicacion_egreso_fecha, 
						NOW() AS FechaActual
					FROM
					dau.dau
					WHERE
						dau.dau_id IN ($list_dau) ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR lista_espera_servicio<br>");
			return $datos;
		}

		function getPacienteDau($objCon, $parametros){
			$condicion 	= "";
			// print('<pre>'); print_r($parametros); print('</pre>');
			$sql="SELECT
			dau.cama.dau_id,
			dau.cama.est_id,
			dau.sala.sal_descripcion,
			dau.cama.cam_descripcion,
			paciente.paciente.nombres,
			paciente.paciente.apellidopat,
			paciente.paciente.apellidomat,
			paciente.paciente.nroficha,
			paciente.paciente.rut,
			paciente.paciente.fechanac,
			paciente.paciente.sexo,

			paciente.paciente.transexual,
			paciente.paciente.nombreSocial,

			dau.dau.dau_categorizacion,
			dau.dau.dau_categorizacion_fecha,
			dau.dau.dau_motivo_descripcion,
			dau.motivo_consulta.mot_descripcion,
			dau.sub_motivo_consulta.sub_mot_descripcion,
			dau.dau.dau_ingreso_sala_fecha,
			dau.dau.dau_inicio_atencion_fecha,
			dau.dau.dau_indicacion_egreso_fecha,
			camas.sscc.servicio,
			dau.dau_tiene_indicacion.dau_ind_usuario_indica,
			dau.dau.dau_apreciacion_diagnostica,
			dau.dau.dau_cierre_cie10,
			dau.dau.dau_hipotesis_diagnostica_inicial,
			dau.dau.dau_cierre_fundamento_diag,
			dau.dau.dau_indicaciones_completas,
			dau.dau.dau_indicaciones_solicitadas,
			dau.dau.dau_indicaciones_realizadas,
			dau.categorizacion.cat_nivel,
			cie10.cie10.nombreCIE as nombre,
			dau.dau_admision_fecha,
			DATE(dau.dau_admision_fecha)		  AS dau_admision_fechanNormal,
			DATE(dau.dau_indicacion_egreso_fecha) AS dau_indicacion_egreso_fechanNormal,

			DATE_FORMAT(FROM_DAYS(TO_DAYS(dau_admision_fecha) - TO_DAYS(paciente.fechanac)),'%Y') + 0 AS EDAD,
			dau.dau_tiene_indicacion.dau_ind_fecha_indicada
			FROM
			dau.cama
			INNER JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
			INNER JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
			INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
			LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
			INNER JOIN dau.dau_tiene_indicacion ON dau.cama.dau_id = dau.dau_tiene_indicacion.dau_id
			INNER JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
			INNER JOIN cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
			";


			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .=" dau.cama.est_id = 11";

			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .=" dau.cama.cam_activa = 'S'";

			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .=" dau.dau.dau_indicacion_egreso = 4 ";

			if ($parametros['frm_rut_paciente']) {
				$parametros['frm_rut_paciente'] 	= str_replace(".","",$parametros['frm_rut_paciente']);
				$parametros['frm_rut_paciente'] 	= str_replace(".","",$parametros['frm_rut_paciente']);
				$parametros['frm_rut_paciente'] 	= str_replace(".","",$parametros['frm_rut_paciente']);
				$rut 					= explode("-", $parametros['frm_rut_paciente']);
				$parametros['frm_rut_paciente'] 	= $rut[0];

				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .=" paciente.paciente.rut = '{$parametros['frm_rut_paciente']}'";
			}

			if ($parametros['frm_ficha']) {
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .=" paciente.paciente.nroficha = '{$parametros['frm_ficha']}'";
			}


			if ($parametros['frm_dau']) {
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .=" dau.cama.dau_id = '{$parametros['frm_dau']}'";
			}


			if ($parametros['frm_nombre_paciente']) {
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .=" paciente.paciente.nombres LIKE '%{$parametros['frm_nombre_paciente']}%'";
			}


			$sql   .= $condicion ;

			$sql   .= " ORDER BY dau.dau_indicacion_egreso_fecha asc" ;


			// print('<pre>'); print_r($sql); print('</pre>');
			$datos = $objCon->consultaSQL($sql,"<br>ERROR getPacienteDau <br>");
			return $datos;
		}

		function actualiza_estado_paciente($objCon, $parametros){
			$sql="UPDATE paciente.paciente
				  SET    paciente.fallecido='S'
		          WHERE id={$parametros['frm_id_paciente']}";
			$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Detalle Paciente Fallecido");
			return $response;
		}



		function pacienteSexo($objCon, $parametros){
			$sql="SELECT
					paciente.sexo
					FROM
					paciente.paciente
					WHERE paciente.id={$parametros['frm_id_paciente']}";
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar categorizacion dau<br>");
			return $resultado;
		}



		function cambiarConsultorioPaciente ( $objCon, $parametros ) {

			$sql = "UPDATE
						paciente.paciente
				 	SET
					 	paciente.paciente.centroatencionprimaria = '{$parametros['codigoConsultorioActual']}'
		            WHERE
						paciente.paciente.id = '{$parametros['idPaciente']}' ";

			$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Centro de Atención Primaria del Paciente");

		}

		



		function obtenerInformacionPaciente ( $objCon, $idPaciente ) {

			$sql = "SELECT
						paciente.paciente.*,
						paciente.nacionalidadavis.NACdescripcion
					FROM
						paciente.paciente
					LEFT JOIN
						paciente.nacionalidadavis ON paciente.nacionalidadavis.NACcodigo = paciente.paciente.nacionalidad
					WHERE
						paciente.paciente.id = '{$idPaciente}'
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información de Paciente<br>");

			return $resultado[0];

		}

		function obtenerDatosPacienteDau($objCon, $id_dau){
			$sql = "SELECT
					dau.dau.dau_id,
					dau.dau.id_paciente,
					dau.dau.idctacte,
					dau.dau.dau_admision_fecha,
					paciente.paciente.rut,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					paciente.paciente.fechanac,
					paciente.paciente.sexo,
					paciente.paciente.dv
					FROM
					dau.dau
					INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
					dau.dau.dau_id = {$id_dau}
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información de Paciente<br>");
			return $resultado[0];
		}

		//02-07-24
		function buscarPacientesTransesualesusandoLikeReturIds($objCon, $placeholders){

			$sql = "SELECT id FROM paciente.paciente WHERE id IN ($placeholders)";

			$response = $objCon->consultaSQL($sql, "Error buscarPacientesTransesualesusandoLikeReturIds");
			
			return $response;

		}

		function buscarPacientesTransesualesusandoLike($objCon, $nombreSocial){

			$sql = "SELECT
						paciente.id
					FROM
						paciente.paciente
					WHERE
						nombreSocial LIKE '%{$nombreSocial}%' && transexual = 'S'";

			$response = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Nacionalidad<br>");
			return $response;

		}
		//02-07-24

		//03-07-24
		function actualizarPacienteTrans($objCon, $parametros){
			$sql = "UPDATE paciente.paciente
					SET transexual = '{$parametros["frm_transexual"]}',
						identidad_genero = '{$parametros["frm_identidadGenero"]}',
						nombre_legal = '{$parametros["frm_nombre_legal"]}',
						nombreSocial = '{$parametros["frm_nombreSocial"]}'
					WHERE id = '{$parametros["paciente_idAux"]}'";
		
			$response = $objCon->ejecutarSQL($sql, "Error al actualizarPacienteTrans");
			return $response;
		}
		
		public function getInfoPacienteTrans($objCon, $paciente_id){
			$sql = "SELECT
						transexual,
						identidad_genero,
						nombre_legal,
						nombreSocial
					FROM
						paciente.paciente
					WHERE
						id = '{$paciente_id}'";

			$response = $objCon->consultaSQL($sql,"<br>Error al getInfoPacienteTrans<br>");
			return $response;
		}

		function logInsertPacienteTrans($objCon, $parametros){
			$sql = "INSERT INTO bdlog.log_paciente_trans (
				paciente_id,
				transexual,
				nombreSocial,
				nombre_legal,
				identidad_genero,
				sistema,
				accion,
				fecha,
				profesional_id
			) VALUES (
				'{$parametros["paciente_idAux"]}',
				'{$parametros["frm_transexual"]}',
				'{$parametros["frm_nombreSocial"]}',
				'{$parametros["frm_nombre_legal"]}',
				'{$parametros["frm_identidadGenero"]}',
				'{$parametros["SistemaAux"]}',
				'{$parametros["accionAux"]}',
				NOW(),
				'{$parametros["usuario_id"]}'
			);";
		
			$response = $objCon->ejecutarSQL($sql, "Error al logInsertPacienteTrans");
			return $response;
		}

		//03-07-24
		
		//02-09-24
		public function getInfoBasicPac($objCon, $paciente_id){
			$sql = "SELECT
						id,
						rut,
						nombres,
						apellidopat,
						apellidomat,
						dv,
						transexual,
						nombreSocial,
						identidad_genero
					FROM
						paciente.paciente
					WHERE
						id = {$paciente_id}";

			$response = $objCon->consultaSQL($sql,"<br>Error al getInfoBasicPac<br>");
			return $response;
		}
		//02-09-24



		function obtenerDatosPacienteParaReemplazo($conn, $parametros) {
			require_once("Util.class.php");

			$util = new Util();
			$realizarConsulta = false;
			$condicion = "";

			$sql = "
				SELECT
					paciente.*
				FROM
					paciente.paciente AS paciente
			";

			if ($util->existe($parametros["rut"])) {
				$realizarConsulta = true;

				$condicion .= ($util->existe($condicion)) ? " AND " : " WHERE ";
				$condicion .= " paciente.rut = '{$parametros['rut']}' ";
			}

			if ($util->existe($parametros["rut_extranjero"])) {
				$realizarConsulta = true;

				$condicion .= ($util->existe($condicion)) ? " AND " : " WHERE ";
				$condicion .= " paciente.rut_extranjero = '{$parametros['rut_extranjero']}' ";
			}

			if (!$realizarConsulta) {
				return array();
			}

			$sql .= $condicion;

			return $conn->consultaSQL($sql,"Error al obtenerDatosPacienteParaReemplazo");
		}

		function ingresarDauPacienteNN($conn, $parametros) {
			$sql = "
				INSERT INTO
					dau.dau_paciente_nn(
						idDau,
						ctaCte,
						idPacienteNN,
						fechaCreacion,
						usuarioCreacion
					)
				VALUES(
					'{$parametros['idDau']}',
					'{$parametros['ctaCte']}',
					'{$parametros['idPacienteNN']}',
					NOW(),
					'{$parametros['usuarioCreacion']}'
				)
			";

			$conn->ejecutarSQL($sql, "Error al registrar ingresarDauPacienteNN");
		}

		function actualizarNombrePacienteNN($conn, $parametros) {
			$sql = "
				UPDATE
					paciente.paciente AS paciente
				SET
					paciente.nombres = '{$parametros['nombrePacienteNN']}'
				WHERE
					paciente.id = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al registrar actualizarNombrePacienteNN");
		}

		function modificarIdPacienteNNEnDauPacienteNN($conn, $parametros) {
			$sql = "
				UPDATE
					dau.dau_paciente_nn AS dauPacienteNN
				SET
					dauPacienteNN.idPaciente = '{$parametros['idPaciente']}',
					dauPacienteNN.fechaReemplazo = NOW(),
					dauPacienteNN.usuarioReemplazo = '{$parametros['usuarioReemplazo']}'
				WHERE
					dauPacienteNN.idDau = '{$parametros['idDau']}'
				AND
					dauPacienteNN.idPacienteNN = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al modificarIdPacienteNNEnDauPacienteNN");
		}



		function modificarIdPacienteNNEnDau($conn, $parametros) {
			$sql = "
				UPDATE
					dau.dau AS dau
				SET
					dau.id_paciente = '{$parametros['idPaciente']}'
				WHERE
					dau.dau_id = '{$parametros['idDau']}'
				AND
					dau.id_paciente = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al modificarIdPacienteNNEnDau");
		}



		function modificarIdPacienteNNEnRegistroViolencia($conn, $parametros) {
			$sql = "
				UPDATE
					rce.registro_violencia AS registroViolencia
				SET
					registroViolencia.idPaciente = '{$parametros['idPaciente']}'
				WHERE
					registroViolencia.idDau = '{$parametros['idDau']}'
				AND
					registroViolencia.idRCE = '{$parametros['idRCE']}'
				AND
					registroViolencia.idPaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnRegistroViolencia");
		}



		function modificarIdPacienteNNEnSolicitudAPS($conn, $parametros) {
			$sql = "
				UPDATE
					rce.solicitud_aps AS solicitudAPS
				SET
					solicitudAPS.idPaciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudAPS.idDau = '{$parametros['idDau']}'
				AND
					solicitudAPS.idPaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnSolicitudAPS");
		}



		function modificarIdPacienteNNEnSolicitudEspecialista($conn, $parametros) {
			$sql = "
				UPDATE
					rce.solicitud_especialista AS solicitudEspecialista
				SET
					solicitudEspecialista.SESPidPaciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudEspecialista.SESPidRCE = '{$parametros['idRCE']}'
				AND
					solicitudEspecialista.SESPidPaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnSolicitudEspecialista");
		}



		function modificarIdPacienteNNEnSolicitudEvolucion($conn, $parametros) {
			$sql = "
				UPDATE
					rce.solicitud_evolucion AS solicitudEvolucion
				SET
					solicitudEvolucion.SEVOidPaciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudEvolucion.SEVOidRCE = '{$parametros['idRCE']}'
				AND
					solicitudEvolucion.SEVOidPaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnSolicitudEvolucion");
		}



		function modificarIdPacienteNNEnSolicitudInicioAtencion($conn, $parametros) {
			$sql = "
				UPDATE
					rce.solicitud_inicioatencion AS solicitudInicioAtencion
				SET
					solicitudInicioAtencion.SIAidPaciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudInicioAtencion.SIAidRCE = '{$parametros['idRCE']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnSolicitudInicioAtencion");
		}



		function modificarIdPacienteNNEnSolicitudSIC($conn, $parametros) {
			$sql = "
				UPDATE
					rce.solicitud_sic AS solicitudSIC
				SET
					solicitudSIC.SICidPaciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudSIC.SICdau = '{$parametros['idDau']}'
				AND
					solicitudSIC.SICidPaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnSolicitudSIC");
		}

		function modificarIdPacienteNNEnSolicitudLaboratorio($conn, $parametros) {
			$sql = "
				UPDATE
					laboratorio.solicitud AS solicitudLaboratorio
				SET
					solicitudLaboratorio.id_paciente = '{$parametros['idPaciente']}'
				WHERE
					solicitudLaboratorio.id_paciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnCtaCte");
		}



		function modificarIdPacienteNNEnCtaCte($conn, $parametros) {
			$sql = "
				UPDATE
					paciente.ctacte AS ctacte
				SET
					ctacte.idpaciente = '{$parametros['idPaciente']}'
				WHERE
					ctacte.idctacte = '{$parametros['ctaCte']}'
				AND
					ctacte.idpaciente = '{$parametros['idPacienteNN']}'
			";

			return $conn->consultaSQL($sql,"Error al modificarIdPacienteNNEnCtaCte");
		}

		function modificarIdPacienteNNEnDetallePrestacion($conn, $parametros) {
			$sql = "
				UPDATE
					recauda.detalle_prestacion AS detallePrestacion
				SET
					detallePrestacion.det_pre_rut_paciente = '{$parametros['idPaciente']}'
				WHERE
					detallePrestacion.det_pre_cta_cte = '{$parametros['ctaCte']}'
				AND
					detallePrestacion.det_pre_rut_paciente = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al modificarIdPacienteNNEnDetallePrestacion");
		}



		function modificarIdPacienteNNEnMatriz($conn, $parametros) {
			$sql = "
				UPDATE
					estadistica.matrizppvppi AS matriz
				SET
					matriz.matrizPacieCod = '{$parametros['idPaciente']}',
					matriz.matrizRutPaciente = '{$parametros['rutPaciente']}',
					matriz.matrizNombrePacie = '{$parametros['nombrePaciente']}',
					matriz.matrizFichaPacie = '{$parametros['numeroFicha']}',
					matriz.matrizSexoPacie = '{$parametros['sexoPaciente']}',
					matriz.matrizFNacPacie = '{$parametros['fechaNacimientoPaciente']}',
					matriz.matrizPreviCod = '{$parametros['previsionPaciente']}'
				WHERE
					matriz.matrizPacieCod = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al modificarIdPacienteNNEnMatriz");
		}



		function eliminarPacienteEnPaciente($conn, $parametros) {
			$sql = "
				DELETE FROM
					paciente.paciente
				WHERE
					paciente.paciente.id = '{$parametros['idPacienteNN']}'
			";

			$conn->ejecutarSQL($sql, "Error al eliminarPacienteEnPaciente");
		}
	}
?>