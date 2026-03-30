<?php
	class Localidad{

		function listarRegiones($objCon){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    REG_Id,
                        REG_Descripcion
					FROM 
					    paciente.region
					ORDER BY 
                        REG_Id";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Regiones (Base Datos Paciente)<br>");
			return $datos;
		}
		


		function listarCiudades($objCon){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    CIU_Id,
                        CIU_Descripcion
				    FROM 
					    paciente.ciudad
					ORDER BY 
                        CIU_descripcion";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Ciudades (Base Datos Paciente)<br>");
            return $datos;
		}
		


		function listarCiudadesPorRegion($objCon, $regId){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    CIU_Id,
                        CIU_Descripcion
				    FROM 
					    paciente.ciudad
                    WHERE
                        paciente.ciudad.REG_Id = '{$regId}'
					ORDER BY 
                        CIU_descripcion";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Ciudades (Base Datos Paciente)<br>");
            return $datos;
		}
		


		function listarComunas($objCon){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    id,
                        comuna
				    FROM 
					    paciente.comuna
					ORDER BY 
                        comuna";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Ciudades (Base Datos Paciente)<br>");
            return $datos;
		}
		


		function listarComunasPorRegion($objCon, $ciuId){
			// $objCon->db_select("paciente");
			$sql="  SELECT 
					    id,
                        comuna
				    FROM 
					    paciente.comuna
                    WHERE
                        paciente.comuna.CIU_Id = '{$ciuId}'
					ORDER BY 
                        comuna";

			$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla de Ciudades (Base Datos Paciente)<br>");
            return $datos;
        }
	}
?>