<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class MapaPiso{

	function loadTablaCategorizacion($objCon, $tipoMP){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				INNER JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.est_id = '2' ";
		if ($tipoMP == 'mp') {
				$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}
				$sql .= "ORDER BY dau.dau.dau_categorizacion_actual, dau.dau.dau_admision_fecha";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function cargarPacientesGine($objCon){
		$sql="SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS 'FechaActual',
				dau.categorizacion.cat_nombre_mostrar,
				dau.categorizacion.cat_nivel
			FROM
				dau.dau
			INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
			INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE
				dau.dau.est_id IN('1','2') AND dau.dau.dau_atencion = '3'
			ORDER BY	dau.dau.est_id DESC,
			dau.dau.dau_admision_fecha";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function loadTabla($objCon, $tipoMP){

		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.est_id BETWEEN 1 AND 2 ";
		if ($tipoMP == 'mp') {
				$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}
		$sql .= "ORDER BY dau.dau.est_id DESC, dau.dau.dau_categorizacion_actual, dau.dau.dau_admision_fecha";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function totalPacientesCategorizados($objCon, $tipoMP){

		$sql = " 	SELECT
						COUNT(dau.dau.dau_id) AS totalPacientesCategorizados
					FROM
						dau.dau
					WHERE
					dau.dau.est_id = 2 ";

		if ($tipoMP == 'mp') {
			$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function totalPacientesListaEspera($objCon, $tipoMP){

		$sql = " 	SELECT
						COUNT(dau.dau.dau_id) AS totalPacientesListaEspera
					FROM
						dau.dau
					WHERE
					dau.dau.est_id = 1 ";

		if ($tipoMP == 'mp') {
			$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Lista Espera<br>");
		return $datos;
	}



	function cantidadesADMCAT($objCon, $tipoMP){
		$sql = "SELECT
				dau.dau.est_id,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.est_id BETWEEN 1 AND 2 ";
		if ($tipoMP == 'mp') {
				$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}

		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function loadCamas($objCon, $dau_id = null, $camaOrigen = null){

		$sql = "SELECT
				dau.sala.sal_orden,
				dau.sala.sal_tipo_cama,
				dau.tipo_sala_grupo.tipo_sala_grupo_descripcion,
				dau.tipo_sala_grupo.tipo_sala_grupo_id,
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.dau_id,
				dau.cama.est_id,
				dau.cama.cam_descripcion,
				dau.cama.cam_fecha_desocupada,
				dau.sala.sal_descripcion,
				dau.sala.sal_tipo,
				dau.sala.sal_grupo,
				dau.sala.sal_nombre_mostrar,
				dau.sala.sal_resumen_nombre_mostrar,
				dau.sala.sal_resumen,
				dau.sala.sal_doble_columna,
				dau.sala.sal_pertenece_grupo,
				dau.dau.id_paciente,
				dau.dau.idctacte,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_ingreso_sala_fecha,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.dau.dau_indicacion_egreso_aplica_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_defuncion_fecha,
				dau.dau.dau_cierre_administrativo,
				dau.dau.dau_cierre_administrativo_usuario,
				dau.dau.dau_cierre_administrativo_observacion,
				dau.dau.dau_indicaciones_completas,
				dau.dau.dau_indicaciones_solicitadas,
				dau.dau.dau_indicaciones_realizadas,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.rut_extranjero,
				paciente.paciente.tipodoc,	
				paciente.paciente.transexual, paciente.paciente.nombreSocial, paciente.paciente.identidad_genero, paciente.paciente.nombre_legal,
				camas.sscc.servicio,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.dau.dau_tipo_accidente,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_nombre_mostrar,
				dau.categorizacion.cat_tiempo_alerta,
				dau.dau_tiene_indicacion.ind_egr_id,
				dau.tipo_cama.tipo_cama_descripcion,
				dau.tipo_cama.tipo_cama_sigla,
				dau.dau.dau_atencion,
				dau.dau.dau_sintomasRespiratorios AS sintomasRespiratorios,
				acceso.usuario.nombreusuario AS atencionIniciadaPor
				FROM
				dau.cama
				LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
				LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				INNER JOIN dau.tipo_sala_grupo ON dau.sala.sal_grupo = dau.tipo_sala_grupo.tipo_sala_grupo_id
				INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
				LEFT JOIN acceso.usuario ON dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario
				WHERE dau.sala.sal_tipo IN ('A', 'P', 'GO')
				AND dau.cama.cam_activa = 'S' ";

				if ( !is_null($dau_id) && !empty($dau_id) ){
					$sql .= " AND dau.cama.dau_id = '{$dau_id}' ";
				}

				if ( !is_null($camaOrigen) && !empty($camaOrigen) ){
					$sql .= " AND dau.cama.cam_id = '{$camaOrigen}' ";
				}

				$sql .= " ORDER BY dau.sala.sal_orden ASC, dau.cama.sal_id, dau.cama.cam_id";

		$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
		return $datos;
	}



	function loadCamasGine($objCon){

		$sql = "SELECT
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.dau_id,
				dau.cama.est_id,
				dau.cama.cam_descripcion,
				dau.sala.sal_descripcion,
				dau.dau.id_paciente,
				dau.dau.idctacte,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_ingreso_sala_fecha,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.dau.dau_indicacion_egreso_aplica_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_defuncion_fecha,
				dau.dau.dau_cierre_administrativo,
				dau.dau.dau_cierre_administrativo_usuario,
				dau.dau.dau_cierre_administrativo_observacion,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.rut_extranjero,
				paciente.paciente.tipodoc,
				camas.sscc.servicio,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.dau.dau_tipo_accidente,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.cama
				LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
				LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.cama.sal_id  BETWEEN 8 AND 14
				ORDER BY dau.cama.cam_id";
		$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
		return $datos;
	}



	function getDatasMovePacienteTblCatToSalaUE($objCon, $id_dau){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.id_paciente,
				dau.dau.idctacte,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.rut_extranjero,
				paciente.paciente.tipodoc,
				dau.dau.dau_tipo_accidente,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.atencion.ate_descripcion,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.dau_id = '$id_dau'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener los datos del paciente que se esta moviendo<br>");
		return $datos;
	}



	function getPacienteSigueCamaOrg($objCon,$parametros){
		$sql = "SELECT cama.*,
					sala.sal_resumen
				FROM dau.cama
				INNER JOIN dau.sala ON cama.sal_id = sala.sal_id
				WHERE cama.dau_id = '{$parametros['id_dau']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener los datos del paciente que se esta consultando<br>");
		return $datos;

	}



	function getEstadoCamaDestino($objCon,$parametros){
		$sql = "SELECT *
				FROM dau.cama
				WHERE sal_id = '{$parametros['num_salaDest']}'
				AND cam_descripcion = '{$parametros['cama']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener de la cama que se esta consultando<br>");
		return $datos;
	}



	function updateSalaUEOrig($objCon,$parametros){
		$sql = "UPDATE dau.cama
				SET dau_id = NULL,
					est_id = '10',
					cam_fecha_desocupada = NOW()
				WHERE sal_id = '{$parametros['salaOrig_id']}' AND cam_descripcion = '{$parametros['camaOrig']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar la cama de origen del paciente");
	}



	function updateSalaUEOrigGINE($objCon,$parametros){

		switch ($parametros['salaOrig']) {
			case 'BOX1GO':
				$parametros['salaOrig'] = '8';
				break;
			case 'BOX2GO':
				$parametros['salaOrig'] = '9';
				break;
			case 'ECOGRAFO':
				$parametros['salaOrig'] = '10';
				break;
			case 'BOXPERMEDLE':
				$parametros['salaOrig'] = '11';
				break;
			case 'HIDRATACIONGO':
				$parametros['salaOrig'] = '12';
				break;
			case 'ENTREVISTA':
				$parametros['salaOrig'] = '13';
				break;
			case 'ESPERAGO':
				$parametros['salaOrig'] = '14';
				break;
			case 'BOX1MP':
				$parametros['salaOrig'] = '15';
				break;
			case 'BOX2MP':
				$parametros['salaOrig'] = '16';
				break;
			case 'BOX3MP':
				$parametros['salaOrig'] = '17';
				break;
			case 'BOX4MP':
				$parametros['salaOrig'] = '18';
				break;
			case 'BOX5MP':
				$parametros['salaOrig'] = '19';
				break;
			case 'BOX6MP':
				$parametros['salaOrig'] = '20';
				break;
			case 'BOX7MP':
				$parametros['salaOrig'] = '21';
				break;
			case 'BOX8MP':
				$parametros['salaOrig'] = '22';
				break;
			case 'BOXCATMP':
				$parametros['salaOrig'] = '23';
				break;
			case 'BOXLESMP':
				$parametros['salaOrig'] = '24';
				break;
			case 'BOXREAMP':
				$parametros['salaOrig'] = '25';
				break;
			case 'BOXOBSMP':
				$parametros['salaOrig'] = '26';
				break;
			case 'BOXHIDMP':
				$parametros['salaOrig'] = '27';
				break;
			case 'BOXP1MP':
				$parametros['salaOrig'] = '28';
				break;
			case 'BOXP2MP':
				$parametros['salaOrig'] = '29';
				break;
			case 'BOXP3MP':
				$parametros['salaOrig'] = '30';
				break;
			case 'BOXPASPEDMP':
				$parametros['salaOrig'] = '31';
				break;
			case 'BOXCAMLIBMP':
				$parametros['salaOrig'] = '32';
				break;
			default:
				break;
		}
		$sql = "UPDATE cama
				SET dau_id = NULL,
					est_id = '10',
					cam_fecha_desocupada = NOW()
				WHERE sal_id = '{$parametros['salaOrig']}' AND cam_descripcion = '{$parametros['camaOrig']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar la cama de origen del paciente");
	}



	function loadTablaEspera($objCon, $tipoMP){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS 'FechaActual'
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				WHERE dau.dau.est_id = '1' ";
		if ($tipoMP == 'mp') {
				$sql .= "AND dau.dau.dau_atencion <> '3' ";
		}
		else{
				$sql .= "AND dau.dau.dau_atencion = '3' ";
		}
				$sql .= "ORDER BY dau.dau.dau_admision_fecha";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function updateSalaUEDestino($objCon, $parametros){

		$sql = "UPDATE dau.cama
				SET dau_id = '{$parametros['id_dau']}',
					est_id = '11',
					cam_fecha_desocupada = null
				WHERE sal_id = '{$parametros['salaDest_id']}' AND cam_descripcion = '{$parametros['cama']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar la cama de destino del paciente");
	}



	function updateSalaUEDestinoGINE($objCon, $parametros){

		switch ($parametros['sala']) {
			case 'BOX1GO':
				$parametros['sala'] = '8';
				break;
			case 'BOX2GO':
				$parametros['sala'] = '9';
				break;
			case 'ECOGRAFO':
				$parametros['sala'] = '10';
				break;
			case 'BOXPERMEDLE':
				$parametros['sala'] = '11';
				break;
			case 'HIDRATACIONGO':
				$parametros['sala'] = '12';
				break;
			case 'ENTREVISTA':
				$parametros['sala'] = '13';
				break;
			case 'ESPERAGO':
				$parametros['sala'] = '14';
				break;
			case 'BOX1MP':
				$parametros['sala'] = '15';
				break;
			case 'BOX2MP':
				$parametros['sala'] = '16';
				break;
			case 'BOX3MP':
				$parametros['sala'] = '17';
				break;
			case 'BOX4MP':
				$parametros['sala'] = '18';
				break;
			case 'BOX5MP':
				$parametros['sala'] = '19';
				break;
			case 'BOX6MP':
				$parametros['sala'] = '20';
				break;
			case 'BOX7MP':
				$parametros['sala'] = '21';
				break;
			case 'BOX8MP':
				$parametros['sala'] = '22';
				break;
			case 'BOXCATMP':
				$parametros['sala'] = '23';
				break;
			case 'BOXLESMP':
				$parametros['sala'] = '24';
				break;
			case 'BOXREAMP':
				$parametros['sala'] = '25';
				break;
			case 'BOXOBSMP':
				$parametros['sala'] = '26';
				break;
			case 'BOXHIDMP':
				$parametros['sala'] = '27';
				break;
			case 'BOXP1MP':
				$parametros['sala'] = '28';
				break;
			case 'BOXP2MP':
				$parametros['sala'] = '29';
				break;
			case 'BOXP3MP':
				$parametros['sala'] = '30';
				break;
			case 'BOXPASPEDMP':
				$parametros['sala'] = '31';
				break;
			case 'BOXCAMLIBMP':
				$parametros['sala'] = '32';
				break;
			default:
				break;
		}
		$sql = "UPDATE cama
				SET dau_id = '{$parametros['id_dau']}',
					est_id = '11',
					cam_fecha_desocupada = null
				WHERE sal_id = '{$parametros['sala']}' AND cam_descripcion = '{$parametros['cama']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar la cama de destino del paciente");
	}



	function updateDatasPacienteCatInDAU($objCon, $parametros){

		$sql = "UPDATE dau.dau
				SET est_id = '8',
					dau_ingreso_sala_fecha = NOW(),
					dau_ingreso_sala_usuario = '{$parametros['dau_mov_usuario']}'
				WHERE dau_id = '{$parametros['id_dau']}'";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar los datos del paciente en DAU");
	}



	function getDatasPacienteSalaUE($objCon, $id_dau){

		$sql = "SELECT
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.dau_id,
				dau.cama.est_id,
				dau.cama.cam_descripcion,
				dau.sala.sal_descripcion,
				dau.dau.id_paciente,
				dau.dau.idctacte,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_ingreso_sala_fecha,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.dau.dau_indicacion_egreso_aplica_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_defuncion_fecha,
				dau.dau.dau_cierre_administrativo,
				dau.dau.dau_cierre_administrativo_usuario,
				dau.dau.dau_cierre_administrativo_observacion,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.rut_extranjero,
				paciente.paciente.tipodoc,
				camas.sscc.servicio,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.dau.dau_tipo_accidente,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_id,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.cama
				LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
				LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.cama.dau_id ='$id_dau'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener los datos del paciente para crear el tooltip<br>");
		return $datos;
	}



	function getNombrePaciente($objCon, $id_dau){
		$sql = "SELECT
				dau.dau.dau_id,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				dau.dau.dau_atencion
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				WHERE dau.dau.dau_id = '$id_dau'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener al nombre del paciente<br>");
		return $datos;
	}



	function getEstadoPacienteDAU($objCon, $parametros){
		$sql = "SELECT
				dau.dau.est_id
				FROM
				dau.dau
				WHERE
				dau.dau.dau_id = '{$parametros['id_dau']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener el estado del paciente<br>");
		return $datos;
	}



	function getLugarPaciente($objCon, $parametros){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.cam_descripcion,
				dau.sala.sal_resumen AS sal_desc
				FROM
				dau.dau
				LEFT JOIN dau.cama ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.sala ON dau.sala.sal_id = dau.cama.sal_id
				WHERE dau.dau.dau_id = '{$parametros['id_dau']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener el lugar del paciente<br>");
		return $datos;
	}



	function getIdCama($objCon, $parametros){
		$sql = "SELECT
				dau.cama.cam_id
				FROM
				dau.cama
				WHERE dau.cama.sal_id = '{$parametros['sal_id']}'
				AND dau.cama.cam_descripcion = '{$parametros['cam_descripcion']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener el lugar del paciente<br>");
		return $datos;
	}



	function getLugarPaciente_clinico($objCon, $parametros){
		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.cam_descripcion,
				dau.dau.dau_atencion
				FROM
				dau.dau
				INNER JOIN dau.cama ON dau.cama.dau_id = dau.dau.dau_id
				WHERE dau.dau.dau_id = '{$parametros['id_dau']}'";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener el lugar del paciente<br>");
		return $datos;
	}



	function soloPedia($objCon){
		$sql="SELECT
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2)),1,0)) AS SC,
				Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2)),1,0)) AS PC,
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
				Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
				FROM
				dau.dau
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau_atencion <>3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloPedia<br>");
		return $datos;
	}



	function soloPedia3($objCon){
		$sql="SELECT
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2)),1,0)) AS SC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2)),1,0)) AS PC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <>3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloPedia<br>");
		return $datos;
	}



	function soloAdul($objCon){
		$sql="SELECT
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1)),1,0)) AS SC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(2)),1,0)) AS PC,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloAdul<br>");
		return $datos;
	}



	function soloAdulto3($objCon){
		$sql="SELECT
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1)),1,0)) AS SC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1)),1,0)) AS PC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloAdulto3<br>");
		return $datos;
	}



	function todosAdultoPediatrico2($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2)),1,0)) AS SC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2)),1,0)) AS PC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico2<br>");
		return $datos;
	}



	function todosAdultoPediatrico1($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2)),1,0)) AS SC,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2)),1,0)) AS PC,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico1<br>");
		return $datos;
	}



	function todosAdultoPediatricoGeneral($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id IN (1) AND dau.dau_atencion IN(1,2)),1,0)) AS SC,
			Sum(if((dau.est_id IN (2) AND dau.dau_atencion IN(1,2)),1,0)) AS PC,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
			WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico1<br>");
		return $datos;
	}



	function soloGine($objCon){
		$sql="SELECT
			Sum(if((dau.est_id IN (1) AND dau.dau_atencion = 3),1,0)) AS SC,
			Sum(if((dau.est_id IN (2) AND dau.dau_atencion = 3),1,0)) AS PC,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener solo ginecología<br>");
		return $datos;
	}



	function soloGine2($objCon){
		$sql="SELECT
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3),1,0)) AS SC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3),1,0)) AS PC,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id= 2 AND dau.dau_atencion = 3 AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener solo ginecología<br>");
		return $datos;
	}



	function todos($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id IN (1) AND dau.dau_atencion IN(1,2,3)),1,0)) AS SC,
			Sum(if((dau.est_id IN (2) AND dau.dau_atencion IN(1,2,3)),1,0)) AS PC,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id IN (1,2) AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id ";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico1<br>");
		return $datos;
	}



	function todos1($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3)),1,0)) AS SC,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3)),1,0)) AS PC,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id = 1 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id ";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico1<br>");
		return $datos;
	}



	function todos2($objCon){
		$sql = "SELECT
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3)),1,0)) AS SC,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3)),1,0)) AS PC,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C1')),1,0)) AS C1,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C2')),1,0)) AS C2,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C3')),1,0)) AS C3,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C4')),1,0)) AS C4,
			Sum(if((dau.est_id = 2 AND dau.dau_atencion IN(1,2,3) AND dau.categorizacion.cat_nombre_mostrar IN('C5')),1,0)) AS C5
			FROM
			dau.dau
			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id ";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener todosAdultoPediatrico1<br>");
		return $datos;
	}



	function soloAdul2($objCon){
		$sql="SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.est_id=1 AND dau.dau.dau_atencion <> '3' AND dau.dau_atencion = '1'
				ORDER BY dau.dau.est_id DESC";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloAdul<br>");
		return $datos;
	}



	function adultoPediatrico($objCon){
		$sql="SELECT
		Sum(if((dau.est_id= 1 AND dau.dau_atencion IN(1,2)),1,0)) AS SC,
		Sum(if((dau.est_id= 2 AND dau.dau_atencion IN(1,2)),1,0)) AS PC
		FROM
		dau.dau
		WHERE dau.dau_atencion <> 3";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener soloAdul<br>");
		return $datos;
	}



	function cantidadTipoCamas($objCon, $sal_id){
		$sql = "SELECT
				Count(cama.cam_id) as 'cantidad',
				sala.sal_tipo_cama,
				tipo_cama.tipo_cama_descripcion,
				tipo_cama.tipo_cama_sigla
				FROM
				dau.cama
				INNER JOIN dau.sala ON cama.sal_id = sala.sal_id
				INNER JOIN dau.tipo_cama ON sala.sal_tipo_cama = tipo_cama.tipo_cama_id
				WHERE cama.cam_activa = 'S'
				AND cama.sal_id = $sal_id";

		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener las cantidades y tipo de Camas<br>");
		return $datos;

	}


	function SelectUnidad($objCon,$tipoMapa){
		$sql = "SELECT id_unidad ,  UPPER(unidad_descripcion) AS unidad_descripcion
				FROM
				dau.unidad
				
				WHERE unidad.activo = 'A' ";
				if($tipoMapa == 'mapaGinecologico'){
					$sql .= "AND id_unidad = 4 ";
				}else if($tipoMapa == 'mapaAdultoPediatrico'){ 
					$sql .= "AND id_unidad in(1,2,3) ";
				}

		$sql .= "ORDER BY id_unidad asc";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}
	function SelectCategorizacion($objCon){
		$sql = "SELECT * from dau.categorizacion";

		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener SelectCategorizacion<br>");
		return $datos;
	}
	function loadTablaFull($objCon){

		$sql = "SELECT
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.dau.id_paciente,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion,
				dau.dau.dau_categorizacion_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_indiferenciado,
				dau.dau.dau_paciente_trasladado,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.sexo,
				paciente.paciente.fechanac,
				paciente.paciente.transexual,
				paciente.paciente.nombreSocial,
				dau.dau.dau_atencion,
				dau.atencion.ate_descripcion,
				dau.dau.dau_sintomasRespiratorios AS sintomasRespiratorios,
				dau.motivo_consulta.mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_tiempo_maximo,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nombre_mostrar
				FROM
				dau.dau
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
				INNER JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				WHERE dau.dau.est_id BETWEEN 1 AND 2 ";

		$sql .= "ORDER BY dau.dau.est_id DESC, dau.dau.dau_categorizacion_actual, dau.dau.dau_admision_fecha";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente Categorizados<br>");
		return $datos;
	}



	function verMapaPisoXProfesional($objCon, $parametros){
		$sql = "SELECT acceso.usuario.usu_conf_urgencia
				FROM acceso.usuario
				WHERE acceso.usuario.idusuario = '{$parametros['idusuario']}'
				AND acceso.usuario.rutusuario = '{$parametros['rutusuario']}'";

		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener las configuracion de vizualización del Profesional<br>");
		return $datos;
	}


	function loadCamasFullGroup($objCon, $id_unidad ){

		 $sql = "SELECT
				*,
				UPPER(sal_nombre_mostrar) AS sal_nombre_mostrarUpper
				FROM dau.sala
				WHERE dau.sala.id_unidad = '{$id_unidad}'
				AND dau.sala.sal_activo = 'S' ";


				$sql .= " order by sal_tipo, sal_orden asc";

		$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
		return $datos;
	}
	// function loadCamasFullGroup($objCon, $dau_id = null, $camaOrigen = null, $tipoSala = 'A'){

	// 	$sql = "SELECT
	// 			dau.sala.sal_orden,
	// 			dau.sala.sal_tipo_cama,
	// 			dau.sala.sal_tipo,
	// 			dau.tipo_sala_grupo.tipo_sala_grupo_descripcion,
	// 			dau.tipo_sala_grupo.tipo_sala_grupo_id,
	// 			dau.cama.cam_id,
	// 			dau.cama.sal_id,
	// 			dau.cama.dau_id,
	// 			dau.cama.est_id,
	// 			dau.cama.cam_descripcion,
	// 			dau.cama.cam_fecha_desocupada,
	// 			dau.sala.sal_descripcion,
	// 			dau.sala.sal_tipo,
	// 			dau.sala.sal_grupo,
	// 			dau.sala.sal_nombre_mostrar,
	// 			dau.sala.sal_resumen_nombre_mostrar,
	// 			dau.sala.sal_resumen,
	// 			dau.sala.sal_doble_columna,
	// 			dau.sala.sal_pertenece_grupo,
	// 			dau.dau.id_paciente,
	// 			dau.dau.idctacte,
	// 			dau.dau.dau_admision_fecha,
	// 			dau.dau.dau_categorizacion_actual_fecha,
	// 			dau.dau.dau_categorizacion_actual,
	// 			dau.dau.dau_ingreso_sala_fecha,
	// 			dau.dau.dau_inicio_atencion_fecha,
	// 			dau.dau.dau_indicacion_egreso,
	// 			dau.dau.dau_indicacion_egreso_fecha,
	// 			dau.dau.dau_indicacion_egreso_aplica_fecha,
	// 			dau.dau.dau_motivo_consulta,
	// 			dau.dau.dau_motivo_descripcion,
	// 			dau.dau.dau_defuncion_fecha,
	// 			dau.dau.dau_cierre_administrativo,
	// 			dau.dau.dau_cierre_administrativo_usuario,
	// 			dau.dau.dau_cierre_administrativo_observacion,
	// 			dau.dau.dau_indicaciones_completas,
	// 			dau.dau.dau_indicaciones_solicitadas,
	// 			dau.dau.dau_indicaciones_realizadas,
	// 			dau.motivo_consulta.mot_descripcion,
	// 			paciente.paciente.rut,
	// 			paciente.paciente.nombres,
	// 			paciente.paciente.apellidopat,
	// 			paciente.paciente.apellidomat,
	// 			paciente.paciente.fechanac,
	// 			paciente.paciente.sexo,
	// 			paciente.paciente.rut_extranjero,
	// 			paciente.paciente.tipodoc,
	// 			camas.sscc.servicio,
	// 			dau.indicacion_egreso.ind_egr_descripcion,
	// 			dau.dau.dau_tipo_accidente,
	// 			dau.sub_motivo_consulta.sub_mot_descripcion,
	// 			NOW() AS FechaActual,
	// 			dau.categorizacion.cat_tipo,
	// 			dau.categorizacion.cat_nivel,
	// 			dau.categorizacion.cat_nombre_mostrar,
	// 			dau.categorizacion.cat_tiempo_alerta,
	// 			dau.dau_tiene_indicacion.ind_egr_id,
	// 			dau.tipo_cama.tipo_cama_descripcion,
	// 			dau.tipo_cama.tipo_cama_sigla,
	// 			dau.dau.dau_atencion,
	// 			dau.dau.dau_inicio_atencion_usuario,
	// 			dau.dau.dau_sintomasRespiratorios AS sintomasRespiratorios,
	// 			acceso.usuario.nombreusuario AS atencionIniciadaPor,
	// 			IF(
	// 				paciente.paciente.rut = 0
	// 				OR paciente.paciente.rut = NULL
	// 				OR paciente.paciente.rut = ''
	// 				, paciente.paciente.rut_extranjero
	// 				, ''
	// 			) AS runPacienteExtranjero,
	// 			IF(
	// 				paciente.paciente.rut <> 0
	// 				AND paciente.paciente.rut IS NOT NULL
	// 				AND paciente.paciente.rut <> ''
	// 				, paciente.paciente.rut
	// 				, ''
	// 			) AS runPaciente
	// 			FROM
	// 			dau.cama
	// 			LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
	// 			LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
	// 			LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
	// 			LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
	// 			LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
	// 			LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
	// 			LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
	// 			LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
	// 			LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
	// 			INNER JOIN dau.tipo_sala_grupo ON dau.sala.sal_grupo = dau.tipo_sala_grupo.tipo_sala_grupo_id
	// 			INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
	// 			LEFT JOIN acceso.usuario ON dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario
	// 			WHERE dau.sala.sal_tipo = '{$tipoSala}'
	// 			AND dau.cama.cam_activa = 'S' ";

	// 			if ( !is_null($dau_id) && !empty($dau_id) ){
	// 				$sql .= " AND dau.cama.dau_id = '{$dau_id}' ";
	// 			}

	// 			if ( !is_null($camaOrigen) && !empty($camaOrigen) ){
	// 				$sql .= " AND dau.cama.cam_id = '{$camaOrigen}' ";
	// 			}

	// 			$sql .= " group by sal_nombre_mostrar";

	// 	$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
	// 	return $datos;
	// }
	function loadCamasFull($objCon, $dau_id = null, $camaOrigen = null, $tipoSala = 'A'){

		$sql = "SELECT
				dau.sala.sal_orden,
				dau.sala.sal_tipo_cama,
				dau.sala.sal_tipo,
				dau.tipo_sala_grupo.tipo_sala_grupo_descripcion,
				dau.tipo_sala_grupo.tipo_sala_grupo_id,
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.dau_id,
				dau.cama.est_id,
				dau.cama.cam_descripcion,
				dau.cama.cam_fecha_desocupada,
				dau.sala.sal_descripcion,
				dau.sala.sal_tipo,
				dau.sala.sal_grupo,
				dau.sala.sal_nombre_mostrar,
				dau.sala.sal_resumen_nombre_mostrar,
				dau.sala.sal_resumen,
				dau.sala.sal_doble_columna,
				dau.sala.sal_pertenece_grupo,
				dau.dau.id_paciente,
				dau.dau.idctacte,
				dau.dau.dau_admision_fecha,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_ingreso_sala_fecha,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.dau.dau_indicacion_egreso_aplica_fecha,
				dau.dau.dau_motivo_consulta,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_defuncion_fecha,
				dau.dau.dau_cierre_administrativo,
				dau.dau.dau_cierre_administrativo_usuario,
				dau.dau.dau_cierre_administrativo_observacion,
				dau.dau.dau_indicaciones_completas,
				dau.dau.dau_indicaciones_solicitadas,
				dau.dau.dau_indicaciones_realizadas,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.rut,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.rut_extranjero,
				paciente.paciente.tipodoc,
				camas.sscc.servicio,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.dau.dau_tipo_accidente,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_tipo,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_nombre_mostrar,
				dau.categorizacion.cat_tiempo_alerta,
				dau.dau_tiene_indicacion.ind_egr_id,
				dau.tipo_cama.tipo_cama_descripcion,
				dau.tipo_cama.tipo_cama_sigla,
				dau.dau.dau_atencion,
				dau.dau.dau_inicio_atencion_usuario,
				dau.dau.dau_sintomasRespiratorios AS sintomasRespiratorios,
				acceso.usuario.nombreusuario AS atencionIniciadaPor,
				IF(
					paciente.paciente.rut = 0
					OR paciente.paciente.rut = NULL
					OR paciente.paciente.rut = ''
					, paciente.paciente.rut_extranjero
					, ''
				) AS runPacienteExtranjero,
				IF(
					paciente.paciente.rut <> 0
					AND paciente.paciente.rut IS NOT NULL
					AND paciente.paciente.rut <> ''
					, paciente.paciente.rut
					, ''
				) AS runPaciente
				FROM
				dau.cama
				LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
				LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				INNER JOIN dau.tipo_sala_grupo ON dau.sala.sal_grupo = dau.tipo_sala_grupo.tipo_sala_grupo_id
				INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
				LEFT JOIN acceso.usuario ON dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario
				WHERE dau.sala.sal_tipo = '{$tipoSala}'
				AND dau.cama.cam_activa = 'S' ";

				if ( !is_null($dau_id) && !empty($dau_id) ){
					$sql .= " AND dau.cama.dau_id = '{$dau_id}' ";
				}

				if ( !is_null($camaOrigen) && !empty($camaOrigen) ){
					$sql .= " AND dau.cama.cam_id = '{$camaOrigen}' ";
				}

				$sql .= " ORDER BY dau.sala.sal_orden ASC, dau.cama.sal_id, dau.cama.cam_id";

		$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
		return $datos;
	}
	function loadCamasFullTipo($objCon, $dau_id = null, $camaOrigen = null,  $sal_id){

		$sql = "SELECT
				dau.cama.cam_id,
				dau.cama.sal_id,
				dau.cama.dau_id,
				dau.cama.est_id,
				dau.cama.cam_descripcion,
				dau.cama.cam_fecha_desocupada,
				dau.sala.sal_tipo,
				dau.sala.sal_tipo,
				dau.sala.sal_resumen_nombre_mostrar,
				dau.sala.sal_resumen,
				dau.dau.id_paciente,
				dau.dau.dau_categorizacion_actual_fecha,
				dau.dau.dau_ingreso_sala_fecha,
				dau.dau.dau_inicio_atencion_fecha,
				dau.dau.dau_indicacion_egreso_fecha,
				dau.dau.dau_motivo_descripcion,
				dau.dau.dau_indicaciones_completas,
				dau.dau.dau_indicaciones_solicitadas,
				dau.dau.dau_indicaciones_realizadas,
				dau.motivo_consulta.mot_descripcion,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				paciente.paciente.fechanac,
				paciente.paciente.sexo,
				paciente.paciente.transexual,
				paciente.paciente.identidad_genero,
				paciente.paciente.nombreSocial,
				camas.sscc.servicio,
				dau.indicacion_egreso.ind_egr_descripcion,
				dau.sub_motivo_consulta.sub_mot_descripcion,
				NOW() AS FechaActual,
				dau.categorizacion.cat_nivel,
				dau.categorizacion.cat_nombre_mostrar,
				dau.categorizacion.cat_tiempo_alerta,
				dau.dau_tiene_indicacion.ind_egr_id,
				dau.tipo_cama.tipo_cama_sigla,
				dau.dau.dau_atencion,
				dau.dau.dau_sintomasRespiratorios AS sintomasRespiratorios,
				acceso.usuario.nombreusuario AS atencionIniciadaPor,
				UPPER(evoUser.nombreusuario) as dau_usuario_ultima_evo, 
				IF(
				    dau.dau.dau_usuario_ultima_evo IS NOT NULL 
				    AND dau.dau.dau_usuario_ultima_evo <> '',
				    dau.dau.dau_usuario_ultima_evo,
				    acceso.usuario.idusuario
				) AS atencionIniciadaPorID,
				-- acceso.usuario.idusuario AS atencionIniciadaPorID,
				IF(
					paciente.paciente.rut = 0
					OR paciente.paciente.rut = NULL
					OR paciente.paciente.rut = ''
					, paciente.paciente.rut_extranjero
					, ''
				) AS runPacienteExtranjero,
				IF(
					paciente.paciente.rut <> 0
					AND paciente.paciente.rut IS NOT NULL
					AND paciente.paciente.rut <> ''
					, paciente.paciente.rut
					, ''
				) AS runPaciente
				FROM
				dau.cama
				LEFT JOIN dau.sala ON dau.cama.sal_id = dau.sala.sal_id
				LEFT JOIN dau.dau ON dau.cama.dau_id = dau.dau.dau_id
				LEFT JOIN dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
				LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				LEFT JOIN dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
				LEFT JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
				LEFT JOIN dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
				LEFT JOIN dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
				LEFT JOIN dau.categorizacion ON dau.dau.dau_categorizacion_actual = dau.categorizacion.cat_id
				INNER JOIN dau.tipo_sala_grupo ON dau.sala.sal_grupo = dau.tipo_sala_grupo.tipo_sala_grupo_id
				INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
				LEFT JOIN acceso.usuario ON CONVERT(CAST(dau.dau_inicio_atencion_usuario as BINARY) USING latin1) COLLATE latin1_spanish_ci  = acceso.usuario.idusuario
				LEFT JOIN acceso.usuario as evoUser ON CONVERT(CAST(dau.dau_usuario_ultima_evo as BINARY) USING latin1) COLLATE latin1_spanish_ci   = evoUser.idusuario
				WHERE dau.cama.cam_activa = 'S' 
				AND cama.sal_id = '{$sal_id}' ";

				if ( !is_null($dau_id) && !empty($dau_id) ){
					$sql .= " AND dau.cama.dau_id = '{$dau_id}' ";
				}

				if ( !is_null($camaOrigen) && !empty($camaOrigen) ){
					$sql .= " AND dau.cama.cam_id = '{$camaOrigen}' ";
				}

				$sql .= " ORDER BY dau.sala.sal_orden ASC, dau.cama.sal_id, dau.cama.cam_id";

		$datos = $objCon->consultaSQL($sql, "<br>Error al listar Camas Box<br>");
		return $datos;
	}



	function guardarTipoMapaUsuario( $objCon, $parametros ) {

		$sql = 	" 	UPDATE
						acceso.usuario
					SET
						acceso.usuario.usu_conf_urgencia = '{$parametros['tipoMapa']}'
					WHERE
						acceso.usuario.idusuario = '{$parametros['idusuario']}'  ";

		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar Tipo Mapa para Usuario de Urgencia");

	}



	function consultarTipoMapaUsuarioUsuario( $objCon, $idUsuario ) {

		$sql = "	SELECT
						acceso.usuario.usu_conf_urgencia
					FROM
						acceso.usuario
					WHERE
						acceso.usuario.idusuario = '{$idUsuario}'  ";

		$response = $objCon->consultaSQL($sql, "Error al Obtener Datos de Tipo de Mapa según Usuario");

		return $response[0];

	}



	function consultarDatosPacienteParaDesplegarEnCategorizacion ( $objCon, $idDau ) {


	}

}
?>
