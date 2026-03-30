<?php
	class Atencion{

		function listarAtencion($objCon){
			$sql="SELECT
				atencion.ate_id,
				atencion.ate_descripcion
				FROM
				dau.atencion";

			$datos = $objCon->consultaSQL($sql,"<br>ERROR AL ATENCION<br>");
			return $datos;

		}

	}
?>