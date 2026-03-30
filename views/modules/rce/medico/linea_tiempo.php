<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
require_once ("../../../../class/Util.class.php"); 			$objUtil       = new Util;
require_once("../../../../class/Dau.class.php" );  			$objDau 	   = new Dau;
require_once("../../../../class/Connection.class.php"); 	$objCon        = new Connection();
require_once('../../../../class/Config.class.php');      	$objConfig 	   = new Config;
require_once('../../../../class/Rce.class.php'); 			$objRce        = new Rce;
require("../../../../config/config.php");

$permisos = $_SESSION['permisosDAU'.SessionName];
$objCon->db_connect();
$parametros                    = $objUtil->getFormulario($_POST);
$dau_id                        = $_POST['dau_id'];
$datosDAU                      = $objDau->ListarPacientesDau($objCon, $parametros);
$datosDAUPaciente              = $objDau->buscarListaPaciente($objCon,$parametros);
// print_r("<pre>"); print_r($$datosDAU[0]); print_r("</pre>");
$buscarCamaYsala               = $objDau->buscarCamaYsala($objCon,$parametros);
$listarIndicaciones            = $objDau->listarIndicaciones($objCon,$parametros);
$obtenerEstadosIndicaciones    = $objDau->obtenerEstadosIndicaciones($objCon,$parametros);
$obtenerIndicacionEgreso       = $objDau->obtenerIndicacionEgreso($objCon,$parametros);
$obtenerEstadosIndicacionesDau = $objDau->obtenerEstadosIndicacionesDau($objCon,$parametros);
$datosLT                       = $objDau->ListarPacienteLineaTiempo($objCon, $parametros);
$idRce                         = $objRce->obtenerIdRCESegunDAU($objCon, $_POST['dau_id']);
$version   					   = $objUtil->versionJS();

// $existeSolicitudAltaUrgencia   = $objDau->existeSolicitudAltaUrgencia($objCon, $idDAU);
if ( $objDau->pacienteTieneIndicacionAlta($objCon, $dau_id) ) {
	$pacienteTieneIndicacionAlta = '1';
}

unset($_SESSION['datosPacienteDau']);
$_SESSION['datosPacienteDau']  = $datosDAU[0];

$nombre = $_SESSION['MM_Username'.SessionName];

$rut = $_SESSION['MM_RUNUSU'.SessionName];

if ( isset($_SESSION['usuarioActivo']) ) {

	$nombre = $_SESSION['usuarioActivo']['usuario'];
	
	$rut = $_SESSION['usuarioActivo']['rut'];
	
}

$usuarioMarcaAgua = strtoupper (substr($nombre, 0, 3)."".substr($rut,-3));





$transexual_bd   = $datosDAUPaciente[0]['transexual'];
$nombreSocial_bd = $datosDAUPaciente[0]['nombreSocial'];
$nombrePaciente  = $datosDAUPaciente[0]['nombres'].' '.$datosDAUPaciente[0]['apellidopat'].' '.$datosDAUPaciente[0]['apellidomat'];
$nombreLabel      = 'Nombre';

$infoInputLabel  = $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'');
?>

<style>
.timeline-steps {
    display: flex;
    justify-content: center;
    flex-wrap: wrap
}

.timeline-steps .timeline-step {
    align-items: center;
    display: flex;
    flex-direction: column;
    position: relative;
    margin: 1rem
}

@media (min-width:768px) {
    .timeline-steps .timeline-step:not(:last-child):after {
        content: "";
        display: block;
        border-top: .25rem dotted #3b82f6;
        width: 3.46rem;
        position: absolute;
        left: 7.5rem;
        top: .3125rem
    }
    .timeline-steps .timeline-step:not(:first-child):before {
        content: "";
        display: block;
        border-top: .25rem dotted #3b82f6;
        width: 3.8125rem;
        position: absolute;
        right: 7.5rem;
        top: .3125rem
    }
}

.timeline-steps .timeline-content {
    width: 10rem;
    text-align: center
}

.timeline-steps .timeline-content .inner-circle {
    border-radius: 1.5rem;
    height: 1rem;
    width: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #3b82f6
}

.timeline-steps .timeline-content .inner-circle:before {
    content: "";
    background-color: #3b82f6;
    display: inline-block;
    height: 3rem;
    width: 3rem;
    min-width: 3rem;
    border-radius: 6.25rem;
    opacity: .5
}
	/* css para hacer el scroll de las tablas */
/*	tbody {
	    display:block;
	}
	thead, tbody tr{
	    display:table;
	    width:100%;
	    table-layout:fixed;
	}
	thead {
	    width: calc( 100% - 1.1em );
	}
	th{
		height: 1px;
	}*/

	/* css para la linea de tiempo de atencion*/
	.timeline {
	    list-style-type: none;
	    display: flex;
	}
	.timestamp {
		margin-top: 0px;
	    margin-bottom: 15px;
	    display: flex;
	    flex-direction: column;
	    align-items: center;
	    font-weight: 100;
	}
	.author{
		font-size: 12px;
	}
	.status {
	    padding: 10px ;
	    display: flex;
	    justify-content: center;
	    border-top: 4px solid #A2C3DA;
	    position: relative;
	    align-items: center;
	}
	.done {
	    padding: 10px ;
	    display: flex;
	    justify-content: center;
	    border-top: 4px solid #176b87;
	    position: relative;
	    align-items: center;
	    color: #176b87;
	}
	.status:before {
	    content: "";
	    width: 25px;
	    height: 25px;
	    background-color: white;
	    border-radius: 25px;
	    border: 1px solid #ddd;
	    position: absolute;
	    top: -15px;
	    left: 42%;
	}
	.done:before {
	    content: "";
	    width: 25px;
	    height: 25px;
	    background-color: #176b87;
	    border-radius: 25px;
	    border: 1px solid #176b87;
	    position: absolute;
	    top: -15px;
	    left: 42%;
	}
	.transcurrido {
	   	position: absolute;
	    padding-left: 145px;
	    margin-top: 80px;
	    font-size: 11px;
	}
	/*.espera{
	    width: 265px;
	   	text-align: center;
	}
	.prueba.done:before{
		background-color: red;
	}

	.form-group {
		margin-bottom: 4px;
	}
	
	body {

		background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='100px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(231, 226, 226)' font-size='20' ><?php echo $usuarioMarcaAgua; ?></text></svg>");

	}*/
</style>
<div class="row mt-2 mb-3">
						
					</div>
					<div class="timeline row m-1 mb-3 m-3" id="timeline">
						<!-- <div class="row"> -->
							<div class="col-lg-12 mt-3">
								<label class="text-secondary"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Tiempos de Atención</label>
							</div>
						<!-- </div> -->
						<?php for ($x=0; $x < 6 ; $x++) {
							if ($datosLT[$x]['estado'] == 1) {
								$estlt = "Admisión";
							}else if ($datosLT[$x]['estado'] == 2){
								$estlt = "Categorización";
							}else if ($datosLT[$x]['estado'] == 3){
								$estlt = "Inicio Atención";
							}else if ($datosLT[$x]['estado'] == 4){
								$estlt = "Indicación Egreso";
							}else if ($datosLT[$x]['estado'] == 5){
								if ($_SESSION['datosPacienteDau']['est_id'] == 5) {
									$estlt = "Cierre";
								}
							}else if ($datosLT[$x]['estado'] == 6){
								if ($_SESSION['datosPacienteDau']['est_id'] == 6) {
									$estlt = "Cierre: Anula";
								}else if ($_SESSION['datosPacienteDau']['est_id'] == 7){
									$estlt = "Cierre: N.E.A.";
								}else if ($_SESSION['datosPacienteDau']['est_descripcion	'] == 5){
									$estlt = "Cierre: Administrativo";
								}
							}else if ($datosLT[$x]['estado'] == 8){
								$estlt = "Ingreso Box";
							}?>
							<li class="li col"  style="padding-right: 0px;padding-left: 0px;">
								<div align="center" class="timestamp">
									<span class="author">
										<?php if($datosLT[$x]['usuario'] != null){?>
											<th style="font-weight: normal;"><?=$datosLT[$x]['usuario'];?></th>
										<?php }else{
												echo "<br>";
										}?>
									</span>
									<span class="date mifuente12 text-secondary">
										<?php if($datosLT[$x]['fecha'] != null){ ?>
											<th class="mifuente12">
												<?=date("d-m-Y H:i",strtotime($datosLT[$x]['fecha']));?>
											</th>
										<?php }else{
											echo "<br>";
										}?>
									<span>
								</div>
								<?php if($datosLT[$x]['usuario'] != null) {?>
								<div align="center" class="done">
									<h5 style="font-size: 11px;"><?=$estlt?></h5>
								</div>
								<?php }else{?>
								<div align="center" class="status">
									<h5></h5>
								</div>
								<?php }?>
							</li>
						<?php }?>
					</div>
					<br><br>