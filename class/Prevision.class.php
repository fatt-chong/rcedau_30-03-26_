<?php
	class Prevision{

		function listarPrevision($objCon,$parametros){
			// $objCon->db_select("paciente");
			$sql="SELECT prevision,id
				  from paciente.prevision
				  ORDER BY prevision ASC";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Prevision<br>");
			return $datos;
		}



		function listarPrevisionSinFonasa($objCon){
			// $objCon->db_select("paciente");
			$sql="SELECT prevision,id from paciente.prevision WHERE prevision.id NOT IN (0, 1, 2, 3)";
			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Prevision<br>");
			return $datos;
		}

	}
?>