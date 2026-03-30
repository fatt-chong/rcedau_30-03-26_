<?php 
class Pronostico{

	function listarPronosticos($objCon){	
		$sql="SELECT PRONcodigo,PRONdescripcion FROM rce.pronostico";
		$datos = $objCon->consultaSQL($sql,"Error al listar citas");
		return $datos;
	}

}
?>