<?php
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 		$objCon      		= new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');				$objUtil 			= new Util;
require_once('../../../../class/AltaUrgencia.class.php');		$objAltaUrgencia    = new AltaUrgencia;
$parametros        = $objUtil->getFormulario($_POST);
$respuestaConsulta = $objAltaUrgencia->obtenerDatosIndicacionAltaUrgencia($objCon, $parametros['SAUid']);
?>
<!--
################################################################################################################################################
                                                        DESPLIGUE DETALLE ALTA URGENCIA
-->
<div id="contenidoIndicacionAltaUrgencia" class=" m-3">
    <!-- CIE 10 -->
	<div class="row">
		<!-- <div class="col-md-12"> -->
	    	<div class="form-group col-sm-2">
	    		<label class="text-secondary"><svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="color: #59a9ff;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg> CIE10</label>
			</div>
			<div class="form-group col-sm-10">
				<label class="label2 mifuente"><?=$respuestaConsulta[0]['SAUidCie10'].' - '.$respuestaConsulta[0]['descripcionCIE10']?></label>
			</div>
		<!-- </div> -->
    </div>
    <!-- CIE 10 Abierto -->
    <div class="row">
		<!-- <div class="col-md-12"> -->
	    	<div class="form-group col-sm-2">
	    		<label class="text-secondary"><svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="color: #59a9ff;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg> CIE10 Abierto</label>
			</div>
			<div class="form-group col-sm-10">
				<label class="label2 mifuente"><?=$respuestaConsulta[0]['SAUcie10Abierto']?></label>
			</div>
		<!-- </div> -->
    </div>
    <!-- Indicaciones -->
    <div class="row">
		<!-- <div class="col-md-12"> -->
	    	<div class="form-group col-sm-2">
	    		<label class="text-secondary"><svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="color: #59a9ff;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>Indicaciones</label>
			</div>
			<div class="form-group col-sm-10">
				<label class="label2 mifuente"><?=$respuestaConsulta[0]['SAUindicaciones']?></label>
			</div>
		<!-- </div> -->
    </div>
    <!-- Indicación Egreso -->
    <div class="row">
		<!-- <div class="col-md-12"> -->
	    	<div class="form-group col-sm-2">
	    		<label class="text-secondary"><svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="color: #59a9ff;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>Indicación Egreso</label>
			</div>
			<div class="form-group col-sm-10">
				<label class="label2 mifuente"><?=$respuestaConsulta[0]['descripcionIndicacionEgreso']?></label>
			</div>
		</div>
    <!-- </div> -->
</div>