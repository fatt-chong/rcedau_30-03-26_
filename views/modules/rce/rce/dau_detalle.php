<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
$permisos = $_SESSION['permiso'.SessionName];
require_once('../../../../class/Connection.class.php');     $objCon  = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php'); 			$objUtil = new Util;
require_once('../../../../class/Rce.class.php'); 			$objRce  = new Rce;
require_once('../../../../class/Dau.class.php'); 			$objDau  = new Dau;

$dau_id 			= $_POST['dau_id'];
$datosPaciente 		= $objDau->obtenerDatosDetalleDauDesplegarCategorizacion($objCon, $dau_id);
// print('<pre>'); print_r($_SESSION['datosPacienteDau']); print('</pre>');
// print('<pre>'); print_r($datosPaciente); print('</pre>');
$datosUrgencia 		= $objRce ->ListarDatosAtencion($objCon, $dau_id);
$version 			= $objUtil->versionJS();

// print('<pre>'); print_r($datosUrgencia 	); print('</pre>');
$datosPaciente['nombreCompletoPaciente']     = $objUtil->DatoPacienteTrans($datosPaciente['transexual'],$datosPaciente['nombreSocial'],$datosPaciente['nombreCompletoPaciente']); 
?>
<script type="text/javascript">

$(document).ready(function(){
	$('#btn_recategorizar').on('click', function ( ) {
		// alert( $('#tipoMapa').val() );
		// if ( perfilUsuario == 'gestionCama' ) {
		// 	return;
		// }
		// let idDau 				= $(this).attr('id');
		// parametroIdDau.idDau 	= idDau;
		var idDau = $('#dau_id').val();
		if ( $('#tipoMapa').val() == 'mapaGinecologico' ) {
			let botones = 	[
				{ id: 'btnCategorizarSDD', value: 'Categorizar', class: 'btn btn-primary' }
			];
			modalFormulario("<label class='mifuente'>Categorizar Paciente  </label>",raiz+'/views/modules/rce/categorizacion/categorizacionSDD.php',`t_trid=${idDau}&recategorizar=S`,'#categorizarPaciente',"modal-lg","light","fas fa-minus text-primary-light mifuente16",botones);
	
			return;
		}
		modalFormulario_noCabecera('', raiz+"/views/modules/rce/categorizacion/categorizacion.php", `t_trid=${idDau}&bandera_acceso=DAU`, "#modalDetalleCategorizacion", "modal-lg", "", "fas fa-plus",'');
	});
});

</script>

<form id="form_signos_vitales" name="form_signos_vitales" class="formularios mr-3 ml-3" role="form" method="POST">
        <div class="row mb-2 align-items-center">
        <div class="col-auto">
            <label class="text-secondary ml-3 mb-0">
                <i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> 
                Detalle de Categorización
            </label>
        </div>
        <?php if ( array_search(1746, $permisos) != null ) { ?> 
        <?php if($datosPaciente['est_id'] == 8) { ?>
        <div class="col-auto">
            <button type="button" id="btn_recategorizar" class="btn btn-sm btn-outline-primary ml-2">
                <i class="fas fa-sync-alt mr-1"></i> Recategorizar
            </button>
        </div>
        <?php } ?>
        <?php } ?>
    </div>

    
<!-- <form id="form_signos_vitales" name="form_signos_vitales" style="margin-bottom: 0px !important;"> -->

<input type="hidden" id="dau_id" name="dau_id" value="<?php echo $_POST['dau_id']; ?>">
<!-- <label style="font-size: 18px; font-weight: 500;"> Detalle de Categorización</label> -->
	<div class="bd-callout bd-callout-warning border-bottom">
		<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos del Paciente <b>( DAU # <?= $datosPaciente['dau_id'];?>)</b></h6>
		<div class="row pr-2 pl-2">
			<div class="col-lg-2 ">
				<p class="m-0 p-0 mifuente">Nombre Paciente</p>
			</div>
			<div class="col-lg-5 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?= $datosPaciente['nombreCompletoPaciente'];?></label></p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Edad</p>
			</div>

			<div class="col-lg-4 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?=$objUtil->edadActualCompleto($datosPaciente['fechanac']);?></label> </p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Religión</p>
			</div>
			<div class="col-lg-4 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?= isset($datosPaciente['religion_descripcion']) ? $datosPaciente['religion_descripcion'] : '-'; ?></label> </p>
			</div>
			<div class="col-lg-2">
				<p class="m-0 p-0 mifuente">Motivo Consulta </p>
			</div>

			<div class="col-lg-10 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?=$datosPaciente['descripcionConsulta'].' - '.$datosPaciente['detalle'];?></label> </p>
			</div>
		</div>
	</div>
	<!-- <hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; "> -->
	<div class="bd-callout bd-callout-warning border-bottom">
		<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Datos Epidemiológicos</h6>
		<input type="hidden" id="viajeOProcedencia" value="<?php echo $datosPaciente['dau_viaje_epidemiologico']; ?>">
    	<input type="hidden" id="pais" value="<?php echo $datosPaciente['dau_pais_epidemiologia']; ?>">
    	<input type="hidden" id="observacion" value="<?php echo $datosPaciente['dau_observacion_epidemiologica']; ?>">
		<div class="row pr-2 pl-2">
			<div class="col-lg-12 ">
				<p class="m-0 p-0 mifuente">¿Viaje o procedencia del extranjero en el último mes? :<label class="ml-2 texto-valor mb-0 " ><?php echo ($datosPaciente["dau_viaje_epidemiologico"] === "S") ? "Si" : "No"; ?></label> </p>
			</div>
			<?php if ($datosPaciente['dau_viaje_epidemiologico'] == 'S') { ?>
			<div class="col-lg-6 ">
				<p class="m-0 p-0 mifuente">País :<label class="ml-2 texto-valor mb-0 " ><?php echo ucwords($datosPaciente['dau_pais_epidemiologia']); ?></label> </p>
			</div>
			<div class="col-lg-6 ">
				<p class="m-0 p-0 mifuente">Observaciones :<label class="ml-2 texto-valor mb-0 " ><?php echo $datosPaciente["dau_observacion_epidemiologica"]; ?></label> </p>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php if($datosUrgencia[0]['cat_tipo'] != "SDD") {  ?>
	<!-- <hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; "> -->
	<div class="bd-callout bd-callout-warning border-bottom">
		<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Etapa 1</h6>
		<div class="row pr-2 pl-2  pb-2">
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Respuesta 
				</p>
			</div>
			<div class="col-lg-11 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e1_respuesta'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e1_respuesta'] == "S" ) {
						echo "Si";
					} else if ( $datosUrgencia[0]['dau_cat_e1_respuesta'] == "N" ) {
						echo "No";
					}
					?></label> 
				</p>
			</div>
		</div>
		<hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; ">
		<h6 id="ensure-correct-role-and-provide-a-label  " style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Etapa 2</h6>
		<div class="row pr-2 pl-2 pb-2">
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">AVDI 
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente"> :<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e2_avdi'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e2_avdi'] == 1 ) {
						echo "Alerta";
					} else if ( $datosUrgencia[0]['dau_cat_e2_avdi'] == 2 ) {
						echo "Respuesta verbal";
					} else if ( $datosUrgencia[0]['dau_cat_e2_avdi'] == 3 ) {
						echo "Respuesta al dolor";
					} else if ( $datosUrgencia[0]['dau_cat_e2_avdi'] == 4 ) {
						echo "Inconsciente";
					}
					?></label> 
				</p>
			</div>

			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Distresado 
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">&nbsp;&nbsp;&nbsp;:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e2_distresado'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e2_distresado'] == "S" ) {
							echo "Si";
					} else if ( $datosUrgencia[0]['dau_cat_e2_distresado'] == "N" ) {
							echo "No";
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">EVA 
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e2_eva'] == "" ) {
						echo "-";
					} else {
						echo $datosUrgencia[0]['dau_cat_e2_eva'];
					}
					?></label> 
				</p>
			</div>
		</div>
		<hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; ">
		<h6 id="ensure-correct-role-and-provide-a-label  " style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Etapa 3</h6>
		<div class="row pr-2 pl-2 pb-2">
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Respuesta
				</p>
			</div>
			<div class="col-lg-11 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e3_respuesta'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e3_respuesta'] == 1 ) {
							echo "Ninguno";
					} else if ( $datosUrgencia[0]['dau_cat_e3_respuesta'] == 2 ) {
							echo "Uno";
					} else if ( $datosUrgencia[0]['dau_cat_e3_respuesta'] == 3 ) {
						echo "Varios";
					}
					?></label> 
				</p>
			</div>
		</div>
		<hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; ">
		<h6 id="ensure-correct-role-and-provide-a-label  " style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Etapa 4</h6>
		<div class="row pr-2 pl-2 ">
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">SaO2 
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_sao2'] == "" ) {
						echo "-";
					} else {
						echo $datosUrgencia[0]['dau_cat_e4_sao2'];
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">FR
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">&nbsp;&nbsp;&nbsp;:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_frecuencia_respiratoria'] == "" || $datosUrgencia[0]['dau_cat_e4_frecuencia_respiratoria'] == NULL ) {
						echo "-";
					} else {
						echo $datosUrgencia[0]['dau_cat_e4_frecuencia_respiratoria'];
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">FC
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_frecuencia_cardiaca'] == "" ) {
						echo "-";
					} else {
						echo $datosUrgencia[0]['dau_cat_e4_frecuencia_cardiaca'];
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Tº 
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_temperatura'] == "" ) {
						echo "-";
					} else {
						echo $datosUrgencia[0]['dau_cat_e4_temperatura'];
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Inmunizaciones
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">&nbsp;&nbsp;&nbsp;:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_inmunizaciones'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e4_inmunizaciones'] == 1 ) {
							echo "Esquema completo";
					} else if ( $datosUrgencia[0]['dau_cat_e4_inmunizaciones'] == 2 ) {
						echo "Esquema incompleto";
					}
					?></label> 
				</p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Fiebre
				</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['dau_cat_e4_origen_fiebre'] == "" ) {
						echo "-";
					} else if ( $datosUrgencia[0]['dau_cat_e4_origen_fiebre'] == 1 ) {
							echo "Origen Determinado";
					} else if ( $datosUrgencia[0]['dau_cat_e4_origen_fiebre'] == 2 ) {
						echo "Origen no vidente";
					}
					?></label> 
				</p>
			</div>
		</div>
	</div>

	<?php }else{ ?>

		<div class="bd-callout bd-callout-warning border-bottom">
		<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Signos Vitales</h6>
		<div class="row pr-2 pl-2  pb-2">
			<div class="col ">
				<p class="m-0 p-0 mifuente">Presión 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php echo $datosUrgencia[0]['dau_cat_sdd_presion_1']; ?></label> 
				</p>
			</div>

			<div class="col ">
				<p class="m-0 p-0 mifuente">Pulso 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php echo $datosUrgencia[0]['dau_cat_sdd_presion_2']; ?></label> 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">Temperatura 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php echo $datosUrgencia[0]['dau_cat_sdd_temperatura']; ?>°C</label> 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">Saturometría
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php echo $datosUrgencia[0]['dau_cat_sdd_saturaciono2']; ?>SaO2</label> 
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">Temperatura Rectal
				</p>
			</div>
			<div class="col ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php echo $datosUrgencia[0]['dau_cat_sdd_temp_rectal']; ?>°C</label> 
				</p>
			</div>
		</div>
	</div>
	<?php } ?>
	<!-- <hr style="margin-top: 0.1rem; margin-bottom : 0.1rem; "> -->
	<div class="bd-callout bd-callout-warning ">
		<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Resultado</h6>
		<div class="row pr-2 pl-2">
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Cat </p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " >
					<?php
					if ( $datosUrgencia[0]['cat_id'] == "ESI-1" || $datosUrgencia[0]['cat_id'] == 'C1' ) {
						echo "C1";
					}else if ( $datosUrgencia[0]['cat_id'] == "ESI-2" || $datosUrgencia[0]['cat_id'] == 'C2' ) {
						echo "C2";
					}else if ( $datosUrgencia[0]['cat_id'] == "ESI-3" || $datosUrgencia[0]['cat_id'] == 'C3' ) {
						echo "C3";
					}else if ( $datosUrgencia[0]['cat_id'] == "ESI-4" || $datosUrgencia[0]['cat_id'] == 'C4' ) {
						echo "C4";
					}else if ( $datosUrgencia[0]['cat_id'] == "ESI-5" || $datosUrgencia[0]['cat_id'] == 'C5' ) {
						echo "C5";
					}
					?>
				</label> </p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Usuario </p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">&nbsp;&nbsp;&nbsp;:<label class="ml-2 texto-valor mb-0 " ><?=$datosUrgencia[0]['dau_cat_usuario_inserta'];?></label> </p>
			</div>
			<div class="col-lg-1 ">
				<p class="m-0 p-0 mifuente">Fecha</p>
			</div>
			<div class="col-lg-3 ">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?=date("d-m-Y",strtotime($datosUrgencia[0]['dau_cat_fecha']));?></label> </p>
			</div>
			<?php if($datosUrgencia[0]["dau_cat_obs_enfermera"] != ""){?>
			<div class="col-lg-2 mt-2">
				<p class="m-0 p-0 mifuente">Observación Enfermera </p>
			</div>
			<div class="col-lg-10 mt-2">
				<p class="m-0 p-0 mifuente">:<label class="ml-2 texto-valor mb-0 " ><?php echo $datosUrgencia[0]["dau_cat_obs_enfermera"]; ?></label> </p>
			</div>
			<?php } ?>
		</div>
	</div>
	<br>
	<?php if($_POST['btn'] != 'N') { ?>
	<hr style="margin-top: 0.3rem; margin-bottom: 0.8rem;">
	<div class="row pr-2 pl-2">
		<div class="col-lg-6">
		</div>
		<div class="col-lg-3"> <button id="btn_signos_vitales" type="button" name="btn_signos_vitales" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-heartbeat mr-2"></i>Signos Vitales</button> </div>
		<div class="col-lg-3"> <button id="btn_nea" type="button" name="btn_nea" class="btn btn-sm btn-primary2  col-lg-12 text-center" ><i class="fas fa-times mr-2"></i> N.E.A.</button> </div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		dau_id = $('#dau_id').val();

		$("#btn_signos_vitales").click(function(){
			modalFormulario_noCabecera('', raiz+"/views/modules/rce/signos_vitales/signos_vitales.php", 'dau_id='+dau_id+'&tipoMapa='+$('#tipoMapa').val(), "#modalSignosVitales", "modal-lgg", "", "fas fa-plus",'');
		});
		$("#btn_nea").click(function(){
			modalFormulario_noCabecera('', raiz+"/views/modules/rce/nea/modalAplicarNEA.php", 'dau_id='+dau_id, "#modalNEA", "modal-md", "", "fas fa-plus",'');
		});
	});
</script>
	<?php } ?>
<!-- <form id="form_signos_vitales" name="form_signos_vitales"> -->
</form>
