<?php
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 			$objCon      			= new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       			$objUtil     			= new Util;
require_once('../../../class/HojaEnfermeria.class.php');     	$objHoja_enfermeria     = new Hoja_enfermeria;


$parametros                 	= $objUtil->getFormulario($_POST);
$dau_id 						= $parametros['dau_id'];
$rsProcedimientosRealizados     = $objHoja_enfermeria->SelectIndicaciones_enfermeria($objCon, $parametros);
?>
<?php if( count($rsProcedimientosRealizados) > 0 ) { ?>
<div class="mr-2 ml-2">
 <B> Indicaciones Enfermeria <i class="fas fa-user-nurse mr-2 text-primary"></i></B>
	<table id="tbl_diarias" width="100%" class="table table-hover" style="font-size: 10px; margin-bottom: 0rem !important;">
<!-- 		<thead>
			<tr class="detalle">
				<td class=" mifuente12 text-center" width="30%">
					Indicación
				</td>
				<td class=" mifuente12 text-center" width="15%" >
					Tipo
				</td>
				<td class=" mifuente12 text-center" width="15%" >
					Solicitud Indicación
				</td>
				<td class=" mifuente12 text-center" width="15%" >
					Inicio Indicación
				</td>
				<td class=" mifuente12 text-center" width="15%" >
					Aplicado/Rechazado
				</td>
				<td class=" mifuente12 text-center" width="10%" >
					Accion
				</td>
			</tr>
		</thead> -->
		<tbody id="contenidoProcedimientosEnfermeria">
			<?php foreach ($rsProcedimientosRealizados as $Procedimientos) {
				$comentario = ""; 
				if( htmlspecialchars($Procedimientos['comentario']) != "" ){
					$comentario = " <i class='fas fa-bell text-danger'></i> <b class='text-danger' style='font-weight: 400;'>Observación: ".htmlspecialchars($Procedimientos['comentario'])."</b>";
				} 
				$fechaIniciado = ""; // Inicializar vacío
				$fechaAplicado = ""; // Inicializar vacío
				$fechaRechazado = ""; // Inicializar vacío
				$color 			= ""; // Inicializar vacío
				$bgcolor 		= ""; // Inicializar vacío
				$fechaApliRech 	= ""; // Inicializar vacío

				$fechaInserta  = date("d-m-Y H:i", strtotime($Procedimientos["fecha_creado"] . " " . $Procedimientos["hora_creado"]));

				if (!empty($Procedimientos["fecha_iniciado"]) && !empty($Procedimientos["hora_iniciado"])) {
				    $fechaIniciado = date("d-m-Y H:i", strtotime($Procedimientos["fecha_iniciado"] . " " . $Procedimientos["hora_iniciado"]));
				}if (!empty($Procedimientos["fecha_aplicado"]) && !empty($Procedimientos["hora_aplicado"])) {
				    $fechaApliRech = htmlspecialchars($Procedimientos['usuario_iniciado'])." - Aplicado <br>".date("d-m-Y H:i", strtotime($Procedimientos["fecha_aplicado"] . " " . $Procedimientos["hora_aplicado"]));
				    $color 		= "#1eb31e";
				    $bgcolor 	= "#E8FFE8;";
				}if (!empty($Procedimientos["fecha_rechazado"]) && !empty($Procedimientos["hora_rechazado"])) {
				    $fechaApliRech = htmlspecialchars($Procedimientos['usuario_iniciado'])." - Rechazado <br>".date("d-m-Y H:i", strtotime($Procedimientos["fecha_rechazado"] . " " . $Procedimientos["hora_rechazado"]));
				    $color 		= "#f95757";
				    $bgcolor 	= "#fbe2e2;";
				}
			 ?>
			 <tr style="background-color: <?=$bgcolor?>;">
                <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                    <svg aria-hidden="true" focusable="false" data-prefix="fad" style="color: <?=$color;?> !important; " data-icon="grip-lines-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" class="svg-inline--fa fa-grip-lines-vertical fa-w-8 fa-3x"><g class="fa-group"><path fill="currentColor" d="M224,16V496a16,16,0,0,1-16,16H176a16,16,0,0,1-16-16V16A16,16,0,0,1,176,0h32A16,16,0,0,1,224,16Z" class="fa-secondary"></path><path fill="currentColor" d="M96,16V496a16,16,0,0,1-16,16H48a16,16,0,0,1-16-16V16A16,16,0,0,1,48,0H80A16,16,0,0,1,96,16Z" class="fa-primary"></path></g></svg>
                </td>
                <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                    <b><i><?=htmlspecialchars($Procedimientos['descripcion_tipo'])?> (<?=htmlspecialchars($Procedimientos['descripcion_estado'])?>)<br>
                    <label style="font-size: 9px; color: cornflowerblue;">
                    <?= htmlspecialchars($fechaInserta) ?>                                           
                    </label>
                    </i></b>
                </td>
                <td class="my-1 py-1 mx-1 px-1 mifuente10 text-center" style="vertical-align:middle;">
                    <label id="label_nombre" class="d-inline-block" style="max-width: 250px;">
                      <b> <?= htmlspecialchars($Procedimientos['nombre_procedimiento']) ?> : </b> <?= htmlspecialchars($Procedimientos['nombre_subProcedimiento'])."<br>".$comentario; ?>
                	</label>                                    
                </td>
                <td width="100px;" class="my-1 py-1 mx-1 px-1 encabezado text-center" style="vertical-align:middle;">                     
					<button type="button" class="btn btn-sm mifuente btn-primary verIndicacionEnfermeria" id="<?=($Procedimientos['id'])?>" alt="Detalle Solicitud Indicacion" title="Detalle Solicitud Indicacion"><i class="fas fa-search"></i></button>                                                                                                  
                </td>
            </tr>

			<?php } ?>
		</tbody>
	</table>
</div>

<?php } else{ ?>


<div class="alert alert-warning p-3 m-3" role="alert">
	<div class="row">
		<div class="col-lg-12 text-center">
  			Actualmente no existen indicaciones de enfermería registradas.
		</div>
		<div class="col-lg-4 text-center">
		</div>
	</div>
</div>
<?php } ?>
<script type="text/javascript">
	$("#contenidoProcedimientosEnfermeria").on('click','.verIndicacionEnfermeria',function(){
        var indicacion_id = $(this).attr('id');
        modalFormulario_noCabecera('', `${raiz}/views/modules/enfermera/verIniciarIndicacionEnfermeria.php`, `indicacion_id=${indicacion_id}`,'#verIniciarIndicacionEnfermeriaModal', "modal-lg", "", "fas fa-plus");
    });

</script>