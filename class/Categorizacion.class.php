<?php

class Categorizacion{

	function searchPaciente($objCon,$id){
		$sql = "SELECT
					dau.dau.dau_id,
					dau.dau.est_id,
					dau.dau.id_paciente,
					dau.dau.dau_atencion,
					dau.dau.dau_admision_fecha,
					dau.dau.dau_categorizacion_actual,
					dau.dau.dau_motivo_consulta,
					dau.dau.idctacte,
					dau.dau.dau_mordedura,
					dau.dau.dau_entrega_informacion,
					dau.dau.dau_observacionEntregaInformacion,
					dau.dau.dau_se_entrega_informacion,
					dau.dau.dau_motivo_descripcion,
					dau.dau.dau_categorizacion_fecha,
					dau.dau.dau_viaje_epidemiologico,
					dau.dau.dau_pais_epidemiologia,
					dau.dau.dau_observacion_epidemiologica,
					dau.dau.dau_paciente_prevision,
					dau.dau.dau_paciente_forma_pago,
					dau.dau.dau_paciente_aps,
					paciente.paciente.rut,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,

					paciente.paciente.transexual,
					paciente.paciente.nombreSocial,


					paciente.paciente.sexo,
					paciente.paciente.fechanac,
					dau.dau.dau_paciente_complejo,
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
					) AS runPaciente,
					dau.dau.dau_paciente_edad,
					religion.rlg_descripcion AS religion_descripcion
				FROM
					dau.dau
				INNER JOIN
					paciente.paciente ON dau.dau.id_paciente = paciente.paciente.id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE
					dau.dau.dau_id = '$id'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}


	function asignarEsi($objCon, $parametros){
    // Campos obligatorios
    $campos = [
        "dau_id" => $parametros["dau_id"],
        "cat_id" => "'{$parametros["catesi"]}'",
        "dau_cat_fecha" => "NOW()",
        "dau_cat_e1_respuesta" => "'{$parametros["dau_cat_1_resp"]}'",
        "dau_cat_e2_avdi" => "'{$parametros["dau_cat_2_avdi"]}'",
        "dau_cat_e2_distresado" => "'{$parametros["dau_cat_2_dist"]}'",
        "dau_cat_e2_eva" => $parametros["dau_cat_2_eva"],
        "dau_cat_e3_respuesta" => $parametros["dau_cat_3_resp"],
        "dau_cat_e4_sao2" => $parametros["dau_cat_4_satu"],
        "dau_cat_e4_frecuencia_respiratoria" => "'{$parametros["dau_cat_4_fr"]}'",
        "dau_cat_e4_frecuencia_cardiaca" => $parametros["dau_cat_4_fc"],
        "dau_cat_e4_temperatura" => $parametros["dau_cat_4_temp"],
        "dau_cat_e4_inmunizaciones" => $parametros["dau_cat_4_inmu"],
        "dau_cat_e4_origen_fiebre" => $parametros["dau_cat_4_fiebre"],
        "dau_cat_usuario_inserta" => "'{$parametros["dau_cat_usuario_inserta"]}'",
        "dau_cat_considerada" => "'{$parametros["dau_cat_considerada"]}'"
    ];

    // Campos opcionales: solo si tienen contenido
    if (!empty($parametros["dau_cat_obs_enfermera"])) {
        $campos["dau_cat_obs_enfermera"] = "'{$parametros["dau_cat_obs_enfermera"]}'";
    }
    if (!empty($parametros["frm_dispositivoinvasivo"])) {
        $campos["id_parametros_invasivo"] = "'{$parametros["frm_dispositivoinvasivo"]}'";
    }
    if (!empty($parametros["texto_dispositivo_invasivo"])) {
        $campos["invasivo_texto"] = "'{$parametros["texto_dispositivo_invasivo"]}'";
    }

    // Armar columnas y valores para el SQL
    $columnas = implode(", ", array_keys($campos));
    $valores = implode(", ", array_values($campos));

    $sql = "INSERT INTO dau.dau_tiene_categorizacion ($columnas) VALUES ($valores)";

    $response = $objCon->ejecutarSQL($sql, "<br>Error al insertar categorización<br>");
}
	// function asignarEsi($objCon, $parametros){
	// 	$sql =	"INSERT INTO
	// 				dau.dau_tiene_categorizacion
	// 			   	(dau_id,
	// 				cat_id,
	// 				dau_cat_fecha,
	// 				dau_cat_e1_respuesta,
	// 				dau_cat_e2_avdi,
	// 				dau_cat_e2_distresado,
	// 				dau_cat_e2_eva,
	// 				dau_cat_e3_respuesta,
	// 				dau_cat_e4_sao2,
	// 				dau_cat_e4_frecuencia_respiratoria,
	// 				dau_cat_e4_frecuencia_cardiaca,
	// 				dau_cat_e4_temperatura,
	// 				dau_cat_e4_inmunizaciones,
	// 				dau_cat_e4_origen_fiebre,
	// 				dau_cat_obs_enfermera,
	// 				id_parametros_invasivo,
	// 				invasivo_texto,
	// 				dau_cat_usuario_inserta,
	// 				dau_cat_considerada)
	// 		    VALUES
	// 			    ({$parametros["dau_id"]},
	// 			    '{$parametros["catesi"]}',
	// 			    NOW(),
	// 			    '{$parametros["dau_cat_1_resp"]}',
	// 			    '{$parametros["dau_cat_2_avdi"]}',
	// 			    '{$parametros["dau_cat_2_dist"]}',
	// 			    {$parametros["dau_cat_2_eva"]},
	// 			    {$parametros["dau_cat_3_resp"]},
	// 			    {$parametros["dau_cat_4_satu"]},
	// 			    '{$parametros["dau_cat_4_fr"]}',
	// 			    {$parametros["dau_cat_4_fc"]},
	// 			    {$parametros["dau_cat_4_temp"]},
	// 			    {$parametros["dau_cat_4_inmu"]},
	// 			    {$parametros["dau_cat_4_fiebre"]},
	// 			    '{$parametros["dau_cat_obs_enfermera"]}',
	// 			    '{$parametros["frm_dispositivoinvasivo"]}',
	// 			    '{$parametros["texto_dispositivo_invasivo"]}',
	// 			    '{$parametros["dau_cat_usuario_inserta"]}',
	// 				'{$parametros["dau_cat_considerada"]}')";
	// 	$response = $objCon->ejecutarSQL($sql,"<br>Error al insertar categorización<br>");
	// }



	function updEstado($objCon, $parametros){
		$sql ="UPDATE  dau.dau
			SET
				est_id = {$parametros["est_id"]},
				dau_categorizacion_actual ='{$parametros["catesi"]}',
				dau_categorizacion_considerada ='{$parametros["dau_cat_considerada"]}',
				dau_categorizacion_actual_fecha =NOW(),
				dau_categorizacion_actual_usuario ='{$parametros["dau_cat_usuario_inserta"]}',
				dau_viaje_epidemiologico = '{$parametros['dau_viaje_epidemiologico']}',
				dau_pais_epidemiologia = '{$parametros['dau_pais_epidemiologia']}',
				dau_indiferenciado = '{$parametros['dau_indiferenciado']}',
				dau_observacion_epidemiologica = '{$parametros['dau_observacion_epidemiologica']}'
			WHERE dau_id = {$parametros["dau_id"]}";
		$response = $objCon->ejecutarSQL($sql,"<br>Erroral actualizar categorización<br>");
	}



	function updEstadoCat($objCon, $parametros){
		$sql =	"UPDATE
					dau.dau
				SET
					est_id = {$parametros["est_id"]},
					dau_categorizacion = '{$parametros["catesi"]}',
					dau_categorizacion_considerada = '{$parametros["dau_cat_considerada"]}',
					dau_categorizacion_fecha = NOW(),
					dau_categorizacion_usuario = '{$parametros["dau_cat_usuario_inserta"]}',
					dau_categorizacion_actual = '{$parametros["catesi"]}',
					dau_categorizacion_actual_fecha = NOW(),
					dau_categorizacion_actual_usuario = '{$parametros["dau_cat_usuario_inserta"]}',
					dau_indiferenciado = '{$parametros['dau_indiferenciado']}',
					dau_viaje_epidemiologico = '{$parametros['dau_viaje_epidemiologico']}',
					dau_pais_epidemiologia = '{$parametros['dau_pais_epidemiologia']}',
					dau_observacion_epidemiologica = '{$parametros['dau_observacion_epidemiologica']}'
				WHERE
					dau_id = {$parametros["dau_id"]}";
		$response = $objCon->ejecutarSQL($sql,"<br>Error al actualizar categorización<br>");
	}
	function RecategorizarDau($objCon, $parametros){
		$sql =	"UPDATE
					dau.dau
				SET
					dau_categorizacion = '{$parametros["catesi"]}',
					dau_categorizacion_considerada = '{$parametros["dau_cat_considerada"]}',
					dau_categorizacion_fecha = NOW(),
					dau_categorizacion_usuario = '{$parametros["dau_cat_usuario_inserta"]}',
					dau_categorizacion_actual = '{$parametros["catesi"]}',
					dau_categorizacion_actual_fecha = NOW(),
					dau_categorizacion_actual_usuario = '{$parametros["dau_cat_usuario_inserta"]}',
					dau_indiferenciado = '{$parametros['dau_indiferenciado']}',
					dau_viaje_epidemiologico = '{$parametros['dau_viaje_epidemiologico']}',
					dau_pais_epidemiologia = '{$parametros['dau_pais_epidemiologia']}',
					dau_observacion_epidemiologica = '{$parametros['dau_observacion_epidemiologica']}'
				WHERE
					dau_id = {$parametros["dau_id"]}";
		$response = $objCon->ejecutarSQL($sql,"<br>Error al actualizar categorización<br>");
	}



	function searchPacienterau($objCon,$id){
		$sql = "SELECT
					rau.rau.rut,
					rau.rau.estado,
					rau.rau.motivoconsulta,
					paciente.paciente.nombres,
					paciente.paciente.apellidopat,
					paciente.paciente.apellidomat,
					paciente.paciente.sexo,
					paciente.paciente.fechanac,
					religion.rlg_descripcion AS religion_descripcion
				FROM
					rau.rau
				INNER JOIN
					paciente.paciente ON rau.rau.idpaciente = paciente.paciente.id
                LEFT JOIN paciente.religion on paciente.religion.rlg_id = paciente.paciente.religion
				WHERE
					rau.rau.idrau = '$id'";
		$datosRau = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datosRau;
	}



	function edadAno($fecha_de_nacimiento){
		$fecha_actual = date ("Y-m-d");
		$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
		$array_actual = explode ( "-", $fecha_actual );
		if($array_nacimiento[0] > 1900){
			$anos =  $array_actual[0] - $array_nacimiento[0];
			$meses = $array_actual[1] - $array_nacimiento[1];
			$dias =  $array_actual[2] - $array_nacimiento[2];

			if ($dias < 0) {
				--$meses;

				switch ($array_actual[1]) {
					   case 1:     $dias_mes_anterior=31; break;
					   case 2:     $dias_mes_anterior=31; break;
					   case 3:
							if (checkdate(2,29,$array_actual[0]))
							{
								$dias_mes_anterior=29; break;
							} else {
								$dias_mes_anterior=28; break;
							}
					   case 4:     $dias_mes_anterior=31; break;
					   case 5:     $dias_mes_anterior=30; break;
					   case 6:     $dias_mes_anterior=31; break;
					   case 7:     $dias_mes_anterior=30; break;
					   case 8:     $dias_mes_anterior=31; break;
					   case 9:     $dias_mes_anterior=31; break;
					   case 10:     $dias_mes_anterior=30; break;
					   case 11:     $dias_mes_anterior=31; break;
					   case 12:     $dias_mes_anterior=30; break;
				}
				$dias=$dias + $dias_mes_anterior;
			}
			if ($meses < 0) {
				--$anos;
				$meses=$meses + 12;
			}
			$edadCompleta = "$anos";
			return($edadCompleta);
		}else{
			return("* Verificar Fecha de Nacimiento");
		}
	}



	function edadMes($fecha_de_nacimiento){
		$fecha_actual = date ("Y-m-d");
		$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
		$array_actual = explode ( "-", $fecha_actual );
		if($array_nacimiento[0] > 1900){
			$anos =  $array_actual[0] - $array_nacimiento[0];
			$meses = $array_actual[1] - $array_nacimiento[1];
			$dias =  $array_actual[2] - $array_nacimiento[2];
			if ($dias < 0) {
				--$meses;
				switch ($array_actual[1]) {
					   case 1:     $dias_mes_anterior=31; break;
					   case 2:     $dias_mes_anterior=31; break;
					   case 3:
							if (checkdate(2,29,$array_actual[0]))
							{
								$dias_mes_anterior=29; break;
							} else {
								$dias_mes_anterior=28; break;
							}
					   case 4:     $dias_mes_anterior=31; break;
					   case 5:     $dias_mes_anterior=30; break;
					   case 6:     $dias_mes_anterior=31; break;
					   case 7:     $dias_mes_anterior=30; break;
					   case 8:     $dias_mes_anterior=31; break;
					   case 9:     $dias_mes_anterior=31; break;
					   case 10:     $dias_mes_anterior=30; break;
					   case 11:     $dias_mes_anterior=31; break;
					   case 12:     $dias_mes_anterior=30; break;
				}
				$dias=$dias + $dias_mes_anterior;
			}
			if ($meses < 0) {
				--$anos;
				$meses=$meses + 12;
			}
			$edadCompleta = "$meses";
			return($edadCompleta);
		}else{
			return("* Verificar Fecha de Nacimiento");
		}
	}



	function edadDia($fecha_de_nacimiento){
		$fecha_actual = date ("Y-m-d");
		$array_nacimiento = explode ( "-", $fecha_de_nacimiento );
		$array_actual = explode ( "-", $fecha_actual );
		if($array_nacimiento[0] > 1900){
			$anos =  $array_actual[0] - $array_nacimiento[0];
			$meses = $array_actual[1] - $array_nacimiento[1];
			$dias =  $array_actual[2] - $array_nacimiento[2];
			if ($dias < 0) {
				--$meses;
				switch ($array_actual[1]) {
					   case 1:     $dias_mes_anterior=31; break;
					   case 2:     $dias_mes_anterior=31; break;
					   case 3:
							if (checkdate(2,29,$array_actual[0]))
							{
								$dias_mes_anterior=29; break;
							} else {
								$dias_mes_anterior=28; break;
							}
					   case 4:     $dias_mes_anterior=31; break;
					   case 5:     $dias_mes_anterior=30; break;
					   case 6:     $dias_mes_anterior=31; break;
					   case 7:     $dias_mes_anterior=30; break;
					   case 8:     $dias_mes_anterior=31; break;
					   case 9:     $dias_mes_anterior=31; break;
					   case 10:     $dias_mes_anterior=30; break;
					   case 11:     $dias_mes_anterior=31; break;
					   case 12:     $dias_mes_anterior=30; break;
				}
				$dias=$dias + $dias_mes_anterior;
				if ( $dias < 0 ) $dias = $dias * -1;
			}
			if ($meses < 0) {
				--$anos;
				$meses=$meses + 12;
			}
			$edadCompleta = "$dias";
			return($edadCompleta);
		}else{
			return("* Verificar Fecha de Nacimiento");
		}
	}



	function getDatosDauCat($objCon, $parametros){
		$objCon->setDB("DAU");
		$sql = "SELECT
				dau.dau_id,
				dau.dau_categorizacion_actual,
				categorizacion.cat_nivel,
				categorizacion.cat_tipo,
				categorizacion.cat_nombre_mostrar
				FROM
				dau
				INNER JOIN categorizacion ON dau.dau_categorizacion_actual = categorizacion.cat_id
				WHERE dau.dau_id = '{$parametros['id_dau']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener los Datos del Paciente categorizado");
		return $datos;
	}



	function asignarSDD($objCon, $parametros){
		$sql =	"INSERT INTO
					dau.dau_tiene_categorizacion
				   	(dau_id,
					cat_id,
					dau_cat_fecha,
					dau_cat_sdd_presion_1,
					dau_cat_sdd_presion_2,
					dau_cat_sdd_pulso,
					dau_cat_sdd_temperatura,
					dau_cat_sdd_saturaciono2,
					dau_cat_sdd_temp_rectal,
					dau_cat_usuario_inserta)
			    VALUES
				    ({$parametros["dau_id"]},
				    '{$parametros["dau_cat_4_categ"]}',
				    NOW(),
				    {$parametros["dau_cat_4_fr_1"]},
				    {$parametros["dau_cat_4_fr_2"]},
				    {$parametros["dau_cat_4_fc"]},
				    {$parametros["dau_cat_4_temp"]},
				    {$parametros["dau_cat_4_satu"]},
				    {$parametros["dau_cat_4_temp_rec"]},
					'{$parametros["dau_mov_usuario"]}')";
		$response = $objCon->ejecutarSQL($sql,"<br>Error al insertar categorización<br>");
		return $response;
	}



	function listarCategorizacion($objCon, $parametros){
		$sql = "SELECT
				categorizacion.cat_id,
				categorizacion.cat_nivel,
				categorizacion.cat_nombre,
				categorizacion.cat_descripcion,
				categorizacion.cat_tiempo_maximo,
				categorizacion.cat_tipo,
				categorizacion.cat_nombre_mostrar
				FROM categorizacion
				WHERE categorizacion.cat_tipo = '{$parametros['tipoCat']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener las Categorizaciones");
		return $datos;

	}



	function agregarCategorizacionPosteriorDAU($objCon, $parametros){
		$sql =	"INSERT INTO
					dau_tiene_categorizacion
				   	(dau_id,
					cat_id,
					dau_cat_fecha,
					dau_cat_posterior,
					dau_cat_posterior_motivo)
			    VALUES
				    ({$parametros["dau_id"]},
				    '{$parametros["categorizacion"]}',
				    NOW(),
				    {$parametros["dau_cat_posterior"]},
				    '{$parametros["dau_cat_posterior_motivo"]}')";
		$response = $objCon->ejecutarSQL($sql,"<br>Error al insertar categorización posterior<br>");
	}



	function updateDAUCategorizacionPosterior($objCon, $parametros){
		$sql =	"UPDATE
					dau
				SET
					dau_categorizacion = '{$parametros["categorizacion"]}',
					dau_categorizacion_fecha = NOW(),
					dau_categorizacion_usuario = '{$parametros["dau_cat_usuario_inserta"]}',
					dau_categorizacion_actual = '{$parametros["categorizacion"]}',
					dau_categorizacion_actual_fecha = NOW(),
					dau_categorizacion_actual_usuario = '{$parametros["dau_cat_usuario_inserta"]}'
				WHERE
					dau_id = {$parametros["dau_id"]}";
		$response = $objCon->ejecutarSQL($sql,"<br>Error al actualizar categorización posterior en DAU<br>");
	}



	function listarCategorizaciones($objCon){
		$sql="SELECT
			categorizacion.cat_id,
			categorizacion.cat_nombre_mostrar
		FROM
			dau.categorizacion
		WHERE categorizacion.cat_id IN('ESI-1','ESI-2','ESI-3','ESI-4','ESI-5')";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener las Categorizaciones");
		return $datos;
	}



	function listarPacientes_IND_ENF($objCon, $parametros){
		$condicion = "";
		$sql="
			SELECT
				paciente.paciente.rut,
				paciente.paciente.rut_extranjero,
				CONCAT(
					paciente.paciente.nombres,
					' ',
					paciente.paciente.apellidopat,
					' ',
					paciente.paciente.apellidomat
				) AS nombre,
				dau.dau.dau_categorizacion_actual,
				dau.sala.sal_descripcion,
				dau.sala.id_unidad,
				dau.dau.dau_id,
				dau.dau.est_id,
				dau.cama.cam_descripcion,
				dau.tipo_cama.tipo_cama_sigla,
				dau.dau.dau_inicio_atencion_fecha,
				rce.registroclinico.regId,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 6
							AND rce.solicitud_indicaciones.sol_ind_estado = 1
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadSolicitadaProcedimiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 6
							AND rce.solicitud_indicaciones.sol_ind_estado = 4
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadAplicadaProcedimiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 6
							AND rce.solicitud_indicaciones.sol_ind_estado <> 6
							AND rce.solicitud_indicaciones.sol_ind_estado <> 8
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadTotalProcedimiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado = 1 AND le.prestaciones_imagenologia.tipo_examen = 'TC'
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadSolicitadaImagenologiaTC,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado = 4 AND le.prestaciones_imagenologia.tipo_examen = 'TC'
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadAplicadaImagenologiaTC,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado <> 6 AND le.prestaciones_imagenologia.tipo_examen = 'TC'
							AND rce.solicitud_imagenologia.sol_ima_estado <> 8
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadTotalImagenologiaTC,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado = 1 AND le.prestaciones_imagenologia.tipo_examen <> 'TC'
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadSolicitadaImagenologia,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado = 4 AND le.prestaciones_imagenologia.tipo_examen <> 'TC'
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadAplicadaImagenologia,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_imagenologia.sol_ima_estado <> 6 AND le.prestaciones_imagenologia.tipo_examen <> 'TC'
							AND rce.solicitud_imagenologia.sol_ima_estado <> 8
						THEN
							rce.solicitud_imagenologia.sol_ima_id
					END
				) AS cantidadTotalImagenologia,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 2
							AND rce.solicitud_indicaciones.sol_ind_estado = 1
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadSolicitadaTratamiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 2
							AND rce.solicitud_indicaciones.sol_ind_estado = 4
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadAplicadaTratamiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 2
							AND rce.solicitud_indicaciones.sol_ind_estado <> 6
							AND rce.solicitud_indicaciones.sol_ind_estado <> 8
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadTotalTratamiento,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_laboratorio.sol_lab_estado = 1
						THEN
							rce.solicitud_laboratorio.sol_lab_id
					END
				) AS cantidadSolicitadaLaboratorio,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_laboratorio.sol_lab_estado = 4
						THEN
							rce.solicitud_laboratorio.sol_lab_id
					END
				) AS cantidadAplicadaLaboratorio,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_laboratorio.sol_lab_estado <> 6
							AND rce.solicitud_laboratorio.sol_lab_estado <> 8
						THEN
							rce.solicitud_laboratorio.sol_lab_id
					END
				) AS cantidadTotalLaboratorio,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 4
							AND rce.solicitud_indicaciones.sol_ind_estado = 1
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadSolicitadaOtros,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 4
							AND rce.solicitud_indicaciones.sol_ind_estado = 4
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadAplicadaOtros,
				COUNT(
					DISTINCT CASE
						WHEN
							rce.solicitud_indicaciones.sol_ind_servicio = 4
							AND rce.solicitud_indicaciones.sol_ind_estado <> 6
							AND rce.solicitud_indicaciones.sol_ind_estado <> 8
						THEN
							rce.solicitud_indicaciones.sol_ind_id
					END
				) AS cantidadTotalOtros,
				COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_especialista.SESPestado = 1 THEN rce.solicitud_especialista.SESPid
			        END
			    ) +
			    COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_otros_especialidad.estado_sol_otro = 1 THEN rce.solicitud_otros_especialidad.id_sol_otro
			        END
			    ) AS cantidadSolicitadaEspecialidad,
			    COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_especialista.SESPestado = 4 THEN rce.solicitud_especialista.SESPid
			        END
			    ) +
			    COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_otros_especialidad.estado_sol_otro = 4 THEN rce.solicitud_otros_especialidad.id_sol_otro
			        END
			    ) AS cantidadAplicadaEspecialidad,

			    COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_especialista.SESPestado <> 6
							AND	rce.solicitud_especialista.SESPestado <> 8 THEN rce.solicitud_especialista.SESPid
			        END
			    ) +
			    COUNT(
			        DISTINCT CASE
			            WHEN rce.solicitud_otros_especialidad.estado_sol_otro <> 6
							AND	rce.solicitud_otros_especialidad.estado_sol_otro <> 8 THEN rce.solicitud_otros_especialidad.id_sol_otro
			        END
			    ) AS cantidadTotalEspecialidad,
				MIN(
				    CASE
				        WHEN le.prestaciones_imagenologia.tipo_examen = 'TC'
				        THEN rce.solicitud_imagenologia.sol_ima_fechaInserta
				    END
				) AS primera_fecha_solicitud_imagenologiaTC,
				MIN(
				    CASE
				        WHEN le.prestaciones_imagenologia.tipo_examen <> 'TC'
				        THEN rce.solicitud_imagenologia.sol_ima_fechaInserta
				    END
				) AS primera_fecha_solicitud_imagenologia,
				CASE 
				    WHEN LEAST(
				        COALESCE(MIN(solicitud_especialista.SESPfecha), '9999-12-31'),
				        COALESCE(MIN(solicitud_otros_especialidad.sol_otro_fecha), '9999-12-31')
				    ) = '9999-12-31' THEN NULL
				    ELSE LEAST(
				        COALESCE(MIN(solicitud_especialista.SESPfecha), '9999-12-31'),
				        COALESCE(MIN(solicitud_otros_especialidad.sol_otro_fecha), '9999-12-31')
				    )
				END AS primera_fecha_solicitud_especialista,
				MIN(rce.solicitud_laboratorio.sol_lab_fechaInserta) AS primera_fecha_solicitud_laboratorio,
				MIN(CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 6  AND rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8 THEN rce.solicitud_indicaciones.sol_ind_fechaInserta END) AS primera_fecha_Procedimiento, 
    			MIN(CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 4  AND rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8 THEN rce.solicitud_indicaciones.sol_ind_fechaInserta END) AS primera_fecha_Otros, 
    			MIN(CASE WHEN rce.solicitud_indicaciones.sol_ind_servicio = 2  AND rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8 THEN rce.solicitud_indicaciones.sol_ind_fechaInserta END) AS primera_fecha_Tratamiento,
				CASE 
		        WHEN LEAST(
		            COALESCE(MIN(rce.solicitud_indicaciones.sol_ind_fechaInserta), '9999-12-31'), 
		            COALESCE(MIN(rce.solicitud_imagenologia.sol_ima_fechaInserta), '9999-12-31'), 
		            COALESCE(MIN(rce.solicitud_especialista.SESPfecha), '9999-12-31'),
		            COALESCE(MIN(rce.solicitud_otros_especialidad.sol_otro_fecha), '9999-12-31'),  
		            COALESCE(MIN(rce.solicitud_laboratorio.sol_lab_fechaInserta), '9999-12-31')
		        ) = '9999-12-31' THEN NULL
		        ELSE LEAST(
		            COALESCE(MIN(rce.solicitud_indicaciones.sol_ind_fechaInserta), '9999-12-31'), 
		            COALESCE(MIN(rce.solicitud_imagenologia.sol_ima_fechaInserta), '9999-12-31'), 
		            COALESCE(MIN(rce.solicitud_especialista.SESPfecha), '9999-12-31'),
		            COALESCE(MIN(rce.solicitud_otros_especialidad.sol_otro_fecha), '9999-12-31'),  
		            COALESCE(MIN(rce.solicitud_laboratorio.sol_lab_fechaInserta), '9999-12-31')
		        )
		    END AS fecha_mas_pequena,
			    NOW() AS fecha_servidor
			FROM
				dau.dau
			INNER JOIN
				dau.cama
				ON dau.cama.dau_id = dau.dau.dau_id
			INNER JOIN
				dau.sala
				ON dau.cama.sal_id = dau.sala.sal_id
			INNER JOIN
				paciente.paciente
				ON dau.dau.id_paciente = paciente.paciente.id
			INNER JOIN dau.tipo_cama ON dau.sala.sal_tipo_cama = dau.tipo_cama.tipo_cama_id
			INNER JOIN
				rce.registroclinico
				ON rce.registroclinico.dau_id = dau.dau.dau_id
			LEFT JOIN
				rce.solicitud_indicaciones
				ON rce.registroclinico.regId = rce.solicitud_indicaciones.regId
			LEFT JOIN
				rce.solicitud_imagenologia
				ON rce.registroclinico.regId = rce.solicitud_imagenologia.regId
			LEFT JOIN 
			    rce.detalle_solicitud_imagenologia_dalca ON rce.solicitud_imagenologia.sol_ima_id = rce.detalle_solicitud_imagenologia_dalca.idSolicitudImagenologia
			LEFT JOIN 
			    le.prestaciones_imagenologia ON rce.detalle_solicitud_imagenologia_dalca.idPrestacionImagenologia = le.prestaciones_imagenologia.id_prestaciones
			LEFT JOIN
				rce.solicitud_laboratorio
				ON rce.solicitud_laboratorio.regId = rce.registroclinico.regId
			LEFT JOIN
				rce.solicitud_especialista
				ON rce.registroclinico.regId = rce.solicitud_especialista.SESPidRCE

			LEFT JOIN
				rce.solicitud_otros_especialidad
				ON rce.registroclinico.regId = rce.solicitud_otros_especialidad.idRCE
			WHERE dau.dau.dau_inicio_atencion_fecha IS NOT NULL AND dau.cama.cam_activa = 'S'
		";
		$condicionWhere ="";
		if ($parametros['frm_unidad'] > 0) {
			$sql .= " AND dau.sala.id_unidad = '{$parametros['frm_unidad']}' ";
		}
		$condicionHaving ="";
		if ($parametros['indicacion'] == 1) {

			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.="   primera_fecha_solicitud_especialista IS NOT NULL ";
		}
		if ($parametros['indicacion'] == 2) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.="  primera_fecha_Procedimiento IS NOT NULL ";
		}if ($parametros['indicacion'] == 7) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" primera_fecha_solicitud_imagenologiaTC IS NOT NULL ";
		}
		if ($parametros['indicacion'] == 3) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" primera_fecha_solicitud_imagenologia IS NOT NULL ";
		}
		if ($parametros['indicacion'] == 4) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" primera_fecha_Tratamiento IS NOT NULL ";
		}
		if ($parametros['indicacion'] == 5) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" primera_fecha_solicitud_laboratorio IS NOT NULL ";
		}
		if ($parametros['indicacion'] == 6) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" primera_fecha_Otros IS NOT NULL ";
		}

		if ($parametros['estado'] == 1) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" (  	(cantidadAplicadaEspecialidad = cantidadTotalEspecialidad ) AND 
								    (cantidadAplicadaProcedimiento = cantidadTotalProcedimiento ) AND 
								    (cantidadAplicadaImagenologia = cantidadTotalImagenologia ) AND 
								    (cantidadAplicadaImagenologiaTC = cantidadTotalImagenologiaTC ) AND
								    (cantidadAplicadaTratamiento = cantidadTotalTratamiento ) AND 
								    (cantidadAplicadaLaboratorio = cantidadTotalLaboratorio ) AND 
								    (cantidadAplicadaOtros = cantidadTotalOtros ) 
								    and fecha_mas_pequena is not null )";
		}
		if ($parametros['estado'] == 2) {
			$condicionHaving .= ($condicionHaving == "") ? " HAVING " : " AND ";
			$condicionHaving.=" (  	!( (cantidadAplicadaEspecialidad = cantidadTotalEspecialidad ) AND 
								    (cantidadAplicadaProcedimiento = cantidadTotalProcedimiento ) AND 
								    (cantidadAplicadaImagenologia = cantidadTotalImagenologia ) AND
								    (cantidadAplicadaImagenologiaTC = cantidadTotalImagenologiaTC ) AND  
								    (cantidadAplicadaTratamiento = cantidadTotalTratamiento ) AND 
								    (cantidadAplicadaLaboratorio = cantidadTotalLaboratorio ) AND 
								    (cantidadAplicadaOtros = cantidadTotalOtros ) )
								    and fecha_mas_pequena is not null )";
		}

  		$sql  .= $condicion;
		 $sql .= "
			GROUP BY
				dau.dau.dau_id ".$condicionHaving." 
			
		";

		if ($parametros['indicacion'] == 1) {
			$sql .= " ORDER BY    primera_fecha_solicitud_especialista ASC ";
		}else if ($parametros['indicacion'] == 2) {
			$sql .= " ORDER BY   primera_fecha_Procedimiento ASC ";
		}else if ($parametros['indicacion'] == 3) {
			$sql .= " ORDER BY  primera_fecha_solicitud_imagenologia ASC ";
		}else if ($parametros['indicacion'] == 7) {
			$sql .= " ORDER BY  primera_fecha_solicitud_imagenologiaTC ASC ";
		}else if ($parametros['indicacion'] == 4) {
			$sql .= " ORDER BY  primera_fecha_Tratamiento ASC ";
		}else if ($parametros['indicacion'] == 5) {
			$sql .= " ORDER BY  primera_fecha_solicitud_laboratorio ASC ";
		}else if ($parametros['indicacion'] == 6) {
			$sql .= " ORDER BY  primera_fecha_Otros ASC ";
		}else{
			$sql .= " ORDER BY fecha_mas_pequena  ASC ";
		}

		// echo $sql;
		return $objCon->consultaSQL($sql,"<br>Error al obtener listarPacientes_IND_ENF");
	}



	function listar_Solicitud_Tratamiento($objCon, $parametros){
		$sql="SELECT
		rce.estado_indicacion.est_descripcion,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) as solicitado,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado = 4),1,0)) as aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON rce.solicitud_indicaciones.sol_ind_estado = rce.estado_indicacion.est_id
		WHERE regId ='{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio='2'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_Tratamiento");
		return $datos;
	}



	function listar_Solicitud_procedimiento($objCon, $parametros){
		$sql="SELECT
		rce.estado_indicacion.est_descripcion,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) as solicitado,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado = 4),1,0)) as aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON rce.solicitud_indicaciones.sol_ind_estado = rce.estado_indicacion.est_id
		WHERE regId ='{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio='6'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_Tratamiento");
		return $datos;
	}



	function listar_Solicitud_Imagenologia($objCon, $parametros){
		$sql="SELECT
		rce.tipo_indicaciones.ser_descripcion,
		rce.solicitud_imagenologia.sol_ima_estado,
		Sum(if((rce.solicitud_imagenologia.sol_ima_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_imagenologia.sol_ima_estado= 4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_imagenologia.sol_ima_estado <> 6 AND rce.solicitud_imagenologia.sol_ima_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_imagenologia
		INNER JOIN rce.tipo_indicaciones ON rce.solicitud_imagenologia.sol_ima_tipo = rce.tipo_indicaciones.ser_codigo
		WHERE regId ='{$parametros['regId']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_Imagenologia");
		return $datos;
	}



	function listar_Solicitud_laboratorio($objCon, $parametros){
		$sql="SELECT
		rce.tipo_indicaciones.ser_descripcion,
		rce.solicitud_laboratorio.sol_lab_estado,
		Sum(if((rce.solicitud_laboratorio.sol_lab_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_laboratorio.sol_lab_estado= 4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_laboratorio.sol_lab_estado <> 6 AND rce.solicitud_laboratorio.sol_lab_estado <> 8, 1,0)) AS TOTAL
		FROM
		rce.solicitud_laboratorio
		INNER JOIN rce.tipo_indicaciones ON solicitud_laboratorio.sol_lab_tipo = tipo_indicaciones.ser_codigo
		WHERE regId ='{$parametros['regId']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_laboratorio");
		return $datos;
	}



	function listar_Solicitud_otros($objCon, $parametros){
		$sql="SELECT
		rce.tipo_indicaciones.ser_descripcion,
		rce.solicitud_indicaciones.sol_ind_servicio,
		rce.solicitud_indicaciones.sol_ind_estado,
		Sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_indicaciones.sol_ind_estado= 4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.tipo_indicaciones
		INNER JOIN rce.solicitud_indicaciones ON solicitud_indicaciones.sol_ind_estado = tipo_indicaciones.ser_codigo
		WHERE regId ='{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio='4'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_otros");
		return $datos;
	}



	function listar_Solicitud_Total($objCon, $parametros){
		$sql="SELECT SUM(aplicada) AS aplicada, SUM(TOTAL) AS total FROM (SELECT
		sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) as solicitado,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado = 4),1,0)) as aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON rce.solicitud_indicaciones.sol_ind_estado = rce.estado_indicacion.est_id
		WHERE regId = '{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio = '2'
		UNION ALL
		SELECT
		sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) as solicitado,
		sum(if((rce.solicitud_indicaciones.sol_ind_estado = 4),1,0)) as aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_indicaciones
		INNER JOIN rce.estado_indicacion ON rce.solicitud_indicaciones.sol_ind_estado = rce.estado_indicacion.est_id
		WHERE regId = '{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio = '6'
		UNION ALL
		SELECT
		Sum(if((rce.solicitud_imagenologia.sol_ima_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_imagenologia.sol_ima_estado= 4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_imagenologia.sol_ima_estado <> 6 AND rce.solicitud_imagenologia.sol_ima_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.solicitud_imagenologia
		INNER JOIN rce.tipo_indicaciones ON rce.solicitud_imagenologia.sol_ima_tipo = rce.tipo_indicaciones.ser_codigo
		WHERE regId = '{$parametros['regId']}'
		UNION ALL
		SELECT
		Sum(if((rce.solicitud_laboratorio.sol_lab_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_laboratorio.sol_lab_estado=4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_laboratorio.sol_lab_estado <> 6 AND rce.solicitud_laboratorio.sol_lab_estado <> 8, 1,0)) AS TOTAL
		FROM
		rce.solicitud_laboratorio
		INNER JOIN rce.tipo_indicaciones ON solicitud_laboratorio.sol_lab_tipo = tipo_indicaciones.ser_codigo
		WHERE regId = '{$parametros['regId']}'
		UNION ALL
		SELECT
		Sum(if((rce.solicitud_indicaciones.sol_ind_estado= 1),1,0)) AS solicitado,
		Sum(if((rce.solicitud_indicaciones.sol_ind_estado= 4),1,0)) AS aplicada,
		SUM(if(rce.solicitud_indicaciones.sol_ind_estado <> 6 AND rce.solicitud_indicaciones.sol_ind_estado <> 8, 1, 0)) AS TOTAL
		FROM
		rce.tipo_indicaciones
		INNER JOIN rce.solicitud_indicaciones ON solicitud_indicaciones.sol_ind_estado = tipo_indicaciones.ser_codigo
		WHERE regId = '{$parametros['regId']}' AND rce.solicitud_indicaciones.sol_ind_servicio = '4') AS Consulta";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_Solicitud_Total");
		return $datos;
	}



	function listar_alta_hogar($objCon, $dau_id){
		$sql="SELECT ind_egr_id as respuesta
			FROM dau_tiene_indicacion
			WHERE dau_id = '$dau_id'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al obtener listar_alta_hogar");
		return $datos;
	}

}
?>
