<?php
session_start();
error_reporting(0);
// $permisos = $_SESSION['permisosDAU'.SessionName];

require("../../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
require_once ("../../../../class/Util.class.php");              $objUtil                = new Util;
require_once("../../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once("../../../../class/Connection.class.php");         $objCon                 = new Connection();
require_once('../../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Bitacora.class.php');           $objBitacora            = new Bitacora;
require_once('../../../../class/AltaUrgencia.class.php');           $objAltaUrgencia            = new AltaUrgencia;
require_once('../../../../class/Diagnosticos.class.php');      $objDiagnosticos    = new Diagnosticos;
// require_once('../../../../class/Evolucion.class.php');          $objEvolucion           = new Evolucion;
// require("../../../../config/config.php");

$objCon->db_connect();
$parametros                     = $objUtil->getFormulario($_POST);


$parametros     	       = $objUtil->getFormulario($_POST);
$parametros['cta_cte']     = $parametros['idctacte'];
	$rsRce_diagnostico 	= $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);

?>
<script type="text/javascript">
	$(document).ready(function(){
	function consultar_cie10_TABLA(id_cie10) {
        let z = 0;
        let arrFun = new Array;

        $("#contenido_diagnostico tr").each(function (element) {
            let id_cie10 = $(this).find("td.td_id_cie10_TABLA").text();
            arrFun[z] = id_cie10;
            z++;
        });

        let encontrado = arrFun.find(function (element) {
            if (element == id_cie10) {
                return true;
            } else {
                return false;
            }
        });
        return encontrado;
    }
	var frm_servicio        = $('#frm_servicio').val();
    var frm_sala            = $('#frm_sala').val();
    var frm_cama            = $('#frm_cama').val();
    var frm_cod_servicio    = $('#frm_cod_servicio').val();
    var idctacte          = $('#idctacte').val();
    var rce_id              = $('#rce_id').val();
    var pac_id              = $('#pac_id').val();
		$("#frm_diagnostico").autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: raiz+"/controllers/server/medico/main_controller.php",
                dataType: "json",
                data: {
                    term : request.term,
                    accion : 'busquedaSensitivaDiagnostico',
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function(event, ui){
            $("#frm_diagnostico").val(ui.item.value);
            $("#hidden_frm_diagnostico").val(ui.item.id);
            $("#hidden_frm_diagnostico_descrip").val(ui.item.nomcompletoCIE);
            $('#frm_diagnostico').prop('readonly', true);
        },
        open: function(){
            $('.ui-menu').css( "font-weight" );

            $('.ui-menu').addClass( "col-lg-12" );
            $('.ui-menu').addClass( "mifuente11" );
            $(this).autocomplete("widget").css({                
                "max-height": 200,
                "overflow-y": "scroll",
                "overflow-x": "hidden",
                "z-index": 1050
                // "font-size": "12px"
            });
        }
    }).on("focus", function () {
        // $(this).autocomplete("search", '');
    });

    function removerAviso() {
        $.validity.start();
        $.validity.end();
    }

	// $('#btn_add_diagnostico').click(function(){
	// 	var btn_agregar_diag = function(){
	//         $.validity.start();
	//         if($("#hidden_frm_diagnostico").val() == ""){
	//             $('#frm_diagnostico').assert(false,"Debe indicar el Diagnostico.");

	//         }
	//         let rs_consultar_cie10_TABLA = consultar_cie10_TABLA($("#hidden_frm_diagnostico").val());
	//         if (rs_consultar_cie10_TABLA) {
	//             $(".btn_add_diagnostico").assert(false,'Cie10 Existente');
	//             $("#hidden_frm_diagnostico_descrip").val('');
	//             $("#hidden_frm_diagnostico").val('');
	//             $("#frm_diagnostico").val('');
	//             $("#frm_diagnostico").attr("readonly", false); 
	//             $("#frm_diagnostico").focus();
	//             const myTimeout = setTimeout(removerAviso, 1500);
	//         }
	//         result = $.validity.end();
	//         if(result.valid==false){
	//             return false;
	//         }
	//         var respDiagnostico = function(response){
 //                $("#modal_btn_add_diagnostico").modal( 'hide' ).data( 'bs.modal', null );
	//         	// ajaxContent(raiz+'/views/modules/rce/medico/diagnostico.php','idctacte='+idctacte+'&frm_servicio='+frm_servicio+"&pac_id="+pac_id,'#div_diagnostico2','', true);
 //                ajaxContentFast('/RCEDAU/views/modules/rce/medico/diagnostico.php','dau_id='+$('#dau_id').val()+'&rce_id='+rce_id+'&idPaciente='+pac_id+'&idctacte='+idctacte,'#div_diagnostico');
	//         };
	//         ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','hidden_frm_diagnostico='+$('#hidden_frm_diagnostico').val()+'&rce_id='+$('#rce_id').val()+'&dau_id='+$('#dau_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val()+'&hidden_frm_diagnostico_descrip='+$('#hidden_frm_diagnostico_descrip').val()+'&accion=guardarDiagnostico&cta_cte='+idctacte, 'POST', 'JSON', 1,'Cargando...',respDiagnostico);                          
 //        };
	// 	var botones =   [
 //                            { id: 'btn_agregar_diag', value: ' Guardar', function: btn_agregar_diag, class: 'btn btn-success' }
 //                        ];
	// 	modalFormulario('<label class="mifuente text-primary">Agregar Diagnóstico</label>',raiz+"/views/modules/rce/medico/agregar_diagnostico.php",'rce_id='+$('#rce_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val(),'#modal_btn_add_diagnostico','modal-md','', 'fas fa-align-justify text-primary',botones);
	//     });
	    $('.btn_delete').click(function(){
	        var id       = parseInt($(this).attr('id').replace('btn_delete',''));            
	        var resServer = function(response){
	        	// ajaxContent(raiz+'/views/modules/rce/medico/diagnostico.php','idctacte='+idctacte+'&frm_servicio='+frm_servicio+"&pac_id="+pac_id,'#div_diagnostico2','', true);
                // ajaxContentFast('/RCEDAU/views/modules/rce/medico/diagnostico.php','dau_id='+$('#dau_id').val()+'&rce_id='+rce_id+'&idPaciente='+pac_id+'&idctacte='+idctacte,'#div_diagnostico');
                 // ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(), '#contenido');
                        ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(),'#contenido','', true);
	        };
	        ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','hidden_frm_diagnostico='+$('#hidden_frm_diagnostico').val()+'&rce_id='+$('#rce_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val()+'&hidden_frm_diagnostico_descrip='+$('#hidden_frm_diagnostico_descrip').val()+'&id='+id+'&accion=eliminarDiagnosticoRCE', 'POST', 'JSON', 1,'Cargando...',resServer);
	        
	    });
	    $('.btn_diag_escrito').click(function(){
	        var id       = parseInt($(this).attr('id').replace('btn_diag_escrito','')); 
	        var Funcionbtn1 = function(){
                $.validity.start();
	            if($("#frm_diagnostico_descrip").val() == ""){
	                $('#frm_diagnostico_descrip').assert(false,"Debe indicar la Descripción del Diagnóstico.");

	            }
	            result = $.validity.end();
	            if(result.valid==false){
	                return false;
	            }
	            removerValidity();
                var respuestaControlador = function(response){
                    switch(response.status){
                        case "success":
                                // ajaxContent(raiz+'/views/modules/rce/medico/diagnostico.php','idctacte='+idctacte+'&frm_servicio='+frm_servicio+"&pac_id="+pac_id,'#div_diagnostico2','', true);
                                // ajaxContentFast('/RCEDAU/views/modules/rce/medico/diagnostico.php','dau_id='+$('#dau_id').val()+'&rce_id='+rce_id+'&idPaciente='+pac_id+'&idctacte='+idctacte,'#div_diagnostico');
                                
                        ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(),'#contenido','', true);
                            // ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(), '#contenido');
                        break;

                        case "error":   
                                modalMensaje("Error en el proceso", "No se pudo agregar al paciente a la lista.", "error_archivo_no_eliminado",  "#modal", "", "danger");
                        break;
                        default:   
                                modalMensaje("Error en el proceso", "No se pudo agregar al paciente a la lista.", "error_archivo_no_eliminado",  "#modal", "", "danger");
                        break;
                    }
                    $('#modal_btn_diag_escrito').modal( 'hide' ).data( 'bs.modal', null );
                };
                ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','frm_diagnostico_descrip='+encodeURIComponent($('#frm_diagnostico_descrip').val())+'&rce_diagnostico_id='+id+'&accion=actualizarDiagnosticoRCE&ctacte='+idctacte, 'POST', 'JSON', 1,'Cargando...',respuestaControlador);                          
            };

	        var botones =   [
	                            { id: 'btn_descrip_diag', value: ' Guardar', function: Funcionbtn1, class: 'btn btn-success' }
	                        ];
	        modalFormulario('<label class="mifuente text-primary">Descripción Diagnóstico</label>',raiz+"/views/modules/rce/medico/descrip_diagnostico.php",'hidden_frm_diagnostico='+$('#hidden_frm_diagnostico').val()+'&rce_id='+$('#rce_id').val()+'&rce_evolucion_id='+$('#rce_evolucion_id').val()+'&hidden_frm_diagnostico_descrip='+$('#hidden_frm_diagnostico_descrip').val()+'&id='+id,'#modal_btn_diag_escrito','modal-md','', 'fas fa-align-justify text-primary',botones);
	        
	    });
    });
</script>
<style type="text/css">
	/*.ScrollStylePDiagnosticos{
        max-height: calc(100vh - 520px);
    	overflow: auto;
    }*/

    <style>
    /* Estilos personalizados para diferentes tamanhos de tela */
    @media (max-height: 576px) { /* Tela pequena */
      .ScrollStylePDiagnosticos {
         max-height: calc(100vh - 520px);
    	overflow-x: hidden;
      }
    }

    @media (min-height: 577px) and (max-height: 768px) { /* Tela média */
      .ScrollStylePDiagnosticos {
         max-height: 135px;
    	overflow-x: hidden;
      }
    }

    @media (min-height: 769px) and (max-height: 992px) { /* Tela grande */
      .ScrollStylePDiagnosticos {
         max-height: 160px;
    	overflow-x: hidden;
      }
    }
    @media (min-height: 993px) and (max-height: 1080px)  { /* Tela extra grande */
      .ScrollStylePDiagnosticos {
         max-height: 218px;
    	overflow-x: hidden;
      }
    }
    @media (min-height: 1080px)  { /* Tela extra grande */
      .ScrollStylePDiagnosticos {
         max-height: 218px;
    	overflow-x: hidden;
      }
    }
  </style>
</style>

</style>
	<div class="row ScrollStylePDiagnosticos">
		<!-- <div id="divAnte" class=""> -->
			<?php if( count($rsRce_diagnostico) > 0 ){ ?>
			<!-- <div class="row  mb-2"> -->
				<div class=" col-lg-12 col-md-12" style="padding-left: 0px !important; padding-right: 0px !important;">
					<div class="table-responsive-lg">
					<table id="tabla_contenido_insumos" width="100%" class="table table-hover" style="font-size: 14px;">
							<tbody id="contenido_diagnostico" >
								<?php  for ($i=0; $i < count($rsRce_diagnostico) ; $i++) { 
                                    $codigo = $rsRce_diagnostico[$i]['id_cie10'];
                                    $texto = $rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                                    $posicion = strpos($texto, $codigo);
                                    if ($posicion !== false) {
                                    }else{
                                        $rsRce_diagnostico[$i]['diagnistico_descripcion_text'] = $rsRce_diagnostico[$i]['id_cie10']." ".$rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                                    }
                                        ?>
								<tr id="id<?php echo $rsRce_diagnostico[$i]['id_compartido'];?>">
									
									<td class="my-1 py-1 mx-1 px-1 mifuente11 td_id_cie10_TABLA " hidden ><?php echo $rsRce_diagnostico[$i]['id_cie10'];?></td>

									<td class="my-1 py-1 mx-1 px-1 mifuente11  " width="90%"><?php echo $rsRce_diagnostico[$i]['diagnistico_descripcion_text'];?><br> <i><label><?php if($rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']!= ""){ echo "-&nbsp;&nbsp;".$rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario'].""; } ?></label></i></td>
									<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" >
										<button id="btn_diag_escrito<?php echo $rsRce_diagnostico[$i]['id_compartido'];?>"   style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" class="btn btn-outline-primary  mifuente btn_diag_escrito"><i class="fas fa-align-justify"></i></button>
		  							</td>
									<?php  if ( $_SESSION['MM_Username'.SessionName] == $rsRce_diagnostico[$i]['usuario'] ) { ?>
										<td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" >
											<button id="btn_delete<?php echo $rsRce_diagnostico[$i]['id_compartido'];?>" style="padding: .275rem .35rem !important; border-color: #28a74500!important;" type="button" class="btn btn-outline-danger  mifuente btn_delete"><i class="fas fa-trash-alt"></i></button>
										</td>
									<?php } ?>
		  						</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			<!-- </div> -->
			<?php } else {?>
			<?php } ?>
		<!-- </div> -->
	</div>
<!-- </div> -->
<!-- </div>