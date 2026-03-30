<?php
	class funcionesAgenda{

		function contadorCitas($objCon,$parametros){
			  $sql="SELECT cita.CITcodigo 
				  FROM   agenda.cita
				  WHERE  cita.PACidentificador = '{$parametros['frm_id_paciente']}' AND cita.CITestado_cita IN (15,11)"; 
	    	$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
			return $datos;
		}



		function contadorInterconsultas($objCon,$parametros){
			$sql ="SELECT interconsulta.INTcodigo
    			   FROM   agenda.interconsulta
    			   WHERE  interconsulta.PACidentificador = '{$parametros['frm_id_paciente']}' AND interconsulta.INTestado IN (1, 2)"; 
    		$datos = $objCon->consultaSQL($sql,"<br>Error al listar Tabla Paciente<br>");
			return $datos;
		}
			
	}		
?>