<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
$permisos = $_SESSION['permiso'.SessionName];
require_once ("../../../../class/Util.class.php"); 			$objUtil       = new Util;
require_once("../../../../class/Dau.class.php" );  			$objDau 	   = new Dau;
require_once("../../../../class/Connection.class.php"); 	$objCon        = new Connection();
require_once('../../../../class/Config.class.php');      	$objConfig 	   = new Config;
require_once('../../../../class/Rce.class.php'); 			$objRce        = new Rce;
// require("../../../../config/config.php");

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
$pacienteTieneIndicacionAlta  = "";
if ( $objDau->pacienteTieneIndicacionAlta($objCon, $dau_id) ) {
	$pacienteTieneIndicacionAlta = '1';
}
unset($_SESSION['datosPacienteDau']);
$_SESSION['datosPacienteDau']  	= $datosDAU[0];
$nombre = $_SESSION['MM_Username'.SessionName];
$rut = $_SESSION['MM_RUNUSU'.SessionName];
if ( isset($_SESSION['usuarioActivo']) ) {
	$nombre 					= $_SESSION['usuarioActivo']['usuario'];
	$rut 						= $_SESSION['usuarioActivo']['rut'];
}
$usuarioMarcaAgua 				= strtoupper (substr($nombre, 0, 3)."".substr($rut,-3));
$transexual_bd 					= $datosDAUPaciente[0]['transexual'] ?? null;
$nombreSocial_bd 				= $datosDAUPaciente[0]['nombreSocial'] ?? null;
$nombrePaciente = 
    ($datosDAUPaciente[0]['nombres'] ?? '') . ' ' .
    ($datosDAUPaciente[0]['apellidopat'] ?? '') . ' ' .
    ($datosDAUPaciente[0]['apellidomat'] ?? '');
$nombreLabel      				= 'Nombre';
$descripcion 					= $obtenerIndicacionEgreso[0]['ind_egr_descripcion'] ?? '';
$infoInputLabel  				= $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'');
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
	tbody {
	    display:block;
	}
	thead, tbody tr{
	    display:table;
	    width:100%;
	    table-layout:fixed;
	}
	thead {
	    /*width: calc( 100% - 1.1em );*/
	}
	th{
		height: 1px;
	}

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
	.espera{
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

	}
</style>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
</head>
<body>
	<input type="hidden" name="pac_id"         id="pac_id"          value="<?=$_SESSION['datosPacienteDau']['id_paciente']?>" >
	<input type="hidden" name="dau"            id="dau"             value="<?=$_SESSION['datosPacienteDau']['dau_id']?>">
	<input type="hidden" name="id_cama" id="id_cama" value="<?= isset($_SESSION['datosPacienteDau']['cam_descripcion']) ? $_SESSION['datosPacienteDau']['cam_descripcion'] : '' ?>">
	<input type="hidden" name="rut"            id="rut"             value="<?=$datosDAUPaciente[0]['rut']?>">
	<input type="hidden" name="tipoMapa"       id="tipoMapa"        value="<?=$parametros['tipoMapa']?>">
	<input type="hidden" name="pacienteNombre" id="pacienteNombre"  value="<?=$datosDAUPaciente[0]['nombres']?>">
	<input type="hidden" name="pacienteAP"     id="pacienteAP"      value="<?=$datosDAUPaciente[0]['apellidopat']?>">
	<input type="hidden" name="pacienteAM"     id="pacienteAM"      value="<?=$datosDAUPaciente[0]['apellidomat']?>">
	<input type="hidden" name="ctacte"         id="ctacte"          value="<?=$_SESSION['datosPacienteDau']['idctacte']?>">
	<input type="hidden" name="dau_atencion"   id="dau_atencion"    value="<?=$_SESSION['datosPacienteDau']['dau_atencion']?>">
	<input type="hidden" name="est_id"		   id="est_id" 			value="<?=$_SESSION['datosPacienteDau']['est_id']?>">
	<input type="hidden" name="idRce"  		   id="idRce" 	        value="<?=$idRce['regId']?>">
	<input type="hidden" name="idRce"  		   id="indicacionAlta"  value="<?=$descripcion ?>">
	<div class="row mt-0 mb-2  ">
		<div class="col-lg-9   text-secondary  " style="font-size: 20px;">
			<button type="button" id="btnVolver" class="btn margin-6 btn-sm btn-outline-primary volverWorklist mifuente12 btnVolver"><svg class="svg-inline--fa fa-chevron-left fa-w-10" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path></svg><!-- <i class="fas fa-chevron-left "></i> -->&nbsp;&nbsp;Atrás</button>
			<label class="mifuente17"> <i class="fas fa-user-injured mr-1 text-primary"></i> <?=$infoInputLabel['input']?></label> <label class="mifuente " style="font-weight: 500;">(DAU : <?=$parametros['dau_id']?>)</label>
		</div>
		<div class="col-lg-3 col-md-3 col-3 text-center ">
			
			<button type="button" class="btn btn-sm btn-outline-primarydiag verificarAccesoRCE" data-toggle="tooltip" data-placement="top" title="" style="border-color: #007bff00;padding: 0rem 0.5rem;" data-original-title="Ver RCE">
				<i class="fas fa-user-md  text-primary  " style="font-size: 25px; color: #4972b1 !important; cursor: pointer;"></i>
			</button>

           
			<?php 
			if ( array_search(1756, $permisos) != null ) { ?>
				<button type="button" name="<?=$parametros['dau_id']?>" class="btn btn-sm btn-outline-primarydiag perfilEnfermeria" data-toggle="tooltip" data-placement="top" title="" style="border-color: #007bff00;padding: 0rem 0.5rem;" data-original-title="Perfil Enfermeria">
					<i class="fas fa-user-nurse   text-primary  " style="font-size: 25px; color: #4972b1 !important; cursor: pointer;"></i>
				</button>
			<?php } ?>
			<?php if ( $idRce['regId'] != null ) { ?>
			<button type="button" class="btn btn-sm btn-outline-primarydiag indicaciones" data-toggle="tooltip" data-placement="top" title="" style="border-color: #007bff00;padding: 0rem 0.5rem;" data-original-title="Ver Indicaciones">
				<i class="fas fa-hand-holding-medical  text-primary  " style="font-size: 25px; color: #4972b1 !important; cursor: pointer;"></i>
			</button>
			<?php } ?>
			<button type="button" class="btn btn-sm btn-outline-primarydiag SignoVitales" name="btnVerIndicacionAplica" data-toggle="tooltip" data-placement="top" title="" style="border-color: #007bff00;padding: 0rem 0.5rem;" data-original-title="Ver RCE">
				<i class="fas fa-briefcase-medical  text-primary  " style="font-size: 25px; color: #4972b1 !important; cursor: pointer;"></i>
			</button>

		</div>
	</div>
	<div class=" row d-flex align-items-stretch">
		<div class="col-lg-9 col-md-9 col-9">
			<div class="card border-primary1 form-group">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">RUT </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$objUtil->formatearNumero($datosDAUPaciente[0]['rut']).'-'.$objUtil->generaDigito($datosDAUPaciente[0]['rut']);?>" readonly="">
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Ficha  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$datosDAUPaciente[0]['nroficha'];?>" readonly="">
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Prevision  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?php echo $datosDAUPaciente[0]['prevision'];?>" readonly="">
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Fono 1  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$datosDAUPaciente[0]['fono1'];?>" readonly="">
						</div>
					</div>
					<div class="mini_salto2"></div>
					<div class="row">
						<div class="col-lg-4 col-md-12 col-12">
							<label class=" encabezado col-lg-12">Nombre  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$infoInputLabel['input'];?>" readonly="">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Religión   </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?= isset($datosDAUPaciente[0]['religion_descripcion']) ? $datosDAUPaciente[0]['religion_descripcion'] : '-'; ?>" readonly="">
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Edad   </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$objUtil->edadActualCompleto($datosDAUPaciente[0]['fechanac']);?>" readonly="">
						</div>
						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Fono 2   </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$datosDAUPaciente[0]['fono2'];?>" readonly="">
						</div>
					</div>
					<div class="mini_salto2"></div>				
					<div class="row">
						<div class="col-lg-9 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Direccion  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?php echo $datosDAUPaciente[0]['Direccion'];?>" readonly="">
						</div>

						<div class="col-lg-3 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Fono 3  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$datosDAUPaciente[0]['fono3'];?>" readonly="">
						</div>
					</div>
				</div>
			</div>
			<div class="card border-primary1 form-group mt-3">
				<div class="card-body">
					<div class="row">
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Paciente </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?php echo $_SESSION['datosPacienteDau']['dau_atencion2'];?>" readonly="">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Consulta  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?php echo $_SESSION['datosPacienteDau']['mot_descripcion'];?>" readonly="">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Estado  </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?php echo $obtenerEstadosIndicacionesDau[0]['est_descripcion'];?>" readonly="">
						</div>
						<div class="col-lg-2 col-md-12 col-12">
							<label class=" encabezado col-lg-12">CAT  </label>
							<?php if($_SESSION['datosPacienteDau']['cat_nombre_mostrar']!=null || ($_SESSION['datosPacienteDau']['est_id']==5 || $_SESSION['datosPacienteDau']['est_id']==6 || $_SESSION['datosPacienteDau']['est_id']==7)){?>
								<div class="row mr-1 ml-1">
									<div id="catAct_<?=$dau_id?>" class="alert col-lg-8 catAct_<?=$_SESSION['datosPacienteDau']['cat_nivel']?>_detDau text-center mifuente12" role="alert" style="padding: 0.1rem 1rem; margin-bottom:0px;">
									<?=$_SESSION['datosPacienteDau']['cat_nombre_mostrar'];?>
									</div>
									<button id="<?=$parametros['dau_id']?>" <?php if($_SESSION['datosPacienteDau']['cat_nivel'] == "") {?>  <?php } else {?>  <?php } ?> type="button" class="btnDau btn btn-sm col-lg-4 btn-primary21">
									<i class="fa fa-search" aria-hidden="true" data-toggle="tooltip" data-placement="top" data-original-title="Detalle de Categorización" class="red-tooltip"></i>
									</button>
								</div>
							<?php } else {
								echo '<input type="text" class="form-control" name="" value="Sin Categorización" readonly="true" class="label2">';
							}?>
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Sala   </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$buscarCamaYsala[0]['sal_descripcion'];?>" readonly="">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Cama   </label>
							<input type="text" id="" class="form-control form-control-sm mifuente12" name="" value="<?=$buscarCamaYsala[0]['cam_descripcion'];?>" readonly="">
						</div>
					</div>				
					<div class="row">
						<div class="col-lg-12 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Detalle  </label>
							<textarea id="" readonly  class="form-control mifuente12" rows="1"><?=$_SESSION['datosPacienteDau']['dau_motivo_descripcion'];?></textarea>
						</div>
					</div>
					<!-- <hr> -->
					<div class="row">
						<div class="col-lg-12 mt-3">
							<label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Indicaciones</label>
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Fecha Ind.</label>
							<?php if($listarIndicaciones[0]['dau_ind_fecha_indicada']==null){?>
								<input value=''  class="form-control form-control-sm mifuente12" readonly>
							<?php }else{?>
								<input readonly  class="form-control form-control-sm mifuente12" value="<?=date("d-m-Y H:i:s",strtotime($listarIndicaciones[0]['dau_ind_fecha_indicada']));?>">
							<?php } ?>
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Indicación </label>
							<input readonly  class="form-control form-control-sm mifuente12" value="<?=$listarIndicaciones[0]['ind_descripcion']?>">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
							<label class=" encabezado col-lg-12">Det. Indicación </label>
							<?php
							if (!empty($obtenerIndicacionEgreso) && isset($obtenerIndicacionEgreso[0])) {
							    $servicio = $obtenerIndicacionEgreso[0]['servicio'] ?? '';
							    $descripcion = $obtenerIndicacionEgreso[0]['ind_egr_descripcion'] ?? '';

							    $valor = $descripcion 
							        ? (($descripcion == "Hospitalización" ? substr($descripcion, 0, 4) : $descripcion) . ($servicio ? '-' . $servicio : ''))
							        : '';
							} else {
							    $valor = ''; // Valor predeterminado si no hay datos
							}
							?>
							<input readonly  class="form-control form-control-sm mifuente12" value="<?=$valor?>">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
						    <label class="encabezado col-lg-12">Estado </label>
						    <input readonly class="form-control form-control-sm mifuente12" 
						           value="<?= isset($obtenerEstadosIndicaciones[0]['est_descripcion']) ? $obtenerEstadosIndicaciones[0]['est_descripcion'] : '' ?>">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
						    <label class="encabezado col-lg-12">Ind. Por </label>
						    <input readonly class="form-control form-control-sm mifuente12" 
						           value="<?= isset($listarIndicaciones[0]['dau_ind_usuario_indica']) ? $listarIndicaciones[0]['dau_ind_usuario_indica'] : '' ?>">
						</div>
						<div class="col-lg-2 col-md-6 col-6">
						    <label class="encabezado col-lg-12">Aplicada </label>
						    <input readonly class="form-control form-control-sm mifuente12" 
						           value="<?= isset($listarIndicaciones[0]['dau_ind_usuario_aplica']) ? $listarIndicaciones[0]['dau_ind_usuario_aplica'] : '' ?>">
						</div>
					</div>
					<div class="timeline row m-1" id="timeline">
						<!-- <div class="row"> -->
							<div class="col-lg-12 mt-3">
								<label class="text-secondary"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Línea de tiempo</label>
							</div>
						<!-- </div> -->
						<?php for ($x = 0; $x < 6; $x++) { 
						    if (!isset($datosLT[$x]['estado'])) {
						        // Salta la iteración si el índice no está definido
						        $datosLT[$x]['estado'] = "";
						    }

						    $estlt = ''; // Inicializamos $estlt
						    switch ($datosLT[$x]['estado']) {
						        case 1:
						            $estlt = "Admisión";
						            break;
						        case 2:
						            $estlt = "Categorización";
						            break;
						        case 3:
						            $estlt = "Inicio Atención";
						            break;
						        case 4:
						            $estlt = "Indicación Egreso";
						            break;
						        case 5:
						            if (!empty($_SESSION['datosPacienteDau']['est_id']) && $_SESSION['datosPacienteDau']['est_id'] == 5) {
						                $estlt = "Cierre";
						            }
						            break;
						        case 6:
						            if (!empty($_SESSION['datosPacienteDau']['est_id'])) {
						                if ($_SESSION['datosPacienteDau']['est_id'] == 6) {
						                    $estlt = "Cierre: Anula";
						                } elseif ($_SESSION['datosPacienteDau']['est_id'] == 7) {
						                    $estlt = "Cierre: N.E.A.";
						                } elseif ($_SESSION['datosPacienteDau']['est_id'] == 5) { // Asegúrate que esta es la condición correcta
						                    $estlt = "Cierre: Administrativo";
						                }
						            }
						            break;
						        case 8:
						            $estlt = "Ingreso Box";
						            break;
						    }
						    ?>
						    <li class="li col" style="padding-right: 0px; padding-left: 0px;">
						        <div align="center" class="timestamp">
						            <span class="author">
						                <?php if (!empty($datosLT[$x]['usuario'])) { ?>
						                    <th style="font-weight: normal;"><?= htmlspecialchars($datosLT[$x]['usuario']) ?></th>
						                <?php } else {
						                    echo "<br>";
						                } ?>
						            </span>
						            <span class="date mifuente12 text-secondary">
						                <?php if (!empty($datosLT[$x]['fecha'])) { ?>
						                    <th class="mifuente12">
						                        <?= date("d-m-Y H:i", strtotime($datosLT[$x]['fecha'])) ?>
						                    </th>
						                <?php } else {
						                    echo "<br>";
						                } ?>
						            </span>
						        </div>
						        <?php if (!empty($datosLT[$x]['usuario'])) { ?>
						            <div align="center" class="done">
						                <h5 style="font-size: 11px;"><?= htmlspecialchars($estlt) ?></h5>
						            </div>
						        <?php } else { ?>
						            <div align="center" class="status">
						                <h5></h5>
						            </div>
						        <?php } ?>
						    </li>
						<?php } ?>
					</div>
				</div>
			</div>
			<!-- <div class="row" > -->
				<!-- <div class="col-md-12"> -->


					<!-- <div class="panel-body" align="center" > -->

							<!-- <div class="transcurrido"> -->

								<!-- <div class="timeline"> -->
									<?php
$total = count($datosLT) - 1;
for ($y = 0; $y < $total; $y++) {
    // Verificar si 'fecha' está definida en el array antes de acceder
    if (isset($datosLT[$y]['fecha'])) {
        $inicio = $datosLT[$y]['fecha'];
    } else {
        $inicio = null; // Asigna un valor predeterminado si no existe
    }

    $y++;

    // Verificar también si 'fecha' está definida para el segundo índice
    if (isset($datosLT[$y]['fecha'])) {
        $fin = $datosLT[$y]['fecha'];
    } else {
        $fin = null; // Asigna un valor predeterminado si no existe
    }

    if ($inicio && $fin) {
        $fechaFinal = $objUtil->get_timespan_string_sseg($inicio, $fin);
        $arrayFechaFinal = explode(" ", $fechaFinal);
        if ($fechaFinal == "") {
            // echo "<div><label class='label2'>0 minutos</label></div>";
        } else {
            // echo "<div id='prueba2' style='margin-right: -75%;'><label class='label2'>".$arrayFechaFinal[0]." ".$arrayFechaFinal[1]."<br>".$arrayFechaFinal[2]." ".$arrayFechaFinal[3]."</label></div>";
        }
    }
}
?>

		</div>
		<div class="col-lg-3 col-md-3 col-3 ">
			<div class="card border-primary1" style="height: 100%;max-height: 100%;">
				<div class="card-header bg-light text-secondary">
				    <svg class="svg-inline--fa fa-cog fa-w-16" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="cog" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M487.4 315.7l-42.6-24.6c4.3-23.2 4.3-47 0-70.2l42.6-24.6c4.9-2.8 7.1-8.6 5.5-14-11.1-35.6-30-67.8-54.7-94.6-3.8-4.1-10-5.1-14.8-2.3L380.8 110c-17.9-15.4-38.5-27.3-60.8-35.1V25.8c0-5.6-3.9-10.5-9.4-11.7-36.7-8.2-74.3-7.8-109.2 0-5.5 1.2-9.4 6.1-9.4 11.7V75c-22.2 7.9-42.8 19.8-60.8 35.1L88.7 85.5c-4.9-2.8-11-1.9-14.8 2.3-24.7 26.7-43.6 58.9-54.7 94.6-1.7 5.4.6 11.2 5.5 14L67.3 221c-4.3 23.2-4.3 47 0 70.2l-42.6 24.6c-4.9 2.8-7.1 8.6-5.5 14 11.1 35.6 30 67.8 54.7 94.6 3.8 4.1 10 5.1 14.8 2.3l42.6-24.6c17.9 15.4 38.5 27.3 60.8 35.1v49.2c0 5.6 3.9 10.5 9.4 11.7 36.7 8.2 74.3 7.8 109.2 0 5.5-1.2 9.4-6.1 9.4-11.7v-49.2c22.2-7.9 42.8-19.8 60.8-35.1l42.6 24.6c4.9 2.8 11 1.9 14.8-2.3 24.7-26.7 43.6-58.9 54.7-94.6 1.5-5.5-.7-11.3-5.6-14.1zM256 336c-44.1 0-80-35.9-80-80s35.9-80 80-80 80 35.9 80 80-35.9 80-80 80z"></path></svg><!-- <i class="fas fa-cog "></i> -->&nbsp;&nbsp;<label class=" encabezado" style="font-size: 30px;">Acciones</label>
				</div>
				<div class="card-body mifuente12">
					<br>
					<div class="salto"></div>
					<div class="row text-left">
						<div class="col-md-12 text-left">
						<?php if ( array_search(817, $permisos) != null ) { ?>
							<?php
								if($_SESSION['datosPacienteDau']['est_id'] != 8){?>
							<button id="<?=$parametros['dau_id']?>" type="button" name="btnVerIniciarAtencion" class="btn mb-2 btn-warning verInformacionIniciarAtencion col-lg-12 mifuente12" >
								<div class="row">
									<div class="col-lg-4 text-right">
										<i class="fas fa-user-injured" style="font-size: 17px;"></i> 
									</div>
									<div class="col-lg-6 text-center">
										Ver Inicio Atención
									</div>
								</div>
							</button>
							<?php }else{?>
							<!-- <button id="<?=$parametros['dau_id']?>" type="button" name="btnIniciarAtencion" class="btn mb-2 btn-info verIniciarAtencion col-lg-12 mifuente12" >
								<div class="row">
									<div class="col-lg-4 text-right">
										<i class="fas fa-user-injured" style="font-size: 17px;"></i> 
									</div>
									<div class="col-lg-6 text-center">
										Iniciar Atención
									</div>
								</div>
							</button> -->
							<?php }	?>
						<?php }?>
						</div>
					</div>
					<?php if ( array_search(819, $permisos) != null ) { ?>
					<div class="row text-left">
						<div class="col-md-12 text-left">
							<button id="<?=$parametros['dau_id']?>" type="button" name="btnPyxis" class="btn btnPyxis btn-primary  btn-sm mb-2 btn-block btn_comiteHerida text-left">
								<div class="row">
									<div class="col-lg-4 text-right">
										<i class="fas fa-pills text-white" style="font-size: 17px;"></i>
									</div>
									<div class="col-lg-6 text-center">
										Enviar a Pyxis
									</div>
								</div>
							</button>
						</div>
					</div>
					<?php }?>
					<?php if ( array_search(845, $permisos) != null ) { ?>
					<div class="row text-left">
						<div class="col-md-12 text-left">
							<button id="btnHistorialClinico" type="button" name="btnHistorialClinico" class="btn btn-primary  btn-sm mb-2 btn-block historialClinico text-left">
							<div class="row">
								<div class="col-lg-4 text-right">
									<!-- <i class="fas fa-laptop-medical"></i> -->
									<svg class="svg-inline--fa fa-laptop-medical fa-w-20" style="font-size: 17px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="laptop-medical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M232 224h56v56a8 8 0 0 0 8 8h48a8 8 0 0 0 8-8v-56h56a8 8 0 0 0 8-8v-48a8 8 0 0 0-8-8h-56v-56a8 8 0 0 0-8-8h-48a8 8 0 0 0-8 8v56h-56a8 8 0 0 0-8 8v48a8 8 0 0 0 8 8zM576 48a48.14 48.14 0 0 0-48-48H112a48.14 48.14 0 0 0-48 48v336h512zm-64 272H128V64h384zm112 96H381.54c-.74 19.81-14.71 32-32.74 32H288c-18.69 0-33-17.47-32.77-32H16a16 16 0 0 0-16 16v16a64.19 64.19 0 0 0 64 64h512a64.19 64.19 0 0 0 64-64v-16a16 16 0 0 0-16-16z"></path></svg><!-- <i class="fas fa-laptop-medical" style="font-size: 17px;"></i> -->
								</div>
								<div class="col-lg-6 text-center">
									Historial Clinico
								</div>
							</div>
							</button>
						</div>
					</div>
					<?php }?>
					<?php if ( array_search(816, $permisos) != null ) { ?>
						<?php
						$disabledIE = 'disabled';
						if ($_SESSION['datosPacienteDau']['est_id'] == 8) {
							$disabledIE = 'disabled';
						}
					}?>
		            <hr>	            											
		            <?php if ( array_search(818, $permisos) != null ) { ?>
		            <div class="row">
						<div class="col-md-12">
						<?php if ($obtenerEstadosIndicaciones[0]['est_id'] != 20 && $obtenerEstadosIndicaciones[0]['est_id'] === null && isset($_SESSION['datosPacienteDau']['est_id']) && $_SESSION['datosPacienteDau']['est_id'] != 5 && $_SESSION['datosPacienteDau']['est_id'] != 6 && $_SESSION['datosPacienteDau']['est_id'] != 7) {  ?>	
							<button id="<?=$parametros['dau_id']?>" type="button" name="btnVerIndicacionAplica" class="btn btn-primary verInformacionAplicaEgreso btn-sm mb-2 btn-block SolicitudAlta">
								<div class="row">
									<div class="col-lg-4 text-right">
										<svg class="svg-inline--fa fa-home fa-w-18" style="font-size: 17px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="home" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path></svg>
									</div>
									<div class="col-lg-6 text-center">
										Aplicar Egreso
									</div>
								</div>
							</button> 
						<?php } else if ( $_SESSION['datosPacienteDau']['est_id']==5 || $_SESSION['datosPacienteDau']['est_id']==6 || $_SESSION['datosPacienteDau']['est_id']==7 ) { ?>
							<button id="" type="button" name="" class="btn btn-primary  btn-sm mb-2 btn-block " disabled>
								<div class="row">
									<div class="col-lg-4 text-right">
										<svg class="svg-inline--fa fa-home fa-w-18" style="font-size: 17px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="home" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path></svg>
									</div>
									<div class="col-lg-6 text-center">
										Aplicar Egreso
									</div>
								</div>
							</button>
						<?php
						} else { ?>
							<button id="<?=$parametros['dau_id']?>" type="button" name="btnIndicacionAplica" class="btn btn-primary verindicacionaplica btn-sm mb-2 btn-block " >
								<div class="row">
									<div class="col-lg-4 text-right">
										<svg class="svg-inline--fa fa-home fa-w-18" style="font-size: 17px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="home" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" data-fa-i2svg=""><path fill="currentColor" d="M280.37 148.26L96 300.11V464a16 16 0 0 0 16 16l112.06-.29a16 16 0 0 0 15.92-16V368a16 16 0 0 1 16-16h64a16 16 0 0 1 16 16v95.64a16 16 0 0 0 16 16.05L464 480a16 16 0 0 0 16-16V300L295.67 148.26a12.19 12.19 0 0 0-15.3 0zM571.6 251.47L488 182.56V44.05a12 12 0 0 0-12-12h-56a12 12 0 0 0-12 12v72.61L318.47 43a48 48 0 0 0-61 0L4.34 251.47a12 12 0 0 0-1.6 16.9l25.5 31A12 12 0 0 0 45.15 301l235.22-193.74a12.19 12.19 0 0 1 15.3 0L530.9 301a12 12 0 0 0 16.9-1.6l25.5-31a12 12 0 0 0-1.7-16.93z"></path></svg>
									</div>
									<div class="col-lg-6 text-center">
										Aplicar Egreso
									</div>
								</div>
							</button>
						<?php } ?>
						</div>
					</div>
					<?php } ?>
					<div class="row text-left">
						<div class="col-md-12 text-left">
							<button id="<?=$parametros['dau_id']?>" type="button" name="btnNEA" class="btn btn-primary  btn-sm mb-2 btn-block aplicarNEA text-left" <?php if($_SESSION['datosPacienteDau']['est_id']==5 || $_SESSION['datosPacienteDau']['est_id']==6 || $_SESSION['datosPacienteDau']['est_id']==7 || $pacienteTieneIndicacionAlta ==1){echo "disabled";}?> >
							<div class="row">
								<div class="col-lg-4 text-right">
									<i class="fas fa-bullhorn text-white" style="font-size: 17px;"></i>
								</div>
								<div class="col-lg-6 text-center">
									N.E.A.
								</div>
							</div>
							</button>
						</div>
					</div>
					<hr>
					<?php
					if (
						!pacienteTieneEstadoDauCerrado($datosDAU[0]["est_id"])
						&& cumpleCondicionesParaDesplegarLPP($datosDAU[0])
						||(
							pacienteTieneEstadoDauCerrado($_SESSION["datosPacienteDau"]["est_id"])
							&& existeLPP($datosDAU[0]["dau_id"])
						)
					) {
					?>
					<div class="row text-left">
						<div class="col-md-12 text-left">
							<button id="verIngresarLPP-<?=$parametros['dau_id']?>" type="button" name="verDetalleDau" class="btn btn-success  btn-sm mb-2 btn-block verIngresarLPP text-left" >
							<div class="row">
								<div class="col-lg-4 text-right">
									<i class="fas fa-file-medical text-white" style="font-size: 17px;"></i>
								</div>
								<div class="col-lg-6 text-center">
									LPP
								</div>
							</div>
							</button>
						</div>
					</div>

					<?php
					}
					?>
					<div class="row text-left">
						<div class="col-md-12 text-left">
							<button id="<?=$parametros['dau_id']?>" type="button" name="verDetalleDau" class="btn btn-primary  btn-sm mb-2 btn-block verDetalleDau text-left" >
							<div class="row">
								<div class="col-lg-4 text-right">
									<i class="fas fa-file-medical text-white" style="font-size: 17px;"></i>
								</div>
								<div class="col-lg-6 text-center">
									Detalle DAU
								</div>
							</div>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
$(document).ready(function(){
	let idDau 			= $("#dau").val();
	let idRce 			= $("#idRce").val();
	let estadoDau 		= $("#est_id").val();
	let pacienteId 		= $('#pac_id').val();
	let tipoMapa 		= $('#tipoMapa').val();
	let tipoAtencion 	= $("#dau_atencion").val();
	banderapiso			= 'DETALLEDAU';

	$("#prueba").css("overflow-y","hidden");

	$(".btnVolver").click(function(){
		// view("#contenido");
		ajaxContentSlideLeft(raiz+localStorage.getItem('urlAtras'),localStorage.getItem('parametrosAtras'), '#contenido');
		// if(tipoMapa == ''){
		// 	ajaxContent(`${raiz}/views/modules/consulta/consulta.php`,'','#contenido','', true);
		// }else{
		// 	ajaxContent(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa,'#contenido','', true);
		// }
	});
	$(".verIniciarAtencion").click(function(){
		if ( perfilUsuario !== 'medico' && perfilUsuario !== 'full') {
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/inicioAtencion.php", `dau_id=${idDau}&tipoMapa=${tipoMapa}`, "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus",'');
	});
	$(".verindicacionaplica").click(function(){
		if ( perfilUsuario === 'administrativo' ) {
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/modalIndicacionAplica.php", $("#frmIndicacionAplica").serialize()+`&dau_id=${idDau}&tipoMapa=${tipoMapa}`, "#modalmodalIndicacionAplica", "modal-lg", "", "fas fa-plus",'');
	});
	$("#btnHistorialClinico").click(function(){

		modalFormulario('<label class="mifuente text-primary">Historial Clinico</label>',raiz+"/views/modules/rce/rce/historial_clinico.php",`paciente_id=${pacienteId}`,'#modal_historial','modal-lg','', 'fas fa-laptop-medical text-primary','');
	});
	$(".aplicarNEA").click(function(){

		modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'dau_id='+idDau+'&tipoMapa='+tipoMapa, "#modalNEA", "modal-md", "", "fas fa-plus",'');
	});
	$(".verInformacionIniciarAtencion").click(function(){
		if ( perfilUsuario === 'administrativo' ) {
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/modalInformacionInicioAtencion.php", 'dau_id='+idDau+'&tipoMapa='+tipoMapa, "#modalInformacionInicioAtencion", "modal-md", "", "fas fa-plus",'');
	});
	$(".verInformacionAplicaEgreso").click(function(){
		if ( perfilUsuario === 'administrativo' ) {
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/modalInformacionAplicarEgreso.php", `&dau_id=${idDau}&tipoMapa=${tipoMapa}`, "#modalInformacionAplicarEgreso", "modal-lg", "", "fas fa-plus",'');
	});
	$(".btnDau").click(function(){

		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/dau_detalle.php", 'dau_id='+idDau+'&btn=N', "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus");
	});
	$(".SignoVitales").click(function(){
		
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", `dau_id=${idDau}&estadoDau=${estadoDau}&tipoMapa=${tipoMapa}`, "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
	});
	$('.verDetalleDau').on('click', function(){
		
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_detalleDau.php", 'idDau='+idDau, "#ver_detalleDau", "modal-lg", "", "fas fa-plus");

	});
	$('.btnPyxis').click(async function () {
		if ( perfilUsuario !== 'administrativo') {
			let rutPaciente      = $("#rut").val();
			let nombrePaciente   = $("#pacienteNombre").val();
			let APpaciente       = $("#pacienteAP").val();
			let AMpaciente       = $("#pacienteAM").val();
			let ctactePaciente   = $("#ctacte").val();
			var funcion = async function(){ //inicio funcion
				var fn_interna = function(){//inicio fn_interna
					var pyxis      = function(response){
						switch(response.status){
							case "success":
								var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Información de Pyxis </h4>  <hr>  <p class="mb-0">El paciente ha sido enviado a la maquina de Pyxis correctamente..</p></div>';
                				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
								ajaxContent(`${raiz}/views/modules/mapa_piso_full/detalle_dau/detalle_dau.php`,'dau_id='+idDau+'&tipoMapa='+tipoMapa,'#contenido','', true);
							break;
							case "error":   
								var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en registrar Pyxis<br><br>'+response.message+'.</p></div>';
	            				modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
							break;
	                        default:
	                            ErrorSistemaDefecto();
	                        break;
						}
					}
					ajaxRequest(raiz+'/controllers/server/pyxis/main_controller.php', 'accion=pyxis'+'&rutPaciente='+rutPaciente+'&nombrePaciente='+nombrePaciente+'&APpaciente='+APpaciente+'&AMpaciente='+AMpaciente+'&idPaciente='+pacienteId+'&ctactePaciente='+ctactePaciente+'&dau_id='+idDau , 'POST', 'JSON', 1,'Enviando Pyxis...', pyxis);
				}
				// fn_global = fn_interna;
				if ($("#dau_atencion").val() == 3) {
	            	const estadoPermiso = await validarPermisoUsuario('btn_pyxisGine');
	            	if (estadoPermiso) fn_interna();
	            }else{
	            	const estadoPermiso = await validarPermisoUsuario('btn_pyxis');
	            	if (estadoPermiso) fn_interna();
	            }
			}
        	modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a enviar pyxis, <b>¿Desea continuar?</b>", "primary", funcion);
		}
	});
	$(".verificarAccesoRCE").click(function(){
		// alert(perfilUsuario)
		if ( idDau == undefined || perfilUsuario == 'tens' || perfilUsuario == 'enfermero' || perfilUsuario == '' || perfilUsuario == 'matrona') {
			var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Ingreso RCE </h4>  <hr>  <p class="mb-0">Usted no cuenta con el permiso para ingresar a RCE del paciente.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
			return;
		}
		ajaxContent(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+tipoMapa+`&dau_id=${idDau}`, '#contenido');
	});

	$(".PerfilEnfermeria").click(function(){
		// if ( idDau == undefined || perfilUsuario == 'tens' || perfilUsuario == 'medico' || perfilUsuario == '' ) {
		// 	var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Ingreso RCE </h4>  <hr>  <p class="mb-0">Usted no cuenta con el permiso para ingresar al Perfil Enfermeria.</p></div>';
  //           modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
		// 	return;
		// }
		ajaxContent(`${raiz}/views/modules/enfermera/perfil_enfermeria.php`,'tipoMapa='+tipoMapa+`&dau_id=${idDau}`, '#contenido');
	});
	$(".indicaciones").click(function(){
		modalFormulario_noCabecera('', raiz+"/views/modules/enfermera/despliegueIndicaciones.php", `dau_id=${idDau}&tipoMapa=${tipoMapa}&regId=${idRce}&banderaDetalleDau=1`, "#ver_detalleDau", "modal-lg", "", "fas fa-plus");
		// modalFormulario(`Detalle Indicación ${idDau}`, `${raiz}/views/modules/Enfermera/despliegueIndicaciones.php`, `dau_id=${idDau}&regId=${idRce}&banderaDetalleDau=1`, '#detalleIndicacion', '80%', '80%');
	});
	$(".verIngresarLPP").on("click", function() {

		const botones = (!pacienteEgresado())
			? [{
					id: "btnIngresarLPP",
					value: 'Ingresar LPP',
					class: "btn btn-primary"
				}]
			: [];
		
		modalFormulario("<label class='mifuente'>Documento Detalle DAU  </label>",raiz+'/views/modules/mapa_piso_full/detalle_dau/lpp.php',`idDau=${idDau}`,'#LPP',"modal-md","primary","fas fa-folder-plus",botones);
	});
	function pacienteEgresado ( ) {
        const parametros 		   =  {idDau : idDau, accion : 'pacienteEgresado'};
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php', parametros, 'POST', 'JSON', 1);
        if ( respuestaAjaxRequest.status == 'success' ) {
            return true;
        }
	    return false;
    }

});
</script>

<?php

function pacienteTieneEstadoDauCerrado($estadoDau) {
	return ( $estadoDau == 5 || $estadoDau == 6 || $estadoDau == 7
	);
}
function cumpleCondicionesParaDesplegarLPP($DAU) {
	require_once('../../../../class/Util.class.php');
	$util 						= new Util();
	$categorizacion 			= $DAU["dau_categorizacion"];
 	$categorizacionesPrimarias 	= array(
		"C1",
		"ESI-1",
		"C2",
		"ESI-2"
	);
	$categorizacionSecundarias 	= array(
		"C3",
		"ESI-3",
		"C4",
		"ESI-4",
		"C5",
		"ESI-5"
	);
	if (in_array($categorizacion, $categorizacionesPrimarias)) {
		return true;
	}
	if (
		!in_array($categorizacion, $categorizacionSecundarias)
		|| !$util->existe($DAU["dau_inicio_atencion_fecha"])
	) {
		return false;
	}
	$SEIS_HORAS 				= 21600;
	$segundosInicioAtencion 	= strtotime($DAU["dau_inicio_atencion_fecha"]);
	$segundosHoraActual 		= strtotime(date("Y-m-d H:s:i"));
	$diferenciaHora 			= $segundosHoraActual - $segundosInicioAtencion;
	return ($diferenciaHora >= $SEIS_HORAS);
}

function existeLPP($idDau) {
	require_once('../../../../class/Connection.class.php');
	require_once("../../../../class/LPP.class.php" );
	require_once('../../../../class/Util.class.php');

	$objCon 					= new Connection();
	$util 						= new Util();
	$LPP 						= new LPP();
	$objCon->db_connect();
	$resultado 					= $LPP->obtenerLPP($objCon, array("idDau" => $idDau));
	return ($util->existe($resultado));
}
?>
