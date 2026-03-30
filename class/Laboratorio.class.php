<?php
class Laboratorio{
	function getLaboratorio1($objCon, $rut){
    $objCon->setDB("laboratorio");
    $sql1 = "SELECT
                laboratorio.controllaboratorio.anio,
                laboratorio.controllaboratorio.desc_servicio,
                laboratorio.controllaboratorio.fecha_extraccion,
                laboratorio.controllaboratorio.fecha_registro,
                laboratorio.controllaboratorio.desc_solicitante,
                laboratorio.controllaboratorio.nomb_medico,
                laboratorio.controllaboratorio.solicitud_examen,
                laboratorio.controllaboratorio.tipo_solicitante,
                laboratorio.controllaboratorio.estado,
                laboratorio.controllaboratorio.infinity,
                '' as contenido_base64,
                '' as fecha_solicitud
            FROM
            laboratorio.controllaboratorio
            WHERE
            laboratorio.controllaboratorio.rut_paciente = '$rut' AND
            laboratorio.controllaboratorio.estado IS NULL  
            AND (laboratorio.controllaboratorio.rut_paciente != '' or laboratorio.controllaboratorio.rut_paciente != 0)
            UNION
            SELECT
                YEAR(laboratorio.list_controllaboratorio.fecha_registro) as anio,
                laboratorio.list_controllaboratorio.desc_servicio,
                laboratorio.list_controllaboratorio.fecha_extraccion,
                laboratorio.list_controllaboratorio.fecha_registro,
                laboratorio.list_controllaboratorio.desc_solicitante,
                laboratorio.list_controllaboratorio.nomb_medico,
                laboratorio.list_controllaboratorio.solicitud_examen,
                laboratorio.list_controllaboratorio.tipo_solicitante,
                laboratorio.list_controllaboratorio.estado,
                '' as infinity,
                'S' as contenido_base64,
                laboratorio.list_controllaboratorio.fecha_solicitud 
            FROM
            laboratorio.list_controllaboratorio
            WHERE 
            laboratorio.list_controllaboratorio.rut_paciente='$rut' AND
            laboratorio.list_controllaboratorio.estado IS NULL  
            AND (laboratorio.list_controllaboratorio.rut_paciente != '' or laboratorio.list_controllaboratorio.rut_paciente != 0)
            order by fecha_extraccion DESC
            LIMIT 1";
    $datos = $objCon->consultaSQL($sql1,"Error al obtener laboratorio para transfusión");
    return $datos;
}
	function getExamenesLaboratorio($objCon){
		$sql="SELECT pre_examen,pre_codOmega
			FROM laboratorio.prestacion
			WHERE prestacion.pre_filtro_urgencia = 'S'
			ORDER BY
			prestacion.pre_examen ASC";
		$datos = $objCon->consultaSQL($sql,"Error al getExamenesLaboratorio");
		return $datos;
	}


	function SelectSolicitud_laboratorio ( $objCon, $parametros ) {

		$sql 		= "	SELECT * 
						FROM
							rce.solicitud_laboratorio ";
	
		if(isset($parametros['sol_lab_id'])){
				$condicion .= ($condicion == "") ? " WHERE " : "  AND ";
				$condicion.=" solicitud_laboratorio.sol_lab_id = '{$parametros['sol_lab_id']}'";
		}
		$sql .= $condicion;
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
		
	}

	function insertarSolicitudLab($objCon,$parametros){
		$sql="INSERT INTO laboratorio.solicitud
			(tipo_id,
			sol_lab_id,
			est_id,
			id_paciente,
			sol_usuarioInserta,
			sol_fechaInserta)
			VALUES(
			{$parametros['tipo_id_lab']},
			{$parametros['solicitud_id']},
			{$parametros['lab_est_id']},
			'{$parametros['idPaciente']}',
			'{$parametros['usuario_Aplica']}',
			NOW())";
		$response = $objCon->ejecutarSQL($sql, "Error al insertarSolicitudLab");
	}



	function insertarSolicitudLaboratorio($objCon,$parametros){
		$sql="INSERT INTO rce.solicitud_laboratorio
			(regId,
			sol_lab_estado,
			sol_lab_tipo,
			sol_lab_usuarioInserta,
			id_cabecera_indicaciones,
			sol_lab_fechaInserta)
			VALUES(
			{$parametros['rce_id']},
			'{$parametros['estado_indicacion']}',
			'{$parametros['servicio']}',
			'{$parametros['dau_mov_usuario']}',
			'{$parametros['id_cabecera_indicaciones']}',
			'{$parametros['sol_lab_fechaInserta']}')";
		$response = $objCon->ejecutarSQL($sql, "Error al insertarNuevaIndicacion lab");
		$lab_id = $objCon->lastInsertId();
		return $lab_id;
	}



	function insertarDetalleIndicacionLaboratorio($objCon,$parametros){
		$sql="INSERT INTO rce.detalle_solicitud_laboratorio
		(sol_lab_id,
		det_lab_estado,
		det_lab_codigo,
		det_lab_descripcion
		)VALUES(
		{$parametros['sol_lab_id']},
		{$parametros['est_id']},
		'{$parametros['codigo']}',
		'{$parametros['descripcion']}')";
		$response = $objCon->ejecutarSQL($sql, "Error al insertarDetalleIndicacion");
		return $objCon->lastInsertId();
	}



	function editarSolicitudLaboratorio($objCon,$parametros){
		$condicion 	= "";
		$sql="UPDATE rce.solicitud_laboratorio";
		if(isset($parametros['estado_indicacion'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_estado = '{$parametros['estado_indicacion']}'";
		}
		if(isset($parametros['usuario_Aplica'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_usuarioAplica = '{$parametros['usuario_Aplica']}', sol_lab_fechaAplica = NOW()";
		}
		if(isset($parametros['observacion_aplica'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_observacion = '{$parametros['observacion_aplica']}'";
		}
		if(isset($parametros['usuarioAnula'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_usuarioAnula = '{$parametros['usuarioAnula']}', sol_lab_fechaAnula = NOW()";
		}
		if(isset($parametros['observacion_detalle'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_obs_Anula = '{$parametros['observacion_detalle']}'";
		}
		if(isset($parametros['usuario_Elimina'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_usuarioElimina = '{$parametros['usuario_Elimina']}', sol_lab_fechaElimina = NOW()";
		}
		if(isset($parametros['observacion_elimina'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_lab_obsElimina = '{$parametros['observacion_elimina']}'";
		}
		$sql .= $condicion." WHERE sol_lab_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarSolicitudImagenologia");
	}



	function editarSolicitudLabnet($objCon,$parametros){
		$sql="UPDATE laboratorio.solicitud";
		if(isset($parametros['estado_indicacion_labnet'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" est_id = '{$parametros['estado_indicacion_labnet']}'";
		}
		if(isset($parametros['usuarioAnula'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_usuarioAnula = '{$parametros['usuarioAnula']}', sol_fechaAnula = NOW()";
		}
		if(isset($parametros['observacion_anula'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" sol_obsAnula = '{$parametros['observacion_anula']}'";
		}
		$sql .= $condicion." WHERE sol_lab_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarSolicitudImagenologia");
	}

	function editarDetalleLaboratorio($objCon,$parametros){
		$sql="UPDATE rce.detalle_solicitud_laboratorio";
		if(isset($parametros['estado_indicacion'])){
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" det_lab_estado = '{$parametros['estado_indicacion']}'";
		}
		$sql .= $condicion." WHERE sol_lab_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->ejecutarSQL($sql, "Error al editarSolicitudImagenologia");
	}



	function listarIndicacionesLaboratorio($objCon,$parametros){
		$sql="SELECT
			solicitud_laboratorio.sol_lab_id,
			solicitud_laboratorio.regId,
			solicitud_laboratorio.sol_lab_estado,
			solicitud_laboratorio.sol_lab_tipo,
			solicitud_laboratorio.sol_lab_usuarioInserta,
			solicitud_laboratorio.sol_lab_fechaInserta,
			solicitud_laboratorio.sol_lab_usuarioAplica,
			solicitud_laboratorio.sol_lab_fechaAplica,
			solicitud_laboratorio.sol_lab_observacion,
			detalle_solicitud_laboratorio.sol_lab_id,
			detalle_solicitud_laboratorio.det_lab_id,
			detalle_solicitud_laboratorio.det_lab_estado,
			detalle_solicitud_laboratorio.det_lab_codigo,
			detalle_solicitud_laboratorio.det_lab_descripcion,
			prestacion.tubo_id 
			FROM
			rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN laboratorio.prestacion ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			WHERE solicitud_laboratorio.sol_lab_id = {$parametros['solicitud_id']}";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}
	function listarIndicacionesLaboratorioporTubo($objCon,$parametros){
		$sql="SELECT
			solicitud_laboratorio.sol_lab_id,
			solicitud_laboratorio.regId,
			solicitud_laboratorio.sol_lab_estado,
			solicitud_laboratorio.sol_lab_tipo,
			solicitud_laboratorio.sol_lab_usuarioInserta,
			solicitud_laboratorio.sol_lab_fechaInserta,
			solicitud_laboratorio.sol_lab_usuarioAplica,
			solicitud_laboratorio.sol_lab_fechaAplica,
			solicitud_laboratorio.sol_lab_observacion,
			detalle_solicitud_laboratorio.sol_lab_id,
			detalle_solicitud_laboratorio.det_lab_id,
			detalle_solicitud_laboratorio.det_lab_estado,
			detalle_solicitud_laboratorio.det_lab_codigo,
			detalle_solicitud_laboratorio.det_lab_descripcion,
			prestacion.tubo_id
			FROM
			rce.solicitud_laboratorio
			INNER JOIN rce.detalle_solicitud_laboratorio ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
			INNER JOIN laboratorio.prestacion ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
			WHERE solicitud_laboratorio.regId = '{$parametros['regId']}' 
			AND solicitud_laboratorio.sol_lab_fechaInserta = '{$parametros['sol_lab_fechaInserta']}' ";
			
			if(isset($parametros['tubo_id'])){
				$condicion .= ($condicion == "") ? " AND " : "  ";
				$condicion.=" prestacion.tubo_id = '{$parametros['tubo_id']}'";
			}else{
				$condicion .= ($condicion == "") ? " AND " : "  ";
				$condicion.=" (prestacion.tubo_id = '' or prestacion.tubo_id is null)";
			}
			$sql .= $condicion;
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function listarPrestaciones($objCon,$id){
		$sql="SELECT
				laboratorio.prestacion.pre_codOmega,
				UPPER(laboratorio.prestacion.pre_examen) as pre_examen,
				laboratorio.prestacion.pre_seccion,
				laboratorio.prestacion.pre_pacienteUrgencia
				FROM
				laboratorio.prestacion
				WHERE
				laboratorio.prestacion.pre_seccion_nueva = '$id'
				AND 
				laboratorio.prestacion.filtro_RCELaboratorio = 'S'
				ORDER BY
				laboratorio.prestacion.pre_examen";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}
	function listarPrestaciones2($objCon,$id){
		$sql="SELECT
				laboratorio.prestacion.pre_codOmega,
				UPPER(laboratorio.prestacion.pre_examen) as pre_examen,
				laboratorio.prestacion.pre_seccion,
				laboratorio.prestacion.pre_pacienteUrgencia
				FROM
				laboratorio.prestacion
				WHERE
				laboratorio.prestacion.pre_seccion_nueva = '$id'
				AND 
				laboratorio.prestacion.filtro_RCELaboratorio = 'S'
				AND
				laboratorio.prestacion.pre_pacienteUrgencia <> 'S'
				ORDER BY
				laboratorio.prestacion.pre_examen";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function listarPrestaciones_urg($objCon,$id){
		$sql="SELECT
				laboratorio.prestacion.pre_codOmega,
				UPPER(laboratorio.prestacion.pre_examen) as pre_examen,
				laboratorio.prestacion.pre_seccion,
				laboratorio.prestacion.pre_pacienteUrgencia
				FROM
				laboratorio.prestacion
				WHERE
				laboratorio.prestacion.pre_seccion_nueva = '$id'
				AND 
				laboratorio.prestacion.filtro_RCELaboratorio = 'S' 
				AND
				laboratorio.prestacion.pre_pacienteUrgencia = 'S'
				ORDER BY
				laboratorio.prestacion.pre_examen";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function detallePrestacion($objCon){
		$sql="SELECT
				laboratorio.prestacion.pre_examen,
				laboratorio.prestacion.pre_codOmega,
				laboratorio.seccion.`desc`
				FROM
				laboratorio.prestacion
				INNER JOIN laboratorio.seccion ON prestacion.pre_seccion = seccion.cod";
		$resultado = $objCon->consultaSQL($sql,"Error al listarIndicaciones");
		return $resultado;
	}



	function listarExamenesSeccion($objCon,$id,$seccion){
		$sql="SELECT
					rce.detalle_solicitud_laboratorio.sol_lab_id,
					rce.detalle_solicitud_laboratorio.det_lab_codigo,
					rce.detalle_solicitud_laboratorio.det_lab_descripcion,
					laboratorio.prestacion.pre_seccion
					FROM
					rce.detalle_solicitud_laboratorio
					INNER JOIN laboratorio.prestacion ON rce.detalle_solicitud_laboratorio.det_lab_codigo = laboratorio.prestacion.pre_codOmega
					COLLATE utf8_spanish_ci
					WHERE rce.detalle_solicitud_laboratorio.sol_lab_id = '$id'
					AND laboratorio.prestacion.pre_seccion = '$seccion'
					ORDER BY rce.detalle_solicitud_laboratorio.sol_lab_id";
		$resultado = $objCon->consultaSQL($sql,"Error al listarExamenesSeccion");
		return $resultado;
	}



	function buscarExamenLaboratorioCanceladoPreviamente ( $objCon, $idExamen ) {
		
		$sql 		= "	SELECT 
							laboratorio.solicitud.est_id
						FROM
							laboratorio.solicitud
						WHERE
							laboratorio.solicitud.sol_lab_id = '{$idExamen}' ";

		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener si examen fue cancelado previamente");

		return $resultado;
	
	}



	function cambiarEstadoSolicitudLab ( $objCon, $idExamen ) {

		$sql = "UPDATE
					laboratorio.solicitud
				SET 
					laboratorio.solicitud.est_id 			 	 = 1,
					laboratorio.solicitud.sol_usuarioCancela 	 = NULL,
					laboratorio.solicitud.sol_fechaCancela   	 = NULL,
					laboratorio.solicitud.sol_obsCancela 		 = NULL
				WHERE
					laboratorio.solicitud.sol_lab_id = '{$idExamen}' ";

		$objCon->ejecutarSQL($sql, "Error al cambiar estado de solicitud de laboratorio");
	}



	function examenCanceladoPreviamente ( $objCon, $idExamen ) {

		$sql 		= "	SELECT
							laboratorio.solicitud.sol_usuarioCancela
						FROM
							laboratorio.solicitud
						WHERE
							laboratorio.solicitud.sol_lab_id = '{$idExamen}' ";
	
		$resultado 	= $objCon->consultaSQL($sql,"Error al obtener si examen fue cancelado previamente");

		return $resultado;
		
	}



	function consultarMovimientosCancelacionExamenes ( $objCon, $idExamen ) {

		$sql 		= " SELECT 
							laboratorio.log_cancelacion_examen.fechaCancela,
							laboratorio.log_cancelacion_examen.usuarioCancela,
							laboratorio.log_cancelacion_examen.descripcionCancela,
							laboratorio.log_cancelacion_examen.observacionCancela
						 FROM
							laboratorio.log_cancelacion_examen 
						WHERE
							laboratorio.log_cancelacion_examen.idExamen    = '{$idExamen}' ";	
					 
		$resultado  = $objCon->consultaSQL($sql,"Error al consultar log de cancelación de exámenes");
		
		return $resultado;

	}



	function consultarExamenesCanceladosDesdeMapaPiso ( $objCon, $idDau ) {

		$sql		= 	" 	SELECT
								laboratorio.solicitud.est_id 
							FROM
								rce.registroclinico
							INNER JOIN 
								rce.solicitud_laboratorio ON rce.registroclinico.regId = rce.solicitud_laboratorio.regId
							INNER JOIN 
								laboratorio.solicitud ON rce.solicitud_laboratorio.sol_lab_id = laboratorio.solicitud.sol_lab_id
							WHERE 
								rce.registroclinico.dau_id = '${idDau}'
							AND 
								laboratorio.solicitud.est_id = 6 ";

		$resultado  = $objCon->consultaSQL($sql,"Error al consultar examenes cancelados desde mapa piso");
				
		return $resultado;
		
	}

}
?>