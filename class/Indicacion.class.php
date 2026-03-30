<?php
class Indicacion{

	function listarIndicacion($objCon){

		$sql="SELECT
			indicacion_egreso.ind_egr_id,
			indicacion_egreso.ind_egr_descripcion
		FROM
			dau.indicacion_egreso";

		$datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR INDICACION<br>");
		return $datos;

		}

	}
?>