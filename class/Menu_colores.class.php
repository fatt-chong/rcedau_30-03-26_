<?php
// error_reporting(0);
class Menu_colores{
	function SelectMenu_colores($objCon,$parametros){
		$sql="SELECT *
				FROM
				dau.menu_colores
		WHERE menu_colores.tipo='{$parametros['tipo']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar SelectMenu_colores<br>");
		return $datos;
	}
	function SelectProfesional($objCon,$parametros){
		$sql="SELECT *
				FROM
				parametros_clinicos.profesional
		WHERE profesional.PROcodigo='{$parametros['PROcodigo']}'";
		$datos = $objCon->consultaSQL($sql,"<br>Error al listar SelectMenu_colores<br>");
		return $datos;
	}
}
?>