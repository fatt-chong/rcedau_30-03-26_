<?php
	class Cierre{

		function cierreAdministrativoDAU($objCon, $parametros){
			$sql=" UPDATE dau.dau ";

			$condicion = "";

			if ($parametros['frm_condicion_ingreso']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_condicion_ingreso_id = '{$parametros['frm_condicion_ingreso']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_condicion_ingreso_id = NULL";
			}

			if ($parametros['frm_pronostico']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_pronostico_id = '{$parametros['frm_pronostico']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_pronostico_id = NULL";
			}

			if ($parametros['frm_peso']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_peso = '{$parametros['frm_peso']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_peso = NULL";
			}

			if ($parametros['frm_estatura']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_estatura = '{$parametros['frm_estatura']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_estatura = NULL";
			}

			if ($parametros['frm_tratamiento']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_tratamiento_id = '{$parametros['frm_tratamiento']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_tratamiento_id = NULL";
			}

			if ($parametros['frm_atendido_por']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_atendidopor_id = '{$parametros['frm_atendido_por']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_atendidopor_id = NULL";
			}

			if ($parametros['frm_etilico']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_estado_etilico = '{$parametros['frm_etilico']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_estado_etilico = NULL";
			}

			if ($parametros['Profesional']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_profesional_id = '{$parametros['Profesional']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_profesional_id = NULL";
			}

			if ($parametros['frm_turno']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_turno_id = '{$parametros['frm_turno']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_turno_id = NULL";
			}

			if ($parametros['frm_hora_atencion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_hora_atencion = '{$parametros['frm_hora_atencion']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_hora_atencion = NULL";
			}

			if ($parametros['frm_auge']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_auge = '{$parametros['frm_auge']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_auge = NULL";
			}

			if ($parametros['frm_postinor']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_entrega_postinor = '{$parametros['frm_postinor']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_entrega_postinor = NULL";
			}

			if ($parametros['frm_hepatitisB']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_hepatitisB = '{$parametros['frm_hepatitisB']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_hepatitisB = NULL";
			}

			if ($parametros['frm_pertinencia']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_pertinencia = '{$parametros['frm_pertinencia']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_pertinencia = NULL";
			}

			if ($parametros['resultado']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_resultado = '{$parametros['resultado']}'";
			}

			if ($parametros['frm_nro']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_numero_frasco = '{$parametros['frm_nro']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_numero_frasco = NULL";
			}

			if ($parametros['horaAcoholemia']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_fecha = '{$parametros['horaAcoholemia']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_fecha = NULL";
			}

			if ($parametros['frm_profesional_alcoholemia']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_medico = '{$parametros['frm_profesional_alcoholemia']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_medico = NULL";
			}

			if ($parametros['frm_observacion_alcoholemia']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_apreciacion = '{$parametros['frm_observacion_alcoholemia']}'";
			}else{
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_alcoholemia_apreciacion = NULL";
			}

			if ($parametros['radio_egreso'] && $parametros['radio_egreso'] != "5") {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" est_id = '{$parametros['radio_egreso']}'";

				if ($parametros['dau_cierre_administrativo']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" dau_cierre_administrativo = '{$parametros['dau_cierre_administrativo']}'";
				}

				if ($parametros['dau_cierre_administrativo']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" dau_cierre_administrativo = '{$parametros['dau_cierre_administrativo']}'";
				}

				if ($parametros['reg_usuario_insercion']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" dau_cierre_administrativo_usuario = '{$parametros['reg_usuario_insercion']}'";
				}

				if ($parametros['frm_fecha_egreso_adm']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" dau_cierre_administrativo_fecha = NOW()";
				}

				if ($parametros['fecha_cierre_final']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" dau_cierre_fecha_final = NOW()";
				}

			}
			else {
				if ($parametros['frm_est_id']) {
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" est_id = '{$parametros['frm_est_id']}'";
				}else{
					$condicion .= ($condicion == "") ? " SET " : " , ";
					$condicion.=" est_id = '{$parametros['frm_estado_cierre']}'";
				}


			}

			if ($parametros['frm_indicacion_egreso']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_indicacion_egreso = '{$parametros['frm_indicacion_egreso']}'";
			}
			else if ($parametros['frm_indicacion_egreso_h']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_indicacion_egreso = '{$parametros['frm_indicacion_egreso_h']}'";
			}

			if ($parametros['frm_servicio']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_servicio = '{$parametros['frm_servicio']}'";
			}
			else if ($parametros['frm_servicio_h']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_servicio = '{$parametros['frm_servicio_h']}'";
			}

			if ($parametros['frm_motivo_egreso']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_administrativo_observacion = '{$parametros['frm_motivo_egreso']}'";
			}

			if ($parametros['frm_fallecimiento_fecha']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_defuncion_fecha = '{$parametros['frm_fallecimiento_fecha']}'";
			}
			else if ($parametros['frm_fallecimiento_fecha_h']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_defuncion_fecha = '{$parametros['frm_fallecimiento_fecha_h']}'";
			}

			if ($parametros['reg_usuario_defuncion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_defuncion_usuario = '{$parametros['reg_usuario_defuncion']}'";
			}

			if ($parametros['frm_destionos_h']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_atl_der_id = '{$parametros['frm_destionos_h']}'";
			}
			if ($parametros['especialidad']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_ind_especialidad = '{$parametros['especialidad']}'";
			}
			if ($parametros['aps']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_ind_aps = '{$parametros['aps']}'";
			}
			if ($parametros['frm_otros']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_ind_otros = '{$parametros['frm_otros']}'";
			}
			if ($parametros['frm_sum_indicacion']) {
				$condicion .= ($condicion == "") ? " SET " : " , ";
				$condicion.=" dau_cierre_des_id = '{$parametros['frm_sum_indicacion']}'";
			}
			$sql .= $condicion." WHERE dau_id = {$parametros['Iddau']}";
			$response = $objCon->ejecutarSQL($sql, "ERROR AL CERRAR DAU");
			return $response;
	}



	function vaciarCamaCierre($objCon, $parametros){
		$sql=" UPDATE dau.cama ";
		$condicion = "";


		if ($parametros['frm_est_id']==3 || $parametros['frm_est_id']==4 || $parametros['frm_est_id']==8 || $parametros['frm_est_id']==7) {
			$condicion .= ($condicion == "") ? " SET " : " , ";
			$condicion.=" dau_id = NULL, est_id = 10, cam_fecha_desocupada = NOW()";
		}

		$sql .= $condicion." WHERE dau_id = {$parametros['Iddau']}";
		$response = $objCon->ejecutarSQL($sql, "ERROR AL VACIAR CAMA CIERRE");
		return $response;
	}



	function listarCondicionIngreso($objCon){
		$sql="SELECT
			condicion_ingreso.con_ingreso_id,
			condicion_ingreso.con_ingreso_nombre
		FROM
			dau.condicion_ingreso";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR CONDICION INGRESO<br>");
		return $datos;
	}



	function listarPronostico($objCon){
		$sql="	SELECT
		pronostico.pro_pronostico_id,
		pronostico.pro_pronostico_nombre
		FROM
		dau.pronostico";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR ATENDIDO POR<br>");
		return $datos;
	}




	function listarTratamiento($objCon){
		$sql="	SELECT
		tratamiento.tra_tratamiento_id,
		tratamiento.tra_tratamiento_nombre
		FROM
		dau.tratamiento";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR TRATAMIENTO<br>");
		return $datos;
	}



	function listarAtendidoPor($objCon){
		$sql="SELECT
		atendido_por.ate_atendidopor_id,
		atendido_por.ate_atendidopor_nombre
		FROM
		dau.atendido_por";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR ATENDIDO POR<br>");
		return $datos;
	}



	function listarTurno($objCon){
		$sql="SELECT
		turno.tur_turno_id,
		turno.tur_nombre
		FROM
		dau.turno";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR TURNO<br>");
		return $datos;
	}



	function listarTipoAtencion($objCon){
		$sql="SELECT
		atencion.ate_id,
		atencion.ate_descripcion
		FROM
		dau.atencion
		where ate_id != 3";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR TIPO ATENCION<br>");
		return $datos;
	}

}
?>