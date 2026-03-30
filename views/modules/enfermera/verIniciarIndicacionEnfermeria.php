<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 								$objCon      								= new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       								$objUtil     								= new Util;
require_once('../../../class/HojaEnfermeria.class.php');     						$objHoja_enfermeria     					= new Hoja_enfermeria;
require_once('../../../class/Trazabilidad_indicaciones_enfermeria.class.php');   $ObjTrazabilidad_indicaciones_enfermeria    = new Trazabilidad_indicaciones_enfermeria;

$parametros             		= $objUtil->getFormulario($_POST);

$indicacion_id 				   = $parametros['indicacion_id'];
$rsIndicaciones_enfermeria     = $objHoja_enfermeria->SelectIndicaciones_enfermeria($objCon, $parametros);
$rsTrazabilidad_enfermeria     = $ObjTrazabilidad_indicaciones_enfermeria->SelectByIndicacion($objCon, $indicacion_id);
// print('<pre>'); print_r($rsTrazabilidad_enfermeria); print('</pre>');
?>
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/modal_detalle_indicacion.js?v=<?=$version;?>"></script>
<input type="hidden" id="dau_id" 		 name="dau_id" 			value="<?=$dau_id?>">
<input type="hidden" id="solicitud_id" 	 name="solicitud_id" 	value="<?=$parametros['solicitud_id']?>">
<input type="hidden" id="tipo_solicitud" name="tipo_solicitud" 	value="<?=$parametros['tipo_id'];?>">

<div class="bd-callout bd-callout-warning ">
    <div class="row pr-2 pl-2">
        <div class="col-lg-2 ">
            <p class="m-0 p-0 mifuente">Tipo</p>
        </div>
        <div class="col-lg-4 ">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$rsIndicaciones_enfermeria[0]['descripcion_tipo']?></label></p>
        </div>
        <div class="col-lg-2 ">
            <p class="m-0 p-0 mifuente">Estado</p>
        </div>
        <div class="col-lg-4 ">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?= htmlspecialchars($rsIndicaciones_enfermeria[0]['descripcion_estado']) ?></label></p>
        </div>
        <div class="col-lg-2 mt-2">
            <p class="m-0 p-0 mifuente">Examen</p>
        </div>
        <div class="col-lg-10 mt-2">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?= htmlspecialchars($rsIndicaciones_enfermeria[0]['nombre_procedimiento']) ?> (<?= htmlspecialchars($rsIndicaciones_enfermeria[0]['nombre_subProcedimiento']) ?>)</label></p>
        </div>
        <div class="col-lg-2 mt-2">
            <p class="m-0 p-0 mifuente">Observación</p>
        </div>
        <div class="col-lg-4 mt-2">
            <p class="m-0 p-0 mifuente">:<label  class="ml-2 texto-valor mb-0 " ><?=$rsIndicaciones_enfermeria[0]['comentario']?></label></p>
        </div>

    </div>

    <div id="contenidoTrazabilidad"><!-- <hr> -->
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
				<?php foreach ($rsTrazabilidad_enfermeria as $traza) { ?>
					<tr class="">
						<td class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="20%"><?=$traza['fecha']?> <?=$traza['hora']?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="30%"><?=$traza['movimiento']?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="15%"><b><?=$traza['descripcion_estado']?></b></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="20%"><?=$traza['usuario']?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente12 text-center " width="15%"><?=$traza['observacion']?></td>
					</tr>

				<?php } ?>
				</tbody>
		</table>
	</div>
</div>