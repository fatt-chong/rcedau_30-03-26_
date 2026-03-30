<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
if ( array_search(834, $permisos) == null ) {

	$GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));

}

require_once('../../../class/Connection.class.php');    $objCon            = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');          $objUtil           = new Util;
require_once('../../../class/Admision.class.php');   	$objAdmision       = new Admision;

$campos 	= $objUtil->getFormulario($_POST);
if(count($campos) > 0 ){
	$datos  = $objAdmision->listarDatosBuscador($objCon,$campos);
}else{
	$datos  = $objAdmision->listarDatosBuscadorInDAU($objCon);
}
?>
<div class=" m-0 p-0 col-lg-12  mifuente12">
<table id="table_Admision" class="table display table-condensed table-hover mifuente12" width="100%">
	<thead>
		<tr class="encabezadoListAdmision">
			<th class="text-center"># DAU</th>
			<th class="text-center" >Admisión</th>
			<th class="text-center">Documento</th>
			<th class="text-center">N° Documento</th>
			<th class="text-center">Nombre</th>
			<th class="text-center">CtaCte</th>
			<th class="text-center">Estado</th>
			<th class="text-center">Motivo Consulta</th>
			<th class="text-center">Atención</th>
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
	<tbody id="contenidoTabla" class="detalleListAdmision">
		<?php
		for ( $i = 0; $i < count($datos); $i++ ) {
		?>
		<tr id="<?=$datos[$i]['dau_id']?>">
			<td class="pt-3 text-center"><?=$datos[$i]['dau_id']?></td>
			<td class="pt-3  "><span class='hide'><?php echo $datos[$i]['dau_admision_fecha']; ?></span><?php echo date("d-m-Y H:i", strtotime($datos[$i]['dau_admision_fecha'])); ?></td>
			<td class="pt-3 ">
				<?php 
				if ( $datos[$i]['extranjero'] == "S" && $datos[$i]['rut'] != "0" && $datos[$i]['rut_extranjero'] != "" ) {

					echo "RUT";
				
				} else {
					
					if ( ($datos[$i]['rut'] || $datos[$i]['rut'] == 0) && $datos[$i]['extranjero'] != "S" ) {

						echo "RUT";

					}

					if ( ($datos[$i]['rut_extranjero'] == 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 1 ) { 
					
						echo "DNI";
					
					}

					if ( ($datos[$i]['rut_extranjero'] == 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 2 ) { 
						
						echo "PASAPORTE";
					
					}

					if ( ($datos[$i]['rut_extranjero'] == 0 || $datos[$i]['rut_extranjero']) && $datos[$i]['id_doc_extranjero'] == 3 ) { 
						
						echo "OTROS";
					
					}

					if ( $datos[$i]['rut'] ==0 && ($datos[$i]['rut_extranjero'] || $datos[$i]['rut_extranjero'] == 0) && $datos[$i]['extranjero'] == "S" && ($datos[$i]['id_doc_extranjero'] == "" || $datos[$i]['id_doc_extranjero'] == 0) ) {

						echo "No definido";
					
					}
				
				}
				?>
			</td>
			<td class="pt-3">
				<?php 
				if ( $datos[$i]['extranjero'] == "S" && $datos[$i]['rut'] != "0" && $datos[$i]['rut_extranjero'] != "" ) {
				?>
				
					<?=$objUtil->formatearNumero($datos[$i]['rut']).'-'.$objUtil->generaDigito($datos[$i]['rut']);?>
				
				<?php
				} else { 
				
					if ( ($datos[$i]['rut'] || $datos[$i]['rut'] == 0) && $datos[$i]['extranjero'] != "S" ) {
						
						echo $objUtil->formatearNumero($datos[$i]['rut']).'-'.$objUtil->generaDigito($datos[$i]['rut']);
					
					}else{
						
						echo $datos[$i]['rut_extranjero'];
					
					}
				
				}
				?>

			</td>
			<td class="pt-3 text-truncate"><?=$datos[$i]['nombres'].' '.$datos[$i]['apellidopat'].' '.$datos[$i]['apellidomat']?></td>
			<td class="pt-3 "><?=$datos[$i]['idctacte']?></td>
			<td class="pt-3 "><?=$datos[$i]['est_descripcion']?></td>
			<td class="pt-3 "><?=$datos[$i]['mot_descripcion']?></td>
			<td class="pt-3 "><?=$datos[$i]['ate_descripcion']?></td>
			<td class="text-center">
				<?php
				if ( array_search(839, $permisos) != null ) {  
					
					if ( $datos[$i]['est_id'] != 5 ) {
					?>
						<button type="button" class="btn btn-primary2 btn-sm mifuente12 col-lg-12 shadow dauVerDetalleAdmision"  id="dauVerDetalleAdmision<?=$datos[$i]['dau_id']?>" ><i class="fas fa-file mifuente13 mr-2"></i>Admisión	
						</button>
						<!-- <a href="#" class="item-menu" data-toggle="tooltip" data-placement="top" title="Detalle Admision" style="text-decoration: none;">
							<img id="dauVerDetalleAdmision<?=$datos[$i]['dau_id']?>" class="dauVerDetalleAdmision" src="<?=PATH?>/assets/img/informacion.png" style="cursor: pointer;"/>
						</a> -->
					
					<?php
					}
					
				}
				
				if ( array_search(840, $permisos) != null ) { 
				?> 
					<!-- <a href="#" class="item-menu" data-toggle="tooltip" data-placement="top" title="Mas Información" style="text-decoration: none;">
						<img id="dauVerDetalle<?=$datos[$i]['dau_id']?>" class="dauVerDetalle" src="<?=PATH?>/assets/img/lupa2.png" style="cursor: pointer;"/>
					</a> -->
				
				<?php
				}
				?>
				
				<!-- <a href="#" class="item-menu" data-toggle="tooltip" data-placement="top" title="Mas Información" style="text-decoration: none;">
					<img id="dauVerDetalle_antiguo<?=$datos[$i]['dau_id']?>" class="dauVerDetalle_antiguo" src="<?=PATH?>/assets/img/lupa2_.png" style="cursor: pointer;"/>
				</a> -->
			</td>
		</tr>
		<?php
		}
		?>
	</tbody>
	 
</table>						
</div>

<script type="text/javascript">
	$(document).ready(function() {
// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\admision\admisionUpdateDetalle.php
		tablaNormal("#table_Admision");
		$(".dauVerDetalleAdmision").click(function(){

			let id = $(this).attr('id').replace('dauVerDetalleAdmision','');
			
			ajaxContent(raiz+'/views/modules/admision/admisionUpdateDetalle.php','id='+id,'#contenido','', true);
			
		});
	});
</script>