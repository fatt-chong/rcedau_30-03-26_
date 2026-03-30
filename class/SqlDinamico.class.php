<?php  
// session_start();
require(dirname(__FILE__)."/../config/config.php");
date_default_timezone_set("America/Santiago");

class SqlDinamico{

	function construirInsert($objCon, $tabla, $datos) {

		$datosFiltrados = array();
	    foreach ($datos as $clave => $valor) {
	        if ($valor !== '' && $valor !== null) {
	            $datosFiltrados[$clave] = $valor;
	        }
	    }

	    $columnas 	= implode(', ', array_keys($datosFiltrados));
	    $valores 	= implode("', '", array_values($datosFiltrados));

	    $sql 		= "INSERT INTO $tabla ($columnas) VALUES ('$valores');";
	    
	    $datos  	=  $objCon->ejecutarSQL($sql, "ERROR AL insertarAnalisisFn");
		$folio 		= $objCon->lastInsertId();
		return $folio;
	}
	function generarSelect($objCon, $tabla,  $condiciones = array() ,$order ) {

	   $condicionesSQL = "";
	    if (!empty($condiciones)) {
	        $condicionesSQL = " WHERE ";
	        $condicionesSQL .= implode(" AND ", $condiciones);
	    }

	    // Construir la consulta SQL
	    $sql = "SELECT * FROM $tabla" . $condicionesSQL.$order;

		// print_r("<pre>"); print_r($sql); print_r("</pre>");

	    // echo $sql;
	    $datos = $objCon->consultaSQL($sql,"<br>ERROR AL LISTAR generarSelect<br>");
        return $datos;
	}
	function construirUpdate($objCon, $tabla, $datos, $condiciones) {
	    // Filtrar los datos para eliminar valores vacíos o nulos
	    $datosFiltrados = array();
	    foreach ($datos as $clave => $valor) {
	        if ($valor !== '' && $valor !== null) {
	            $datosFiltrados[$clave] = $valor;
	        }
	    }

	    // Construir la lista de asignaciones
	    $asignaciones = array();
	    foreach ($datosFiltrados as $clave => $valor) {
	        $asignaciones[] = "$clave = '$valor'";
	    }
	    $asignacionesSQL = implode(', ', $asignaciones);

	    // Construir la parte de la consulta SQL para las condiciones
	    $condicionesSQL = "";
	    if (!empty($condiciones)) {
	        $condicionesSQL = " WHERE ";
	        $condicionesSQL .= implode(" AND ", $condiciones);
	    }

	    // Construir la consulta SQL
	    $sql = "UPDATE $tabla SET $asignacionesSQL$condicionesSQL";

	    // Ejecutar la consulta SQL
	    $objCon->ejecutarSQL($sql, "ERROR AL realizarUpdate");
	}
}