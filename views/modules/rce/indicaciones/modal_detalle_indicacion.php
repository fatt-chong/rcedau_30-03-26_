<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 			$objCon      				= new Connection; $objCon->db_connect();
require_once('../../../../class/RegistroClinico.class.php'); 		$objRce 					= new RegistroClinico;
require_once('../../../../class/Imagenologia.class.php'); 	  		$objImagenologia 			= new Imagenologia;
require_once('../../../../class/Laboratorio.class.php'); 			$objLaboratorio 			= new Laboratorio;
require_once('../../../../class/Util.class.php');					$objUtil 					= new Util;

$parametros             		= $objUtil->getFormulario($_POST);
$parametros['arreglo_id_tipo']	= explode('-',$parametros['sol_id']);
$parametros['solicitud_id']		= $parametros['arreglo_id_tipo'][0];
$parametros['tipo_id']			= $parametros['arreglo_id_tipo'][1];

if ( $parametros['tipo_id'] == 2 || $parametros['tipo_id'] == 4 || $parametros['tipo_id'] == 6 || $parametros['tipo_id'] == 8) {

	$indicaciones 				= $objRce->listarIndicaciones($objCon,$parametros);

}

if ( $parametros['tipo_id'] == 1 ) {

	$indicaciones = $objImagenologia->listarIndicacionesImagenologia($objCon,$parametros);

}

if ( $parametros['tipo_id'] == 3 ) {
	$listarIndicaciones 						= $objLaboratorio -> listarIndicacionesLaboratorio($objCon,$parametros);
	$parametrosExamen['regId'] 					= $listarIndicaciones[0]['regId'];
	$parametrosExamen['tubo_id'] 				= $listarIndicaciones[0]['tubo_id'];
	$parametrosExamen['sol_lab_fechaInserta'] 	= $listarIndicaciones[0]['sol_lab_fechaInserta'];
	$listarIndicacionesporTubo 					= $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
}

$version  = $objUtil->versionJS();
?>

<style>
	.form-group {
		margin-bottom: 4px;
	}
</style>

<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/modal_detalle_indicacion.js?v=<?=$version;?>"></script>




<!--
################################################################################################################################################
                                                        DESPLIGUE DETALLES DE INDICACIONES
-->
<div id="contenidoImagenologia">

	<!-- Campos Ocultos -->
	<input type="hidden" id="dau_id" 		 name="dau_id" 			value="<?=$dau_id?>">
	<input type="hidden" id="solicitud_id" 	 name="solicitud_id" 	value="<?=$parametros['solicitud_id']?>">
	<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" 	value="<?=$parametros['tipo_id'];?>">

	<?php
	switch ($parametros['tipo_id']) {

		//Detalles de Laboratorio
		case '3':

			$detalleQuimica 			= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],1);
			$detalleHemato 				= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],3);
			$detalleOrina	 				= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],4);
			$detalleGases	 				= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],10);
			$detalleDeposiciones 	= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],11);
			$detalleLiquidos			= $objLaboratorio->listarExamenesSeccion($objCon,$parametros['solicitud_id'],12);

			?>

			<!-- <div align="center" class="col-md-12 col-centered" style="margin-top: -10px;padding: 8px;width: 100%;"> -->

				<?php
				//Químicos
				if (isset($detalleQuimica[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleQuimica, "Químico",$listarIndicacionesporTubo);
				}

				//Hematológico
				if (isset($detalleHemato[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleHemato, "Hematológicos",$listarIndicacionesporTubo);
				}

				//Orina
				if (isset($detalleOrina[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleOrina, "Orina",$listarIndicacionesporTubo);
				}

				//Gases
				if (isset($detalleGases[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleGases, "Gases",$listarIndicacionesporTubo);
				}

				//Deposiciones
				if (isset($detalleDeposiciones[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleDeposiciones, "Deposiciones",$listarIndicacionesporTubo);
				}

				//Líquidos
				if (isset($detalleLiquidos[0]['det_lab_codigo'])) {
					desplegarDetallesLaboratorio($detalleLiquidos, "Líquidos",$listarIndicacionesporTubo);
				}
				?>

			<!-- </div> -->

		<?php
		break;

		//Otros Detalles
		default:
		?>
		<div class="bd-callout bd-callout-warning ">
        <div class="row pr-2 pl-2">
            <div class="col-lg-2 ">
                <p class="m-0 p-0 mifuente">Tipo</p>
            </div>
            <div class="col-lg-4 ">
                <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$indicaciones[0]['ser_descripcion']?></label></p>
            </div>
            <div class="col-lg-2 ">
                <p class="m-0 p-0 mifuente">Estado</p>
            </div>
            <div class="col-lg-4 ">
                <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$indicaciones[0]['est_descripcion']?></label></p>
            </div>
            <div class="col-lg-2 mt-2">
                <p class="m-0 p-0 mifuente">Examen</p>
            </div>
            <div class="col-lg-10 mt-2">
                <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$indicaciones[0]['descripcion']?><?php
				if ( ! empty($indicaciones[0]['descripcionClasificacion']) && ! is_null($indicaciones[0]['descripcionClasificacion']) ) {
				?>
					<label class="mifuente"> ( <?=$indicaciones[0]['descripcionClasificacion']?> ) </label>
				<?php
				}
				?></label></p>
            </div>
        </div>
		<?php
		break;

	}
	?>

	<div id="contenidoTrazabilidad"></div>

	<div id="contenidoHistorialCancelacion"></div>

</div>



<!--
################################################################################################################################################
                                                                    FUNCIONES PHP
-->
<?php

function desplegarDetallesLaboratorio($objLaboratorio, $titulo,$listarIndicacionesporTubo){
?>
<div class="bd-callout bd-callout-warning ">
	<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <svg class="svg-inline--fa fa-circle-notch fa-w-16 text-danger mr-2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-notch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"></path></svg><?php echo $titulo; ?></h6>
	<?php for($i=0;$i<count($listarIndicacionesporTubo);$i++){ 
		if($listarIndicacionesporTubo[$i]['sol_lab_estado'] == 6 || $listarIndicacionesporTubo[$i]['sol_lab_estado'] == 8){ $icono ='<i class="fas fa-vial text-danger mr-2 mifuente20"></i>';}else{ $icono='<i class="fas fa-vial text-primary mr-2 mifuente20"></i>';}
		?>
	<div class="row pr-2 pl-2 mb-1 ">
        <div class="col-lg-2 ">
            <p class="m-0 p-0 mifuente"><?=$icono?>Prestación</p>
        </div>
        <div class="col-lg-3 ">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$listarIndicacionesporTubo[$i]['det_lab_codigo']?></label></p>
        </div>
        <div class="col-lg-3 ">
            <p class="m-0 p-0 mifuente">Descripción de Exámenes</p>
        </div>
        <div class="col-lg-4 ">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$listarIndicacionesporTubo[$i]['det_lab_descripcion']?></label></p>
        </div>
	</div>
	<?php } ?>
</div>
<?php
}
?>
