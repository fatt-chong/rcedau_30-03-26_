<?php


print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
 print('<pre>'); print_r($_SESSION['permiso'.SessionName]); print('</pre>');
 die();
error_reporting(0);
require_once('../../../class/Connection.class.php');	$objCon        = new Connection; $objCon->db_connect();
require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;
require_once('../../../class/Dau.class.php');  			$objDau   	   = new Dau;
require_once('../../../class/Rce.class.php');  			$objRce   	   = new Rce;
require_once('../../../class/Util.class.php');      	$objUtil       = new Util;
require_once('../../../class/Config.class.php');      	$objConfig     = new Config;
require("../../../config/config.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$permisos = $_SESSION['permisosDAU'.SessionName];
if ( array_search(831, $permisos) == null ) {$GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));}
ini_set('memory_limit', '1000M');

// $permisos = $_SESSION['permisosDAU'.SessionName];
if ( ! empty($_POST) && ! is_null($_POST) ) {
	$parametros	= $objUtil->getFormulario($_POST);
}
if ( ! empty($_GET) && ! is_null($_GET) ) {
	$parametros	= $objUtil->getFormulario($_GET);
}
$version    					= $objUtil->versionJS();
$datos          				= $objMapaPiso->loadTablaFull($objCon);
$rsCategorizacion          		= $objMapaPiso->SelectCategorizacion($objCon);

$rsUnidad          				= $objMapaPiso->SelectUnidad($objCon, $parametros['tipoMapa']);
$datosTriageAct 				= $objConfig->getTipoTriageActivo($objCon);
$listado 						= $objDau->listarDAUEspecialidadGinecologica($objCon);
$idusuario 						= $_SESSION['MM_Username'.SessionName];
$rshorario 						= $objUtil->getHorarioServidor($objCon);
$horaServidor 					=  $rshorario[0]['fecha']."T".$rshorario[0]['hora'];	
$tipoMapaUsuario 				= $objMapaPiso->consultarTipoMapaUsuarioUsuario($objCon, $idusuario);
$_SESSION['contadorColumnas'] 	= 2;
$_SESSION['contadorColumnas3'] 	= 3;
$_SESSION['contadorColumnas4'] 	= 4;
// print('<pre>'); print_r($rsCategorizacion); print('</pre>');
//Permisos
if ( array_search(822,$permisos) != null ) {
	$draggableLPE 				= 'true';
	$classLPE 					= 'tbl_esp ';
} else {
	$draggableLPE 				= 'false';
}
if ( array_search(821,$permisos ) != null ) {
	$draggableLPC 				= 'true';
	$classLPC 					= 'tbl_cat ';
} else {
	$draggableMP 				= 'false';
}
if ( array_search(820,$permisos) != null ) {
	$draggableMP 				= 'true';
	$classMP 					= 'camaMapaPiso ';
} else {
	$draggableMP 				= 'false';
}
// if ( array_search(1746,$permisos) != null ) {
// 	$pacienteEnEspera 				= 'pacienteEnEspera';
// }
//Recorrido de contenido
$SC2  							= 0;
$CAT2 							= 0;
for( $i = 0; $i < count($datos); $i++) {
	if ( $datos[$i]['est_id'] == '2' ) {
		$CAT2 					= $CAT2 + 1;
	}
	if ( $datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == '' ) {
		$SC2 					= $SC2 + 1;
	}
}
$nombre 						= $_SESSION['MM_Username'.SessionName];
$rut 							= $_SESSION['MM_RUNUSU'.SessionName];
$usuario        				= $_SESSION['MM_Username'.SessionName];
$permisosPerfil 				= $objConfig->cargarPermisoDau($objCon,$usuario);
if ( array_search(1746,$permisosPerfil) != null ) {
	$pacienteEnEspera 				= 'pacienteEnEspera';
}
?>
<script>
var categoriaMapeo = <?php echo json_encode($rsCategorizacion); ?>;
</script>

<style type="text/css">
	.tooltip-inner {
    max-width: 520px;      /* ancho máximo */
    overflow-x: auto;      /* scroll vertical si se pasa */
    text-align: left;      /* mejora lectura */
    white-space: normal;   /* por si acaso */
}
</style>
<script>
    var horaServidorMapa = '<?= $horaServidor ?>';
</script>
<script type="text/javascript" src="<?=PATH?>/controllers/client/mapa_piso_full/mapa_piso_full.js?v=<?=$version;?>1"></script>

<input type="hidden" name="tipoMapaUsuario"  id="tipoMapaUsuario"  value="<?php echo $tipoMapaUsuario['usu_conf_urgencia']; ?>">
<input type="hidden" name="tipoMapa"  		 id="tipoMapa"  	   value="<?php echo $parametros['tipoMapa']; ?>">

<div class="row mb-1" style="background-color:#ffffff00; ">
    <div class="col-md-3">
        <h1 class=" mb-0" style="font-size: 25px !important">Mapa de Piso</h1>
    </div>
    <div class=" col-md-9 text-right mt-1">
        <input class="form-check-input" type="checkbox" id="checkAtencionIniciadasPor" name="checkAtencionIniciadasPor">
        <label class="form-check-label" for="checkAtencionIniciadasPor">Atención Iniciadas por Mí</label>
    </div>
</div>
<div class="row" style="display: none;" >
	<div id="" class="col-md-4 has-feedback checkBoxsMapas">
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_adulto" name="frm_mp_adulto" type="checkbox" value="S">
				<label for="frm_mp_adulto"  class="control-label">
				    <strong>Adulto</strong>
				</label>
			</div>
		</div>
	</div>
	<div id="" class="col-md-4 has-feedback checkBoxsMapas">
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_pediatrico" name="frm_mp_pediatrico" type="checkbox" value="S">
				<label for="frm_mp_pediatrico"  class="control-label">
					<strong>Pediátrico</strong>
				</label>
			</div>
		</div>
	</div>
	<div id="" class="col-md-4 has-feedback checkBoxsMapas">
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_ginecologia" name="frm_mp_ginecologia" type="checkbox" value="S">
				<label for="frm_mp_ginecologia"  class="control-label">
					<strong>Ginecología</strong>
				</label>
			</div>
		</div>
	</div>
</div>
<div id="divMapapisoFull" class="row  content  " style="overflow-y: hidden;" >
	<div id="divTablaMapaPiso" class="well mt-2 col-lg-3  p-1 " >
		<div class="row">
			<div class=" mifuente12 col-lg-7">
				<input type="search" id="searchInput" class="form-control form-control-sm mifuente12" placeholder="Buscar..." >
			</div>
			<div class=" mifuente12 col-lg-5">
				<button data-search="next" class="btn btn-primary2 btn-sm" style=""><i class="fas fa-arrow-up"></i></button>
				<button data-search="prev" class="btn btn-primary2 btn-sm" style=""><i class="fas fa-arrow-down"></i></button>
				<button data-search="clear" class="btn btn-danger btn-sm" style=""><i class="fas fa-times"></i></button>
				<label id="lblResult" class="mifuente12" style="">0</label>
			</div>
		</div>
		<div class="row pac-list" id="contenidoPacientes">
			<input type="hidden" name="SC2"  id="SC2"  value="<?=$SC2;?>">
		 	<input type="hidden" name="CAT2" id="CAT2" value="<?=$CAT2;?>">
			<div class="col-md-12">
				<div id="pacienteTotal">
	  				<div class="row mt-1">
					  	<div class=" mifuente12 col-md-7" id="divSeleccione">
					  		<select id="frm_tipo_Atenciones" class="form-control form-control-sm mifuente12" style="">
								<option value="1">Todos</option>
								<option value="2">Adulto</option>
								<option value="3">Pediátrico</option>
								<option value="4">Ginecología</option>
								<option value="6">Indiferenciado</option>
							</select>
					  	</div>
					  	<div class=" mifuente12 col-md-5" id="divBtnFiltro">
							<button id="quitarFiltros" style="" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
					  	</div>
					</div>
					<div class="row mifuente11 mt-1" id="pacienteTotal_" style="">
					  	<div class=" mifuente11 col-md-6" style="padding-right:10px!important">
					  		<label style="margin-bottom: 0rem !important;"><strong>PACIENTES EN ESPERA <label id="lblResultPE" class="resultadoPacienteEspera" style="margin-bottom: 0rem !important;">(<?=$SC2?>)</label></strong></label>
					  	</div>
					  	<div class=" mifuente11 col-md-6 text-right" >
					  		<label style="margin-bottom: 0rem !important;"><strong>CATEGORIZADOS <label id="lblResultPC" class="resultadoPacienteCategorizados"  style="margin-bottom: 0rem !important;">(<?=$CAT2?>)</label></strong></label>
					  	</div>
					</div>
				</div>
				<div class="thumbnail "  style="    height: calc(80vh - 70px);
    max-height:  calc(67vh - 59px);
    overflow-y: auto; overflow-x: hidden;" >
					<table id="tablaPacientesEspera" class="display mifuente12 table table-condensed table-hover table-mapa-piso tblCat otraClass"  style="max-height: 400px; overflow-y: auto; ">
						<?php
						$style = ( $parametros['tipoMapa'] == "mapaGinecologico" && $objUtil->existe($listado) ) ? "height:180px !important;" : "";
						?>
						<tbody id="tbodycategorizacion" style="<?php echo $style; ?>">
							<tr class="table-mapa-piso-encabezado table-primary3" align="center">
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="display: none !important;">
								</td>
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="width: 12%;">
									DAU
								</td>
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="width: 46%;">
									Paciente
								</td>
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="width: 12%;">
									Edad
								</td>
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="width: 12%;">
									Motivo
								</td>
								<td class="mifuente11 my-2 py-2 mx-2 px-2 text-center" style="width: 10%;">
									<?php
									if ( isset($datosTriageAct[0]['config_abreviado']) ) {
										if ( $datosTriageAct[0]['config_abreviado'] == 'SDD' ) {
											echo 'CAT';
										} else if ( $datosTriageAct[0]['config_abreviado'] == 'ESI' ) {
											echo 'ESI';
										} else {
											echo 'CAT/ESI';
										}
									} else {
										echo 'CAT/ESI';
									}
									?>
								</td>
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 20%;">
									T. Espera
								</td>
							</tr>
							<?php
							$C1  = 0;
							$C2  = 0;
							$C3  = 0;
							$C4  = 0;
							$C5  = 0;
							$SC  = 0;
							$CAT = 0;
							$total = 0;
							for ( $i = 0; $i < count($datos); $i++ ) {

								$datos[$i]['fechaEspera'] = $datos[$i]['dau_admision_fecha'];
								if($datos[$i]['est_id'] == 2){

									$datos[$i]['fechaEspera'] = $datos[$i]['dau_categorizacion_fecha'];
								}
								// $datos[$i]['fechaEspera'] = $datos[$i]['dau_categorizacion_fecha'];
								if ( $datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == '' ) {
									$SC = $SC + 1;
								}
								if ($datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C1' ) {
									$C1 = $C1 + 1;
								}
								if ( $datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C2' ) {
									$C2 = $C2 + 1;
								}
								if ( $datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C3' ) {
									$C3 = $C3 + 1;
								}
								if ( $datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C4' ) {
									$C4 = $C4 + 1;
								}
								if ( $datos[$i]['est_id'] == '2' && $datos[$i]['cat_nombre_mostrar'] == 'C5' ) {
									$C5 = $C5 + 1;
								}
								$CATeg 	= $C1 + $C2 + $C3 + $C4 + $C5;
								$total 	= $SC + $C1 + $C2 + $C3 + $C4 + $C5;
								switch ( $datos[$i]['est_id'] ) {
									case 1:
										if ( array_search(823, $permisos) != null ) {
											$dragable = 'draggable="'.$draggableLPE.'"';
										}
									break;
									case 2:
										$dragable = 'draggable="'.$draggableLPC.'"';
									break;
								}
								$tipoPaciente   		= evaluarTipoPaciente(substr(strtoupper($datos[$i]['ate_descripcion']), 0, 1));
								$sincategorizar 		= evaluarCategorizacionPaciente($datos[$i]['est_id'], $datos[$i]['cat_nombre_mostrar']);
								$tipoCategorizacion 	= evaluarTipoCategorizacionpaciente($datos[$i]['cat_nivel']);
								$claseTablaPaciente 	= '';
								if ( $tipoPaciente == 'adulto' || $tipoPaciente == 'pediatrico' ) {
									$tipoPaciente 		.= " adultoPediatrico";
								}
								$trasladado 			= '';
								if ( $datos[$i]['dau_paciente_trasladado'] == 'S' ) {
									$trasladado 		= " flashingBorder";
								}
								$sintomasRespiratorios 	= '';
								if ( strpos($datos[$i]['sintomasRespiratorios'], 'S') !== false ) {
									$sintomasRespiratorios = " sintomasRespiratorios";
								}
								$dau_indiferenciado 	= "";
								if ( $datos[$i]['dau_indiferenciado'] == 'S' ) {
									$dau_indiferenciado = " indiferenciado";
									$datos[$i]['ate_descripcion'] = 'I';
								}
								switch ( $datos[$i]['est_id'] ) {
									case 1:
										$claseTablaPaciente = $classLPE.' tr_tblCat-default arrastre_espera  '.$pacienteEnEspera.'  '.$tipoPaciente.' '.$dau_indiferenciado.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
									break;
									case 2:
										$claseTablaPaciente = $classLPC.' '.$tipoCategorizacion.' pacienteCategorizado '.$tipoPaciente.' '.$dau_indiferenciado.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
									break;
								}
								$nombrePaciente = strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat']) ;
								
								$datos[$i]['nombrePaciente']     = $objUtil->DatoPacienteTrans($datos[$i]['transexual'],$datos[$i]['nombreSocial'],$nombrePaciente); 

								?>
							<tr id="<?=$datos[$i]['dau_id']?>" draggable="true" style="cursor: pointer;background-color: #f8f8f8;" class='<?php echo $claseTablaPaciente; ?>' >
								<td align="center" class="id_dau" style="display: none !important;">
									<input type="hidden" class="inp_id_dau" value="<?=$datos[$i]['dau_id'];?>"/>
									<input type="hidden" class="tipoPaciente" value="<?=$tipoPaciente;?>"/>
									<input type="hidden" class="nombrePaciente" value="<?=strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat'])?>"/>
								</td>
								<td align="center" class="mifuente10  contenido2 my-0 py-1 mx-0 px-0" >
									<?=$datos[$i]['dau_id']?> <br>(<?=substr(strtoupper($datos[$i]['ate_descripcion']), 0, 1);?>)
								</td>
								<td align="center" class="mifuente10  contenido2 my-0 py-1 mx-0 px-0" >
									<?=$datos[$i]['nombrePaciente'];?>
								</td>
								<td align="center" class="mifuente10  contenido2 my-0 py-1 mx-0 px-0" >
									<?=$objUtil->edadActual($datos[$i]['fechanac']);?> años
								</td>
								<td align="center" class="mifuente10  contenido2 my-0 py-1 mx-0 px-0" >
									<?=substr(strtoupper($datos[$i]['mot_descripcion']), 0, 5)?>
								</td>
								<td align="center" class="mifuente10  contenido2 my-0 py-1 mx-0 px-0" >
									<?php
									if ( $datos[$i]['cat_nombre_mostrar'] == "" ) {
										echo "No";
									} else {
										echo $datos[$i]['cat_nombre_mostrar'];
									}?>
								</td>
								<td class="tiempo-espera mifuente10 contenido2 my-0 py-1 mx-0 px-0 text-center"
								    data-fecha-ingreso="<?=$datos[$i]['fechaEspera'];?>"
								    data-categoria="<?=$datos[$i]['cat_nombre_mostrar'];?>">
								</td>
            					<!-- <td class="tiempo-espera mifuente10  contenido2 my-0 py-1 mx-0 px-0 text-center" data-fecha-ingreso="<?=$datos[$i]['fechaEspera'];?>"></td> -->
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php
				if ( $parametros['tipoMapa'] == "mapaGinecologico" && $objUtil->existe($listado) ) { ?>
				<div class="thumbnail">
					<h4 style=" margin-top: 2px;"><small style="font-size: 10px !important; font-weight: bold; color:black"><strong>SOLICITUDES ESPECIALIDADES GINECOLÓGICAS</strong></small></h4>
					<table id="tablaEspecialidadGinecologica" class="display table-condensed table-hover table-mapa-piso">
						<thead>
							<tr class="table-mapa-piso-encabezado" align="center" width="100%">
								<td class="col-xs-1 headers2" style="display: none !important;"></td>
								<td class="col-xs-1 headers2" style="width:35%;">DAU</td>
								<td class="col-xs-1 headers2" style="width:40%;">Paciente</td>
								<td class="col-xs-1 headers2" style="width:20%;">Edad</td>
								<td class="col-xs-1 headers2" style="width:20%;">CAT</td>
							</tr>
						</thead>
						<tbody style="height:180px;">
							<?php
								foreach ( $listado AS $paciente ) {
									if($paciente['transexual'] == 'S'){
										$paciente['nombrePaciente'] = '<img src="'.PATH.'/assets/img/transIco.png" width="'.$width.'" height="'.$height.'" class="infoTooltip" title="Paciente Transexual"><b>'.strtoupper($nombreSocial_bd).'</b>'." / ".$paciente['nombrePaciente'];
									}
									$idTR = $paciente["idSolicitudEspecialista"]."-".$paciente["idDAU"]."-".$paciente["idRCE"]."-".$paciente["idPaciente"]."-".$paciente["tipoAtencion"];
									$tipoCategorizacion = evaluarTipoCategorizacionpaciente($paciente["nivelCategorizacion"]);
									echo '<tr class="flashingBorder '.$tipoCategorizacion.' pacientesEspecialidadGinecologica" id='.$idTR.' style="cursor:pointer;">';
									echo '<td style="width:35%; text-align:center;">'.$paciente['idDAU'].'<br />'.$paciente['atencionPaciente'].'</td>';
									echo '<td style="width:40%; font-size:8px; text-align:center;">'.$paciente['nombrePaciente'].'</td>';
									echo '<td style="width:20%; text-align:center;">'.$paciente['edadPaciente'].'</td>';
									echo '<td style="width:20%; text-align:center;">'.$paciente['categorizacionPaciente'].'</td>';
									echo '</tr>';
								}
							?>
						</tbody>
					</table>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-lg-9 ">
		<ul class="nav nav-tabs " style="border-bottom: 0px !important;">
			<?php 
			for( $i = 0; $i < count($rsUnidad); $i++ ) { 
				if($i == 0){
					$active = "active";
				}else{
					$active = "";
				}
			?>
			<li class="nav-item mifuente12 col pr-0 pl-0 buscadorLi" >
				<a href="#<?=$rsUnidad[$i]['id_unidad']?>" id="<?=$rsUnidad[$i]['id_unidad']?>" data-target="#unidad<?=$rsUnidad[$i]['id_unidad']?>" style="font-size: 14px !important; padding: 4px !important;" class="nav-link mifuente12 text-center tuvieja <?=$active;?>  ActivoColor  sub aria-controls" role="tab" data-target="#section-6" data-toggle="tab">
					<b><?=$rsUnidad[$i]['unidad_descripcion']?></b>
				</a>
			</li>
			<?php } ?>
		</ul>
		<div class="tab-content border" style=" height : fit-content; border :2px solid #176b87 !important; ">
			<?php 
			for( $i = 0; $i < count($rsUnidad); $i++ ) { 
				if($i == 0){
					$active = "active";
				}else{
					$active = "";
				}
			?>
		  	<div class="tab-pane  <?=$active;?>" id="unidad<?=$rsUnidad[$i]['id_unidad']?>" role="tabpanel" aria-labelledby="unidad-tab">
		  		<table width="100%" align="" id="tablaCamasDrop" style="height: 100%;">
					<tbody >
						<tr>
							<td align="" >
								<div class="" style="height: 100%;">
									<table width="100%" style="height: 100%;">
										<tbody>
											<tr>
												<?php
												$sal_id_anterior 	= '';
												$sal_id_actual 		= '';
												$pReg 				= '';
												$grupo_id_actual 	= '';
												$grupo_id_anterior 	= '';
												$datosCamaGroup   	= $objMapaPiso->loadCamasFullGroup($objCon, $rsUnidad[$i]['id_unidad']);
												foreach ($datosCamaGroup as $clave => $datosCamaGroupvalor) {
													if ( $datosCamaGroupvalor['sal_doble_columna'] == 'S' ) {
														$nomPanel = strtoupper($datosCamaGroupvalor['sal_nombre_mostrarUpper']);
													} else {
														if ( strlen($datosCamaGroupvalor['sal_nombre_mostrarUpper']) > 5 && $parametros['tipoMapa'] == "mapaAdultoPediatrico") {
															$nomPanel = substr(strtoupper($datosCamaGroupvalor['sal_nombre_mostrarUpper']), 0, 5).".";
														} else {
															$nomPanel = strtoupper($datosCamaGroupvalor['sal_nombre_mostrarUpper']);
														}
													}
													if($datosCamaGroupvalor['sal_tipo'] == 'P'){
														$StyleBackgroundSala = "background-color : antiquewhite;";
													}else{

														$StyleBackgroundSala = "";
													}
													?>
													<td align="center" valign="top" style="height: 100%; <?=$StyleBackgroundSala;?> " class="border-right border-left">
														<fieldset class=" text-center"  ">
															<div class="  "   >
																<div class="panel-mp panel-info text-center panel-custom">
																	<div class="panel-body panel-body-custom mifuente14" >
																		<b><?=$nomPanel;?></b>
																	</div>
																</div>
															</div>
															<div class="grid-containerMapa ">
													        <?php 
																$datosCama2   	= $objMapaPiso->loadCamasFullTipo($objCon, '', '',  $datosCamaGroupvalor['sal_id']);
																$contadorTR 		= 1;
																for( $q = 0; $q < count($datosCama2); $q++ ) { 
																	$rsEspecialista = array();
																	$cate    =  categorizacion($datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_nivel']);
																	$play   =  iconoPlayInicioAtencion($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['ind_egr_id'], $datosCama2[$q]['dau_id']);
																	$ind    =  iconoIndicacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_indicaciones_solicitadas'], $datosCama2[$q]['dau_indicaciones_realizadas']);
																	$icoPac =  iconoPaciente($datosCama2[$q]['sexo'],$datosCama2[$q]['transexual'],$datosCama2[$q]['identidad_genero']);
																	// dau_inicio_atencion_fecha
																	// dau_ingreso_sala_fecha
																	$reloj  =  tiempoEsperaDesdeCategorizacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_ingreso_sala_fecha'], $datosCama2[$q]['dau_categorizacion_actual_fecha'], $datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_tiempo_alerta'],$rshorario,$datosCama2[$q]['dau_inicio_atencion_fecha']);
																	$covid  =  "";
																	if ( $datosCama2[$q]['dau_id'] == '' ) {
																		$css_ti_aapli = "plomo";
																	} else {
																		$rsEspecialista =  $objDau->getEspecialidadesREG($objCon,$datosCama2[$q]['dau_id']);
																		$rsCate 		=  $objRce ->ListarDatosAtencion($objCon, $datosCama2[$q]['dau_id']);
																		$css_ti_aapli 	= colorTiempo($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['dau_indicaciones_completas'], $datosCama2[$q]['FechaActual']);
																	}
																	$nombrePaciente = $datosCama2[$q]['nombres']." ".$datosCama2[$q]['apellidopat']." ".$datosCama2[$q]['apellidomat'];
																	
																	?>
																	<div id="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" draggable="<?=$draggableMP?>" class="  grid-item mifuente10 contPacRecidencia   well-camas-<?=$css_ti_aapli?> <?=$classMP?>  center-block tooltip-mp col-cam creadorTool verInfoPac" style="cursor: pointer; width: 50px !important ;min-height:40px;" data-toggle="tooltip"  data-html="true" title="" >
																							
														       <?php 
																if($datosCama2[$q]['est_id']!= '10'){ ?>
																	<div hidden id="contTECAM_'<?=$datosCama2[$q]['dau_id']?>" class="contadorTEspCamas"></div>
																	<input class="css_border" type="hidden" value="<?=$css_ti_aapli?>"/>
																	<input class="hidden" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>

																	<?php if(isset($rsEspecialista[0]['especialidades']) && $rsEspecialista[0]['especialidades'] != "") { ?>
    <input class="especialistas" type="hidden" value="<?=$rsEspecialista[0]['especialidades']?>"/>
		<?php } ?>																	<?php if($rsCate[0]["dau_cat_obs_enfermera"] !="") { ?>
																	<input class="dau_cat_obs_enfermera" type="hidden" value="<?=$rsCate[0]["dau_cat_obs_enfermera"]?>"/>
																	<?php } ?>

																	<input type="hidden" id="categorizacionActualHidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																	<?php echo $cate.$play.$ind.$icoPac.$reloj.$covid; 
																} else { ?>
																		<input type="hidden" id="<?=strtotime($datosCama2[$q]['cam_fecha_desocupada'])?>" class="tiempoCamaDesocupadaHidden" value="<?=$datosCama2[$q]['cam_fecha_desocupada']?>" />
																<?php } ?>
																	<input class="cama_id" type="hidden" id="<?=$datosCama2[$q]['cam_id']?>" class="numeroCamaHidden" value="<?=$datosCama2[$q]['cam_id']?>" />
																	<input class="sala_id" type="hidden" value="<?=$datosCama2[$q]['sal_id']?>" />
																	<input class="salaTipo" type="hidden" value="<?=$datosCama2[$q]['sal_tipo']?>" />
																	<input class="dau_id" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>
																	<input class="nombreSocial" type="hidden" value="<?=$datosCama2[$q]['nombreSocial']?>"/>
																	<input class="cama_descripcion" type="hidden" value="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" />
																	<input class="nombre_paciente" type="hidden" value="<?=$nombrePaciente?>" />
																	<input class="fecha_categorizacion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_categorizacion_actual_fecha']))?>" />
																	<input class="nombre_categorizacion" type="hidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																	<input class="descripcion_consulta" type="hidden" value="<?=$datosCama2[$q]['mot_descripcion'].' '.$datosCama2[$q]['sub_mot_descripcion'].' '.$datosCama2[$q]['dau_motivo_descripcion']?>" />
																	<input class="ingreso_sala" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_ingreso_sala_fecha']))?>" />
																	<input class="fecha_atencion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_inicio_atencion_fecha']))?>" />
																	<?php if($datosCama2[$q]['dau_inicio_atencion_fecha'] !="") { ?>
																		<input class="fecha_atencion2" type="hidden" value="<?=Date("Y-m-d H:i:s", strtotime($datosCama2[$q]['dau_inicio_atencion_fecha']))?>" />
																	<?php } ?>
																	<input class="fecha_atencionDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_inicio_atencion_fecha'])?>" />
																	<input class="fecha_egreso" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha']))?>" />
																	<?php if($datosCama2[$q]['dau_indicacion_egreso_fecha'] !="") { ?>
																		<input class="fecha_egreso2" type="hidden" value="<?=Date("Y-m-d H:i:s", strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha']))?>" />
																	<?php } ?>
																	<input class="fecha_egresoDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha'])?>" />
																	<input class="motivo_egreso" type="hidden" value="<?=$datosCama2[$q]['ind_egr_descripcion']?>" />
																	<input class="tipoPaciente" type="hidden" value="<?=$datosCama2[$q]['dau_atencion']?>" />
																	<input class="servicioHospitalizacion" type="hidden" value="<?=$datosCama2[$q]['servicio']?>" />
																	<input class="atencionIniciadaPor" type="hidden" value="<?=$datosCama2[$q]['atencionIniciadaPor']?>" />
																	<input class="atencionIniciadaPorID" type="hidden" value="<?=$datosCama2[$q]['atencionIniciadaPorID']?>" />
																	<input class="dau_usuario_ultima_evo" type="hidden" value="<?=$datosCama2[$q]['dau_usuario_ultima_evo']?>" />
																	<input class="edadPaciente" type="hidden" value="<?=$GLOBALS['objUtil']->obtener_edad($datosCama2[$q]['fechanac'])?>" />
																	<input class="runPacienteExtranjero" type="hidden" value="<?=$datosCama2[$q]['runPacienteExtranjero']?>" />
																	<input class="runPaciente" type="hidden" value="<?=$GLOBALS["objUtil"]->formatearNumero($datosCama2[$q]['runPaciente']).'-'.$GLOBALS["objUtil"]->generaDigito($datosCama2[$q]['runPaciente'])?>" />
														  		<?php if ( existeExamenLaboratorioCancelado($objCon, $datosCama2[$q]['dau_id']) ){ ?>
														  			<input class="examenLaboratorioCancelado" type="hidden" value="S" />
																<?php } if ( existeSintomasRespiratorios($datosCama2[$q]['sintomasRespiratorios']) ){ ?>
																	<input class="sintomasRespiratorios" type="hidden" value="S" />
																<?php } ?>
																<label style="color:black; margin-top: 64px; font-size: 12px">
																<?=$datosCama2[$q]['tipo_cama_sigla']." - ".$datosCama2[$q]['cam_descripcion']?>
																</label>
															</div>
																
															<?php } ?>
														    </div>		
														</fieldset>
													</td>
												<?php } ?>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
		  	</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="row">
	<div class=" mifuente12 p-1  col-md-3 mt-0 mb-0 pb-0">
		<strong>RESUMEN DE CATEGORIZACIONES </strong>
		<div class="thumbnail">
			<table id="" width ="100%" class="display table-condensed table-hover table">
				<tr class="table-mapa-piso-encabezado table-primary3" align="center">
					<td class="mifuente12 my-2 py-2 mx-2 px-2">C1</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">C2</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">C3</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">C4</td>
				  	<td class="mifuente12 my-2 py-2 mx-2 px-2">C5</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">S/C</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">T. CAT</td>
					<td class="mifuente12 my-2 py-2 mx-2 px-2">Total</td>
				</tr>
				<tr>
					<td   class=" mifuente10 my-2 py-2 mx-2 px-2 tr_tblCat-ESI-1" align="center"><label style="margin-bottom: 0rem !important;" id="td_c1"><?php echo $C1;?></label></td>
					<td   class=" mifuente10 my-2 py-2 mx-2 px-2 tr_tblCat-ESI-2" align="center"><label style="margin-bottom: 0rem !important;" id="td_c2"><?php echo $C2;?></label></td>
				  	<td   class=" mifuente10 my-2 py-2 mx-2 px-2 tr_tblCat-ESI-3" align="center"><label style="margin-bottom: 0rem !important;" id="td_c3"><?php echo $C3;?></label></td>
					<td   class=" mifuente10 my-2 py-2 mx-2 px-2 tr_tblCat-ESI-4" align="center"><label style="margin-bottom: 0rem !important;" id="td_c4"><?php echo $C4;?></label></td>
					<td   class=" mifuente10 my-2 py-2 mx-2 px-2 tr_tblCat-ESI-5" align="center"><label style="margin-bottom: 0rem !important;" id="td_c5"><?php echo $C5;?></label></td>
					<td   class="mifuente10 my-2 py-2 mx-2 px-2" align="center"><label style="margin-bottom: 0rem !important;" id="td_sc"><?php echo $SC;?></label></td>
					<td   class="mifuente10 my-2 py-2 mx-2 px-2" align="center"><label style="margin-bottom: 0rem !important;" id="td_cat"><?php echo $CATeg;?></label></td>
					<td   class="mifuente10 my-2 py-2 mx-2 px-2" align="center" style="background-color: #1e73be;color: #ffffff;">
						<label style="margin-bottom: 0rem !important;" id="td_total" style="color: #ffffff;">
						<?php
							if( $total == '' || $total == 0 ) {
								echo '0';
							} else {
								echo $total;
							}?>
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-5  mifuente12 p-1 mt-1 mb-0 pb-0" >
		<strong>&nbsp;&nbsp;COLORES </strong>
		<div class="thumbnail">
			<table id="" width ="100%" class="display table-condensed table-hover mt-1 ">
				<tr>
					<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:50%">
						<label class="" style="margin-bottom: 0rem !important;width: 100%;" >
							<span  class="pt-1 pb-0  " style="border-bottom: 7px solid #6cb061!important">
								<label style="width: 100%; font-weight: 400; color: black">Indicaciones Aplicadas en Menos de 6 Horas</label>
							</span>
						</label>
					</td>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center"  style="width:50%">
						<label class="" style="margin-bottom: 0rem !important;width: 100%;" >
							<span  class="pt-1 pb-0  " style="border-bottom: 7px solid #FFD700!important">
								<label style="width: 100%; font-weight: 400; color: black">6 Horas Después de Inicio de Atención</label>
							</span>
						</label>
					</td>
				</tr>
				<tr>
					<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center"  style="width:50%">
						<label class="" style="margin-bottom: 0rem !important;width: 100%;" >
							<span  class="pt-1 pb-0  " style="border-bottom: 7px solid #eb4d4b!important">
								<label style="width: 100%; font-weight: 400; color: black">Menos de 12 Horas Transcurridas desde Alta Urgencia</label>
							</span>
						</label>
					</td>
					<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center"  style="width:50%">
						<label class="" style="margin-bottom: 0rem !important;width: 100%;" >
							<span  class="pt-1 pb-0  " style="border-bottom: 7px solid #e056fd!important">
								<label style="width: 100%; font-weight: 400; color: black">Más de 12 Horas Transcurridas desde Alta Urgencia</label>
							</span>
						</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-4 mifuente12  p-1 mt-1 mb-0 pb-0" >
		<strong>&nbsp;&nbsp;ICONOS </strong>
		<div class="thumbnail">
			<table id="" width ="100%" class="display table-condensed table-hover mt-2">
				<tr >
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 border-right" >
						<i class="fa fa-play darkcolor-barra2"  aria-hidden="true"></i>
						<label style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Inicio de Atención</label>
					</td>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 border-right" >
						<i class="fa fa-home darkcolor-barra2"  aria-hidden="true"></i>
						<label style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Alta a Casa Aplicada</label>
					</td>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 " >
						<i class="fa fa-info-circle darkcolor-barra2"  aria-hidden="true"></i>
						<label style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Indicación Solicitada</label>
					</td>
				</tr>
				<tr>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 border-right" >
						<i class="fa fa-ambulance darkcolor-barra2"  aria-hidden="true"></i>
						<label style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Derivado a Otra Institución</label>
					</td>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 border-right" >
						<i class="fa fa-times darkcolor-barra2"  aria-hidden="true"></i>
						<label style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Rechaza Hospitalización</label>
					</td>
					<td   class=" mifuente11 my-1 py-1 mx-1 px-1 " >
						<i class="fa fa-plus darkcolor-barra2"  aria-hidden="true"></i>
						<label  style="font-weight:normal; color: #176b87; font-weight:bold;">&nbsp;Defunción</label>
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>
<?php
	if ( isset($_SESSION['usuarioActivo']['usuario']) ) {
		echo '<input type="hidden" id="usuarioLogueado" value="'.$_SESSION['usuarioActivo']['nombre'].'" />';
		echo '<input type="hidden" id="usuarioLogueadoID" value="'.$_SESSION['usuarioActivo']['usuario'].'" />';
	} 
	else {
		if ( isset($_SESSION['MM_Username'.SessionName]) ) {
			echo '<input type="hidden" id="usuarioLogueado" value="'.$_SESSION['MM_UsernameName'.SessionName].'" />';
			echo '<input type="hidden" id="usuarioLogueadoID" value="'.$_SESSION['MM_Username'.SessionName].'" />';
		}
	}
?>

<?php
function colorTiempo ( $dau_indicacion_egreso_fecha, $dau_inicio_atencion_fecha, $dau_indicacion_terminada, $FechaActual ) {
	$fecha_inicio_atencion   = strtotime($FechaActual) - strtotime($dau_inicio_atencion_fecha);
	$fecha_indicacion_egreso = strtotime($FechaActual) - strtotime($dau_indicacion_egreso_fecha);
	if ( !is_null($dau_inicio_atencion_fecha) && !empty($dau_inicio_atencion_fecha) ) {
		if ( $fecha_inicio_atencion > 21599 ) {
			if ( !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha) ) {
				if ( $fecha_indicacion_egreso < 43200 ) {
					return 'rojo';
				} else if ( $fecha_indicacion_egreso >= 43200 ) {
					return 'fucsia';
				}
			} else {
				return 'amarillo';
			}
		} else {
			if ( $dau_indicacion_terminada == 1 ) {
				if ( !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha) ) {
					if ( $fecha_indicacion_egreso < 43200 ) {
						return 'rojo';
					} else if ( $fecha_indicacion_egreso >= 43200 ) {
						return 'fucsia';
					}
				} else {
					return 'verde';
				}
			} else if ( $dau_indicacion_terminada == 0 ) {
				if ( !is_null($dau_indicacion_egreso_fecha) && !empty($dau_indicacion_egreso_fecha) ) {
					if ( $fecha_indicacion_egreso < 43200 ) {
						return 'rojo';
					} else if ( $fecha_indicacion_egreso >= 43200 ) {
						return 'fucsia';
					}
				} else {
					return 'plomo';
				}
			}
		}
	} else {
		return 'plomo';
	}
}
function iconoPaciente ( $sexo , $transexual , $identidad_genero  ) {
	if( $transexual == 'S'){
		if($identidad_genero == "TM"){
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 10px; width: 21px;" src="'.PATH.'/assets/img/pacienteM2.png">';
		}else if($identidad_genero == "TF"){
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 10px; width: 21px;" src="'.PATH.'/assets/img/pacienteF.png">';
		}elseif($identidad_genero == "NB" || $identidad_genero == ""){
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 10px; width: 21px;" src="'.PATH.'/assets/img/indefinido_.png">';
		}
	}else{
		if ( $sexo == 'M' || $sexo == '1' ) {
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 10px; width: 21px;" src="'.PATH.'/assets/img/pacienteM2.png">';
		} else if ( $sexo == 'F' || $sexo == '0' ) {
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 21px; width: 21px;" src="'.PATH.'/assets/img/pacienteF.png">';
		} else {
			return '<img class="imagenPaciente shadow-sm" style="position: absolute; width: 21px; width: 21px;" src="'.PATH.'/assets/img/indefinido.png">';
		}
	}
}
function iconoPlayInicioAtencion ( $dau_indicacion_egreso_fecha, $dau_inicio_atencion_fecha, $ind_egr_id, $dau_id ) {
	if ( !isset($dau_indicacion_egreso_fecha) ) {
		if ( $dau_inicio_atencion_fecha ) {
			return '<span class="text-downright"><i class="fas fa-play shadow text-light mifuente12 " aria-hidden="true"></i></span>';
		}
	} else if ( $ind_egr_id == 3 ) {
		return '<span class="text-downright"><i class="fas fa-home shadow mifuente12 " aria-hidden="true"></i></span>';
	} else if ( $ind_egr_id == 5 ) {
		return '<span class="text-downright"><i class="fas fa-times shadow mifuente12 " aria-hidden="true"></i></span>';
	} else if ( $ind_egr_id == 6 ) {
		return '<span class="text-downright"><i class="fas fa-plus shadow mifuente12 " aria-hidden="true"></i></span>';
	} else if ( $ind_egr_id == 7 ) {
		return '<span class="text-downright"><i class="fas fa-ambulance shadow mifuente12 " aria-hidden="true"></i></span>';
	}
}
function iconoIndicacion ( $dau_id, $dau_indicaciones_solicitadas, $dau_indicaciones_realizadas ) {
	if ( $dau_indicaciones_solicitadas != 0 && $dau_indicaciones_solicitadas != "" && $dau_indicaciones_solicitadas > $dau_indicaciones_realizadas ) {
		return '<span class="text-upleft-custom"><i class="fas fa-info-circle faa-flash throb2 " aria-hidden="true"></i></span>';
	}
}
function categorizacion ($cat_nombre_mostrar, $cat_nivel ) {
	if ( isset($cat_nombre_mostrar) ) {
		return '<span class="text-downleft-'.$cat_nivel.'">'.$cat_nombre_mostrar.'</span>';
	} else {
		return '<span class="text-downleft-default">--</span>';
	}
}
function noExisteInicioAtencion ( $inicioAtencion ) {

	return ( is_null($inicioAtencion) || empty($inicioAtencion) || ($inicioAtencion == '0000-00-00 00:00:00') || ($inicioAtencion == '31-12-1969 21:00:00') ) ? true : false;
}
function pacienteAunNoCategorizado ( $tiempoCategorizacion ) {
	return ( is_null($tiempoCategorizacion) || empty($tiempoCategorizacion) || ($tiempoCategorizacion == '0000-00-00 00:00:00') || ($tiempoCategorizacion == '31-12-1969 21:00:00') ) ? false : true;
}
function tiempoAlertaCumplido( $tiempoActual, $tiempoAlerta ) {
	return ( $tiempoActual > $tiempoAlerta ) ? true : false;
}
function tipoCategorizacionSuperfluo ( $tipoCategorizacion ) {
	return ( $tipoCategorizacion != 'C5' ) ? true : false;
}
function tiempoEsperaDesdeCategorizacion ( $dauId, $dau_ingreso_sala_fecha, $tiempoCategorizacion,  $tipoCategorizacion, $tiempoAlerta, $rshorario,$dau_inicio_atencion_fecha ) {

			// return '<span id="relojEsperaCategorizacion_'.$dauId.'" class="text-upleft-custom"><i class="far fa-clock throb2 text-danger"></i></span>';

	if ( noExisteInicioAtencion($dau_inicio_atencion_fecha) != "" && pacienteAunNoCategorizado($tiempoCategorizacion) ) {
		// echo $tiempoAlerta;
		$segundos 				= 60;
		// $tiempoActual 			= strtotime($horaServidor);
		// $tiempoCategorizacion 	= strtotime($dau_ingreso_sala_fecha);
		// $tiempoActual 			= $tiempoActual - $tiempoCategorizacion;
		// $tiempoAlerta 			= $tiempoAlerta * $segundos;

		$horaServidor = $rshorario[0]['fecha']."T".$rshorario[0]['hora'];
		$tiempoActual = strtotime($horaServidor);
		$tiempoCategorizacion = strtotime($dau_ingreso_sala_fecha);

		// diferencia en segundos
		$diferenciaSegundos = $tiempoActual - $tiempoCategorizacion;

		// pasar a minutos
		$tiempoActual = floor($diferenciaSegundos / 60);

		// echo $rshorario[0]['fecha'];
		// echo $tiempoActual ;
		// echo "<br>";
		// echo $tiempoAlerta;
		if ( tiempoAlertaCumplido($tiempoActual, $tiempoAlerta) && tipoCategorizacionSuperfluo($tipoCategorizacion) ) {
			return '<span id="relojEsperaCategorizacion_'.$dauId.'" class="text-upleft-custom"><i class="far fa-clock throb2 text-danger"></i></span>';
		}
	}
}
function existeExamenLaboratorioCancelado ( $objCon, $idDau ) {
	require_once('../../../class/Laboratorio.class.php');  	$objLaboratorio = new Laboratorio;
	$resultadoConsulta = $objLaboratorio->consultarExamenesCanceladosDesdeMapaPiso($objCon, $idDau);
	return ( !empty($resultadoConsulta) && !is_null($resultadoConsulta) ) ? true : false;
}
function examenCovid ( $objCon, $idPaciente ) {
	require_once("../../../class/FormularioSeguimiento.class.php");
	$objFormulario = new FormularioSeguimiento;
	$examenCovid = $objFormulario->verificarExamenPositivo($objCon, $idPaciente);
	if ( is_null($examenCovid) || empty($examenCovid) ) {
		return;
	}
	$style = '';
	// if ( $examenCovid['estadoFormulario'] == 3 ) {
	// 	$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:#ff7878; font-size:11px;"';
	// }
	// if ( $examenCovid['estadoFormulario'] == 4 ) {
	// 	$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:#3db73d; font-size:11px;"';
	// }
	return '<span class="text-upright-custom"><i class="icon-cog fa fa-circle"'.$style.' aria-hidden="true"></i></span>';
}
function evaluarTipoPaciente ( $tipoPaciente ) {
	if ( $tipoPaciente == 'A' ) {
		return 'adulto';
	} else if ( $tipoPaciente == 'P' ) {
		return 'pediatrico';
	} else if ( $tipoPaciente == 'G' ) {
		return 'ginecologico';
	} else {
		return '';
	}
}
function evaluarCategorizacionPaciente ( $estadoPaciente, $nombreCategorizacion ) {
	if ( $estadoPaciente == '1' && $nombreCategorizacion == '' ) {
		return 'sincategorizar';
	} else {
		return '';
	}
}
function evaluarTipoCategorizacionpaciente ( $tipoCategorizacion ) {
	if ( $tipoCategorizacion == '1' ) {
		return 'tr_tblCat-ESI-1 bg-danger';
	} else if ( $tipoCategorizacion == '2' ) {
		return 'tr_tblCat-ESI-2';
	} else if ( $tipoCategorizacion == '3' ) {
		return 'tr_tblCat-ESI-3 bg-warning';
	} else if ( $tipoCategorizacion == '4' ) {
		return 'tr_tblCat-ESI-4 bg-success';
	} else {
		return 'tr_tblCat-ESI-5 bg-info';
	}
}
function existeSintomasRespiratorios ( $sintomasRespiratorios ) {
	if ( is_null($sintomasRespiratorios) || empty($sintomasRespiratorios) ) {
		return false;
	}
	if ( strpos($sintomasRespiratorios, 'S') === false ) {
		return false;
	}
	return true;
}
?>
