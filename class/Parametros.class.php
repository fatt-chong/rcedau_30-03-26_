<?php
	class Parametros{
		function getParametros($objCon,$parametros){
			$sql="SELECT *
				FROM
				dau.parametros";
			if ($parametros['tipo_parametros']) {
                $condicion .= ($condicion == "") ? " WHERE " : " AND ";
                $condicion .= "parametros.tipo_parametros = '{$parametros['tipo_parametros']}' ";
            }if ($parametros['id_parametros']) {
                $condicion .= ($condicion == "") ? " WHERE " : " AND ";
                $condicion .= "parametros.id_parametros = '{$parametros['id_parametros']}' ";
            }
            $sql        .= $condicion." order by id_parametros asc";
            $datos = $objCon->consultaSQL($sql,"<br>Error al listar los Datos del Paciente");
            return $datos;
		}
	}
?>