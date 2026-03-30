<?php
session_start();
	class Silla{

		function getSillasDialisis($objCon, $parametros){
			$condicion = "";
			$sql="SELECT * 
			FROM
			dau.sala WHERE dau.sala.sal_tipo = 'A'  ";
			$sql .= $condicion;
			$datos = $objCon->consultaSQL($sql, "Error al getSalasDialisis");
			return $datos;
		}

	}
?>