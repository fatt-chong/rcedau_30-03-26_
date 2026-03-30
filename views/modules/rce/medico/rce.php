<?php
session_start();
// 


error_reporting(0);
require("../../../../config/config.php");
require_once ("../../../../class/Util.class.php"); 				$objUtil       			= new Util;
require_once("../../../../class/Dau.class.php" );  				$objDau 	   			= new Dau;
require_once("../../../../class/Connection.class.php"); 		$objCon        			= new Connection();
require_once('../../../../class/Config.class.php');      		$objConfig 	   			= new Config;
require_once('../../../../class/Rce.class.php'); 				$objRce        			= new Rce;
require_once('../../../../class/Categorizacion.class.php'); 	$objCategorizacion  	= new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php'); 	$objRegistroClinico  	= new RegistroClinico;
require_once('../../../../class/Rce.class.php'); 				$objRce  				= new Rce;
require_once('../../../../class/Bitacora.class.php'); 			$objBitacora  			= new Bitacora;
require_once('../../../../class/AltaUrgencia.class.php'); 		$objAltaUrgencia  		= new AltaUrgencia;
require_once('../../../../class/Evolucion.class.php'); 			$objEvolucion  			= new Evolucion;
require_once('../../../../class/Diagnosticos.class.php');      	$objDiagnosticos    	= new Diagnosticos;
// require("../../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
$objCon->db_connect();
$parametros                    		= $objUtil->getFormulario($_POST);
$parametros['id_dau']				= $_POST['dau_id'];
$datosRce 							= $objRegistroClinico->consultaRCE($objCon, $parametros);
$datosU 							= $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$datosDAU 							= $objDau->ListarPacientesDau($objCon, $parametros);
$lista 								= $objRce->listarAntecedentes($objCon, $parametros);
$listaSignos 						= $objRce->listarSignosVitales($objCon, $datosU[0]['id_paciente'], $datosRce[0]['regId']);
$parametros['rce_id']				= $datosRce[0]['regId'];
$parametros['BITid'] 				= $parametros['dau_id'];
// $listadoBITACORA 				= $objBitacora->listarBitacora($objCon, $parametros);
$imgSexo 							= $objUtil->imagenRCE($datosU[0]['sexo'],$datosU[0]['dau_paciente_edad']);
$transexual_bd   					= $datosU[0]['transexual'];
$nombreSocial_bd 					= $datosU[0]['nombreSocial'];
$nombrePaciente  					= $datosU[0]['nombres'].' '.$datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
$nombreLabel      					= 'Nombre';
$infoInputLabel  					= $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'S');
$parametros["pac_id"] 				= $datosU[0]['id_paciente'];
$rsIndicaciones         			= $objRce->listarAntecedentes($objCon,$parametros);
$tiposAtencion 						= array(1 => "Adulto",2 => "Pediátrico",3 => "Ginecológico");
$tiposConsultas 					= array(1 => "Accidente",2 => "Enfermedad",3 => "Agresión",4 => "C. Lesiones",5 => "Alcoholemia");
$runPaciente 						= (empty($datosU[0]["runPacienteExtranjero"]))
								? $objUtil->formatearNumero($datosU[0]["runPaciente"]).'-'.$objUtil->generaDigito($datosU[0]["runPaciente"])
								: $datosU[0]["runPacienteExtranjero"];
$tiposCategorizaciones 				= array("ESI-1" => "tbl_cat tr_tblCat-ESI-1 bg-danger",	"C1" => "tbl_cat tr_tblCat-ESI-1 bg-danger","ESI-2" => "tbl_cat tr_tblCat-ESI-2","C2" => "tbl_cat tr_tblCat-ESI-2","ESI-3" => "tbl_cat tr_tblCat-ESI-3 bg-warning",	"C3" => "tbl_cat tr_tblCat-ESI-3 bg-warning","ESI-4" => "tbl_cat tr_tblCat-ESI-4 bg-success","C4" => "tbl_cat tr_tblCat-ESI-4 bg-success","ESI-5" => "tbl_cat tr_tblCat-ESI-5 bg-info","C5" => "tbl_cat tr_tblCat-ESI-5 bg-info","defautl" => "tbl_cat tr_tblCat-init bg-info");
$clase 								= ($objUtil->existe($datosU[0]['dau_categorizacion_actual']))
								? $tiposCategorizaciones[$datosU[0]['dau_categorizacion_actual']]
								: $tiposCategorizaciones["default"];

$inicioAtencion = "";
$ocultarBitaroca = "";
unset($_SESSION['datosPacienteDau']);
$_SESSION['datosPacienteDau'] 		= $datosDAU[0];
$_SESSION['RCE']['pacienteCita'] 	= $datosU[0]['id_paciente'];
$_SESSION['RCE']['rutPaciente'] 	= $datosU[0]['rut'];
$_SESSION['RCE']['idPaciente'] 		= $datosU[0]['id_paciente'];

$existeSolicitudAltaUrgencia 		= $objAltaUrgencia->existeSolicitudAltaUrgencia($objCon, $parametros['id_dau']);
$respuestaConsulta 					= $objDau->tiempoIndicacionEgreso($objCon, $parametros['id_dau']);
$rsCumpleReceta 					= $objDau->cumpleCondicionesParaDesplegarRecetaGES($objCon, $parametros['id_dau']);
if($parametros['rce_id']>0){
	$parametrosEvo['rce_id'] 			= $parametros['rce_id'];
	$parametrosEvo['usuario'] 			= $_SESSION['MM_Username'.SessionName];
	$rsEvolucion 						= $objEvolucion->obtenerDatosSolicitudEvolucionGlobal($objCon, $parametrosEvo);
}
// $parametrosEvo['rce_id'] 			= $parametros['rce_id'];
// $parametrosEvo['usuario'] 			= $_SESSION['MM_Username'.SessionName];
// $rsEvolucion 						= $objEvolucion->obtenerDatosSolicitudEvolucionGlobal($objCon, $parametrosEvo);
// Verifica si $rsEvolucion no está vacío y contiene el índice 0
if (!empty($rsEvolucion) && isset($rsEvolucion[0]['SEVOevolucion'])) {
    $evolucionTexto = $rsEvolucion[0]['SEVOevolucion'];
} else {
    $evolucionTexto = ''; // Valor por defecto si no hay datos
}
$buscarCamaYsala               		= $objDau->buscarCamaYsala($objCon,$parametros);
$parametros['cta_cte'] 				= $datosU[0]['idctacte'];
$rsRce_diagnostico 					= $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$_SESSION['RCE']['pacienteCita'] 	= $datosU[0]['id_paciente'];
// print('<pre>'); print_r($rsRce_diagnostico); print('</pre>');
// if( empty($datosDAU[0]['dau_inicio_atencion_fecha']) || count($existeSolicitudAltaUrgencia) > 0  || ( $datosDAU[0]['est_id'] == 5 || $datosDAU[0]['est_id'] == 6 || $datosDAU[0]['est_id'] == 7 ) ){
// 	$disabled = 'disabled';
// }
// if( empty($datosDAU[0]['dau_inicio_atencion_fecha']) || count($existeSolicitudAltaUrgencia) > 0  || ( $datosDAU[0]['est_id'] == 5 || $datosDAU[0]['est_id'] == 6 || $datosDAU[0]['est_id'] == 7 ) ){
// 	$disabledAltaUrgencia = 'disabled';
// }
$rsFecha 		= $objUtil->getHorarioServidor($objCon);
$fechaServidor 	= $rsFecha[0]['fecha']." ".$rsFecha[0]['hora'];
$disabled 		= "";

// Transfusiones: habilitado desde el 03-03-2026
$fechaLimiteTransfusiones = '2026-03-03';
$fechaActual = date('Y-m-d');
$transfusionesHabilitado = ($fechaActual >= $fechaLimiteTransfusiones);
$disabledTransfusiones = $transfusionesHabilitado ? '' : 'disabled';


?>

<?php
$disabledAplicarEgreso = '';
$disabledAltaUrgencia  = "";
if (
	pacienteNoTieneInicioAtencion($datosDAU[0]['dau_inicio_atencion_fecha'])
	|| pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia)
	|| pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])
) {

	$disabled = 'disabled';
	if($existeSolicitudAltaUrgencia[0]['tipoSolicitud'] == 4){
		$disabled ="";
	}
}

if (
	pacienteNoTieneInicioAtencion($datosDAU[0]['dau_inicio_atencion_fecha'])
	|| pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])
	|| !tiempoPermitidoIndicacionEgreso($respuestaConsulta,$fechaServidor)
) {
	$disabledAltaUrgencia = 'disabled';
}

if (
	pacienteNoTieneIndicacionEgreso($datosDAU[0]['est_id'])
	|| pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])
) {
	$disabledAplicarEgreso = 'disabled';
}
?>
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/rce/rce.js?v=<?=date('H:M:s');?>"></script>
<script type="text/javascript">
	// Tooltip (solo si jQuery está disponible)
	if (typeof jQuery !== 'undefined') {
		jQuery(function () {
			jQuery('[data-toggle="tooltip"]').tooltip();
		});
	}
	
	// Cuenta regresiva para Transfusiones (03-03-2026) - sin jQuery
	(function() {
		function initCountdown() {
			var fechaLimite = new Date('2026-03-03T00:00:00');
			var countdownEl = document.getElementById('countdownTransfusiones');
			if (!countdownEl) return;
			
			function actualizarCountdown() {
				var ahora = new Date();
				if (ahora >= fechaLimite) {
					location.reload();
					return;
				}
				var diff = fechaLimite - ahora;
				var dias = Math.floor(diff / (1000 * 60 * 60 * 24));
				var horas = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var min = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
				var seg = Math.floor((diff % (1000 * 60)) / 1000);
				
				var diasEl = document.getElementById('diasTransfusiones');
				var horasEl = document.getElementById('horasTransfusiones');
				var minEl = document.getElementById('minTransfusiones');
				var segEl = document.getElementById('segTransfusiones');
				
				if (diasEl) diasEl.textContent = dias;
				if (horasEl) horasEl.textContent = String(horas).padStart(2, '0');
				if (minEl) minEl.textContent = String(min).padStart(2, '0');
				if (segEl) segEl.textContent = String(seg).padStart(2, '0');
			}
			
			actualizarCountdown();
			setInterval(actualizarCountdown, 1000);
		}
		
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', initCountdown);
		} else {
			initCountdown();
		}
	})();
</script>
<style>
    /* Estilos personalizados para diferentes tamanhos de tela */
    @media (max-height: 576px) { /* Tela pequena */
		.ScrollStylePEvo {
			max-height: calc(105vh - 318px);
			overflow: auto;
		}
    }
    @media (min-height: 577px) and (max-height: 768px) { /* Tela média */
		.ScrollStylePEvo {
			max-height: 385px;
			overflow: auto;
		}
    }
    @media (min-height: 769px) and (max-height: 992px) { /* Tela grande */
		.ScrollStylePEvo {
			max-height: 385px;
			overflow: auto;
		}
    }
    @media (min-height: 993px) and (max-height: 1080px)  { /* Tela extra grande */
		.ScrollStylePEvo {
			max-height: 575px;
			overflow: auto;
		}
    }
    @media (min-height: 1080px)   { /* Tela extra grande */
		.ScrollStylePEvo {
			max-height: 575px;
			overflow: auto;
		}
    }


    @media (max-height: 576px) { /* Tela pequena */
		.ScrollStylePBitacora {
			cursor: pointer;
			max-height: calc(105vh - 318px);
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 577px) and (max-height: 768px) { /* Tela média */
		.ScrollStylePBitacora {
			cursor: pointer;
			max-height: 600px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 769px) and (max-height: 992px) { /* Tela grande */
		.ScrollStylePBitacora {
			cursor: pointer;
			max-height: 600px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 993px) and (max-height: 1080px)  { /* Tela extra grande */
		.ScrollStylePBitacora {
			cursor: pointer;
			min-height: 875px;
			max-height: 875px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 1080px)   { /* Tela extra grande */
		.ScrollStylePBitacora {
			cursor: pointer;
			min-height: 875px;
			max-height: 875px;
			overflow-x: hidden;
			
		}
    }



     @media (max-height: 576px) { /* Tela pequena */
		.ScrollStylePBitacoraHoja {
			cursor: pointer;
			max-height: calc(105vh - 318px);
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 577px) and (max-height: 768px) { /* Tela média */
		.ScrollStylePBitacoraHoja {
			cursor: pointer;
			max-height: 600px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 769px) and (max-height: 992px) { /* Tela grande */
		.ScrollStylePBitacoraHoja {
			cursor: pointer;
			max-height: 600px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 993px) and (max-height: 1080px)  { /* Tela extra grande */
		.ScrollStylePBitacoraHoja {
			cursor: pointer;
			min-height: 875px;
			max-height: 875px;
			overflow-x: hidden;
			
		}
    }
    @media (min-height: 1080px)   { /* Tela extra grande */
		.ScrollStylePBitacoraHoja {
			cursor: pointer;
			min-height: 875px;
			max-height: 875px;
			overflow-x: hidden;
			
		}
    }


    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	    color: #000;
	    background-color: #b8daff;
	}
	.navIndicacion{
		font-weight: 500; font-size: 14px; padding: 0.4rem 0rem; border-radius: 0rem !important;
	}
	.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
	    color: #000;
	    background-color: #ffffff;
	}
	.btn:focus {
	    outline: 0;
	    box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 0%);
	}
	.card {
		font-weight: 400;
		border: 0;
		-webkit-box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
		box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12);
	}
	.testimonial-card .avatar {
	    width: 120px;
	    margin-top: -60px;
	    overflow: hidden;
	    border: 5px solid #fff;
	    border-radius: 50%;
	}
</style>
   <style>
        #menu2 {
            transition: width 0.3s ease;
        }
        #info2 {
            transition: width 0.3s ease;
        }
        .mifuente22{
        	font-size: 22px;
        }
    </style>
<form class="formularios" name="formulario_rce_medico" id="formulario_rce_medico" >

<script>
    $(document).ready(function() {
        $('#toggleMenu').click(function() {
            $('#menu2').toggleClass('d-none');
            $('#info2').toggleClass('col-md-9 col-md-12');

            let icon = $('#menuIcon');
            if ($('#menu2').hasClass('d-none')) {
                icon.removeClass('fa-caret-square-down').addClass('fa-caret-square-up');
            } else {
                icon.removeClass('fa-caret-square-up').addClass('fa-caret-square-down');
            }
        });
    });
</script>


	<input type="hidden" id="dau_id" 		name="dau_id" 			value="<?php echo $parametros['dau_id'];?>">
	<input type="hidden" id="tipoMapa" 		name="tipoMapa" 		value="<?php echo $parametros['tipoMapa'];?>">
	<input type="hidden" id="idctacte" 		name="idctacte" 		value="<?php echo $datosU[0]['idctacte'];?>">
	<input type="hidden" id="id_paciente" 	name="id_paciente" 		value="<?php echo $datosU[0]['id_paciente'];?>">
	<input type="hidden" id="rce_id" 		name="rce_id" 			value="<?php echo $parametros['rce_id'];?>">
	<input type="hidden" id="salaCama" 		name="salaCama" 		value="<?php echo $_SESSION['datosPacienteDau']['salaCama'];?>">
	<input type="hidden" id="rut" 			name="rut" 				value="<?php echo $datosU[0]['rut'];?>">
	<input type="hidden" id="id_cama" 		name="id_cama" 			value="<?php echo $_SESSION['datosPacienteDau']['cam_id'];?>">
	<input type="hidden" id="estadoDau" 	name="estadoDau" 		value="<?php echo $_SESSION['datosPacienteDau']['est_id'];?>">
	<input type="hidden" id="tipoAtencion" 	name="tipoAtencion" 	value="<?php echo $_SESSION['datosPacienteDau']['dau_atencion'];?>">
	<div class="row form-group">
		<div class="col-lg-10   text-secondary  " style="font-size: 20px;">
			
			<button type="button"   class="btn margin-6 btn-sm btn-outline-primary volverWorklist_detalle mifuente"><i class="fas fa-chevron-left "></i>&nbsp;&nbsp;Atrás</button>
			&nbsp;&nbsp; DAU N° <?=$parametros['dau_id']?>  <svg aria-hidden="true" focusable="false" data-prefix="fad" style="color: #0069d9 !important;" data-icon="grip-lines-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" class="svg-inline--fa fa-grip-lines-vertical fa-w-8 "><g class="fa-group"><path fill="currentColor" d="M224,16V496a16,16,0,0,1-16,16H176a16,16,0,0,1-16-16V16A16,16,0,0,1,176,0h32A16,16,0,0,1,224,16Z" class="fa-secondary"></path><path fill="currentColor" d="M96,16V496a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V16A16,16,0,0,1,48,0H80A16,16,0,0,1,96,16Z" class="fa-primary"></path></g></svg>
			 <?=$buscarCamaYsala[0]['sal_descripcion'];?> C-<?=$buscarCamaYsala[0]['cam_descripcion'];?><i class="fas fa-map-marker-alt ml-1 text-danger mifuente19 throb"></i>
		</div>

		<div class="col-lg-2   text-secondary text-right pr-4" style="font-size: 20px;">
			<button type="button"  id="toggleMenu" class="btn btn-sm btn-outline-primarydiag" ata-toggle="tooltip" data-placement="top" title="Ver/Ocultar Menú" style="border-color: #007bff00;padding: 0rem 0.5rem;"><i id="menuIcon" class="far fa-caret-square-down mifuente22"></i>&nbsp;&nbsp;Menú</button>
		</div>
	</div>
	<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-11" id="info2">
			<div class="row equal">
				<div class="col-md-3">
					<div class="row " style="height: 100%" >
						<div class="col-lg-12"  style="height: 100%">
							<div class="card testimonial-card" style="height: 100%"  >
		          				<div class="card-up indigo lighten-1"> 
		          					<div class="row">
		          						<div class="col-lg-9">
		          						</div>
		          						<div class="col-lg-3 mt-2">
											<span class="align-middle">
												<button id="btn_historial_link" type="button" class="btn btn-sm btn-outline-primarydiag btn_historial_link" data-toggle="tooltip" data-placement="top" title="Ver Historial Clínico" style="border-color: #007bff00;padding: 0rem 0.5rem;"   ><i class="fas fa-laptop-medical  mifuente18 "></i>
												</button>
											</span>
		          						</div>
		          					</div>
								</div>
								<div class="avatar mx-auto white mt-0">
									<img src="../estandar/iconoPersonas/<?= $imgSexo;?>.png" style="width: 100px;top: -22px; position: absolute; " class="rounded-circle" >
								</div>
	          					<div class="card-body pt-4">
						            <div class="row">
						            	<div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Nombre</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?php if($datosU[0]['transexual'] == 'S'){ ?>
										<i class="fas fa-venus-mars " style="color:#dd3bd1;"></i>
										<?php  } ?>
										<?=$infoInputLabel['input']?></label>
							            </div>
						            	<div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>RUN</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?=$runPaciente?></label>
							            </div>
							            <div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Edad</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?=$datosU[0]['dau_paciente_edad'].' años ('.$objUtil->fechaInvertida($datosU[0]['fechanac']).' )';?></label>
							            </div>
							            <div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Religión</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?= isset($datosU[0]['religion_descripcion']) ? $datosU[0]['religion_descripcion'] : '-'; ?></label>
							            </div>
							            <div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Atención</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $tiposAtencion[$datosU[0]["dau_atencion"]];?></label>
							            </div>
							            <div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Mot. Consulta</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $tiposConsultas[$datosU[0]["dau_motivo_consulta"]];?></label>
							            </div>
							            <div class="col-md-12 ">
							            	<label style="font-weight: normal; margin-bottom: 0rem !important; font-size: 11px;" for="inputApellido"><b>Detalle</b>&nbsp;&nbsp;:&nbsp;&nbsp;<?php echo $datosU[0]['dau_motivo_descripcion'] ?></label>
							            </div>
						            </div>
						            <div id="divCatEsi" class="mt-2" >
										<table class="table table-sm table-borderless" >
											<thead>
												<tr>
													<td  class="mifuente12" style="background-color: #007bff;color: white;">
														<center>CAT</center>
													</td>
													<?php
													if ($datosU[0]['dau_categorizacion_actual'] == "") {
													?>
													<td align="center" colspan="3" class="mifuente12 my-1 py-1 mx-1 px-1 <?php echo $clase;?>">Paciente sin categorización</td>
													<?php
													} else { ?>
													<td align="center" class="mifuente12 my-1 py-1 mx-1 px-1 <?php echo $clase;?> align-items-center">
														<span class="text-center w-100"><?php echo $datosU[0]['dau_categorizacion_actual'];?></span>
														
														<button id="btnDau" type="button" class="btn float-right btn-sm btn-outline-primarydiag btnDau" data-toggle="tooltip" data-placement="top" title="Detalle de Categorización" style="border-color: #007bff00;padding: 0rem 0.5rem;"   ><i class="fa fa-search   mifuente18 "></i>
														</button>


														<!-- <i id="btnDau" name="btnDau" class=" float-right mr-2 fa fa-search btnDau text-primary mifuente16 botonesActivos"  aria-hidden="true" data-toggle='tooltip' data-placement='top' data-original-title='Detalle de Categorización' style="cursor: pointer;"></i> -->
													</td>
													<?php }?>
												</tr>
												<tr>
													<td  class="mifuente12" style="background-color: #007bff;color: white;">
														<center>Fecha</center>
													</td>
													<td align="center" class=" my-1 py-1 mx-1 px-1 mifuente12 <?php echo $clase;?>">
														<?php
														echo ($objUtil->existe($datosU[0]['dau_categorizacion_fecha'] ))
															? date("d-m-Y H:i:s", strtotime($datosU[0]['dau_categorizacion_fecha']))
															: "Sin fecha de categorización";
														?>
													</td>
												</tr>
											</thead>
										</table>
									</div>
								
									<div class="row mt-3">
										<div id="divAnte" class="col-md-12 ">
											<div id="accordion">
											<?php  for ($i=0; $i <count($rsIndicaciones); $i++) {
												$parametros['id_indicaciones'] = $rsIndicaciones[$i]['tipAntId'];
												$rceIndi 		= $objRce->antecedenteIngresado($objCon,$parametros);
												$numAntecedente = count($rceIndi);
											?>
												<div class="card " >
												    <div class="card-reader45  text-dark" id="heading<?=$i?>"   style="background-color: #b8daff; padding: 0rem !important;">
												    	<div class="row">
												    		<div class="col-lg-8 col-md-9 text-primary1 mifuente12">
												    			<a class="btn   btn-link text-primary1 mifuente12" data-toggle="collapse" data-target="#collapse<?=$i?>" aria-expanded="true" aria-controls="collapse<?=$i?>">
														        <i class="fas fa-folder-open text-primary1"></i>&nbsp;&nbsp;
														        <?=$rsIndicaciones[$i]['tipAntDescripcion']?>
														        <?php if ($numAntecedente > 0){ ?>&nbsp;<span class="badge badge-primary"> <?php echo "&nbsp;".$numAntecedente."&nbsp;";?> </span>
														    	<?php } ?>
														        </a>
												    		</div>
												    		<div class="col-lg-4 text-right col-md-3 text-primary1 mifuente12"   >
												    			<button id="agrAntecedente<?=$rsIndicaciones[$i]['tipAntId']?>" type="button" class="btn float-right btn-sm btn-outline-primarydiag agregarAnt" data-toggle="tooltip" data-placement="top" title="Detalle de Categorización" style="border-color: #007bff00;padding: 0rem 0.5rem;"   ><i class="fa fa-folder-plus   mifuente15 "></i>
												    		</div>
												  		</div>
												    </div>
												    <div id="collapse<?=$i?>" class="collapse " aria-labelledby="heading<?=$i?>" data-parent="#accordion">
												    	<div class="card-body" style="padding: 0.55rem !important;">
													        <table id="detalleAnt1" class="table  infoAntecedentes mifuente11" style="margin-bottom: 0rem !important;">
																<tbody class="mifuente11">
																	<?php 
																		for($a=0;$a<count($rceIndi);$a++){
																			$parametros['diagcie10'] = $rceIndi[$a]['pac_ant_descripcion'];
																			if($parametros['id_indicaciones']==10){
																				$arrCie10 = $objRce->infoCIE10($objCon,$parametros);
																				$descripcion = $arrCie10[0]['codigoCIE'].' '.$arrCie10[0]['nombreCIE'];
																			}else{
																					$descripcion = $rceIndi[$a]['antDescripcion']." : ".$rceIndi[$a]['pac_ant_descripcion'];
																				}
																			?>
																			<tr <?php echo $disabled; ?>  >
																				<td id="tdAnte" class ="my-1 py-1 mx-1 px-1" ><?= $descripcion; ?></td>
																				<td id="tdAnte" class ="my-1 py-1 mx-1 px-1" width="35%"><?php  if($rceIndi[$a]['pac_ant_fecha_inicio'] == "0000-00-00" || $rceIndi[$a]['pac_ant_fecha_inicio'] == ""){echo "Sin fecha";}else{ echo $objUtil->fechaInvertida($rceIndi[$a]['pac_ant_fecha_inicio']); } ?></td>
																			</tr>
																		<?php   } 
																		if(count($rceIndi) == 0){ ?>
																			<tr> <td colspan="2" class="text-center my-1 py-1 mx-1 px-1"> No hay Información registrada. </td></tr> 
																		<?php } ?>
																</tbody>
															</table>
												      	</div>
												    </div>
												</div>
											<?php  }?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php if ( is_null($datosDAU[0]['dau_inicio_atencion_fecha']) || empty($datosDAU[0]['dau_inicio_atencion_fecha']) ) { $claseInicio = "col-md-9"; $ocultarBitaroca = "hidden ";
					$inicioAtencion = 1;
				}else{ $claseInicio = "col-md-3";}
				?>
				<div class="<?=$claseInicio?>" style="padding-left: 0px !important; padding-right: 0px !important;">
					<div class="row "  style="height: 30%">
						<div class="col-lg-12 " style="height: 100%">
							<div class="card testimonial-card " style="height: 100%" >
								<div class="card-up indigo lighten-1" style="    height: 35px;">

									<div class="row m-2">
										<div class="col-lg-10">
											<h4 class="card-title " style="font-size: 14px !important;"><i class="fas fa-file-medical-alt text-primary mifuente18 mr-2"></i> &nbsp;Diagnósticos</h4>
										</div>
										<div class="col-lg-2">
											<span class="align-middle"><button type="button"  class="btn btn-sm btn-outline-primarydiag btn_add_diagnostico" style="border-color: #007bff00;padding: 0rem 0.5rem;" <?php echo $disabled; ?>  ><i class="fas fa-plus  mifuente18 "></i></button></span>
										</div>
										
									</div>
								</div>
								<div class="card-body pt-3 pb-0">
									<?php if( count($rsRce_diagnostico) > 0 ){ ?>
									<div id='div_diagnostico'></div>
									<?php } else{?>
									<div id='div_diagnostico'></div>
									<div class="alert alert-light text-center mifuente" role="alert">
									  No hay diagnósticos disponibles en este momento. <br>¿Desea agregarlo? <span class="align-middle"><br><button  type="button" class=" mt-2 btn btn-sm mifuente  btn-outline-primary btn_add_diagnostico" <?php echo $disabled; ?>   > Agregar Diagnósticos</button></span>
									</div>
								<?php } ?>
								</div>
							</div>
							
						</div>
					</div>
					<div class="row "  style="height: 70%">
						<div class="col-lg-12 " style="height: 100%">
							<div class="card testimonial-card " style="height: 100%" >
								<div class="card-up indigo lighten-1" style="    height: 35px;">
									<div class="row m-2">
										<div class="col-lg-12">
											<h4 class="card-title " style="font-size: 14px !important;"><i class="fas fa-file-medical-alt text-primary mifuente18 mr-2"></i> &nbsp;Evolución </h4>
										</div>
									</div>
								</div>
							    <div class="card-body pt-3 pb-0">
									<div class=" row mt-0 ScrollStylePEvo mb-0 mb-1" style="padding-right: 0px; padding-left: 0px;">
										<?php if( $inicioAtencion == 1) { ?>
										<input type="hidden" id="inicioAtencion" name="inicioAtencion" value="0" />
										<div id="div_inicioAtencion" class="col-lg-12  " >
						                </div>
										<?php  } else { ?>
										<div class="col-lg-12 mb-2 " style="padding-right: 0px; padding-left: 0px;">
											<div class="tab-content " id="myTabContent">
												<textarea class="form-control form-control-sm mifuente12 " rows="18" id="frm_historia_clinica" name="frm_historia_clinica" placeholder="Historia Clínica..." oninput="filterInput_libre(event)" onpaste="handlePaste_libre(event)" ></textarea>
											</div>
										</div>
										<div class="col-lg-3">
										</div>
										<div class="col-lg-9"> <button id="btn_agregar_evolucion" type="button" <?php echo $disabled;?> name="btn_agregar_evolucion" class="btn btn-sm btn-outline-primarydiag  mifuente11 col-lg-12 text-center" ><i class="fas fa-plus mr-2"></i>Agregar Evolución</button> </div>
										<?php  }  ?>
									</div>
				    			</div>
				    		</div>
						</div>
					</div>
				</div>
				<div class="col-md-6" <?=$ocultarBitaroca?> >
					<div class="row " style="height: 100%" >
						<div class="col-lg-12" style="height: 100%">
							<div class="card testimonial-card" style="height: 100%" >
								<nav class="nav nav-pills flex-column flex-sm-row" style="background-color: #b8daff;">
  									<a class="flex-sm-fill text-sm-center nav-link navIndicacion active"  id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i>Bitacora</a>
  									<a class="flex-sm-fill text-sm-center nav-link  navIndicacion"   id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" ><i class="fas fa-bookmark text-primary mifuente18 mr-3"></i> Indicaciones</a>
								</nav>
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
								  		<div id='div_bitacora' class="ScrollStylePBitacora mb-1" style="background-color: white;" ></div>
								  		
									</div>
									<div class="tab-pane fade " id="home" role="tabpanel" aria-labelledby="home-tab">
								  		<div id='div_indicacion' class="ScrollStylePBitacora mb-1" style="background-color: white;" ></div>
								  	</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-1" id="menu2">
			
			<!-- <hr class="m-2"> -->
			<div class="row">
				<div class="col-md-12">
					<button id="btnSignosVitales" name="btnSignosVitales" type="button" class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Signos Vitales"><i class="fas fa-heartbeat throb" style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Signos Vitales</label></button>
				</div>
				<div class="col-md-12">
					<button id="btnAgregarIndicaciones" name="btnAgregarIndicaciones" type="button" <?php echo $disabled;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Indicaciones"><i class="fas fa-hand-holding-medical " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Indicaciones</label></button>
				</div>
				<!-- <div class="col-md-12">
					<button id="btnAgregarEvolucion" name="btnAgregarEvolucion" type="button" <?php echo $disabled;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Evolución"><i class="fas fa-comment-medical " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Evolución</label></button>
				</div> -->

				<div class="col-md-12">
					<button id="btnAgregarEspecialista" name="btnAgregarEspecialista" type="button" <?php echo $disabled;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Especialista"><i class="fas fa-users " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Especialista</label></button>
				</div>
				<!-- <div class="col-md-12">
					<button id="btnAgregarEspecialistaOtro" name="btnAgregarEspecialistaOtro" type="button" <?php echo $disabled;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Especialista"><i class="fas fa-users " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Otro Especialista</label></button>
				</div> -->
				
				<div class="col-md-12">
					<button id="formulariosEnfermeria" name="formulariosEnfermeria" type="button"  class="btn btn-primary btn-sm mb-2 btn-block formulariosEnfermeriaMenu"  data-toggle="tooltip" data-placement="left" title="Hoja Hospitalización"><i class="fas fa-file-signature " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Formularios</label></button>
				</div>
				<div class="col-md-12">
					<?php if (!$transfusionesHabilitado): ?>
					<div id="countdownTransfusiones" class="text-center mifuente11 text-danger" style="font-size: 9px !important; margin-top: 2px;">
						<span id="diasTransfusiones">--</span>d <span id="horasTransfusiones">--</span>h <span id="minTransfusiones">--</span>m <span id="segTransfusiones">--</span>s
					</div>
					<?php endif; ?>
					<button id="btnTransfusiones" name="btnTransfusiones" <?php echo $disabledTransfusiones;?> type="button"  class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Transfusiones"><i class="fas fa-tint " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Transfusiones</label></button>
					
				</div>
				<div class="col-md-12">
					<button id="btnTiemposAtencion" name="btnTiemposAtencion" type="button" class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Tiempos"><i class="fas fa-stopwatch " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Tiempos</label></button>
				</div>
				<div class="col-md-12">
					<button id="btnAltaUrgencia" name="btnAltaUrgencia" type="button" <?php echo $disabledAltaUrgencia;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Alta Urgencia"><i class="fas fa-file-medical " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Alta Urgencia</label></button>
					<hr class="m-2">
				</div>
				<div class="col-md-12">
					<button id="btnAplicarEgreso" name="btnAplicarEgreso" type="button" <?php echo $disabledAplicarEgreso;?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Aplicar Egreso"><i class="fas fa-clinic-medical " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Aplicar Egreso</label></button>
				</div>
				<div class="col-md-12">
					<button id="aplicarNEA" name="aplicarNEA" type="button" <?php
					if (
						$_SESSION['datosPacienteDau']['est_id'] == 4
						|| $_SESSION['datosPacienteDau']['est_id'] == 5
						|| $_SESSION['datosPacienteDau']['est_id'] == 6
						|| $_SESSION['datosPacienteDau']['est_id'] == 7
						
					) {
						echo "disabled";
					} ?> class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="N.E.A."><i class="fas fa-bullhorn " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >N.E.A.</label></button>
					<hr class="m-2">
				</div>

				<?php if (pacienteTieneIndicacionAltaHospitalizacion($existeSolicitudAltaUrgencia)) { ?>
				<div class="col-md-12">
					<button id="<?php echo $parametros['id_dau'] ?>" name="verHojaHospitalizacion" type="button"  class="btn btn-success btn-sm mb-2 btn-block verHojaHospitalizacion"  data-toggle="tooltip" data-placement="left" title="Hoja Hospitalización"><i class="fas fa-file-pdf " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Hospitalización</label></button>
				</div>
				<?php } ?>
				<?php if (pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) ){ ?>
				<div class="col-md-6" style="padding-right: 0px !important; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;">
					<button id="btnVerRCE" name="btnVerRCE" type="button"  class="btn btn-success btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Ver RCE" style="border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" ><i class="fas fa-file-pdf mt-1" style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" > RCE</label></button>
				</div>
				<?php } else { ?>
					<div class="col-md-6" style="padding-right: 0px !important; border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;">
					<button id="btnVerRCEIncompleto" name="btnVerRCEIncompleto" type="button"  class="btn btn-success btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Ver RCE" style="border-top-right-radius: 0px !important; border-bottom-right-radius: 0px !important;" ><i class="fas fa-file-pdf mt-1" style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" > RCE</label></button>
				</div>
				<?php } ?>
				<div class="col-md-6" style="padding-left: 0px !important; border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;">
					<button id="<?php echo $parametros['id_dau'] ?>" name="verDetalleDau" type="button"  class="btn btn-success btn-sm mb-2 btn-block verDetalleDau"  data-toggle="tooltip" data-placement="left" title="Detalle DAU" style="border-top-left-radius: 0px !important; border-bottom-left-radius: 0px !important;" ><i class="fas fa-file-pdf mt-1" style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" > DAU</label></button>
				</div>
				<?php
			if (
				!pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])
				||(
					pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id'])
					&& cumpleCondicionesParaDesplegarHospitalAmigo($parametros["dau_id"])
				)
			) {
			?>
				<div class="col-md-12">
					<button id="<?php echo $parametros['id_dau'] ?>"  type="button"  class="btn btn-success btn-sm mb-2 btn-block verHospitalAmigo"  data-toggle="tooltip" data-placement="left" title="Hoja Hospitalización"><i class="fas fa-user-friends" style="font-size: 16px !important;"></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Hospital Amigo</label></button>
				</div>
				<?php } ?>
				<?php if ( pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) && cumpleCondicionesParaDesplegarRecetaGES($rsCumpleReceta) ) { ?>
				<div class="col-md-12">
					<button id="recetaGES-<?php echo $parametros['id_dau'];?>" name="verRecetaGES" type="button"  class="btn btn-success btn-sm mb-2 btn-block verRecetaGES"  data-toggle="tooltip" data-placement="left" title="Receta GES"><i class="fas fa-pills " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Receta GES</label></button>
				</div>
				<?php } ?>
				<?php 
				// if (pacienteNoTieneInicioAtencion($datosDAU[0]['dau_inicio_atencion_fecha']) || pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) || pacienteTieneEstadoDauCerrado($datosDAU[0]['est_id']) ) {
					// $disabled = 'disabled';
				// } 
				?>
				<!-- <div class="col-md-12">
					<button id="btnCargarPlantillas" name="btnCargarPlantillas" type="button"  class="btn btn-primary btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Cargar Plantillas" <?php echo $disabled; ?> ><i class="fas fa-sticky-note " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Cargar Plantillas</label></button>
					<hr class="m-2">
				</div> -->
				<div class="col-md-12">
					<button id="btnEntregaTurno" name="btnEntregaTurno" type="button"  class="btn btn-danger btn-sm mb-2 btn-block "  data-toggle="tooltip" data-placement="left" title="Entrega Turno" <?php echo $disabled; ?> ><i class="fas fa-user-friends " style="font-size: 16px !important;" ></i><br><label style="font-size: 9px !important; margin-bottom: 0rem !important; margin-top: 0.2rem !important;" >Entrega Turno</label></button>
				</div>
			</div>	
		</div>
	</div>
	</div>
</form>
<?php
function cumpleCondicionesParaDesplegarRecetaGES($rsCumpleReceta) {

	return $rsCumpleReceta[0]["cumple"] === "S";
}
function tipoIndicacionEsHospitalizacion($tipoSolicitud) {

	$hospitalizacion 		= 4;
	return $tipoSolicitud 	== $hospitalizacion;
}
function pacienteTieneIndicacionAltaHospitalizacion($existeSolicitudAltaUrgencia) {

	return ( pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) && tipoIndicacionEsHospitalizacion($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) );
}
function pacienteNoTieneInicioAtencion($dau_inicio_atencion_fecha) {

	return ( is_null($dau_inicio_atencion_fecha) || empty($dau_inicio_atencion_fecha) );
}
function pacienteTieneIndicacionAlta($existeSolicitudAltaUrgencia) {
    // Verificamos que el arreglo no esté vacío y que el índice 0 exista
    return isset($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && 
           !is_null($existeSolicitudAltaUrgencia[0]['tipoSolicitud']) && 
           !empty($existeSolicitudAltaUrgencia[0]['tipoSolicitud']);
}
function pacienteTieneEstadoDauCerrado($estadoDau) {

	return ( $estadoDau == 5 || $estadoDau == 6 || $estadoDau == 7 );
}
function tiempoPermitidoIndicacionEgreso($respuestaConsulta,$fechaServidor) {
	if ( !empty($respuestaConsulta['dau_indicacion_egreso_fecha']) && !is_null($respuestaConsulta['dau_indicacion_egreso_fecha']) ) {
		$intervaloTiempo 	= strtotime($fechaServidor) - strtotime($respuestaConsulta['dau_indicacion_egreso_fecha']);
		$tiempoPermitido 	= 1800;
		return ($intervaloTiempo > $tiempoPermitido && $respuestaConsulta['dau_indicacion_egreso'] == 4)
			? false
			: true;
	}
	return true;
}
function pacienteNoTieneIndicacionEgreso($estadoDau){

	return ($estadoDau != 4);
}
function cumpleCondicionesParaDesplegarHospitalAmigo($idDau) {
	require_once('../../../class/Connection.class.php');
	require_once('../../../class/Util.class.php');
	require_once('../../../class/HospitalAmigo.class.php');

	$objCon 		= new Connection;
	$util 			= new Util;
	$hospitalAmigo 	= new HospitalAmigo;
	$objCon->db_connect();
	$resultado 		= $hospitalAmigo->obtenerAcompaniante($objCon, array("idDau" => $idDau));

	return ($util->existe($resultado[0]["idDauAcompaniante"]));
}

?>