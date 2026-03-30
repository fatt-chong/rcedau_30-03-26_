<?php
	class Sector{

        //Listar sectores de domicilio
		function listarSectorDomicilio($objCon){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    id_sector_domiciliario,
                        descripcion_sector_domiciliario
					FROM 
					    paciente.sector_domiciliario
					ORDER BY 
                        id_sector_domiciliario";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Regiones (Base Datos Paciente)<br>");
			return $datos;
		}
		
	}
?>