<?php
	class Nacionalidad{

		function listarNacionalidad($objCon,$parametros){
			// $objCon->db_select("paciente");
			$sql="SELECT 
					NACcodigo,
					NACdescripcion,
					NACpais 
					FROM 
					paciente.nacionalidadavis
					ORDER BY NACdescripcion";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Nacionalidad<br>");
			return $datos;
		}
		
	}
?>