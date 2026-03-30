<script type="text/javascript">
	$(document).ready(function(){


	// function consultar_cie10_TABLA(id_cie10) {
 //        let z = 0;
 //        let arrFun = new Array;

 //        $("#contenido_diagnostico tr").each(function (element) {
 //            let id_cie10 = $(this).find("td.td_id_cie10_TABLA").text();
 //            arrFun[z] = id_cie10;
 //            z++;
 //        });

 //        let encontrado = arrFun.find(function (element) {
 //            if (element == id_cie10) {
 //                return true;
 //            } else {
 //                return false;
 //            }
 //        });
 //        return encontrado;
 //    }
	var frm_servicio        = $('#frm_servicio').val();
    var frm_sala            = $('#frm_sala').val();
    var frm_cama            = $('#frm_cama').val();
    var frm_cod_servicio    = $('#frm_cod_servicio').val();
    var frm_ctacte          = $('#ctacte').val();
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

            $('.ui-menu').addClass( "col-md-8" );
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
    });


    $(document).ready(function() {
        $(document).on('shown.bs.modal', '.modal', function () {
            var inputText = $('#frm_diagnostico');
            
            if (inputText.length > 0 && inputText.val() !== "") {
                inputText.focus();
                
                var strLength = inputText.val().length;
                inputText[0].setSelectionRange(strLength, strLength);
            } else if (inputText.length > 0) {
                setTimeout(function () {
                    inputText.focus();
                }, 0);
            }
        });
    });
</script>
    <div class="row m-3">
		<div class="col-md-12">
			<input type="text" class="form-control form-control-sm mifuente" name="frm_diagnostico" id="frm_diagnostico">
			<input type="hidden"  name="hidden_frm_diagnostico_descrip" id="hidden_frm_diagnostico_descrip">
			<input type="hidden"  name="hidden_frm_diagnostico" id="hidden_frm_diagnostico">
		</div>
	</div>