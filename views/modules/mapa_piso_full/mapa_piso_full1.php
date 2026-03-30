<div class=" col-lg-7  pr-1 pl-1 pb-1"  id="nav-tabContent">
		<div class="col-md-12 text-center pt-1 pb-1" style="background-color: #b3d2ff;" >
			<label for="titulo_mapa_adulto"  style="margin-bottom: 1px;" class="control-label mifuente14">
	       		<strong>ATENCIÓN ADULTO </strong>
			</label>
		</div>
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
										$ultimaCama 		= end($datosCamaGroup);
										for( $i = 0; $i < count($datosCamaGroup); $i++ ) { 
											if ( $datosCamaGroup[$i]['sal_doble_columna'] == 'S' ) {
												$nomPanel = strtoupper($datosCamaGroup[$i]['tipo_sala_grupo_descripcion']);
											} else {
												if ( strlen($datosCamaGroup[$i]['tipo_sala_grupo_descripcion']) > 5 ) {
													$nomPanel = substr(strtoupper($datosCamaGroup[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";
												} else {
													$nomPanel = strtoupper($datosCamaGroup[$i]['tipo_sala_grupo_descripcion']);
												}
											}?>
											<td align="center" valign="top" style="height: 100%;">
												<fieldset class="scheduler-border border-right text-center" style="">
													<div class="  "  <?=$styleRow?> >
														<div class="panel-mp panel-info text-center panel-custom">
															<div class="panel-body panel-body-custom mifuente10" >
																<b><?=$nomPanel;?></b>
															</div>
														</div>
													</div>
													<table class=" " border="0" width="100%" height="100%" align="center" style="height: 100%;">
														<tbody>
															<tr valign="top" align="center" >
																<td valign="top" align="center" style="padding: 1px !important; width: 100%;">
																	<table width="">
																		<tbody>
																			<tr align="center" class="" >
																			<?php 
																			$datosCama2   	= $objMapaPiso->loadCamasFullTipo($objCon, '', '', 'A', $datosCamaGroup[$i]['tipo_sala_grupo_id']);
																			$contadorTR 		= 1;
																			for( $q = 0; $q < count($datosCama2); $q++ ) { 
																				$cat    =  categorizacion($datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_nivel']);
																				$play   =  iconoPlayInicioAtencion($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['ind_egr_id'], $datosCama2[$q]['dau_id']);
																				$ind    =  iconoIndicacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_indicaciones_solicitadas'], $datosCama2[$q]['dau_indicaciones_realizadas']);
																				$icoPac =  iconoPaciente($datosCama2[$q]['sexo']);
																				$reloj  =  tiempoEsperaDesdeCategorizacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['dau_categorizacion_actual_fecha'], $datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_tiempo_alerta']);
																				$covid  =  examenCovid($objCon, $datosCama2[$q]['id_paciente']);
																				if ( $datosCama2[$q]['dau_id'] == '' ) {
																					$css_ti_aapli = "plomo";
																				} else {
																					$css_ti_aapli = colorTiempo($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['dau_indicaciones_completas'], $datosCama2[$q]['FechaActual']);
																				}
																				?>
																				<td align="center" id="122/113581/122/2/" draggable="true" class="tablacamasDropclass" >
																					<div class="well-camas-container  verDetalle puntero  infoPaciente" id="2236853-113581"  data-original-title="" title="">
																					<div id="122" class="well-camas-<?=$css_ti_aapli?>      table-orange " style=" border: 1px solid C2;width: 100%">
																					<div id="contenidoPacienteAislamiento">
																					<div id="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" draggable="<?=$draggableMP?>" class="contPacRecidencia  <?=$classMP?> verInfoPac center-block tooltip-mp col-cam" style="cursor: pointer; width: 43px !important ;min-height:40px;"> 
																					<?php 
																					if($datosCama2[$q]['est_id']!= '10'){ ?>
																						<div hidden id="contTECAM_'<?=$datosCama2[$q]['dau_id']?>" class="contadorTEspCamas"></div>
																						<input class="css_border" type="hidden" value="<?=$css_ti_aapli?>"/>
																						<input class="hidden" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>
																						<input type="hidden" id="categorizacionActualHidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																						<?php echo $cat.$play.$ind.$icoPac.$reloj.$covid; 
																					} else { ?>
																							<input type="hidden" id="<?=strtotime($datosCama['cam_fecha_desocupada'])?>" class="tiempoCamaDesocupadaHidden" value="<?=$datosCama['cam_fecha_desocupada']?>" />
																					<?php } ?>
																						<input class="cama_id" type="hidden" id="<?=$datosCama2[$q]['cam_id']?>" class="numeroCamaHidden" value="<?=$datosCama2[$q]['cam_id']?>" />
																						<input class="sala_id" type="hidden" value="<?=$datosCama2[$q]['sal_id']?>" />
																						<input class="salaTipo" type="hidden" value="<?=$datosCama2[$q]['sal_tipo']?>" />
																						<input class="dau_id" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>
																						<input class="cama_descripcion" type="hidden" value="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" />
																						<input class="nombre_paciente" type="hidden" value="<?=$datosCama2[$q]['nombres']." ".$datosCama2[$q]['apellidopat']." ".$datosCama2[$q]['apellidomat']?>" />
																						<input class="fecha_categorizacion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_categorizacion_actual_fecha']))?>" />
																						<input class="nombre_categorizacion" type="hidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																						<input class="descripcion_consulta" type="hidden" value="<?=$datosCama2[$q]['mot_descripcion'].' '.$datosCama2[$q]['sub_mot_descripcion'].' '.$datosCama2[$q]['dau_motivo_descripcion']?>" />
																						<input class="ingreso_sala" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_ingreso_sala_fecha']))?>" />
																						<input class="fecha_atencion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_inicio_atencion_fecha']))?>" />
																						<input class="fecha_atencionDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_inicio_atencion_fecha'])?>" />
																						<input class="fecha_egreso" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha']))?>" />
																						<input class="fecha_egresoDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha'])?>" />
																						<input class="motivo_egreso" type="hidden" value="<?=$datosCama2[$q]['ind_egr_descripcion']?>" />
																						<input class="tipoPaciente" type="hidden" value="<?=$datosCama2[$q]['dau_atencion']?>" />
																						<input class="servicioHospitalizacion" type="hidden" value="<?=$datosCama2[$q]['servicio']?>" />
																						<input class="atencionIniciadaPor" type="hidden" value="<?=$datosCama2[$q]['atencionIniciadaPor']?>" />
																						<input class="edadPaciente" type="hidden" value="<?=$GLOBALS['objUtil']->edadActualCompleto($datosCama2[$q]['fechanac'])?>" />
																						<input class="runPacienteExtranjero" type="hidden" value="<?=$datosCama2[$q]['runPacienteExtranjero']?>" />
																						<input class="runPaciente" type="hidden" value="<?=$GLOBALS["objUtil"]->formatearNumero($datosCama2[$q]['runPaciente']).'-'.$GLOBALS["objUtil"]->generaDigito($datosCama2[$q]['runPaciente'])?>" />
																			  		<?php if ( existeExamenLaboratorioCancelado($objCon, $datosCama2[$q]['dau_id']) ){ ?>
																			  			<input class="examenLaboratorioCancelado" type="hidden" value="S" />
																					<?php } if ( existeSintomasRespiratorios($datosCama2[$q]['sintomasRespiratorios']) ){ ?>
																						<input class="sintomasRespiratorios" type="hidden" value="S" />
																					<?php } ?>
																					</div>
																					</div>
																					</div>
																					<div class="letraCategorizacion" style="color: ;">
																						<label class="letra_cambiada mifuente10" style="margin-bottom : 1px"><center><?=$datosCama2[$q]['tipo_cama_sigla']." - ".$datosCama2[$q]['cam_descripcion']?></center></label>
																					</div>
																					</div>
																					<?php if( count($datosCama2) < 9 ) { echo "</tr>"; }
																					else if ($contadorTR % 3 == 0){echo "</tr>";} 
																					$contadorTR ++;?>
																				</td>
																			<?php } ?>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
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
	<div class=" col-lg-2  pr-1 pl-1 pb-1"  id="nav-tabContent">
		<div class="col-md-12 text-center pt-1 pb-1" style="background-color: #ffe082;" >
			<label for="titulo_mapa_adulto"  style="margin-bottom: 1px;" class="control-label mifuente14">
	       		<strong>ATENCIÓN PEDIÁTRICO </strong>
			</label>
		</div>
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
										$ultimaCama 		= end($datosCamaPediGroup);
										for( $i = 0; $i < count($datosCamaPediGroup); $i++ ) { 
											if ( $datosCamaPediGroup[$i]['sal_doble_columna'] == 'S' ) {
												$nomPanel = strtoupper($datosCamaPediGroup[$i]['tipo_sala_grupo_descripcion']);
											} else {
												if ( strlen($datosCamaPediGroup[$i]['tipo_sala_grupo_descripcion']) > 5 ) {
													$nomPanel = substr(strtoupper($datosCamaPediGroup[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";
												} else {
													$nomPanel = strtoupper($datosCamaPediGroup[$i]['tipo_sala_grupo_descripcion']);
												}
											}?>
											<td align="center" valign="top" style="height: 100%;">
												<fieldset class="scheduler-border border-right text-center" style="">
													<div class="  "  <?=$styleRow?> >
														<div class="panel-mp panel-info text-center panel-custom">
															<div class="panel-body panel-body-custom mifuente10" >
																<b><?=$nomPanel;?></b>
															</div>
														</div>
													</div>
													<table class=" " border="0" width="100%" height="100%" align="center" style="height: 100%;">
														<tbody>
															<tr valign="top" align="center" >
																<td valign="top" align="center" style="padding: 1px !important; width: 100%;">
																	<table width="">
																		<tbody>
																			<tr align="center" class="" >
																			<?php 
																			$datosCama2   	= $objMapaPiso->loadCamasFullTipo($objCon, '', '', 'P', $datosCamaPediGroup[$i]['tipo_sala_grupo_id']);
																			$contadorTR 		= 1;
																			for( $q = 0; $q < count($datosCama2); $q++ ) { 
																				$cat    =  categorizacion($datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_nivel']);
																				$play   =  iconoPlayInicioAtencion($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['ind_egr_id'], $datosCama2[$q]['dau_id']);
																				$ind    =  iconoIndicacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_indicaciones_solicitadas'], $datosCama2[$q]['dau_indicaciones_realizadas']);
																				$icoPac =  iconoPaciente($datosCama2[$q]['sexo']);
																				$reloj  =  tiempoEsperaDesdeCategorizacion($datosCama2[$q]['dau_id'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['dau_categorizacion_actual_fecha'], $datosCama2[$q]['cat_nombre_mostrar'], $datosCama2[$q]['cat_tiempo_alerta']);
																				$covid  =  examenCovid($objCon, $datosCama2[$q]['id_paciente']);
																				if ( $datosCama2[$q]['dau_id'] == '' ) {
																					$css_ti_aapli = "plomo";
																				} else {
																					$css_ti_aapli = colorTiempo($datosCama2[$q]['dau_indicacion_egreso_fecha'], $datosCama2[$q]['dau_inicio_atencion_fecha'], $datosCama2[$q]['dau_indicaciones_completas'], $datosCama2[$q]['FechaActual']);
																				}
																				?>
																				<td align="center" id="122/113581/122/2/" draggable="true" class="tablacamasDropclass" >
																					<div class="well-camas-container  verDetalle puntero  infoPaciente" id="2236853-113581"  data-original-title="" title="">
																					<div id="122" class="well-camas-<?=$css_ti_aapli?>      table-orange " style=" border: 1px solid C2;width: 100%">
																					<div id="contenidoPacienteAislamiento">
																					<div id="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" draggable="<?=$draggableMP?>" class="contPacRecidencia  <?=$classMP?> verInfoPac center-block tooltip-mp col-cam" style="cursor: pointer; width: 43px !important ;min-height:40px;"> 
																					<?php 
																					if($datosCama2[$q]['est_id']!= '10'){ ?>
																						<div hidden id="contTECAM_'<?=$datosCama2[$q]['dau_id']?>" class="contadorTEspCamas"></div>
																						<input class="css_border" type="hidden" value="<?=$css_ti_aapli?>"/>
																						<input class="hidden" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>
																						<input type="hidden" id="categorizacionActualHidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																						<?php echo $cat.$play.$ind.$icoPac.$reloj.$covid; 
																					} else { ?>
																							<input type="hidden" id="<?=strtotime($datosCama['cam_fecha_desocupada'])?>" class="tiempoCamaDesocupadaHidden" value="<?=$datosCama['cam_fecha_desocupada']?>" />
																					<?php } ?>
																						<input class="cama_id" type="hidden" id="<?=$datosCama2[$q]['cam_id']?>" class="numeroCamaHidden" value="<?=$datosCama2[$q]['cam_id']?>" />
																						<input class="sala_id" type="hidden" value="<?=$datosCama2[$q]['sal_id']?>" />
																						<input class="salaTipo" type="hidden" value="<?=$datosCama2[$q]['sal_tipo']?>" />
																						<input class="dau_id" type="hidden" value="<?=$datosCama2[$q]['dau_id']?>"/>
																						<input class="cama_descripcion" type="hidden" value="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" />
																						<input class="nombre_paciente" type="hidden" value="<?=$datosCama2[$q]['nombres']." ".$datosCama2[$q]['apellidopat']." ".$datosCama2[$q]['apellidomat']?>" />
																						<input class="fecha_categorizacion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_categorizacion_actual_fecha']))?>" />
																						<input class="nombre_categorizacion" type="hidden" value="<?=$datosCama2[$q]['cat_nombre_mostrar']?>" />
																						<input class="descripcion_consulta" type="hidden" value="<?=$datosCama2[$q]['mot_descripcion'].' '.$datosCama2[$q]['sub_mot_descripcion'].' '.$datosCama2[$q]['dau_motivo_descripcion']?>" />
																						<input class="ingreso_sala" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_ingreso_sala_fecha']))?>" />
																						<input class="fecha_atencion" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_inicio_atencion_fecha']))?>" />
																						<input class="fecha_atencionDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_inicio_atencion_fecha'])?>" />
																						<input class="fecha_egreso" type="hidden" value="<?=Date("d-m-Y H:i:s", strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha']))?>" />
																						<input class="fecha_egresoDinamica" type="hidden" value="<?=strtotime($datosCama2[$q]['dau_indicacion_egreso_fecha'])?>" />
																						<input class="motivo_egreso" type="hidden" value="<?=$datosCama2[$q]['ind_egr_descripcion']?>" />
																						<input class="tipoPaciente" type="hidden" value="<?=$datosCama2[$q]['dau_atencion']?>" />
																						<input class="servicioHospitalizacion" type="hidden" value="<?=$datosCama2[$q]['servicio']?>" />
																						<input class="atencionIniciadaPor" type="hidden" value="<?=$datosCama2[$q]['atencionIniciadaPor']?>" />
																						<input class="edadPaciente" type="hidden" value="<?=$GLOBALS['objUtil']->edadActualCompleto($datosCama2[$q]['fechanac'])?>" />
																						<input class="runPacienteExtranjero" type="hidden" value="<?=$datosCama2[$q]['runPacienteExtranjero']?>" />
																						<input class="runPaciente" type="hidden" value="<?=$GLOBALS["objUtil"]->formatearNumero($datosCama2[$q]['runPaciente']).'-'.$GLOBALS["objUtil"]->generaDigito($datosCama2[$q]['runPaciente'])?>" />
																			  		<?php if ( existeExamenLaboratorioCancelado($objCon, $datosCama2[$q]['dau_id']) ){ ?>
																			  			<input class="examenLaboratorioCancelado" type="hidden" value="S" />
																					<?php } if ( existeSintomasRespiratorios($datosCama2[$q]['sintomasRespiratorios']) ){ ?>
																						<input class="sintomasRespiratorios" type="hidden" value="S" />
																					<?php } ?>
																					</div>
																					</div>
																					</div>
																					<div class="letraCategorizacion" style="color: ;">
																						<label class="letra_cambiada mifuente10" style="margin-bottom : 1px"><center><?=$datosCama2[$q]['tipo_cama_sigla']." - ".$datosCama2[$q]['cam_descripcion']?></center></label>
																					</div>
																					</div>
																					<?php if( count($datosCama2) < 9 ) { echo "</tr>"; }
																					else if ($contadorTR % 2 == 0){echo "</tr>";} 
																					$contadorTR ++;?>
																				</td>
																			<?php } ?>
																			</tr>
																		</tbody>
																	</table>
																</td>
															</tr>
														</tbody>
													</table>
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










<?php
session_start();
ini_set('memory_limit', '1000M');

$permisos = $_SESSION['permisosDAU'.SessionName];

// if ( array_search(831, $permisos) == null ) {$GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));}

require_once('../../../class/Connection.class.php');	$objCon        = new Connection; $objCon->db_connect();
require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;
require_once('../../../class/Dau.class.php');  			$objDau   	   = new Dau;
require_once('../../../class/Util.class.php');      	$objUtil       = new Util;
// require_once('../../../class/Config.class.php');      	$objConfig     = new Config;

require("../../../config/config.php");

if ( ! empty($_POST) && ! is_null($_POST) ) {

	$parametros	= $objUtil->getFormulario($_POST);

}

if ( ! empty($_GET) && ! is_null($_GET) ) {

	$parametros	= $objUtil->getFormulario($_GET);

}

$version    	= $objUtil->versionJS();
$datos          = $objMapaPiso->loadTablaFull($objCon);
$datosCama   	= $objMapaPiso->loadCamasFull($objCon, '', '', 'A');
$datosCamaPedi	= $objMapaPiso->loadCamasFull($objCon, '', '', 'P');
$datosSalaGine	= $objMapaPiso->loadCamasFull($objCon, '', '', 'GO');
// $datosTriageAct = $objConfig->getTipoTriageActivo($objCon);
// $listado 		= $objDau->listarDAUEspecialidadGinecologica($objCon);

// if ( isset($_SESSION['usuarioActivo']['usuario']) ) {

// 	$idusuario 	= $_SESSION['usuarioActivo']['usuario'];

// } else {

// 	$idusuario 	= $_SESSION['MM_Username'.SessionName];

// }

// $tipoMapaUsuario = $objMapaPiso->consultarTipoMapaUsuarioUsuario($objCon, $idusuario);

// $_SESSION['contadorColumnas'] = 2;

// $_SESSION['contadorColumnas3'] = 3;

// $_SESSION['contadorColumnas4'] = 4;

// $respVerMP = $objMapaPiso->verMapaPisoXProfesional($objCon, $parametros);

// //Permisos
// if ( array_search(822,$permisos) != null ) {

// 	$draggableLPE = 'true';

// 	$classLPE = 'tbl_esp ';

// } else {

// 	$draggableLPE = 'false';

// }

// if ( array_search(821,$permisos ) != null ) {

// 	$draggableLPC = 'true';

// 	$classLPC = 'tbl_cat ';

// } else {

// 	$draggableMP = 'false';

// }

// if ( array_search(820,$permisos) != null ) {

// 	$draggableMP = 'true';

// 	$classMP = 'camaMapaPiso ';

// } else {

// 	$draggableMP = 'false';

// }

// //Recorrido de contenido
// $SC2  = 0;

// $CAT2 = 0;

// for( $i = 0; $i < count($datos); $i++) {

// 	if ( $datos[$i]['est_id'] == '2' ) {

// 		$CAT2 = $CAT2 + 1;

// 	}

// 	if ( $datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == '' ) {

// 		$SC2 = $SC2 + 1;

// 	}

// }

// $nombre = $_SESSION['MM_Username'.SessionName];

// $rut = $_SESSION['MM_RUNUSU'.SessionName];

// if ( isset($_SESSION['usuarioActivo']) ) {

// 	$nombre = $_SESSION['usuarioActivo']['usuario'];

// 	$rut = $_SESSION['usuarioActivo']['rut'];

// }

// $usuarioMarcaAgua = strtoupper (substr($nombre, 0, 3)."".substr($rut,-3));
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
?>
<style>
        .table-container {
            /*height: 80vh; */
            overflow-y: auto; /* Habilita el scroll vertical */
        }
        .table-container table {
            width: 100%;
            table-layout: fixed; /* Hace que las columnas tengan un ancho fijo */
        }
        .table-container thead, .table-container tbody {
            display: table;
            width: 100%;
        }
        .table-container tbody {
            display: block;
            height: calc(70vh - 115px); /* Resta la altura del thead, ajusta según tu necesidad */
            overflow-y: auto;
        }
        .table-container thead {
            display: table;
            /*width: calc(100% - 1em); */
        }
        .tr_tblCat-default{
		    border: 1px solid #cacaca;
		}
		.tr_tblCat-init{
		    background-color: #f8f8f8;
		}
		.tr_tblCat-ESI-1{  /* inicio  RAA */
		    /*border: 1px solid #E53256;*/
		    background-color: #f1bfbf !important;
		}
		.tr_tblCat-ESI-2{
		    background-color: #fdd8b4 !important;
		    /*border: 1px solid #F19B47;*/
		}
		.tr_tblCat-ESI-3{
		    /*border: 1px solid #ffe55b;*/
		    background-color: #fbfdbd !important;
		}
		.tr_tblCat-ESI-4{
		    /*border: 1px solid #508261;*/
		    background-color: #ccefc4 !important;
		}
		.tr_tblCat-ESI-5{
		    /*border: 1px solid #505AAA;*/
		    background-color: #c5e7f8 !important;    /* FIN  RAA */
		}
		.tr_tblCat-1{
		    border: 1px solid #E53256 !important;;
		    background-color: #f1bfbf !important;;
		}
		.tr_tblCat-2{
		    background-color: #fdd8b4 !important;;
		    border: 1px solid #F19B47 !important;;
		}
		.tr_tblCat-3{
		    border: 1px solid #ffe55b !important;;
		    background-color: #fbfdbd !important;;
		}
		.tr_tblCat-4{
		    border: 1px solid #508261 !important;;
		    background-color: #ccefc4 !important;;
		}
		.tr_tblCat-5{
		    border: 1px solid #505AAA !important;;
		    background-color: #c5e7f8 !important;;
		}
</style>
<script type="text/javascript">
	//Contador Tiempo Espera Categorizados.
	var arr_contador_cat = document.querySelectorAll('.contadorTEspCat');
	for(var j = 0; j < arr_contador_cat.length; j++){
		if (intervals.length>0) {
			var id_div_cont = arr_contador_cat[j].id;
			var arr_idcont = id_div_cont.split('_');
			var inter = searchObj(intervals, arr_idcont[1]);
			clearInterval(parseInt(inter));
			deleteElementObj(intervals, arr_idcont[1]);
		}
	}
	contadorTiempoEspera(arr_contador_cat, 'categorizacion');
</script>
<div class="row">
	<div class=" mifuente12 col-lg-4">
		<div id="divMapapisoFull" class="" style="">

			<!--
			**************************************************************************
											TABLA LISTA PACIENTES
			**************************************************************************
			-->
			<div id="divTablaMapaPiso" class="well" style="">
				<!-- Cuadro de Búsqueda -->
				<div class="header">
					<div class="row">
						<div class=" mifuente12 col-lg-8">
							<input type="search" class="form-control form-control-sm mifuente12" placeholder="Buscar..." >
						</div>
						<div class=" mifuente12 col-lg-4">
							<button data-search="next" class="btn btn-primary2 btn-sm" style=""><i class="fas fa-arrow-up"></i></button>&nbsp;
							<button data-search="prev" class="btn btn-primary2 btn-sm" style=""><i class="fas fa-arrow-down"></i></button>&nbsp;
							<button data-search="clear" class="btn btn-danger btn-sm" style=""><i class="fas fa-times"></i></button>&nbsp;
							<label id="lblResult" class="mifuente12" style="">0</label>
						</div>
					</div>
				</div>

				<div class="row pac-list" id="contenidoPacientes">
					<input type="hidden" name="SC2"  id="SC2"  value="<?=$SC2;?>">
				 	<input type="hidden" name="CAT2" id="CAT2" value="<?=$CAT2;?>">
					<div class=" mifuente12 col-md-12">
		  				<div id="pacienteTotal">
						  	<!-- Selección de Filtros -->
			  				<div class="row mt-1">
							  	<div class=" mifuente12 col-md-8" id="divSeleccione">
							  		<select id="frm_tipo_Atenciones" class="form-control form-control-sm mifuente12" style="">
										<option value="1">Todos</option>
										<option value="2">Adulto</option>
										<option value="3">Pediátrico</option>
										<option value="4">Ginecología</option>
									</select>
							  	</div>
							  	<div class=" mifuente12 col-md-4" id="divBtnFiltro">
									<button id="quitarFiltros" style="" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
							  	</div>
							</div>
							<div class="row mifuente12 mt-1" id="pacienteTotal_" style="">
							  	<div class=" mifuente12 col-md-6" >
							  		<label style="margin-bottom: 0rem !important;"><strong>PACIENTES EN ESPERA <label id="lblResultPE" class="resultadoPacienteEspera" style="margin-bottom: 0rem !important;">(<?=$SC2?>)</label></strong></label>
							  	</div>
							  	<div class=" mifuente12 col-md-6 text-right" >
							  		<label style="margin-bottom: 0rem !important;"><strong>CATEGORIZADOS <label id="lblResultPC" class="resultadoPacienteCategorizados"  style="margin-bottom: 0rem !important;">(<?=$CAT2?>)</label></strong></label>
							  	</div>
							</div>
						</div>
        					<div class="table-container" style="max-height: calc(70vh - 115px); height: auto; overflow-y: auto;">
							<table id="tablaPacientesEspera" width="100%" class="display mifuente12 table table-condensed table-hover table-mapa-piso tblCat otraClass">
								<?php $style = ( $parametros['tipoMapa'] == "mapaGinecologico" && $objUtil->existe($listado) ) ? "height:180px !important;" : ""; ?>
								 <thead id="tbodycategorizacion" style="<?php echo $style; ?>">
				                    <tr class="table-mapa-piso-encabezado table-primary3" align="center">
										<!-- <td class="mifuente12 my-2 py-2 mx-2 px-2" style="display: none !important; width: 12%;" > -->
										<!-- </td> -->
										<th class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style=" width: 12%;">
											DAU
										</th>
										<th class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style=" width: 50%;">
											Paciente
										</th>
										<th class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style=" width: 12%;" >
											Motivo
										</th>
										<th class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style=" width: 10%;">
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
										</th>
										<th class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style=" width: 16%;" >
											T. Espera
										</th>
									</tr>
                				</thead>
                				<!-- <div class="table-container"> -->
								<tbody id="tbodycategorizacion"  >

									<?php
									$C1  = 0;
									$C2  = 0;
									$C3  = 0;
									$C4  = 0;
									$C5  = 0;
									$SC  = 0;
									$CAT = 0;
									for ( $i = 0; $i < count($datos); $i++ ) {
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
										$CAT = $C1 + $C2 + $C3 + $C4 + $C5;
										$total = $SC + $C1 + $C2 + $C3 + $C4 + $C5;
										$tipoPaciente   	= evaluarTipoPaciente(substr(strtoupper($datos[$i]['ate_descripcion']), 0, 1));
										$sincategorizar 	= evaluarCategorizacionPaciente($datos[$i]['est_id'], $datos[$i]['cat_nombre_mostrar']);
										$tipoCategorizacion = evaluarTipoCategorizacionpaciente($datos[$i]['cat_nivel']);
										$claseTablaPaciente = '';
										if ( $tipoPaciente == 'adulto' || $tipoPaciente == 'pediatrico' ) {
											$tipoPaciente .= " adultoPediatrico";
										}
										$trasladado = '';
										if ( $datos[$i]['dau_paciente_trasladado'] == 'S' ) {
											$trasladado = " flashingBorder";
										}
										$sintomasRespiratorios = '';
										if ( strpos($datos[$i]['sintomasRespiratorios'], 'S') !== false ) {
											$sintomasRespiratorios = " sintomasRespiratorios";
										}
										switch ( $datos[$i]['est_id'] ) {
											case 1:
												$claseTablaPaciente = $classLPE.' tr_tblCat-default arrastre_espera  pacienteEnEspera '.$tipoPaciente.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
											break;
											case 2:
												$claseTablaPaciente = $classLPC.' '.$tipoCategorizacion.' pacienteCategorizado '.$tipoPaciente.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
											break;
										}?>
										<tr id="<?=$datos[$i]['dau_id']?>" draggable="true" ondragstart="drag(event)" class='<?php echo $claseTablaPaciente; ?>' style="background-color: #f8f8f8; border: 1px;"
											 >
											<!-- <td align="center" class="id_dau" style="display: none !important;"> -->
												<!-- <input type="hidden" class="inp_id_dau" value="<?=$datos[$i]['dau_id'];?>"/> -->
												<!-- <input type="hidden" class="tipoPaciente" value="<?=$tipoPaciente;?>"/> -->
												<!-- <input type="hidden" class="nombrePaciente" value="<?=strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat'])?>"/> -->
											<!-- </td> -->
											<td align="center" class=" mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 12%;" >
												<?=$datos[$i]['dau_id']?> (<?=substr(strtoupper($datos[$i]['ate_descripcion']), 0, 1);?>)
											</td>
											<td align="center" style=" width: 46%; " class=" mifuente10  contenido2 my-1 py-1 mx-1 px-1" >
												<?=strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat'])." (".$objUtil->edadActual($datos[$i]['fechanac'])." años)";?>
											</td>
											<td align="center" class=" mifuente10  contenido2 my-1 py-1 mx-1 px-1" style=" width: 10%; " >
												<?=substr(strtoupper($datos[$i]['mot_descripcion']), 0, 5)?>
											</td>
											<td align="center" class=" mifuente10  contenido2 my-1 py-1 mx-1 px-1" style=" width: 10%;">
												<?php
												if ( $datos[$i]['cat_nombre_mostrar'] == "" ) {
													echo "No";
												} else {
													echo $datos[$i]['cat_nombre_mostrar'];
												}
												?>
											</td>
											<td align="center" class=" mifuente10 col-xs-1 contenido2 my-1 py-1 mx-1 px-1" style=" width: 16%;" >
												<?php
												$idTiempoPaciente    = '';
												$claseTiempoPaciente = '';
												switch ( $datos[$i]['est_id'] ) {
													case 1:
														$idTiempoPaciente    = 'contTEA';
														$claseTiempoPaciente = 'contadorTEspAdm';
													break;
													case 2:
														$idTiempoPaciente    = 'contTEC';
														$claseTiempoPaciente = 'contadorTEspCat';
														echo '<div id="contTEC_'.$datos[$i]['dau_id'].'" class="contadorTEspCat"></div>';
													break;
												}
												?>
												<div id="<?php echo $idTiempoPaciente.'_'.$datos[$i]['dau_id']; ?>" class="<?php echo $claseTiempoPaciente; ?>" ></div>
											</td>
										</tr>
									<?php } ?>
								</tbody>
								<!-- </div> -->
							</table>
						</div>
						<!-- Despliegue Resumen de Categorizaciones -->
						<div class="row pac-list mt-1" id="pacienteTotal2">
							<div class=" mifuente12 col-md-12">
								<label ><strong>RESUMEN DE CATEGORIZACIONES </strong></label>
								<div class="thumbnail" style="margin-top: -8px;">
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
											<td   class="mifuente10 my-2 py-2 mx-2 px-2" align="center"><label style="margin-bottom: 0rem !important;" id="td_cat"><?php echo $CAT;?></label></td>
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
				      	</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


	<!-- <div id="divMapaPisoAPG col-lg-8" > -->

		<!--
		**************************************************************************
										MAPA ATENCIÓN ADULTO
		**************************************************************************
		-->
		<!-- <div id="mapapiso_adulto" class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 pac-list" >

			<div class="col-md-12"  style="text-align:center; margin-top: -7px;">

				<br>

				<label for="titulo_mapa_adulto"  class="control-label">

	           		<strong>ATENCIÓN ADULTO <br/></strong>

				</label>

			</div>

			<?php

			$sal_id_anterior = '';

			$sal_id_actual = '';

			$pReg = '';

			$grupo_id_actual = '';

			$grupo_id_anterior = '';

			$ultimaCama = end($datosCama);

			for( $i = 0; $i < count($datosCama); $i++ ) {

				$sal_id_actual = $datosCama[$i]['sal_id'];

				$grupo_id_actual = $datosCama[$i]['tipo_sala_grupo_id'];

				$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosCama[$i]['sal_id']);

				if ( $sal_id_anterior == $sal_id_actual ) {

					if ( $datosCama[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosCama[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosCama[$i], $draggableMP, $classMP, $datosCama[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);

				} else {

					$pReg = true;

					if ( $i != 0 ) {

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							echo '</div></div></div>';

						} else {

							echo '<hr class="hr-custom">';

						}

					}

					if ( $pReg == true ) {

						$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosCama[$i]['sal_id']);

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							$cantColumnas = $respCantipo[0]['cantidad'] / 8;

							$cantColumnas = ceil($cantColumnas);

							$panel;

							$nomPanel;

							if ( $datosCama[$i]['sal_doble_columna'] == 'S' ) {

								if ( $cantColumnas > 2 ) {

									$panel = 'div-panel-4';

								} else {

									$panel = 'div-panel-2';

								}

								$nomPanel = strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']);

							} else {

								$panel = 'div-panel';

								if ( strlen($datosCama[$i]['tipo_sala_grupo_descripcion']) > 5 ) {

									$nomPanel = substr(strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";

								} else {

									$nomPanel = strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']);

								}

							}

							?>

							<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 <?=$panel;?>" style="margin:0px 13px 0px -30px !important;">

								<div class="panel-mp panel-info text-center panel-custom">

									<div class="panel-body panel-body-custom" style="background-color:#E7EEFC; border-radius:2px; width:133%;">

										<p><strong><?=$nomPanel;?></strong></p>
				    	<?php

						}

						$pReg = false;

					}


					if ( $datosCama[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosCama[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosCama[$i], $draggableMP, $classMP, $datosCama[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);

				}

				$sal_id_anterior = $datosCama[$i]['sal_id'];

				$grupo_id_anterior = $datosCama[$i]['tipo_sala_grupo_id'];

				if ( $ultimaCama['cam_id'] == $datosCama[$i]['cam_id'] ) {

					echo '</div></div></div>';

				}

			}

			?>

		</div> -->

		<!--
		**************************************************************************
								MAPA ATENCIÓN PEDIÁTRICO
		**************************************************************************
		-->
	<!-- 	<div id="mapapiso_pediatrico" class="col-2 col-sm-2 col-md-2 col-lg-2 col-xl-2 pac-list" >

			<div class="col-md-12"  style="text-align:center; margin-top: -7px;">

				<br>

				<label for="titulo_mapa_pediatrico"  class="control-label">

					<strong>ATENCIÓN PEDIÁTRICO <br/></strong>

				</label>

			</div>

			<?php

			$sal_id_anterior = '';

			$sal_id_actual = '';

			$pReg = '';

			$grupo_id_actual = '';

			$grupo_id_anterior = '';

			$ultimaCama = end($datosCamaPedi);


			for ( $i = 0; $i < count($datosCamaPedi); $i++ ) {

				$sal_id_actual = $datosCamaPedi[$i]['sal_id'];

				$grupo_id_actual = $datosCamaPedi[$i]['tipo_sala_grupo_id'];

				$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosCamaPedi[$i]['sal_id']);

				if ( $sal_id_anterior == $sal_id_actual ) {

					if ( $datosCamaPedi[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosCamaPedi[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosCamaPedi[$i], $draggableMP, $classMP, $datosCamaPedi[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);

				} else {

					$pReg = true;

					if ( $i != 0 ) {

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							echo '</div></div></div>';

						} else {

							echo '<hr class="hr-custom">';
						}

					}

					if ( $pReg == true ) {

						$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosCamaPedi[$i]['sal_id']);

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							$cantColumnas = $respCantipo[0]['cantidad'] / 8;

							$cantColumnas = ceil($cantColumnas);

							$panel;

							$nomPanel;

							if ( $datosCamaPedi[$i]['sal_doble_columna'] == 'S' ) {

								if ( $cantColumnas > 2 ) {

									$panel = 'div-panel-4';

								} else {

									$panel = 'div-panel-2';

								}

								$nomPanel = strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']);

							} else {

								$panel = 'div-panel';

								if ( strlen($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']) > 5 ) {

									$nomPanel = substr(strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";

								} else {

		            				$nomPanel = strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']);

								}

							}

							?>

							<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 <?=$panel;?>" style="margin-left:-25px;">

								<div class="panel-mp panel-info text-center panel-custom">

									<div class="panel-body panel-body-custom" style="margin-right: -1px;">

										<p><strong><?=$nomPanel;?></strong></p>

				        <?php

						}

						$pReg = false;

					}

					if ( $datosCamaPedi[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosCamaPedi[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosCamaPedi[$i], $draggableMP, $classMP, $datosCamaPedi[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);
				}

				$sal_id_anterior = $datosCamaPedi[$i]['sal_id'];

				$grupo_id_anterior = $datosCamaPedi[$i]['tipo_sala_grupo_id'];

				if ( $ultimaCama['cam_id'] == $datosCamaPedi[$i]['cam_id'] ) {

					echo '</div></div></div>';

				}

			}

			?>

		</div> -->

		<!--
		**************************************************************************
								MAPA ATENCIÓN GINECOLÓGICO
		**************************************************************************
		-->
		<!-- <div id="mapapiso_ginecologico" class="col-5 col-sm-5 col-md-5 col-lg-5 col-xl-5 pac-list mapapiso_gine_full" >

			<div class="col-md-12"  style="text-align:center; margin-top: -7px;">

				<br>

				<label for="titulo_mapa_ginecologico"  class="control-label">

					<strong>ATENCIÓN GINECOLÓGICO <br/></strong>

				</label>

			</div>

			<?php

			$sal_id_anterior = '';

			$sal_id_actual = '';

			$pReg = '';

			$grupo_id_actual = '';

			$grupo_id_anterior = '';

			$ultimaCama = end($datosSalaGine);


			for($i=0; $i < count($datosSalaGine); $i++){

				$sal_id_actual = $datosSalaGine[$i]['sal_id'];

				$grupo_id_actual = $datosSalaGine[$i]['tipo_sala_grupo_id'];

				$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosSalaGine[$i]['sal_id']);

				if ( $sal_id_anterior == $sal_id_actual ) {

					if ( $datosSalaGine[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosSalaGine[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosSalaGine[$i], $draggableMP, $classMP, $datosSalaGine[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);

				} else {

					$pReg = true;

					if ( $i != 0 ) {

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							echo '</div></div></div>';

						} else {

							echo '<hr class="hr-custom">';

						}

					}

					if ( $pReg == true ) {

						$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosSalaGine[$i]['sal_id']);

						if ( $grupo_id_anterior != $grupo_id_actual ) {

							$cantColumnas = $respCantipo[0]['cantidad'] / 8;

							$cantColumnas = ceil($cantColumnas);

							$panel;

							$nomPanel;

							if ( $datosSalaGine[$i]['sal_doble_columna'] == 'S' ) {

								if ( $cantColumnas > 2 ) {

									$panel = 'div-panel-4-gine';

								} else {

									$panel = 'div-panel-2-gine';

								}

								$nomPanel = strtoupper($datosSalaGine[$i]['tipo_sala_grupo_descripcion']);

							} else {

								$panel = 'div-panel-gine';

								if ( strlen($datosSalaGine[$i]['tipo_sala_grupo_descripcion']) > 5 ) {

		            				$nomPanel = substr(strtoupper($datosSalaGine[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";

								} else {

		            				$nomPanel = strtoupper($datosSalaGine[$i]['tipo_sala_grupo_descripcion']);

								}

							}

							?>

							<div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1 <?=$panel;?>" style="margin-left:-26px;">

								<div class="panel-mp panel-info text-center panel-custom">

									<div class="panel-body panel-body-custom">

										<p><strong><?=$nomPanel;?></strong></p>

						<?php

						}

						$pReg = false;

					}

					if ( $datosSalaGine[$i]['sal_pertenece_grupo'] == 'S' ) {

						$preCama = $datosSalaGine[$i]['sal_nombre_mostrar'];

					} else {

						$preCama = '';

					}

					echo cargarCama($objCon, $datosSalaGine[$i], $draggableMP, $classMP, $datosSalaGine[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);

				}

				$sal_id_anterior = $datosSalaGine[$i]['sal_id'];

				$grupo_id_anterior = $datosSalaGine[$i]['tipo_sala_grupo_id'];

				if ( $ultimaCama['cam_id'] == $datosSalaGine[$i]['cam_id'] ) {

					echo '</div></div></div>';

				}

			}

			?>

		</div> -->

	<!-- </div> -->

	<!--
	**************************************************************************
								LEYENDAS COLORES
	**************************************************************************
	-->
	<!-- <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" >

		<div class="panel panel-default" >

			<div class="panel-heading" style="background-color:#1e73be;color:#ffffff; padding: 0px 15px !important;"><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;<label class="detalle" style="color: #ffffff;">Colores</label></div>

			<div class="panel-body" style="padding: 2px !important;">

				<div class="row col-lg-12" align="left" style="position: relative;left: 50px;">

					<span style="border:1px solid;border-color:#000000; background-color: #32CD32"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;Indicaciones Aplicadas en Menos de 6 Horas</label><br>

				</div>

				<div class="row col-lg-12" align="left" style="position: relative;left: 50px;">

					<span style="border:1px solid;border-color:#000000; background-color: #FFD700";><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;6 Horas Después de Inicio de Atención</label><br>

				</div>

				<div class="row col-lg-12" align="left" style="position: relative;left: 50px;">

					<span style="border:1px solid;border-color:#000000; background-color: #eb4d4b"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;Menos de 12 Horas Transcurridas desde Alta Urgencia</label><br>

				</div>

				<div class="row col-lg-12" align="left" style="position: relative;left: 50px;">

					<span style="border:1px solid;border-color:#000000; background-color: #e056fd"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;Más de 12 Horas Transcurridas desde Alta Urgencia</label><br>

				</div>

			</div>

		</div>

	</div> -->

	<!--
	**************************************************************************
								LEYENDAS ICONOS
	**************************************************************************
	-->
	<!-- <div class="col-1 col-sm-1 col-md-1 col-lg-1 col-xl-1" >

		<div class="panel panel-default" >

			<div class="panel-heading" style="background-color:#1e73be;color:#ffffff; padding: 0px 15px !important;"><i class="fa fa-info-circle" aria-hidden="true"></i> &nbsp;<label class="detalle" style="color: #ffffff;">Iconos</label></div>

			<div class="panel-body" style="padding: 2px !important;">

				<div class="row">

					<div class="col-lg-6" align="left" style="position: relative;left: 20px;">

						<span><i class="fa fa-play" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Inicio de Atención</label><br>

					</div>

					<div class="col-lg-6" align="left" style="position: relative;">

						<span><i class="fa fa-home" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Alta a Casa Aplicada</label><br>

					</div>

					<div class="col-lg-6" align="left" style="position: relative;left: 20px;">

						<span><i class="fa fa-info-circle" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Indicación Solicitada</label><br>

					</div>

					<div class="col-lg-6" align="left" style="position: relative;">

						<span><i class="fa fa-ambulance" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Derivado a Otra Institución</label><br>

					</div>

					<div class="col-lg-6" align="left" style="position: relative;left: 20px;">

						<span><i class="fa fa-times" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Rechaza Hospitalización</label><br>

					</div>

					<div class="col-lg-6" align="left" style="position: relative;">

						<span><i class="fa fa-plus" aria-hidden="true"></i></span><label style="font-weight:normal; color: #000000; font-weight:bold;">&nbsp;&nbsp;Defunción</label><br>

					</div>

				</div>

			</div>

		</div>

	</div> -->

<!-- </div> -->