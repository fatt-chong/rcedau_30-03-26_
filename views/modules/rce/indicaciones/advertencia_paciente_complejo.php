<?php
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require_once('../../../../class/Util.class.php'); $objUtil = new Util;
?>

<script>
	
	validar("#frm_observacion","letras_numeros_caracteres");

</script>

<form id="frm_cambioPacienteComplejo" class="formularios" name="frm_cambioPacienteComplejo" role="form" method="POST">
	
	<div class="row">
	
		<div class="col-md-12">
	
			<div class="alert alert-danger">
	
				<strong>¡ATENCIÓN!</strong> Al activar la opción de PACIENTE COMPLEJO se habilitarán todos los exámenes disponibles por el Hospital, esta acción es irreversible 
	
			</div>
	
		</div>		
	
	</div>

	<div class="row">	
	
		<!-- <div class="col-md-12"> -->
	
			<div class="form-group col-sm-12">
	
				<label class="encabezado pull-left">Observación</label>
	
			</div>
	
			<div class="form-group col-sm-12" >											
	
				<textarea id="frm_observacion" name="frm_observacion" class="form-control mifuente" rows="2"></textarea>
	
			</div>
	
		<!-- </div>										 -->
	
	</div>

</form>