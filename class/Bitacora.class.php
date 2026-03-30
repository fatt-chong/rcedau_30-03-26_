<?php
class Bitacora{
	function guardarBitacora($objCon, $parametros){
		$sql = "INSERT INTO rce.bitacora(BITid,
									BITtipo_codigo,
									BITtipo_descripcion,
									BITdatetime,
									BITusuario,
									BITdescripcion)
				VALUES(	{$parametros['BITid']},
					   	{$parametros['BITtipo_codigo']},
					   	'{$parametros['BITtipo_descripcion']}',
						NOW(),
						'{$parametros['BITusuario']}',
						'{$parametros['BITdescripcion']}')";

		$response = $objCon->ejecutarSQL($sql, "Error al Insertar BITACORA");
		return $response;
	}
	function SelectDauMovimientoEnfermeria($objCon, $parametros){
		$sql = " 
		SELECT
		    dau_movimiento_rce.sol_tipo_id AS BITtipo_codigo,
		    dau_movimiento_rce.dau_mov_rce_fecha AS BITdatetime,
		    dau_movimiento_rce.dau_mov_rce_usuario AS BITusuario,
		    detalle_indicaciones_rce.descripcion_detalle AS BITdescripcion,
		    CASE
		        WHEN dau_movimiento_rce.sol_ind_est_id = 1 THEN 'Iniciado'
		        WHEN dau_movimiento_rce.sol_ind_est_id = 4 THEN 'Aplicado'
		        WHEN dau_movimiento_rce.sol_ind_est_id = 6 THEN 'Anulado'
		        ELSE NULL
		    END AS estado_solicitud,
		    dau_movimiento_rce.dau_observacion_rce AS observacion,
		    rce.detalle_indicaciones_rce.tipo_descripcion,
		    2 as tipo
		FROM
		    dau.dau_movimiento_rce
		INNER JOIN rce.detalle_indicaciones_rce 
		    ON dau_movimiento_rce.rce_sol_id = detalle_indicaciones_rce.solicitud_id 
		    AND dau_movimiento_rce.sol_tipo_id = detalle_indicaciones_rce.tipo
		WHERE
		dau_movimiento_rce.dau_id = {$parametros['dau_id']} and 
		    dau_movimiento_rce.rce_sol_id = {$parametros['rce_sol_id']}
		    AND (
		        dau_movimiento_rce.dau_observacion_rce IS NOT NULL
		        AND dau_movimiento_rce.dau_observacion_rce <> ''
		    )
		    AND dau_movimiento_rce.movimiento_enfermeria  ='S' ";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}
	function listarBitacoraplusEnfermera($objCon, $parametros){
		 $sql = "
				 SELECT
				    dau_movimiento_rce.sol_tipo_id AS BITtipo_codigo,
				    dau_movimiento_rce.dau_mov_rce_fecha AS BITdatetime,
				    dau_movimiento_rce.dau_mov_rce_usuario AS BITusuario,
				    CASE
				        WHEN dau_movimiento_rce.sol_tipo_id = 3 THEN
				            GROUP_CONCAT(detalle_indicaciones_rce.descripcion_detalle SEPARATOR ', ')
				        ELSE detalle_indicaciones_rce.descripcion_detalle
				    END AS BITdescripcion,
				    CASE
				        WHEN dau_movimiento_rce.sol_ind_est_id = 1 THEN 'Iniciado'
				        WHEN dau_movimiento_rce.sol_ind_est_id = 4 THEN 'Aplicado'
				        WHEN dau_movimiento_rce.sol_ind_est_id = 6 THEN 'Anulado'
				        ELSE NULL
				    END AS estado_solicitud,
				    dau_movimiento_rce.dau_observacion_rce AS observacion,
				    rce.detalle_indicaciones_rce.tipo_descripcion,
				    2 as tipo
				FROM
				    dau.dau_movimiento_rce
				INNER JOIN rce.detalle_indicaciones_rce 
				    ON dau_movimiento_rce.rce_sol_id = detalle_indicaciones_rce.solicitud_id 
				    AND dau_movimiento_rce.sol_tipo_id = detalle_indicaciones_rce.tipo
				WHERE
				    dau_movimiento_rce.dau_id = {$parametros['BITid']}
				    AND (
				        dau_movimiento_rce.dau_observacion_rce IS NOT NULL
				        AND dau_movimiento_rce.dau_observacion_rce <> ''
				    )
				    AND dau_movimiento_rce.movimiento_enfermeria  ='S'
				GROUP BY
				    dau_movimiento_rce.dau_mov_rce_fecha

				UNION ALL

				SELECT
				    bitacora.BITtipo_codigo AS tipo_codigo,
				    bitacora.BITdatetime AS BITdatetime,
				    bitacora.BITusuario AS usuario,
				    bitacora.BITdescripcion AS descripcion,
				    NULL AS estado_solicitud,
				    NULL AS observacion,
				    NULL AS tipo_descripcion,
				    1 as tipo
				FROM
				    rce.bitacora
				INNER JOIN rce.bitacora_origen 
				    ON bitacora.BITtipo_codigo = bitacora_origen.BORcodigo 
				    AND bitacora_origen.BORtipo = 1
				WHERE
				    bitacora.BITid = {$parametros['BITid']}
				ORDER BY BITdatetime asc ";
		// echo $sql;
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}
	function listarBitacora($objCon, $parametros){
		$condicion 	= "";
		 $sql = "SELECT
					*
				FROM rce.bitacora
				INNER JOIN rce.bitacora_origen ON bitacora.BITtipo_codigo = bitacora_origen.BORcodigo AND bitacora_origen.BORtipo = 1";

		$condicion = " WHERE bitacora.BITid =  {$parametros['BITid']}";
		if ($parametros['BITtipo_codigo']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= "bitacora.BITtipo_codigo = {$parametros['BITtipo_codigo']}";
		}
		
		$sql 		.= $condicion ." order by BITcodigo desc";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}
	function listarBitacoraHosp($objCon, $parametros){
		$condicion 	= "";
		 $sql = "SELECT
					*
				FROM rce.bitacora
				INNER JOIN rce.bitacora_origen ON bitacora.BITtipo_codigo = bitacora_origen.BORcodigo AND bitacora_origen.BORtipo = 1";

		$condicion = " WHERE bitacora.BITid =  {$parametros['BITid']}";
		if ($parametros['BITtipo_codigo']) {
			$condicion .= ($condicion == "") ? " WHERE " : " AND ";
			$condicion .= "bitacora.BITtipo_codigo = {$parametros['BITtipo_codigo']}";
		}
		
		$sql 		.= $condicion ." order by BITcodigo asc";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
		return $datos;
	}

}
?>
