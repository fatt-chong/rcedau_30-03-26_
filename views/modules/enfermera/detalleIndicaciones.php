<?php
session_start();
error_reporting(0);
$permisos = $_SESSION["permisosDAU"];

require("../../../config/config.php");
require_once ("../../../class/Util.class.php"); 			$objUtil 			= new Util;
require_once("../../../class/Connection.class.php");		$objCon 			= new Connection();			$objCon->db_connect();
require_once("../../../class/RegistroClinico.class.php");	$objRegistroClinico = new RegistroClinico;
require_once('../../../class/Imagenologia.class.php');		$objRayos 			= new Imagenologia;
require_once('../../../class/Laboratorio.class.php');		$objLaboratorio 	= new Laboratorio;
require_once('../../../class/Dau.class.php');				$objDau 			= new Dau;


$parametros 			= $objUtil->getFormulario($_POST);
$resRce 				= $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros["rce_id"] 	= $resRce[0]["regId"];
$rsTipoExmamen 			= $objRayos->getTipoExamen($objCon);
$listaServicios 		= $objRegistroClinico->listarServiciosIndicaciones($objCon);
$listadoExaLab 			= $objLaboratorio->getExamenesLaboratorio($objCon);
$listadoIndicaciones 	= $objRegistroClinico->listarIndicacionesRCE_enf2($objCon,$parametros);
$datosDAU 				= $objDau ->ListarPacientesDau($objCon, $parametros);
$estadoDau 				= (int)$datosDAU[0]["est_id"];

$estadoSolicitado 		= 1;
$estadoAplicado 		= 4;
$estadoCerrado 			= 5;
$estadoAnulado 			= 6;
$estadoRecepcionado 	= 7;
$servicioImagenologia 	= 1;

$version = $objUtil->versionJS();
?>



<!--
########################################################################################################################
ARCHIVO JS
-->
<script type="text/javascript" src="<?php echo PATH?>/controllers/client/enfermera/indicaciones_modal.js?v=<?php echo $version;?>142"></script>



<!--
########################################################################################################################
ESTILOS
-->


<style>
	div.dataTables_wrapper div.dataTables_filter label {
		display: none;
		font-weight: normal;
		text-align: left;
		white-space: nowrap;
	}

	div.dataTables_wrapper div.dataTables_info {
		display: none;
		padding-top: 8px;
		white-space: nowrap;
	}
	#tablaContenidoIndicacionesEnfermera .ui-selected {
	    background: #c7defd;
	    color: #000000;
	}
	#tablaContenidoIndicacionesEnfermera .ui-selecting{
	    background: #c7defd;
	    color: #000000;
	}
	.color-E7F4FF{
   		background-color: #E1F1FF;
	}
	.color-F0FFF0{

   		background-color: #E8FFE8;
	}
	.color-FFF0F6{

   		background-color: #FFDBE5;
	}
</style>



<!--
########################################################################################################################
DESPLIGUE FORMULARIO
-->
<!-- <div class="container-fluid"> -->
	<!-- <div class="col-md-12"> -->
		<div class="container mt-1">
		    <select id="frm_aplicados" name="frm_aplicados" class="form-control form-control-sm mifuente12" >
				<option value="" <?php if($parametros['frm_aplicados'] == ""){ echo "selected";} ?>  >Todas las indicaciones</option>
				<option value="S" <?php if($parametros['frm_aplicados'] == "S"){ echo "selected";} ?> >Aplicados</option>
				<option value="N" <?php if($parametros['frm_aplicados'] == "N"){ echo "selected";} ?> >Pendientes de Aplicar</option>
		    </select>
		</div>
		<form id="frm_solicitud_rayo" name="frm_solicitud_rayo">
			<!-- *********************************************************************
                                    	Campos Ocultos
			**************************************************************************
			-->
			<input type="hidden" id="dau_id" name="dau_id" value="<?php echo $parametros["dau_id"]?>">
			<input type="hidden" id="id_rce" name="id_rce" value="<?php echo $parametros["rce_id"]?>">
			<input type="hidden" id="ind_id" name="ind_id" value="<?php echo $parametros["ind_id"]?>">
			<input type="hidden" id="sala_cama" name="sala_cama" value="<?php echo $datosDAU[0]["salaCama"];?>">
			<input type="hidden" id="idPaciente" name="idPaciente" value="<?php echo $datosDAU[0]["id_paciente"];?>">
			<input type="hidden" id="frm_numero_dau_session" name="" value="<?php echo $_SESSION["modulos"]["Enfermera"]["indacacionEnf"]["frm_numero_dau"]?>">
			<input type="hidden" id="frm_tipoCategorizacion_session" name="" value="<?php echo $_SESSION["modulos"]["Enfermera"]["indacacionEnf"]["frm_tipoCategorizacion"]?>">
			<input type="hidden" id="frm_nombrePaciente_session" name="" value="<?php echo $_SESSION["modulos"]["Enfermera"]["indacacionEnf"]["frm_nombrePaciente"]?>">
			<input type="hidden" id="frm_rut_session" name="" value="<?php echo $_SESSION["modulos"]["Enfermera"]["indacacionEnf"]["frm_rut"]?>">



			<!-- *********************************************************************
                                    	Indicaciones
			**************************************************************************
			-->
			<div style="display: block; max-height: 750px; overflow-y: auto; overflow-x: hidden">
				<table width="100%" id="tablaContenidoIndicacionesEnfermera" class="table  table-hover table-condensed tablasHisto">
					<thead>
						<tr class="detalle">
							<td
								
								class=" mifuente12 text-center" width="36%"
							>
								Indicación
							</td>
							<td
								
								class=" mifuente12 text-center" width="15%"
							>
								Tipo
							</td>
							
							<td
								
								class=" mifuente12 text-center" width="12%"
							>
								Inicio Indicación
							</td>
							<td
								
								class=" mifuente12 text-center" width="12%"
							>
								Toma Muestra
							</td>
							<td
								
								class=" mifuente12 text-center" width="10%"
							>
								Aplicado
							</td>
							<td 
								
								class=" mifuente12 text-center" width="15%"
							>
								Accion
							</td>
							<td style="display:none">
								UsuarioTomaMuestra
							</td>
							<td style="display:none">
								UsuarioIndicacion
							</td>
						</tr>
					</thead>
					<tbody id="contenidoIndicacionesEnfermera">
						<?php
						for ($i = 0; $i < count($listadoIndicaciones); $i++) {
							$clasesYColores = array(
								1 => array(
									"seleccionable" => "seleccionable",
									"color" => "color-E7F4FF"
								),
								7 => array(
									"seleccionable" => "seleccionable",
									"color" => "color-E7F4FF"
								),
								4 => array(
									"seleccionable" => "restringida",
									"color" => "color-F0FFF0"
								),
								6 => array(
									"seleccionable" => "restringida",
									"color" => "color-FFF0F6"
								)
							);
							$estado = "";

							if ($listadoIndicaciones[$i]["descripcion"] === "Solicitud Laboratorio") {
								if (
									(int)$listadoIndicaciones[$i]["estado"] === $estadoSolicitado
									&& !$objUtil->existe($listadoIndicaciones[$i]["fechaTomaMuestra"])
									&& !solicitudCanceladaPreviamente($listadoIndicaciones[$i]["sol_id"])
								){
									$estadoSolicitud = $listadoIndicaciones[$i]["estadoDescripcion"];

								} else if (
									(int)$listadoIndicaciones[$i]["estado"] === $estadoSolicitado
									&& $objUtil->existe($listadoIndicaciones[$i]["fechaTomaMuestra"])
								) {
									$estadoSolicitud = $listadoIndicaciones[$i]["estadoDescripcion"].'<br />(Toma Muestra)';

								} else if (
									(int)$listadoIndicaciones[$i]["estado"] === $estadoSolicitado
									&& !$objUtil->existe($listadoIndicaciones[$i]["fechaTomaMuestra"])
									&& solicitudCanceladaPreviamente($listadoIndicaciones[$i]["sol_id"])
								) {
									$estadoSolicitud = $listadoIndicaciones[$i]["estadoDescripcion"].'<br />(M. Cancelada)';

								} else if (
									(int)$listadoIndicaciones[$i]["estado"] === $estadoRecepcionado
									&& $objUtil->existe($listadoIndicaciones[$i]["fechaTomaMuestra"])
								) {
									$estadoSolicitud = 'Solicitado<br />(Recepcionado)';

								} else {
									$estadoSolicitud = $listadoIndicaciones[$i]["estadoDescripcion"];
								}

							} else {
								$estadoSolicitud = $listadoIndicaciones[$i]["estadoDescripcion"];
							}
							$fechaInserta 	= date("d-m-Y H:i",strtotime($listadoIndicaciones[$i]["fechaInserta"]));
							$fechaAplica 	= ($objUtil->existe($listadoIndicaciones[$i]["fechaAplica"]))
									? date("d-m-Y H:i",strtotime($listadoIndicaciones[$i]["fechaAplica"]))
									: "";
							?>
							<tr
								class="<?php echo $clasesYColores[(int)$listadoIndicaciones[$i]["estado"]]["color"]; ?> <?php echo $clasesYColores[(int)$listadoIndicaciones[$i]["estado"]]["seleccionable"];; ?>"
								id="<?php echo $listadoIndicaciones[$i]["sol_id"]."-".$listadoIndicaciones[$i]["servicio"]."-".$listadoIndicaciones[$i]["sic_id"]?>"
								style="cursor:context-menu;"
							>
								<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center " width="36%" style="vertical-align:middle;">
									<?php
									echo $listadoIndicaciones[$i]["Prestacion"];

									if ($objUtil->existe($listadoIndicaciones[$i]["descripcionClasificacion"])) {
										echo "
											<br />
											({$listadoIndicaciones[$i]["descripcionClasificacion"]})
										";
									}
									?>
								</td>
								<?php
								for ($z = 0; $z < count($listaServicios); $z++) {
									if ((int)$listadoIndicaciones[$i]["servicio"] === (int)$listaServicios[$z]["ser_codigo"]) {
										echo '
											<td
												class="my-1 py-1 mx-1 px-1 mifuente11 text-center" width="15%" style="vertical-align:middle;"
												id="'.$listadoIndicaciones[$i]["servicio"].'"
											>
												 <b>'.$listaServicios[$z]["ser_descripcion"].' ('.$estadoSolicitud.')</b><br><label style="font-size: 11px; color: cornflowerblue; margin-bottom: 0rem !important;">'.$listadoIndicaciones[$i]["usuarioInserta"].'
										-
										'.$fechaInserta.'</label>
											</td>
										';
									}
								}
								?>
								
								<?php
								$fechaInserta = date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]["fechaInserta"]));
								$fechaAplica = ($objUtil->existe($listadoIndicaciones[$i]["fechaAplica"]))
									? date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]["fechaAplica"]))
									: "";

								

								echo (
									$objUtil->existe($listadoIndicaciones[$i]["UsuarioIniciaIndicacion"])
									&& $objUtil->existe($listadoIndicaciones[$i]["fechaIniciaIndicacion"])
								)
									? '
										<td
											class="my-1 py-1 mx-1 px-1 mifuente11 text-center"  width="12%" style="vertical-align:middle;"
										>
											'.$listadoIndicaciones[$i]["UsuarioIniciaIndicacion"].'
											<br />
											'.date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]["fechaIniciaIndicacion"])).'
										</td>
										'
									: '<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" width="12%" style="vertical-align:middle;">-----</td>';

								echo (
									$objUtil->existe($listadoIndicaciones[$i]["usuarioTomaMuestra"])
									&& $objUtil->existe($listadoIndicaciones[$i]["fechaTomaMuestra"])
								)
									? '
										<td
											class="my-1 py-1 mx-1 px-1 mifuente11 text-center" width="12%" style="vertical-align:middle;"
										>
											'.$listadoIndicaciones[$i]["usuarioTomaMuestra"].'
											<br />
											'.date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]["fechaTomaMuestra"])).'
										</td>
										'
									: '<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center"  width="12%" style="vertical-align:middle;">-----</td>';

								echo (
									(int)$listadoIndicaciones[$i]["estado"] === $estadoAplicado
									|| (int)$listadoIndicaciones[$i]["estado"] === $estadoAnulado
								)
									?	'
										<td
											class="my-1 py-1 mx-1 px-1 mifuente11 text-center" width="10%"  style="vertical-align:middle;"
										>
											'.$listadoIndicaciones[$i]["usuarioAplica"].'
											<br />
											'.$fechaAplica.'
										</td>
										'
									: '<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center"  width="10%"  style="vertical-align:middle;" >-----</td>';
								?>
								<!-- Acciones -->
								<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" width="15%"  style="vertical-align:middle;">
									<?php
									$displayAplicar = 'style="display:none"';
									$displayAnular = 'style="display:none"';
									$displayTratamiento = 'style="display:none"';
									$displayLaboratorio = 'style="display:none"';
									$displayGestionRealizada = 'style="display:none"';

									if (solicitudEsDeLaboratorio($listadoIndicaciones[$i]["descripcion"])) {
										$displayAplicar = 'style="display:none"';
									}

									if(solicitudEsDeEspecialista($listadoIndicaciones[$i]["descripcion"])) {
										$idSolicitud = $listadoIndicaciones[$i]["sol_id"];
										$class = 'btn btn-sm mifuente btn-primary verModalDetalleIndicacion2';
										if (
											$objUtil->existe($listadoIndicaciones[$i]["UsuarioIniciaIndicacion"])
											&& !$objUtil->existe($listadoIndicaciones[$i]["usuarioTomaMuestra"])
											&& (int)$listadoIndicaciones[$i]["estado"] === $estadoSolicitado
										) {
											$displayGestionRealizada = "";
										}

									} else if(solicitudEsDeEspecialistaOtros($listadoIndicaciones[$i]["descripcion"])) {
										$idSolicitud = $listadoIndicaciones[$i]["sol_id"];
										$class = 'btn btn-sm mifuente btn-primary verModalDetalleIndicacion2Otros';
										if (
											$objUtil->existe($listadoIndicaciones[$i]["UsuarioIniciaIndicacion"])
											&& !$objUtil->existe($listadoIndicaciones[$i]["usuarioTomaMuestra"])
											&& (int)$listadoIndicaciones[$i]["estado"] === $estadoSolicitado
										) {
											$displayGestionRealizada = "";
										}

									}else {
										$idSolicitud = $listadoIndicaciones[$i]["sol_id"]."-".$listadoIndicaciones[$i]["servicio"];
										$class = 'btn btn-sm mifuente btn-primary verModalDetalleIndicacion';
									}

									if (
										solicitudAunNoAplicada($listadoIndicaciones[$i]["estado"])
										&& !solicitudEsDeEspecialista($listadoIndicaciones[$i]["descripcion"])
										&& !solicitudEsDeEspecialistaOtros($listadoIndicaciones[$i]["descripcion"])
										&& !solicitudEsDeLaboratorio($listadoIndicaciones[$i]["descripcion"])
									) {
										$displayAplicar = '';
										$displayAnular = '';
									}

									if (solicitudEsTipoTratamientoYAunNoSeInicia($listadoIndicaciones[$i])) {
										$displayAplicar = 'style="display:none"';
										$displayAnular = 'style="display:none"';
										if($listadoIndicaciones[$i]["estado"] == 1){
											$displayTratamiento = '';
										}
										// $displayTratamiento = '';

									} else if (
										solicitudAunNoAplicada($listadoIndicaciones[$i]["estado"])
										&& solicitudEsTipoTratamientoYSeInicio($listadoIndicaciones[$i])
									) {
										$displayAplicar = '';
										$displayAnular = '';
									}

									if (solicitudEsTipoLaboratorioYAunNoSeTomaMuestra($listadoIndicaciones[$i]) && $listadoIndicaciones[$i]["estado"] != 6){
										$displayAplicar = 'style="display:none"';
										$displayAnular = 'style="display:none"';
										$displayLaboratorio = '';

									} else if (
										solicitudAunNoAplicada($listadoIndicaciones[$i]["estado"])
										&& solicitudEsTipoLaboratorioYSeInicio($listadoIndicaciones[$i])
										&& !solicitudEsTipoLaboratorioYSeInicio($listadoIndicaciones[$i])
									) {
										$displayAplicar = '';
										$displayAnular = '';

									} else if (
										solicitudAunNoAplicada($listadoIndicaciones[$i]["estado"])
										&& solicitudEsTipoLaboratorioYSeInicio($listadoIndicaciones[$i])
										&& solicitudEsTipoLaboratorioYSeInicio($listadoIndicaciones[$i])
									) {
										$displayAplicar = 'style="display:none"';
										$displayAnular = '';
									}
									?>
									<!-- Ver información de solicitud -->
									<button
										type="button"
										class="<?php echo $class; ?>"
										id="<?php echo $idSolicitud; ?>"
										alt="Detalle Solicitud Indicacion"
										title="Detalle Solicitud Indicacion"
									>
										<i class="fa fa-search"/>
									</button>

									<!-- Hoja de Imagenología -->
									<?php
									if ((int)$listadoIndicaciones[$i]["servicio"] === $servicioImagenologia){
										if (date("Y-m-d", strtotime($listadoIndicaciones[$i]["fechaInserta"])) < FECHA_INTEGRACION_DALCA) {
											echo '
												<button
													type="button"
													class="btn btn-sm mifuente btn-primary verHojaImagenologia"
													id="'.$idSolicitud.'"
													alt="Hoja Imagenología"
													title="Hoja Imagenología"
												>
													<i class="fas fa-file-pdf"></i>
												</button>
											';
										}

										if (
											date("Y-m-d", strtotime($listadoIndicaciones[$i]["fechaInserta"])) >= FECHA_INTEGRACION_DALCA
											&& $objUtil->existe($listadoIndicaciones[$i]["idSolicitudDalca"])
										) {
											echo '
												<button
													type="button"
													class="btn btn-sm mifuente btn-primary verHojaImagenologiaDalca"
													id="'.$listadoIndicaciones[$i]["idSolicitudDalca"].'"
													alt="Hoja Imagenología"
													title="Hoja Imagenología"
												>
													<i class="fas fa-file-pdf"></i>
												</button>
											';

											if ((int)$listadoIndicaciones[$i]["estado"] === $estadoAplicado) {
												echo '
													<button
														type="button"
														class="btn btn-sm mifuente btn-warning verInformeSolicitudImagenologiaDalca"
														id="'.$listadoIndicaciones[$i]["idSolicitudDalca"].'"
														alt="Informe Solicitud"
														title="Informe Solicitud"
													>
														<i class="fas fa-file-pdf"></i>
													</button>
												';
											}

											if ((int)$listadoIndicaciones[$i]["estado"] !== $estadoAnulado) {
												echo '
													<button
														type="button"
														class="btn btn-sm mifuente btn-success verImagenSolicitudImagenologiaDalca"
														id="'.$listadoIndicaciones[$i]["idSolicitudDalca"].'"
														alt="Informe Solicitud"
														title="Imagen Solicitud"
													>
														<i class="fa fa-camera"/>
													</button>
												';
											}
										}
									}

									if (
										(
											!$objUtil->existe($listadoIndicaciones[$i]["informe"])
											|| $listadoIndicaciones[$i]["informe"] === "N"
										)
										&& $objUtil->existe($listadoIndicaciones[$i]["urlResultado"])
									) {
										echo '
											<button
												type="button"
												class="btn btn-sm mifuente btn-warning verURLResultado"
												id="'.$listadoIndicaciones[$i]["urlResultado"].'"
												alt="Detalle Examen"
												title="Detalle Examen"
											>
												<i class="fas fa-image"></i>
											</button>
										';
									}

									if (
										$objUtil->existe($listadoIndicaciones[$i]["informe"])
										&& $listadoIndicaciones[$i]["informe"] === "S"
										&& $objUtil->existe($listadoIndicaciones[$i]["urlResultado"])
									){
										echo '
											<button
												type="button"
												class="btn btn-sm mifuente btn-success verURLResultado"
												id="'.$listadoIndicaciones[$i]["urlResultado"].'"
												alt="Detalle Examen"
												title="Detalle Examen Validado"
											>
												<i class="fas fa-image"></i>
											</button>
										';
									}

									if (
										$estadoDau !== $estadoCerrado
										&& $estadoDau !== $estadoAnulado
										&& $estadoDau !== $estadoRecepcionado
									) {
										//Aplicar solicitud
										if ((int)$listadoIndicaciones[$i]["servicio"] !== $servicioImagenologia) {
											echo '
												<button
													type="button"
													class="btn btn-sm mifuente btn-success aplicarIndicacion"
													id="'.$listadoIndicaciones[$i]["sol_id"].'-'.$listadoIndicaciones[$i]["servicio"].'-'.$listadoIndicaciones[$i]["sic_id"].'"
													alt="Aplicar Solicitud Indicacion"
													title="Aplicar Solicitud Indicacion"
													'.$displayAplicar.'
												>
													<i class="fa fa-check"/>
												</button>
											';
										}

										//Anular solicitud
										echo '
											<button
												type="button"
												class="btn btn-sm mifuente btn-warning anularIndicacionesAplicadas"
												id="'.$listadoIndicaciones[$i]["sol_id"].'-'.$listadoIndicaciones[$i]["servicio"].'"
												alt="Anular Solicitud Indicacion"
												title="Anular Solicitud Indicacion"
												'.$displayAnular.'
											>
												<i class="fa fa-minus-circle"/>
											</button>
										';

										//Iniciar solicitud
										echo '
											<button
												type="button"
												class="btn btn-sm mifuente btn-light iniciarIndicaciones"
												id="'.$listadoIndicaciones[$i]["sol_id"].'-'.$listadoIndicaciones[$i]["servicio"].'"
												alt="Iniciar Indicacion"
												title="Iniciar"
												'.$displayTratamiento.'
											>
												<i class="fas fa-play"></i>
											</button>
										';

										//Toma muestra
										echo '
											<button
												type="button"
												id="'.$listadoIndicaciones[$i]["sol_id"].'-'.$listadoIndicaciones[$i]["servicio"].'"
												class="btn btn-sm mifuente bg-light tomaMuestra"
												alt="Toma de Muestra"
												title="Muestra"
												'.$displayLaboratorio.'
											>
												<i class="fas fa-thumbtack"></i>
											</button>
										';

										//Realizar gestión solicitud especialista
										echo '
											<button
												type="button"
												class="btn btn-sm mifuente btn-light gestionRealizada"
												id="'.$idSolicitud.'"
												alt="Gestión Realizada"
												title="Gestión Realizada"
												'.$displayGestionRealizada.'
											>
												<i class="fas fa-play"></i>
											</button>
										';
									}
									?>
								</td>
								<td hidden="true">
									<?php
									echo $listadoIndicaciones[$i]["usuarioTomaMuestra"];
									?>
								</td>
								<td hidden="true">
									<?php
									echo $listadoIndicaciones[$i]["UsuarioIniciaIndicacion"]
									?>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
    </form>
	<!-- </div> -->
<!-- </div> -->


<!--
########################################################################################################################
LEYENDAS
-->
<div class="row m-2" >
	<div class="col-7  mifuente12 p-1 mt-1 mb-0 pb-0">
		<strong><i class="fas fa-info mr-2"></i>&nbsp;Leyendas </strong>
		<div class="thumbnail">
			<table id="" width="100%" class="display table-condensed table-hover mt-1 ">
				<tbody>
					<tr>
						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:33%">
							<label class="" style="margin-bottom: 0rem !important;width: 100%; ">
								<span style="border:1px solid;border-color:#000; background-color: #E1F1FF;" class="color-E7F4FF"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"></label>&nbsp;&nbsp;</span> <br>Indicaciones solicitadas.
							</label>
						</td>
						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:33%">
							<label class="" style="margin-bottom: 0rem !important;width: 100%;">
								<span style="border:1px solid;border-color:#000; background-color: #FFDBE5" class="color-FFF0F6"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"></label></span> <br>Indicaciones Terminadas. 
							</label>
						</td>
						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:33%">
							<label class="" style="margin-bottom: 0rem !important;width: 100%;">
							<span style="border:1px solid;border-color:#000; background-color: #E8FFE8" class="color-F0FFF0"><label style="width: 50px;">&nbsp;</label></span><label style="font-weight:normal;"></label></span> <br>Indicaciones aplicadas.
							</label>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-2  mifuente12 p-1 mt-1 mb-0 pb-0">
		<strong>&nbsp; </strong>
	</div>
	<div class="col-3  mifuente12 p-1 mt-1 mb-0 pb-0">
		<strong><i class="fas fa-info mr-2"></i>&nbsp;Acciones Globales </strong>
		<div class="thumbnail">
			<table id="" width="100%" class="display table-condensed table-hover mt-1 ">
				<tbody>
					<tr>
						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:25%">
							<button type="button" class="btn btn-sm mifuente col-lg-12 btn-success aplicarVariasIndicaciones"  alt="Aplicar Solicitudes Indicacion" title="Aplicar Solicitudes Indicacion" ><i class="fa fa-check" /></button>
						</td>

						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:25%">
							<button type="button" class="btn btn-sm mifuente col-lg-12 btn-warning anularVariasIndicaciones"  alt="Anular Solicitudes Indicacion" title="Anular Solicitudes Indicacion"><i class="fa fa-minus-circle" /></button>
						</td>

						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:25%">
							<button type="button" class="btn btn-sm mifuente col-lg-12 btn-info iniciarVariasIndicaciones"  alt="Iniciar Solicitudes Indicacion" title="Iniciar Solicitudes Indicacion"><i class="fas fa-play"></i></button>
						</td>

						<td class=" mifuente11 my-1 py-1 mx-1 px-1 " align="center" style="width:25%">
							<button type="button" class="btn btn-sm mifuente col-lg-12 btn-info tomaMuestraVariasIndicaciones"  alt="Toma Muestras Solicitudes Indicacion" title="Toma Muestras Solicitudes Indicacion"><i class="fas fa-thumbtack"></i></button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!--
########################################################################################################################
FUNCIONES PHP
-->
<?php
function solicitudEsDeLaboratorio ($tipoSolicitud) {
	return $tipoSolicitud === "Solicitud Laboratorio";
}



function solicitudEsDeEspecialista ($tipoSolicitud) {
	return $tipoSolicitud === "Solicitud Especialista";
}


function solicitudEsDeEspecialistaOtros ($tipoSolicitud) {
	return $tipoSolicitud === "Solicitud Especialista Otros";
}


function solicitudAunNoAplicada ($estadoSolicitud) {
	return (
		(int)$estadoSolicitud !== 4
		&& (int)$estadoSolicitud !== 5
		&& (int)$estadoSolicitud !== 6
		&& (int)$estadoSolicitud !== 8
	);
}



function solicitudEsTipoTratamientoYAunNoSeInicia ($solicitud) {
	return (
		$solicitud["descripcion"] === "Solicitud Tratamiento"
		&& empty($solicitud["UsuarioIniciaIndicacion"])
		&& empty($solicitud["fechaIniciaIndicacion"])
	);
}



function solicitudEsTipoTratamientoYSeInicio ($solicitud) {
	return (
		$solicitud["descripcion"] === "Solicitud Tratamiento"
		&& !empty($solicitud["UsuarioIniciaIndicacion"])
		&& !empty($solicitud["fechaIniciaIndicacion"])
	);
}



function solicitudEsTipoLaboratorioYAunNoSeTomaMuestra ($solicitud) {
	return (
		$solicitud["descripcion"] === "Solicitud Laboratorio"
		&& empty($solicitud["usuarioTomaMuestra"])
		&& empty($solicitud["fechaTomaMuestra"])
	);
}



function solicitudEsTipoLaboratorioYSeInicio ($solicitud) {
	return (
		$solicitud["descripcion"] === "Solicitud Laboratorio"
		&& !empty($solicitud["usuarioTomaMuestra"])
		&& !empty($solicitud["fechaTomaMuestra"])
	);
}



function solicitudCanceladaPreviamente ($idExamen) {
	require_once("../../../class/Connection.class.php");
	require_once('../../../class/Laboratorio.class.php');

	$objCon = new Connection();
	$objLaboratorio	= new Laboratorio;

	$objCon->db_connect();

	$resultadoConsulta = $objLaboratorio->examenCanceladoPreviamente($objCon, $idExamen);

	return !(
		empty($resultadoConsulta[0]["sol_usuarioCancela"])
		|| is_null($resultadoConsulta[0]["sol_usuarioCancela"])
	);
}
