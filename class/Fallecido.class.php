<?php
	class Fallecido{
		function grabar_estado($objCon, $parametros){
			$sql="INSERT INTO  paciente.fallecido (fal_id_paciente,
										  fal_reporta,
										  fal_fecha_notificacion,
										  fal_fecha_difuncion,
										  fal_hora_difuncion,
										  fal_fecha_ingreso,
										  fal_observacion,
										  fal_usuario)
				  VALUES ('{$parametros['frm_id_paciente']}',
				  		  '{$parametros['frm_reporta']}',
				  		  '{$parametros['frm_notificacion']}',
				  		  '{$parametros['frm_fechaDefuncion']}',
				  		  '{$parametros['frm_hora']}',
				  		  '{$parametros['frm_fechaIngreso']}',
				  		  '{$parametros['frm_observacion']}',
				  		  '{$parametros['reg_usuario_insercion']}')";
				  $response = $objCon->ejecutarSQL($sql, "Error al Insertar Paciente Fallecido");
				  return $response;
		} 
	}
?>