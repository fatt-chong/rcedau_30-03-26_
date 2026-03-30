<?php
class MotivoConsulta{

	function listarMotivo($objCon,$parametros){			
		$sql="SELECT
		motivo_consulta.mot_id,
		motivo_consulta.mot_descripcion
		FROM
		dau.motivo_consulta";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Nacionalidad<br>");
		return $datos;
	}

}
?>