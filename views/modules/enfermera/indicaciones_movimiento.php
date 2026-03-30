<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 	$objCon      		= new Connection;
require_once('../../../class/Imagenologia.class.php'); 	$objRayos  			= new Imagenologia;
require_once('../../../class/RegistroClinico.class.php');$objRegistroClinico = new RegistroClinico;
require_once("../../../class/Movimiento.class.php"); 	$objMovimiento 		= new Movimiento;
require_once('../../../class/Laboratorio.class.php'); 	$objLaboratorio 	= new Laboratorio;
require_once('../../../class/Util.class.php'); 			$objUtil 			= new Util;

$objCon->db_connect();
$parametros						= $objUtil->getFormulario($_POST);
$parametros['arreglo_ind']		= explode('-',$parametros['indicacion_id']);
$parametros['indicacion_id']	= $parametros['arreglo_ind'][0];
$parametros['tipo_id']			= $parametros['arreglo_ind'][1];
$parametros['sic_id']			= $parametros['arreglo_ind'][2];

if($parametros['tipo_solicitud'] == 1){
	$DatosRceIma 				= $objRayos->datosDauRce($objCon,$parametros);
	$parametros['rce_id']		= $DatosRceIma[0]['regId'];
	$resIndicaciones			= $objRayos->movimientosIndicaciones($objCon,$parametros);
}else if($parametros['tipo_solicitud'] == 3){
	$listarIndicaciones 						= $objLaboratorio -> listarIndicacionesLaboratorio($objCon,$parametros);
	$parametrosExamen['regId'] 					= $listarIndicaciones[0]['regId'];
	$parametrosExamen['tubo_id'] 				= $listarIndicaciones[0]['tubo_id'];
	$parametrosExamen['sol_lab_fechaInserta'] 	= $listarIndicaciones[0]['sol_lab_fechaInserta'];

	$listarIndicacionesporTubo 					= $objLaboratorio -> listarIndicacionesLaboratorioporTubo($objCon,$parametrosExamen);
	$parametrosMov['solicitud_id_list'] 		= "";
	for ($i=0; $i<count($listarIndicacionesporTubo); $i++){
		if($i>0){
			$parametrosMov['solicitud_id_list'] 	.= ",".$listarIndicacionesporTubo[$i]['sol_lab_id'];
		}else{
			$parametrosMov['solicitud_id_list'] 	.= "".$listarIndicacionesporTubo[$i]['sol_lab_id'];

		}
	}
	// $listarIndicacionesporTubo = $objLaboratorio->listarIndicacionesLaboratorioporTubo($objCon, $parametrosExamen);
	// Utiliza la función array_column para extraer 'sol_lab_id' y luego implode para crear una cadena separada por comas.
	// $parametrosMov['solicitud_id_list'] = implode(",", array_column($listarIndicacionesporTubo, 'sol_lab_id'));

	$parametrosMov['tipo_solicitud']		= 3;
	$parametrosMov['dau_id']				= $parametros['dau_id'];
	$resIndicaciones						= $objRayos->movimientosIndicaciones($objCon,$parametrosMov);
}else{
	$DatosRceTra				= $objRegistroClinico->listarIndicaciones($objCon,$parametros);
	$parametros['rce_id']		= $DatosRceTra[0]['regId'];
	$resIndicaciones			= $objRayos->movimientosIndicaciones($objCon,$parametros);
}
?>
<!-- <hr> -->
<br>
<h6>Trazabilidad de Movimientos</h6>
<table id="tabla_trazabilidad" class="table table-condensed">
	<thead>
		<tr>
			<td width="20%" class=" mifuente12 text-center">Fecha</td>
			<td width="30%" class=" mifuente12 text-center">Movimiento</td>
			<td width="15%" class=" mifuente12 text-center">Estado</td>
			<td width="20%" class=" mifuente12 text-center">Usuario</td>
			<td width="15%" class=" mifuente12 text-center">Observación</td>
		</tr>
	</thead>
	<tbody>
	<?php
	for ($i=0; $i<count($resIndicaciones); $i++){
		$classColor ="";
		if($resIndicaciones[$i]['sol_ind_est_id'] == 6 || $resIndicaciones[$i]['sol_ind_est_id'] == 8){
			$classColor ="table-danger";
		}
		if($resIndicaciones[$i]['det_lab_descripcion'] != null){
			$resIndicaciones[$i]['dau_mov_rce_accion'] = "(".$resIndicaciones[$i]['det_lab_descripcion'].") ".$resIndicaciones[$i]['dau_mov_rce_accion'];
		}
	?>
		<tr class="<?=$classColor?>">
			<?php $fecha = date("d-m-Y H:i:s",strtotime($resIndicaciones[$i]['dau_mov_rce_fecha']))?>
			<td  class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="20%"><?=$fecha?></td>
			<td  class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="30%"><?=$resIndicaciones[$i]['dau_mov_rce_accion']?></td>
			<td  class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="15%"><?=$resIndicaciones[$i]['est_descripcion']?></td>
			<td  class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="20%"><?=$resIndicaciones[$i]['dau_mov_rce_usuario']?></td>
			<td  class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="15%"><?=$resIndicaciones[$i]['dau_observacion_rce']?></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>