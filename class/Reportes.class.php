<?php
	class Reportes{
		function registroAtencionDiaria($objCon,$parametros){

			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.est_id,
						dau.dau.dau_categorizacion,
						dau.dau.dau_cierre_administrativo,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						dau.dau.dau_cierre_servicio,
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaEgreso,
						camas.sscc.servicio,
						dau.dau.dau_paciente_edad,
						dau.dau.idctacte,
						dau.dau.dau_indicacion_egreso_fecha AS fechaHoraIndicacionEgreso,
						dau.dau.dau_indicacion_egreso_aplica_fecha AS fechaHoraAplicacionEgreso,
						paciente.paciente.id,
						dau.dau.dau_indicacion_egreso
					FROM
						dau.dau
					LEFT JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					LEFT JOIN
						camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
					WHERE
						dau.est_id='5'
					AND
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') = '{$parametros['frm_inicio']}'
					ORDER BY
						dau.dau.dau_id ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte Registro Atención Diaria<br>");

			return $datos;

		}

		function registroAtencionDiariaPanelViral($objCon,$parametros){

			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.est_id,
						dau.dau.dau_categorizacion,
						dau.dau.dau_cierre_administrativo,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,
						dau.dau.dau_cierre_servicio,
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaEgreso,
						camas.sscc.servicio,
						dau.dau.dau_paciente_edad,
						dau.dau.idctacte,
						dau.dau.dau_indicacion_egreso_fecha AS fechaHora,
						paciente.paciente.id,
						dau.dau.dau_indicacion_egreso,
						CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS 'cie10',
						GROUP_CONCAT(IF(solicitud_indicaciones.sol_ind_servicio = 4 AND rce.solicitud_indicaciones.sol_ind_descripcion LIKE '%panel viral%', 'Si', NULL)) AS panelViral
					FROM
						dau.dau
					LEFT JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					LEFT JOIN
						camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
					LEFT JOIN
						cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					LEFT JOIN
						rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
					LEFT JOIN
						rce.solicitud_indicaciones ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
					WHERE
						dau.est_id='5'
					AND
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') = '{$parametros['frm_inicio']}'
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					GROUP BY
						dau.dau.dau_id
					ORDER BY
						dau.dau.dau_id ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atención diaria panel viral<br>");

			return $datos;

		}
		function reporteREM08SeccionB1 ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_categorizacion AS categorizacionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion, dau_categorizacion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion = 3
					AND
						dau.dau.dau_medico_involucrado_ginecologia = 'S'
					AND
						dau.dau.dau_categorizacion IN ('C1', 'C2', 'C3', 'C4', 'C5')
					ORDER BY
						dau.dau.dau_categorizacion, paciente.paciente.sexo DESC, edadPaciente   ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}
		function registroHospitalizacion($objCon,$parametros){

			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.est_id,
						dau.dau.dau_categorizacion,
						dau.dau.dau_cierre_administrativo,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						dau.dau.dau_cierre_servicio,
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaEgreso,
						camas.sscc.servicio,
						dau.dau.dau_paciente_edad,
						dau.dau.idctacte,
						dau.dau.dau_indicacion_egreso_fecha AS fechaHoraIndicacionEgreso,
						dau.dau.dau_indicacion_egreso_aplica_fecha AS fechaHoraAplicacionEgreso,
						paciente.paciente.id,
						dau.dau.dau_indicacion_egreso
					FROM
						dau.dau
					LEFT JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					LEFT JOIN
						camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
					WHERE
						dau.est_id='5'
					AND
						dau.dau_indicacion_egreso = '4'
					AND
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					ORDER BY
						dau.dau.dau_id ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte Hospitalizacion<br>");

			return $datos;

		}



		function registroHospitalizacionPanelViral($objCon,$parametros){

			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.est_id,
						dau.dau.dau_categorizacion,
						dau.dau.dau_cierre_administrativo,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,
						dau.dau.dau_cierre_servicio,
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaEgreso,
						camas.sscc.servicio,
						dau.dau.dau_paciente_edad,
						dau.dau.idctacte,
						dau.dau.dau_indicacion_egreso_fecha AS fechaHora,
						paciente.paciente.id,
						dau.dau.dau_indicacion_egreso,
						CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS 'cie10',
						GROUP_CONCAT(IF(solicitud_indicaciones.sol_ind_servicio = 4 AND rce.solicitud_indicaciones.sol_ind_descripcion LIKE '%panel viral%', 'Si', NULL)) AS panelViral
					FROM
						dau.dau
					LEFT JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					LEFT JOIN
						camas.sscc ON dau.dau.dau_cierre_servicio = camas.sscc.id
					LEFT JOIN
						cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					LEFT JOIN
						rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
					LEFT JOIN
						rce.solicitud_indicaciones ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
					WHERE
						dau.est_id='5'
					AND
						dau.dau_indicacion_egreso = '4'
					AND
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					GROUP BY
						dau.dau.dau_id
					ORDER BY
						dau.dau.dau_id ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte Hospitalizacion<br>");

			return $datos;

		}



		function registroAlcoholemia($objCon,$parametros){

			$sql = "SELECT
						paciente.paciente.rut,
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						dau.dau.dau_alcoholemia_numero_frasco,
						DATE_FORMAT(dau.dau.dau_alcoholemia_fecha, '%Y-%m-%d') AS fechaAlcoholemia,
						dau.dau.dau_alcoholemia_fecha AS fechaHora,
						dau.etilico.eti_descripcion,
						dau.dau.dau_alcoholemia_apreciacion,
						parametros_clinicos.profesional.PROdescripcion,
						paciente.paciente.rut_extranjero,
						dau.dau.dau_id,
						paciente.paciente.extranjero
					FROM
						paciente.paciente
					INNER JOIN
						dau.dau ON paciente.paciente.id = dau.dau.id_paciente
					INNER JOIN
						dau.etilico ON dau.dau.dau_alcoholemia_estado_etilico = dau.etilico.eti_id
					INNER JOIN
						parametros_clinicos.profesional ON dau.dau.dau_alcoholemia_medico = parametros_clinicos.profesional.PROcodigo
					WHERE
						DATE_FORMAT(dau.dau.dau_alcoholemia_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					ORDER BY
						dau.dau.dau_id";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte registro Alcoholemia<br>");

			return $datos;

		}



		function libroMaternidad($objCon,$parametros){

			$sql = "SELECT
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						dau.dau.dau_id,
						paciente.nacionalidad.nacionalidadnombre,
						dau.dau.idctacte,
						dau.dau.dau_admision_fecha,
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmisionSH,
						dau.dau.dau_indicacion_egreso_aplica_fecha,
						DATE_FORMAT(dau.dau.dau_indicacion_egreso_aplica_fecha, '%Y-%m-%d') AS fechaEgresoSH,
						cie10.cie10.nombreCIE,
						paciente.prevision.prevision
					FROM
						dau.dau
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						paciente.nacionalidad ON paciente.nacionalidad.nacionalidad = paciente.paciente.nacionalidad
					LEFT JOIN
						cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					INNER JOIN
						paciente.prevision ON dau.dau.dau_paciente_prevision = paciente.prevision.id
					WHERE
						est_id='5'
					AND
						dau_indicacion_egreso = '4'
					AND
						dau_cierre_servicio = '16'
					AND
						dau_indicacion_egreso_aplica_fecha !=''
					AND
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					ORDER BY
						dau.dau.dau_id";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte libro Maternidad<br>");

			return $datos;

		}



		function accidentesTrabajo($objCon,$parametros){

			$sql = "SELECT
						paciente.paciente.nombres,
						paciente.paciente.apellidopat,
						paciente.paciente.apellidomat,

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						dau.dau.dau_id,
						dau.dau.dau_admision_fecha,
						paciente.paciente.rut,
						paciente.paciente.rut_extranjero,
						dau.medio_llegada.med_descripcion,
						dau.indicacion_egreso.ind_egr_descripcion,
						paciente.paciente.extranjero
					FROM
						dau.dau
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.medio_llegada ON dau.medio_llegada.med_id = dau.dau.dau_forma_llegada
					INNER JOIN
						dau.indicacion_egreso ON dau.dau.dau_indicacion_egreso = dau.indicacion_egreso.ind_egr_id
					WHERE
						dau.dau.dau_accidente_transito_tipo <> 0
					AND
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
				  	ORDER BY
					  	dau.dau.dau_id ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte accidentesTrabajo<br>");

			return $datos;

		}



		function prestacionesPorEdad($objCon,$parametros){

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau_cierre_fecha_final' : 'dau.dau_admision_fecha';

			$sql = "SELECT
						dau.dau_cierre_cie10,
						dau.dau_paciente_edad,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision,
						dau.est_id,
						atencion.ate_descripcion
				  	FROM
						dau.dau
				  	INNER JOIN
					  	dau.atencion ON atencion.ate_id = dau.dau_atencion
				  	WHERE
					  	DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id = 5
					AND
						atencion.ate_id IN (1,2)
					AND
						dau.dau_cierre_cie10 IS NOT NULL";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte accidentesTrabajo<br>");

			return $datos;

		}



		function prestacionesPorAccidente($objCon,$parametros){

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau_cierre_fecha_final' : 'dau.dau_admision_fecha';

			$sql = "SELECT
						dau.dau_cierre_cie10,
						dau.dau_paciente_edad,
						dau.dau_admision_fecha,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision
					FROM
						dau.dau
					WHERE
						DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.dau_motivo_consulta=1
					AND
						dau.dau_tipo_accidente=3
					AND
						dau.est_id = 5
					AND
						dau.dau_atencion IN (1,2)
					AND
						dau.dau_cierre_cie10 IS NOT NULL";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionesPorAccidente<br>");

			return $datos;

		}



		function prestacionEdadTotal($objCon,$parametros){

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau_cierre_fecha_final' : 'dau.dau_admision_fecha';

			$sql = "SELECT
						dau.dau_cierre_cie10,
						dau.dau_paciente_edad,
						dau.dau_admision_fecha,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision
					FROM
						dau.dau
					LEFT JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_id = dau_post_indicacion_egreso.idDau
					WHERE
						DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.dau_indicacion_egreso = 4
					AND
						dau.est_id = 5
					AND
						dau.dau_atencion IN (1,2)
					AND
						dau.dau_cierre_cie10 IS NOT NULL
					AND
						( ISNULL(dau_post_indicacion_egreso.tipoPostIndicacionEgreso) OR dau_post_indicacion_egreso.tipoPostIndicacionEgreso = 7 )";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionesPorAccidente<br>");

			return $datos;

		}



		function cantidadDAUCerrados($objCon,$parametros){

			$sql = "SELECT
						dau.dau_id
					FROM
						dau.dau
					WHERE
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'";

					if ($parametros['est_id'] == 'TODOS') {
						$condicion = " AND dau.est_id IN (5, 6, 7)";
					}
					else{
						$condicion = " AND dau.est_id = '{$parametros['est_id']}'";
					}

			$sql .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error cantidad NEA<br>");

			return $datos;

		}



		function diarreasAgudas($objCon,$parametros){

			$sql = "SELECT
						paciente.paciente.fechanac,
						paciente.paciente.sexo,
						dau.dau.dau_admision_fecha,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision,
						dayofweek(dau.dau.dau_admision_fecha) AS DiaSemana,
						dau.dau.dau_cierre_cie10,
						dau.dau.dau_paciente_edad
					FROM
						paciente.paciente
					INNER JOIN
						dau.dau ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						dau.dau.dau_cierre_cie10 BETWEEN 'A090' AND 'A09Z'
					AND
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id IN (1,2,3,4,5,8)
					AND
						dau.dau_atencion IN (1,2)
					AND
						paciente.paciente.sexo IS NOT NULL
					AND
						dau.dau.dau_paciente_edad IS NOT NULL
					AND
						dau.dau.dau_admision_fecha IS NOT NULL
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte diarreasAgudas<br>");

			return $datos;

		}



		function prestacionEdadTotalIra($objCon,$parametros){

			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.dau_cierre_cie10,
						dau.dau.dau_paciente_edad,
						paciente.paciente.sexo
				  	FROM
						paciente.paciente
				  	INNER JOIN
					  	dau.dau ON dau.dau.id_paciente = paciente.paciente.id
				  	WHERE
					  	DATE_FORMAT(dau.dau_admision_fecha,'%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id IN (1,2,3,4,5,8)
					AND
						dau.dau_atencion IN (1,2)
					AND
						paciente.paciente.sexo IS NOT NULL
					AND
						dau.dau.dau_paciente_edad IS NOT NULL
					AND
						dau.dau.dau_admision_fecha IS NOT NULL
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionEdadTotalIra<br>");

			return $datos;

		}



		function rem08($objCon,$parametros){
			$sql = "SELECT
						dau.dau.dau_id,
						dau.dau.est_id,
						dau.dau.id_paciente,
						dau.dau.idctacte,
						dau.dau.dau_admision_fecha,
						dau.dau.dau_admision_usuario,
						dau.dau.dau_categorizacion_actual,
						dau.dau.dau_categorizacion_actual_fecha,
						dau.dau.dau_categorizacion_actual_usuario,
						dau.dau.dau_ingreso_sala_fecha,
						dau.dau.dau_ingreso_sala_usuario,
						dau.dau.dau_inicio_atencion_fecha,
						dau.dau.dau_inicio_atencion_usuario,
						dau.dau.dau_indicacion_egreso,
						dau.dau.dau_indicacion_egreso_fecha,
						dau.dau.dau_indicacion_egreso_usuario,
						dau.dau.dau_indicacion_egreso_aplica_fecha,
						dau.dau.dau_indicacion_egreso_aplica_usuario,
						dau.dau.dau_apreciacion_diagnostica,
						dau.dau.dau_terapia_inicial,
						dau.dau.dau_paciente_aps,
						dau.dau.dau_paciente_domicilio,
						dau.dau.dau_paciente_domicilio_tipo,
						dau.dau.dau_paciente_edad,
						dau.dau.dau_paciente_prevision,
						dau.dau.dau_paciente_forma_pago,
						dau.dau.dau_atencion,
						dau.dau.dau_motivo_consulta,
						dau.dau.dau_motivo_descripcion,
						dau.dau.dau_forma_llegada,
						dau.dau.dau_mordedura,
						dau.dau.dau_intoxicacion,
						dau.dau.dau_quemadura,
						dau.dau.dau_imputado,
						dau.dau.dau_reanimacion,
						dau.dau.dau_tipo_accidente,
						dau.dau.dau_accidente_escolar_institucion,
						dau.dau.dau_accidente_escolar_numero,
						dau.dau.dau_accidente_escolar_nombre,
						dau.dau.dau_accidente_trabajo_mutualidad,
						dau.dau.dau_accidente_transito_tipo,
						dau.dau.dau_accidente_hogar_lugar,
						dau.dau.dau_accidente_otro_lugar,
						dau.dau.dau_agresion_vif,
						dau.dau.dau_alcoholemia_fecha,
						dau.dau.dau_alcoholemia_apreciacion,
						dau.dau.dau_alcoholemia_numero_frasco,
						dau.dau.dau_alcoholemia_resultado,
						dau.dau.dau_alcoholemia_estado_etilico,
						dau.dau.dau_alcoholemia_medico,
						dau.dau.dau_defuncion_fecha,
						dau.dau.dau_defuncion_usuario,
						dau.dau.dau_pyxis,
						dau.dau.dau_cierre_administrativo,
						dau.dau.dau_cierre_condicion_ingreso_id,
						dau.dau.dau_cierre_pronostico_id,
						dau.dau.dau_cierre_peso,
						dau.dau.dau_cierre_estatura,
						dau.dau.dau_cierre_tratamiento_id,
						dau.dau.dau_cierre_atendidopor_id,
						dau.dau.dau_cierre_profesional_id,
						dau.dau.dau_cierre_turno_id,
						dau.dau.dau_cierre_hora_atencion,
						dau.dau.dau_cierre_auge,
						dau.dau.dau_cierre_entrega_postinor,
						dau.dau.dau_cierre_hepatitisB,
						dau.dau.dau_cierre_pertinencia,
						dau.dau.dau_cierre_servicio,
						dau.dau.dau_cierre_cie10,
						dau.dau.dau_cierre_administrativo_observacion,
						dau.dau.dau_cierre_administrativo_usuario,
						dau.dau.dau_cierre_administrativo_fecha,
						paciente.paciente.sexo,
						dau.atendido_por.ate_atendidopor_nombre,
						dau.atendido_por.ate_atendidopor_id
					FROM
						dau.dau
					INNER JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					left JOIN
						dau.atendido_por ON dau.atendido_por.ate_atendidopor_id = dau.dau.dau_cierre_atendidopor_id
					WHERE
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id NOT IN ('6','7') ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionEdadTotalIra<br>");

			return $datos;

		}



		function rem08mordedura($objCon,$parametros){

			$sql = "SELECT
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='M' && datos.dau_mordedura = 1,1,0)) as 0a4m1,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='M' && datos.dau_mordedura = 2,1,0)) as 0a4m2,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='M' && datos.dau_mordedura = 6,1,0)) as 0a4m6,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='M' && datos.dau_mordedura = 9,1,0)) as 0a4m9,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='M' && datos.dau_mordedura = 10,1,0)) as 0a4m10,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='F' && datos.dau_mordedura = 1,1,0)) as 0a4f1,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='F' && datos.dau_mordedura = 2,1,0)) as 0a4f2,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='F' && datos.dau_mordedura = 6,1,0)) as 0a4f6,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='F' && datos.dau_mordedura = 9,1,0)) as 0a4f9,
						sum(if((datos.dau_paciente_edad >= 0 && datos.dau_paciente_edad <= 4) && datos.sexo='F' && datos.dau_mordedura = 10,1,0)) as 0a4f10,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='M' && datos.dau_mordedura = 1,1,0)) as 5a9m1,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='M' && datos.dau_mordedura = 2,1,0)) as 5a9m2,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='M' && datos.dau_mordedura = 6,1,0)) as 5a9m6,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='M' && datos.dau_mordedura = 9,1,0)) as 5a9m9,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='M' && datos.dau_mordedura = 10,1,0)) as 5a9m10,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='F' && datos.dau_mordedura = 1,1,0)) as 5a9f1,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='F' && datos.dau_mordedura = 2,1,0)) as 5a9f2,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='F' && datos.dau_mordedura = 6,1,0)) as 5a9f6,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='F' && datos.dau_mordedura = 9,1,0)) as 5a9f9,
						sum(if((datos.dau_paciente_edad >= 5 && datos.dau_paciente_edad <= 9) && datos.sexo='F' && datos.dau_mordedura = 10,1,0)) as 5a9f10,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='M' && datos.dau_mordedura = 1,1,0)) as 10a14m1,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='M' && datos.dau_mordedura = 2,1,0)) as 10a14m2,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='M' && datos.dau_mordedura = 6,1,0)) as 10a14m6,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='M' && datos.dau_mordedura = 9,1,0)) as 10a14m9,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='M' && datos.dau_mordedura = 10,1,0)) as 10a14m10,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='F' && datos.dau_mordedura = 1,1,0)) as 10a14f1,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='F' && datos.dau_mordedura = 2,1,0)) as 10a14f2,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='F' && datos.dau_mordedura = 6,1,0)) as 10a14f6,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='F' && datos.dau_mordedura = 9,1,0)) as 10a14f9,
						sum(if((datos.dau_paciente_edad >= 10 && datos.dau_paciente_edad <= 14) && datos.sexo='F' && datos.dau_mordedura = 10,1,0)) as 10a14f10,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='M' && datos.dau_mordedura = 1,1,0)) as 15amm1,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='M' && datos.dau_mordedura = 2,1,0)) as 15amm2,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='M' && datos.dau_mordedura = 6,1,0)) as 15amm6,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='M' && datos.dau_mordedura = 9,1,0)) as 15amm9,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='M' && datos.dau_mordedura = 10,1,0)) as 15amm10,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='F' && datos.dau_mordedura = 1,1,0)) as 15amf1,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='F' && datos.dau_mordedura = 2,1,0)) as 15amf2,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='F' && datos.dau_mordedura = 6,1,0)) as 15amf6,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='F' && datos.dau_mordedura = 9,1,0)) as 15amf9,
						sum(if((datos.dau_paciente_edad >= 15) && datos.sexo='F' && datos.dau_mordedura = 10,1,0)) as 15amf10
						FROM(
							SELECT
								dau.dau.dau_id,
								paciente.paciente.sexo,
								dau.mordedura.mor_descripcion,
								dau.dau.dau_paciente_edad,
								dau.dau.dau_mordedura
							FROM
								dau.dau
							INNER JOIN
								paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
							INNER JOIN
								dau.mordedura ON dau.dau.dau_mordedura = dau.mordedura.mor_id
							WHERE
								dau.mordedura.mor_id <> 0
							and
								dau_mordedura IN ('1','6','2','9','10')
							and
								DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
							AND
								dau.est_id NOT IN ('6','7')
							) as datos";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte prestacionEdadTotalIra<br>");

			return $datos;

		}



		function categorizacionUrgencia($objCon,$parametros,$fecha){

			$condicion = '';

			$sql = "SELECT
						dau.dau.dau_id,
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%Y-%m-%d') AS dau_admision_fecha,
						dau.dau.dau_categorizacion_actual,
						dau.dau.est_id,
						dau.dau.dau_atencion,
						dau.categorizacion.cat_nivel
					FROM
						dau.dau
					LEFT JOIN
						categorizacion ON dau.dau_categorizacion_actual = categorizacion.cat_id ";

					if ($parametros['frm_inicio']) {
						$condicion .= ($condicion == "") ? " WHERE " : " AND ";
						$condicion.="DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') = '$fecha'";
					}
					if ($parametros['tipoAtencion']!="0") {
						$condicion .= ($condicion == "") ? " WHERE " : " AND ";
						$condicion.="dau.dau.dau_atencion='{$parametros['tipoAtencion']}'";
					}else{
						$condicion .= ($condicion == "") ? " WHERE " : " AND ";
						$condicion.="dau.dau.dau_atencion IN (1,2,3)";
					}

			$sql  .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte categorizacionUrgencia<br>");

			return $datos;

		}



		function atencionesSinDiagnostico($objCon,$parametros){

			$condicion = '';

			$sql = "SELECT
						paciente.paciente.rut,
						dau.dau.dau_id,
						dau.dau.dau_atencion,
						dau.dau.dau_admision_fecha,
						paciente.paciente.rut_extranjero,
						paciente.paciente.extranjero
					FROM
						dau.dau
					INNER JOIN
						paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					WHERE
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id = 5
					AND
						(dau.dau_cierre_cie10 IS NULL OR dau.dau_cierre_cie10 = '')";

					if ($parametros['tipoAtencion'] == 0) {
						$condicion.=" AND dau.dau_atencion IN (1,2)";
					}else{
						$condicion.=" AND dau.dau_atencion = {$parametros['tipoAtencion']}";
					}

			$sql  .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionesSinDiagnostico<br>");

			return $datos;

		}



		function tiempoEsperaNuevo($objCon,$parametros){

			$sql = "SELECT
						paciente.paciente.sexo,
						dau.dau.dau_indicacion_egreso_aplica_fecha,
						dau.dau.dau_indicacion_egreso_fecha,
						SEC_TO_TIME((TIMESTAMPDIFF(MINUTE ,dau.dau_indicacion_egreso_fecha, dau.dau_indicacion_egreso_aplica_fecha ))*60) AS diferencia,
						paciente.paciente.nombres,
						paciente.paciente.fechanac,
						paciente.paciente.conveniopago,
						dau.dau.dau_paciente_edad
					FROM
						dau.dau
					INNER JOIN paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
					WHERE DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}' AND dau.est_id IN (1,2,3,4,8,5) AND dau.dau_indicacion_egreso IN (4)
					and dau.dau_atencion IN (1,2)";
		$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte tiempoEsperaNuevo<br>");
		return $datos;
		}



		function atencionAdulto($objCon,$parametros){
			$sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 1
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionAdulto<br>");
			return $datos;
		}



		function listarPacienteAdulto($objCon,$parametros){
			$sql="SELECT
					dau.dau.dau_indicacion_egreso_fecha,
					DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaIndicacion,
					dau.dau.dau_cierre_profesional_id,
					parametros_clinicos.profesional.PROdescripcion,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					dau.dau.id_paciente,
					dau.dau.dau_id
					FROM
					parametros_clinicos.profesional
					INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
					INNER JOIN paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
				WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 1 AND dau_cierre_profesional_id='{$parametros['dau_cierre_profesional_id']}'
				GROUP BY dau.dau.dau_id
				ORDER BY dau.dau.dau_indicacion_egreso_fecha ASC";
				$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte listarPacienteAdulto<br>");
				return $datos;
		}



		function atencionPediatrica($objCon,$parametros){
			 $sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 2
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionPediatrica<br>");
			return $datos;
		}



		function listarPacientePediatrico($objCon,$parametros){
			$sql="SELECT
					dau.dau.dau_indicacion_egreso_fecha,
					DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaIndicacion,
					dau.dau.dau_cierre_profesional_id,
					parametros_clinicos.profesional.PROdescripcion,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					dau.dau.id_paciente,
					dau.dau.dau_id
					FROM
					parametros_clinicos.profesional
					INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
					INNER JOIN paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
				WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 2
				GROUP BY dau.dau.dau_id
				ORDER BY dau.dau.dau_indicacion_egreso_fecha ASC";
				$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte listarPacientePediatrico<br>");
				return $datos;
		}



		function atencionGinecologica($objCon,$parametros){
			$sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 3
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionGinecologica<br>");
			return $datos;
		}



		function listarPacienteGinecologica($objCon,$parametros){
			$sql="SELECT
					dau.dau.dau_indicacion_egreso_fecha,
					DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') AS fechaIndicacion,
					dau.dau.dau_cierre_profesional_id,
					parametros_clinicos.profesional.PROdescripcion,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					dau.dau.id_paciente,
					dau.dau.dau_id
					FROM
					parametros_clinicos.profesional
					INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
					INNER JOIN paciente.paciente ON paciente.paciente.id = dau.dau.id_paciente
				WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '{$parametros['frm_fecha_admision_desde']}' AND '{$parametros['frm_fecha_admision_hasta']}' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 3
				GROUP BY dau.dau.dau_id
				ORDER BY dau.dau.dau_indicacion_egreso_fecha ASC";
				$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte listarPacienteGinecologica<br>");
				return $datos;
		}



		function atencionMedicasTurno($objCon,$parametros){
			$sql="SELECT
					COUNT(DISTINCT(dau.dau_id)) AS TOTAL,

					(dau.dau_cierre_administrativo_fecha),
					dau.dau_cierre_profesional_id,
					parametros_clinicos.profesional.PROdescripcion
					FROM dau.dau


					INNER JOIN parametros_clinicos.profesional ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
					WHERE (dau.dau_cierre_administrativo_fecha) BETWEEN '{$parametros['frm_inicio']}' AND DATE_ADD('{$parametros['frm_inicio']}', INTERVAL 24 HOUR) AND dau.dau.est_id = 5


					GROUP BY dau.dau_cierre_profesional_id
					ORDER BY parametros_clinicos.profesional.PROdescripcion";
				$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionMedicasTurno<br>");
				return $datos;
		}



		function entregaTurnoCategorizacionAdulto($objCon,$parametros){
			$sql="SELECT
				dau.dau.dau_id,
				DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') AS fecha,
				dau.dau.dau_admision_fecha as fechaHora,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,

				paciente.paciente.transexual,
				paciente.paciente.nombreSocial,

				dau.dau.dau_paciente_edad,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_atencion
			FROM
				dau.dau
			LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			WHERE
			dau.dau_paciente_edad > 12	AND dau.dau_categorizacion_actual IS NULL AND DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') = '{$parametros['fechaInicio']}' AND
			DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d %H:%i:%s') BETWEEN '{$parametros['horaInicio']}' AND '{$parametros['horaFin']}' AND dau.dau.dau_atencion=1";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla DAU <br>");
			return $datos;

		}



		function entregaTurnoCategorizacionPediatrico($objCon,$parametros){
			 $sql="SELECT
				dau.dau.dau_id,
				DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') AS fecha,
				dau.dau.dau_admision_fecha as fechaHora,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,

				paciente.paciente.transexual,
				paciente.paciente.nombreSocial,
				
				dau.dau.dau_paciente_edad,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_atencion
			FROM
				dau.dau
			LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			WHERE
			dau.dau_paciente_edad <= 12	AND dau.dau_categorizacion_actual IS NULL AND DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') = '{$parametros['fechaInicio']}' AND
			DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d %H:%i:%s') BETWEEN '{$parametros['horaInicio']}' AND '{$parametros['horaFin']}' AND dau.dau.dau_atencion=2";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla DAU <br>");
			return $datos;
		}



		function entregaTurnoCategorizacionGinecologico($objCon,$parametros){
			 $sql="SELECT
				dau.dau.dau_id,
				DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') AS fecha,
				dau.dau.dau_admision_fecha as fechaHora,
				paciente.paciente.nombres,
				paciente.paciente.apellidopat,
				paciente.paciente.apellidomat,
				dau.dau.dau_paciente_edad,
				dau.dau.dau_categorizacion_actual,
				dau.dau.dau_atencion
			FROM
				dau.dau
			LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
			WHERE
			dau.dau_categorizacion_actual IS NULL AND DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d') = '{$parametros['fechaInicio']}' AND
			DATE_FORMAT(dau.dau.dau_admision_fecha,'%Y-%m-%d %H:%i:%s') BETWEEN '{$parametros['horaInicio']}' AND '{$parametros['horaFin']}' AND dau.dau.dau_atencion=3";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla DAU <br>");
			return $datos;
		}



		function estadisticaAtencionPorDia($objCon,$parametros){
	      $sql="SELECT SUM(cantidadx.cantidad) as cantidad FROM (
	          SELECT
	            Count(*) AS cantidad
	          FROM
	            dau.dau
	          LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
	          WHERE
	            dau.dau.est_id IN (1, 2, 3, 4, 8)
	          AND dau.dau.dau_atencion = '{$parametros['tipoAtencion']}'
	          AND DATE_FORMAT(
	            dau.dau.dau_admision_fecha,
	            '%Y-%m-%d'
	          ) BETWEEN '{$parametros['fechaInicio']}'
	          AND '{$parametros['fechaInicio']}'
	          UNION ALL
	          SELECT
	            Count(*) AS cantidad
	          FROM
	            dau.dau
	          LEFT JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
	          WHERE
	            dau.dau.est_id = 5
	          AND dau.dau.dau_atencion = '{$parametros['tipoAtencion']}'
	          AND DATE_FORMAT(
	            dau.dau.dau_admision_fecha,
	            '%Y-%m-%d'
	          ) BETWEEN '{$parametros['fechaInicio']}'
	          AND '{$parametros['fechaInicio']}'
	          AND dau.dau.dau_indicacion_egreso = 3) as cantidadx";
	      $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR estadisticaAtencionPorDia <br>");
	      return $datos;
		}



		function estadisticaAtencionPorHora($objCon,$parametros,$horaInicio,$horaFin){
			$sql="SELECT count(dau.dau.dau_id) as cantidad
							FROM dau.dau
							Inner Join paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
							WHERE (EXTRACT(YEAR FROM curdate()) - EXTRACT(YEAR FROM paciente.paciente.fechanac)) BETWEEN  0 AND 99999
								AND date(dau.dau.dau_admision_fecha)  BETWEEN '{$parametros['fechaInicio']}' AND '{$parametros['fechaFin']}'
								AND time(dau.dau.dau_admision_fecha)  BETWEEN '$horaInicio' AND '$horaFin'
								AND dau.dau.est_id in (1,2,3,4,5,8)
								AND dau.dau.dau_atencion= '{$parametros['tipoAtencion']}'";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTA ESTADISTICA POR HORA <br>");
			return $datos;
		}



		function resumenTiemposEspera($objCon,$parametros){
			$condicion = '';
			$sql="SELECT
					(TIMESTAMPDIFF(MINUTE,(dau.dau_categorizacion_actual_fecha),
					(dau.dau_inicio_atencion_fecha))) AS CATE_ATEN,(TIMESTAMPDIFF(MINUTE,(dau.dau_admision_fecha),
					(dau.dau_categorizacion_actual_fecha))) AS ADM_CATE,(TIMESTAMPDIFF(MINUTE,(dau.dau_indicacion_egreso_fecha),
					(dau.dau_indicacion_egreso_aplica_fecha))) AS IND_ATEN,
					dau.dau_id,
					dau.dau_motivo_consulta,
					dau.dau_admision_fecha,
					dau.dau_categorizacion_actual,
					dau.est_id,
					dau.dau_atencion,
					dau.dau_indicacion_egreso,
					dau.dau_categorizacion_actual_fecha,
					dau.dau_inicio_atencion_fecha,
					dau.dau_indicacion_egreso_fecha,
					dau.dau_indicacion_egreso_aplica_fecha,
					dau.dau_cierre_servicio,
					estado.est_descripcion,
					motivo_consulta.mot_descripcion,
					indicacion_egreso.ind_egr_descripcion,
					dau.dau_cierre_administrativo_fecha
				FROM
					dau.dau
				LEFT JOIN dau.estado ON dau.est_id = estado.est_id
				LEFT JOIN dau.motivo_consulta ON dau.dau_motivo_consulta = motivo_consulta.mot_id
				LEFT JOIN dau.indicacion_egreso ON dau.dau_indicacion_egreso = indicacion_egreso.ind_egr_id
				WHERE date(dau.dau_admision_fecha) BETWEEN '{$parametros['fechaInicio']}' AND '{$parametros['fechaFin']}' AND dau.dau.est_id IN (1,2,3,4,5,8)  AND dau.dau_motivo_consulta IN(1,2,3,4,5) AND dau.dau_indicacion_egreso IN (4)";


				if ($parametros['frm_tipoAtencion']==0) {
					$condicion .= ($condicion == "") ? " AND " : " AND ";
					$condicion.=" dau.dau_atencion IN (1,2,3)";
				}else{
					$condicion .= ($condicion == "") ? " AND " : " AND ";
					$condicion.=" dau.dau_atencion = '{$parametros['frm_tipoAtencion']}'";
				}

			$sql .= $condicion;
			$sql  .= " ORDER BY dau_id DESC ";
			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR RESUMEN TIEMPO DE ESPERA<br>");
			return $datos;
		}



		function atencionAdulto2($objCon,$inicio,$fin){
			$sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '$inicio' AND '$fin' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 1
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionAdulto<br>");
			return $datos;
		}



		function atencionPediatrica2($objCon,$inicio,$fin){
			 $sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '$inicio' AND '$fin' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 2
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionPediatrica<br>");
			return $datos;
		}



		function atencionGinecologica2($objCon,$inicio,$fin){
			$sql="SELECT COUNT(DISTINCT(dau.dau.dau_id)) AS TOTAL,
					(dau.dau.dau_indicacion_egreso_fecha),
					 dau.dau.dau_cierre_profesional_id,
					 parametros_clinicos.profesional.PROdescripcion
				  FROM
					 parametros_clinicos.profesional
				  INNER JOIN dau.dau ON dau.dau.dau_cierre_profesional_id = parametros_clinicos.profesional.PROcodigo
				  WHERE DATE_FORMAT(dau.dau.dau_indicacion_egreso_fecha, '%Y-%m-%d') BETWEEN '$inicio' AND '$fin' AND dau.dau.est_id IN (1, 2, 3, 4, 5, 8) AND dau.dau.dau_atencion = 3
				  GROUP BY dau.dau.dau_cierre_profesional_id
				  ORDER BY parametros_clinicos.profesional.PROdescripcion ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte atencionGinecologica<br>");
			return $datos;
		}



		function entregaCierreDAU($objCon,$inicio,$fin){
			$sqlAdulto="SELECT
						SUM(IF(dau.est_id IN (1, 2, 3, 4, 8),1,0)) AS P,
						SUM(IF(dau.est_id IN (5),1,0)) AS D,
						SUM(IF(dau.est_id IN (7),1,0)) AS N,
						SUM(IF(dau.est_id IN (6),1,0)) AS NU
						FROM
							dau.dau
						WHERE
							DATE_FORMAT(
								dau.dau_admision_fecha,
								'%Y-%m-%d'
							) BETWEEN '$inicio'
						AND '$fin'
						AND dau.dau_atencion = 1";
			$datosAdulto = $objCon->consultaSQL($sqlAdulto,"<br>Error entregaCierreDAU sqlAdulto<br>");

			$sqlPed="SELECT
						SUM(IF(dau.est_id IN (1, 2, 3, 4, 8),1,0)) AS P,
						SUM(IF(dau.est_id IN (5),1,0)) AS D,
						SUM(IF(dau.est_id IN (7),1,0)) AS N,
						SUM(IF(dau.est_id IN (6),1,0)) AS NU
						FROM
							dau.dau
						WHERE
							DATE_FORMAT(
								dau.dau_admision_fecha,
								'%Y-%m-%d'
							) BETWEEN '$inicio'
						AND '$fin'
						AND dau.dau_atencion = 2";
			$datosPediatrico = $objCon->consultaSQL($sqlPed,"<br>Error entregaCierreDAU sqlPed<br>");

			$sqlGine="SELECT
						SUM(IF(dau.est_id IN (1, 2, 3, 4, 8),1,0)) AS P,
						SUM(IF(dau.est_id IN (5),1,0)) AS D,
						SUM(IF(dau.est_id IN (7),1,0)) AS N,
						SUM(IF(dau.est_id IN (6),1,0)) AS NU
						FROM
							dau.dau
						WHERE
							DATE_FORMAT(
								dau.dau_admision_fecha,
								'%Y-%m-%d'
							) BETWEEN '$inicio'
						AND '$fin'
						AND dau.dau_atencion = 3";
			$datosGine = $objCon->consultaSQL($sqlGine,"<br>Error entregaCierreDAU sqlGine<br>");

			$arrDatos = array();
			$arrDatos['A'] = $datosAdulto;
			$arrDatos['P'] = $datosPediatrico;
			$arrDatos['G'] = $datosGine;

			return $arrDatos;
		}



		function listaEntregaCierreDAU($objCon,$inicio,$fin){
			$sqlAdulto="SELECT
						dau.dau.dau_id,
						dau.dau.dau_admision_fecha,
						dau.atencion.ate_descripcion,
						CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombre
						FROM
						dau.dau
						INNER JOIN dau.atencion ON dau.dau.dau_atencion = dau.atencion.ate_id
						INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							DATE_FORMAT(
								dau.dau_admision_fecha,
								'%Y-%m-%d'
							) BETWEEN '$inicio'
						AND '$fin'
						AND dau.est_id IN (2,3,4)";
			return $datosAdulto = $objCon->consultaSQL($sqlAdulto,"<br>Error entregaCierreDAU sqlAdulto<br>");
		}




		function listarSalaHidratacion_u_Observacion($objCon,$parametros){
			 $sql="SELECT
				dau.dau_movimiento_cama.dau_id,
				dau.dau.dau_admision_fecha,


				IF(ISNULL(dau.dau_tipo_documento),
				CASE
				WHEN paciente.id_doc_extranjero = 0 THEN 'RUN'
				WHEN ISNULL(paciente.id_doc_extranjero) THEN 'RUN'
				WHEN paciente.id_doc_extranjero = 1 THEN 'DNI'
				WHEN paciente.id_doc_extranjero = 2 THEN 'Pasaporte'
				WHEN paciente.id_doc_extranjero = 3 THEN 'Otros'
				END
				,
				CASE
				WHEN dau.dau_tipo_documento = '0' THEN 'RUN'
				WHEN dau.dau_tipo_documento = '1' THEN 'DNI'
				WHEN dau.dau_tipo_documento = '2' THEN 'Pasaporte'
				WHEN dau.dau_tipo_documento = '3' THEN 'Otros'
				END
				)AS Tipo_documento,
				IF(ISNULL(dau.dau_tipo_documento),
				CASE
				WHEN paciente.id_doc_extranjero = '0' THEN (select CONCAT(p.rut, '-', p.dv) from paciente.paciente as p where p.rut = paciente.rut )
				WHEN ISNULL(paciente.id_doc_extranjero) THEN (select CONCAT(p.rut, '-', p.dv) from paciente.paciente as p where p.rut = paciente.rut )
				WHEN paciente.id_doc_extranjero = '1' THEN paciente.rut_extranjero
				WHEN paciente.id_doc_extranjero = '2' THEN paciente.rut_extranjero
				WHEN paciente.id_doc_extranjero = '3' THEN paciente.rut_extranjero
				END
				,
				CASE
				WHEN dau.dau_tipo_documento = '0' THEN CONCAT(dau.dau_numero_documento,'-',dau.digitoVerificador(dau.dau_numero_documento))
				WHEN dau.dau_tipo_documento = '1' THEN dau.dau_numero_documento
				WHEN dau.dau_tipo_documento = '2' THEN dau.dau_numero_documento
				WHEN dau.dau_tipo_documento = '3' THEN dau.dau_numero_documento
				END
				) as rut,



				CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS NOMBRE_PACIENTE,
				paciente.paciente.transexual,
				paciente.paciente.nombreSocial,
				dau.dau_movimiento_cama.dau_mov_cama_fecha_ingreso,
				dau.dau_movimiento_cama.dau_mov_cama_fecha_egreso,
				DATEDIFF(dau_movimiento_cama.dau_mov_cama_fecha_egreso,dau_movimiento_cama.dau_mov_cama_fecha_ingreso) AS DIFdeDia,
				HOUR(TIMEDIFF(dau_movimiento_cama.dau_mov_cama_fecha_egreso,dau_movimiento_cama.dau_mov_cama_fecha_ingreso)) AS DIFdeHras,
				CASE
				WHEN dau.dau_movimiento_cama.sal_id = 41 THEN 'Observación MP'
				WHEN dau.dau_movimiento_cama.sal_id = 40 THEN 'Hidratación MP'
				END
				AS TIPOSALA,
				paciente.prevision.prevision
				FROM
				dau.dau_movimiento_cama
				INNER JOIN dau.dau ON dau.dau_movimiento_cama.dau_id = dau.dau.dau_id
				INNER JOIN paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
				INNER JOIN paciente.prevision ON dau.dau.dau_paciente_prevision = paciente.prevision.id
				WHERE
				date(dau_movimiento_cama.dau_mov_cama_fecha_ingreso) BETWEEN '{$parametros['fechaInicio']}' AND '{$parametros['fechaFin']}' AND	dau.dau_movimiento_cama.sal_id IN (40, 41)
				GROUP BY
				dau.dau_movimiento_cama.dau_id";
				$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte Hospitalizacion<br>");
				return $datos;
		}



		function numeroPacientesConIndicacionFinal ( $objCon, $parametros ) {

				$sql = "SELECT
							SUM(IF(dau.dau.dau_atencion = 1, 1, 0)) AS totalPacientesAdultoCierre,
							SUM(IF(dau.dau.dau_atencion = 2, 1, 0)) AS totalPacientesPediatricoCierre,
							SUM(IF(dau.dau.dau_atencion = 3, 1, 0)) AS totalPacientesGinecologicoCierre
						FROM
							dau.dau
						WHERE
							dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
						AND
							dau.dau.est_id IN (4, 5) ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

				return $datos[0];

		}



		function numeroPacientesConIndicacionFinalNEA ( $objCon, $parametros ) {

				$sql = "SELECT
							SUM(IF(dau.dau.dau_atencion = 1, 1, 0)) AS totalPacientesAdultoNEA,
							SUM(IF(dau.dau.dau_atencion = 2, 1, 0)) AS totalPacientesPediatricoNEA,
							SUM(IF(dau.dau.dau_atencion = 3, 1, 0)) AS totalPacientesGinecologicoNEA
						FROM
							dau.dau
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
						AND
							dau.dau.est_id = 7 ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

				return $datos[0];

		}



		function numeroPacientesDiagnosticoDiarreasAgudas ( $objCon, $parametros ) {

			$sql = "SELECT
						SUM( IF ( dau.dau.dau_atencion = 1, 1, 0 ) ) AS totalPacientesAdultosDiarreasAgudas,
						SUM( IF ( dau.dau.dau_atencion = 2, 1, 0 ) ) AS totalPacientesPediatricosDiarreasAgudas
					FROM
						dau.dau
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						dau.dau.dau_cierre_cie10 BETWEEN 'A090' AND 'A09Z'
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau_atencion IN ( 1, 2 )  ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos[0];

		}



		function numeroPacientesDiagnosticoInfeccionesRespiratoriasAgudas ( $objCon, $parametros ) {

			$sql = "SELECT
						SUM( IF ( dau.dau.dau_atencion = 1, 1, 0 ) ) AS totalPacientesAdultosRespiratoriasAgudas,
						SUM( IF ( dau.dau.dau_atencion = 2, 1, 0 ) ) AS totalPacientesPediatricosRespiratoriasAgudas
					FROM
						dau.dau
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						(dau.dau.dau_cierre_cie10 BETWEEN 'J200' AND  'J219'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J120' AND  'J181'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J111' AND  'J111'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J000' AND  'J99Z'
						)
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau_atencion IN ( 1, 2 )  ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos[0];

		}



		function numeroPacientesOtrosDiagnosticos ( $objCon, $parametros ) {

			$sql = "SELECT
						SUM( IF ( dau.dau.dau_atencion = 1, 1, 0 ) ) AS totalPacientesAdultosOtrosDiagnosticos,
						SUM( IF ( dau.dau.dau_atencion = 2, 1, 0 ) ) AS totalPacientesPediatricosOtrosDiagnosticos
					FROM
						dau.dau
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						(
								dau.dau.dau_cierre_cie10 NOT BETWEEN 'A090' AND 'A09Z'
							AND
								dau.dau.dau_cierre_cie10 NOT BETWEEN 'J200' AND  'J219'
							AND
								dau.dau.dau_cierre_cie10 NOT BETWEEN 'J120' AND  'J181'
							AND
								dau.dau.dau_cierre_cie10 NOT BETWEEN 'J111' AND  'J111'
							AND
								dau.dau.dau_cierre_cie10 NOT BETWEEN 'J000' AND  'J99Z'
						)
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau_atencion IN ( 1, 2 )  ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos[0];

		}




		function numeroPacientesPorCategorizacion ( $objCon, $parametros ) {

				$sql = "SELECT
							dau.dau.dau_categorizacion AS categorizacionPaciente,
							SUM( IF ( dau.dau.dau_atencion = 1, 1, 0 ) ) AS totalPacientesAdultoCategorizados,
							SUM( IF ( dau.dau.dau_atencion = 2, 1, 0 ) ) AS totalPacientesPediatricoCategorizados
						FROM
							dau.dau
						WHERE
							dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
						AND
							dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}'
						AND
							dau.dau_atencion IN ( 1, 2 )
						AND
							dau.dau.est_id IN (4, 5)
						GROUP BY
							dau.dau.dau_categorizacion ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

				return $datos[0];

		}



		function numeroPacientesPorIntravenosos ( $objCon, $parametros ) {

				$sql = "SELECT
							datos.dau_categorizacion AS categorizacionPaciente,
							SUM( IF (datos.dau_atencion = 1 , 1, 0)) AS totalPacientesAdultoIntravenoso,
							SUM( IF (datos.dau_atencion = 2 , 1, 0)) AS totalPacientesPediatricoIntravenoso
						FROM
						(
							SELECT
								dau.dau.dau_categorizacion,
								dau.dau_id,
								rce.solicitud_indicaciones.sol_clasificacionTratamiento,
								dau.dau_atencion
							FROM
								dau.dau
							LEFT JOIN
								rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
							LEFT JOIN
								rce.solicitud_indicaciones ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
							WHERE
								dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
							AND
								dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}'
							AND
								rce.solicitud_indicaciones.sol_clasificacionTratamiento = 1
							AND
								dau.dau.est_id IN  (4, 5)
							AND
								dau.dau.dau_atencion IN (1, 2)
							GROUP BY
								dau.dau_id
						) as datos
						GROUP BY
							datos.dau_categorizacion ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

				return $datos[0];

		}



		function numeroPacientesConIndicacionHospitalizacion( $objCon, $parametros ) {

				$sql = "SELECT
							SUM(IF(dau.dau.dau_atencion = 1, 1, 0)) AS totalPacientesAdultoHospitalizado,
							SUM(IF(dau.dau.dau_atencion = 2, 1, 0)) AS totalPacientesPediatricoHospitalizado,
							SUM(IF(dau.dau.dau_atencion = 3, 1, 0)) AS totalPacientesGinecologicoHospitalizado
						FROM
							dau.dau
						WHERE
							dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
						AND
							dau.dau.est_id IN (4, 5)
						AND
							dau.dau.dau_indicacion_egreso = 4 ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

				return $datos[0];

		}



		function funcionariosEnviarReportesDAURCE ( $objCon ) {

			$sql = "SELECT
						acceso.usuario.emailusuario,
						acceso.usuario.nombreusuario
					FROM
						acceso.usuario
					WHERE
						acceso.usuario.usu_reporte_dau = 'S' ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function tiemposPromediosPorCategorizacion ( $objCon, $parametros ) {

			$sql = "SELECT
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_tipo_ingreso AS tipoPaciente,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora AS hora,
						CONCAT(dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_fecha, ' ', dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora ) AS fechaHora,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi1 AS tiempoPromedioESI1,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi2 AS tiempoPromedioESI2,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi3 AS tiempoPromedioESI3,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi4 AS tiempoPromedioESI4,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi5 AS tiempoPromedioESI5
					FROM
						dau.promedios_tiempos_espera_urgencia
					WHERE
						CAST(CONCAT(dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_fecha, ' ', dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora) as datetime) BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora REGEXP '([0-2][0-9]):([0][0]):([0][0])'
					AND
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_tipo_ingreso = '{$parametros['tipoPaciente']}' ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function tiemposMaximosPorCategorizacion ( $objCon, $parametros ) {

			$sql = "SELECT
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_tipo_ingreso AS tipoPaciente,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora AS hora,
						CONCAT(dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_fecha, ' ', dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora ) AS fechaHora,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi1_tiempoMax AS tiempoMaximoESI1,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi2_tiempoMax AS tiempoMaximoESI2,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi3_tiempoMax AS tiempoMaximoESI3,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi4_tiempoMax AS tiempoMaximoESI4,
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_cat_iniAte_esi5_tiempoMax AS tiempoMaximoESI5
					FROM
						dau.promedios_tiempos_espera_urgencia
					WHERE
						CAST(CONCAT(dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_fecha, ' ', dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora) as datetime) BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_hora REGEXP '([0-2][0-9]):([0][0]):([0][0])'
					AND
						dau.promedios_tiempos_espera_urgencia.promTiempoEspUrg_tipo_ingreso = '{$parametros['tipoPaciente']}' ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function tiemposEsperaPorProfesional ( $objCon, $parametros ) {

			$sql = "SELECT
						acceso.usuario.nombreusuario AS nombreProfesional,
						datos.cantidadPacientesESI1,
						datos.cantidadPacientesESI2,
						datos.cantidadPacientesESI3,
						datos.cantidadPacientesESI4,
						datos.cantidadPacientesESI5,
						datos.totalPacientes,
						ROUND(datos.Promedio) AS tiempoPromedio,
						datos.tiempoMinimo,
						datos.tiempoMaximo
					FROM
						(
						SELECT
							dau.dau.dau_inicio_atencion_usuario,
							COUNT(dau.dau.dau_inicio_atencion_usuario) as totalPacientes,
							AVG(TIMESTAMPDIFF(MINUTE,dau.dau_admision_fecha,dau.dau_inicio_atencion_fecha)) AS Promedio,
							MAX(TIMESTAMPDIFF(MINUTE,dau.dau_admision_fecha,dau.dau_inicio_atencion_fecha)) AS tiempoMaximo,
							MIN(TIMESTAMPDIFF(MINUTE,dau.dau_admision_fecha,dau.dau_inicio_atencion_fecha)) AS tiempoMinimo,
							SUM( IF (dau.dau.dau_categorizacion = 'ESI-1', 1, 0)) AS cantidadPacientesESI1,
							SUM( IF (dau.dau.dau_categorizacion = 'ESI-2', 1, 0)) AS cantidadPacientesESI2,
							SUM( IF (dau.dau.dau_categorizacion = 'ESI-3', 1, 0)) AS cantidadPacientesESI3,
							SUM( IF (dau.dau.dau_categorizacion = 'ESI-4', 1, 0)) AS cantidadPacientesESI4,
							SUM( IF (dau.dau.dau_categorizacion = 'ESI-5', 1, 0)) AS cantidadPacientesESI5
						FROM
							dau.dau
						WHERE
							dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
						AND
							dau.dau.dau_categorizacion IN ('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5')
						AND
							dau.dau.est_id IN  (4, 5)
						AND
							dau.dau.dau_atencion IN (1, 2)
						GROUP BY
							dau.dau.dau_inicio_atencion_usuario
						) as datos
					INNER JOIN acceso.usuario ON datos.dau_inicio_atencion_usuario = acceso.usuario.idusuario ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function cantidadPacientesSegunTipoEstado ( $objCon, $parametros ) {

			$fechaAnterior = date('Y-m-d', strtotime($parametros['fechaAnterior']));

			$fechaActual = date('Y-m-d' , strtotime($parametros['fechaActual']));

			$fechaABuscar = '';

			switch ( $parametros['tipoEstado'] ) {

				case 'admision':
					$fechaABuscar = 'dau.dau.dau_admision_fecha';
				break;

				case 'categorizados':
					$fechaABuscar = 'dau.dau.dau_categorizacion_fecha';
				break;

				case 'inicioAtencion':
					$fechaABuscar = 'dau.dau.dau_inicio_atencion_fecha';
				break;

				case 'indicacionEgreso':
					$fechaABuscar = 'dau.dau.dau_indicacion_egreso_fecha';
				break;

				case 'egresados':
					$fechaABuscar = 'dau.dau.dau_cierre_fecha_final';
				break;

			}

			$sql = "SELECT
						dau.dau.dau_id,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 08:00:00' AND '{$fechaAnterior} 08:59:59', 1, 0)) AS cantidad08,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 09:00:00' AND '{$fechaAnterior} 9:59:59', 1, 0)) AS cantidad09,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 10:00:00' AND '{$fechaAnterior} 10:59:59', 1, 0)) AS cantidad10,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 11:00:00' AND '{$fechaAnterior} 11:59:59', 1, 0)) AS cantidad11,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 12:00:00' AND '{$fechaAnterior} 12:59:59', 1, 0)) AS cantidad12,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 13:00:00' AND '{$fechaAnterior} 13:59:59', 1, 0)) AS cantidad13,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 14:00:00' AND '{$fechaAnterior} 14:59:59', 1, 0)) AS cantidad14,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 15:00:00' AND '{$fechaAnterior} 15:59:59', 1, 0)) AS cantidad15,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 16:00:00' AND '{$fechaAnterior} 16:59:59', 1, 0)) AS cantidad16,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 17:00:00' AND '{$fechaAnterior} 17:59:59', 1, 0)) AS cantidad17,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 18:00:00' AND '{$fechaAnterior} 18:59:59', 1, 0)) AS cantidad18,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 19:00:00' AND '{$fechaAnterior} 19:59:59', 1, 0)) AS cantidad19,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 20:00:00' AND '{$fechaAnterior} 20:59:59', 1, 0)) AS cantidad20,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 21:00:00' AND '{$fechaAnterior} 21:59:59', 1, 0)) AS cantidad21,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 22:00:00' AND '{$fechaAnterior} 22:59:59', 1, 0)) AS cantidad22,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaAnterior} 23:00:00' AND '{$fechaAnterior} 23:59:59', 1, 0)) AS cantidad23,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 00:00:00' AND '{$fechaActual} 00:59:59', 1, 0)) AS cantidad00,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 01:00:00' AND '{$fechaActual} 01:59:59', 1, 0)) AS cantidad01,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 02:00:00' AND '{$fechaActual} 02:59:59', 1, 0)) AS cantidad02,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 03:00:00' AND '{$fechaActual} 03:59:59', 1, 0)) AS cantidad03,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 04:00:00' AND '{$fechaActual} 04:59:59', 1, 0)) AS cantidad04,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 05:00:00' AND '{$fechaActual} 05:59:59', 1, 0)) AS cantidad05,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 06:00:00' AND '{$fechaActual} 06:59:59', 1, 0)) AS cantidad06,
						SUM(IF({$fechaABuscar} BETWEEN '{$fechaActual} 07:00:00' AND '{$fechaActual} 07:59:59', 1, 0)) AS cantidad07
					FROM
						dau.dau
					WHERE
						{$fechaABuscar} BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					GROUP BY
						dau.dau.dau_atencion ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function obtenerCantidadAltasSegunProfesional ( $objCon, $parametros ) {

			$sql = "SELECT
						CASE
							WHEN epicrisis.epicrisismedica.epimedMedico <> '' THEN epicrisis.epicrisismedica.epimedMedico
							WHEN epicrisis.epicrisisucisai.epiucisai_medicoalta <> '' THEN epicrisis.epicrisisucisai.epiucisai_medicoalta
							ELSE 'Sin Epicrisis'
						END
						AS nombreProfesional,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Medicina',1,0)) as altaMedicina,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Oncologia',1,0)) as altaOncologia,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Cirugia',1,0)) as altaCirugia,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Cirugia Aislamiento',1,0)) as altaCirugiaAislamiento,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Traumatologia',1,0)) as altaTraumatologia,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Pediatria',1,0)) as altaPediatria,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'Psiquiatria',1,0)) as altaPsiquiatria,
						SUM(IF(camas.hospitalizaciones.que_servicio = 'CR de la Mujer',1,0)) as altaCRDeLaMujer,
						SUM(IF(camas.hospitalizaciones.que_servicio <> 'Medicina'
								AND camas.hospitalizaciones.que_servicio <> 'Oncologia'
								AND camas.hospitalizaciones.que_servicio <> 'Cirugia'
								AND camas.hospitalizaciones.que_servicio <> 'Cirugia Aislamiento'
								AND camas.hospitalizaciones.que_servicio <> 'Traumatologia'
								AND camas.hospitalizaciones.que_servicio <> 'Pediatria'
								AND camas.hospitalizaciones.que_servicio <> 'Psiquiatria'
								AND camas.hospitalizaciones.que_servicio <> 'CR de la Mujer'
								,1,0
							)) as altaOtros
					FROM
						camas.hospitalizaciones
					LEFT JOIN epicrisis.epicrisismedica ON camas.hospitalizaciones.cta_cte = epicrisis.epicrisismedica.epimedCtacte
					LEFT JOIN epicrisis.epicrisisucisai ON camas.hospitalizaciones.cta_cte = epicrisis.epicrisisucisai.epiucisai_ctacte
					INNER JOIN camas.sscc ON camas.hospitalizaciones.cod_destino = camas.sscc.id
					WHERE
						concat (hospitalizaciones.fecha_egreso,' ',hospitalizaciones.hora_egreso) BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						camas.hospitalizaciones.cod_destino > 100
					GROUP  BY
						CASE WHEN BINARY nombreProfesional = BINARY 'Sin Epicrisis' THEN 1 ELSE 0 END, nombreProfesional COLLATE utf8_spanish_ci
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function insertarHospitalizacionesUrgencia ( $objCon, $parametros ) {

			$sql = "INSERT INTO
						dau.reportes_hospitalizaciones_urgencia(
							fechaDesde,
							fechaHasta,
							numeroHospitalizacionesAdulto,
							numeroHospitalizacionesAdulto12,
							numeroHospitalizacionesAdulto24,
							numeroHospitalizacionesPediatrico,
							numeroHospitalizacionesPediatrico12,
							numeroHospitalizacionesPediatrico24
						)
					VALUES(
							'{$parametros['fechaAnterior']}',
							'{$parametros['fechaActual']}',
							'{$parametros['numeroHospitalizacionesAdulto']}',
							'{$parametros['numeroHospitalizacionesAdulto12']}',
							'{$parametros['numeroHospitalizacionesAdulto24']}',
							'{$parametros['numeroHospitalizacionesPediatrico']}',
							'{$parametros['numeroHospitalizacionesPediatrico12']}',
							'{$parametros['numeroHospitalizacionesPediatrico24']}'
						)	";

	    $datos = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Diario DAU RCE<br>");

		}



		function cantidadPacientesHospitalizacionesUrgencia ( $objCon ) {

			$sql = "SELECT
						dau.reportes_hospitalizaciones_urgencia.*
					FROM
						dau.reportes_hospitalizaciones_urgencia
					ORDER BY
						dau.reportes_hospitalizaciones_urgencia.idReporteHospitalizacionesUrgencia DESC
					LIMIT 15	";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function obtenerDemandaUrgenciaAdultoPediatrica ( $objCon, $parametros ) {

				$sql = "SELECT
							SUM(IF(dau.dau.dau_atencion = 1, 1, 0)) AS totalPacientesAdulto,
							SUM(IF(dau.dau.dau_atencion = 1 AND dau.dau.est_id = 5, 1, 0)) AS totalPacientesAdultoCierre,
							SUM(IF(dau.dau.dau_atencion = 1 AND dau.dau.est_id = 6, 1, 0)) AS totalPacientesAdultoAnula,
							SUM(IF(dau.dau.dau_atencion = 1 AND dau.dau.est_id = 7, 1, 0)) AS totalPacientesAdultoNEA,
							SUM(IF(dau.dau.dau_atencion = 2, 1, 0)) AS totalPacientesPediatrico,
							SUM(IF(dau.dau.dau_atencion = 2 AND dau.dau.est_id IN (4,5), 1, 0)) AS totalPacientesPediatricoCierre,
							SUM(IF(dau.dau.dau_atencion = 2 AND dau.dau.est_id = 6, 1, 0)) AS totalPacientesPediatricoAnula,
							SUM(IF(dau.dau.dau_atencion = 2 AND dau.dau.est_id = 7, 1, 0)) AS totalPacientesPediatricoNEA
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND
							dau.dau.est_id IN (5, 6, 7)
						AND
							dau.dau_atencion IN (1, 2)";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia 2<br>");

				return $datos[0];

		}



		function obtenerDetalleDemandaUrgenciaAdultoPediatrica ( $objCon, $parametros, &$totalPag, &$total ){

				require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';



				$sql = "SELECT
							dau.dau.dau_id AS numeroDAU,
							CASE
								WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
								WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
							END AS tipoAtencion,
							CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
							paciente.paciente.rut AS rutPaciente,
							dau.dau.dau_admision_fecha AS fechaAdmision,
							dau.dau.dau_categorizacion_fecha AS fechaCategorizacion,
							dau.dau.dau_inicio_atencion_fecha AS fechaInicioAtencion,
							dau.dau.dau_indicacion_egreso_fecha AS fechaIndicacionEgreso,
							dau.dau.dau_indicacion_egreso_aplica_fecha AS fechaAplicacionIndicacionEgreso,
							dau.dau.dau_cierre_fecha_final AS fechaCierreFinal
						FROM
							dau.dau FORCE INDEX ( dau_admision_fecha )
						INNER JOIN
							paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND
							dau.dau.est_id = {$parametros['tipoDetalleADesplegar']} ";

						$condicion = " AND dau.dau.dau_atencion IN (1, 2) ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion = " AND dau.dau.dau_atencion IN {$parametros['tipoAtencion']} ";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND dau.dau.dau_id = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['nombrePaciente']}%',' ','%')";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND paciente.paciente.rut = '{$parametros['rutPaciente']}' ";

						}


				$sql .= $condicion;

				$sql .= " ORDER BY dau.dau.dau_id DESC ";

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function obtenerResumenTiemposEspera ( $objCon, $parametros ) {

				$sql = "SELECT
							datos.dau_atencion AS tipoAtencion,
							datos.dau_categorizacion AS tipoCategorizacion,
							COUNT(datos.dau_id) AS totalAtendidos,
							SEC_TO_TIME(ROUND(AVG(TIMESTAMPDIFF(SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha)))) AS tiempoPromedio,
							CASE
								WHEN datos.dau_categorizacion = 'ESI-1' THEN
								SEC_TO_TIME(ROUND(MAX(TIMESTAMPDIFF( SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha))))
								WHEN datos.dau_categorizacion = 'ESI-2' THEN
								SEC_TO_TIME(ROUND(MAX(TIMESTAMPDIFF( SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha))))
								WHEN datos.dau_categorizacion = 'ESI-3' THEN
								SEC_TO_TIME(ROUND(MAX(TIMESTAMPDIFF( SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha))))
								WHEN datos.dau_categorizacion = 'ESI-4' THEN
								SEC_TO_TIME(ROUND(MAX(TIMESTAMPDIFF( SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha))))
								WHEN datos.dau_categorizacion = 'ESI-5' THEN
								SEC_TO_TIME(ROUND(MAX(TIMESTAMPDIFF( SECOND, datos.dau_categorizacion_fecha, datos.dau_inicio_atencion_fecha))))
							END AS tiempoMaximo
						FROM(
						SELECT
							dau.dau.dau_id,
							dau.dau_categorizacion,
							dau.dau.dau_atencion,
							dau.dau.dau_categorizacion_fecha,
							dau.dau.dau_inicio_atencion_fecha
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND dau.dau_categorizacion IN ('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5')
							AND dau.dau.est_id = 5
							AND dau.dau_atencion IN (1, 2)
							AND dau.dau.dau_cierre_fecha_final IS NOT NULL
						) AS datos
						GROUP BY
							datos.dau_atencion, datos.dau_categorizacion ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos;

		}



		function obtenerResumenTiemposEsperaNEA ( $objCon, $parametros ) {

				$sql = "SELECT
							COUNT( dau.dau_id ) AS totalAtencion,
							SEC_TO_TIME( ROUND( AVG( TIMESTAMPDIFF( SECOND, dau.dau.dau_admision_fecha, dau.dau.dau_cierre_fecha_final ) ) ) ) AS tiempoPromedio,
							SEC_TO_TIME( ROUND( MAX( TIMESTAMPDIFF( SECOND, dau.dau.dau_admision_fecha, dau.dau.dau_cierre_fecha_final ) ) ) ) AS tiempoMaximo
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND dau.dau.est_id IN ( 7 )
						AND dau.dau_atencion IN (1, 2)
						AND dau.dau_admision_fecha IS NOT NULL
						AND dau.dau_cierre_fecha_final IS NOT NULL
						GROUP BY
							dau.dau.dau_atencion ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos;

		}



		function obtenerDetalleResumenTiemposEspera ( $objCon, $parametros, &$totalPag, &$total ){

				require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';

				if ( $parametros['tipoCategorizacion'] != 'NEA' ) {

					$fechaResto1 = 'dau.dau.dau_categorizacion_fecha';

					$fechaResto2 = 'dau.dau.dau_inicio_atencion_fecha';

					$condicion .= "AND
										dau.dau.est_id = 5
									AND
										dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}' ";

				} else {

					$fechaResto1 = 'dau.dau.dau_admision_fecha';

					$fechaResto2 = 'dau.dau.dau_cierre_fecha_final';

					$condicion .= "AND
										dau.dau.est_id = 7
									AND
										dau.dau_admision_fecha IS NOT NULL
									AND
										dau.dau_cierre_fecha_final IS NOT NULL ";

				}

				 $sql = "SELECT
							dau.dau.dau_id AS numeroDAU,
							CASE
								WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
								WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
							END AS tipoAtencion,
							CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
							paciente.paciente.rut AS rutPaciente,
							dau.dau.dau_admision_fecha AS fechaAdmision,
							dau.dau.dau_categorizacion_fecha AS fechaCategorizacion,
							dau.dau.dau_inicio_atencion_fecha AS fechaInicioAtencion,
							dau.dau.dau_cierre_fecha_final AS fechaCierre,
							SEC_TO_TIME(ROUND(TIMESTAMPDIFF(SECOND, {$fechaResto1}, {$fechaResto2}))) AS tiempoEspera
						FROM
							dau.dau FORCE INDEX ( dau_admision_fecha )
						INNER JOIN
							paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59' ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion .= " AND dau.dau.dau_atencion IN {$parametros['tipoAtencion']} ";

						} else {

							$condicion .= " AND dau.dau.dau_atencion IN (1, 2)";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND dau.dau.dau_id = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['nombrePaciente']}%',' ','%')";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND paciente.paciente.rut = '{$parametros['rutPaciente']}' ";

						}


				$sql .= $condicion;

				$sql .= " ORDER BY tiempoEspera DESC ";

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function crearTablaTemporalTotalMuestras ( $objCon, $parametros ) {

				$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.muestra_deciles AS
						(SELECT
							dau.dau.est_id,
							dau.dau.dau_id,
							dau.dau.dau_atencion,
							dau.dau.dau_categorizacion,
							dau.dau.dau_categorizacion_fecha,
							dau.dau.dau_inicio_atencion_fecha,
							TIMESTAMPDIFF(SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha) AS tiempoEspera
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND
								dau.dau.dau_categorizacion IN ( 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5' )
							AND
								dau.dau.est_id = 5
							AND
								dau.dau.dau_atencion IN (1, 2)
							AND
								dau.dau.dau_categorizacion_fecha IS NOT NULL
							AND
								dau.dau.dau_inicio_atencion_fecha IS NOT NULL
							ORDER BY
								dau.dau.est_id, dau.dau.dau_atencion, dau.dau.dau_categorizacion, tiempoEspera ASC )";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia1<br>");

				$sql = "CREATE INDEX indexMuestraDeciles USING BTREE ON dau.muestra_deciles(dau_atencion, dau_categorizacion) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia2<br>");

		}



		function crearTablaTemporalTotalMuestrasNEA ( $objCon, $parametros ) {

				$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.muestra_deciles_nea AS
						(SELECT
							dau.dau.est_id,
							dau.dau.dau_id,
							dau.dau.dau_admision_fecha,
							dau.dau.dau_atencion,
							dau.dau.dau_categorizacion,
							dau.dau.dau_categorizacion_fecha,
							dau.dau.dau_inicio_atencion_fecha,
							dau.dau.dau_cierre_fecha_final,
							CASE
								WHEN dau.dau.dau_categorizacion_fecha IS NOT NULL AND dau.dau.dau_inicio_atencion_fecha IS NULL THEN
									TIMESTAMPDIFF(SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_cierre_fecha_final)
								WHEN dau.dau.dau_categorizacion_fecha IS NULL THEN
									TIMESTAMPDIFF(SECOND, dau.dau.dau_admision_fecha, dau.dau.dau_cierre_fecha_final)
								ELSE
									TIMESTAMPDIFF(SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha)
							END AS tiempoEspera
						FROM
							dau.dau
						FORCE
							INDEX (dau_admision_fecha)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND
								dau.dau.est_id = 7
							AND
								dau.dau.dau_atencion IN (1, 2)
							AND
								dau.dau.dau_cierre_fecha_final IS NOT NULL
							HAVING
								tiempoEspera > 0
							ORDER BY
								dau.dau.dau_atencion, tiempoEspera ASC)";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia11<br>");

				$sql = "CREATE INDEX indexMuestraDecilesNEA USING BTREE ON dau.muestra_deciles_nea(dau_atencion) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia22<br>");

		}



		function crearTablaTemporalTotalMuestrasDetalle ( $objCon, $parametros ) {

				$condicion = '';

				if ( $parametros['tipoCategorizacion'] != 'NEA' ) {

						$fechaResto1 = 'dau.dau.dau_categorizacion_fecha';

						$fechaResto2 = 'dau.dau.dau_inicio_atencion_fecha';

						$condicion .= "AND
											dau.dau.est_id = 5
										AND
											dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}'
										AND
											dau.dau.dau_admision_fecha IS NOT NULL
										AND
											dau.dau.dau_inicio_atencion_fecha IS NOT NULL";

				} else {

					$fechaResto1 = 'dau.dau.dau_admision_fecha';

					$fechaResto2 = 'dau.dau.dau_cierre_fecha_final';

					$condicion .= "AND
										dau.dau.est_id = 7
									AND
										dau.dau_admision_fecha IS NOT NULL
									AND
										dau.dau_cierre_fecha_final IS NOT NULL ";

				}

				$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.detalle_muestra_deciles AS
						(SELECT
							dau.dau.dau_id AS numeroDAU,
							dau.dau.dau_atencion,
							dau.dau.dau_categorizacion AS tipoCategorizacion,
							CASE
								WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
								WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
							END AS tipoAtencion,
							CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
							paciente.paciente.rut AS rutPaciente,
							dau.dau.dau_categorizacion_fecha AS fechaCategorizacion,
							dau.dau.dau_inicio_atencion_fecha AS fechaInicioAtencion,
							dau.dau.dau_admision_fecha AS fechaAdmision,
							dau.dau.dau_cierre_fecha_final AS fechaCierre,
							IF(TIMESTAMPDIFF(SECOND, {$fechaResto1}, {$fechaResto2}) > 0, SEC_TO_TIME(ROUND(TIMESTAMPDIFF(SECOND, {$fechaResto1}, {$fechaResto2}))), 0) AS tiempoEspera
						FROM
							dau.dau
						FORCE INDEX (dau_admision_fecha)
						INNER JOIN
							paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59' ";

						$sql .= $condicion;

						$sql .= "	AND
										dau.dau.dau_atencion = {$parametros['tipoAtencion']}
									HAVING
										tiempoEspera > 0
									ORDER BY
										dau.dau.est_id, tiempoEspera ASC )";


				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function obtenerTotalMuestras ( $objCon, $parametros ) {

				$sql = "SELECT
							COUNT(muestra_deciles.dau_id) AS totalMuestras
						FROM
							dau.muestra_deciles FORCE INDEX (indexMuestraDeciles)
						WHERE
							muestra_deciles.dau_atencion = '{$parametros['tipoAtencion']}'
						AND
							muestra_deciles.dau_categorizacion = '{$parametros['tipoCategorizacion']}' ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos[0];

		}



		function obtenerTotalMuestrasNEA ( $objCon, $parametros ) {

				$sql = "SELECT
							COUNT(muestra_deciles_nea.dau_id) AS totalMuestras
						FROM
							dau.muestra_deciles_nea FORCE INDEX (indexMuestraDecilesNEA)
						WHERE
							muestra_deciles_nea.dau_atencion = '{$parametros['tipoAtencion']}' ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos[0];

		}



		function obtenerTotalMuestrasDetalle ( $objCon, $parametros ) {

			$condicion = '';

			if ( $parametros['tipoCategorizacion'] != 'NEA' ) {

					$condicion .= " WHERE
										detalle_muestra_deciles.tipoCategorizacion = '{$parametros['tipoCategorizacion']}'
									AND
										detalle_muestra_deciles.dau_atencion = '{$parametros['tipoAtencion']}' ";

				} else {

					$condicion .= " WHERE
										detalle_muestra_deciles.dau_atencion = '{$parametros['tipoAtencion']}' ";

				}

				$sql = "SELECT
							COUNT(detalle_muestra_deciles.numeroDAU) AS totalMuestras
						FROM
							dau.detalle_muestra_deciles ";

				$sql .= $condicion;

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia obtenerTotalMuestrasDetalle<br>");

				return $datos[0];

		}



		function obtenerTiempoPromedioDeciles ( $objCon, $parametros ) {

			$sql = "SELECT
						SEC_TO_TIME(ROUND(AVG(datos.tiempoEspera))) AS tiempoPromedio
					FROM(
						SELECT
							muestra_deciles.dau_categorizacion_fecha,
							muestra_deciles.dau_inicio_atencion_fecha,
							muestra_deciles.tiempoEspera
						FROM
							dau.muestra_deciles FORCE INDEX (indexMuestraDeciles)
						WHERE
							muestra_deciles.dau_atencion = '{$parametros['tipoAtencion']}'
						AND
							muestra_deciles.dau_categorizacion = '{$parametros['tipoCategorizacion']}'
					LIMIT {$parametros['cantidadATomar']} OFFSET {$parametros['desdeDondeTomar']}) AS datos";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

			return $datos[0];

		}



		function obtenerTiempoPromedioDecilesNEA ( $objCon, $parametros ) {

			$sql = "SELECT
						SEC_TO_TIME(ROUND(AVG(datos.tiempoEspera))) AS tiempoPromedio
					FROM(
						SELECT
							muestra_deciles_nea.tiempoEspera
						FROM
							dau.muestra_deciles_nea FORCE INDEX (indexMuestraDecilesNEA)
						WHERE
							muestra_deciles_nea.dau_atencion = '{$parametros['tipoAtencion']}'

					LIMIT {$parametros['cantidadATomar']} OFFSET {$parametros['desdeDondeTomar']}) AS datos";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

			return $datos[0];

		}



		function obtenerDetalleResumenTiemposEsperaDeciles ( $objCon, $parametros, &$totalPag, &$total ) {

			require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';

				$sql = "SELECT
							*
						FROM
							dau.detalle_muestra_deciles ";

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= ( $condicion == '' ) ? ' WHERE ' : ' AND ';

							$condicion .= "detalle_muestra_deciles.numeroDAU = '{$parametros['numeroDAU']}' ";

						}

						if ( ! empty($parametros['nombrePaciente']) && ! is_null($parametros['nombrePaciente']) ) {

							$condicion .= ( $condicion == '' ) ? ' WHERE ' : ' AND ';

							$condicion .= "detalle_muestra_deciles.nombrePaciente LIKE '%{$parametros['nombrePaciente']}%'";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= ( $condicion == '' ) ? ' WHERE ' : ' AND ';

							$condicion .= "detalle_muestra_deciles.rutPaciente = '{$parametros['rutPaciente']}' ";

						}

				$sql .= $condicion;

				$sql .= " ORDER BY tiempoEspera ASC ";

				if ( isset($parametros['cantidadATomar']) && isset($parametros['desdeDondeTomar']) ) {

					$sql .= " LIMIT {$parametros['cantidadATomar']} OFFSET {$parametros['desdeDondeTomar']}";

				}

				$datos    = $objCon->consultaSQL($sql, '');

				$total    = count($datos);

				$sql2    .= "SELECT datos.* FROM (".$sql.") AS datos LIMIT $offset, $limit";

				$datos    = $objCon->consultaSQL($sql2,"<br>Error en el Reporte Tiempos CR Urgencia1<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function obtenerResumenCumplimientoCategorizacionESI ( $objCon, $parametros ) {

				$sql = "SELECT
							dau.dau.dau_atencion AS tipoAtencion,
							dau.dau.dau_categorizacion AS tipoCategorizacion,
							COUNT( dau.dau_id ) AS totalAtencion,
						CASE
							WHEN dau.dau_categorizacion = 'ESI-1' THEN
							SUM( IF ( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) <= 60, 1, 0 ) )
							WHEN dau.dau_categorizacion = 'ESI-2' THEN
							SUM( IF ( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) <= 1860, 1, 0 ) )
							WHEN dau.dau_categorizacion = 'ESI-3' THEN
							SUM( IF ( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) <= 5460, 1, 0 ) )
							WHEN dau.dau_categorizacion = 'ESI-4' THEN
							SUM( IF ( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) <= 10860, 1, 0 ) )
							WHEN dau.dau_categorizacion = 'ESI-5' THEN
							SUM( IF ( TIMESTAMPDIFF( SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha ) > 0, 1, 0 ) )
							END AS aTiempo
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND dau.dau_categorizacion IN ('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5')
							AND dau.dau.est_id = 5
							AND dau.dau_atencion IN (1, 2)
							AND dau.dau.dau_admision_fecha IS NOT NULL
							AND dau.dau.dau_inicio_atencion_fecha IS NOT NULL
							AND dau.dau.dau_categorizacion_fecha IS NOT NULL
							AND dau.dau.dau_cierre_fecha_final IS NOT NULL
						GROUP BY
							dau.dau.dau_atencion, dau.dau.dau_categorizacion";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos;

		}



		function obtenerDetalleResumenCumplimientoCategorizacionESI ( $objCon, $parametros, &$totalPag, &$total ) {

			require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';

				$sql = "SELECT
							datos2.*
						FROM(
							SELECT
								datos.numeroDAU,
								datos.dau_atencion,
								datos.tipoCategorizacion,
								datos.tipoAtencion,
								datos.nombrePaciente,
								datos.rutPaciente,
								datos.fechaAdmision,
								datos.fechaCategorizacion,
								datos.fechaInicioAtencion,
								SEC_TO_TIME(datos.restaTiempoEspera) AS tiempoEspera,
								CASE
									WHEN datos.tipoCategorizacion = 'ESI-1' AND datos.restaTiempoEspera <= 60 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-1' AND datos.restaTiempoEspera > 60  THEN 'No a tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-2' AND datos.restaTiempoEspera <= 1860 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-2' AND datos.restaTiempoEspera > 1860  THEN 'No a tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-3' AND datos.restaTiempoEspera <= 5460 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-3' AND datos.restaTiempoEspera > 5460  THEN 'No a tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-4' AND datos.restaTiempoEspera <= 10860 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-4' AND datos.restaTiempoEspera > 10860 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-5' AND datos.restaTiempoEspera <= 10860 THEN 'A tiempo'
									WHEN datos.tipoCategorizacion = 'ESI-5' AND datos.restaTiempoEspera > 10860 THEN 'A tiempo'
								END AS aTiempo
							FROM(
								SELECT
									dau.dau.dau_id AS numeroDAU,
									dau.dau.dau_atencion,
									dau.dau.dau_categorizacion AS tipoCategorizacion,
									CASE
										WHEN dau.dau.dau_atencion = 1 THEN
										'Adulto'
										WHEN dau.dau.dau_atencion = 2 THEN
										'Pediátrico'
									END AS tipoAtencion,
									CONCAT(paciente.paciente.nombres, ' ', paciente.paciente.apellidopat, ' ', paciente.paciente.apellidomat) AS nombrePaciente,
									paciente.paciente.rut AS rutPaciente,
									dau.dau.dau_admision_fecha AS fechaAdmision,
									dau.dau.dau_categorizacion_fecha AS fechaCategorizacion,
									dau.dau.dau_inicio_atencion_fecha AS fechaInicioAtencion,
									ROUND(TIMESTAMPDIFF(SECOND, dau.dau.dau_categorizacion_fecha, dau.dau.dau_inicio_atencion_fecha)) AS restaTiempoEspera
								FROM
									dau.dau FORCE INDEX ( dau_admision_fecha )
								INNER JOIN
									paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
								WHERE
									dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
								AND
									dau.dau.est_id = 5
								AND
									dau.dau.dau_categorizacion = '{$parametros['tipoCategorizacion']}'
								AND
									dau.dau.dau_admision_fecha IS NOT NULL
								AND
									dau.dau.dau_categorizacion_fecha IS NOT NULL
								AND
									dau.dau.dau_inicio_atencion_fecha IS NOT NULL ) AS datos ) AS datos2 ";

						$condicion = " WHERE datos2.dau_atencion IN (1, 2) ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion = " WHERE datos2.dau_atencion IN {$parametros['tipoAtencion']} ";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND datos2.numeroDAU = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND datos2.nombrePaciente LIKE '%{$parametros['nombrePaciente']}%' ";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND datos2.rutPaciente = '{$parametros['rutPaciente']}' ";

						}

						if ( ! empty($parametros['aTiempo']) && ! is_null($parametros['aTiempo']) && $parametros['aTiempo'] != 'Ambos' ) {

							$condicion .= " AND datos2.aTiempo = '{$parametros['aTiempo']}' ";

						}


				$sql .= $condicion;

				$sql .= " ORDER BY datos2.tiempoEspera ASC ";

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function obtenerResumenDiagnosticosInespecificos ( $objCon, $parametros ) {

				$sql = "SELECT
							SUM(IF(dau.dau.dau_atencion = 1, 1, 0)) AS totalAdultos,
							SUM(IF(dau.dau.dau_atencion = 2, 1, 0)) AS totalPediatricos,
							SUM(IF(dau.dau.dau_atencion = 1 AND dau.dau.dau_cierre_cie10 = '{$parametros['tipoDiagnostico']}', 1, 0)) AS totalAdultosDiagnostico,
							SUM(IF(dau.dau.dau_atencion = 2 AND dau.dau.dau_cierre_cie10 = '{$parametros['tipoDiagnostico']}', 1, 0)) AS totalPediatricosDiagnostico
						FROM
							dau.dau
						FORCE INDEX (cierreFechaFinal)
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND dau.dau.est_id = 5
							AND dau.dau_atencion IN (1, 2)
							AND dau.dau.dau_cierre_fecha_final IS NOT NULL ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos[0];

		}



		function obtenerDetalleResumenDiagnosticosInespecificos ( $objCon, $parametros, &$totalPag, &$total ){

				require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';



				$sql = "SELECT
							dau.dau.dau_id AS numeroDAU,
							CASE
								WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
								WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
							END AS tipoAtencion,
							CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
							paciente.paciente.rut AS rutPaciente,
							dau.dau.dau_admision_fecha AS fechaAdmision,
							dau.dau.dau_categorizacion_fecha AS fechaCategorizacion,
							dau.dau.dau_inicio_atencion_fecha AS fechaInicioAtencion,
							dau.dau.dau_indicacion_egreso_fecha AS fechaIndicacionEgreso,
							dau.dau.dau_indicacion_egreso_aplica_fecha AS fechaAplicacionIndicacionEgreso,
							dau.dau.dau_cierre_fecha_final AS fechaCierreFinal
						FROM
							dau.dau FORCE INDEX ( dau_admision_fecha )
						INNER JOIN
							paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND
							dau.dau.est_id = 5
						AND
							dau.dau.dau_cierre_cie10 = '{$parametros['tipoDiagnostico']}' ";

						$condicion = " AND dau.dau.dau_atencion IN (1, 2) ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion = " AND dau.dau.dau_atencion IN {$parametros['tipoAtencion']} ";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND dau.dau.dau_id = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND CONCAT(paciente.nombres,' ',paciente.apellidopat,' ',paciente.apellidomat) LIKE REPLACE('%{$parametros['nombrePaciente']}%',' ','%')";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND paciente.paciente.rut= '{$parametros['rutPaciente']}' ";

						}


				$sql .= $condicion;

				$sql .= " ORDER BY dau.dau.dau_id DESC ";

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function crearTablaTemporalTiemposLaboratorio ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.tiempos_laboratorio AS
					(SELECT
						rce.registroclinico.dau_id,
						TIMESTAMPDIFF(SECOND, rce.solicitud_laboratorio.sol_lab_fechaInserta, rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra) as tiempoInsertaTomaMuestra,
						TIMESTAMPDIFF( SECOND, rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra, laboratorio.solicitud.sol_fechaRecepcion) as tiempoTomaMuestraRecepcion,
						TIMESTAMPDIFF( SECOND, laboratorio.solicitud.sol_fechaRecepcion, laboratorio.solicitud.sol_fechaRealiza) as tiempoRecepcionRealizacion
					FROM
					dau.dau
					FORCE INDEX (dau_admision_fecha)
					INNER JOIN rce.registroclinico  FORCE INDEX (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
					INNER JOIN rce.solicitud_laboratorio FORCE INDEX (regId) ON rce.registroclinico.regId = rce.solicitud_laboratorio.regId
					INNER JOIN laboratorio.solicitud FORCE INDEX (sol_lab_id) ON rce.solicitud_laboratorio.sol_lab_id = laboratorio.solicitud.sol_lab_id
					WHERE
						dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						laboratorio.solicitud.est_id = 4
					AND
						rce.solicitud_laboratorio.sol_lab_estado = 4
					AND
						rce.solicitud_laboratorio.sol_lab_fechaInserta IS NOT NULL
					AND
						rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra IS NOT NULL
					AND
						laboratorio.solicitud.sol_fechaRecepcion IS NOT NULL
					AND
						laboratorio.solicitud.sol_fechaRealiza IS NOT NULL
					HAVING
						tiempoInsertaTomaMuestra > 0 AND tiempoTomaMuestraRecepcion > 0 AND tiempoRecepcionRealizacion > 0
					ORDER BY
						laboratorio.solicitud.sol_fechaRealiza ASC ) ";

				$objCon->ejecutarSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$sql = "CREATE INDEX indexTiemposLaboratorio USING BTREE ON dau.tiempos_laboratorio(dau_id) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function crearTablaTemporalDetalleTiemposLaboratorio ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.detalle_tiempos_laboratorio AS
					(SELECT
							rce.registroclinico.dau_id AS numeroDAU,
							dau.dau.dau_atencion,
							CASE
								WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
								WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
							END AS tipoAtencion,
							CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
							paciente.paciente.rut AS rutPaciente,
							rce.solicitud_laboratorio.sol_lab_id AS idSolicitudLaboratorio,
							rce.detalle_solicitud_laboratorio.det_lab_descripcion AS descripcionSolicitudLaboratorio,
							TIMESTAMPDIFF(SECOND, rce.solicitud_laboratorio.sol_lab_fechaInserta, rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra) AS tiempoInsertaTomaMuestra,
							TIMESTAMPDIFF(SECOND, rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra, laboratorio.solicitud.sol_fechaRecepcion) AS tiempoTomaMuestraRecepcion,
							TIMESTAMPDIFF(SECOND, laboratorio.solicitud.sol_fechaRecepcion, laboratorio.solicitud.sol_fechaRealiza) AS tiempoRecepcionRealizacion
						FROM
							dau.dau FORCE INDEX ( cierreFechaFinal )
							INNER JOIN rce.registroclinico FORCE INDEX (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
							INNER JOIN rce.solicitud_laboratorio FORCE INDEX (regId) ON rce.registroclinico.regId = rce.solicitud_laboratorio.regId
							INNER JOIN laboratorio.solicitud FORCE INDEX (sol_lab_id) ON rce.solicitud_laboratorio.sol_lab_id = laboratorio.solicitud.sol_lab_id
							INNER JOIN rce.detalle_solicitud_laboratorio FORCE INDEX (sol_lab_id) ON rce.solicitud_laboratorio.sol_lab_id = rce.detalle_solicitud_laboratorio.sol_lab_id
							INNER JOIN paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
						WHERE
							dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND dau.dau.est_id = 5
							AND laboratorio.solicitud.est_id = 4
							AND rce.solicitud_laboratorio.sol_lab_estado = 4
							AND rce.solicitud_laboratorio.sol_lab_fechaInserta IS NOT NULL
							AND rce.solicitud_laboratorio.sol_lab_fechaTomaMuestra IS NOT NULL
							AND laboratorio.solicitud.sol_fechaRecepcion IS NOT NULL
							AND laboratorio.solicitud.sol_fechaRealiza IS NOT NULL
							HAVING
								tiempoInsertaTomaMuestra > 0 AND tiempoTomaMuestraRecepcion > 0 AND tiempoRecepcionRealizacion > 0) ";

				$objCon->ejecutarSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$sql = "CREATE INDEX indexDetalleTiemposLaboratorio USING BTREE ON dau.detalle_tiempos_laboratorio(dau_atencion) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function obtenerTotalDAUTiemposLaboratorio ( $objCon ) {

				$sql = "SELECT
							COUNT(tiempos_laboratorio.dau_id) AS totalDAUTiemposLaboratorio
						FROM
							dau.tiempos_laboratorio FORCE INDEX (indexTiemposLaboratorio)
						GROUP BY
							tiempos_laboratorio.dau_id ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalDatos = count($datos);

				return $totalDatos;

		}



		function obtenerDetalleDAUTiemposLaboratorio ( $objCon, $parametros, &$totalPag, &$total ) {

			require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';

				$sql = "SELECT
							detalle_tiempos_laboratorio.*,
							COUNT(detalle_tiempos_laboratorio.idSolicitudLaboratorio) AS totalSolicitudesLaboratorio,
							SEC_TO_TIME(ROUND(AVG(detalle_tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoPromedioInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(MAX(detalle_tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoMaximoInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(MIN(detalle_tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoMinimoInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(AVG(detalle_tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoPromedioTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(MAX(detalle_tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoMaximoTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(MIN(detalle_tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoMinimoTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(AVG(detalle_tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoPromedioRecepcionRealizacion,
							SEC_TO_TIME(ROUND(MAX(detalle_tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoMaximoRecepcionRealizacion,
							SEC_TO_TIME(ROUND(MIN(detalle_tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoMinimoRecepcionRealizacion
						FROM
							dau.detalle_tiempos_laboratorio FORCE INDEX (indexDetalleTiemposLaboratorio)";

						$condicion = " WHERE detalle_tiempos_laboratorio.dau_atencion IN (1, 2) ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion = " WHERE detalle_tiempos_laboratorio.dau_atencion IN {$parametros['tipoAtencion']} ";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND detalle_tiempos_laboratorio.numeroDAU = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND detalle_tiempos_laboratorio.nombrePaciente LIKE '%{$parametros['nombrePaciente']}%' ";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND detalle_tiempos_laboratorio.rutPaciente = '{$parametros['rutPaciente']}' ";

						}


				$sql .= $condicion;

				$sql .= " GROUP BY detalle_tiempos_laboratorio.numeroDAU DESC "; //echo $sql;

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function obtenerResumenTiemposLaboratorio ( $objCon ) {

				$sql = "SELECT
							COUNT(tiempos_laboratorio.dau_id) AS cantidadIndicaciones,
							SEC_TO_TIME(ROUND(AVG(tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoPromedioInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(MAX(tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoMaximoInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(MIN(tiempos_laboratorio.tiempoInsertaTomaMuestra))) AS tiempoMinimoInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(AVG(tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoPromedioTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(MAX(tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoMaximoTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(MIN(tiempos_laboratorio.tiempoTomaMuestraRecepcion))) AS tiempoMinimoTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(AVG(tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoPromedioRecepcionRealizacion,
							SEC_TO_TIME(ROUND(MAX(tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoMaximoRecepcionRealizacion,
							SEC_TO_TIME(ROUND(MIN(tiempos_laboratorio.tiempoRecepcionRealizacion))) AS tiempoMinimoRecepcionRealizacion
						FROM
							dau.tiempos_laboratorio ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos[0];

		}



		function obtenerDetalleTiemposLaboratorio ( $objCon, $numeroDAU ) {

				$sql = "SELECT
							detalle_tiempos_laboratorio.idSolicitudLaboratorio,
							detalle_tiempos_laboratorio.descripcionSolicitudLaboratorio,
							SEC_TO_TIME(ROUND(detalle_tiempos_laboratorio.tiempoInsertaTomaMuestra)) AS tiempoInsertaTomaMuestra,
							SEC_TO_TIME(ROUND(detalle_tiempos_laboratorio.tiempoTomaMuestraRecepcion)) AS tiempoTomaMuestraRecepcion,
							SEC_TO_TIME(ROUND(detalle_tiempos_laboratorio.tiempoRecepcionRealizacion)) AS tiempoRecepcionRealizacion
						FROM
							dau.detalle_tiempos_laboratorio
						WHERE
							detalle_tiempos_laboratorio.numeroDAU = '{$numeroDAU}'
						ORDER BY
							detalle_tiempos_laboratorio.idSolicitudLaboratorio ASC";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR obtenerDetalleTiemposLaboratorio<br>");

				return $datos;

		}



		function crearTablaTemporalTiemposImagenologia ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.tiempos_imagenologia AS
					(SELECT
						dau.dau.dau_id,
						rce.registroclinico.regId,
						IF(
							rce.detalle_solicitud_imagenologia.det_ima_tipo_examen IS NULL
							OR rce.detalle_solicitud_imagenologia.det_ima_tipo_examen = '',
							le.prestaciones_imagenologia.tipo_examen,
							rce.detalle_solicitud_imagenologia.det_ima_tipo_examen
						) AS det_ima_tipo_examen,
						rce.solicitud_imagenologia.sol_ima_fechaInserta,
						rce.solicitud_imagenologia.sol_ima_fechaAplica,
						TIMESTAMPDIFF( SECOND, rce.solicitud_imagenologia.sol_ima_fechaInserta, rce.solicitud_imagenologia.sol_ima_fechaAplica) as tiempoInsertaAplica
					FROM
						dau.dau
					FORCE INDEX
						(cierreFechaFinal)
					INNER JOIN
						rce.registroclinico FORCE INDEX (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
					INNER JOIN
						rce.solicitud_imagenologia FORCE INDEX (REG_ID) ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
					INNER JOIN
						rce.detalle_solicitud_imagenologia FORCE INDEX (SOL_ima_id) ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia.sol_ima_id
					LEFT JOIN
						rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
					LEFT JOIN
						le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
					WHERE
						dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						rce.solicitud_imagenologia.sol_ima_fechaInserta IS NOT NULL
					AND
						rce.solicitud_imagenologia.sol_ima_fechaAplica IS NOT NULL
					AND
						dau.dau.est_id = 5
					AND
						rce.solicitud_imagenologia.sol_ima_estado = 4
					HAVING
						tiempoInsertaAplica > 0
					ORDER BY
						rce.detalle_solicitud_imagenologia.det_ima_tipo_examen) ";

				$objCon->ejecutarSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$sql = "CREATE INDEX indexTiemposImagenologia USING BTREE ON dau.tiempos_imagenologia(det_ima_tipo_examen) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function crearTablaTemporalDetalleTiemposImagenologia ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS detalle_tiempos_imagenologia AS
					(
						SELECT
							datosDetalleImagenologia.*
						FROM
						(
							SELECT
								dau.dau.dau_id AS numeroDAU,
								dau.dau.dau_atencion,
								CASE
									WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
									WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
									WHEN dau.dau.dau_atencion = 3 THEN 'Ginecológico'
								END AS tipoAtencion,
								CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
								paciente.paciente.rut AS rutPaciente,
								rce.detalle_solicitud_imagenologia.sol_ima_id AS idSolicitudImagenologia,
								rce.detalle_solicitud_imagenologia.det_ima_tipo_examen COLLATE utf8_spanish_ci AS tipoExamenImagenologia,
								rce.detalle_solicitud_imagenologia.det_ima_descripcion COLLATE utf8_spanish_ci AS descripcionExamenImagenologia,
								rce.solicitud_imagenologia.sol_ima_fechaInserta,
								rce.solicitud_imagenologia.sol_ima_fechaAplica,
								TIMESTAMPDIFF( SECOND, rce.solicitud_imagenologia.sol_ima_fechaInserta, rce.solicitud_imagenologia.sol_ima_fechaAplica) as tiempoInsertaAplica
							FROM
								dau.dau
							FORCE INDEX
								(cierreFechaFinal)
							INNER JOIN
								rce.registroclinico FORCE INDEX (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
							INNER JOIN
								rce.solicitud_imagenologia FORCE INDEX (REG_ID) ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
							INNER JOIN
								rce.detalle_solicitud_imagenologia FORCE INDEX (SOL_ima_id) ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia.sol_ima_id
							INNER JOIN
								paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
							WHERE
								dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND
								rce.solicitud_imagenologia.sol_ima_fechaInserta IS NOT NULL
							AND
								rce.solicitud_imagenologia.sol_ima_fechaAplica IS NOT NULL
							AND
								dau.dau.est_id = 5
							AND
								rce.solicitud_imagenologia.sol_ima_estado = 4

							UNION ALL

							SELECT
								dau.dau.dau_id AS numeroDAU,
								dau.dau.dau_atencion,
								CASE
									WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
									WHEN dau.dau.dau_atencion = 2 THEN 'Pediátrico'
									WHEN dau.dau.dau_atencion = 3 THEN 'Ginecológico'
								END AS tipoAtencion,
								CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS nombrePaciente,
								paciente.paciente.rut AS rutPaciente,
								rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia AS idSolicitudImagenologia,
								le.prestaciones_imagenologia.tipo_examen AS tipoExamenImagenologia,
								le.prestaciones_imagenologia.examen AS descripcionExamenImagenologia,
								rce.solicitud_imagenologia.sol_ima_fechaInserta,
								rce.solicitud_imagenologia.sol_ima_fechaAplica,
								TIMESTAMPDIFF( SECOND, rce.solicitud_imagenologia.sol_ima_fechaInserta, rce.solicitud_imagenologia.sol_ima_fechaAplica) as tiempoInsertaAplica
							FROM
								dau.dau
							FORCE INDEX
								(cierreFechaFinal)
							INNER JOIN
								rce.registroclinico FORCE INDEX (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
							INNER JOIN
								rce.solicitud_imagenologia FORCE INDEX (REG_ID) ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
							INNER JOIN
								rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
							INNER JOIN
								le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
							INNER JOIN
								paciente.paciente FORCE INDEX (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
							WHERE
								dau.dau.dau_cierre_fecha_final BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
							AND
								rce.solicitud_imagenologia.sol_ima_fechaInserta IS NOT NULL
							AND
								rce.solicitud_imagenologia.sol_ima_fechaAplica IS NOT NULL
							AND
								dau.dau.est_id = 5
							AND
								rce.solicitud_imagenologia.sol_ima_estado = 4

							HAVING
								tiempoInsertaAplica > 0
							ORDER BY
								tipoExamenImagenologia, numeroDAU
						) AS datosDetalleImagenologia
					)
				";

				$objCon->ejecutarSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$sql = "CREATE INDEX indexDetalleTiemposImagenologia_tipoExamen USING BTREE ON detalle_tiempos_imagenologia(tipoExamenImagenologia) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$sql = "CREATE INDEX indexDetalleTiemposImagenologia_numeroDAU USING BTREE ON detalle_tiempos_imagenologia(numeroDAU) ";

				$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function obtenerDetalleDAUTiemposImagenologia ( $objCon, $parametros, &$totalPag, &$total ) {

			require_once("Util.class.php");       $objUtil    = new Util;

				if ( $_SESSION['pagina_actual'] < 1 ) {

						$_SESSION['pagina_actual'] = 1;

				}

				$limit = 20;

				$offset = ($_SESSION['pagina_actual']-1) * $limit;

				$condicion = '';

				$sql = "SELECT
							detalle_tiempos_imagenologia.*,
							COUNT(detalle_tiempos_imagenologia.idSolicitudImagenologia) AS totalSolicitudesImagenologia,
							SEC_TO_TIME(ROUND(AVG(detalle_tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoPromedioInsertaAplica,
							SEC_TO_TIME(ROUND(MAX(detalle_tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoMaximoInsertaAplica,
							SEC_TO_TIME(ROUND(MIN(detalle_tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoMinimoInsertaAplica
						FROM
							detalle_tiempos_imagenologia  FORCE INDEX (indexDetalleTiemposImagenologia_tipoExamen)
						WHERE
							detalle_tiempos_imagenologia.tipoExamenImagenologia = '{$parametros['tipoExamen']}' ";

						$condicion = " AND detalle_tiempos_imagenologia.dau_atencion IN (1, 2) ";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion = " AND detalle_tiempos_imagenologia.dau_atencion IN {$parametros['tipoAtencion']} ";

						}

						if ( ! empty($parametros['numeroDAU']) && ! is_null($parametros['numeroDAU']) ) {

							$condicion .= " AND detalle_tiempos_imagenologia.numeroDAU = '{$parametros['numeroDAU']}' ";

						}

						if ( $parametros['nombrePaciente'] ) {

							$condicion .= " AND detalle_tiempos_imagenologia.nombrePaciente LIKE '%{$parametros['nombrePaciente']}%' ";

						}

						if ( ! empty($parametros['rutPaciente']) && ! is_null($parametros['rutPaciente']) ) {

							$condicion .= " AND detalle_tiempos_imagenologia.rutPaciente = '{$parametros['rutPaciente']}' ";

						}


				$sql .= $condicion;

				$sql .= " GROUP BY detalle_tiempos_imagenologia.numeroDAU DESC ";

				$datos = $objCon->consultaSQL($sql, '');

				$sqlTotalResultados = " SELECT FOUND_ROWS() as totalResultados";

				$totalResultados = $objCon->consultaSQL($sqlTotalResultados,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$total    = $totalResultados[0]["totalResultados"];

				$sql  .= " LIMIT $offset, $limit";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalPag = ceil($total/$limit);

				return $datos;

		}



		function obtenerDetalleTiemposImagenologia ( $objCon, $parametros) {

				$sql = "SELECT
							detalle_tiempos_imagenologia.tipoExamenImagenologia,
							detalle_tiempos_imagenologia.descripcionExamenImagenologia,
							SEC_TO_TIME(ROUND(detalle_tiempos_imagenologia.tiempoInsertaAplica)) AS tiempoInsertaAplica
						FROM
							detalle_tiempos_imagenologia FORCE INDEX (indexDetalleTiemposImagenologia_numeroDAU)
						WHERE
							detalle_tiempos_imagenologia.numeroDAU = '{$parametros['numeroDAU']}'
						AND
							detalle_tiempos_imagenologia.tipoExamenImagenologia = '{$parametros['tipoExamenImagenologia']}'
						ORDER BY
							detalle_tiempos_imagenologia.idSolicitudImagenologia ASC";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos;

		}



		function obtenerTotalDAUTiemposImagenologia ( $objCon, $tipoExamen ) {

				$sql = "SELECT
							COUNT(tiempos_imagenologia.dau_id) AS totalDAUTiemposImagenologia
						FROM
							dau.tiempos_imagenologia FORCE INDEX (indexTiemposImagenologia)
						WHERE
							tiempos_imagenologia.det_ima_tipo_examen = '{$tipoExamen}'
						GROUP BY
							tiempos_imagenologia.dau_id ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				$totalDatos = count($datos);

				return $totalDatos;

		}



		function obtenerResumenTiemposImagenologia ( $objCon ) {

				$sql = "SELECT
							COUNT(tiempos_imagenologia.dau_id) AS cantidadIndicaciones,
							tiempos_imagenologia.det_ima_tipo_examen AS tipoExamen,
							SEC_TO_TIME(ROUND(AVG(tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoPromedioInsertaAplica,
							SEC_TO_TIME(ROUND(MAX(tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoMaximoInsertaAplica,
							SEC_TO_TIME(ROUND(MIN(tiempos_imagenologia.tiempoInsertaAplica))) AS tiempoMinimoInsertaAplica
						FROM
							dau.tiempos_imagenologia FORCE INDEX (indexTiemposImagenologia)
						GROUP BY
							tipoExamen ";

				$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

				return $datos;

		}



		function eliminarTablaTemporalMuestraDeciles ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.muestra_deciles";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function eliminarTablaTemporalTiemposLaboratorio ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.tiempos_laboratorio";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function eliminarTablaTemporalTiemposImagenologia ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.tiempos_imagenologia";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function eliminarTablaTemporalDetalleMuestrasDeciles ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.detalle_muestra_deciles";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function eliminarTablaTemporalDetalleTiemposLaboratorio ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.detalle_tiempos_laboratorio";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR eliminarTablaTemporalDetalleTiemposLaboratorio<br>");

		}



		function eliminarTablaTemporalDetalleTiemposImagenologia ( $objCon ) {

			$sql = " DROP TABLE IF EXISTS dau.detalle_tiempos_imagenologia";

			$respuesta = $objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos CR Urgencia<br>");

		}



		function obtenerMedicosUrgencia ( $objCon ) {

			$sql = "SELECT
						acceso.usuario.nombreusuario,
						acceso.usuario.idusuario
					FROM
						acceso.usuario_has_rol
					INNER JOIN acceso.usuario ON acceso.usuario_has_rol.usuario_idusuario = acceso.usuario.idusuario
					WHERE
						acceso.usuario_has_rol.rol_idrol = 1126
					ORDER BY
						acceso.usuario.nombreUsuario ASC ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

			return $datos;

		}



		function obtenerReporteRendimientoCRUrgencia ( $objCon, $parametros ) {

			$sql = "SELECT
					DATE_FORMAT(dau.dau.dau_inicio_atencion_fecha, '%d-%m-%Y') AS fecha,
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}', 1, 0 ) ) AS 'totalPacientes',
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}', 1, 0 ) ) AS 'totalPacientesEgresados',
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-1', 1, 0 ) ) AS pacientesAtendidosESI1,
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-1', 1, 0 ) ) AS pacientesEgresadosESI1,
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-2', 1, 0 ) ) AS pacientesAtendidosESI2,
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-2', 1, 0 ) ) AS pacientesEgresadosESI2,
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-3', 1, 0 ) ) AS pacientesAtendidosESI3,
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-3', 1, 0 ) ) AS pacientesEgresadosESI3,
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-4', 1, 0 ) ) AS pacientesAtendidosESI4,
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-4', 1, 0 ) ) AS pacientesEgresadosESI4,
					SUM( IF ( dau.dau_inicio_atencion_usuario 	= '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-5', 1, 0 ) ) AS pacientesAtendidosESI5,
					SUM( IF ( dau.dau_indicacion_egreso_usuario = '{$parametros['idMedicoUrgencia']}' AND dau.dau_categorizacion = 'ESI-5', 1, 0 ) ) AS pacientesEgresadosESI5,
					SUM(
						(	SELECT
								1
							FROM
								rce.registroclinico FORCE INDEX ( dau_id )
							LEFT JOIN
								rce.solicitud_indicaciones FORCE INDEX ( regId ) ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
							WHERE
								rce.registroclinico.dau_id = dau.dau_id
							AND
								rce.solicitud_indicaciones.sol_ind_usuarioInserta = '{$parametros['idMedicoUrgencia']}'
							AND
								dau.dau_categorizacion = 'ESI-4'
							AND
								rce.solicitud_indicaciones.sol_clasificacionTratamiento = 1
							GROUP BY
								dau.dau_id
						)
					) AS intravenososESI4,
					SUM(
						(	SELECT
								1
							FROM
								rce.registroclinico FORCE INDEX ( dau_id )
							LEFT JOIN
								rce.solicitud_indicaciones FORCE INDEX ( regId ) ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
							WHERE
								rce.registroclinico.dau_id = dau.dau_id
							AND
								rce.solicitud_indicaciones.sol_ind_usuarioInserta = '{$parametros['idMedicoUrgencia']}'
							AND
								dau.dau_categorizacion = 'ESI-5'
							AND
								rce.solicitud_indicaciones.sol_clasificacionTratamiento = 1
							GROUP BY
								rce.registroclinico.regId
						)
					) AS intravenososESI5,
					(	SELECT
							count(rce.solicitud_especialista.SESPid) as cantidad
						FROM
							rce.solicitud_especialista
						WHERE
							rce.solicitud_especialista.SESPestado = 4
						AND
							date(rce.solicitud_especialista.SESPfechaAplicacion) = date(dau.dau.dau_inicio_atencion_fecha)
						AND
							rce.solicitud_especialista.SESPusuario = '{$parametros['idMedicoUrgencia']}'
					) as totalSolicitudesEspecialistaPedidas,
					(	SELECT
							count(rce.solicitud_especialista.SESPid) as cantidad
						FROM
							rce.solicitud_especialista
						WHERE
							rce.solicitud_especialista.SESPestado = 4
						AND
							date(rce.solicitud_especialista.SESPfechaAplicacion) = date(dau.dau.dau_inicio_atencion_fecha)
						AND
							rce.solicitud_especialista.SESPusuarioAplica = '{$parametros['idMedicoUrgencia']}'
					) as totalSolicitudesEspecialistaRealizadas
				FROM
					dau.dau
				WHERE
					dau.dau.dau_inicio_atencion_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' and '{$parametros['fechaActual']} 23:59:59'
				AND
					dau.dau.est_id = 5
				GROUP BY
					date(dau.dau.dau_inicio_atencion_fecha)
				HAVING
					totalPacientes > 0
				OR
					totalPacientesEgresados > 0
				OR
					totalSolicitudesEspecialistaPedidas > 0
				OR
					totalSolicitudesEspecialistaRealizadas > 0";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

			return $datos;

		}



		function crearTablaTemporalResumenTiemposCiclo ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.tiempos_ciclo AS
					(
						SELECT
							dau.dau_id,
							dau.dau_atencion,
							dau.dau_categorizacion,
							dau.dau_indicacion_egreso,
							dau.dau_admision_fecha,
							dau.dau_cierre_fecha_final,
							TIMESTAMPDIFF( SECOND, dau.dau_admision_fecha, dau.dau_cierre_fecha_final) AS tiempoEnBox
						FROM
							dau.dau
						FORCE
							INDEX (dau_admision_fecha)
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND
							dau.dau_admision_fecha IS NOT NULL
						AND
							dau.dau_cierre_fecha_final IS NOT NULL
						AND
							dau.est_id = 5
						AND
							dau.dau_atencion IN (1, 2)
						AND
							dau.dau.dau_categorizacion IN ( 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5' )
						HAVING
							tiempoEnBox > 0
						ORDER BY
							dau.dau_atencion, dau.dau_categorizacion, dau.dau_indicacion_egreso
					)";

			$objCon->ejecutarSQL($sql,"<br>Error en el Reporte Tiempos de crearTablaTemporalResumenTiemposCiclo<br>");

			$sql = "CREATE INDEX indexTiemposCicloAdulto USING BTREE ON dau.tiempos_ciclo(dau_atencion, dau_categorizacion, dau_indicacion_egreso) ";

			$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos de crearTablaTemporalResumenTiemposCiclo<br>");

		}



		function obtenerTiemposCiclo ( $objCon, $parametros ) {

			$condicion = '';

			$sql = "SELECT
						COUNT(tiempos_ciclo.dau_id) AS totalAtencion,
						SEC_TO_TIME(ROUND(AVG(tiempos_ciclo.tiempoEnBox))) AS tiempoPromedioEnBox,
						SEC_TO_TIME(ROUND(MIN(tiempos_ciclo.tiempoEnBox))) AS tiempoMinimoEnBox,
						SEC_TO_TIME(ROUND(MAX(tiempos_ciclo.tiempoEnBox))) AS tiempoMaximoEnBox
					FROM
						dau.tiempos_ciclo FORCE INDEX (indexTiemposCicloAdulto)";

					if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " tiempos_ciclo.dau_atencion  = '{$parametros['tipoAtencion']}' ";

					}

					if ( ! empty($parametros['tipoCategorizacion']) && ! is_null($parametros['tipoCategorizacion']) && $parametros['tipoCategorizacion'] != 'Atendidos' ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " tiempos_ciclo.dau_categorizacion  = '{$parametros['tipoCategorizacion']}' ";

					}

					if ( ! empty($parametros['tipoEgreso']) && ! is_null($parametros['tipoEgreso']) ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " tiempos_ciclo.dau_indicacion_egreso = '{$parametros['tipoEgreso']}' ";

					}

			$sql .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos de Ciclo<br>");

			return $datos[0];

		}



		function crearTablaTemporalTiemposCicloHospitalizacionUrgencia ( $objCon, $parametros ) {

			$sql = "CREATE TEMPORARY TABLE IF NOT EXISTS dau.muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia AS
					(
						SELECT
							dau.dau.dau_id,
							dau.dau.dau_atencion,
							dau.dau.dau_categorizacion,
							dau.dau.dau_indicacion_egreso,
							dau.dau.dau_admision_fecha,
							dau.dau.dau_indicacion_egreso_fecha,
							dau.dau.dau_cierre_fecha_final,
							TIMESTAMPDIFF(SECOND, dau.dau.dau_admision_fecha, dau.dau.dau_indicacion_egreso_fecha) AS tiempoEspera,
							TIMESTAMPDIFF(SECOND, dau.dau.dau_admision_fecha, dau.dau.dau_cierre_fecha_final) AS tiempoEsperaCierre
						FROM
							dau.dau
						FORCE
							INDEX (dau_admision_fecha)
						WHERE
							dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
						AND
							dau.dau.dau_categorizacion IN ( 'ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5' )
						AND
							dau.dau.est_id = 5
						AND
							dau.dau.dau_atencion IN (1, 2)
						AND
							dau.dau.dau_admision_fecha IS NOT NULL
						AND
							dau.dau.dau_cierre_fecha_final IS NOT NULL
						AND
							dau.dau.dau_indicacion_egreso IN (3, 4)
						HAVING
							tiempoEspera > 0 AND tiempoEsperaCierre > 0
						ORDER BY
							dau.dau.dau_atencion, dau.dau.dau_indicacion_egreso, dau.dau.dau_categorizacion ASC
					)";

			$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos de crearTablaTemporalTiemposCicloHospitalizacionUrgencia<br>");

			$sql = "CREATE INDEX indexMuestraDecilesTiempoCicloAdultos USING BTREE ON dau.muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia(dau_atencion, dau_indicacion_egreso, dau_categorizacion) ";

			$objCon->ejecutarSQL($sql, "<br>Error en el Reporte Tiempos de crearTablaTemporalTiemposCicloHospitalizacionUrgencia<br>");

		}



		function obtenerTotalMuestrasCicloHospitalizacionUrgencia ( $objCon, $parametros ) {

			$condicion = '';

			$sql = "SELECT
						COUNT(muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_id) AS totalMuestras
					FROM
						dau.muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia FORCE INDEX (indexMuestraDecilesTiempoCicloAdultos) ";

					if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_atencion  = '{$parametros['tipoAtencion']}' ";

					}

					if ( ! empty($parametros['tipoEgreso']) && ! is_null($parametros['tipoEgreso']) ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_indicacion_egreso  = '{$parametros['tipoEgreso']}' ";

					}

					if ( ! empty($parametros['tipoCategorizacion']) && ! is_null($parametros['tipoCategorizacion']) && $parametros['tipoCategorizacion'] != 'Atendidos' ) {

						$condicion .= ($condicion == "") ? " WHERE " : " AND ";

						$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_categorizacion  = '{$parametros['tipoCategorizacion']}' ";

					}

			$sql .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos de Ciclo<br>");

			return $datos[0];

		}



		function obtenerTiempoPromedioDecilesHospitalizacionUrgencia ( $objCon, $parametros ) {

			$tiempoATomar = '';

			$condicion    = '';

			$orden		    = '';

			if ( $parametros['tipoResumen'] == 'indicacionEgreso' ) {

				$tiempoATomar = 'tiempoEspera';

				$orden .= ' ORDER BY tiempoEspera ASC ';

			} else if ( $parametros['tipoResumen'] == 'cierre' ) {

				$tiempoATomar = 'tiempoEsperaCierre';

				$orden .= ' ORDER BY tiempoEsperaCierre ASC ';

			}



			$sql = "SELECT
						SEC_TO_TIME(ROUND(AVG(datos.{$tiempoATomar}))) AS tiempoPromedio
					FROM(
						SELECT
							muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.{$tiempoATomar}
						FROM
							dau.muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia FORCE INDEX (indexMuestraDecilesTiempoCicloAdultos)";

						if ( ! empty($parametros['tipoAtencion']) && ! is_null($parametros['tipoAtencion']) ) {

							$condicion .= ($condicion == "") ? " WHERE " : " AND ";

							$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_atencion  = '{$parametros['tipoAtencion']}' ";

						}

						if ( ! empty($parametros['tipoEgreso']) && ! is_null($parametros['tipoEgreso']) ) {

							$condicion .= ($condicion == "") ? " WHERE " : " AND ";

							$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_indicacion_egreso  = '{$parametros['tipoEgreso']}' ";

						}

						if ( ! empty($parametros['tipoCategorizacion']) && ! is_null($parametros['tipoCategorizacion']) && $parametros['tipoCategorizacion'] != 'Atendidos' ) {

							$condicion .= ($condicion == "") ? " WHERE " : " AND ";

							$condicion.= " muestra_deciles_tiempo_ciclo_hospitalizacion_urgencia.dau_categorizacion  = '{$parametros['tipoCategorizacion']}' ";

						}

			$sql .= $condicion.$orden;

			$sql .= "	LIMIT {$parametros['cantidadATomar']} OFFSET {$parametros['desdeDondeTomar']}) AS datos";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Tiempos CR Urgencia<br>");

			return $datos[0];

		}



		function obtenerDauCerradosEnSemanas ( $objCon, $anioResumen ) {

			$sql = "SELECT
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 0  THEN 1 ELSE 0 END) AS dauCerradosSemana1,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 1  THEN 1 ELSE 0 END) AS dauCerradosSemana2,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 2  THEN 1 ELSE 0 END) AS dauCerradosSemana3,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 3  THEN 1 ELSE 0 END) AS dauCerradosSemana4,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 4  THEN 1 ELSE 0 END) AS dauCerradosSemana5,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 5  THEN 1 ELSE 0 END) AS dauCerradosSemana6,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 6  THEN 1 ELSE 0 END) AS dauCerradosSemana7,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 7  THEN 1 ELSE 0 END) AS dauCerradosSemana8,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 8  THEN 1 ELSE 0 END) AS dauCerradosSemana9,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 9  THEN 1 ELSE 0 END) AS dauCerradosSemana10,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 10 THEN 1 ELSE 0 END) AS dauCerradosSemana11,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 11 THEN 1 ELSE 0 END) AS dauCerradosSemana12,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 12 THEN 1 ELSE 0 END) AS dauCerradosSemana13,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 13 THEN 1 ELSE 0 END) AS dauCerradosSemana14,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 14 THEN 1 ELSE 0 END) AS dauCerradosSemana15,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 15 THEN 1 ELSE 0 END) AS dauCerradosSemana16,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 16 THEN 1 ELSE 0 END) AS dauCerradosSemana17,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 17 THEN 1 ELSE 0 END) AS dauCerradosSemana18,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 18 THEN 1 ELSE 0 END) AS dauCerradosSemana19,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 19 THEN 1 ELSE 0 END) AS dauCerradosSemana20,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 20 THEN 1 ELSE 0 END) AS dauCerradosSemana21,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 21 THEN 1 ELSE 0 END) AS dauCerradosSemana22,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 22 THEN 1 ELSE 0 END) AS dauCerradosSemana23,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 23 THEN 1 ELSE 0 END) AS dauCerradosSemana24,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 24 THEN 1 ELSE 0 END) AS dauCerradosSemana25,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 25 THEN 1 ELSE 0 END) AS dauCerradosSemana26,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 26 THEN 1 ELSE 0 END) AS dauCerradosSemana27,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 27 THEN 1 ELSE 0 END) AS dauCerradosSemana28,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 28 THEN 1 ELSE 0 END) AS dauCerradosSemana29,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 29 THEN 1 ELSE 0 END) AS dauCerradosSemana30,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 30 THEN 1 ELSE 0 END) AS dauCerradosSemana31,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 31 THEN 1 ELSE 0 END) AS dauCerradosSemana32,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 32 THEN 1 ELSE 0 END) AS dauCerradosSemana33,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 33 THEN 1 ELSE 0 END) AS dauCerradosSemana34,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 34 THEN 1 ELSE 0 END) AS dauCerradosSemana35,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 35 THEN 1 ELSE 0 END) AS dauCerradosSemana36,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 36 THEN 1 ELSE 0 END) AS dauCerradosSemana37,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 37 THEN 1 ELSE 0 END) AS dauCerradosSemana38,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 38 THEN 1 ELSE 0 END) AS dauCerradosSemana39,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 39 THEN 1 ELSE 0 END) AS dauCerradosSemana40,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 40 THEN 1 ELSE 0 END) AS dauCerradosSemana41,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 41 THEN 1 ELSE 0 END) AS dauCerradosSemana42,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 42 THEN 1 ELSE 0 END) AS dauCerradosSemana43,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 43 THEN 1 ELSE 0 END) AS dauCerradosSemana44,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 44 THEN 1 ELSE 0 END) AS dauCerradosSemana45,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 45 THEN 1 ELSE 0 END) AS dauCerradosSemana46,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 46 THEN 1 ELSE 0 END) AS dauCerradosSemana47,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 47 THEN 1 ELSE 0 END) AS dauCerradosSemana48,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 48 THEN 1 ELSE 0 END) AS dauCerradosSemana49,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 49 THEN 1 ELSE 0 END) AS dauCerradosSemana50,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 50 THEN 1 ELSE 0 END) AS dauCerradosSemana51,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 51 THEN 1 ELSE 0 END) AS dauCerradosSemana52
					FROM
						dau.dau
					FORCE INDEX
						(reporteSemanasEpidemiologicas_TotalDAU)
					WHERE
						dau.dau.est_id = 5
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_cierre_fecha_final BETWEEN '{$anioResumen}-01-01 00:00:00' AND '{$anioResumen}-12-31 23:59:59'
					AND
						dau.dau.dau_cierre_fecha_final IS NOT NULL
					GROUP BY
						dau.dau.dau_atencion
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte DAU's cerrados por semanas<br>");

			return $datos;

		}



		function obtenerDauEnfermedadesEpidemiologicasEnSemanas ( $objCon, $anioResumen ) {

			$sql = "SELECT
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 0  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana1,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 1  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana2,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 2  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana3,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 3  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana4,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 4  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana5,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 5  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana6,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 6  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana7,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 7  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana8,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 8  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana9,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 9  THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana10,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 10 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana11,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 11 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana12,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 12 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana13,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 13 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana14,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 14 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana15,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 15 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana16,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 16 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana17,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 17 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana18,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 18 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana19,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 19 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana20,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 20 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana21,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 21 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana22,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 22 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana23,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 23 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana24,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 24 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana25,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 25 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana26,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 26 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana27,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 27 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana28,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 28 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana29,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 29 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana30,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 30 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana31,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 31 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana32,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 32 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana33,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 33 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana34,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 34 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana35,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 35 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana36,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 36 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana37,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 37 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana38,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 38 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana39,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 39 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana40,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 40 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana41,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 41 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana42,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 42 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana43,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 43 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana44,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 44 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana45,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 45 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana46,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 46 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana47,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 47 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana48,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 48 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana49,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 49 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana50,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 50 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana51,
						SUM(CASE WHEN WEEK(dau.dau.dau_cierre_fecha_final, 1) = 51 THEN 1 ELSE 0 END) AS dauCerradosEnfermedadesRespiratoriasSemana52
					FROM
						dau.dau
					FORCE INDEX
						(reporteSemanasEpidemiologicas_TotalDAU)
					WHERE
						dau.dau.est_id = 5
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_cierre_fecha_final BETWEEN '{$anioResumen}-01-01 00:00:00' AND '{$anioResumen}-12-31 23:59:59'
					AND
						dau.dau.dau_cierre_fecha_final IS NOT NULL
					AND
						(
						dau.dau.dau_cierre_cie10 BETWEEN 'J200' AND  'J219'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J120' AND  'J181'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J111' AND  'J111'
						OR
						dau.dau.dau_cierre_cie10 BETWEEN 'J000' AND  'J99Z'
						)
					GROUP BY
						dau.dau.dau_atencion
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte DAU's enfermedades epidemiológicas por semanas<br>");

			return $datos;

		}



		function numeroAtencionesPorMedico ( $objCon, $parametros ) {

			$sql = "SELECT
						paciente.paciente.sexo AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						paciente.paciente.sexo IN ('M', 'F')
					ORDER BY
						paciente.paciente.sexo DESC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function numeroAtencionesPorGinecologo ( $objCon, $parametros ) {

			$sql = "SELECT
						paciente.paciente.sexo AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						dau.dau.dau_atencion = 3
					AND
						paciente.paciente.sexo = 'F'
					AND
						dau.dau.dau_medico_involucrado_ginecologia = 'S'
					ORDER BY
						paciente.paciente.sexo ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function numeroAtencionesPorMatrona ( $objCon, $parametros ) {

			$sql = "SELECT
						paciente.paciente.sexo AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						dau.dau.dau_indicacion_egreso_fecha BETWEEN '{$parametros['fechaAnterior']}' AND '{$parametros['fechaActual']}'
					AND
						dau.dau.est_id IN (4, 5)
					AND
						dau.dau.dau_atencion = 3
					AND
						paciente.paciente.sexo = 'F'
					AND
						dau.dau.dau_medico_involucrado_ginecologia = 'N'
					ORDER BY
						paciente.paciente.sexo ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte Diario DAU RCE<br>");

			return $datos;

		}



		function reporteREM08SeccionA1AdultoPediatra ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$condicion = " AND dau.dau.est_id = 5
						   AND dau.dau.dau_cierre_cie10 IS NOT NULL";

			if ( ! empty($parametros['demandaUrgencia']) && ! is_null($parametros['demandaUrgencia']) ) {

				$condicion = " AND dau.dau.est_id IN (5, 7) ";

			}

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						dau.consultorios.tiposRed AS tipoRedEstablecimiento
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					LEFT JOIN
						dau.paciente_derivado ON dau.paciente_derivado.idDau = dau.dau.dau_id
					LEFT JOIN
						dau.consultorios ON dau.consultorios.con_id = dau.paciente_derivado.idEstablecimientoRedSalud
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					";

			$sql .= $condicion;

			$sql .= "
					AND
						dau.dau.dau_atencion IN (1, 2)
					ORDER BY
						edadPaciente ASC, paciente.paciente.sexo DESC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionA1AdultoPediatraCategorizadosAnulados ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						COUNT(dau.dau.dau_id) AS totalDemandaCategorizadosAnulados
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 6
					AND
						dau.dau.dau_categorizacion IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionA1GinecoObstetra ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$condicion = " AND dau.dau.est_id = 5
						   AND dau.dau.dau_cierre_cie10 IS NOT NULL";

			if ( ! empty($parametros['demandaUrgencia']) && ! is_null($parametros['demandaUrgencia']) ) {

				$condicion = " AND dau.dau.est_id IN (5, 7) ";

			}

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						dau.consultorios.tiposRed AS tipoRedEstablecimiento
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					LEFT JOIN
						dau.paciente_derivado ON dau.paciente_derivado.idDau = dau.dau.dau_id
					LEFT JOIN
						dau.consultorios ON dau.consultorios.con_id = dau.paciente_derivado.idEstablecimientoRedSalud
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59' ";

			$sql .= $condicion;

			$sql .= "
					AND
						dau.dau.dau_atencion = 3
					AND
						dau.dau.dau_medico_involucrado_ginecologia = 'S'
					ORDER BY
						paciente.paciente.sexo, edadPaciente  ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionA1Matrona ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion = 3
					AND
						dau.dau.dau_medico_involucrado_ginecologia = 'N'
					ORDER BY
						paciente.paciente.sexo, edadPaciente  ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionA1Odontologo ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$condicion = " AND dau.dau.est_id = 5 ";

			if ( ! empty($parametros['demandaUrgencia']) && ! is_null($parametros['demandaUrgencia']) ) {

				$condicion = " AND dau.dau.est_id IN (5, 7) ";

			}

			$sql = "SELECT
						pabnet.solicitudes.dau_id,
						pabnet.tabla_quirurgica.tq_estado,
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						dau.consultorios.tiposRed AS tipoRedEstablecimiento
					FROM
						parametros_clinicos.profesional
					INNER JOIN
						pabnet.solicitudes_has_medicos ON parametros_clinicos.profesional.PROcodigo = pabnet.solicitudes_has_medicos.sol_PROcodigo
					INNER JOIN
						pabnet.solicitudes ON pabnet.solicitudes_has_medicos.sol_id = pabnet.solicitudes.sol_id
					INNER JOIN
						paciente.paciente ON pabnet.solicitudes.sol_id_paciente = paciente.paciente.id
					INNER JOIN
						pabnet.tabla_quirurgica ON pabnet.tabla_quirurgica.sol_id = pabnet.solicitudes.sol_id
					INNER JOIN
						dau.dau ON pabnet.solicitudes.dau_id = dau.dau.dau_id
					LEFT JOIN
						dau.paciente_derivado ON dau.paciente_derivado.idDau = dau.dau.dau_id
					LEFT JOIN
						dau.consultorios ON dau.consultorios.con_id = dau.paciente_derivado.idEstablecimientoRedSalud
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						parametros_clinicos.profesional.TIPROcodigo = 6
					AND
						pabnet.solicitudes.dau_id IS NOT NULL
					AND
						pabnet.tabla_quirurgica.tq_estado IN (4,6) ";

			$sql .= $condicion;

			$sql .= "
					AND
						dau.dau.dau_atencion IN (1, 2)
					GROUP BY
						pabnet.solicitudes.dau_id
					ORDER BY
						paciente.paciente.sexo, edadPaciente  ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionA1OdontologoCategorizadosAnulados ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						count(pabnet.solicitudes.dau_id) AS totalDemandaCategorizadosAnulados
					FROM
						parametros_clinicos.profesional
					INNER JOIN
						pabnet.solicitudes_has_medicos ON parametros_clinicos.profesional.PROcodigo = pabnet.solicitudes_has_medicos.sol_PROcodigo
					INNER JOIN
						pabnet.solicitudes ON pabnet.solicitudes_has_medicos.sol_id = pabnet.solicitudes.sol_id
					INNER JOIN
						paciente.paciente ON pabnet.solicitudes.sol_id_paciente = paciente.paciente.id
					INNER JOIN
						pabnet.tabla_quirurgica ON pabnet.tabla_quirurgica.sol_id = pabnet.solicitudes.sol_id
					INNER JOIN
						dau.dau ON pabnet.solicitudes.dau_id = dau.dau.dau_id
					LEFT JOIN
						dau.paciente_derivado ON dau.paciente_derivado.idDau = dau.dau.dau_id
					LEFT JOIN
						dau.consultorios ON dau.consultorios.con_id = dau.paciente_derivado.idEstablecimientoRedSalud
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						parametros_clinicos.profesional.TIPROcodigo = 6
					AND
						pabnet.solicitudes.dau_id IS NOT NULL
					AND
						pabnet.tabla_quirurgica.tq_estado IN (4,6)
					AND
						dau.dau.est_id = 6
					AND
						dau.dau.dau_categorizacion IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionB ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_categorizacion AS categorizacionPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion, dau_categorizacion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_categorizacion IN ('ESI-1', 'ESI-2', 'ESI-3', 'ESI-4', 'ESI-5')
					ORDER BY
						dau.dau.dau_categorizacion, paciente.paciente.sexo DESC, edadPaciente   ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionCPsiquiatrica ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						SUM(IF(dau.dau.dau_paciente_edad BETWEEN 0 AND 17,1, 0)) AS totalPacientes
					FROM
						dau.dau
					FORCE INDEX
						( fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion )
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.dau_tiene_indicacion ON dau.dau_tiene_indicacion.dau_id = dau.dau.dau_id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN ( 1, 2 )
					AND
						dau.dau_tiene_indicacion.ind_egr_id = 4
					AND
						dau.dau_tiene_indicacion.des_id = 2
					AND
						dau.dau_tiene_indicacion.dau_ind_servicio = 12
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionCNeurocirugia ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						COUNT(dau.dau.dau_id) AS totalPacientes
					FROM
						dau.dau
					FORCE
						INDEX($indiceFecha, fk_reference_4, dau_atencion)
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN ( 1, 2 )
					AND
						dau.dau.dau_hospitalizacion_otros_servicios = 'Neurocirugía'
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionDPacientesHospitalizacion ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						TIME_TO_SEC(TIMEDIFF(dau.dau.dau_cierre_fecha_final, dau.dau.dau_indicacion_egreso_fecha)) AS tiempoEspera,
						dau.tipo_post_indicacion_egreso.descripcionPostIndicacionEgreso AS tipoHospitalizacion
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					LEFT JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.idDau = dau.dau.dau_id
					LEFT JOIN
						dau.tipo_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.tipoPostIndicacionEgreso = dau.tipo_post_indicacion_egreso.idPostIndicacionEgreso
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_indicacion_egreso = 4
					ORDER BY
						paciente.paciente.sexo DESC, edadPaciente ASC, tiempoEspera   ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionDPacientesTipoHospitalizacion ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						dau.tipo_post_indicacion_egreso.descripcionPostIndicacionEgreso AS tipoHospitalizacion
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.idDau = dau.dau.dau_id
					INNER JOIN
						dau.tipo_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.tipoPostIndicacionEgreso = dau.tipo_post_indicacion_egreso.idPostIndicacionEgreso
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_indicacion_egreso = 4
					GROUP BY
						dau.dau.dau_id

					UNION ALL

					SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						'Rechazo Hospitalización' AS tipoHospitalizacion
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_indicacion_egreso = 5
					GROUP BY
						dau.dau.dau_id

					UNION ALL

					SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						'Pabellón' AS tipoHospitalizacion
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_indicacion_egreso = 4
					AND
						dau.dau.dau_hospitalizacion_otros_servicios = 'Pabellón'
					GROUP BY
						dau.dau.dau_id


					ORDER BY
						sexoPaciente DESC, edadPaciente ASC, tipoHospitalizacion ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionFFallecidoProcesoAtencion ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						dau.dau.dau_indicacion_egreso = 6
					ORDER BY
						paciente.paciente.sexo DESC, edadPaciente ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionFFallecidoEsperaCama ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.idDau = dau.dau.dau_id
					INNER JOIN
						dau.tipo_post_indicacion_egreso ON dau.dau_post_indicacion_egreso.tipoPostIndicacionEgreso = dau.tipo_post_indicacion_egreso.idPostIndicacionEgreso
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						dau.dau.dau_indicacion_egreso = 4
					AND
						dau.tipo_post_indicacion_egreso.idPostIndicacionEgreso = 5
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionG ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						rce.registro_violencia.idTipoViolencia,
						rce.tipo_violencia.descripcionTipoViolencia,
						rce.registro_violencia.idTipoAgresor,
						rce.registro_violencia.idTipoLesionVictima,
						rce.registro_violencia.victimaEmbarazada,
						IF(paciente.paciente.etnia <> 0 AND paciente.paciente.etnia <> 1, 'S', 'N') AS victimaPuebloOriginario,
						IF(paciente.paciente.nacionalidad IS NOT NULL AND (paciente.paciente.nacionalidad <> 'cl' AND paciente.paciente.nacionalidad <> 'CL' AND paciente.paciente.nacionalidad <> 'NOI' AND paciente.paciente.nacionalidad <> 'NOINF'), 'S', 'N') AS victimaMigrante
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						rce.registro_violencia ON dau.dau.dau_id = rce.registro_violencia.idDau
					INNER JOIN
						rce.tipo_violencia ON rce.registro_violencia.idTipoViolencia = rce.tipo_violencia.idTipoViolencia
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						rce.registro_violencia.idTipoViolencia IN (2, 4)
					ORDER BY
						rce.registro_violencia.idTipoViolencia DESC, paciente.paciente.sexo DESC, edadPaciente ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionL ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						dau.dau.dau_forma_llegada,
						dau.medio_llegada.med_descripcion,
						dau.medio_llegada.formaMedioLlegada,
						dau.medio_llegada.filtroSamu,
						dau.medio_llegada.avanzadaOBasica,
						dau.medio_llegada.filtroEnrutado,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.medio_llegada ON dau.dau.dau_forma_llegada = dau.medio_llegada.med_id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						dau.dau.dau_id NOT IN (SELECT dau.paciente_derivado.idDau FROM dau.paciente_derivado)
					ORDER BY
						dau.dau.dau_paciente_critico ASC, dau.dau.dau_forma_llegada ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionM ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$sql = "SELECT
						dau.medio_llegada.med_descripcion,
						dau.dau.dau_paciente_critico AS pacienteCritico,
						dau.medio_llegada.formaMedioLlegada,
						dau.medio_llegada.filtroSamu,
						dau.medio_llegada.filtroEnrutado,
						paciente.paciente.prevision AS previsionPaciente
					FROM
						dau.dau
					INNER JOIN
						dau.paciente_derivado ON dau.dau.dau_id = dau.paciente_derivado.idDau
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						dau.medio_llegada ON dau.dau.dau_forma_llegada = dau.medio_llegada.med_id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						dau.medio_llegada.filtroSamu IS NOT NULL
					ORDER BY
						dau.medio_llegada.med_descripcion ASC, dau.medio_llegada.formaMedioLlegada ASC, dau.medio_llegada.filtroSamu DESC, dau.medio_llegada.filtroEnrutado DESC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionO ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						rce.tipo_sospecha_penetracion.descripcionSospechaPenetracion as tipoPenetracion,
						rce.registro_violencia.victimaEmbarazada AS gestante,
						dau.dau.dau_cierre_entrega_postinor AS anticoncepcion,
						dau.dau.dau_cierre_hepatitisB AS hepatitisB,
						rce.registro_violencia.idTipoProfilaxis,
						rce.registro_violencia.idTipoAgresor
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						rce.registro_violencia ON rce.registro_violencia.idDau = dau.dau.dau_id
					INNER JOIN
						rce.tipo_sospecha_penetracion ON rce.tipo_sospecha_penetracion.idTipoSospechaPenetracion = rce.registro_violencia.idTipoSospechaPenetracion
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						rce.registro_violencia.idTipoViolencia = 3
					ORDER BY
						paciente.paciente.sexo DESC, edadPaciente ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionP ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						CASE
							WHEN SUM(IF(rce.registro_violencia.usuarioRegistraViolencia IN ('jcontreras', 'czenteno'), 1, 0)) IS NULL THEN 0
							ELSE SUM(IF(rce.registro_violencia.usuarioRegistraViolencia IN ('jcontreras', 'czenteno'), 1, 0))
						END AS medicosForenses,
						CASE
							WHEN SUM(IF(rce.registro_violencia.usuarioRegistraViolencia NOT IN ('jcontreras', 'czenteno'), 1, 0)) IS NULL THEN 0
							ELSE SUM(IF(rce.registro_violencia.usuarioRegistraViolencia NOT IN ('jcontreras', 'czenteno'), 1, 0))
						END AS otrosMedicos
					FROM
						dau.dau
					FORCE INDEX
						( $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						rce.registro_violencia ON rce.registro_violencia.idDau = dau.dau.dau_id
					INNER JOIN
						rce.tipo_sospecha_penetracion ON rce.tipo_sospecha_penetracion.idTipoSospechaPenetracion = rce.registro_violencia.idTipoSospechaPenetracion
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						rce.registro_violencia.idTipoViolencia = 3
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionP_2 ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						SUM(IF(rce.registro_violencia.peritoSexual = 'Turno', 1, 0)) AS peritoSexualTurno,
						SUM(IF(rce.registro_violencia.peritoSexual = 'Llamado', 1, 0)) AS peritoSexualLlamado,
						SUM(IF(rce.registro_violencia.peritoSexual = 'Otros Médicos', 1, 0)) AS peritoSexualOtrosMedicos
					FROM
						dau.dau
					FORCE INDEX
						( $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						rce.registro_violencia ON rce.registro_violencia.idDau = dau.dau.dau_id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						rce.registro_violencia.idTipoViolencia = 3
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos[0];

		}



		function reporteREM08SeccionQ ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						rce.registro_violencia ON rce.registro_violencia.idDau = dau.dau.dau_id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					AND
						rce.registro_violencia.idTipoViolencia = 1
					ORDER BY
						paciente.paciente.sexo DESC, edadPaciente ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function reporteREM08SeccionR ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$indiceFecha = ( date("Y", strtotime($parametros['fechaAnterior'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						dau.dau.dau_mordedura AS animalMordedura,
						dau.dau.dau_tipo_mordedura AS tipoMordedura
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['fechaAnterior']} 00:00:00' AND '{$parametros['fechaActual']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2, 3)
					ORDER BY
						paciente.paciente.sexo DESC, edadPaciente ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el Reporte REM 08<br>");

			return $datos;

		}



		function pacientesPabellon ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

            $indiceFecha = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'cierreFechaFinal' : 'dau_admision_fecha';

			$sql = "SELECT
						IF(paciente.paciente.sexo <> 'M' AND paciente.paciente.sexo <> 'F', 'F', paciente.paciente.sexo) AS sexoPaciente,
						dau.dau.dau_paciente_edad AS edadPaciente,
						paciente.paciente.prevision AS previsionPaciente,
						'Pabellón' AS tipoHospitalizacion
					FROM
						dau.dau
					FORCE INDEX
						(fk_reference_13, $indiceFecha, fk_reference_4, dau_atencion)
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					WHERE
						$fechaBusqueda BETWEEN '{$parametros['frm_inicio']} 00:00:00' AND '{$parametros['frm_fin']} 23:59:59'
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						dau.dau.dau_atencion IN (1, 2)
					AND
						dau.dau.dau_indicacion_egreso = 4
					AND
						dau.dau.dau_hospitalizacion_otros_servicios = 'Pabellón'
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en obtener total de pacientes en pabellón<br>");

			return $datos;

		}



		function pacientesSospechasCoronavirus ($objCon, $parametros, $tipoAtencion ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$condicion = '';

			if ( $tipoAtencion == 'hospitalizaciones' ) {

				$condicion .= ' AND dau.dau.dau_indicacion_egreso = 4
				                AND	( ISNULL(dau_post_indicacion_egreso.tipoPostIndicacionEgreso) OR dau_post_indicacion_egreso.tipoPostIndicacionEgreso = 7 )';

			}

			$sql = "SELECT
						dau.dau_cierre_cie10,
						dau.dau_paciente_edad AS edadPaciente,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision,
						dau.est_id,
						atencion.ate_descripcion
				  	FROM
						dau.dau
				  	INNER JOIN
					  	dau.atencion ON atencion.ate_id = dau.dau_atencion
					LEFT JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_id = dau_post_indicacion_egreso.idDau
				  	WHERE
					  	DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id = 5
					AND
						atencion.ate_id IN (1,2)
					AND
						(
							dau.dau_cierre_cie10 = 'Z290'
						OR
							dau.dau_cierre_cie10 = 'Z208'
						OR
							dau.dau_cierre_cie10 = 'U072'
						)
					AND
						dau.dau_cierre_cie10 IS NOT NULL
					";

			$sql .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte sospecha coronavirus<br>");

			return $datos;

		}



		function pacientesCoronavirus ($objCon, $parametros, $tipoAtencion ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$condicion = '';

			if ( $tipoAtencion == 'hospitalizaciones' ) {

				$condicion .= ' AND dau.dau.dau_indicacion_egreso = 4
				                AND	( ISNULL(dau_post_indicacion_egreso.tipoPostIndicacionEgreso) OR dau_post_indicacion_egreso.tipoPostIndicacionEgreso = 7 )';

			}

			$sql = "SELECT
						dau.dau_cierre_cie10,
						dau.dau_paciente_edad AS edadPaciente,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision,
						dau.est_id,
						atencion.ate_descripcion
				  	FROM
						dau.dau
				  	INNER JOIN
					  	dau.atencion ON atencion.ate_id = dau.dau_atencion
					LEFT JOIN
						dau.dau_post_indicacion_egreso ON dau.dau_id = dau_post_indicacion_egreso.idDau
				  	WHERE
					  	DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						dau.est_id = 5
					AND
						atencion.ate_id IN (1,2)
					AND
						dau.dau_cierre_cie10 = 'U071'
					AND
						dau.dau_cierre_cie10 IS NOT NULL
					";

			$sql .= $condicion;

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte coronavirus<br>");

			return $datos;

		}



		function totalDemanda ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$sql = "SELECT
						dau.dau_paciente_edad AS edadPaciente,
						DATE_FORMAT(dau.dau_admision_fecha, '%Y-%m-%d') AS fechaAdmision
				  	FROM
						dau.dau
				 	INNER JOIN
					  	dau.atencion ON atencion.ate_id = dau.dau_atencion
				  	WHERE
					  	DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						atencion.ate_id IN (1,2)
					AND
						dau.dau.est_id IN (5, 7)
					ORDER BY
						dau.dau.dau_paciente_edad
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte total demanda<br>");

			return $datos;

		}



		function totalDemandaCategorizadosAnulados ( $objCon, $parametros ) {

			$fechaBusqueda = ( date("Y", strtotime($parametros['frm_inicio'])) > 2020 ) ? 'dau.dau.dau_cierre_fecha_final' : 'dau.dau.dau_admision_fecha';

			$sql = "SELECT
						COUNT(dau.dau.dau_id) AS totalDemandaCategorizadosAnulados
				  	FROM
						dau.dau
				 	INNER JOIN
					  	dau.atencion ON atencion.ate_id = dau.dau_atencion
				  	WHERE
					  	DATE_FORMAT($fechaBusqueda, '%Y-%m-%d') BETWEEN '{$parametros['frm_inicio']}' AND '{$parametros['frm_fin']}'
					AND
						atencion.ate_id IN (1,2)
					AND
						dau.dau.est_id = 6
					AND
						dau.dau.dau_categorizacion IS NOT NULL
					ORDER BY
						dau.dau.dau_paciente_edad
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en el reporte total demanda<br>");

			return $datos[0];

		}



		function pdfPacientesEnfermedadesRespiratoriasTurnos ( $objCon, $parametros, $tipoTurno ) {

			$condicion = "";

			$condicion .= " AND dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnteriorTurnoDia']} 08:00:00' AND '{$parametros['fechaAnteriorTurnoDia']} 19:59:59' ";

			if ( $tipoTurno == 'Noche' ) {

				$condicion = " AND dau.dau.dau_admision_fecha BETWEEN '{$parametros['fechaAnteriorTurnoDia']} 20:00:00' AND '{$parametros['fechaAnteriorTurnoNoche']} 07:59:59' ";

			}

			$sql = "SELECT
						formulario.formulario_interno.form_int_dau as 'Id DAU',
						CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS 'Nombre Paciente',
						CONCAT(paciente.paciente.rut,'-',paciente.paciente.dv) AS 'RUN Paciente',
						formulario.formulario_interno.form_int_celular AS 'Celular Paciente',
						formulario.formulario_interno.form_int_telefono AS 'Telefono Paciente',
						paciente.paciente.direccion AS 'Dirección Paciente',
						CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS 'CIE10',
						formulario.destino.des_descripcion 'Descripción Destino',
						dau.dau.dau_paciente_edad 'Edad Paciente'
					FROM
						formulario.formulario_interno
					LEFT JOIN
						dau.dau ON formulario.formulario_interno.form_int_dau = dau.dau.dau_id
					LEFT JOIN
						paciente.paciente ON formulario.formulario_interno.form_int_idpaciente = paciente.paciente.id
					LEFT JOIN
						cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					LEFT JOIN
						formulario.destino ON formulario.formulario_interno.form_int_destino = formulario.destino.des_id
					WHERE
						dau.dau.est_id = 5
 					";

			$sql .= $condicion;

			$sql .= " ORDER BY dau.dau.dau_id ASC ";

			$datos = $objCon->consultaSQL($sql,"<br>Error en buscar cantidad de pacientes<br>");

			return $datos;

		}



		function obtenerInformacionDAUSContingencia ( $objCon ) {

			$sql = "SELECT
						dau.dau.dau_id AS 'id_dau',
						dau.dau.id_paciente AS 'id_paciente',
						paciente.paciente.rut,
						paciente.paciente.rut_extranjero,
						CASE
							WHEN
								(paciente.paciente.rut <> 0
								AND
								paciente.paciente.rut IS NOT NULL
								AND
								paciente.paciente.rut <> '')
							THEN 1

							WHEN
								(paciente.paciente.rut_extranjero <> 0
								AND
								paciente.paciente.rut_extranjero IS NOT NULL
								AND
								paciente.paciente.rut_extranjero <> '')
							THEN 9

							WHEN
								(paciente.paciente.rut_extranjero = 0
								OR
								paciente.paciente.rut_extranjero = NULL
								OR paciente.paciente.rut_extranjero = '')
								AND
								(paciente.paciente.rut = 0
								OR
								paciente.paciente.rut IS NULL
								OR
								paciente.paciente.rut = '')
							THEN 4
						END
						AS 'tipo_identificacion',
						DATE_FORMAT(paciente.paciente.fechanac, '%d-%m-%Y') AS 'fecha_nacimiento',
						paciente.paciente.sexo AS sexoPaciente,
						CASE
							WHEN
								paciente.paciente.sexo = 'M'
							THEN '01'

							WHEN
								paciente.paciente.sexo = 'F'
							THEN '02'

							WHEN
									(paciente.paciente.sexo <> 'M'
								AND
									paciente.paciente.sexo <> 'F')
							THEN '99'
						END
						AS 'sexo',
						dau.dau.dau_atencion AS 'dau_atencion',
						dau.dau.dau_admision_fecha,
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%d-%m-%Y') AS 'fecha_adm',
						DATE_FORMAT(dau.dau.dau_admision_fecha, '%H:%i:%s') AS 'hora_adm',
						dau.dau.dau_motivo_consulta,
						dau.motivo_consulta.mot_descripcion,
						dau.dau.dau_tipo_accidente,
						dau.sub_motivo_consulta.sub_mot_descripcion,
						dau.dau.dau_manifestaciones,
						CASE
							WHEN
								dau.dau.dau_manifestaciones = 'S'
							THEN '08'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_tipo_accidente = 2)
							THEN '11'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_tipo_accidente = 1)
							THEN '12'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_tipo_accidente = 4)
							THEN '03'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_motivo_consulta = 3)
							THEN '04'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_tipo_accidente = 3)
							THEN '05'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_agresion_vif = 'S')
							THEN '06'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									dau.dau.dau_tipo_accidente = 5)
							THEN '07'

							WHEN
								(
									(dau.dau.dau_manifestaciones = 'N'
									OR
									dau.dau.dau_manifestaciones IS NULL
									OR
									dau.dau.dau_manifestaciones = ''
									)
								AND
									(dau.dau.dau_motivo_consulta = 2
										OR
										dau.dau.dau_motivo_consulta = 4
										OR
										dau.dau.dau_motivo_consulta = 5)
								)
							THEN '07'
						END
						AS 'tipo_accidente',
						dau.dau.dau_inicio_atencion_fecha,
						DATE_FORMAT(dau.dau.dau_inicio_atencion_fecha, '%d-%m-%Y') AS 'fecha_atencion',
						DATE_FORMAT(dau.dau.dau_inicio_atencion_fecha, '%H:%i:%s') AS 'hora_atencion',
						acceso.usuario.rutusuario AS 'run_responsable',
						digitoVerificador(acceso.usuario.rutusuario) AS 'dv_responsable',
						dau.dau.dau_medico_involucrado_ginecologia,
						CASE
							WHEN
							(dau.dau.dau_atencion = 3
							AND
							dau.dau.dau_medico_involucrado_ginecologia = 'N')
							THEN '05'
							ELSE '01'
						END AS 'titulo_profesional'
					FROM
						dau.dau
					LEFT JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						acceso.usuario ON dau.dau.dau_inicio_atencion_usuario = acceso.usuario.idusuario
					LEFT JOIN
						dau.motivo_consulta ON dau.dau.dau_motivo_consulta = dau.motivo_consulta.mot_id
					LEFT JOIN
						dau.sub_motivo_consulta ON dau.dau.dau_tipo_accidente = dau.sub_motivo_consulta.sub_mot_id
					WHERE
						dau.dau.dau_cierre_fecha_final BETWEEN DATE_SUB(NOW(),INTERVAL 1 HOUR)  AND NOW()
					AND
						dau.dau.est_id = 5
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL

					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en buscar cantidad de pacientes<br>");

			return $datos;

		}



		function obtenerReporteEnfermedadesEpidemiologicas ( $objCon, $parametros ) {

			$sql = "SELECT
						dau.dau.dau_id AS 'idDau',
						CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidopat,' ',paciente.paciente.apellidomat) AS 'nombre',
						
						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,

						IF(paciente.paciente.rut = 0 OR paciente.paciente.rut = NULL OR paciente.paciente.rut = '', paciente.paciente.rut_extranjero, CONCAT(paciente.paciente.rut,'-',paciente.paciente.dv)) AS 'run',
						dau.dau.dau_admision_fecha AS 'fechaAdmision',
						dau.dau.dau_cierre_fecha_final AS 'fechaCierre',
						dau.indicacion_egreso.ind_egr_descripcion AS 'indicacionEgreso',
						dau.destino.des_nombre AS 'destino',
						CONCAT(cie10.cie10.codigoCIE,' - ',cie10.cie10.nombreCIE) AS 'CIE10',
						rce.registroclinico.regCIE10Abierto AS 'hipotesisFinal'
					FROM
						dau.dau
					INNER JOIN
						paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						cie10.cie10 ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					INNER JOIN
						dau.dau_tiene_indicacion ON dau.dau.dau_id = dau.dau_tiene_indicacion.dau_id
					INNER JOIN
						dau.indicacion_egreso ON dau.dau_tiene_indicacion.ind_egr_id = dau.indicacion_egreso.ind_egr_id
					LEFT JOIN
						dau.destino ON dau.dau_tiene_indicacion.des_id = dau.destino.des_id
					INNER JOIN
						rce.registroclinico ON dau.dau.dau_id = rce.registroclinico.dau_id
					WHERE
						dau.dau.est_id = 5
					AND
						dau.dau_tiene_indicacion.est_id = 21
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						(
							dau.dau.dau_cierre_cie10 IN ('B069', 'B059', 'A809')
							OR
							dau.dau.dau_cierre_cie10 BETWEEN 'J00X' AND 'J22X'
						)
					AND
						DATE(dau.dau.dau_cierre_fecha_final) BETWEEN '{$parametros['fechaInicio']}' AND '{$parametros['fechaTermino']}'
					ORDER BY
						dau.dau.dau_id ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en buscar información relacionada a reporte enfermedades epidemiológicas<br>");

			return $datos;

		}



		function obtenerReporteEndovenosoCat4 ( $objCon, $parametros ) {

			$sql = "SELECT
						dau.dau.dau_id AS 'Id Dau',
						dau.dau.dau_admision_fecha AS 'Fecha Admisión',
						dau.dau.id_paciente AS 'Id Paciente',
						CASE
							WHEN dau.dau.dau_atencion = 1 THEN 'Adulto'
							WHEN dau.dau.dau_atencion = 2 THEN 'Ped.'
							WHEN dau.dau.dau_atencion = 3 THEN 'Gine.'
						END AS 'Tipo Atención',
						CONCAT(paciente.paciente.nombres,' ',paciente.paciente.apellidomat,' ',paciente.paciente.apellidomat) AS 'Nombre Paciente',

						paciente.paciente.transexual,
						paciente.paciente.nombreSocial,
						
						CASE
							WHEN paciente.paciente.rut IS NOT NULL AND paciente.paciente.rut <> '' AND paciente.paciente.rut <> 0 THEN CONCAT(paciente.paciente.rut,'-',paciente.paciente.dv)
							WHEN paciente.paciente.rut IS NULL OR paciente.paciente.rut = '' OR paciente.paciente.rut = 0 THEN paciente.paciente.rut_extranjero
						END AS 'RUT Paciente',
						dau.dau.dau_paciente_edad AS 'Edad Paciente',
						dau.dau.dau_categorizacion AS 'Categorización Paciente',
						rce.solicitud_indicaciones.sol_ind_descripcion AS 'Tratamiento',
						rce.solicitud_indicaciones.sol_ind_fechaInserta AS 'Fecha Solicitud Indicación',
						rce.solicitud_indicaciones.sol_ind_usuarioInserta AS 'Usuario Inserta Solicitud',
						rce.solicitud_indicaciones.sol_ind_fechaIniciaIndicacion AS 'Fecha Inicio Indicación',
						rce.solicitud_indicaciones.sol_ind_usuarioIniciaIndicacion AS 'Usuario Inicia Indicación',
						rce.solicitud_indicaciones.sol_ind_fechaAplica AS 'Fecha Aplica Indicación',
						rce.solicitud_indicaciones.sol_ind_usuarioAplica AS 'Usuario Aplica Indicación',
						CONCAT(dau.dau.dau_cierre_cie10,' - ',cie10.cie10.nombreCIE) AS 'CIE10'
					FROM
						dau.dau FORCE INDEX (dau_admision_fecha)
					INNER JOIN
						paciente.paciente FORCE INDEX FOR JOIN (idx_idpaciente) ON dau.dau.id_paciente = paciente.paciente.id
					INNER JOIN
						cie10.cie10 FORCE INDEX FOR JOIN (index1) ON dau.dau.dau_cierre_cie10 = cie10.cie10.codigoCIE
					INNER JOIN
						rce.registroclinico FORCE INDEX FOR JOIN (dau_id) ON dau.dau.dau_id = rce.registroclinico.dau_id
					INNER JOIN
						rce.solicitud_indicaciones FORCE INDEX FOR JOIN (regId) ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
					WHERE
						dau.dau.est_id = 5
					AND
						dau.dau.dau_categorizacion IN ('ESI-4', 'C4')
					AND
						dau.dau.dau_cierre_cie10 IS NOT NULL
					AND
						rce.solicitud_indicaciones.sol_ind_estado = 4
					AND
						rce.solicitud_indicaciones.sol_ind_servicio = 2
					AND
						rce.solicitud_indicaciones.sol_clasificacionTratamiento = 1
					AND
						DATE(dau.dau.dau_admision_fecha) BETWEEN '{$parametros['fechaInicio']}' AND '{$parametros['fechaTermino']}'
					ORDER BY
						dau.dau.dau_admision_fecha ASC
					";

			$datos = $objCon->consultaSQL($sql,"<br>Error en buscar información relacionada a reporte pacientes categorización ESI-4 con indicación de tratamiento endovenoso<br>");

			return $datos;

		}



		function reporteCategorizacionTotalUrgencia($objCon, $parametros) {
			require_once("Util.class.php");

			$objUtil = new Util();
			$condicion = "";

			$sql = "
				SELECT
					DATE(dau.dau.dau_admision_fecha) AS fechaAdmision,
					COUNT(dau.dau.dau_id) AS totalAdmisionados,
					SUM(
						IF(dau.dau.est_id NOT IN(6, 7) AND dau.categorizacion.cat_nivel = 1, 1, 0)
					) AS totalCAT1,
					SUM(
						IF(dau.dau.est_id NOT IN(6, 7) AND dau.categorizacion.cat_nivel = 2, 1, 0)
					) AS totalCAT2,
					SUM(
						IF(dau.dau.est_id NOT IN(6, 7) AND dau.categorizacion.cat_nivel = 3, 1, 0)
					) AS totalCAT3,
					SUM(
						IF(dau.dau.est_id NOT IN(6, 7) AND dau.categorizacion.cat_nivel = 4, 1, 0)
					) AS totalCAT4,
					SUM(
						IF(dau.dau.est_id NOT IN(6, 7) AND dau.categorizacion.cat_nivel = 5, 1, 0)
					) AS totalCAT5,
					SUM(
						IF(dau.dau.est_id IN(6, 7) AND (dau.dau.dau_categorizacion IS NULL OR dau.dau.dau_categorizacion = '') , 1, 0)
					) AS totalNEASinCAT,
					SUM(
						IF(dau.dau.est_id IN(6, 7) AND (dau.dau.dau_categorizacion IS NOT NULL AND dau.dau.dau_categorizacion <> '') , 1, 0)
					) AS totalNEAConCAT
				FROM
					dau.dau USE INDEX(dau_admision_fecha, dau_categorizacion)
				LEFT JOIN
					dau.categorizacion
					ON dau.dau.dau_categorizacion = dau.categorizacion.cat_id
			";

			if (
				$objUtil->existe($parametros["fechaInicio"])
				&& !$objUtil->existe($parametros["fechaFin"])
			){
				$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
				$condicion .= " DATE(dau.dau.dau_admision_fecha) = '{$parametros['fechaInicio']}' ";
			}

			if (
				$objUtil->existe($parametros["fechaInicio"])
				&& $objUtil->existe($parametros["fechaFin"])
			){
				$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
				$condicion .= "
					DATE(dau.dau.dau_admision_fecha)
					BETWEEN '{$parametros['fechaInicio']}'
					AND '{$parametros['fechaFin']}'
				";
			}

			if (
				$objUtil->existe($parametros["tipoAtencion"])
				&& (int)$parametros["tipoAtencion"] !== 0
			) {
				$condicion .= (!$objUtil->existe($condicion)) ? " WHERE " : " AND ";
				$condicion .= " dau.dau.dau_atencion = '{$parametros['tipoAtencion']}' ";
			}

			$condicion .= "
				GROUP BY
					DATE(dau.dau.dau_admision_fecha)
			";

			$sql .= $condicion;
			return $objCon->consultaSQL($sql,"<br>Error en el reporte categorizacionUrgencia<br>");
		}
	}
?>
