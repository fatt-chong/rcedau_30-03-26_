<?php
session_start();
ini_set('memory_limit', '1000M');

$permisos = $_SESSION['permisosDAU'.SessionName];

if ( array_search(831, $permisos) == null ) {$GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));}

require_once('../../../class/Connection.class.php');	$objCon        = new Connection; $objCon->db_connect();
require_once('../../../class/MapaPiso.class.php');  	$objMapaPiso   = new MapaPiso;
require_once('../../../class/Dau.class.php');  			$objDau   	   = new Dau;
require_once('../../../class/Util.class.php');      	$objUtil       = new Util;
require_once('../../../class/Config.class.php');      	$objConfig     = new Config;
require("../../../config/config.php");

if ( ! empty($_POST) && ! is_null($_POST) ) {
	$parametros	= $objUtil->getFormulario($_POST);
}
if ( ! empty($_GET) && ! is_null($_GET) ) {
	$parametros	= $objUtil->getFormulario($_GET);
}

$version    		= $objUtil->versionJS();
$datos          	= $objMapaPiso->loadTablaFull($objCon);
$rsUnidad          	= $objMapaPiso->SelectUnidad($objCon);

$datosCama   		= $objMapaPiso->loadCamasFull($objCon, '', '', 'A');
// $datosCamaGroup   	= $objMapaPiso->loadCamasFullGroup($objCon, '', '', 'A');

$datosCamaPedi		= $objMapaPiso->loadCamasFull($objCon, '', '', 'P');
$datosCamaPediGroup	= $objMapaPiso->loadCamasFullGroup($objCon, '', '', 'P');

$datosSalaGine		= $objMapaPiso->loadCamasFull($objCon, '', '', 'GO');
$datosTriageAct 	= $objConfig->getTipoTriageActivo($objCon);
$listado 			= $objDau->listarDAUEspecialidadGinecologica($objCon);

if ( isset($_SESSION['usuarioActivo']['usuario']) ) {
	$idusuario 	= $_SESSION['usuarioActivo']['usuario'];
} else {
	$idusuario 	= $_SESSION['MM_Username'.SessionName];
}
$tipoMapaUsuario = $objMapaPiso->consultarTipoMapaUsuarioUsuario($objCon, $idusuario);
$_SESSION['contadorColumnas'] = 2;
$_SESSION['contadorColumnas3'] = 3;
$_SESSION['contadorColumnas4'] = 4;
$respVerMP = $objMapaPiso->verMapaPisoXProfesional($objCon, $parametros);
//Permisos
if ( array_search(822,$permisos) != null ) {
	$draggableLPE = 'true';
	$classLPE = 'tbl_esp ';
} else {
	$draggableLPE = 'false';
}
if ( array_search(821,$permisos ) != null ) {
	$draggableLPC = 'true';
	$classLPC = 'tbl_cat ';
} else {
	$draggableMP = 'false';
}
if ( array_search(820,$permisos) != null ) {
	$draggableMP = 'true';
	$classMP = 'camaMapaPiso ';
} else {
	$draggableMP = 'false';
}
//Recorrido de contenido
$SC2  = 0;
$CAT2 = 0;
for( $i = 0; $i < count($datos); $i++) {
	if ( $datos[$i]['est_id'] == '2' ) {
		$CAT2 = $CAT2 + 1;
	}
	if ( $datos[$i]['est_id'] == '1' && $datos[$i]['cat_nombre_mostrar'] == '' ) {
		$SC2 = $SC2 + 1;
	}
}
$nombre = $_SESSION['MM_Username'.SessionName];
$rut = $_SESSION['MM_RUNUSU'.SessionName];
if ( isset($_SESSION['usuarioActivo']) ) {
	$nombre = $_SESSION['usuarioActivo']['usuario'];
	$rut = $_SESSION['usuarioActivo']['rut'];
}
$usuarioMarcaAgua = strtoupper (substr($nombre, 0, 3)."".substr($rut,-3));
?>




<!--
################################################################################################################################################
                                                       			    CARGA JS
-->
<script type="text/javascript" src="<?=PATH?>/controllers/client/mapa_piso_full/mapa_piso_full.js?v=<?=$version;?>"></script>

<!--
################################################################################################################################################
                                                      			CARGA ESTILOS
-->
<style>
	div.dataTables_wrapper div.dataTables_length label {
		display: none;
	}
	div.dataTables_wrapper div.dataTables_filter {
		color: #337ab7;
		font-size: 10px;
		font-family: 'SourceSansPro-Semibold', Fallback, sans-serif;
	}
	mark {
	  background: yellow;
	}
	mark.current {
	  background: orange;
	}
	.sintomasRespiratorios {
		border: 2px solid;
		border-color: black;
	}
	.tooltip-inner {

    	background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='50px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(180, 180, 180)' font-size='13'><?php echo $usuarioMarcaAgua; ?></text></svg>");
	}
	body {

		background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' version='1.1' height='100px' width='100px'><text transform='translate(20, 100) rotate(-45)' fill='rgb(231, 226, 226)' font-size='20' ><?php echo $usuarioMarcaAgua; ?></text></svg>");
	}
</style>

<input type="hidden" name="tipoMapaUsuario"  id="tipoMapaUsuario"  value="<?php echo $tipoMapaUsuario['usu_conf_urgencia']; ?>">
<input type="hidden" name="tipoMapa"  		 id="tipoMapa"  	   value="<?php echo $parametros['tipoMapa']; ?>">
<!--
################################################################################################################################################
                                                       			TÍTULO
-->

<div class=" jumbotron p-0 m-0 mb-0" style="background-color:#ffffff00; ">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="display-4 mb-0" style="font-size: 25px !important">Mapa de Piso</h1>
        <div class="form-group mb-0 d-flex align-items-center">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" id="checkAtencionIniciadasPor" name="checkAtencionIniciadasPor">
                <label class="form-check-label" for="checkAtencionIniciadasPor">Atención Iniciadas por Mí</label>
            </div>
        </div>
    </div>
    <!-- <hr class="m-0 p-0"> -->
</div>
<div class="row" >
	<!-- Checkbox Mapa Adulto -->
	<div id="" class="col-md-1 has-feedback checkBoxsMapas">
		<label for="" class="control-label">&nbsp;</label>
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_adulto" name="frm_mp_adulto" type="checkbox" value="S">
				<label for="frm_mp_adulto"  class="control-label">
				    <strong>Adulto</strong>
				</label>
			</div>
		</div>
	</div>
	<!-- Checkbox Mapa Pediátrico -->
	<div id="" class="col-md-1 has-feedback checkBoxsMapas">
		<label for="" class="control-label">&nbsp;</label>
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_pediatrico" name="frm_mp_pediatrico" type="checkbox" value="S">
				<label for="frm_mp_pediatrico"  class="control-label">
					<strong>Pediátrico</strong>
				</label>
			</div>
		</div>
	</div>
	<!-- Checkbox Mapa Ginecológico -->
	<div id="" class="col-md-1 has-feedback checkBoxsMapas">
		<label for="" class="control-label">&nbsp;</label>
		<div class="input-group">
			<div class="checkbox checkbox-primary">
				<input id="frm_mp_ginecologia" name="frm_mp_ginecologia" type="checkbox" value="S">
				<label for="frm_mp_ginecologia"  class="control-label">
					<strong>Ginecología</strong>
				</label>
			</div>
		</div>
	</div>
	<?php
	$usuario        	= $objUtil->usuarioActivo();
	$permisosPerfil 	= $objConfig->cargarPermisoDau($objCon,$usuario);
	if ( array_search(1110, $permisosPerfil) != null ) { ?>
	<div class="col-md-7">&nbsp;</div>
	<div class="col-md-2">
		<button type="button" id="avisoDonanteOrganos" class="btn btn-primary"  title="Donante Órganos" style="margin-left: 30px;">Donante Órganos</button>
	</div>
	<?php } ?>
</div>
<!--
################################################################################################################################################
                                               				DESPLIEGUE MAPA PISO FULL
-->
<style type="text/css">
	
.grid-container {
    display: grid;
    grid-auto-flow: column;
    grid-auto-columns: 1fr;
    grid-template-rows: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    width: 80vw; /* Ajusta el ancho según tus necesidades */
    height: 90vh; /* Ajusta la altura según tus necesidades */
    border: 1px solid #dee2e6;
    padding: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #ffffff;
    overflow: auto; /* Agrega desplazamiento si hay demasiados elementos */
}

.grid-item {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #007bff;
    color: #ffffff;
    padding: 20px;
    font-size: 1.2em;
    text-align: center;
    border-radius: 5px;
}
</style>
<div id="divMapapisoFull" class="row  content responsive-container " style="overflow-y: hidden;" >
	<div id="divTablaMapaPiso" class="well mt-2 col-lg-3  p-1 " >
		<div class="row">
			<div class=" mifuente12 col-lg-7">
				<input type="search" class="form-control form-control-sm mifuente12" placeholder="Buscar..." >
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
				<div class="thumbnail responsive-container" >
					<table id="tablaPacientesEspera" class="display mifuente12 table table-condensed table-hover table-mapa-piso tblCat otraClass"  style="max-height: 400px; overflow-y: auto;">
						<?php
						$style = ( $parametros['tipoMapa'] == "mapaGinecologico" && $objUtil->existe($listado) ) ? "height:180px !important;" : "";
						?>
						<tbody id="tbodycategorizacion" style="<?php echo $style; ?>">
							<tr class="table-mapa-piso-encabezado table-primary3" align="center">
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="display: none !important;">
								</td>
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 12%;">
									DAU
								</td>
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 34%;">
									Paciente
								</td>
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 12%;">
									Motivo
								</td>
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 10%;">
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
								<td class="mifuente12 my-2 py-2 mx-2 px-2 text-center" style="width: 21%;">
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
								$CAT 	= $C1 + $C2 + $C3 + $C4 + $C5;
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
								switch ( $datos[$i]['est_id'] ) {
									case 1:
										$claseTablaPaciente = $classLPE.' tr_tblCat-default arrastre_espera  pacienteEnEspera '.$tipoPaciente.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
									break;
									case 2:
										$claseTablaPaciente = $classLPC.' '.$tipoCategorizacion.' pacienteCategorizado '.$tipoPaciente.' '.$sincategorizar.' '.$trasladado.' '.$sintomasRespiratorios;
									break;
								}
								?>
							<tr id="<?=$datos[$i]['dau_id']?>" draggable="true" style="cursor: pointer;background-color: #f8f8f8;" class='<?php echo $claseTablaPaciente; ?>' >
								<td align="center" class="id_dau" style="display: none !important;">
									<input type="hidden" class="inp_id_dau" value="<?=$datos[$i]['dau_id'];?>"/>
									<input type="hidden" class="tipoPaciente" value="<?=$tipoPaciente;?>"/>
									<input type="hidden" class="nombrePaciente" value="<?=strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat'])?>"/>
								</td>
								<td align="center" class="mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 15%;">
									<?=$datos[$i]['dau_id']?> <br>(<?=substr(strtoupper($datos[$i]['ate_descripcion']), 0, 1);?>)
								</td>
								<td align="center" class="mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 23.5%; font-size:8px;">
									<?=strtoupper($datos[$i]['nombres']." ".$datos[$i]['apellidopat']." ".$datos[$i]['apellidomat']) . " (".$objUtil->edadActual($datos[$i]['fechanac'])." años)";?>
								</td>
								<td align="center" class="mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 12%;">
									<?=substr(strtoupper($datos[$i]['mot_descripcion']), 0, 5)?>
								</td>
								<td align="center" class="mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 7.3%;">
									<?php
									if ( $datos[$i]['cat_nombre_mostrar'] == "" ) {
										echo "No";
									} else {
										echo $datos[$i]['cat_nombre_mostrar'];
									}?>
								</td>
								<td align="center" class="mifuente10  contenido2 my-1 py-1 mx-1 px-1" style="width: 21.3%;">
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
									} ?>
									<div id="<?php echo $idTiempoPaciente.'_'.$datos[$i]['dau_id']; ?>" class="<?php echo $claseTiempoPaciente; ?>" ></div>
								</td>
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
	<div class="col-lg-9">
		
		  <!-- <button id="caca">Button</button> -->
		 <!--  <button id="caca">Button</button>
		  <script>
		    $(document).ready(function() {
		      let isMouseDown = false;
		      $(document).mousedown(function(event) {
		        if (event.which === 1) { // 1 es el botón izquierdo del mouse
		          isMouseDown = true;
		        }
		      });
		      $(document).mouseup(function(event) {
		        if (event.which === 1) { // 1 es el botón izquierdo del mouse
		          isMouseDown = false;
		        }
		      });
		      $('#caca').mouseenter(function() {
		        if (isMouseDown) {
		          alert('Mouse over with click pressed!'); 
		          isMouseDown = false;
		        }
		      });
		      $(document).mouseleave(function() {
		        isMouseDown = false; // Restablecer el estado si el mouse deja el documento
		      });
		    });
		  </script> -->
  
		<ul class="nav nav-tabs " >
			<?php 
			for( $i = 0; $i < count($rsUnidad); $i++ ) { 
				if($i == 0){
					$active = "active";
				}else{
					$active = "";
				}
			?>
			<li class="nav-item mifuente12 col pr-0 pl-0 buscadorLi" >
				<a href="#<?=$rsUnidad[$i]['id_unidad']?>" id="<?=$rsUnidad[$i]['id_unidad']?>" data-target="#unidad<?=$rsUnidad[$i]['id_unidad']?>" style="font-size: 14px !important" class="nav-link mifuente12 text-center  <?=$active;?>  ActivoColor  sub aria-controls" role="tab" data-target="#section-6" data-toggle="tab">
					<b><?=$rsUnidad[$i]['unidad_descripcion']?></b>
				</a>
			</li>
			<?php } ?>
		</ul>
		<div class="tab-content">
			<?php 
			for( $i = 0; $i < count($rsUnidad); $i++ ) { 
				if($i == 0){
					$active = "active";
				}else{
					$active = "";
				}
			?>
		  	<div class="tab-pane <?=$active;?>" id="unidad<?=$rsUnidad[$i]['id_unidad']?>" role="tabpanel" aria-labelledby="unidad-tab">
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
												// $ultimaCama 		= end($datosCamaGroup);

												$datosCamaGroup   	= $objMapaPiso->loadCamasFullGroup($objCon, $rsUnidad[$i]['id_unidad']);
												foreach ($datosCamaGroup as $clave => $datosCamaGroupvalor) {
													if ( $datosCamaGroupvalor['sal_doble_columna'] == 'S' ) {
														$nomPanel = strtoupper($datosCamaGroupvalor['sal_nombre_mostrar']);
													} else {
														if ( strlen($datosCamaGroupvalor['sal_nombre_mostrar']) > 5 ) {
															$nomPanel = substr(strtoupper($datosCamaGroupvalor['sal_nombre_mostrar']), 0, 5).".";
														} else {
															$nomPanel = strtoupper($datosCamaGroupvalor['sal_nombre_mostrar']);
														}
													}?>
													<td align="center" valign="top" style="height: 100%;">
														<fieldset class="scheduler-border border-right text-center" style="border-right:2px solid #176b87 !important">
															<!-- <br> -->
															<div class="  "  <?=$styleRow?> >
																<div class="panel-mp panel-info text-center panel-custom">
																	<div class="panel-body panel-body-custom mifuente14" >
																		<b><?=$nomPanel;?></b>
																	</div>
																</div>
															</div>																<!-- <br> -->
															<table class=" " border="0" width="100%" height="100%" align="center" style="height: 100%;">
																<tbody>
																	<tr valign="top" align="center" >
																		<td valign="top" align="center" style="padding: 1px !important; width: 100%;">
																			<table width="100%">
																				<tbody>
																					<tr align="center" class="" >
																					<?php 
																					$datosCama2   	= $objMapaPiso->loadCamasFullTipo($objCon, '', '',  $datosCamaGroupvalor['sal_id']);
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
																							<div class="well-camas-container  verDetalle puntero  infoPaciente" id="2236853-113581"  data-original-title="" title="" style="width: 50px !important;">
																							<div id="122" class="well-camas-<?=$css_ti_aapli?>      table-orange " style=" border: 1px solid C2;width: 100%">
																							<div id="contenidoPacienteAislamiento">
																							<div id="<?=$datosCama2[$q]['sal_resumen'].'_'.$datosCama2[$q]['cam_descripcion']?>" draggable="<?=$draggableMP?>" class="contPacRecidencia  <?=$classMP?> verInfoPac center-block tooltip-mp col-cam" style="cursor: pointer; width: 50px !important ;min-height:40px;"> 
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
																							<?php 
																							// if($contadorTR % 8 == 0) {
																							// 	echo '</tr></table></td><td valign="top" align="center" style="padding: 1px !important; width: 100%;"><table><td><tr>';
																							// }else{
																							// 	echo "</tr>";
																							// }
																							// if( count($datosCama2) < 9 ) { echo "</tr>"; }
																							// else if ($contadorTR % 3 == 0){echo "</tr></table><table width='100%'>";
																							// } 
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
			<?php } ?>
		</div>
	</div>


	<!-- </div> -->
	
<!--  -->
	<!-- <div id="mapapiso_adulto" class="col-lg-7  pt-3 mt-2 pac-list "  style="background-color: #eef5ff; top: -16px !important;" >
			<div class="col-md-12"  style="text-align:center; margin-top: -7px;">
				<label for="titulo_mapa_adulto"  class="control-label mifuente14">
	           		<strong>ATENCIÓN ADULTO <br/></strong>
				</label>
			</div>
			<div class="container-xl">
				<div class="row">
			<?php
			$sal_id_anterior 	= '';
			$sal_id_actual 		= '';
			$pReg 				= '';
			$grupo_id_actual 	= '';
			$grupo_id_anterior 	= '';
			$ultimaCama 		= end($datosCama);
			for( $i = 0; $i < count($datosCama); $i++ ) {
				$sal_id_actual = $datosCama[$w]['sal_id'];
				$grupo_id_actual = $datosCama[$i]['tipo_sala_grupo_id'];
				$respCantipo = $objMapaPiso->cantidadTipoCamas($objCon, $datosCama[$i]['sal_id']);
				if ( $sal_id_anterior == $sal_id_actual ) {
					if ( $datosCama[$i]['sal_pertenece_grupo'] == 'S' ) {
						$preCama = $datosCama[$i]['sal_nombre_mostrar'];
					} else {
						$preCama = '';
					}
					 cargarCama($objCon, $datosCama[$i], $draggableMP, $classMP, $datosCama[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);
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
							$styleRow = 'style="padding-right : 6px;padding-left : 6px;"';
							if ( $datosCama[$i]['sal_doble_columna'] == 'S' ) {
								if ( $cantColumnas > 2 ) {
									$panel = '-2';
									$styleRow = 'style="padding-right :18px;padding-left : 18px;"';
								} else {
								}
								$nomPanel = strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']);
							} else {
								if ( strlen($datosCama[$i]['tipo_sala_grupo_descripcion']) > 5 ) {
									$nomPanel = substr(strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";
								} else {
									$nomPanel = strtoupper($datosCama[$i]['tipo_sala_grupo_descripcion']);
								}
							}
							?>
							<div class=" col-lg<?=$panel;?> border-right border-left"  <?=$styleRow?> >
								<div class="panel-mp panel-info text-center panel-custom">
									<div class="panel-body panel-body-custom mifuente10" >
										<p><b><?=$nomPanel;?></b></p>
				    	<?php }
						$pReg = false;
					}
					if ( $datosCama[$i]['sal_pertenece_grupo'] == 'S' ) {
						$preCama = $datosCama[$i]['sal_nombre_mostrar'];
					} else {
						$preCama = '';
					}
					 cargarCama($objCon, $datosCama[$i], $draggableMP, $classMP, $datosCama[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);
				}
				$sal_id_anterior = $datosCama[$i]['sal_id'];
				$grupo_id_anterior = $datosCama[$i]['tipo_sala_grupo_id'];
				if ( $ultimaCama['cam_id'] == $datosCama[$i]['cam_id'] ) {
					echo '</div></div></div>';
				}
			}?>
		</div>
		</div> 
	</div> -->
<!-- <div id="mapapiso_pediatrico" class="col-lg-2 mt-2  pac-list pt-3" style="background-color: #f3edc887; top: -16px !important;" >
	<div class="col-md-12"  style="text-align:center; margin-top: -7px;">
		<label for="titulo_mapa_adulto"  class="control-label mifuente14">
       		<strong>ATENCIÓN PEDIÁTRICO <br/></strong>
		</label>
	</div>
	<div class="container-xl">
		<div class="row">
		<?php
		$sal_id_anterior 	= '';
		$sal_id_actual 		= '';
		$pReg 				= '';
		$grupo_id_actual 	= '';
		$grupo_id_anterior 	= '';
		$ultimaCama 		= end($datosCamaPedi);
		for ( $i = 0; $i < count($datosCamaPedi); $i++ ) {
			$sal_id_actual 		= $datosCamaPedi[$i]['sal_id'];
			$grupo_id_actual 	= $datosCamaPedi[$i]['tipo_sala_grupo_id'];
			$respCantipo 		= $objMapaPiso->cantidadTipoCamas($objCon, $datosCamaPedi[$i]['sal_id']);
			if ( $sal_id_anterior == $sal_id_actual ) {
				if ( $datosCamaPedi[$i]['sal_pertenece_grupo'] == 'S' ) {
					$preCama = $datosCamaPedi[$i]['sal_nombre_mostrar'];
				} else {
					$preCama = '';
				}
				cargarCama($objCon, $datosCamaPedi[$i], $draggableMP, $classMP, $datosCamaPedi[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);
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
						$styleRow = 'style="padding-right : 6px;padding-left : 6px;"';
						if ( $datosCamaPedi[$i]['sal_doble_columna'] == 'S' ) {
							if ( $cantColumnas > 2 ) {
								$panel = '-5';
							} else {
								$panel = '-5';

						$styleRow = 'style="padding-right : 16px;padding-left : 16px;"';
							}
							$nomPanel = strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']);
						} else {
							$panel = '-3';
							if ( strlen($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']) > 5 ) {
								$nomPanel = substr(strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']), 0, 5).".";
							} else {
	            				$nomPanel = strtoupper($datosCamaPedi[$i]['tipo_sala_grupo_descripcion']);
							}
						}
						?>
						<div class=" col-lg<?=$panel;?> border-right border-left"  <?=$styleRow?> >
									<div class="panel-mp panel-info text-center panel-custom">
										<div class="panel-body panel-body-custom mifuente10" >
											<p><b><?=$nomPanel;?></b></p>
			        <?php }
			        $pReg = false;
			    }
				if ( $datosCamaPedi[$i]['sal_pertenece_grupo'] == 'S' ) {
					$preCama = $datosCamaPedi[$i]['sal_nombre_mostrar'];
				} else {
					$preCama = '';
				}
				cargarCama($objCon, $datosCamaPedi[$i], $draggableMP, $classMP, $datosCamaPedi[$i]['tipo_cama_sigla'], $preCama, $respCantipo[0]['cantidad']);
			}
			$sal_id_anterior = $datosCamaPedi[$i]['sal_id'];
			$grupo_id_anterior = $datosCamaPedi[$i]['tipo_sala_grupo_id'];
			if ( $ultimaCama['cam_id'] == $datosCamaPedi[$i]['cam_id'] ) {
				echo '</div></div></div>';
			}
		}
		?>
		</div> 
	</div>
</div> -->
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

<!--
################################################################################################################################################
                                                       			CAMPOS OCULTOS
-->
<?php
if ( isset($_SESSION['usuarioActivo']['usuario']) ) {

	echo '<input type="hidden" id="usuarioLogueado" value="'.$_SESSION['usuarioActivo']['nombre'].'" />';

} else {
	if ( isset($_SESSION['MM_Username'.SessionName]) ) {

		echo '<input type="hidden" id="usuarioLogueado" value="'.$_SESSION['MM_UsernameName'.SessionName].'" />';

	}

}
?>



<!--
################################################################################################################################################
                                                       			FUNCIONES PHP
-->
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



function iconoPaciente ( $sexo ) {

	if ( $sexo == 'M' || $sexo == '1' ) {

		return '<img class="imagenPaciente shadow-sm" style="position: relative; width: 10px; width: 21px;" src="'.PATH.'/assets/img/pacienteM2.png">';

	} else if ( $sexo == 'F' || $sexo == '0' ) {

		return '<img class="imagenPaciente shadow-sm" style="position: relative; width: 21px; width: 21px;" src="'.PATH.'/assets/img/pacienteF.png">';

	} else {

		return '<img class="imagenPaciente shadow-sm" style="position: relative; width: 21px; width: 21px;" src="'.PATH.'/assets/img/indefinido.png">';

	}

}



function iconoPlayInicioAtencion ( $dau_indicacion_egreso_fecha, $dau_inicio_atencion_fecha, $ind_egr_id, $dau_id ) {

	if ( !isset($dau_indicacion_egreso_fecha) ) {

		if ( $dau_inicio_atencion_fecha ) {

			// return '<span class="iconInicioAte"><svg class="svg-inline--fa fa-play fa-w-14 faa-flash" "="" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="play" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"></path></svg><!-- <i class="fas fa-play faa-flash " "=""></i> --></span>';
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

		return '<span class="text-upleft-custom"><i class="fas fa-info-circle faa-flash throb " aria-hidden="true"></i></span>';

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



function tiempoEsperaDesdeCategorizacion ( $dauId, $inicioAtencion, $tiempoCategorizacion,  $tipoCategorizacion, $tiempoAlerta ) {

	if ( noExisteInicioAtencion($inicioAtencion) && pacienteAunNoCategorizado($tiempoCategorizacion) ) {

		$segundos 				= 60;

		$tiempoActual 			= strtotime(date('Y-m-d H:i:s'));

		$tiempoCategorizacion 	= strtotime($tiempoCategorizacion);

		$tiempoActual 			= $tiempoActual - $tiempoCategorizacion;

		$tiempoAlerta 			= $tiempoAlerta * $segundos;

		if ( tiempoAlertaCumplido($tiempoActual, $tiempoAlerta) && tipoCategorizacionSuperfluo($tipoCategorizacion) ) {

			return '<span id="relojEsperaCategorizacion_'.$dauId.'" class="text-upleft-custom"><i class="far fa-clock throb text-danger"></i></span>';

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

	if ( $examenCovid['estadoFormulario'] == 3 ) {

		$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:#ff7878; font-size:11px;"';

	}

	if ( $examenCovid['estadoFormulario'] == 4 ) {

		$style = 'style="margin-left:-3px; border: 2px solid white; border-radius: 5px; color:#3db73d; font-size:11px;"';

	}

	return '<span class="text-upright-custom"><i class="icon-cog fa fa-circle"'.$style.' aria-hidden="true"></i></span>';


}



function insertarCama($objCon, $datosCama, $css_ti_aapli, $col, $idHtmlCama, $draggableMP, $classMP, $tipoCam, $preCama, $cantidadCamas){

	$cat    =  categorizacion($datosCama['cat_nombre_mostrar'], $datosCama['cat_nivel']);

	$play   =  iconoPlayInicioAtencion($datosCama['dau_indicacion_egreso_fecha'], $datosCama['dau_inicio_atencion_fecha'], $datosCama['ind_egr_id'], $datosCama['dau_id']);

	$ind    =  iconoIndicacion($datosCama['dau_id'], $datosCama['dau_indicaciones_solicitadas'], $datosCama['dau_indicaciones_realizadas']);

	$icoPac =  iconoPaciente($datosCama['sexo']);

	$reloj  =  tiempoEsperaDesdeCategorizacion($datosCama['dau_id'], $datosCama['dau_inicio_atencion_fecha'], $datosCama['dau_categorizacion_actual_fecha'], $datosCama['cat_nombre_mostrar'], $datosCama['cat_tiempo_alerta']);

	$covid  =  examenCovid($objCon, $datosCama['id_paciente']);

	// $css = "width:100% !important";

	// $class = "text-upleft";

	// if ( $col == 6 ) {

	// 	$style = 'style="width:45%;"';

	// }

	// if ( $col == 3 ) {

	// 	$style = 'style="width:28%;"';

	// }
//  $cama = '<div class="card text-center">
//   <div class="card-header">
//     '.$datosCama['nombres']." ".$datosCama['apellidopat']." ".$datosCama['apellidomat'].'
//   </div>
//   <div class="card-body">
//     <h5 class="card-title">Special title treatment</h5>
//     <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
//     <a href="#" class="btn btn-primary">Go somewhere</a>
//   </div>
//   <div  class="card-footer text-muted contadorTEspCamas">
    
//   </div>
// </div>';
	$cama .= '<div style="width:40px !important"  >

			<div id="'.$idHtmlCama.'_'.$datosCama['cam_descripcion'].'" draggable="'.$draggableMP.'" class="well-camas-'.$css_ti_aapli.' '.$classMP.' verInfoPac center-block tooltip-mp col-cam" style="cursor: pointer; width:100% !important ;min-height:40px;"> ' ;

	if($datosCama['est_id']!= '10'){

		$cama .=	'<div hidden id="contTECAM_'.$datosCama['dau_id'].'" class="contadorTEspCamas"></div>
					<input class="css_border" type="hidden" value="'.$css_ti_aapli.'"/>
					<!-- ################################################################################################################ -->
					
					<!-- N DAU -->
					<input class="hidden" type="hidden" value="'.$datosCama['dau_id'].'"/>
					<!-- Categorización -->
					<input type="hidden" id="categorizacionActualHidden" value="'.$datosCama['cat_nombre_mostrar'].'" />
					<!-- ################################################################################################################ -->';

		$cama .= $cat.$play.$ind.$icoPac.$reloj.$covid;

	} else {

		$cama .= '<input type="hidden" id="'.strtotime($datosCama['cam_fecha_desocupada']).'" class="tiempoCamaDesocupadaHidden" value="'.$datosCama['cam_fecha_desocupada'].'" />';

	}

	$cama .= '<input class="cama_id" type="hidden" id="'.$datosCama['cam_id'].'" class="numeroCamaHidden" value="'.$datosCama['cam_id'].'" />
			  <input class="sala_id" type="hidden" value="'.$datosCama['sal_id'].'" />
			  <input class="salaTipo" type="hidden" value="'.$datosCama['sal_tipo'].'" />
			  <input class="dau_id" type="hidden" value="'.$datosCama['dau_id'].'"/>
			  <input class="cama_descripcion" type="hidden" value="'.$idHtmlCama.'_'.$datosCama['cam_descripcion'].'" />
			  <input class="nombre_paciente" type="hidden" value="'.$datosCama['nombres']." ".$datosCama['apellidopat']." ".$datosCama['apellidomat'].'" />
			  <input class="fecha_categorizacion" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($datosCama['dau_categorizacion_actual_fecha'])).'" />
			  <input class="nombre_categorizacion" type="hidden" value="'.$datosCama['cat_nombre_mostrar'].'" />
			  <input class="descripcion_consulta" type="hidden" value="'.$datosCama['mot_descripcion'].' '.$datosCama['sub_mot_descripcion'].' '.$datosCama['dau_motivo_descripcion'].'" />
			  <input class="ingreso_sala" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($datosCama['dau_ingreso_sala_fecha'])).'" />
			  <input class="fecha_atencion" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($datosCama['dau_inicio_atencion_fecha'])).'" />
			  <input class="fecha_atencionDinamica" type="hidden" value="'.strtotime($datosCama['dau_inicio_atencion_fecha']).'" />
			  <input class="fecha_egreso" type="hidden" value="'.Date("d-m-Y H:i:s", strtotime($datosCama['dau_indicacion_egreso_fecha'])).'" />
			  <input class="fecha_egresoDinamica" type="hidden" value="'.strtotime($datosCama['dau_indicacion_egreso_fecha']).'" />
			  <input class="motivo_egreso" type="hidden" value="'.$datosCama['ind_egr_descripcion'].'" />
			  <input class="tipoPaciente" type="hidden" value="'.$datosCama['dau_atencion'].'" />
			  <input class="servicioHospitalizacion" type="hidden" value="'.$datosCama['servicio'].'" />
			  <input class="atencionIniciadaPor" type="hidden" value="'.$datosCama['atencionIniciadaPor'].'" />
			  <input class="edadPaciente" type="hidden" value="'.$GLOBALS['objUtil']->edadActualCompleto($datosCama['fechanac']).'" />
			  <input class="runPacienteExtranjero" type="hidden" value="'.$datosCama['runPacienteExtranjero'].'" />
			  <input class="runPaciente" type="hidden" value="'.$GLOBALS["objUtil"]->formatearNumero($datosCama['runPaciente']).'-'.$GLOBALS["objUtil"]->generaDigito($datosCama['runPaciente']).'" />';

	if ( existeExamenLaboratorioCancelado($objCon, $datosCama['dau_id']) ){

		$cama .= '<input class="examenLaboratorioCancelado" type="hidden" value="S" />';

	}

	if ( existeSintomasRespiratorios($datosCama['sintomasRespiratorios']) ){

		$cama .= '<input class="sintomasRespiratorios" type="hidden" value="S" />';

	}

	$cama .= '</div>';

	if ( $preCama != '' ) {

		if ( $cantidadCamas > 1 ) {

			$cama .= '<div id="" class="numCam mifuente10" >'.$preCama.' '.$tipoCam.$datosCama['cam_descripcion'].'</div>';

		} else {

			$cama .= '<div id="" class="numCam mifuente10" >'.$preCama.'</div>';

		}

	} else {

		$nombreCama = $tipoCam."-".$datosCama["cam_descripcion"];

		if (is_numeric($datosCama["cam_descripcion"]) === false || is_numeric($datosCama["cam_descripcion"]) === "") {
			$nombreCamaSeparado = str_split($datosCama["cam_descripcion"]);
			$nombreCama = $nombreCamaSeparado[0]." - ".$nombreCamaSeparado[1];
		}

		if ($datosCama["cam_descripcion"] === "Aisl") {
			$nombreCama = $datosCama["cam_descripcion"];
		}

		$cama .= '<div id="" class="numCam mifuente10" >'.$preCama.' '.$nombreCama.'</div>';

	}

	$cama .='</div>';

	return $cama;

}



function cargarCama ( $objCon, $datosCama, $draggableMP, $classMP, $tipoCam, $preCama, $cantidadCamas ) {

	$insCama = '';

	$bandera = true;

	if ( $datosCama['dau_id'] == '' ) {

		$css_ti_aapli='plomo';

	} else {

		$css_ti_aapli = colorTiempo($datosCama['dau_indicacion_egreso_fecha'], $datosCama['dau_inicio_atencion_fecha'], $datosCama['dau_indicaciones_completas'], $datosCama['FechaActual']);

	}

	$idHtmlCama = $datosCama['sal_resumen'];

	if ( $cantidadCamas > 10 && $cantidadCamas != 24 && $cantidadCamas != 21) {

		$cantColumnas = $cantidadCamas / 8;

		$cantColumnas = ceil($cantColumnas);

		$cantFilas = $cantidadCamas / ceil($cantColumnas);

		$cantFilas = floor($cantFilas);

		$cantColumnas = 12 / $cantColumnas;

		$cantColumnas = ceil($cantColumnas);

		$col = $cantColumnas;

		if ( $_SESSION['contadorColumnas4'] == 4 ) {

			$insCama .= 	'<div class="row ">';

			$_SESSION['contadorColumnas4']--;

		} else {

			$_SESSION['contadorColumnas4']--;

			if ( $_SESSION['contadorColumnas4'] == 0 ) {

				$_SESSION['contadorColumnas4'] = 4;

				$bandera = false;

			}

		}

	} else if ($cantidadCamas == 32 || $cantidadCamas == 24 || $cantidadCamas == 21) {

		$cantColumnas = $cantidadCamas / 8;

		$cantColumnas = ceil($cantColumnas);

		$cantFilas = $cantidadCamas / ceil($cantColumnas);

		$cantFilas = floor($cantFilas);

		$cantColumnas = 12 / $cantColumnas;

		$cantColumnas = ceil($cantColumnas);

		$col = $cantColumnas;

		if ( $_SESSION['contadorColumnas3'] == 3 ) {

			$insCama .= 	'<div class="row ">';

			$_SESSION['contadorColumnas3']--;

		} else {

			$_SESSION['contadorColumnas3']--;

			if ( $_SESSION['contadorColumnas3'] == 0 ) {

				$_SESSION['contadorColumnas3'] = 3;

				$bandera = false;

			}

		}

	}else {

		if ( $datosCama['sal_doble_columna'] == 'S' ) {

			$col = '6';

			if ( $_SESSION['contadorColumnas'] == 2 ) {

				$insCama .= 	'<div class="row ">';

				$_SESSION['contadorColumnas']--;

			} else {

				$_SESSION['contadorColumnas']--;

				if ( $_SESSION['contadorColumnas'] == 0 ) {

					$_SESSION['contadorColumnas'] = 2;

					$bandera = false;
				}

			}

		} else {

			$col = '12';

			$insCama .= 	'<div class="">';

			$bandera = false;

		}

	}

	$insCama .=	insertarCama($objCon, $datosCama, $css_ti_aapli, $col, $idHtmlCama, $draggableMP, $classMP, $tipoCam, $preCama, $cantidadCamas);

	if ( $bandera == false ) {

		$insCama .= '</div>';

		$bandera = true;

	}

	return $insCama;

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
