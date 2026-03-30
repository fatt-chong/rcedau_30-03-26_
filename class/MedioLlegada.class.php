<?php
class Mediollegada{

	function listarMediollegada($objCon,$parametros){			
		$sql="SELECT
		medio_llegada.med_id,
		medio_llegada.med_descripcion
		FROM
		dau.medio_llegada
		ORDER BY
			medio_llegada.med_descripcion";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Nacionalidad<br>");
		return $datos;
	}

}
?>