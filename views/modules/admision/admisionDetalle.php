<?php
session_start();

require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); $objCon      = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       $objUtil     = new Util;
require_once('../../../class/Admision.class.php');   $objAdmision = new Admision;

$parametros['dau_id'] = $_POST['id'];
$fechaActual          = date('d-m-Y');
$horaActual           = date("G:i:s");
$datos                = $objAdmision->listarDatosDau($objCon,$parametros);

$version    = $objUtil->versionJS();
?>



<!-- 
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<!-- <script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/admision/admisionDetalle.js?v=<?=$version;?>"></script> -->



<!-- 
################################################################################################################################################
                                                            DESPLIEGUE DETALLE DE ADMISIÓN
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Detalle de Admisión</title>
	
</head>

<style media='print'>
	input{display:none;} 
</style>

<style media='screen'>
	input{display:block;} 
</style>

<body style="font-size: 10px !important;
    font-family: 'SourceSansPro-Semibold', Fallback, sans-serif;">
	<style type="text/css">
		tbody {
		    font-size: 10px !important;
		    font-family: 'SourceSansPro-Semibold', Fallback, sans-serif;
		}
		table.reporte{
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 11px;
		}

		table.reporte1{
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 12px;
		}

		td.folio {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 18px;	
		}

		table.foliochico {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 12px;	
		}

		td.reportechico {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 10px;	
		}

		.titulotabla {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: #000000;
			padding: 4px 2px;
			text-align: center;
		}
		.itemcentro {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-weight: bold;
			color: #2564C4;
			padding: 4px 2px;
			padding: 3px;
			
		}
		/*.titulos {
			font-family: Geneva, Arial, Helvetica, sans-serif;
			font-size: 16px;
			font-weight: bold;
			color: #feddbc;
			padding: 4px 4px 4px 6px;
		}*/
		.titulotabla {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: #000000;
			padding: 4px 2px;
			text-align: center;
		}
		.titulomedio {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			color: #ffffff;
			text-align: left;
			margin: 2px;
			font-weight: bold;
			padding-top: 2px;
			padding-bottom: 2px;
			background-image: url(./img/fondo_tit.jpg);
			text-align: center;
		}
		.titulopaneles {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #ffffff;
			text-align: left;
		}
		.derechosreservados {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
			color: #36345c;
			text-align: left;
		}

		input.textacceso
		{
		    border: 0px solid #000000;
		    background-color: transparent;
		}

		a.botonlink {
			background: #575757;
			color: white;
			width: 70px;
			height: 18px;
			border-style: solid;
			border-color: #7c7c7c;
			font-family: sans-serif;
			font-size: 12px;
			font-weight: bold;
		}

		a.enlace {text-decoration: none}

		select.combo {
			outline-color:#feddbc
		}

		.fila_0 { background-color: #FFFFFF;}  
		.fila_1 { background-color: #e8eaec;} 
	</style>
	<div id="contendido2">
		
		<div id="afectado" class="" align="left" style="float:none; width: 100%; padding: 0px;">
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0px" class="reporte" style="font-size: 10px !important;">
				
				<tr>
					
					<td  style="padding: 0px !important;" width="533" height="5">&nbsp;</td>
					
					<td  style="padding: 0px !important;" width="227" rowspan="5" valign="top">
						
						<table  width="100%" border="0" cellpadding="0" cellspacing="0" class="idrau" style="font-size: 10px !important;">
							
							<tr>
							
								<td  style="padding: 0px !important;" width="39" height="10" class="folio">&nbsp;</td>
							
								<td  style="padding: 0px !important;" width="188" class="folio" style="color:black!important;">FOLIO&nbsp;&nbsp;:&nbsp;<?php echo $datos[0]['dau_id']?></td>
							
							</tr>
							
							<tr>
							
								<td  style="padding: 0px !important;" height="10" class="foliochico">&nbsp;</td>
							
								<td  style="padding: 0px !important;" height="10" class="folio" style="color:black!important;">CTACTE:&nbsp;<?php echo $datos[0]['idctacte'] ?></td>
							
							</tr>
							
							<tr>
							
								<td  style="padding: 0px !important;" height="10" class="foliochico">&nbsp;</td>
							
								<td  style="padding: 0px !important;" height="10" class="foliochico">FECHA:&nbsp;<?php echo date("d-m-Y", strtotime(str_replace("/", "-", $datos[0]['dau_admision_fecha']))).' '.date("G:i:s", strtotime(str_replace("/", "-", $datos[0]['dau_admision_fecha']))); ?></td>
							
							</tr>
							
							<tr>
							
								<td  style="padding: 0px !important;" height="10" class="foliochico">&nbsp;</td>
							
								<td  style="padding: 0px !important;" height="10" class="foliochico">USUARIO:&nbsp;<?php echo $datos[0]['dau_admision_usuario'] ?></td>
							
							</tr>
						
						</table>
					
					</td>
				
				</tr>

				<tr>
					
					<td  style="padding: 0px !important;" height="20" align="center"></td>
				
				</tr>

				<tr>
				
					<td  style="padding: 0px !important;" height="20">&nbsp;</td>
				
				</tr>

				<tr>
				
					<td  style="padding: 0px !important;" height="20">&nbsp;</td>
				
				</tr>

				<tr>
				
					<td>
				
						<table  width="100%" border="0" style="letter-spacing: 1px;" cellpadding="3" cellspacing="0">
				
							<tr>
				
								<td  style="padding: 0px !important;" width="27%" valign="top"><strong>PACIENTE</strong></td>
				
								<td  style="padding: 0px !important;" width="2%" valign="top">:</td>
				
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
				
									<?php
									if ( $datos[0]['extranjero'] == "S" && $datos[0]['rut'] != "0" && $datos[0]['rut_extranjero'] != "" ) {

										 echo $datos[0]['rut'].'-'.$objUtil->generaDigito($datos[0]['rut']).',  '.$datos[0]['nombres'].' '.$datos[0]['apellidopat'].' '.$datos[0]['apellidomat'];
									
									} else {

										if ( ($datos[0]['rut'] || $datos[0]['rut'] == 0) && $datos[0]['extranjero'] != "S" ) {
											  
											echo $datos[0]['rut'].'-'.$objUtil->generaDigito($datos[0]['rut']).',  '.$datos[0]['nombres'].' '.$datos[0]['apellidopat'].' '.$datos[0]['apellidomat'];
										
										} else {

											echo $datos[0]['rut_extranjero'].',  '.$datos[0]['nombres'].' '.$datos[0]['apellidopat'].' '.$datos[0]['apellidomat'];
										
										}
									
									}
									?>
								
								</td>
							
							</tr>

							<tr>
							
								<td  style="padding: 0px !important;" valign="top"><strong>F. NACIMIENTO</strong></td>
							
								<td  style="padding: 0px !important;" valign="top">:</td>
							
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
									
									<?php
									echo date("d-m-Y", strtotime($datos[0]['fechanac']));
									?>

									
									<strong>&nbsp;&nbsp;&nbsp;EDAD</strong>&nbsp;&nbsp;: <?php echo $objUtil->edad_paciente($datos[0]['fechanac']) ?>&nbsp;&nbsp;&nbsp;
									
									<strong>SEXO</strong> : 
									
									<?php
									if ( $datos[0]['sexo'] == "M" ) {
										
										echo "MASCULINO";
									
									} else {

										if ( $datos[0]['sexo'] == "F" ) {
											
											echo "FEMENINO";
										
										}

										if ( $datos[0]['sexo'] == "O" ) {
											
											echo "INDETERMINADO";
										
										}

										if ( $datos[0]['sexo'] == "D" ) {
											
											echo "DESCONOCIDO";
										
										}

									} 
									?>
								
								</td>
								
							</tr>

								
							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>CALLE</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" align="left" valign="top">
								
									<?php								
									if ( ! is_null($datos[0]['calle']) || ! empty($datos[0]['calle']) ) {

										echo $datos[0]['calle'];
									
									} else {

										echo "No Definido";
									
									}
									?>
								
								</td>
								
								<td  style="padding: 0px !important;" colspan="2" valign="top"><strong>NÚMERO</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" align="left" valign="top">
								
									<?php 
									if ( ! is_null($datos[0]['numero']) || ! empty($datos[0]['numero']) ) {

										echo $datos[0]['numero'];
									
									} else {

										echo "No Definido";
									
									}
									?>
								
								</td>

							</tr>


							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>RESTO DIRECCIÓN</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
								
									<?php								
									if ( ! is_null($datos[0]['restodedireccion']) && !empty($datos[0]['restodedireccion']) ) { 
										
										echo $datos[0]['restodedireccion'];
									
									} else {

										echo $datos[0]['dau_paciente_domicilio'];
									
									}
									?>

								</td>
							
							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>SECTOR DOMICILIO</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" align="left" valign="top">
								
									<?php 
									if ( ! is_null($datos[0]['sector_domicilio']) && ! empty($datos[0]['sector_domicilio']) ) {

										echo $datos[0]['descripcion_sector_domiciliario'];
									
									} else {

										echo "No Definido";
									
									}
									?>
								
								</td>

								<td  style="padding: 0px !important;" colspan="7" valign="top"><strong>TIPO DOMICILIO</strong>: 
								
									<?php 
									if ( ! is_null($datos[0]['dau_paciente_domicilio_tipo']) && ! empty($datos[0]['dau_paciente_domicilio_tipo']) ) { 
										
										if ( $datos[0]['dau_paciente_domicilio_tipo'] == "R" ) {
											
											echo "RURAL";
										
										} else if ( $datos[0]['dau_paciente_domicilio_tipo'] == "U" ) {
											
											echo "URBANO";
										
										}
									
									} else {

										echo "No Definido";
									
									}
									?>
								
								</td>
							
							</tr>

							<tr>
							
								<td  style="padding: 0px !important;" valign="top"><strong>TELÉFONOS</strong></td>
							
								<td  style="padding: 0px !important;" valign="top">:</td>
							
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
							
									<?php if($datos[0]['PACfono']==0){ echo "Fijo No Definido";}else{ echo $datos[0]['PACfono']; }  ?>,
							
									<?php if($datos[0]['fono1']==0){echo "Celular No Definido";}else{echo $datos[0]['fono1'];}   ?>
							
								</td>

							</tr>
							
							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>PREVISIÓN</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" width="27%" align="left" valign="top"><b><?php echo $datos[0]['prevision'];?></b></td>
								
								<td  style="padding: 0px !important;" colspan="7" valign="top"><strong>ETNIA</strong> : <?php if($datos[0]['etn_descripcion']!=""){ echo $datos[0]['etn_descripcion'];}else{ echo "No definido"; }?></td>

							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>FORMA DE PAGO</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><b><?php echo $datos[0]['instNombre']; ?></b></td>
							
							</tr>

							<tr>
							
								<td  style="padding: 0px !important;" valign="top"><strong>TIPO DE ATENCIÓN</strong></td>
							
								<td  style="padding: 0px !important;" valign="top">:</td>
							
								<td  style="padding: 0px !important;" align="left" valign="top"><?php echo $datos[0]['ate_descripcion'];?></td>
							
							</tr>
							
							<tr>
							
								<td  style="padding: 0px !important;" valign="top"><strong>AFRODESCENDIENTE</strong></td>
							
								<td  style="padding: 0px !important;" valign="top">:</td>
							
								<td  style="padding: 0px !important;" align="left" valign="top">
								
									<?php							
									if ( ! is_null($datos[0]['PACafro']) || ! empty($datos[0]['PACafro']) ) {

										if ( $datos[0]['PACafro'] == 0 ) {

											echo "NO";
										
										} else {

											echo "SI";
										
										}
									
									} else {
											
										echo "No Definido";
									
									}
									?>
								
								</td>
							
							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>PAÍS NACIMIENTO</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
								
									<?php 
									if ( ! is_null($datos[0]['NACpais']) || ! empty($datos[0]['NACpais']) ) {

										echo $datos[0]['NACpais'];
									
									} else {

										echo "No Definido";
									
									}
									?>
									
									<strong>NACIONALIDAD</strong>: 
									
									<?php 
									if ( ! is_null($datos[0]['NACdescripcion']) || ! empty($datos[0]['NACdescripcion']) ) { 
										
										echo $datos[0]['NACdescripcion'];
									
									} else {

										echo "No Definido";
									
									}
									?>
								
								</td>

							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>REGIÓN</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top">
								
									<?php 
									if ( ! is_null($datos[0]['REG_Descripcion']) || ! empty($datos[0]['REG_Descripcion']) ) {

										echo $datos[0]['REG_Descripcion'];
									
									} else {

										echo "No Definido";
									
									}
									?>
									
									<strong>CIUDAD</strong>: 
									
									<?php 
									if ( ! is_null($datos[0]['CIU_Descripcion']) || ! empty($datos[0]['CIU_Descripcion']) ) {

										echo $datos[0]['CIU_Descripcion'];
									
									} else {

										echo "No Definido";
									
									}
									?>

									<strong>COMUNA</strong>: 
									
									<?php 
									if ( ! is_null($datos[0]['comuna']) || ! empty($datos[0]['comuna']) ) {

										echo $datos[0]['comuna'];
									
									} else {

										echo "No Definido";
									
									}
									?>

								</td>

							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>CONSULTORIO</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><?php if($datos[0]['con_descripcion']!=""){echo $datos[0]['con_descripcion']; }else{ echo "No Definido";}?></td>
							
							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>LLEGA EN</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="2" align="left" valign="top"><?php echo $datos[0]['med_descripcion']; ?></td>
								
								<td  style="padding: 0px !important;" width="34%" colspan="5" valign="top">&nbsp;</td>
							
							</tr>

							<tr>
								
								<td  style="padding: 0px !important;" valign="top"><strong>MOTIVO CONSULTA</strong></td>
								
								<td  style="padding: 0px !important;" valign="top">:</td>
								
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><?php echo $datos[0]['mot_descripcion'].'<br>'.$datos[0]['dau_motivo_descripcion']?></td>
							
							</tr>
							
							<?php
							if ( $datos[0]['mor_descripcion'] != '' ) {
							?>
								
								<tr>
								
									<td  style="padding: 0px !important;" valign="top"><strong>MORDEDURA</strong></td>
								
									<td  style="padding: 0px !important;" valign="top">:</td>
								
									<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><?php echo $datos[0]['mor_descripcion'];?></td>
								
								</tr>	
							
							<?php
							}

							if ( $datos[0]['int_descripcion'] != '' ) {
							?>
								
								<tr>	
								
								
									<td  style="padding: 0px !important;" valign="top"><strong>INTOXICACIÓN</strong></td>
								
									<td  style="padding: 0px !important;" valign="top">:</td>
								
									<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><?php echo $datos[0]['int_descripcion'];?></td>
								
								</tr>
							
							<?php	
							}

							if ( $datos[0]['que_descripcion'] != '' ) {
							?>
								
								<tr>
								
									<td  style="padding: 0px !important;" valign="top"><strong>INTOXICACIÓN</strong></td>
								
									<td  style="padding: 0px !important;" valign="top">:</td>
								
									<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"><?php echo $datos[0]['que_descripcion'];?></td>
								
								</tr>
							
							<?php
							}
							?>
							
							<tr>
							
								<td  style="padding: 0px !important;" valign="top">&nbsp;</td>
							
								<td  style="padding: 0px !important;" valign="top">&nbsp;</td>
							
								<td  style="padding: 0px !important;" colspan="7" align="left" valign="top"></td>
							
							</tr>

							<tr>
							
								<td  style="padding: 0px !important;" colspan="9" valign="top"></td>
							
							</tr>

							<tr>
							
								<td>&nbsp;</td>
							
								<td>&nbsp;</td>
							
								<td  style="padding: 0px !important;" align="left">&nbsp;</td>
							
								<td  style="padding: 0px !important;" width="10%">&nbsp;</td>
							
								<td  style="padding: 0px !important;" colspan="5">&nbsp;</td>
							
							</tr>
						
						</table>
					
					</td>
				
				</tr>
			
			</table>

			<p>&nbsp;</p>

		</div>

	</div>

</body>

</html>