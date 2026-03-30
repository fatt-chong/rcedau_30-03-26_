<?php
class Servicios {

	function ListarServiciosDau($objCon){
			 $sql="SELECT
				camas.sscc.id_rau,
				dau.dau_tiene_indicacion.dau_ind_servicio,
				camas.sscc.id,
				camas.sscc.servicio,
				dau.dau_tiene_indicacion.ind_id,
				dau.dau_tiene_indicacion.dau_id
				FROM
				camas.sscc
				LEFT JOIN  dau.dau_tiene_indicacion ON camas.sscc.id_rau = dau.dau_tiene_indicacion.dau_id
				WHERE ver = 1 AND id <> 46";
			 $resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar Servicio dau<br>");
		return $resultado;
	}



	function obtenerServiciosDau($objCon,$parametros){
			$sql="SELECT
				camas.sscc.id_rau,
				dau.dau_tiene_indicacion.dau_ind_servicio,
				camas.sscc.id,
				camas.sscc.servicio,
				dau.dau_tiene_indicacion.ind_id,
				dau.dau_tiene_indicacion.dau_id
				FROM
				dau.dau_tiene_indicacion
				LEFT JOIN  camas.sscc ON camas.sscc.id_rau = dau.dau_tiene_indicacion.dau_id
				WHERE dau.dau_tiene_indicacion.dau_id={$parametros['dau_id']}";
			 $resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar Servicio dau<br>");
		return $resultado;
	}



	function obtenerServicioDau2($objCon,$parametros){ 
		$sql="SELECT
			dau.dau_tiene_indicacion.dau_ind_servicio,
			camas.sscc.id,
			camas.sscc.servicio,
			dau.dau_tiene_indicacion.ind_id,
			dau.dau_tiene_indicacion.dau_id
			FROM
			dau.dau_tiene_indicacion
			INNER JOIN camas.sscc ON dau.dau_tiene_indicacion.dau_ind_servicio = camas.sscc.id
			WHERE dau.dau_tiene_indicacion.dau_id={$parametros['dau_id']}";
		$resultado=$objCon->consultaSQL($sql,"<br>ERROR Listar Servicio dau<br>");
		return $resultado;
	}

}
?>