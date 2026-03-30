<?php
class Hoja_enfermeria{

	function InsertFormularioEnfermeria($objCon, $parametros) {
	    $campos_validos = [
	        'fecha_entrega', 'hora_entrega', 'nombre', 'edad', 'prevision', 'motivo_consulta',
	        'frm_hta', 'frm_diabetes', 'otras', 'frm_quirurgicos', 'frm_hta_alergias',
	        'frm_desconocida',  'frm_estado_general', 'frm_estado_conciente',
	        'frm_cond_higienicas', 'frm_piel_mucosa', 'frm_lesiones', 'frm_ubicacion',
	        'frm_heridas', 'frm_temperatura', 'frm_obs_piel_ubicacion', 'tipo_riesgo',
	        'puntaje_braden', 'ojos', 'verbal', 'motora', 'puntaje_glasgow', 'frm_heridas_cabeza',
	        'frm_pupilas', 'Frm_reflejo_foto', 'frm_bucal_protesis', 'frm_fractura_dentaria',
	        'frm_yugulares', 'frm_cervicales', 'frm_heridas_torax', 'frm_respiracion',
	        'frm_doloreva', 'frm_distension', 'frm_heridas_abdomen', 'frm_movilidad_extremidades',
	        'frm_heridas_extremidades', 'frm_deformidad_extremidades', 'frm_fracturas_extremidades',
	        'frm_amputacion_extremidades', 'frm_luxacion_extremidades', 'frm_genitales_extremidades',
	        'obs_genitales', 'frm_contencion_fisica', 'frm_ext_superiores', 'frm_ext_inferiores',
	        'frm_hoja_contencion', 'obs_hoja_contencion', 'obs_examenes_interconsultas',
	        'obs_tratamientos_box', 'frm_elementos_via', 'frm_elementos_sng', 'frm_elementos_sonda_foley',
	        'frm_elementos_tet', 'frm_valor_recaudacion', 'frm_articulos_personales', 'frm_custodia_cr',
	        'obs_custodia_cr', 'utiles_aseo', 'vestuario', 'ropa_cama','usuario_creacion','hora_creacion','fecha_creacion','frm_alergia','frm_medicamentos_medicos','frm_evolucion_enfermeria','dau_id','sensorial','humedad','actividad','movilidad','nutricion','lesion','puntaje_total', 'frm_otro_util_aseo', 'frm_otra_prenda' , 'frm_otra_ropa_cama','totalGlasgow',
	        'jabon','shampoo','pasta','desodorante','confort','pañal','pijama','pantuflas','polera','poleron','pantalon','almohada','frazada','sabana','frm_via_telefonica' ,'frm_via_presencial' ,'vacuna_covid' ,'vacuna_influenza' ,'contacto_numero' ,'contacto_nombre' ,'contacto_parentesco' ,'frm_examen_fisico_general' ,'nombre_enfermero' ,'nombre_enfermero_rut' ,'entrega_fecha','frm_num_inventario','obs_enfermeria' ,'movilidad_ped', 'actividad_ped', 'Sensorial_ped','humedad_ped','friccion_ped','nutricion_ped','perfusion_ped','cond_fisica','humedad_neo','estado_mental','movilidad_neo','actividad_neo','nutricion_neo','tipobraden','tipoGlasgow'      
	    ];

	    $campos = [];
	    $valores = [];

	    foreach ($campos_validos as $campo) {
	        if (isset($parametros[$campo])) {
	            $campos[] = $campo;
	            $valores[] = "'" . addslashes($parametros[$campo]) . "'";
	        }
	    }

	    if (empty($campos)) return false;

	    $sql = "INSERT INTO dau.formulario_enfermeria (" . implode(', ', $campos) . ") VALUES (" . implode(', ', $valores) . ")";
	    $objCon->ejecutarSQL($sql, "ERROR AL Insertar en InsertFormularioEnfermeria");

	    return $objCon->lastInsertId();
	}
	function UpdateFormularioEnfermeria($objCon, $parametros, $id_hojaEnfermeria) {
	    $campos_validos = [ 'fecha_entrega', 'hora_entrega', 'nombre', 'edad', 'prevision', 'motivo_consulta',
	        'frm_hta', 'frm_diabetes', 'otras', 'frm_quirurgicos', 'frm_hta_alergias',
	        'frm_desconocida',  'frm_estado_general', 'frm_estado_conciente',
	        'frm_cond_higienicas', 'frm_piel_mucosa', 'frm_lesiones', 'frm_ubicacion',
	        'frm_heridas', 'frm_temperatura', 'frm_obs_piel_ubicacion', 'tipo_riesgo',
	        'puntaje_braden', 'ojos', 'verbal', 'motora', 'puntaje_glasgow', 'frm_heridas_cabeza',
	        'frm_pupilas', 'Frm_reflejo_foto', 'frm_bucal_protesis', 'frm_fractura_dentaria',
	        'frm_yugulares', 'frm_cervicales', 'frm_heridas_torax', 'frm_respiracion',
	        'frm_doloreva', 'frm_distension', 'frm_heridas_abdomen', 'frm_movilidad_extremidades',
	        'frm_heridas_extremidades', 'frm_deformidad_extremidades', 'frm_fracturas_extremidades',
	        'frm_amputacion_extremidades', 'frm_luxacion_extremidades', 'frm_genitales_extremidades',
	        'obs_genitales', 'frm_contencion_fisica', 'frm_ext_superiores', 'frm_ext_inferiores',
	        'frm_hoja_contencion', 'obs_hoja_contencion', 'obs_examenes_interconsultas',
	        'obs_tratamientos_box', 'frm_elementos_via', 'frm_elementos_sng', 'frm_elementos_sonda_foley',
	        'frm_elementos_tet', 'frm_valor_recaudacion', 'frm_articulos_personales', 'frm_custodia_cr',
	        'obs_custodia_cr', 'utiles_aseo', 'vestuario', 'ropa_cama','usuario_creacion','frm_alergia','frm_medicamentos_medicos','frm_evolucion_enfermeria','dau_id','sensorial','humedad','actividad','movilidad','nutricion','lesion','puntaje_total', 'frm_otro_util_aseo', 'frm_otra_prenda' , 'frm_otra_ropa_cama' ,'totalGlasgow',
	        'jabon','shampoo','pasta','desodorante','confort','pañal','pijama','pantuflas','polera','poleron','pantalon','almohada','frazada','sabana' ,'frm_via_telefonica' ,'frm_via_presencial' ,'vacuna_covid' ,'vacuna_influenza' ,'contacto_numero' ,'contacto_nombre' ,'contacto_parentesco','frm_examen_fisico_general' ,'nombre_enfermero' ,'nombre_enfermero_rut' ,'entrega_fecha' ,'frm_num_inventario','obs_enfermeria','movilidad_ped', 'actividad_ped', 'Sensorial_ped','humedad_ped','friccion_ped','nutricion_ped','perfusion_ped','cond_fisica','humedad_neo','estado_mental','movilidad_neo','actividad_neo','nutricion_neo','tipobraden','tipoGlasgow'   
	    ];
	    $updates = [];

	    foreach ($campos_validos as $campo) {
	        if (isset($parametros[$campo])) {
	            $updates[] = "$campo = '" . addslashes($parametros[$campo]) . "'";
	        }
	    }

	    if (empty($updates)) return false;

	    $sql = "UPDATE dau.formulario_enfermeria SET " . implode(', ', $updates) . " WHERE id_hojaEnfermeria = '" . intval($id_hojaEnfermeria) . "'";
	    return $objCon->ejecutarSQL($sql, "ERROR AL Actualizar en UpdateFormularioEnfermeria");
	}
	function DeleteFormularioEnfermeria($objCon, $id_hojaEnfermeria) {
	    $sql = "DELETE FROM dau.formulario_enfermeria WHERE id_hojaEnfermeria = '" . intval($id_hojaEnfermeria) . "'";
	    return $objCon->ejecutarSQL($sql, "ERROR AL Eliminar en DeleteFormularioEnfermeria");
	}
	function SelectFormularioEnfermeriaById($objCon, $dau_id) {
	    $sql = "SELECT * FROM dau.formulario_enfermeria WHERE dau_id = '" . intval($dau_id) . "'";
	   	$datos = $objCon->consultaSQL($sql,"<br>ERROR AL ATENCION SelectFormularioEnfermeriaById<br>");
		return $datos;

	}
	function SelectIndicaciones_enfermeria($objCon, $parametros) {
		$condicion = "";
		$sql = " 
			SELECT
			    ie.id,
			    ie.procedimiento_id,
			    ie.comentario,
			    ie.subcategoria_id,
			    accesoUsuarioSolicita.nombreusuario AS nombreUsuario,
			    ie.fecha,
			    ie.hora,
			    UPPER(pe.nombre) AS nombre_procedimiento,
			    pe.descripcion,
			    UPPER(sp.nombre) AS nombre_subProcedimiento,
			    sp.detalle,
			    ie.dau_id,
			    ie.estado,
			    eie.descripcion_estado,
			    tie.descripcion_tipo,

			    tr_creado.fecha AS fecha_creado,
			    tr_creado.hora AS hora_creado,
			    tr_creado.usuario AS usuario_creado,

			    tr_iniciado.fecha AS fecha_iniciado,
			    tr_iniciado.hora AS hora_iniciado,
			    tr_iniciado.usuario AS usuario_iniciado,

			    tr_aplicado.fecha AS fecha_aplicado,
			    tr_aplicado.hora AS hora_aplicado,
			    tr_aplicado.usuario AS usuario_aplicado,

			    tr_rechazado.fecha AS fecha_rechazado,
			    tr_rechazado.hora AS hora_rechazado,
			    tr_rechazado.usuario AS usuario_rechazado

			FROM dau.indicaciones_enfermeria ie
			INNER JOIN dau.procedimientos_enfermeria pe ON ie.procedimiento_id = pe.id
			INNER JOIN dau.subcategorias_procedimiento sp ON ie.subcategoria_id = sp.id AND pe.id = sp.procedimiento_id
			LEFT JOIN acceso.usuario AS accesoUsuarioSolicita ON ie.usuario = accesoUsuarioSolicita.idusuario
			INNER JOIN dau.estado_indicaciones_enfermeria eie ON ie.estado = eie.id_estado_ind_enf
			INNER JOIN dau.cabecera_indicaciones_enfermeria cie ON ie.id_cabecera_indicaciones_enfermeria = cie.id
			INNER JOIN dau.tipo_indicacion_enfermeria tie ON cie.tipo_indicacion = tie.id_tipo_ind_enfermeria

			LEFT JOIN dau.trazabilidad_indicaciones_enfermeria tr_creado 
			  ON tr_creado.id_indicacion_enfermeria = ie.id AND tr_creado.estado = 1
			LEFT JOIN dau.trazabilidad_indicaciones_enfermeria tr_iniciado 
			  ON tr_iniciado.id_indicacion_enfermeria = ie.id AND tr_iniciado.estado = 2
			LEFT JOIN dau.trazabilidad_indicaciones_enfermeria tr_aplicado 
			  ON tr_aplicado.id_indicacion_enfermeria = ie.id AND tr_aplicado.estado = 3
			LEFT JOIN dau.trazabilidad_indicaciones_enfermeria tr_rechazado 
			  ON tr_rechazado.id_indicacion_enfermeria = ie.id AND tr_rechazado.estado = 4

			";
			if(isset($parametros['dau_id'])){
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .= " ie.dau_id  = '{$parametros['dau_id']}'    ";
			}
			if(isset($parametros['indicacion_id'])){
				$condicion .= ($condicion == "") ? " WHERE " : " AND ";
				$condicion .=" ie.id = '" . $parametros['indicacion_id'] . "'  ";
			}
			$sql .= " ".$condicion." ORDER BY ie.id DESC ";
		$datos = $objCon->consultaSQL($sql, "<br>ERROR AL ATENCION SelectIndicaciones_enfermeria<br>");
		return $datos;
	}
	function SelectTratamientosRealizados($objCon, $regId) {
		$sql = " 
	SELECT
		solicitud_indicaciones.sol_ind_id AS sol_id,
		solicitud_indicaciones.sol_ind_servicio as tipo_solicitud_cabecera,
		solicitud_indicaciones.sol_ind_estado AS estado,
		estado_indicacion.est_descripcion AS estadoDescripcion,
		solicitud_indicaciones.sol_ind_servicio AS servicio,
		UPPER(tipo_indicaciones.ser_descripcion) AS descripcion,
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
		INNER JOIN rce.tipo_indicaciones ON solicitud_indicaciones.sol_ind_servicio = tipo_indicaciones.ser_codigo
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_indicaciones.sol_ind_usuarioInserta = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_indicaciones.sol_ind_usuarioAplica = accesoUsuarioAplica.idusuario
		LEFT JOIN rce.clasificacion_tratamiento ON rce.clasificacion_tratamiento.idClasificacion = rce.solicitud_indicaciones.sol_clasificacionTratamiento
		WHERE solicitud_indicaciones.regId = '" . intval($regId) . "' AND solicitud_indicaciones.sol_ind_estado != 8
		ORDER BY solicitud_indicaciones.sol_ind_fechaInserta DESC
		";
		// echo $sql;
		$datos = $objCon->consultaSQL($sql, "<br>ERROR AL ATENCION SelectTratamientosRealizados<br>");
		return $datos;
	}
	function SelectExamenesRealizados($objCon, $regId) {
		$sql = " 
			SELECT *
			FROM (
				-- Primer bloque: solicitud_imagenologia
				SELECT
					rce.solicitud_imagenologia.sol_ima_id AS sol_id,
					'1' AS tipo_solicitud_cabecera,
					rce.solicitud_imagenologia.sol_ima_estado AS estado,
					UPPER(rce.estado_indicacion.est_descripcion) AS estadoDescripcion,
					rce.solicitud_imagenologia.sol_ima_tipo AS servicio,
					UPPER('Solicitud Imagenologia') AS descripcion,
					rce.solicitud_imagenologia.sol_ima_usuarioInserta AS usuarioInserta,
					rce.solicitud_imagenologia.sol_ima_fechaInserta AS fechaInserta,
					IF(DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28', le.prestaciones_imagenologia.tipo_examen COLLATE utf8_general_ci, rce.detalle_solicitud_imagenologia.det_ima_tipo_examen) AS tipoExamen,
					IF(DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28', le.prestaciones_imagenologia.id_prestaciones, rce.detalle_solicitud_imagenologia.det_ima_codigo) AS codigoExamen,
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
					'' AS usuarioTomaMuestra,
					'' AS fechaTomaMuestra,
					IF(DATE(rce.solicitud_imagenologia.sol_ima_fechaInserta) >= '2024-05-28', le.prestaciones_imagenologia.examen COLLATE utf8_general_ci, rce.detalle_solicitud_imagenologia.det_ima_descripcion) AS Prestacion
				FROM
					rce.solicitud_imagenologia
					LEFT JOIN rce.detalle_solicitud_imagenologia ON rce.detalle_solicitud_imagenologia.sol_ima_id = rce.solicitud_imagenologia.sol_ima_id
					LEFT JOIN rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
					LEFT JOIN le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
					LEFT JOIN rce.estado_indicacion ON rce.solicitud_imagenologia.sol_ima_estado = rce.estado_indicacion.est_id
					LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_imagenologia.sol_ima_usuarioInserta = accesoUsuarioSolicita.idusuario
					LEFT JOIN acceso.usuario accesoUsuarioAplica ON rce.solicitud_imagenologia.sol_ima_usuarioAplica = accesoUsuarioAplica.idusuario
					LEFT JOIN rayos.solicitud_imagen_camas ON rce.solicitud_imagenologia.sol_ima_id = rayos.solicitud_imagen_camas.SIC_RCE_sol_ima_id
					LEFT JOIN rayos.solicitud_cabecera_img_registro ON rayos.solicitud_imagen_camas.id_solicitud_cabecera_registro = rayos.solicitud_cabecera_img_registro.id_solicitud_cabecera_registro 
				WHERE
					rce.solicitud_imagenologia.regId =  '" . intval($regId) . "'
					AND rce.solicitud_imagenologia.sol_ima_estado != 8 
				GROUP BY sol_id 

				UNION

				-- Segundo bloque: solicitud_laboratorio
				SELECT
					solicitud_laboratorio.sol_lab_id AS sol_id,
					'3' AS tipo_solicitud_cabecera,
					MIN(solicitud_laboratorio.sol_lab_estado) AS estado,
					UPPER(MAX(estado_indicacion.est_descripcion)) AS estadoDescripcion,
					solicitud_laboratorio.sol_lab_tipo AS servicio,
					UPPER('Solicitud Laboratorio') AS descripcion,
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
					solicitud_laboratorio.sol_lab_usuarioTomaMuestra AS usuarioTomaMuestra,
					MAX(solicitud_laboratorio.sol_lab_fechaTomaMuestra) AS fechaTomaMuestra,
					GROUP_CONCAT(DISTINCT detalle_solicitud_laboratorio.det_lab_descripcion) AS prestaciones
				FROM
					rce.solicitud_laboratorio
					INNER JOIN rce.detalle_solicitud_laboratorio ON solicitud_laboratorio.sol_lab_id = detalle_solicitud_laboratorio.sol_lab_id
					INNER JOIN rce.estado_indicacion ON solicitud_laboratorio.sol_lab_estado = estado_indicacion.est_id
					INNER JOIN laboratorio.prestacion ON prestacion.pre_codOmega = detalle_solicitud_laboratorio.det_lab_codigo COLLATE utf8_spanish_ci
					LEFT JOIN acceso.usuario accesoUsuarioSolicita ON solicitud_laboratorio.sol_lab_usuarioInserta = accesoUsuarioSolicita.idusuario
					LEFT JOIN acceso.usuario accesoUsuarioAplica ON solicitud_laboratorio.sol_lab_usuarioAplica = accesoUsuarioAplica.idusuario 
				WHERE
					solicitud_laboratorio.regId = '" . intval($regId) . "'
					AND solicitud_laboratorio.sol_lab_estado != 8 
				GROUP BY
					solicitud_laboratorio.sol_lab_fechaInserta,
					prestacion.tubo_id,
					solicitud_laboratorio.sol_lab_estado

					UNION
		SELECT
			solicitud_especialista.SESPid AS sol_id,
				'5' as tipo_solicitud_cabecera,
			solicitud_especialista.SESPestado AS estado,
			UPPER(estado_indicacion.est_descripcion) AS estadoDescripcion,
			solicitud_especialista.SESPtipo AS servicio,
			UPPER('Solicitud Especialista') AS descripcion,
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
			solicitud_especialista.SESPusuarioGestionRealizada  AS usuarioTomaMuestra,
			'' AS fechaTomaMuestra,
			UPPER(CASE 
                    WHEN rce.solicitud_especialista.SESPfuente = 'P' THEN parametros_clinicos.especialidad.ESPdescripcion 
                    ELSE otro_especialista.descripcion_otro 
                END) AS Prestacion



		FROM rce.solicitud_especialista
		INNER JOIN rce.estado_indicacion ON solicitud_especialista.SESPestado= estado_indicacion.est_id
		LEFT JOIN parametros_clinicos.especialidad 
                ON rce.solicitud_especialista.SESPfuente = 'P' 
                AND rce.solicitud_especialista.SESPidEspecialidad = parametros_clinicos.especialidad.ESPcodigo
            LEFT JOIN rce.otro_especialista 
                ON rce.solicitud_especialista.SESPfuente = 'O' 
                AND rce.solicitud_especialista.SESPidEspecialidad = otro_especialista.id_otro
		LEFT JOIN acceso.usuario accesoUsuarioSolicita ON rce.solicitud_especialista.SESPusuario = accesoUsuarioSolicita.idusuario
		LEFT JOIN acceso.usuario accesousuarioAplica ON rce.solicitud_especialista.SESPusuarioAplica = accesoUsuarioAplica.idusuario
		WHERE solicitud_especialista.SESPidRCE = '" . intval($regId) . "'
			) AS datos 
			ORDER BY fechaInserta DESC
		";
		// echo $sql;
		$datos = $objCon->consultaSQL($sql, "<br>ERROR AL ATENCION SelectExamenesRealizados<br>");
		return $datos;
	}
}
?>