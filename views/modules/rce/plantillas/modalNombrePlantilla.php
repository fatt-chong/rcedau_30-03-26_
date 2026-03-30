<?php error_reporting(0); ?>
<div id="modalNombrePlantilla">
    <div class="panel panel-default">
        <fieldset>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
	            	    <label class="encabezado">Nombre Plantilla</label>
	            	</div>
                    <div class="col-md-12">
	            	    <input id="input_nombrePlantilla" name="input_nombrePlantilla" type="text" class="form-control form-control-sm mifuente12">
	            	</div>
                </div>
            </div>
        </fieldset>
    </div>    
</div>
<?php if($_POST['rce'] != 'S') { ?>
<hr>
 <div class="row">	
    <div class="col-lg-9">
    </div>
<div class="col-lg-3"> <button id="crearPlantillaInicioAtencion" type="button" name="crearPlantillaInicioAtencion" class="btn btn-sm btn-outline-primarydiag  mifuente11 col-lg-12 text-center" ><i class="fas fa-check-double mr-2"></i>Crear Plantilla</button> </div>
</div>
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
    $("#crearPlantillaInicioAtencion").click(function(){
        $.validity.start();
        if ( $("#input_nombrePlantilla").val() == "" ) {
            $("#input_nombrePlantilla").assert(false,'Debe indicar el nombre');
        }
        result = $.validity.end();
        if ( result.valid == false ) {
            return false;
        }
        var solicitudServidor = function(response){
            switch(response.status){
                case "success":
                    $('#slc_nombrePlantilla').append($('<option>', {
                        value: response.idPlantilla,
                        text: $("#input_nombrePlantilla").val()
                    }));

                    $(`select option[value='${response.idPlantilla}']`).attr("selected","selected");

                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Plantilla Creada. </h4><hr><p>Plantilla creada satisfactoriamente.</p> </div>';
                    modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
                    // ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+tipoMapa+`&dau_id=${dau_id}`,'#contenido','', true);
                    $('#ver_plantilla').modal( 'hide' ).data( 'bs.modal', null );
                break;
                case "error":

                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-edit mr-2" style="color: #5a8dc5; font-size: 26px"></i>Error. </h4><hr><p>'+response.message+'.</p> </div>';
                    modalMensajNoCabecera('Exito',texto,  "#modal", "modal-md", "success");
                break;
                default:        
                    ErrorSistemaDefecto();
                break;
            }
        };
        ajaxRequest(raiz+'/controllers/server/medico/main_controller.php','motivoConsulta='+$("#frm_rce_motivoConsultaSIA").val()+'&hipotesisDiagnosticaInicial='+$("#frm_rce_hipotesisInicialSIA").val()+'&nombrePlantilla='+$('#input_nombrePlantilla').val()+'&accion=crearPlantillaInicioAtencion', 'POST', 'JSON', 1,'Guardando Signos vitales...', solicitudServidor);
        
    });
 });

</script>