<?php
	class Convenio{

		function listarConvenio($objCon,$parametros){
			// $objCon->db_select("recauda");
			$sql="SELECT
			institucion.instCod,
			institucion.instNombre,
			institucion.instFonasa,
			institucion.instParticular,
			institucion.instIsapre,
			institucion.instPensionado
			FROM
			recauda.institucion
			ORDER BY instNombre ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Convenio<br>");
			return $datos;
		}
	}
?>