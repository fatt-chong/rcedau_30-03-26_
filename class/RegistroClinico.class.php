<?php
class RegistroClinico{
	function SelectCie10 ($objCon , $cie10){
		$sql = "SELECT *
		FROM cie10.cie10
		WHERE cie10.codigoCIE = '{$cie10}'";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}
	

	function ObtenerCIE10GES ($objCon , $cie10){
		$sql = "SELECT *
		FROM dau.cie10_ges
		WHERE cie10_ges.Id_cie10 = '{$cie10}'";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}

	function SelectUsuario ($objCon , $usuario){
		$sql = "SELECT UPPER(usuario.nombreusuario) AS nombreusuario
		FROM acceso.usuario
		WHERE usuario.idusuario = '{$usuario}'";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado[0]['nombreusuario'];
	}
	function SelectUsuarioAll ($objCon , $usuario){
		$sql = "SELECT 	
					usuario.nombreusuario, 
					usuario.idusuario, 
					usuario.rutusuario
				FROM acceso.usuario
				WHERE usuario.idusuario = '{$usuario}'";
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}
	function insertaRCE($objCon,$parametros){
		$sql = "INSERT INTO rce.registroclinico
		(eveId,
		dau_id,
		PROcodigo,
		regFecha,
		regEstado,
		regUsuarioInserta,
		RCE_tipo)
		VALUES(
		'{$parametros["evento_id"]}',
		'{$parametros["dau_id"]}',
		'{$parametros["usuarioEve"]}',
		NOW(),
		'{$parametros["estadoRCE"]}',
		'{$parametros["dau_mov_usuario"]}',
		'{$parametros["tipoAtencionPaciente"]}')";

		$resultado = $objCon->ejecutarSQL($sql, "Error al insertar RCE");
		$rce_id = $objCon->lastInsertId($sql);
		return $rce_id;

	}

	function actualizaRCE($objCon,$parametros){
		$condicion = '';
		$sql="UPDATE rce.registroclinico";
		if(isset($parametros['frm_rce_motivoConsulta'])){
			$parametros['frm_rce_motivoConsulta'] = $parametros['frm_rce_motivoConsulta'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regMotivoConsulta = '{$parametros['frm_rce_motivoConsulta']}'";
		}
		if(isset($parametros['frm_rce_hipotesisInicial'])){
			$parametros['frm_rce_hipotesisInicial'] = $parametros['frm_rce_hipotesisInicial'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regHipotesisInicial = '{$parametros['frm_rce_hipotesisInicial']}'";
		}
		if(isset($parametros['frm_indicaciones_alta'])){
			$parametros['frm_indicaciones_alta'] = $parametros['frm_indicaciones_alta'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regIndicacionEgresoUrgencia = '{$parametros['frm_indicaciones_alta']}'";
		}
		if(isset($parametros['frm_hipotesis_final'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regHipotesisFinal = '{$parametros['frm_hipotesis_final']}'";
		}
		if(isset($parametros['frm_codigoCIE10'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regDiagnosticoCIE10 = '{$parametros['frm_codigoCIE10']}'";
		}
		if(isset($parametros['frm_cie10Abierto'])){
			$parametros['frm_cie10Abierto'] = $parametros['frm_cie10Abierto'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regCIE10Abierto = '{$parametros['frm_cie10Abierto']}'";
		}
		if(isset($parametros['frm_pronostico'])){
			$parametros['frm_pronostico'] = $parametros['frm_pronostico'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" PRONcodigo = '{$parametros['frm_pronostico']}'";
		}
		if(isset($parametros['dau_mov_usuario'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regUsuarioActualiza = '{$parametros['dau_mov_usuario']}',regFechaActualiza= NOW()";
		}
		$sql .= $condicion." WHERE regId = '{$parametros['rce_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al actualizaRCE");
	}



	function actualizaRCESIA($objCon,$parametros){
		$condicion = "";

		$sql="UPDATE rce.registroclinico";
		if(isset($parametros['frm_rce_motivoConsultaSIA'])){
			$parametros['frm_rce_motivoConsultaSIA'] = $parametros['frm_rce_motivoConsultaSIA'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regMotivoConsulta = '{$parametros['frm_rce_motivoConsultaSIA']}'";
		}
		if(isset($parametros['frm_rce_hipotesisInicialSIA'])){
			$parametros['frm_rce_hipotesisInicialSIA'] = $parametros['frm_rce_hipotesisInicialSIA'];
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regHipotesisInicial = '{$parametros['frm_rce_hipotesisInicialSIA']}'";
		}
		if(isset($parametros['dau_mov_usuario'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" regUsuarioActualiza = '{$parametros['dau_mov_usuario']}', regFechaActualiza= NOW()";
		}
		$sql .= $condicion." WHERE regId = '{$parametros['rce_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al actualizaRCE");
	}



	function actualizaAlcoh($objCon,$parametros){
		$conn = "";
		$conn2 = "";
		$conn3 = "";
		$condicion = "";

		if($parametros['frm_rce_est_eti']==''){
			$conn.= "dau_alcoholemia_estado_etilico = NULL";
		}else{
			$conn.="dau_alcoholemia_estado_etilico = '{$parametros['frm_rce_est_eti']}'";
		}
		if($parametros['frm_rce_alc_fech']==''){
			$conn2.= "dau_alcoholemia_fecha = NULL";
		}else{
			$conn2.="dau_alcoholemia_fecha = '{$parametros['frm_rce_alc_fech']}'";
		}
		if($parametros['frm_rce_n_frasco']==''){
			$conn3.= "dau_alcoholemia_numero_frasco = NULL";
		}else{
			$conn3.="dau_alcoholemia_numero_frasco = '{$parametros['frm_rce_n_frasco']}'";
		}
		$sql="UPDATE dau.dau";
		if(isset($parametros['frm_rce_est_eti'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn;
		}
		if(isset($parametros['frm_rce_alc_fech'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn2;
		}
		if(isset($parametros['frm_rce_n_frasco'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn3;
		}
		$condicion.= " ,dau_alcoholemia_medico='{$parametros['rut']}'";
		$sql .= $condicion." WHERE dau_id = '{$parametros['dau_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al actualizarALCOH");
	}



	function actualizaAlcohSIA($objCon,$parametros){
		$conn = "";
		$conn2 = "";
		$conn3 = "";
		$condicion = "";

		if($parametros['frm_rce_est_etiSIA']==''){
			$conn.= "dau_alcoholemia_estado_etilico = NULL";
		}else{
			$conn.="dau_alcoholemia_estado_etilico = '{$parametros['frm_rce_est_etiSIA']}'";
		}
		if($parametros['frm_rce_alc_fechSIA']==''){
			$conn2.= "dau_alcoholemia_fecha = NULL";
		}else{
			$conn2.="dau_alcoholemia_fecha = '{$parametros['frm_rce_alc_fechSIA']}'";
		}
		if($parametros['frm_rce_n_frascoSIA']==''){
			$conn3.= "dau_alcoholemia_numero_frasco = NULL";
		}else{
			$conn3.="dau_alcoholemia_numero_frasco = '{$parametros['frm_rce_n_frascoSIA']}'";
		}
		$sql="UPDATE dau.dau";
		if(isset($parametros['frm_rce_est_etiSIA'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn;
		}
		if(isset($parametros['frm_rce_alc_fechSIA'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn2;
		}
		if(isset($parametros['frm_rce_n_frascoSIA'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=$conn3;
		}
		$sql .= $condicion." WHERE dau_id = '{$parametros['dau_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al actualizarALCOH");
	}

	function MovimientoEVOxEspe($objCon,$parametros){
		$sql = "
			SELECT
				parametros_clinicos.especialidad.ESPdescripcion as titulo,
				'1' as tipo, 
			    SESPfecha AS fecha,
				SESPobservacionEspecialista AS evolucion,
				SESPusuarioAplica as usuario
			FROM 
			    rce.solicitud_especialista
			INNER JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			WHERE solicitud_especialista.SESPidRCE = {$parametros['rce_id']}
			UNION ALL
			SELECT 
				'Evolución' as titulo,
				'2' as tipo, 
			    SEVOfecha AS fecha,
				SEVOevolucion AS evolucion,
				SEVOusuario as usuario
			FROM 
			    rce.solicitud_evolucion
			WHERE solicitud_evolucion.SEVOidRCE = {$parametros['rce_id']}
			ORDER BY 
			    fecha asc
			";

		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}
		function MovimientoEVOxEspe2($objCon,$parametros){
		$sql = "
			SELECT
				otro_especialista.descripcion_otro  COLLATE utf8_spanish_ci AS titulo,
				'1' AS tipo, 
				solicitud_otros_especialidad.sol_otro_usuarioAplica_fecha AS fecha, 
				solicitud_otros_especialidad.sol_otro_usuarioAplica_observacion AS evolucion, 
				solicitud_otros_especialidad.sol_otro_usuarioAplica as usuario
			FROM rce.solicitud_otros_especialidad
			INNER JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro
			WHERE solicitud_otros_especialidad.idRCE = {$parametros['rce_id']} and sol_otro_usuarioAplica is not null
				
			UNION ALL
			
			SELECT
			    CASE 
			        WHEN solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion
			        ELSE otro_especialista.descripcion_otro
			    END AS titulo,
			    '1' AS tipo, 
			    SESPfechaAplicacion AS fecha,
			    SESPobservacionEspecialista AS evolucion,
			    SESPusuarioAplica AS usuario
			FROM rce.solicitud_especialista
			LEFT JOIN parametros_clinicos.especialidad 
			    ON solicitud_especialista.SESPfuente = 'P' 
			    AND solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			LEFT JOIN rce.otro_especialista 
			    ON solicitud_especialista.SESPfuente = 'O' 
			    AND solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro

			WHERE solicitud_especialista.SESPidRCE = {$parametros['rce_id']} 
			    AND SESPusuarioAplica IS NOT NULL

			
			UNION ALL
			
			SELECT 
				'Evolución' as titulo,
				'2' as tipo, 
			    SEVOfecha AS fecha,
				SEVOevolucion AS evolucion,
				SEVOusuario as usuario
			FROM 
			    rce.solicitud_evolucion
			WHERE solicitud_evolucion.SEVOidRCE = {$parametros['rce_id']}
			
			UNION ALL
			
			SELECT
		    CASE 
		        WHEN solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion
		        ELSE otro_especialista.descripcion_otro
			    END AS titulo,
			    '3' AS tipo, 
			    SESPfecha AS fecha,
			    SESPobservacion AS evolucion,
			    SESPusuario AS usuario
			FROM rce.solicitud_especialista
			LEFT JOIN parametros_clinicos.especialidad 
			    ON solicitud_especialista.SESPfuente = 'P' 
			    AND solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			LEFT JOIN rce.otro_especialista 
			    ON solicitud_especialista.SESPfuente = 'O' 
			    AND solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro

			WHERE solicitud_especialista.SESPidRCE = {$parametros['rce_id']}
			ORDER BY 
		    fecha asc
			
			";
			// echo $sql;
		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}
	// function MovimientoEVOxEspe2($objCon,$parametros){
	// 	$sql = "
	// 		SELECT
	// 			otro_especialista.descripcion_otro COLLATE utf8_spanish_ci AS titulo, 
	// 			'3' AS tipo, 
	// 			solicitud_otros_especialidad.sol_otro_fecha AS fecha, 
	// 			solicitud_otros_especialidad.sol_otro_observacion AS evolucion, 
	// 			solicitud_otros_especialidad.sol_otro_usuario as usuario
	// 		FROM rce.solicitud_otros_especialidad
	// 		INNER JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro
	// 		WHERE solicitud_otros_especialidad.idRCE = {$parametros['rce_id']}
	// 		UNION ALL

	// 		SELECT
	// 			parametros_clinicos.especialidad.ESPdescripcion as titulo,
	// 			'1' as tipo, 
	// 		    SESPfechaAplicacion AS fecha,
	// 			SESPobservacionEspecialista AS evolucion,
	// 			SESPusuarioAplica as usuario
	// 		FROM 
	// 		    rce.solicitud_especialista
	// 		INNER JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
	// 		WHERE solicitud_especialista.SESPidRCE = {$parametros['rce_id']} and SESPusuarioAplica is not null
	// 		UNION ALL
	// 		SELECT 
	// 			'Evolución' as titulo,
	// 			'2' as tipo, 
	// 		    SEVOfecha AS fecha,
	// 			SEVOevolucion AS evolucion,
	// 			SEVOusuario as usuario
	// 		FROM 
	// 		    rce.solicitud_evolucion
	// 		WHERE solicitud_evolucion.SEVOidRCE = {$parametros['rce_id']}
	// 		UNION ALL
	// 		SELECT
	// 			parametros_clinicos.especialidad.ESPdescripcion as titulo,
	// 			'3' as tipo, 
	// 		    SESPfecha AS fecha,
	// 			SESPobservacion AS evolucion,
	// 			SESPusuario as usuario
	// 		FROM 
	// 		    rce.solicitud_especialista
	// 		INNER JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
	// 		WHERE solicitud_especialista.SESPidRCE = {$parametros['rce_id']}
	// 		ORDER BY 
	// 		    fecha asc
	// 		";

	// 	$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
	// 	return $resultado;
	// }
	function consultaRCE($objCon,$parametros){
		$sql = "SELECT
		registroclinico.regId,
		registroclinico.dau_id,
		registroclinico.eveId,
		registroclinico.PROcodigo,
		registroclinico.regFecha,
		registroclinico.regEstado,
		registroclinico.regAnamnesis,
		registroclinico.regHallazgos,
		registroclinico.regDiagnosticoCie10,
		registroclinico.regDiagnosticoLibre,
		registroclinico.regCtaCte,
		registroclinico.regPrivacidad,
		registroclinico.regUsuarioInserta,
		registroclinico.regUsuarioActualiza,
		registroclinico.regFechaActualiza,
		registroclinico.regHipotesisInicial,
		registroclinico.regHipotesisFinal,
		registroclinico.regIndicacionEgreso,
		registroclinico.regIndicacionEgresoUrgencia,
		registroclinico.regAuge,
		registroclinico.PRONcodigo,
		registroclinico.regMotivoConsulta,
		dau.dau.dau_viaje_epidemiologico,
		dau.dau.dau_pais_epidemiologia,
		dau.dau.dau_inicio_atencion_fecha,
		dau.dau.dau_inicio_atencion_usuario,
		dau.dau.dau_observacion_epidemiologica,
		dau.dau.dau_atencion,
		dau.dau.dau_admision_fecha,
		dau.dau.id_paciente,
		dau.dau.est_id,
		dau.dau.dau_alcoholemia_numero_frasco,
		dau.dau.dau_motivo_consulta,
		dau.dau.dau_categorizacion,
		
		usuarioInserta.nombreusuario AS nombreUsuarioInserta,
		acceso.usuario.nombreusuario AS nombreUsuario,
		registroclinico.regCIE10Abierto,
		dau.seguimiento_paciente.seguimientoPaciente,
		cie10.ges 
		FROM rce.registroclinico
		LEFT JOIN acceso.usuario AS usuarioInserta ON rce.registroclinico.regUsuarioInserta = usuarioInserta.idusuario
		LEFT JOIN acceso.usuario ON rce.registroclinico.regUsuarioActualiza = acceso.usuario.idusuario
		INNER JOIN dau.dau ON rce.registroclinico.dau_id = dau.dau.dau_id
		LEFT JOIN dau.seguimiento_paciente ON dau.dau.dau_id = dau.seguimiento_paciente.idDau
		LEFT JOIN cie10.cie10 ON rce.registroclinico.regDiagnosticoCie10 = cie10.cie10.codigoCIE 
		WHERE rce.registroclinico.dau_id = {$parametros['dau_id']}";

		$resultado = $objCon->consultaSQL($sql,"Error al buscar RCE del paciente");
		return $resultado;
	}


	function insertarSolicitudIndicaciones($objCon,$parametros){

		$sql="INSERT INTO rce.solicitud_indicaciones
		(regId,
		sol_ind_estado,
		sol_ind_servicio,
		sol_clasificacionTratamiento,
		sol_ind_preCod,
		sol_ind_descripcion,
		sol_ind_usuarioInserta,
		id_cabecera_indicaciones,
		id_solicitud_transfusion,
		sol_ind_fechaInserta)
		VALUES(
		{$parametros['rce_id']},
		{$parametros['est_id']},
		{$parametros['servicio']},
		{$parametros['clasificacionTratamiento']},
		'{$parametros['codigo']}',
		'{$parametros['descripcion']}',
		'{$parametros['dau_mov_usuario']}',
		'{$parametros['id_cabecera_indicaciones']}',
		'{$parametros['id_solicitudTransfusion']}',
		NOW())";
		$response = $objCon->ejecutarSQL($sql, "Error al insertarNuevaIndicacion sol indi");
		return $objCon->lastInsertId();
	}


	// function insertarSolicitudIndicaciones($objCon,$parametros){

	// 	$sql="INSERT INTO rce.solicitud_indicaciones
	// 	(regId,
	// 	sol_ind_estado,
	// 	sol_ind_servicio,
	// 	sol_clasificacionTratamiento,
	// 	sol_ind_preCod,
	// 	sol_ind_descripcion,
	// 	sol_ind_usuarioInserta,
	// 	id_cabecera_indicaciones,
	// 	sol_ind_fechaInserta)
	// 	VALUES(
	// 	{$parametros['rce_id']},
	// 	{$parametros['est_id']},
	// 	{$parametros['servicio']},
	// 	{$parametros['clasificacionTratamiento']},
	// 	'{$parametros['codigo']}',
	// 	'{$parametros['descripcion']}',
	// 	'{$parametros['dau_mov_usuario']}'
	// 	'{$parametros['id_cabecera_indicaciones']}',
	// 	NOW())";
	// 	$response = $objCon->ejecutarSQL($sql, "Error al insertarNuevaIndicacion sol indi");
	// 	return $objCon->lastInsertId();
	// }



	// function listarIndicacionesRCE($objCon,$parametros, $eventos = 0){
	// 	$sql="
	// 		SELECT
	// 			rce.solicitud_imagenologia.sol_ima_id AS sol_id,
	// 			rce.solicitud_imagenologia.sol_ima_estado AS estado,
	// 			rce.estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
	// 			'Solicitud Imagenologia' AS descripcion,
	// 			'2' AS cod_descripcion,
	// 			rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
	// 			rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
	// 			IF(
	// 				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
	// 				le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci,
	// 				rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
	// 			) AS tipoExamen,
	// 			IF(
	// 				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
	// 				le.prestaciones_imagenologia.id_prestaciones,
	// 				rce.detalle_solicitud_imagenologia.det_ima_codigo
	// 			) AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplica,
	// 			rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplica,
	// 			rce.solicitud_imagenologia.sol_ima_usuarioAnula AS usuarioAnula,
	// 			rce.solicitud_imagenologia.sol_ima_fechaAnula AS fechaAnula,
	// 			rce.detalle_solicitud_imagenologia.SIC_id AS sic_id,
	// 			rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
	// 			rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			IF(
	// 				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
	// 				le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
	// 				rce.detalle_solicitud_imagenologia.det_ima_descripcion
	// 			) AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			IF(
	// 				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
	// 				'',
	// 				(
	// 					SELECT
	// 						informe.informe
	// 					FROM
	// 						integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
	// 					WHERE
	// 						informe.INTcodigo =
	// 						(
	// 							SELECT
	// 								MAX(i.INTcodigo)
	// 							FROM
	// 								integraciones.integracion_ingrad_respuesta_api_hjnc AS i
	// 							LEFT JOIN
	// 								rayos.solicitud_cabecera_img_registro AS r on r.INTid_imgrad = i.INTidingrad
	// 							WHERE
	// 								r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
	// 							AND
	// 								i.INTprestacion = rayos.solicitud_imagen_camas.ID
	// 						)
	// 				)
	// 			) AS informe,
	// 		IF(
	// 			DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
	// 			'',
	// 			rayos.solicitud_cabecera_img_registro.url_resultado
	// 		) AS urlResultado
	// 		FROM
	// 			rce.solicitud_imagenologia
	// 		LEFT JOIN
	// 			rce.detalle_solicitud_imagenologia
	// 			ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
	// 		LEFT JOIN
	// 			rce.detalle_solicitud_imagenologia_dalca
	// 			ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
	// 		LEFT JOIN
	// 			le.prestaciones_imagenologia
	// 			ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
	// 		LEFT JOIN
	// 			rce.estado_indicacion
	// 			ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
	// 		LEFT JOIN
	// 			acceso.usuario accesoUsuarioSolicita
	// 			ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
	// 		LEFT JOIN
	// 			acceso.usuario accesoUsuarioAplica
	// 			ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
	// 		LEFT
	// 			JOIN rayos.solicitud_imagen_camas
	// 			ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
	// 		LEFT JOIN
	// 			rayos.solicitud_cabecera_img_registro
	// 			ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
	// 		WHERE
	// 			rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
	// 		AND
	// 			rce.solicitud_imagenologia.sol_ima_estado != 8
	// 		GROUP BY
	// 			sol_id

	// 		UNION
	// 		SELECT
	// 		solicitud_indicaciones.sol_ind_id AS sol_id,
	// 		solicitud_indicaciones.sol_ind_estado AS estado,
	// 		estado_indicacion.est_descripcion AS estadoDescripcion,
	// 		solicitud_indicaciones.sol_ind_servicio AS servicio,
	// 		CASE
	// 			WHEN solicitud_indicaciones.sol_ind_servicio = 2 THEN 'Solicitud Tratamiento'
	// 			WHEN solicitud_indicaciones.sol_ind_servicio = 4 THEN 'Solicitud Otros'
	// 			WHEN solicitud_indicaciones.sol_ind_servicio = 6 THEN 'Solicitud Procedimiento'
	// 		END AS descripcion,
	// 		'2' AS cod_descripcion,
	// 		solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
	// 		solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
	// 		'' AS tipoExamen,
	// 		'' AS codigoExamen,
	// 		'' AS codigoPrestacion,
	// 		solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
	// 		solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
	// 		solicitud_indicaciones.sol_ind_usuarioAnula AS usuarioAnula,
	// 		solicitud_indicaciones.sol_ind_fechaAnula AS fechaAnula,
	// 		'' AS sic_id,
	// 		'' AS idSolicitudDalca,
	// 		solicitud_indicaciones.sol_ind_observacion AS observacion,
	// 		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 		solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS UsuarioIniciaIndicacion,
	// 		solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS fechaIniciaIndicacion,
	// 		'' AS usuarioTomaMuestra,
	// 		'' AS fechaTomaMuestra,
	// 		solicitud_indicaciones.sol_ind_descripcion AS Prestacion,
	// 		clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
	// 		'' AS informe,
	// 		'' AS urlResultado
	// 		FROM rce.solicitud_indicaciones
	// 		INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
	// 		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
	// 		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
	// 		LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
	// 		WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND solicitud_indicaciones.sol_ind_estado != 8
	// 		GROUP BY sol_id

	// 		UNION
	// 		SELECT
	// 		solicitud_laboratorio.sol_lab_id AS sol_id,
	// 		solicitud_laboratorio.sol_lab_estado AS estado,
	// 		estado_indicacion.est_descripcion AS estadoDescripcion,
	// 		solicitud_laboratorio.sol_lab_tipo AS servicio,
	// 		'Solicitud Laboratorio' AS descripcion,
	// 		'2' AS cod_descripcion,
	// 		solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
	// 		solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
	// 		'' AS tipoExamen,
	// 		'' AS codigoExamen,
	// 		detalle_solicitud_laboratorio.det_lab_codigo AS codigoPrestacion,
	// 		solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
	// 		solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
	// 		solicitud_laboratorio.sol_lab_usuarioAnula	AS usuarioAnula,
	// 		solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
	// 		'' AS sic_id,
	// 		'' AS idSolicitudDalca,
	// 		solicitud_laboratorio.sol_lab_observacion AS observacion,
	// 		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 		'' AS UsuarioIniciaIndicacion,
	// 		'' AS fechaIniciaIndicacion,
	// 		solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
	// 		solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
	// 		detalle_solicitud_laboratorio.det_lab_descripcion AS Prestacion,
	// 		'' AS descripcionClasificacion,
	// 		'' AS informe,
	// 		'' AS urlResultado
	// 		FROM rce.solicitud_laboratorio
	// 		INNER JOIN rce.detalle_solicitud_laboratorio ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
	// 		INNER JOIN rce.estado_indicacion ON solicitud_laboratorio.sol_lab_estado= estado_indicacion.est_id
	// 		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
	// 		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
	// 		WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}' AND solicitud_laboratorio.sol_lab_estado != 8
	// 		GROUP BY sol_id

	// 		UNION
	// 		SELECT
	// 			solicitud_especialista.SESPid AS sol_id,
	// 			solicitud_especialista.SESPestado AS estado,
	// 			estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			solicitud_especialista.SESPtipo AS servicio,
	// 			'Solicitud Especialista' AS descripcion,
	// 			'2' AS cod_descripcion,
	// 			solicitud_especialista.SESPusuario AS usuarioInserta,
	// 			solicitud_especialista.SESPfecha AS fechaInserta,
	// 			'' AS tipoExamen,
	// 			'' AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			solicitud_especialista.SESPusuarioAplica AS usuarioAplica,
	// 			solicitud_especialista.SESPfechaAplicacion AS fechaAplica,
	// 			'' AS usuarioAnula,
	// 			'' AS fechaAnula,
	// 			'' AS sic_id,
	// 			'' AS idSolicitudDalca,
	// 			solicitud_especialista.SESPobservacion AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			parametros_clinicos.especialidad.ESPdescripcion COLLATE utf8_general_ci AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			'' AS informe,
	// 			'' AS urlResultado
	// 		FROM rce.solicitud_especialista
	// 		INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
	// 		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
	// 		LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
	// 		LEFT JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
	// 		WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}'
	// 		GROUP BY sol_id";

	// 		if($eventos != 0){
	// 			$sql.= "
	// 			UNION
	// 			SELECT
	// 			solicitud_altaUrgencia.SAUid AS sol_id,
	// 			solicitud_altaUrgencia.SAUestado AS estado,
	// 			estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			7 AS servicio,
	// 			'Solicitud Alta Urgencia' AS descripcion,
	// 			'2' AS cod_descripcion,
	// 			solicitud_altaUrgencia.SAUusuario AS usuarioInserta,
	// 			solicitud_altaUrgencia.SAUfecha AS fechaInserta,
	// 			'' AS tipoExamen,
	// 			'' AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			'' AS usuarioAplica,
	// 			solicitud_altaUrgencia.SAUfechaAplica AS fechaAplica,
	// 			'' AS usuarioAnula,
	// 			'' AS fechaAnula,
	// 			'' AS sic_id,
	// 			'' AS idSolicitudDalca,
	// 			'' AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			'------------' AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			'' AS informe,
	// 			'' AS urlResultado
	// 			FROM rce.solicitud_altaUrgencia
	// 			INNER JOIN rce.estado_indicacion ON solicitud_altaUrgencia.SAUestado = estado_indicacion.est_id
	// 			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioSolicita.idusuario
	// 			LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioAplica.idusuario
	// 			WHERE solicitud_altaUrgencia.SAUidRCE = '{$parametros['rce_id']}'
	// 			GROUP BY sol_id

	// 			UNION
	// 			SELECT
	// 			solicitud_inicioatencion.SIAid AS sol_id,
	// 			solicitud_inicioatencion.SIAestado AS estado,
	// 			estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			'' AS servicio,
	// 			'Solicitud Inicio Atención' AS descripcion,
	// 			'1' AS cod_descripcion,
	// 			solicitud_inicioatencion.SIAusuario AS usuarioInserta,
	// 			solicitud_inicioatencion.SIAfecha AS fechaInserta,
	// 			'' AS tipoExamen,
	// 			'' AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			'' AS usuarioAplica,
	// 			solicitud_inicioatencion.SIAfechaModificacion AS fechaAplica,
	// 			'' AS usuarioAnula,
	// 			'' AS fechaAnula,
	// 			'' AS sic_id,
	// 			'' AS idSolicitudDalca,
	// 			'' AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			'------------' AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			'' AS informe,
	// 			'' AS urlResultado
	// 			FROM rce.solicitud_inicioatencion
	// 			INNER JOIN rce.estado_indicacion ON solicitud_inicioatencion.SIAestado= estado_indicacion.est_id
	// 			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_inicioatencion.SIAusuario = accesoUsuarioSolicita.idusuario
	// 			LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_inicioatencion.SIAusuarioModifica = accesoUsuarioAplica.idusuario
	// 			WHERE solicitud_inicioatencion.SIAidRCE = '{$parametros['rce_id']}'
	// 			GROUP BY sol_id

	// 			UNION
	// 			SELECT
	// 			solicitud_evolucion.SEVOid AS sol_id,
	// 			solicitud_evolucion.SEVOestado AS estado,
	// 			estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			'' AS servicio,
	// 			'Solicitud Evolución' AS descripcion,
	// 			'2' AS cod_descripcion,
	// 			solicitud_evolucion.SEVOusuario AS usuarioInserta,
	// 			solicitud_evolucion.SEVOfecha AS fechaInserta,
	// 			'' AS tipoExamen,
	// 			'' AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			'' AS usuarioAplica,
	// 			'' AS fechaAplica,
	// 			'' AS usuarioAnula,
	// 			'' AS fechaAnula,
	// 			'' AS sic_id,
	// 			'' AS idSolicitudDalca,
	// 			'' AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			'' AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			'------' AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			'' AS informe,
	// 			'' AS urlResultado
	// 			FROM rce.solicitud_evolucion
	// 			INNER JOIN rce.estado_indicacion ON solicitud_evolucion.SEVOestado= estado_indicacion.est_id
	// 			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_evolucion.SEVOusuario = accesoUsuarioSolicita.idusuario
	// 			WHERE solicitud_evolucion.SEVOidRCE = '{$parametros['rce_id']}'
	// 			GROUP BY sol_id

	// 			UNION
	// 			SELECT
	// 			solicitud_especialista.SESPid AS sol_id,
	// 			solicitud_especialista.SESPestado AS estado,
	// 			estado_indicacion.est_descripcion AS estadoDescripcion,
	// 			5 AS servicio,
	// 			'Solicitud Especialista' AS descripcion,
	// 			'2' AS cod_descripcion,
	// 			solicitud_especialista.SESPusuario AS usuarioInserta,
	// 			solicitud_especialista.SESPfecha AS fechaInserta,
	// 			'' AS tipoExamen,
	// 			'' AS codigoExamen,
	// 			'' AS codigoPrestacion,
	// 			'' AS usuarioAplica,
	// 			'' AS fechaAplica,
	// 			'' AS usuarioAnula,
	// 			'' AS fechaAnula,
	// 			'' AS sic_id,
	// 			'' AS idSolicitudDalca,
	// 			'' AS observacion,
	// 			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
	// 			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
	// 			'' AS UsuarioIniciaIndicacion,
	// 			'' AS fechaIniciaIndicacion,
	// 			'' AS usuarioTomaMuestra,
	// 			'' AS fechaTomaMuestra,
	// 			'------------' AS Prestacion,
	// 			'' AS descripcionClasificacion,
	// 			'' AS informe,
	// 			'' AS urlResultado
	// 			FROM rce.solicitud_especialista
	// 			INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
	// 			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
	// 			LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
	// 			WHERE solicitud_especialista.SESPid = '{$parametros['rce_id']}'
	// 			GROUP BY sol_id
	// 			";
	// 	}

	// 	$sql.= "
	// 	ORDER BY cod_descripcion DESC,fechaInserta DESC,descripcion DESC, sol_id DESC";
	// 	$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
	// 	return $resultado;
	// }


	function listarIndicacionesRCELab2($objCon,$parametros){
		 $sql = "SELECT
			    solicitud_laboratorio.sol_lab_id AS sol_id, 
			    solicitud_laboratorio.sol_lab_estado AS estado, 
			    estado_indicacion.est_descripcion AS estadoDescripcion,
			    solicitud_laboratorio.sol_lab_tipo AS servicio,
			    'Solicitud Laboratorio' AS descripcion,
			    solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
			    solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra, 
			    detalle_solicitud_laboratorio.det_lab_descripcion AS Prestacion, 
			    '' AS descripcionClasificacion 
			FROM rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio  ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN rce.estado_indicacion ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
			left JOIN laboratorio.prestacion ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}' 
			AND solicitud_laboratorio.sol_lab_fechaInserta = '{$parametros['sol_lab_fechaInserta']}'  ";
			if($parametros['tubo_id'] > 0){
				$sql .=" AND prestacion.tubo_id = '{$parametros['tubo_id']}' ";
			}else{
				$sql .=" AND prestacion.tubo_id is null ";
			}
			
			$sql .="  AND solicitud_laboratorio.sol_lab_estado != 8";
		$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
		return $resultado;
	}
	function listarIndicacionesRCELab($objCon,$parametros){
		 $sql = "SELECT
			    solicitud_laboratorio.sol_lab_id AS sol_id,
			    solicitud_laboratorio.sol_lab_estado AS estado,
			    estado_indicacion.est_descripcion AS estadoDescripcion,
			    solicitud_laboratorio.sol_lab_tipo AS servicio,
			    'Solicitud Laboratorio' AS descripcion,
			    '2' AS cod_descripcion,
			    solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
			    solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
			    '' AS tipoExamen,
			    prestacion.tubo_id AS codigoExamen,
			    detalle_solicitud_laboratorio.det_lab_codigo AS codigosPrestacion,
			    solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
			    solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
			    solicitud_laboratorio.sol_lab_usuarioAnula AS usuarioAnula,
			    solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
			    '' AS sic_id,
			    '' AS idSolicitudDalca,
			    solicitud_laboratorio.sol_lab_observacion AS observacion,
			    accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			    accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			    '' AS UsuarioIniciaIndicacion,
			    '' AS fechaIniciaIndicacion,
			    solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
			    solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
			    detalle_solicitud_laboratorio.det_lab_descripcion AS Prestacion,
			    '' AS descripcionClasificacion,
			    '' AS informe,
			    '' AS urlResultado
			FROM rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio 
			    ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN rce.estado_indicacion 
			    ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
			INNER JOIN laboratorio.prestacion 
			    ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			LEFT JOIN acceso.usuario accesoUsuarioSolicita 
			    ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica 
			    ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
			WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}' 
			AND solicitud_laboratorio.sol_lab_fechaInserta = '{$parametros['sol_lab_fechaInserta']}'  ";
			if($parametros['tubo_id'] > 0){
				$sql .=" AND prestacion.tubo_id = '{$parametros['tubo_id']}' ";
			}else{
				$sql .=" AND prestacion.tubo_id is null ";
			}
			
			$sql .="  AND solicitud_laboratorio.sol_lab_estado != 8";
		$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
		return $resultado;
	}
	
	function listarIndicacionesRCEPDF($objCon,$parametros, $eventos = 0){
		$sql="
			SELECT
				rce.solicitud_imagenologia.sol_ima_id AS sol_id,
				rce.solicitud_imagenologia.sol_ima_estado AS estado,
				rce.estado_indicacion.est_descripcion AS estadoDescripcion,
				rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
				'Solicitud Imagenologia' AS descripcion,
				'2' AS cod_descripcion,
				rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
				rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
				) AS tipoExamen,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.id_prestaciones,
					rce.detalle_solicitud_imagenologia.det_ima_codigo
				) AS codigoExamen,
				'' AS codigoPrestacion,
				rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplica,
				rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplica,
				rce.solicitud_imagenologia.sol_ima_usuarioAnula AS usuarioAnula,
				rce.solicitud_imagenologia.sol_ima_fechaAnula AS fechaAnula,
				rce.detalle_solicitud_imagenologia.SIC_id AS sic_id,
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
				rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_descripcion
				) )AS Prestacion,
				'' AS descripcionClasificacion,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					'',
					(
						SELECT
							informe.informe
						FROM
							integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
						WHERE
							informe.INTcodigo =
							(
								SELECT
									MAX(i.INTcodigo)
								FROM
									integraciones.integracion_ingrad_respuesta_api_hjnc AS i
								LEFT JOIN
									rayos.solicitud_cabecera_img_registro AS r on r.INTid_imgrad = i.INTidingrad
								WHERE
									r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
								AND
									i.INTprestacion = rayos.solicitud_imagen_camas.ID
							)
					)
				) AS informe,
			IF(
				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
				'',
				rayos.solicitud_cabecera_img_registro.url_resultado
			) AS urlResultado
			FROM
				rce.solicitud_imagenologia
			LEFT JOIN
				rce.detalle_solicitud_imagenologia
				ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
			LEFT JOIN
				rce.detalle_solicitud_imagenologia_dalca
				ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
				le.prestaciones_imagenologia
				ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.estado_indicacion
				ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
			LEFT JOIN
				acceso.usuario accesoUsuarioSolicita
				ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN
				acceso.usuario accesoUsuarioAplica
				ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT
				JOIN rayos.solicitud_imagen_camas
				ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
			LEFT JOIN
				rayos.solicitud_cabecera_img_registro
				ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
			WHERE
				rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
			AND
				(rce.solicitud_imagenologia.sol_ima_estado != 8 and rce.solicitud_imagenologia.sol_ima_estado != 6)
			GROUP BY
				sol_id

			UNION
			SELECT
			solicitud_indicaciones.sol_ind_id AS sol_id,
			solicitud_indicaciones.sol_ind_estado AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			solicitud_indicaciones.sol_ind_servicio AS servicio,
			CASE
				WHEN solicitud_indicaciones.sol_ind_servicio = 2 THEN 'Solicitud Tratamiento'
				WHEN solicitud_indicaciones.sol_ind_servicio = 4 THEN 'Solicitud Otros'
				WHEN solicitud_indicaciones.sol_ind_servicio = 6 THEN 'Solicitud Procedimiento'
				WHEN solicitud_indicaciones.sol_ind_servicio = 8 THEN 'Solicitud Transfusion'
			END AS descripcion,
			'2' AS cod_descripcion,
			solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
			solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
			'' AS tipoExamen,
			'' AS codigoExamen,
			'' AS codigoPrestacion,
			solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
			solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
			solicitud_indicaciones.sol_ind_usuarioAnula AS usuarioAnula,
			solicitud_indicaciones.sol_ind_fechaAnula AS fechaAnula,
			'' AS sic_id,
			'' AS idSolicitudDalca,
			solicitud_indicaciones.sol_ind_observacion AS observacion,
			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS UsuarioIniciaIndicacion,
			solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS fechaIniciaIndicacion,
			'' AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			upper(solicitud_indicaciones.sol_ind_descripcion) AS Prestacion,
			clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado
			FROM rce.solicitud_indicaciones
			INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
			WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND (solicitud_indicaciones.sol_ind_estado != 8 and solicitud_indicaciones.sol_ind_estado != 6)
			GROUP BY sol_id

			UNION
			
			SELECT
			    solicitud_laboratorio.sol_lab_id AS sol_id,
			    MIN(solicitud_laboratorio.sol_lab_estado) AS estadoMasBajo, -- Estado más bajo
			    MAX(estado_indicacion.est_descripcion) AS estadoDescripcion,
			    solicitud_laboratorio.sol_lab_tipo AS servicio,
			    'Solicitud Laboratorio' AS descripcion,
			    '2' AS cod_descripcion,
			    solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
			    solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
			    '' AS tipoExamen,
			    prestacion.tubo_id AS codigoExamen,
			    GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_codigo) AS codigosPrestacion,
			    solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
			    solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
			    solicitud_laboratorio.sol_lab_usuarioAnula AS usuarioAnula,
			    solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
			    '' AS sic_id,
			    '' AS idSolicitudDalca,
			    solicitud_laboratorio.sol_lab_observacion AS observacion,
			    accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			    accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			    '' AS UsuarioIniciaIndicacion,
			    '' AS fechaIniciaIndicacion,
			    solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
			    solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
			    upper(GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_descripcion)) AS prestaciones,
			    '' AS descripcionClasificacion,
			    '' AS informe,
			    '' AS urlResultado
			FROM rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio 
			    ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN rce.estado_indicacion 
			    ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
			INNER JOIN laboratorio.prestacion 
			    ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			LEFT JOIN acceso.usuario accesoUsuarioSolicita 
			    ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica 
			    ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
			WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}'  
			  AND (solicitud_laboratorio.sol_lab_estado != 8 and solicitud_laboratorio.sol_lab_estado != 6 )
			GROUP BY solicitud_laboratorio.sol_lab_fechaInserta, prestacion.tubo_id
			UNION
			SELECT
				solicitud_especialista.SESPid AS sol_id,
				solicitud_especialista.SESPestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				solicitud_especialista.SESPtipo AS servicio,
				'Solicitud Especialista' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_especialista.SESPusuario AS usuarioInserta,
				solicitud_especialista.SESPfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				solicitud_especialista.SESPusuarioAplica AS usuarioAplica,
				solicitud_especialista.SESPfechaAplicacion AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				solicitud_especialista.SESPobservacion AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(parametros_clinicos.especialidad.ESPdescripcion COLLATE utf8_general_ci) AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
			FROM rce.solicitud_especialista
			INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
			LEFT JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}'
			GROUP BY sol_id
			UNION
			SELECT
				solicitud_otros_especialidad.id_sol_otro AS sol_id,
				solicitud_otros_especialidad.estado_sol_otro AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				8 AS servicio,
				'Solicitud Especialista Otros' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_otros_especialidad.sol_otro_usuario AS usuarioInserta,
				solicitud_otros_especialidad.sol_otro_fecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(otro_especialista.descripcion_otro) AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado 
			FROM
				rce.solicitud_otros_especialidad
				INNER JOIN rce.estado_indicacion ON solicitud_otros_especialidad.estado_sol_otro = estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON solicitud_otros_especialidad.sol_otro_usuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON solicitud_otros_especialidad.sol_otro_usuarioAplica = accesoUsuarioAplica.idusuario
				LEFT JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro   
			WHERE
				solicitud_otros_especialidad.idRCE = '{$parametros['rce_id']}'
			GROUP BY
				sol_id 
	";

			if($eventos != 0){
				$sql.= "
				UNION
				SELECT
				solicitud_altaUrgencia.SAUid AS sol_id,
				solicitud_altaUrgencia.SAUestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				7 AS servicio,
				'Solicitud Alta Urgencia' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_altaUrgencia.SAUusuario AS usuarioInserta,
				solicitud_altaUrgencia.SAUfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				solicitud_altaUrgencia.SAUfechaAplica AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_altaUrgencia
				INNER JOIN rce.estado_indicacion ON solicitud_altaUrgencia.SAUestado = estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioAplica.idusuario
				WHERE solicitud_altaUrgencia.SAUidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_inicioatencion.SIAid AS sol_id,
				solicitud_inicioatencion.SIAestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Inicio Atención' AS descripcion,
				'1' AS cod_descripcion,
				solicitud_inicioatencion.SIAusuario AS usuarioInserta,
				solicitud_inicioatencion.SIAfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				solicitud_inicioatencion.SIAfechaModificacion AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_inicioatencion
				INNER JOIN rce.estado_indicacion ON solicitud_inicioatencion.SIAestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_inicioatencion.SIAusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_inicioatencion.SIAusuarioModifica = accesoUsuarioAplica.idusuario
				WHERE solicitud_inicioatencion.SIAidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_evolucion.SEVOid AS sol_id,
				solicitud_evolucion.SEVOestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Evolución' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_evolucion.SEVOusuario AS usuarioInserta,
				solicitud_evolucion.SEVOfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				'' AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_evolucion
				INNER JOIN rce.estado_indicacion ON solicitud_evolucion.SEVOestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_evolucion.SEVOusuario = accesoUsuarioSolicita.idusuario
				WHERE solicitud_evolucion.SEVOidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_especialista.SESPid AS sol_id,
				solicitud_especialista.SESPestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				5 AS servicio,
				'Solicitud Especialista' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_especialista.SESPusuario AS usuarioInserta,
				solicitud_especialista.SESPfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_especialista
				INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
				WHERE solicitud_especialista.SESPid = '{$parametros['rce_id']}'
				GROUP BY sol_id
				";
		}

		$sql.= "
		ORDER BY cod_descripcion DESC,fechaInserta DESC,descripcion DESC, sol_id DESC";
		$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
		return $resultado;
	}
	function listarIndicacionesRCE($objCon,$parametros, $eventos = 0){
		$sql="
			SELECT
				rce.solicitud_imagenologia.sol_ima_id AS sol_id,
				rce.solicitud_imagenologia.sol_ima_estado AS estado,
				rce.estado_indicacion.est_descripcion AS estadoDescripcion,
				rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
				'Solicitud Imagenologia' AS descripcion,
				'2' AS cod_descripcion,
				rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
				rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
				) AS tipoExamen,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.id_prestaciones,
					rce.detalle_solicitud_imagenologia.det_ima_codigo
				) AS codigoExamen,
				'' AS codigoPrestacion,
				rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplica,
				rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplica,
				rce.solicitud_imagenologia.sol_ima_usuarioAnula AS usuarioAnula,
				rce.solicitud_imagenologia.sol_ima_fechaAnula AS fechaAnula,
				rce.detalle_solicitud_imagenologia.SIC_id AS sic_id,
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
				rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_descripcion
				) )AS Prestacion,
				'' AS descripcionClasificacion,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					'',
					(
						SELECT
							informe.informe
						FROM
							integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
						WHERE
							informe.INTcodigo =
							(
								SELECT
									MAX(i.INTcodigo)
								FROM
									integraciones.integracion_ingrad_respuesta_api_hjnc AS i
								LEFT JOIN
									rayos.solicitud_cabecera_img_registro AS r on r.INTid_imgrad = i.INTidingrad
								WHERE
									r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
								AND
									i.INTprestacion = rayos.solicitud_imagen_camas.ID
							)
					)
				) AS informe,
			IF(
				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
				'',
				rayos.solicitud_cabecera_img_registro.url_resultado
			) AS urlResultado
			FROM
				rce.solicitud_imagenologia
			LEFT JOIN
				rce.detalle_solicitud_imagenologia
				ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
			LEFT JOIN
				rce.detalle_solicitud_imagenologia_dalca
				ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
				le.prestaciones_imagenologia
				ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.estado_indicacion
				ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
			LEFT JOIN
				acceso.usuario accesoUsuarioSolicita
				ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN
				acceso.usuario accesoUsuarioAplica
				ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT
				JOIN rayos.solicitud_imagen_camas
				ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
			LEFT JOIN
				rayos.solicitud_cabecera_img_registro
				ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
			WHERE
				rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
			AND
				rce.solicitud_imagenologia.sol_ima_estado != 8
			GROUP BY
				sol_id

			UNION
			SELECT
			solicitud_indicaciones.sol_ind_id AS sol_id,
			solicitud_indicaciones.sol_ind_estado AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			solicitud_indicaciones.sol_ind_servicio AS servicio,
			CASE
				WHEN solicitud_indicaciones.sol_ind_servicio = 2 THEN 'Solicitud Tratamiento'
				WHEN solicitud_indicaciones.sol_ind_servicio = 4 THEN 'Solicitud Otros'
				WHEN solicitud_indicaciones.sol_ind_servicio = 6 THEN 'Solicitud Procedimiento'
			END AS descripcion,
			'2' AS cod_descripcion,
			solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
			solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
			'' AS tipoExamen,
			'' AS codigoExamen,
			'' AS codigoPrestacion,
			solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
			solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
			solicitud_indicaciones.sol_ind_usuarioAnula AS usuarioAnula,
			solicitud_indicaciones.sol_ind_fechaAnula AS fechaAnula,
			'' AS sic_id,
			'' AS idSolicitudDalca,
			solicitud_indicaciones.sol_ind_observacion AS observacion,
			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS UsuarioIniciaIndicacion,
			solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS fechaIniciaIndicacion,
			'' AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			upper(solicitud_indicaciones.sol_ind_descripcion) AS Prestacion,
			clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado
			FROM rce.solicitud_indicaciones
			INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
			WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND solicitud_indicaciones.sol_ind_estado != 8
			GROUP BY sol_id

			UNION
			
			SELECT
			    solicitud_laboratorio.sol_lab_id AS sol_id,
			    MIN(solicitud_laboratorio.sol_lab_estado) AS estadoMasBajo, -- Estado más bajo
			    MAX(estado_indicacion.est_descripcion) AS estadoDescripcion,
			    solicitud_laboratorio.sol_lab_tipo AS servicio,
			    'Solicitud Laboratorio' AS descripcion,
			    '2' AS cod_descripcion,
			    solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
			    solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
			    '' AS tipoExamen,
			    prestacion.tubo_id AS codigoExamen,
			    GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_codigo) AS codigosPrestacion,
			    solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
			    solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
			    solicitud_laboratorio.sol_lab_usuarioAnula AS usuarioAnula,
			    solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
			    '' AS sic_id,
			    '' AS idSolicitudDalca,
			    solicitud_laboratorio.sol_lab_observacion AS observacion,
			    accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			    accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			    '' AS UsuarioIniciaIndicacion,
			    '' AS fechaIniciaIndicacion,
			    solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
			    solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
			    upper(GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_descripcion)) AS prestaciones,
			    '' AS descripcionClasificacion,
			    '' AS informe,
			    '' AS urlResultado
			FROM rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio 
			    ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN rce.estado_indicacion 
			    ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
			INNER JOIN laboratorio.prestacion 
			    ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			LEFT JOIN acceso.usuario accesoUsuarioSolicita 
			    ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica 
			    ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
			WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}'  
			  AND solicitud_laboratorio.sol_lab_estado != 8
			GROUP BY solicitud_laboratorio.sol_lab_fechaInserta, prestacion.tubo_id
			UNION
			SELECT
				solicitud_especialista.SESPid AS sol_id,
				solicitud_especialista.SESPestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				solicitud_especialista.SESPtipo AS servicio,
				'Solicitud Especialista' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_especialista.SESPusuario AS usuarioInserta,
				solicitud_especialista.SESPfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				solicitud_especialista.SESPusuarioAplica AS usuarioAplica,
				solicitud_especialista.SESPfechaAplicacion AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				solicitud_especialista.SESPobservacion AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(parametros_clinicos.especialidad.ESPdescripcion COLLATE utf8_general_ci) AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
			FROM rce.solicitud_especialista
			INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
			LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
			LEFT JOIN parametros_clinicos.especialidad ON rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}'
			GROUP BY sol_id
			UNION
			SELECT
				solicitud_otros_especialidad.id_sol_otro AS sol_id,
				solicitud_otros_especialidad.estado_sol_otro AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				8 AS servicio,
				'Solicitud Especialista Otros' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_otros_especialidad.sol_otro_usuario AS usuarioInserta,
				solicitud_otros_especialidad.sol_otro_fecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(otro_especialista.descripcion_otro) AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado 
			FROM
				rce.solicitud_otros_especialidad
				INNER JOIN rce.estado_indicacion ON solicitud_otros_especialidad.estado_sol_otro = estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON solicitud_otros_especialidad.sol_otro_usuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON solicitud_otros_especialidad.sol_otro_usuarioAplica = accesoUsuarioAplica.idusuario
				LEFT JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro   
			WHERE
				solicitud_otros_especialidad.idRCE = '{$parametros['rce_id']}'
			GROUP BY
				sol_id 
	";

			if($eventos != 0){
				$sql.= "
				UNION
				SELECT
				solicitud_altaUrgencia.SAUid AS sol_id,
				solicitud_altaUrgencia.SAUestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				7 AS servicio,
				'Solicitud Alta Urgencia' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_altaUrgencia.SAUusuario AS usuarioInserta,
				solicitud_altaUrgencia.SAUfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				solicitud_altaUrgencia.SAUfechaAplica AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_altaUrgencia
				INNER JOIN rce.estado_indicacion ON solicitud_altaUrgencia.SAUestado = estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_altaurgencia.SAUusuario = accesoUsuarioAplica.idusuario
				WHERE solicitud_altaUrgencia.SAUidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_inicioatencion.SIAid AS sol_id,
				solicitud_inicioatencion.SIAestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Inicio Atención' AS descripcion,
				'1' AS cod_descripcion,
				solicitud_inicioatencion.SIAusuario AS usuarioInserta,
				solicitud_inicioatencion.SIAfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				solicitud_inicioatencion.SIAfechaModificacion AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_inicioatencion
				INNER JOIN rce.estado_indicacion ON solicitud_inicioatencion.SIAestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_inicioatencion.SIAusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_inicioatencion.SIAusuarioModifica = accesoUsuarioAplica.idusuario
				WHERE solicitud_inicioatencion.SIAidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_evolucion.SEVOid AS sol_id,
				solicitud_evolucion.SEVOestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Evolución' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_evolucion.SEVOusuario AS usuarioInserta,
				solicitud_evolucion.SEVOfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				'' AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_evolucion
				INNER JOIN rce.estado_indicacion ON solicitud_evolucion.SEVOestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_evolucion.SEVOusuario = accesoUsuarioSolicita.idusuario
				WHERE solicitud_evolucion.SEVOidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_especialista.SESPid AS sol_id,
				solicitud_especialista.SESPestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				5 AS servicio,
				'Solicitud Especialista' AS descripcion,
				'2' AS cod_descripcion,
				solicitud_especialista.SESPusuario AS usuarioInserta,
				solicitud_especialista.SESPfecha AS fechaInserta,
				'' AS tipoExamen,
				'' AS codigoExamen,
				'' AS codigoPrestacion,
				'' AS usuarioAplica,
				'' AS fechaAplica,
				'' AS usuarioAnula,
				'' AS fechaAnula,
				'' AS sic_id,
				'' AS idSolicitudDalca,
				'' AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_especialista
				INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
				LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
				WHERE solicitud_especialista.SESPid = '{$parametros['rce_id']}'
				GROUP BY sol_id
				";
		}

		$sql.= "
		ORDER BY cod_descripcion DESC,fechaInserta DESC,descripcion DESC, sol_id DESC";
		$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
		return $resultado;
	}
	function listarIndicacionesMedicas($objCon,$parametros, $eventos = 0){
		$sql=" select * from (
			SELECT
			    rce.solicitud_imagenologia.sol_ima_id AS sol_id,
			    rce.solicitud_imagenologia.sol_ima_estado AS estado,
			    rce.estado_indicacion.est_descripcion AS estadoDescripcion,
			    rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
			    'Solicitud Imagenologia' AS descripcion,
			    rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
			    rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
			    IF(
			        DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28',
			        le.prestaciones_imagenologia.id_prestaciones,
			        rce.detalle_solicitud_imagenologia.det_ima_codigo
			    ) AS codigoExamen,
			    rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
			    '' AS usuarioTomaMuestra,
			    '' AS fechaTomaMuestra,
			    UPPER(
			        IF(
			            DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28',
			            le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
			            rce.detalle_solicitud_imagenologia.det_ima_descripcion
			        )
			    ) AS Prestacion,
			    '' AS descripcionClasificacion,
			    IF(
			        DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28',
			        '',
			        (
			            SELECT informe.informe
			            FROM integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
			            WHERE informe.INTcodigo = (
			                SELECT MAX(i.INTcodigo)
			                FROM integraciones.integracion_ingrad_respuesta_api_hjnc AS i
			                LEFT JOIN rayos.solicitud_cabecera_img_registro AS r 
			                ON r.INTid_imgrad = i.INTidingrad
			                WHERE r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
			                AND i.INTprestacion = rayos.solicitud_imagen_camas.ID
			            )
			        )
			    ) AS informe,
			    IF(
			        DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28',
			        '',
			        rayos.solicitud_cabecera_img_registro.url_resultado
			    ) AS urlResultado
			FROM
			    rce.solicitud_imagenologia
			LEFT JOIN
			    rce.detalle_solicitud_imagenologia
			    ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
			LEFT JOIN
			    rce.detalle_solicitud_imagenologia_dalca
			    ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
			    le.prestaciones_imagenologia
			    ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
			    rce.estado_indicacion
			    ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
			LEFT JOIN
			    rayos.solicitud_imagen_camas
			    ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
			LEFT JOIN
			    rayos.solicitud_cabecera_img_registro
			    ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
			WHERE
			    rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
			AND
			    rce.solicitud_imagenologia.sol_ima_estado != 8
			GROUP BY
			    sol_id

			UNION
			SELECT
			solicitud_indicaciones.sol_ind_id AS sol_id,
			solicitud_indicaciones.sol_ind_estado AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			solicitud_indicaciones.sol_ind_servicio AS servicio,
			CASE
				WHEN solicitud_indicaciones.sol_ind_servicio = 2 THEN 'Solicitud Tratamiento'
				WHEN solicitud_indicaciones.sol_ind_servicio = 4 THEN 'Solicitud Otros'
				WHEN solicitud_indicaciones.sol_ind_servicio = 6 THEN 'Solicitud Procedimiento'
				WHEN solicitud_indicaciones.sol_ind_servicio = 8 THEN 'Solicitud Transfusion'
			END AS descripcion,
			solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
			solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
			'' AS codigoExamen,
			'' AS idSolicitudDalca,
			
			'' AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			upper(solicitud_indicaciones.sol_ind_descripcion) AS Prestacion,
			clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado
			FROM rce.solicitud_indicaciones
			INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
			
			LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
			WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND solicitud_indicaciones.sol_ind_estado != 8
			GROUP BY sol_id

			UNION
			
			SELECT
			    solicitud_laboratorio.sol_lab_id AS sol_id,
			    MIN(solicitud_laboratorio.sol_lab_estado) AS estadoMasBajo, 
			    MAX(estado_indicacion.est_descripcion) AS estadoDescripcion,
			    solicitud_laboratorio.sol_lab_tipo AS servicio,
			    'Solicitud Laboratorio' AS descripcion,
			    solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
			    solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
			    prestacion.tubo_id AS codigoExamen,
			    '' AS idSolicitudDalca,
			   
			    solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
			    solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
			    upper(GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_descripcion)) AS prestaciones,
			    '' AS descripcionClasificacion,
			    '' AS informe,
			    '' AS urlResultado
			FROM rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio 
			    ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN rce.estado_indicacion 
			    ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
			INNER JOIN laboratorio.prestacion 
			    ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			LEFT JOIN acceso.usuario accesoUsuarioSolicita 
			    ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario accesoUsuarioAplica 
			    ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
			WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}'  
			  AND solicitud_laboratorio.sol_lab_estado != 8
			GROUP BY solicitud_laboratorio.sol_lab_fechaInserta, prestacion.tubo_id
			UNION
			SELECT 
			    solicitud_especialista.SESPid AS sol_id,
			    solicitud_especialista.SESPestado AS estado,
			    estado_indicacion.est_descripcion AS estadoDescripcion,
			    solicitud_especialista.SESPtipo AS servicio,
			    'Solicitud Especialista' AS descripcion,
			    solicitud_especialista.SESPusuario AS usuarioInserta,
			    solicitud_especialista.SESPfecha AS fechaInserta,
			    '' AS codigoExamen,
			    '' AS idSolicitudDalca,
			    '' AS usuarioTomaMuestra,
			    '' AS fechaTomaMuestra,

			    UPPER(
			        CASE 
			            WHEN solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion 
			            ELSE otro_especialista.descripcion_otro 
			        END COLLATE utf8_general_ci
			    ) AS Prestacion,

			    '' AS descripcionClasificacion,
			    '' AS informe,
			    '' AS urlResultado

			FROM rce.solicitud_especialista
			INNER JOIN rce.estado_indicacion 
			    ON solicitud_especialista.SESPestado = estado_indicacion.est_id
			LEFT JOIN parametros_clinicos.especialidad 
			    ON solicitud_especialista.SESPfuente = 'P' 
			    AND solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
			LEFT JOIN rce.otro_especialista 
			    ON solicitud_especialista.SESPfuente = 'O' 
			    AND solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro

			WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}'

			GROUP BY sol_id
			UNION
			SELECT
				solicitud_otros_especialidad.id_sol_otro AS sol_id,
				solicitud_otros_especialidad.estado_sol_otro AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				8 AS servicio,
				'Solicitud Especialista Otros' AS descripcion,
				solicitud_otros_especialidad.sol_otro_usuario AS usuarioInserta,
				solicitud_otros_especialidad.sol_otro_fecha AS fechaInserta,
				'' AS codigoExamen,
				'' AS idSolicitudDalca,
				
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				upper(otro_especialista.descripcion_otro) AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado 
			FROM
				rce.solicitud_otros_especialidad
				INNER JOIN rce.estado_indicacion ON solicitud_otros_especialidad.estado_sol_otro = estado_indicacion.est_id
				
				LEFT JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro   
			WHERE
				solicitud_otros_especialidad.idRCE = '{$parametros['rce_id']}'
			GROUP BY
				sol_id 
	";

			if($eventos != 0){
				$sql.= "
				UNION
				SELECT
				solicitud_altaUrgencia.SAUid AS sol_id,
				solicitud_altaUrgencia.SAUestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				7 AS servicio,
				'Solicitud Alta Urgencia' AS descripcion,
				solicitud_altaUrgencia.SAUusuario AS usuarioInserta,
				solicitud_altaUrgencia.SAUfecha AS fechaInserta,
				'' AS codigoExamen,
				'' AS idSolicitudDalca,
				
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_altaUrgencia
				INNER JOIN rce.estado_indicacion ON solicitud_altaUrgencia.SAUestado = estado_indicacion.est_id
				
				WHERE solicitud_altaUrgencia.SAUidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_inicioatencion.SIAid AS sol_id,
				solicitud_inicioatencion.SIAestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Inicio Atención' AS descripcion,
				solicitud_inicioatencion.SIAusuario AS usuarioInserta,
				solicitud_inicioatencion.SIAfecha AS fechaInserta,
				'' AS codigoExamen,
				'' AS idSolicitudDalca,
				
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_inicioatencion
				INNER JOIN rce.estado_indicacion ON solicitud_inicioatencion.SIAestado= estado_indicacion.est_id
				
				WHERE solicitud_inicioatencion.SIAidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_evolucion.SEVOid AS sol_id,
				solicitud_evolucion.SEVOestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				'' AS servicio,
				'Solicitud Evolución' AS descripcion,
				solicitud_evolucion.SEVOusuario AS usuarioInserta,
				solicitud_evolucion.SEVOfecha AS fechaInserta,
				'' AS codigoExamen,
				'' AS idSolicitudDalca,
				
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_evolucion
				INNER JOIN rce.estado_indicacion ON solicitud_evolucion.SEVOestado= estado_indicacion.est_id
				LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_evolucion.SEVOusuario = accesoUsuarioSolicita.idusuario
				WHERE solicitud_evolucion.SEVOidRCE = '{$parametros['rce_id']}'
				GROUP BY sol_id

				UNION
				SELECT
				solicitud_especialista.SESPid AS sol_id,
				solicitud_especialista.SESPestado AS estado,
				estado_indicacion.est_descripcion AS estadoDescripcion,
				5 AS servicio,
				'Solicitud Especialista' AS descripcion,
				solicitud_especialista.SESPusuario AS usuarioInserta,
				solicitud_especialista.SESPfecha AS fechaInserta,
				'' AS codigoExamen,
				'' AS idSolicitudDalca,
				
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				'------------' AS Prestacion,
				'' AS descripcionClasificacion,
				'' AS informe,
				'' AS urlResultado
				FROM rce.solicitud_especialista
				INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
				
				WHERE solicitud_especialista.SESPid = '{$parametros['rce_id']}'
				GROUP BY sol_id
				";
		}

		 $sql.= "
		ORDER BY fechaInserta DESC,descripcion DESC, sol_id DESC ) as datos
		 ";
		if($parametros['frm_estados'] > 0 ){
			$sql.= " WHERE estado = '{$parametros['frm_estados']}' ";
		}
		$resultado = $objCon->consultaSQL($sql,"Error al listar Indicaciones");
		return $resultado;
	}

	function listarIndicacionesRCE_enf2($objCon,$parametros){
		$sql="
			SELECT * FROM ( SELECT
				rce.solicitud_imagenologia.sol_ima_id AS sol_id,
				'1' as tipo_solicitud_cabecera,
				rce.solicitud_imagenologia.sol_ima_estado AS estado,
				rce.estado_indicacion.est_descripcion AS estadoDescripcion,
				rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
				'Solicitud Imagenologia' AS descripcion,
				rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
				rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
				) AS tipoExamen,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.id_prestaciones,
					rce.detalle_solicitud_imagenologia.det_ima_codigo
				) AS codigoExamen,
				'' AS codigoPrestacion,
				rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplica,
				rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplica,
				rce.solicitud_imagenologia.sol_ima_usuarioAnula AS usuarioAnula,
				rce.solicitud_imagenologia.sol_ima_fechaAnula AS fechaAnula,
				rce.detalle_solicitud_imagenologia.SIC_id AS sic_id,
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
				rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_descripcion
				) AS Prestacion,
				'' AS descripcionClasificacion,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					'',
					(
						SELECT
							informe.informe
						FROM
							integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
						WHERE
							informe.INTcodigo =
							(
								SELECT
									MAX(i.INTcodigo)
								FROM
									integraciones.integracion_ingrad_respuesta_api_hjnc AS i
								LEFT JOIN
									rayos.solicitud_cabecera_img_registro AS r on r.INTid_imgrad = i.INTidingrad
								WHERE
									r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
								AND
									i.INTprestacion = rayos.solicitud_imagen_camas.ID
							)
					)
				) AS informe,
			IF(
				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
				'',
				rayos.solicitud_cabecera_img_registro.url_resultado
			) AS urlResultado
			FROM
				rce.solicitud_imagenologia
			LEFT JOIN
				rce.detalle_solicitud_imagenologia
				ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
			LEFT JOIN
				rce.detalle_solicitud_imagenologia_dalca
				ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
				le.prestaciones_imagenologia
				ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.estado_indicacion
				ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
			LEFT JOIN
				acceso.usuario accesoUsuarioSolicita
				ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN
				acceso.usuario accesoUsuarioAplica
				ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT
				JOIN rayos.solicitud_imagen_camas
				ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
			LEFT JOIN
				rayos.solicitud_cabecera_img_registro
				ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
			WHERE
				rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
			AND
				rce.solicitud_imagenologia.sol_ima_estado != 8
			GROUP BY
				sol_id

		UNION
		SELECT
		solicitud_indicaciones.sol_ind_id AS sol_id,
		solicitud_indicaciones.sol_ind_servicio as tipo_solicitud_cabecera,
		solicitud_indicaciones.sol_ind_estado AS estado,
		estado_indicacion.est_descripcion AS estadoDescripcion,
		solicitud_indicaciones.sol_ind_servicio AS servicio,
		'Solicitud Tratamiento' AS descripcion,
		solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
		solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
		'' AS tipoExamen,
		'' AS codigoExamen,
		'' AS codigoPrestacion,
		solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
		solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
		solicitud_indicaciones.sol_ind_usuarioAnula AS usuarioAnula,
		solicitud_indicaciones.sol_ind_fechaAnula AS fechaAnula,
		'' AS sic_id,
		'' AS idSolicitudDalca,
		solicitud_indicaciones.sol_ind_observacion AS observacion,
		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
		solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS UsuarioIniciaIndicacion,
		solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS fechaIniciaIndicacion,
		'' AS usuarioTomaMuestra,
		'' AS fechaTomaMuestra,
		solicitud_indicaciones.sol_ind_descripcion AS Prestacion,
		clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
		'' AS informe,
		'' AS urlResultado
		FROM rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
		LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
		WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND solicitud_indicaciones.sol_ind_estado != 8

		UNION
		SELECT
		solicitud_laboratorio.sol_lab_id AS sol_id,
				'3' as tipo_solicitud_cabecera,
		MIN(solicitud_laboratorio.sol_lab_estado) AS estadoMasBajo, 
		MAX(estado_indicacion.est_descripcion) AS estadoDescripcion,
		solicitud_laboratorio.sol_lab_tipo AS servicio,
		'Solicitud Laboratorio' AS descripcion,
		solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
		solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
		'' AS tipoExamen,
		prestacion.tubo_id AS codigoExamen,
		GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_codigo) AS codigosPrestacion,
		solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
		solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
		solicitud_laboratorio.sol_lab_usuarioAnula AS usuarioAnula,
		solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
		'' AS sic_id,
		'' AS idSolicitudDalca,
		solicitud_laboratorio.sol_lab_observacion AS observacion,
		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
		'' AS UsuarioIniciaIndicacion,
		'' AS fechaIniciaIndicacion,
		solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
		MAX(solicitud_laboratorio.sol_lab_fechaTomaMuestra) AS fechaTomaMuestra,
		GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_descripcion) AS prestaciones,
		'' AS descripcionClasificacion,
		'' AS informe,
		'' AS urlResultado
		FROM rce.solicitud_laboratorio
		INNER JOIN rce.detalle_solicitud_laboratorio 
		    ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
		INNER JOIN rce.estado_indicacion 
		    ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
		INNER JOIN laboratorio.prestacion 
		    ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
		LEFT JOIN acceso.usuario accesoUsuarioSolicita 
		    ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesoUsuarioAplica 
		    ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
		WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}'  
		  AND solicitud_laboratorio.sol_lab_estado != 8
		GROUP BY solicitud_laboratorio.sol_lab_fechaInserta, prestacion.tubo_id

		UNION
		SELECT
			solicitud_especialista.SESPid AS sol_id,
				'5' as tipo_solicitud_cabecera,
			solicitud_especialista.SESPestado AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			solicitud_especialista.SESPtipo AS servicio,
			'Solicitud Especialista' AS descripcion,
			solicitud_especialista.SESPusuario AS usuarioInserta,
			solicitud_especialista.SESPfecha AS fechaInserta,
			'' AS tipoExamen,
			'' AS codigoExamen,
			'' AS codigoPrestacion,
			solicitud_especialista.SESPusuarioAplica AS usuarioAplica,
			solicitud_especialista.SESPfechaAplicacion AS fechaAplica,
			'' AS usuarioAnula,
			'' AS fechaAnula,
			'' AS sic_id,
			'' AS idSolicitudDalca,
			solicitud_especialista.SESPobservacion AS observacion,
			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			solicitud_especialista.SESPusuarioEspecialistaDeLlamado AS UsuarioIniciaIndicacion,
			'' AS fechaIniciaIndicacion,
			solicitud_especialista.SESPusuarioGestionRealizada  AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			'' AS Prestacion,
			'' AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado
		FROM rce.solicitud_especialista
		INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
		WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}'
		UNION
		SELECT
			solicitud_otros_especialidad.id_sol_otro AS sol_id,
				'5' as tipo_solicitud_cabecera,
			solicitud_otros_especialidad.estado_sol_otro AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			8 AS servicio,
			'Solicitud Especialista Otros' AS descripcion,
			solicitud_otros_especialidad.sol_otro_usuario AS usuarioInserta,
			solicitud_otros_especialidad.sol_otro_fecha AS fechaInserta,
			'' AS tipoExamen,
			'' AS codigoExamen,
			'' AS codigoPrestacion,
			solicitud_otros_especialidad.sol_otro_usuarioAplica AS usuarioAplica,
			solicitud_otros_especialidad.sol_otro_usuarioAplica_fecha AS fechaAplica,
			'' AS usuarioAnula,
			'' AS fechaAnula,
			'' AS sic_id,
			'' AS idSolicitudDalca,
			solicitud_otros_especialidad.sol_otro_observacion AS observacion,
			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			'' AS UsuarioIniciaIndicacion,
			'' AS fechaIniciaIndicacion,
			'' AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			upper(otro_especialista.descripcion_otro) AS Prestacion,
			'' AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado 
		FROM
			rce.solicitud_otros_especialidad
			INNER JOIN rce.estado_indicacion ON solicitud_otros_especialidad.estado_sol_otro = estado_indicacion.est_id
			LEFT JOIN acceso.usuario as accesoUsuarioSolicita ON solicitud_otros_especialidad.sol_otro_usuario = accesoUsuarioSolicita.idusuario
			LEFT JOIN acceso.usuario as accesoUsuarioAplica ON solicitud_otros_especialidad.sol_otro_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT JOIN rce.otro_especialista ON solicitud_otros_especialidad.id_otro = otro_especialista.id_otro   
		WHERE
			solicitud_otros_especialidad.idRCE = '{$parametros['rce_id']}'
				 ";

		  $sql.= "
			ORDER BY fechaInserta DESC, descripcion DESC, sol_id DESC ) AS datos
		";
		if($parametros['frm_aplicados'] == 'S' ){
			$sql.= " WHERE usuarioAplica != ''  ";
		}else if($parametros['frm_aplicados'] == 'N' ){
			$sql.= " WHERE usuarioAplica is null ";
		}
		// echo $sql;
		return $objCon->consultaSQL($sql,"Error al listar Indicaciones");
	}

	function listarIndicacionesRCE_enf($objCon,$parametros){
		$sql="
			SELECT
				rce.solicitud_imagenologia.sol_ima_id AS sol_id,
				rce.solicitud_imagenologia.sol_ima_estado AS estado,
				rce.estado_indicacion.est_descripcion AS estadoDescripcion,
				rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
				'Solicitud Imagenologia' AS descripcion,
				rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
				rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
				) AS tipoExamen,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.id_prestaciones,
					rce.detalle_solicitud_imagenologia.det_ima_codigo
				) AS codigoExamen,
				'' AS codigoPrestacion,
				rce.solicitud_imagenologia.sol_ima_usuarioAplica AS usuarioAplica,
				rce.solicitud_imagenologia.sol_ima_fechaAplica AS fechaAplica,
				rce.solicitud_imagenologia.sol_ima_usuarioAnula AS usuarioAnula,
				rce.solicitud_imagenologia.sol_ima_fechaAnula AS fechaAnula,
				rce.detalle_solicitud_imagenologia.SIC_id AS sic_id,
				rce.detalle_solicitud_imagenologia_dalca.idSolicitudDalca AS idSolicitudDalca,
				rce.solicitud_imagenologia.sol_ima_obsAplica AS observacion,
				accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
				accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
				'' AS UsuarioIniciaIndicacion,
				'' AS fechaIniciaIndicacion,
				'' AS usuarioTomaMuestra,
				'' AS fechaTomaMuestra,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					le.prestaciones_imagenologia.examen COLLATE utf8_general_ci,
					rce.detalle_solicitud_imagenologia.det_ima_descripcion
				) AS Prestacion,
				'' AS descripcionClasificacion,
				IF(
					DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
					'',
					(
						SELECT
							informe.informe
						FROM
							integraciones.integracion_ingrad_respuesta_api_hjnc AS informe
						WHERE
							informe.INTcodigo =
							(
								SELECT
									MAX(i.INTcodigo)
								FROM
									integraciones.integracion_ingrad_respuesta_api_hjnc AS i
								LEFT JOIN
									rayos.solicitud_cabecera_img_registro AS r on r.INTid_imgrad = i.INTidingrad
								WHERE
									r.INTid_imgrad = rayos.solicitud_cabecera_img_registro.INTid_imgrad
								AND
									i.INTprestacion = rayos.solicitud_imagen_camas.ID
							)
					)
				) AS informe,
			IF(
				DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta)>= '2024-05-28',
				'',
				rayos.solicitud_cabecera_img_registro.url_resultado
			) AS urlResultado
			FROM
				rce.solicitud_imagenologia
			LEFT JOIN
				rce.detalle_solicitud_imagenologia
				ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
			LEFT JOIN
				rce.detalle_solicitud_imagenologia_dalca
				ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN
				le.prestaciones_imagenologia
				ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.estado_indicacion
				ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
			LEFT JOIN
				acceso.usuario accesoUsuarioSolicita
				ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
			LEFT JOIN
				acceso.usuario accesoUsuarioAplica
				ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
			LEFT
				JOIN rayos.solicitud_imagen_camas
				ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
			LEFT JOIN
				rayos.solicitud_cabecera_img_registro
				ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro
			WHERE
				rce.solicitud_imagenologia.regId = '{$parametros['rce_id']}'
			AND
				rce.solicitud_imagenologia.sol_ima_estado != 8
			GROUP BY
				sol_id

		UNION
		SELECT
		solicitud_indicaciones.sol_ind_id AS sol_id,
		solicitud_indicaciones.sol_ind_estado AS estado,
		estado_indicacion.est_descripcion AS estadoDescripcion,
		solicitud_indicaciones.sol_ind_servicio AS servicio,
		'Solicitud Tratamiento' AS descripcion,
		solicitud_indicaciones.sol_ind_usuarioInserta AS usuarioInserta,
		solicitud_indicaciones.sol_ind_fechaInserta AS fechaInserta,
		'' AS tipoExamen,
		'' AS codigoExamen,
		'' AS codigoPrestacion,
		solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
		solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
		solicitud_indicaciones.sol_ind_usuarioAnula AS usuarioAnula,
		solicitud_indicaciones.sol_ind_fechaAnula AS fechaAnula,
		'' AS sic_id,
		'' AS idSolicitudDalca,
		solicitud_indicaciones.sol_ind_observacion AS observacion,
		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
		solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS UsuarioIniciaIndicacion,
		solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS fechaIniciaIndicacion,
		'' AS usuarioTomaMuestra,
		'' AS fechaTomaMuestra,
		solicitud_indicaciones.sol_ind_descripcion AS Prestacion,
		clasificacion_tratamiento.descripcionClasificacion AS descripcionClasificacion,
		'' AS informe,
		'' AS urlResultado
		FROM rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
		LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
		WHERE solicitud_indicaciones.regId = '{$parametros['rce_id']}' AND solicitud_indicaciones.sol_ind_estado != 8

		UNION
		SELECT
		solicitud_laboratorio.sol_lab_id AS sol_id,
		solicitud_laboratorio.sol_lab_estado AS estado,
		estado_indicacion.est_descripcion AS estadoDescripcion,
		solicitud_laboratorio.sol_lab_tipo AS servicio,
		'Solicitud Laboratorio' AS descripcion,
		solicitud_laboratorio.sol_lab_usuarioInserta AS usuarioInserta,
		solicitud_laboratorio.sol_lab_fechaInserta AS fechaInserta,
		'' AS tipoExamen,
		'' AS codigoExamen,
		detalle_solicitud_laboratorio.det_lab_codigo AS codigoPrestacion,
		solicitud_laboratorio.sol_lab_usuarioAplica AS usuarioAplica,
		solicitud_laboratorio.sol_lab_fechaAplica AS fechaAplica,
		solicitud_laboratorio.sol_lab_usuarioAnula AS usuarioAnula,
		solicitud_laboratorio.sol_lab_fechaAnula AS fechaAnula,
		'' AS sic_id,
		'' AS idSolicitudDalca,
		solicitud_laboratorio.sol_lab_observacion AS observacion,
		accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
		accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
		'' AS UsuarioIniciaIndicacion,
		'' AS fechaIniciaIndicacion,
		solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
		solicitud_laboratorio.sol_lab_fechaTomaMuestra AS fechaTomaMuestra,
		detalle_solicitud_laboratorio.det_lab_descripcion AS Prestacion,
		'' AS descripcionClasificacion,
		'' AS informe,
		'' AS urlResultado
		FROM rce.solicitud_laboratorio
		INNER JOIN rce.detalle_solicitud_laboratorio ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
		INNER JOIN rce.estado_indicacion ON solicitud_laboratorio.sol_lab_estado= estado_indicacion.est_id
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario
		WHERE solicitud_laboratorio.regId = '{$parametros['rce_id']}' AND solicitud_laboratorio.sol_lab_estado != 8

		UNION
		SELECT
			solicitud_especialista.SESPid AS sol_id,
			solicitud_especialista.SESPestado AS estado,
			estado_indicacion.est_descripcion AS estadoDescripcion,
			solicitud_especialista.SESPtipo AS servicio,
			'Solicitud Especialista' AS descripcion,
			solicitud_especialista.SESPusuario AS usuarioInserta,
			solicitud_especialista.SESPfecha AS fechaInserta,
			'' AS tipoExamen,
			'' AS codigoExamen,
			'' AS codigoPrestacion,
			solicitud_especialista.SESPusuarioAplica AS usuarioAplica,
			solicitud_especialista.SESPfechaAplicacion AS fechaAplica,
			'' AS usuarioAnula,
			'' AS fechaAnula,
			'' AS sic_id,
			'' AS idSolicitudDalca,
			solicitud_especialista.SESPobservacion AS observacion,
			accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			accesoUsuarioAplica.nombreusuario AS nombreUsuarioAplica,
			solicitud_especialista.SESPusuarioEspecialistaDeLlamado AS UsuarioIniciaIndicacion,
			'' AS fechaIniciaIndicacion,
			solicitud_especialista.SESPusuarioGestionRealizada  AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			'' AS Prestacion,
			'' AS descripcionClasificacion,
			'' AS informe,
			'' AS urlResultado
		FROM rce.solicitud_especialista
		INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
		WHERE solicitud_especialista.SESPidRCE = '{$parametros['rce_id']}' ";

		$sql.= "
			ORDER BY fechaInserta DESC, descripcion DESC, sol_id DESC
		";

		return $objCon->consultaSQL($sql,"Error al listar Indicaciones");
	}



	function listarIndicaciones($objCon,$parametros){
		$sql="SELECT
		solicitud_indicaciones.sol_ind_id,
		solicitud_indicaciones.regId,
		solicitud_indicaciones.id_solicitud_transfusion,
		tipo_indicaciones.ser_descripcion,
		estado_indicacion.est_descripcion,
		solicitud_indicaciones.sol_ind_estado,
		solicitud_indicaciones.sol_ind_descripcion AS descripcion,
		solicitud_indicaciones.sol_ind_usuarioInserta,
		solicitud_indicaciones.sol_ind_fechaInserta,
		solicitud_indicaciones.sol_ind_usuarioAplica AS usuarioAplica,
		solicitud_indicaciones.sol_ind_fechaAplica AS fechaAplica,
		solicitud_indicaciones.sol_ind_observacion AS observacion,
		solicitud_indicaciones.sol_ind_servicio AS Tipo,
		clasificacion_tratamiento.descripcionClasificacion
		FROM
		rce.solicitud_indicaciones
		INNER JOIN rce.tipo_indicaciones ON tipo_indicaciones.ser_codigo = solicitud_indicaciones.sol_ind_servicio
		INNER JOIN rce.estado_indicacion ON solicitud_indicaciones.sol_ind_estado = estado_indicacion.est_id
		LEFT JOIN  rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
		WHERE solicitud_indicaciones.sol_ind_id = {$parametros['solicitud_id']}";

		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function listarServiciosIndicaciones($objCon){
		$sql="SELECT ser_codigo,ser_descripcion
		FROM rce.tipo_indicaciones";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function editarSolicitudIndicaciones($objCon,$parametros){
		$condicion = '';
		$sql="UPDATE rce.solicitud_indicaciones";
		if(isset($parametros['estado_indicacion'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_estado = '{$parametros['estado_indicacion']}'";
		}
		if(isset($parametros['observacion_aplica'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_observacion = '{$parametros['observacion_aplica']}'";
		}
		if(isset($parametros['sol_ind_usuarioIniciaIndicacion_form'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_usuarioIniciaIndicacion = '{$parametros['sol_ind_usuarioIniciaIndicacion_form']}', sol_ind_fechaIniciaIndicacion = NOW()";
		}
		if(isset($parametros['usuario_Aplica'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_usuarioAplica = '{$parametros['usuario_Aplica']}', sol_ind_fechaAplica = NOW()";
		}
		if(isset($parametros['usuarioAnula'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_usuarioAnula = '{$parametros['usuarioAnula']}', sol_ind_fechaAnula = NOW()";
		}
		if(isset($parametros['observacion_detalle'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_obsAnula = '{$parametros['observacion_detalle']}', sol_ind_fechaAnula = NOW()";
		}
		if(isset($parametros['observacion_elimina'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_obsElimina = '{$parametros['observacion_elimina']}'";
		}
		if(isset($parametros['usuario_Elimina'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_ind_usuarioElimina = '{$parametros['usuario_Elimina']}', sol_ind_fechaElimina = NOW()";
		}
		$sql .= $condicion." WHERE sol_ind_id = '{$parametros['solicitud_id']}' AND sol_ind_servicio = '{$parametros['tipo_id']}'";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarSolicitudImagenologia");
	}

	/////////////////////MILTON///////////////////////
	function actualizarSolicitudIndicacionesProcedimiento($objCon,$parametros){
		$condicion = '';
		$sql="UPDATE rce.solicitud_indicaciones";
		if(isset($parametros['sol_ind_estado'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_estado = '{$parametros['sol_ind_estado']}'";
		}
		if(isset($parametros['sol_observacion_procedimiento'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_observacion_procedimiento = '{$parametros['sol_observacion_procedimiento']}'";
		}
		if(isset($parametros['sol_ind_usuarioIniciaIndicacion'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_usuarioIniciaIndicacion = '{$parametros['sol_ind_usuarioIniciaIndicacion']}'";
		}
		if(isset($parametros['sol_ind_fechaIniciaIndicacion'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_fechaIniciaIndicacion = '{$parametros['sol_ind_fechaIniciaIndicacion']}'";
		}
		if(isset($parametros['sol_ind_usuarioAplica'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_usuarioAplica = '{$parametros['sol_ind_usuarioAplica']}'";
		}
		if(isset($parametros['sol_ind_fechaAplica'])){
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" sol_ind_fechaAplica = '{$parametros['sol_ind_fechaAplica']}'";
		}
		$sql .= $condicion." WHERE sol_ind_id = '{$parametros['sol_ind_id']}'
		AND sol_ind_servicio 	= '{$parametros['sol_ind_servicio']}'
		AND sol_ind_preCod 		= '{$parametros['sol_ind_preCod']}' ";
		$resultado = $objCon->ejecutarSQL($sql, "Error al actualizarSolicitudIndicacionesProcedimiento");
	}
	/////////////////////FIN MILTON///////////////////////S



	function listarAnt($objCon,$idPaciente, $dauId){
		$sql="SELECT
				paciente_has_antecedente.pac_ant_id,
				paciente_has_antecedente.antId,
				paciente_has_antecedente.pacId,
				paciente_has_antecedente.pac_ant_fecha_inicio,
				paciente_has_antecedente.pac_ant_fecha_termino,
				paciente_has_antecedente.pac_ant_descripcion,
				paciente_has_antecedente.reg_usuario_inserta,
				paciente_has_antecedente.pac_ant_observacion,
				antecedente.antId,
				antecedente.antDescripcion,
				antecedente.tipAntId,
				tipoantecedente.tipAntId,
				tipoantecedente.tipAntDescripcion
				FROM rce.paciente_has_antecedente
				INNER JOIN rce.antecedente ON paciente_has_antecedente.antId = antecedente.antId
				INNER JOIN rce.tipoantecedente ON antecedente.tipAntId = tipoantecedente.tipAntId
				WHERE
					paciente_has_antecedente.pacId = '$idPaciente'
				AND
					paciente_has_antecedente.dau_id = '{$dauId}' ";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function sensitivaTratamientos ( $objCon, $termino ) {

		$objCon->setDB("paciente");

		$sql = "SELECT
					paciente.prestacion.preCod,
					paciente.prestacion.preNombreCompleto AS preNombre
				FROM
					paciente.prestacion
				WHERE
					paciente.prestacion.preUrgenciaActivo = 'S' ";
		$condicion= "";
		if($termino == 3){
			$condicion.="  AND preUrgenciaGine = 'S' ";
		}
		if($termino == 1){
			$condicion.="  AND preUgenciaAdulto = 'S' ";
		}
		if($termino == 2){
			$condicion.=" AND preUrgenciaPediatrico = 'S' ";
		}
		$sql .= $condicion;
		$datos = $objCon->consultaSQL($sql,"Error al Obtener Tratamientos");
		return $datos;

	}



	function cargarProcedimientosPrevios($objCon, $parametros){
		$objCon->setDB("paciente");
		$sql="SELECT
					paciente.prestacion.preCod,
					paciente.prestacion.preNombre
				FROM
					paciente.prestacion
				WHERE
					paciente.prestacion.preUrgenciaActivo = 'S' AND paciente.prestacion.preCod='{$parametros['preCod']}' ";
		$datos = $objCon->consultaSQL($sql,"Error al Obtener Tratamientos");
		return $datos;
	}



	function cargarDetalleTipoExamen($objCon, $parametros){
		$sql="SELECT preCod,preNombre,preIMAGEClasi,prePacienteUrgencia
		FROM paciente.prestacion
		WHERE  prestacion.preCod = '{$parametros['preCod']}' AND prestacion.preIMAGEClasi= '{$parametros['preIMAGEclasi']}' AND prestacion.preGrupo = '04'";
		$datos = $objCon->consultaSQL($sql,"Error al Obtener cargarDetalleTipoExamen");
		return $datos;
	}



	function obtenerRCEIDSegunEvento ( $objCon, $parametros ) {
		$sql	=
			"SELECT
				MAX(registroclinico.regId) AS idRCE
			FROM
				rce.registroclinico
			WHERE	eveId = '{$parametros['evento_id']}' ";
		$resultado = $objCon->consultaSQL($sql, "Error al obteder el ID del RCE");
		return $resultado[0]['idRCE'];
	}



	function obtenerClasificacionesTratamiento ( $objCon ) {

		$objCon->setDB("rce");

		$sql	=	"SELECT
						clasificacion_tratamiento.idClasificacion,
						clasificacion_tratamiento.descripcionClasificacion
						FROM
							clasificacion_tratamiento
						ORDER BY
						clasificacion_tratamiento.idClasificacion";

		$resultado = $objCon->consultaSQL($sql, "Error al obteder las clasificaciones de tratamiento");

		return $resultado;

	}



	function verificarProcedimientoExamenCOVID19 ( $objCon, $idRCE ) {

		$sql = "SELECT
					rce.solicitud_indicaciones.sol_ind_id
				FROM
					rce.solicitud_indicaciones
				WHERE
					rce.solicitud_indicaciones.regId = '{$idRCE}'
				AND
					rce.solicitud_indicaciones.sol_ind_servicio = 6
				AND
					rce.solicitud_indicaciones.sol_ind_descripcion LIKE '%covid%'
				AND
					rce.solicitud_indicaciones.sol_ind_estado IN (1, 2, 4)
				";

		$resultado = $objCon->consultaSQL($sql, "Error al verificar si paciente cuenta con procedimiento Covid-19");

		return $resultado;

	}

}
