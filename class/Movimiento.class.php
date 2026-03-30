<?php
class Movimiento{

	function guardarMovimiento($objCon, $parametros){
		$sql="INSERT INTO dau.dau_movimiento(dau_id,
				dau_mov_descripcion,
				dau_mov_fecha,
				dau_mov_usuario,
				dau_mov_tipo,
				ip
				)
				VALUES(
						'{$parametros['dau_id']}',
					   	'{$parametros['dau_mov_descripcion']}',
					    NOW(),
					   	'{$parametros['dau_mov_usuario']}',
					   	'{$parametros['dau_mov_tipo']}',
					   	'{$_SERVER['REMOTE_ADDR']}'
						)
						";

		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Movimiento DAU");
		return $response;
	}



	//Guardar los movimientos del paciente en funcion a las camas.
	function guardarMovimientoCama($objCon, $parametros){
		$sql="INSERT INTO dau.dau_movimiento_cama (
				dau_id,
				cam_id,
				sal_id,
				sal_descripcion,
				cam_descripcion,
				dau_mov_cama_fecha_ingreso,
				dau_mov_cama_usuario_ingreso,
				dau_mov_cama_estado
				)
			VALUES
				(
					{$parametros['dau_id']},
					{$parametros['cam_id']},
					{$parametros['sal_id']},
					'{$parametros['sal_descripcion']}',
					'{$parametros['cam_descripcion']}',
					NOW(),
					'{$parametros['dau_mov_cama_usuario_ingreso']}',
					'{$parametros['dau_mov_cama_estado']}'
				)";

		$response = $objCon->ejecutarSQL($sql, "Error al Insertar Movimiento CAMA DAU");
		return $response;
	}



	//Obtener ultimo id de dau_mov_cama del paciente.
	function getIdDauMovimientoCama($objCon, $parametros){
		$sql = "SELECT
					MAX(id_dau_mov_cama) AS id
				FROM
					dau.dau_movimiento_cama
				WHERE
					dau_id = {$parametros['dau_id']}
				AND dau_mov_cama_fecha_egreso IS NULL
				AND dau_mov_cama_usuario_egreso IS NULL";
		$datos = $objCon->consultaSQL($sql, "<br>Error al obtener los datos del paciente que se esta consultando<br>");
		return $datos;
	}



	//Acutalizar el estado de la ultima cama utilizada por el paciente.
	function actualizarMovimientoCama($objCon, $parametros){
		$sql = "UPDATE dau.dau_movimiento_cama
				SET dau_movimiento_cama.dau_mov_cama_fecha_egreso = NOW(),
				 dau_movimiento_cama.dau_mov_cama_usuario_egreso = '{$parametros['dau_mov_cama_usuario_egreso']}',
				 dau_movimiento_cama.dau_mov_cama_estado = '{$parametros['dau_mov_cama_estado']}'
				WHERE
					dau_movimiento_cama.id_dau_mov_cama = {$parametros['id_ultimoMovCam']}";
		$response = $objCon->ejecutarSQL($sql, "Error al Actualizar el movimiento de egreso de cama.");
	}



	function guardarMovimientoRCE($objCon,$parametros){
		$parametros['SIC_id_rayos'] =
			(!is_null($parametros['SIC_id_rayos']) && !empty($parametros["SIC_id_rayos"]))
				?  $parametros["SIC_id_rayos"]
				: "null";

		$parametros['id_solicitud_dalca'] =
			(!is_null($parametros['id_solicitud_dalca']) && !empty($parametros["id_solicitud_dalca"]))
				? $parametros["id_solicitud_dalca"]
				: "null";

		 $sql="
			INSERT INTO
				dau.dau_movimiento_rce(
					dau_id,
					rce_id,
					rce_sol_id,
					sol_ind_id,
					sol_tipo_id,
					sol_ind_est_id,
					dau_mov_rce_accion,
					dau_mov_rce_fecha,
					dau_mov_rce_usuario,
					SIC_id_rayos,
					id_solicitud_dalca,
					movimiento_enfermeria,
					dau_observacion_rce
				)
			VALUES(
				{$parametros['dau_id']},
				{$parametros['rce_id']},
				{$parametros['solicitud_id']},
				{$parametros['indicacion_id']},
				{$parametros['tipo']},
				{$parametros['estado_indicacion_rce']},
				'{$parametros['dau_mov_descripcion']}',
				NOW(),
				'{$parametros['dau_mov_usuario']}',
				{$parametros['SIC_id_rayos']},
				{$parametros['id_solicitud_dalca']},
				'{$parametros['movimiento_enfermeria']}',
				'{$parametros['observacion_rce']}'
			)
		";

		return $objCon->ejecutarSQL($sql, "Error al guardarMovimientoRCE");
	}

}
?>
