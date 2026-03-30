<?php


require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');     $objCon     = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php');           $objUtil    = new Util;
require_once('../../../../class/Reportes.class.php');       $reporte    = new Reportes;


$parametros               = $objUtil->getFormulario($_POST);
$parametros['frm_inicio'] = $objUtil->fechaInvertida($parametros['fechaInicio']);
$parametros['frm_fin']    = $objUtil->fechaInvertida($parametros['fechaFin']);
$fechaHoy 				  = $objUtil->getFechaPalabra(date('Y-m-d'));		
$datos    				  = $reporte->registroAlcoholemia($objCon,$parametros);
?>
<style>
/* Estilos solo para impresión */
@media print {

}
</style>
<?php if(count($datos)>0){?>
<div id="contendidoAlcoholemia" class="modal-content overflow-auto">
<table width="100%" border="0">
	<tr>
		<td border="0" width="160">
			
			<img src="<?=PATH?>/assets/img/logo.png" width="75" height="75" />
			<img src="<?=PATH?>/assets/img/nuestroHospital.png" width="75" height="75" />
		</td>
		<td  border="0" valign="top" class="mifuente">			
			<table>
				<tr>
					<td class="mifuente12" >GOBIERNO DE CHILE</td>
				</tr>

				<tr>
					<td class="mifuente12" >MINISTERIO DE SALUD</td>
				</tr>

				<tr>
					<td class="mifuente12" >HOSPITAL DR. JUAN NOÉ CREVANI</td>
				</tr>

				<tr>
					<td class="mifuente12" >RUT: 61.606.000-7</td>
				</tr>

				<tr>
					<td class="mifuente12" >18 DE SEPTIEMBRE N°1000</td>
				</tr>
			</table>			
		</td>

		<td>
			<table id="fechaTabla" td width="50%" align="right" border="0" style="margin-top: -9%;">			
				<tr>
					<td style="text-align: right;"><?=$fechaHoy?></td>
					
				</tr>

				<tr>

				</tr>
			</table>
		</td>
	</tr>
</table>

<table border="0" align="center">
	<tr>
		<td align="center">
			<strong style="font-size:10; color: ">REGISTRO DE ALCOHOLEMIA <?=$_POST['fechaInicio']?> AL <?=$_POST['fechaFin']?></strong>
		</td>
	</tr>	    
</table>
<div id="scroll" style="overflow-y: scroll; width: 100%; height: 650px;  ">
<table border="1">	    	
	<thead>
		<tr class="encabezado">
			<th width="5%"  bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >DAU</th>
			<th width="25%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Paciente</th>									
			<th width="12%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Rut</th>
			<th width="12%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Fecha/Hora</th>										
			<th width="12%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Est. Etílico</th>
			<th width="12%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Médico</th>
			<th width="7%"  bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Frasco</th>
			<th width="12%" bgcolor="#CCCCCC" style="text-align:center;" class=" font-weight-bold  mifuente11" >Observación</th>					
		</tr>
	</thead>
	<tbody id="">

	<?php
	for ($i=0; $i<count($datos); $i++) { ?>
		<?php
			if(($datos[$i]['rut'] || $datos[$i]['rut']==0) && $datos[$i]['extranjero']!="S"){
				$RUT = $datos[$i]['rut'].'-'.$objUtil->generaDigito($datos[$i]['rut']);
			}else{
				$RUT = $datos[$i]['rut_extranjero'];
			}

			if($datos[$i]['dau_alcoholemia_apreciacion']==""){
				$observacion = "Sin Observación";
			}else{
				$observacion = $datos[$i]['dau_alcoholemia_apreciacion'];
			}

			$transexual_bd   		  = $datos[$i]["transexual"];
			$nombreSocial_bd 		  = $datos[$i]["nombreSocial"];
			$nombrePaciente	      	  = $datos[$i]['nombres'].' '.$datos[$i]['apellidopat'].' '.$datos[$i]['apellidomat'];
			$infoNombre    		  	  = $objUtil->infoNombreDoc($transexual_bd,$nombreSocial_bd,$nombrePaciente);
		?>

		<tr align="left" valign="top">
		<td width="5%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center"><?=$datos[$i]['dau_id']?></td>
		<td width="25%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >&nbsp;<?=$infoNombre?></td>
			<td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=$RUT?></td>
			<td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=date("d-m-Y H:i:s", strtotime($datos[$i]['fechaHora']))?></td>
			<td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=$datos[$i]['eti_descripcion']?></td>
			<td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=$datos[$i]['PROdescripcion']?></td>
			<td width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=$datos[$i]['dau_alcoholemia_numero_frasco']?></td>
			<td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" ><?=$observacion?></td>
		</tr>
	<?php } ?>

		<tr align="left" valign="top">
			<td colspan="8" align="right" bgcolor="#CCCCCC"><strong>TOTAL : <?=$i?>&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
		</tr>	
		</tbody>
	</table>
	</div>
</div>
<?php }else{?>

<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4">
		<div class="alert alert-info">
		<center>
			<?php $result = count($datos); ?>
			<input type="hidden" name="result" id="result" value="<?=$result?>">
			No se encontraron resultados.			
		</center>
		</div>
	</div>
	<div class="col-md-4"></div>
</div>
	
<?php } ?>
