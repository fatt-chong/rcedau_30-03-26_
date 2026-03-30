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
<div class="m-3">
	<table width="100%" id="tablaProcedimientosEnfermeria" class="table  table-hover table-condensed tablasHisto">
		<thead>
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
		</thead>
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
			<tr style="background-color: <?=$bgcolor?>"> 
				<td style="vertical-align: middle; " class="text-nowrap my-1 py-1 mx-1 px-1 mifuente10  text-center "><b><?= htmlspecialchars($Procedimientos['nombre_procedimiento']) ?></b> <br> (<?= htmlspecialchars($Procedimientos['nombre_subProcedimiento']).$comentario; ?>)</td>
				<td class="my-1 py-1 mx-1 px-1 mifuente10  text-center" width="15%" style="vertical-align:middle;" id="3">
												 <b><?= htmlspecialchars($Procedimientos['descripcion_tipo'])."<br>(".htmlspecialchars($Procedimientos['descripcion_estado']).")" ?> 
											</td>
				<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente10  text-center "><label style="font-size: 10px; color: cornflowerblue; margin-bottom: 0rem !important;"><?= htmlspecialchars($Procedimientos['usuario_creado']) ?><br><?= htmlspecialchars($fechaInserta) ?></label></td>
				<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente10  text-center "> <label style="font-size: 10px; color: cornflowerblue; margin-bottom: 0rem !important;"><?= htmlspecialchars($Procedimientos['usuario_iniciado']) ?><br><?= htmlspecialchars($fechaIniciado) ?></label> </td>
				<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente10  text-center "> <label style="font-size: 10px; color: <?=$color?>; margin-bottom: 0rem !important;"><?= ($fechaApliRech) ?></label>  </td>
				<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente10  text-center ">
				<button type="button" class="btn btn-sm mifuente btn-primary verIndicacionEnfermeria" id="<?=($Procedimientos['id'])?>" alt="Detalle Solicitud Indicacion" title="Detalle Solicitud Indicacion"><i class="fas fa-search"></i></button> 
					<?php if($Procedimientos['estado'] == 1){ ?>
						<button type="button" class="btn btn-sm mifuente btn-light IniciarIndicacionEnfermeria" id="<?=($Procedimientos['id'])?>-2" alt="Iniciar Solicitud Indicacion" title="Iniciar Solicitud Indicacion"><i class="fas fa-play"></i></button> 
					<?php } ?>
					<?php if($Procedimientos['estado'] == 2){ ?>
						<button type="button" class="btn btn-sm mifuente btn-success  IniciarIndicacionEnfermeria" id="<?=($Procedimientos['id'])?>-3" alt="Aplicar Solicitud Indicacion" title="Iniciar Solicitud Indicacion"><i class="fas fa-check"></i></button> 
						<button type="button" class="btn btn-sm mifuente btn-danger  IniciarIndicacionEnfermeria" id="<?=($Procedimientos['id'])?>-4" alt="Rechazar Solicitud Indicacion" title="Iniciar Solicitud Indicacion"><i class="fas fa-trash"></i></button> 
					<?php } ?>
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

    $("#contenidoProcedimientosEnfermeria").on('click','.IniciarIndicacionEnfermeria',function(){
        var id_completo 	= $(this).attr('id'); 
	    var partes 			= id_completo.split('-'); 
	    var indicacion_id 	= partes[0];
	    var estado 			= partes[1];
        function confirmarAccionEnIndicacion ( ) {
	    	function funcionIniciar ( ) {
        		const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`, $("#frm_modal_inicio_enfermeria").serialize()+`&indicacion_id=${indicacion_id}&accion=GestionarIndicacion&estado=${estado}`, 'POST', 'JSON', 1,'Aplicando Acción en Indicación...');
        		switch(respuestaAjaxRequest.status){
	                case 'success':
	                    $('#modalObservacion').modal('hide').data('bs.modal', null);
	                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito </h4>  <hr>  <p class="mb-0">Su solicitud se ha ejecutado de forma exitosa</p></div>';
                    	modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
	                    ajaxContent(`${raiz}/views/modules/enfermera/despliegueIndicacionesEnfermeria.php`,'dau_id='+$('#dau_id').val(),'#div_indicacion','', true);
	                break;
	                case 'error' :
	                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Error en aplicar categorización al paciente:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
	                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
	                break;
	            }
	       }
	       modalConfirmacionNuevo("Advertencia", `ATENCIÓN, se procederá a iniciar la Indicación, <b>¿Desea continuar?</b>`, "primary", funcionIniciar);
	    }
        botones =   [
                        { id: 'agregarObservacion', value: ' Aplicar Indicación', function: confirmarAccionEnIndicacion, class: 'btn btn-primary' }
                    ];
        modalFormulario('<label class="mifuente text-primary">Observación Aplicar Indicación</label>', `${raiz}/views/modules/enfermera/IniciarIndicacionEnfermeria.php`,`indicacion_id=${indicacion_id}`,'#modalObservacion','modal-lg','', 'fas fa-align-justify text-primary',botones);
    });

</script>