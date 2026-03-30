<?php
	class FormularioSeguimiento{

		function actualizarFormulario ( $objCon, $parametros ) {

			$sql = "UPDATE
						formulario.formulario_interno
					SET
						formulario.formulario_interno.form_int_celular         = '{$parametros['form_int_celular']}',
						formulario.formulario_interno.form_int_telefono        = '{$parametros['form_int_telefono']}',
						formulario.formulario_interno.form_int_email           = '{$parametros['form_int_email']}',
						formulario.formulario_interno.form_int_estado          = '{$parametros['form_int_estado']}',
						formulario.formulario_interno.form_int_observacion     = '{$parametros['form_int_observacion']}',
						formulario.formulario_interno.form_int_direccion_pac   = '{$parametros['form_int_direccion_pac']}',
						formulario.formulario_interno.form_int_fecha           = CURDATE(),
						formulario.formulario_interno.form_int_hora            = CURTIME(),
						formulario.formulario_interno.form_int_cantpersonas    = '{$parametros['form_int_cantpersonas']}',
						formulario.formulario_interno.form_int_motivosospecha  = '{$parametros['form_int_motivosospecha']}',
						formulario.formulario_interno.form_int_iniciosintimas  = '{$parametros['form_int_iniciosintimas']}',
						formulario.formulario_interno.form_int_estadoingreso   = '{$parametros['form_int_estadoingreso']}',
						formulario.formulario_interno.form_int_ant_epi         = '{$parametros['form_int_ant_epi']}',
						formulario.formulario_interno.form_int_destino         = '{$parametros['form_int_destino']}',
						formulario.formulario_interno.form_int_cant_int        = '{$parametros['form_int_cant_int']}',
						formulario.formulario_interno.form_int_lugar_trabajo   = '{$parametros['form_int_lugar_trabajo']}',
						formulario.formulario_interno.form_int_pais_residencia = '{$parametros['form_int_pais_residencia']}',
						formulario.formulario_interno.form_int_nacionalidad    = '{$parametros['form_int_nacionalidad']}'
					WHERE
						formulario.formulario_interno.form_int_idpaciente = '{$parametros['form_int_idpaciente']}'
					";

			$objCon->ejecutarSQL($sql, "ERROR AL ACTUALIZAR FORMULARIO INTERNO");

		}



		function guardarTomaMuestra ( $objCon, $parametros ) {

			$sql = "INSERT INTO
						formulario.muestra
						(
							form_int_id,
							tomada_por_muestra,
							fecha_toma_muestra,
							covid_solicita_muestra,
							ifi_solicita_muestra,
							tipo_muestra,
							fecha_ingreso_muestra,
							hora_ingreso_muestra,
							usuario_ingreso_muestra,
							lugar_toma_muestra,
							Broncoalveolar,
							Esputo,
							Aspirado_Traqueal,
							Aspirado_Nasofaringeo,
							torulas_nasofaringeas,
							tejido_pulmonar,
							motivo_nueva_muestra,
							pendiente_muestra,
							pendiente_observacion_muestra,
							id_laboratorio,
							id_reg_hosp_establ_origen,
							estado_muestra,
							muestra_iniciosintomas,
							muestra_lugar_trabajo,
							muestra_cantpersonas,
							muestra_destino,
							muestra_ant_epi,
							muestra_embarazada,
							muestra_estadoingreso,
							id_procedencia_muestra
						)
					VALUES
						(
							'{$parametros['form_id']}',
							'{$parametros['tomada_por_muestra']}',
							'{$parametros['fecha_toma_muestra']}',
							'{$parametros['covid_solicita_muestra']}',
							'{$parametros['ifi_solicita_muestra']}',
							'NO',
							CURDATE(),
							CURTIME(),
							'{$parametros['usuario_ingreso_muestra']}',
							'{$parametros['lugar_toma_muestra']}',
							'{$parametros['Broncoalveolar']}',
							'{$parametros['Esputo']}',
							'{$parametros['Aspirado_Traqueal']}',
							'{$parametros['Aspirado_Nasofaringeo']}',
							'{$parametros['torulas_nasofaringeas']}',
							'{$parametros['tejido_pulmonar']}',
							'NO',
							'NO',
							'NO',
							'1',
							'1',
							10,
							'{$parametros['muestra_iniciosintomas']}',
							'{$parametros['muestra_lugar_trabajo']}',
							'{$parametros['muestra_cantpersonas']}',
							'{$parametros['muestra_destino']}',
							'{$parametros['muestra_ant_epi']}',
							'{$parametros['muestra_embarazada']}',
							'{$parametros['muestra_estadoingreso']}',
							1
						)
					";

			$objCon->ejecutarSQL($sql, "ERROR AL GUARDAR FORMULARIO INTERNO");

		}



		function guardarFormulario ( $objCon, $parametros ) {

			$sql = "INSERT INTO
						formulario.formulario_interno
						(
							form_int_idpaciente,
							form_int_celular,
							form_int_telefono,
							form_int_email,
							form_int_estado,
							form_int_observacion,
							form_int_direccion_pac,
							form_int_fecha,
							form_int_hora,
							form_int_cantpersonas,
							form_int_motivosospecha,
							form_int_iniciosintimas,
							form_int_estadoingreso,
							form_int_ant_epi,
							form_int_destino,
							form_int_procedencia,
							form_int_dau,
							form_int_est_hosp,
							form_int_cant_int,
							form_int_lugar_trabajo,
							form_int_pais_residencia,
							form_int_nacionalidad
						)
					VALUES
						(
							'{$parametros['form_int_idpaciente']}',
							'{$parametros['form_int_celular']}',
							'{$parametros['form_int_telefono']}',
							'{$parametros['form_int_email']}',
							8,
							'{$parametros['form_int_observacion']}',
							'{$parametros['form_int_direccion_pac']}',
							CURDATE(),
							CURTIME(),
							'{$parametros['form_int_cantpersonas']}',
							'{$parametros['form_int_motivosospecha']}',
							'{$parametros['form_int_iniciosintimas']}',
							'{$parametros['form_int_estadoingreso']}',
							'{$parametros['form_int_ant_epi']}',
							'{$parametros['form_int_destino']}',
							1,
							'{$parametros['form_int_dau']}',
							1,
							1,
							'{$parametros['form_int_lugar_trabajo']}',
							'{$parametros['form_int_pais_residencia']}',
							'{$parametros['form_int_nacionalidad']}'
						)
					";

			$objCon->ejecutarSQL($sql, "ERROR AL GUARDAR FORMULARIO INTERNO");

			$form_id = $objCon->lastInsertId();

			$sql2 = "INSERT INTO
						formulario.seguimiento
						(
							form_int_id,
							seg_fecha,
							seg_hora,
							seg_usuario,
							seg_motivo,
							seg_observacion_general
						)
					VALUES
						(
							'{$form_id}',
							CURDATE(),
							CURTIME(),
							'{$parametros['seg_usuario']}',
							'4',
							'Paciente con sospecha de COVID-19 a espera de resultados de examen.'
						)
					";

			$objCon->ejecutarSQL($sql2, "ERROR AL GUARDAR SEGUIMIENTO INTERNO");

			$parametros['form_id'] = $form_id;

			$this->guardarTomaMuestra($objCon, $parametros);

		}
		
		
		
		function insertarSeguimiento ( $objCon, $parametros ) {
		
			$sql = "INSERT INTO
						formulario.seguimiento
						(
							form_int_id,
							seg_fecha,
							seg_hora,
							seg_usuario,
							seg_motivo,
							seg_observacion_general
						)
					VALUES
						(
							'{$parametros['form_id']}',
							CURDATE(),
							CURTIME(),
							'{$parametros['seg_usuario']}',
							'{$parametros['seg_motivo']}',
							'{$parametros['seg_observacion_general']}'
						)
					";

			$objCon->ejecutarSQL($sql, "ERROR AL ACTUALIZAR insertarSeguimiento");

		}



		function obtenerAntecedentesEpidemiologicos ( $objCon ) {

            $sql = "SELECT
						formulario.antecedentes_epi.*
					FROM
						formulario.antecedentes_epi
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Antecedentes Epidemiológicos<br>");

			return $resultado;

        }



		function obtenerDestinos ( $objCon ) {

            $sql = "SELECT
						formulario.destino.*
					FROM
						formulario.destino
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Destinos<br>");

			return $resultado;

        }



		function obtenerEstadosIngreso ( $objCon ) {

            $sql = "SELECT
						formulario.estado.*
					FROM
						formulario.estado
					WHERE
						formulario.estado.est_tipo = 2
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Estados Ingreso<br>");

			return $resultado;

        }



		function obtenerInformacionSeguimiento ( $objCon, $idPaciente ) {

			$sql = "SELECT
						formulario.formulario_interno.*
					FROM
						formulario.formulario_interno
					WHERE
						formulario.formulario_interno.form_int_idpaciente = '{$idPaciente}'
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Antecedentes Epidemiológicos según Id DAU<br>");

			return $resultado[0];

		}



		function obtenerInformacionTomaMuestra ( $objCon, $idFormulario ) {

			$sql = "SELECT
						formulario.muestra.*
					FROM
						formulario.muestra
					WHERE
						formulario.muestra.form_int_id ='{$idFormulario}'
					ORDER BY
						formulario.muestra.id_muestra DESC LIMIT 1
					";

			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información sobre Toma de Muetra<br>");

			return $resultado[0];

		}
		
		
		
		function obtenerResultadosMuestrasAnteriores ( $objCon, $idPaciente ) {
		
			$sql = "SELECT
						formulario.muestra.id_muestra,
						formulario.muestra.form_int_id,
						formulario.muestra.tomada_por_muestra,
						formulario.muestra.fecha_toma_muestra,
						formulario.muestra.covid_solicita_muestra,
						formulario.muestra.ifi_solicita_muestra,
						formulario.muestra.tipo_muestra,
						formulario.muestra.fecha_ingreso_muestra,
						formulario.muestra.hora_ingreso_muestra,
						formulario.muestra.usuario_ingreso_muestra,
						formulario.muestra.lugar_toma_muestra,
						formulario.muestra.Broncoalveolar,
						formulario.muestra.Esputo,
						formulario.muestra.Aspirado_Traqueal,
						formulario.muestra.Aspirado_Nasofaringeo,
						formulario.muestra.torulas_nasofaringeas,
						formulario.muestra.tejido_pulmonar,
						formulario.muestra.id_equipo,
						formulario.muestra.id_centro,
						TIMESTAMPDIFF(DAY,muestra.muestra_iniciosintomas,CURDATE()) AS Tiempo_transcurrido_asintomatico,
						TIMESTAMPDIFF(DAY,muestra.fecha_toma_muestra,CURDATE()) AS Tiempo_transcurrido_asintomatico_muestra,
						formulario.muestra.conf_id,
						formulario.muestra.motivo_nueva_muestra,
						formulario.muestra.pendiente_muestra,
						formulario.muestra.pendiente_observacion_muestra,
						formulario.muestra.pendiente_op_id,
						formulario.muestra.estado_muestra,
						formulario.muestra.muestra_validadas,
						formulario.muestra.muestra_fecha_cortes,
						formulario.muestra.solicitud_examen,
						formulario.muestra.fecha_resultado_muestra,
						formulario.muestra.hora_resultado_muestra,
						formulario.muestra.id_laboratorio,
						formulario.muestra.id_reg_hosp_establ_origen,
						formulario.muestra.fecha_validado_muestra,
						formulario.muestra.hora_validado_muestra,
						formulario.muestra.id_corte,
						formulario.muestra.usuario_recepciona,
						formulario.muestra.usuario_valida,
						formulario.muestra.nombre_paciente_omega,
						formulario.muestra.id_lugar_toma,
						formulario.muestra.numero_epivigila_muestra,
						formulario.muestra.embarazada_muestra,
						formulario.muestra.notificacion_usuario,
						formulario.muestra.notificacion_fecha,
						formulario.muestra.notificacion_hora,
						formulario.muestra.notificacion_observacion,
						formulario.muestra.id_procedencia_muestra,
						formulario.muestra.otra_muestra,
						formulario.muestra.liquidoCefalo,
						formulario.muestra.muestra_autorizada,
						formulario.muestra.muestra_autorizada_observacion,
						formulario.muestra.muestra_latitud,
						formulario.muestra.muestra_longitud,
						formulario.muestra.muestra_iniciosintomas,
						formulario.muestra.muestra_lugar_trabajo,
						formulario.muestra.muestra_cantpersonas,
						formulario.muestra.muestra_destino,
						formulario.muestra.muestra_ant_epi,
						formulario.muestra.muestra_embarazada,
						formulario.muestra.muestra_estadoingreso,
						formulario.estado.est_descripcion,
						formulario.muestra.correlativo_laboratorio_muestra,
						formulario.formulario_interno.form_int_idpaciente,
						paciente.paciente.rut,
						paciente.paciente.extranjero,
						paciente.paciente.rut_extranjero
					FROM
						formulario.muestra
					INNER JOIN 
						formulario.estado ON formulario.muestra.estado_muestra = formulario.estado.est_id
					INNER JOIN 
						formulario.formulario_interno ON formulario.muestra.form_int_id = formulario.formulario_interno.form_int_id
					INNER JOIN 
						paciente.paciente ON formulario.formulario_interno.form_int_idpaciente = paciente.paciente.id
					WHERE
						paciente.paciente.id = '{$idPaciente}'				
					";
					
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información sobre Resultados Muestras Anteriores<br>");

			return $resultado;
		
		
		}
		
		
		
		function verificarExamenPositivo ( $objCon, $idPaciente ) {
		
			$sql = "SELECT
						formulario.muestra.estado_muestra AS estadoMuestra,
						formulario.formulario_interno.form_int_estado AS estadoFormulario,
						DATE_FORMAT(formulario.muestra.fecha_toma_muestra, '%d-%m-%Y') AS fechaTomaMuestra,
						TIMESTAMPDIFF(DAY,formulario.muestra.fecha_toma_muestra,CURDATE()) AS Tiempo_transcurrido,
						DATE_FORMAT(formulario.muestra.fecha_resultado_muestra, '%d-%m-%Y') AS fechaResultadoMuestra
					FROM
						formulario.muestra
					INNER JOIN 
						formulario.formulario_interno ON formulario.muestra.form_int_id = formulario.formulario_interno.form_int_id
					WHERE 
						formulario.formulario_interno.form_int_idpaciente = '{$idPaciente}'
					AND
						formulario.muestra.estado_muestra = 3
					AND
						formulario.formulario_interno.form_int_estado IN (3, 4)
					ORDER BY
						formulario.muestra.id_muestra DESC
					";
					
			$resultado=$objCon->consultaSQL($sql,"<br>ERROR Obtener Información sobre Toma de Muetra<br>");

			return $resultado[0];
		
		}

    }

?>