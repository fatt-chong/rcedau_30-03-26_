<?php 
session_start();
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require_once("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();
// require_once("../../../../class/Atencion.class.php");          $objAtencion        = new Atencion;
require_once("../../../../class/Util.class.php");              $objUtil            = new Util;
require_once("../../../../class/RCE.class.php");               $objRCE             = new RCE;
$objCon->db_connect();
$version        = $objUtil->versionJS();
$parametros     = $objUtil->getFormulario($_POST);
if($parametros['idAntecedente']!=10){
	$rsTiposAntecedentes = $objRCE->obtenerTipoAntecedentes($objCon,$parametros);
}
$rsIndicaciones = $objRCE->listarAntecedentes($objCon,$parametros);
?>
<!-- <script type="text/javascript" src="<?=RAIZ?>/controllers/client/gestion_hospital/gestion_hospital/detalle_paciente/acciones/rce_medico/ingresar_antecendetes.js?v=<?=$version;?>"></script> -->

<form id="frm_ingreso_antecedente" class="formularios" name="frm_ingreso_antecedente" role="form" method="POST">
<!-- <div id="avisoExito"></div> -->
<input type="hidden" name="idAntecedente" id="idAntecedente" value="<?= $parametros['idAntecedente']; ?>" />
    <div class="row">
        <div class="col-md-12">
            <div class=" form-group">
                <div class=" text-secondary">
                    <i class="fas fa-folder-open text-primary1"></i>&nbsp;&nbsp;<?php echo $rsIndicaciones[0]['tipAntDescripcion']; ?>
                </div>
                <div class="card-body mifuente">
                    <div class="row">
                        <?php if($parametros['idAntecedente']!=11){ ?>

                        <div class="col-md-6">
                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Fecha Inicio</label>
                            <div class="input-group">  
                                <input type="text" class="form-control form-control-sm mifuente" name="frm_fecha_inicio" id="frm_fecha_inicio" placeholder="DD/MM/AAAA">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Fecha Término</label>
                            <div class="input-group">  
                                <input type="text" class="form-control form-control-sm mifuente" id="frm_fecha_termino" name="frm_fecha_termino" placeholder="DD/MM/AAAA">
                            </div>
                        </div>

                        <?php  } ?>

                        <div class="container-fluid">
                            <div class="form-group">
                                <div class="row">
                                <?php  switch($parametros['idAntecedente']){
                            		case '10': ?>
                                    <div class="col-md-12">
                                        <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Diagnostico</label>
                                        <div class="input-group">               
                                            <input type="text" class="form-control form-control-sm mifuente" id="diagCie10" name="diagCie10" placeholder="CIE-10"/>
                                            <input type="hidden" class="form-control" id="hidden_diagCie10" name="hidden_diagCie10" />
                                        </div>
                                    </div>
                            		<?php  break;
                            		case '11': ?>
                                        <div class="col-md-6">
                                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Tipo</label>
                                            <div class="input-group">               
                                                <select class="form-control  form-control-sm mifuente" id="tipoAntecedente" name="tipoAntecedente">
                                                     <option value="0" placeholder="Seleccione" selected disabled>Seleccione</option>
                                                     <?php  for($a=0;$a<count($rsTiposAntecedentes);$a++){ ?>
                                                     <option value="<?= $rsTiposAntecedentes[$a]['antId']?>"><?=$rsTiposAntecedentes[$a]['antDescripcion']?></option>
                                                     <?php  } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Observación</label>
                                            <div class="input-group">               
                                                <select class="form-control  form-control-sm escolaridad mifuente" id="detalleAntecedente" name="detalleAntecedente">
                                                     <option value="0" placeholder="Seleccione" selected disabled>Seleccione</option>
                                                     <option class="escolar" value="Basica">Basica</option>
                                                     <option class="escolar" value="Media">Media</option>
                                                     <option class="escolar" value="Superior">Superior</option>
                                                     <option class="escolar" value="Completa">Completa</option>
                                                     <option class="escolar" value="Incompleta">Incompleta</option>
                                                     <option class="escolar" value="Ninguna">Ninguna</option>
                                                     <option class="sexual" value="Heterosexual">Heterosexual</option>
                                                     <option class="sexual" value="Homosexual">Homosexual</option>
                                                     <option class="sexual" value="Bisexual">Bisexual</option>
                                                     <option class="sexual" value="Ignorado">Ignorado</option>
                                                     <option class="parejas" value="Ninguna">Ninguna</option>
                                                     <option class="parejas" value="2 a 4">2 a 4</option>
                                                     <option class="parejas" value="5 a 9">5 a 9</option>
                                                     <option class="parejas" value="Mas de 10">Mas de 10</option>
                                                     <option class="usoCondon" value="Siempre">Siempre</option>
                                                     <option class="usoCondon" value="A veces">A veces</option>
                                                     <option class="usoCondon" value="Nunca">Nunca</option>
                                                     <option class="viasUso" value="Oral">Oral</option>
                                                     <option class="viasUso" value="Respiratorio">Respiratorio</option>
                                                     <option class="viasUso" value="Endovenosa">Endovenosa</option>
                                                </select>
                                            </div>
                                        </div>
                            		<?php  break;
                            		default: ?>
                                        <div class="col-md-6">
                                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Tipo</label>
                                            <div class="input-group">               
                                                <select class="form-control form-control-sm mifuente" id="tipoAntecedente" name="tipoAntecedente">
                                                     <option value="0" placeholder="Seleccione" selected disabled>Seleccione</option>
                                                     <?php  for($a=0;$a<count($rsTiposAntecedentes);$a++){ ?>
                                                     <option value="<?= $rsTiposAntecedentes[$a]['antId']?>"><?=$rsTiposAntecedentes[$a]['antDescripcion']?></option>
                                                     <?php  } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="inputEmail" class="col-sm-12 col-form-label encabezado" >Observación</label>
                                            <div class="input-group">  
                                                <textarea class="form-control" rows="3" id="obsAntecedente" name="obsAntecedente" placeholder="Observacion"></textarea>
                                            </div>
                                        </div>
                                	<?php  break;
                                	} ?>
                                	
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
    fechaBootstrapLinked('#frm_fecha_inicio','#frm_fecha_termino');
    var idAntecedente = $("#idAntecedente").val();
    $("#agrAntecedente"+idAntecedente).show();

    switch(idAntecedente){
        case '10':
            $("#diagCie10").autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
                source: function(request, response) {
                    $.ajax({
                        type: "POST",
                        url: "/RCEv2_1/controllers/server/registro_clinico/main_controller.php",
                        dataType: "json",
                            data: {
                                term : request.term,
                                accion : 'busquedaSensitivaCie10',
                            },
                            success: function(data) {
                            response(data);
                            //console.log(data);
                            }
                            });
                        },
                        minLength: 3,
                        select: function(event, ui){
                            $("#hidden_diagCie10").val(ui.item.id);
                        },
                        open: function(){
                            $('.ui-menu').css( "font-weight" );
                            $(this).autocomplete("widget").css({
                                "width": 600,
                                "max-height": 600,
                                "overflow-y": "scroll",
                                "overflow-x": "none",
                                "z-index": 1050,
                                "font-size": "12px"
                            });
                        }
                        
                });
        break;
        case '11':
            $(".escolar").hide();
            $(".sexual").hide();
            $(".parejas").hide();
            $(".usoCondon").hide();
            $(".viasUso").hide();
            $("#tipoAntecedente").on("change", function(e){  //.change(function(){ 
                var tipoAnt = $("#tipoAntecedente option:selected").val();
                switch(tipoAnt){
                    case '46'://Escolaridad
                        $("#detalleAntecedente").val("0");
                        $(".escolar").show();
                        $(".sexual").hide();
                        $(".parejas").hide();
                        $(".usoCondon").hide();
                        $(".viasUso").hide();
                    break;
                    case '47'://Orientacion Sexual
                        $("#detalleAntecedente").val("0");
                        $(".escolar").hide();
                        $(".sexual").show();
                        $(".parejas").hide();
                        $(".usoCondon").hide();
                        $(".viasUso").hide();
                    break;
                    case '48'://Parejas Sexuales
                        $("#detalleAntecedente").val("0");
                        $(".escolar").hide();
                        $(".sexual").hide();
                        $(".parejas").show();
                        $(".usoCondon").hide();
                        $(".viasUso").hide();
                    break;
                    case '49'://Uso de Condon
                        $("#detalleAntecedente").val("0");
                        $(".escolar").hide();
                        $(".sexual").hide();
                        $(".parejas").hide();
                        $(".usoCondon").show();
                        $(".viasUso").hide();
                    break;
                    case '50'://Vias de Uso Drogas
                        $("#detalleAntecedente").val("0");
                        $(".escolar").hide();
                        $(".sexual").hide();
                        $(".parejas").hide();
                        $(".usoCondon").hide();
                        $(".viasUso").show();
                    break;
                    default:
                        $("#detalleAntecedente").val("0");
                        $(".escolar").hide();
                        $(".sexual").hide();
                        $(".parejas").hide();
                        $(".usoCondon").hide();
                        $(".viasUso").hide();
                    break;
                    }
            });
    break;
    }

})
</script>