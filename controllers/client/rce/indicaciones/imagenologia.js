$(document).ready(function(){

    validar($('#frm_examen_obs'),'texto_textarea');
    
    $("#div_exacon").hide();
                $("#div_exacon_hijos").hide();
                $("#div_exacon_hijos_espacio").show();
                $("#chk_exacon").prop('checked', false);
                $('.clase_contraste').prop('checked', false);
                $('.clase_contraste').attr('disabled',true);
                $("#Div_plano").hide();
    $("#frm_diagnostico_texto").autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: raiz+"/controllers/server/gestion_hospital/gestion_hospital/detalle_paciente/acciones/especialista/main_controller.php",
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
            $("#frm_diagnostico_texto").val(ui.item.nomcompletoCIE);
            $("#frm_diagnostico").val(ui.item.nomcompletoCIE);
            $('#frm_diagnostico_texto').prop('readonly', true);
        },
        open: function(){
            var $input = $(this);
                    // console.log("input", $input);
                    var $menu = $input.autocomplete("widget");
                    // console.log("menu", $menu);
                    var inputWidth = $input.outerWidth();
                    // console.log("inputWidth", inputWidth);
                    $menu.outerWidth(inputWidth);           
                    
                    // Resto del código para personalizar el menú desplegable (opcional)
                    $menu.css({
                        "z-index": 1050,
                        "font-size": "12px"
                       
                    });
        }
    }).on("focus", function () {
        // $(this).autocomplete("search", '');
    });
    function consultarListaGrupo(grupo) {
        let z = 0;
        let productosFinal = new Array;

        $("#contenidoRayo tr").each(function (element) {
            var codigoFarmaco = $(this).find("td.verificarGrupo").text();
            var codigoFarmaco2 = $(this).find('input[class*="valor_input_lateridad"]').val()
            var codigoFarmaco3 = $(this).find('input[class*="valor_input_lateridad"]').val()
            productosFinal[z] = codigoFarmaco;
            z++;
        });
        let encontrado = productosFinal.find(function (element) {
            if (element == grupo) {
                return true;
            } else {
                return false;
            }
        });

        return encontrado;
    }

    
     validar("#frm_obs_examen", "letras_numeros_1");
     validar("#frm_diagnostico", "letras_numeros_1");
     validar("#frm_sintomasp", "letras_numeros_1");
     validar("#frm_antecedentes", "letras_numeros_1");
     validar("#frm_otros_text", "letras_numeros_1");
  
    $("#comboTipoExamenes").prop('disabled', true).trigger('chosen:updated');

    $(".chosen-select").chosen(
        {width: "100%"}
    ); 



    $("#frm_tipo_examen").change(function(event){
        $("#comboTipoExamenes").prop('disabled', false).trigger('chosen:updated');
        var valor_combo  = $("#frm_tipo_examen option:selected").val();
        var response     = ajaxRequest(raiz+'/controllers/server/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/main_controller.php', 'valor_combo='+valor_combo+'&accion=cargarParametros', 'POST', 'JSON', 1);
        $("#val_cmb").val(valor_combo);
        $('#comboTipoExamenes').empty(); 
        var newOption = response;
        $('#comboTipoExamenes').append(newOption);
        $('#comboTipoExamenes').trigger("chosen:updated");       
    });

    /***********************new*************************/

    if($("#LEX_otros_frm").val() != ""){
        $('#txt_otros').attr('disabled',false);
    }else{
        $('#txt_otros').attr('disabled',true);
    }

    $("#examenesCargados_I").show();

    $("#chk_exacon").attr('disabled',false);
    $('.clase_contraste').attr('disabled',true);


    $("#div_exacon").hide();
    $("#div_informe_sin_contraste").hide();


    $("#txt_examenes").autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
        source: function(request, response) {
            $.ajax({
                type: "POST",
                url: "./controllers/server/medico/main_controller.php",
                dataType: "json",
                data: {
                    term : request.term,
                    tipoExamen : $('#tipoExamen').val(),
                    accion : 'busquedaSensitiva_examenes',
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 0,
        select: function(event, ui){

            $( "#txt_examenes" ).prop( "disabled", true );

            $("#txt_examenes_codigo").val(ui.item.id_prestaciones);
            $("#descripcion_examen").val(ui.item.examen);
            $("#tipoExamen_2").val(ui.item.tipo_examen);
            $("#conContraste").val(ui.item.contraste);


            if(ui.item.tipo_examen == "RM"){
                $("#div_marca_paso").show()
            }else{
                $("#div_marca_paso").hide()
            }

            

            //Lateralidad == plano

            if(ui.item.Lateralidad == "S"){
                $("#Div_plano").show();
            }else{
                $("#Div_plano").hide();
                $("#frm_plano").val(0);
            }

            //Segmento == extremidad

            if(ui.item.Segmento == "S"){
                $("#Div_extremidad").show();
            }else{
                $("#Div_extremidad").hide();
                $("#frm_extremidad").val(0);
            }

            if(ui.item.contraste == 'S' || ui.item.contraste == 'C'){
                $("#div_exacon").show();
                $("#div_exacon_hijos").show();
                $("#div_exacon_hijos_espacio").hide();

                $( "#frm_examen_ni" ).prop( "checked", true );
                $( "#frm_examen_ni" ).click();

                // alert()

            }else if(ui.item.contraste == 'A'){
                $("#div_exacon").show();
                $("#div_exacon_hijos").show();
                $("#div_exacon_hijos_espacio").hide();

                $( "#frm_examen_ni" ).prop( "disabled", true );
                $( "#frm_examen_si" ).prop( "checked", true );
                $( "#frm_examen_si" ).click();
            }else{
                $("#div_exacon").hide();
                $("#div_exacon_hijos").hide();
                $("#div_exacon_hijos_espacio").show();
                $("#chk_exacon").prop('checked', false);
                $('.clase_contraste').prop('checked', false);
                $('.clase_contraste').attr('disabled',true);
            }

            if(ui.item.contraste == 'N' && ui.item.tipo_examen == 'RX' && $('input:checkbox[id=frm_Embarazo]:checked').val() == 'S'){
                // alert("1")
                $("#div_informe_sin_contraste").show();
            }


            if(ui.item.contraste == 'N' && ui.item.tipo_examen == 'MX' && $('input:checkbox[id=frm_Embarazo]:checked').val() == 'S'){
                // alert("3")
                $("#div_informe_sin_contraste").show();
            }

            

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
    }).on("focus", function () {
        $(this).autocomplete("search", '');
    });


    $("#chk_otros").click(function() {
        if ($(this).is(':checked')) {
            $('#txt_otros').attr('disabled',false);
        }else{
            $('#txt_otros').val('');
            $('#txt_otros').attr('disabled',true);
        }
    });

    $(".chk_exacon").click(function() {
        if ($(this).is(':checked') && $('input:radio[name=chk_exacon]:checked').val() == 'S') {
            // alert("1")
            $('.clase_contraste').attr('disabled',false);
        }else{
            // alert("2")
            if($("#tipoExamen_2").val() == 'TC' && $('input:checkbox[id=frm_Embarazo]:checked').val() == 'S' ){
                var txt_examenes_codigo = encodeURIComponent($("#txt_examenes_codigo").val());
                var id_paciente         = $("#pacId").val();
                var txt_diag            = $(".text_diag").val();
                var emb                 = $('input:checkbox[id=frm_Embarazo]:checked').val();
                // alert(1)
                showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_imagenologico.php?txt_examenes_codigo="+txt_examenes_codigo+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag+'&emb='+emb, "800px", "700px");
            }
            $('#chk_exacon').val('');
            $('.clase_contraste').prop('checked', false);
            $('.clase_contraste').attr('disabled',true);

        }
    });


    $('#chk_coninf').change(function(){

        var txt_examenes_codigo = encodeURIComponent($("#txt_examenes_codigo").val());

        if ( $('input:checkbox[id=chk_coninf]:checked').val() == 'coninf' ) {

            if( ! $('#chk_coninf').is(':checked') ) {

            }else{
                var embarazado     = $('input:checkbox[id=frm_Embarazo]:checked').val();
                var tipoExamen_pdf = $("#tipoExamen_2").val();
                var id_paciente    = $("#pacId").val();
                var txt_diag       = $(".text_diag").val();


                if(embarazado == 'S'){

                    

                    if(tipoExamen_pdf == 'TC'){
                        showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_tomografia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
                    }else if(tipoExamen_pdf == 'RM'){
                        showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_resonancia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
                    }
                }else{

                    

                    if(tipoExamen_pdf == 'TC'){
                        showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_tomografia.php?txt_examenes_codigo="+txt_examenes_codigo+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
                    }else if(tipoExamen_pdf == 'RM'){
                        showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_resonancia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
                    }
                }
            }
        }

    });




    $('#chk_informe_sin_contraste').change(function(){
        var emb           = $('input:checkbox[id=frm_Embarazo]:checked').val();
        var id_paciente    = $("#id_paciente").val();
        var txt_diag       = $("#txt_diag").val();
        var txt_examenes_codigo = encodeURIComponent($("#txt_examenes_codigo").val());

        if( ! $('#chk_informe_sin_contraste').is(':checked') ) {

        }else{
            if ( emb == 'S' ) {
                if($("#tipoExamen_2").val() == 'RX' || $("#tipoExamen_2").val() == 'MX' || $("#tipoExamen_2").val() == 'TC'){
                    alert("11")
                    showFile("/Camas/views/modules/gestion_hospital/gestion_hospital/detalle_paciente/acciones/indicaciones/Imagenologia/PDFconsentimiento_informado_imagenologico.php?txt_examenes_codigo="+txt_examenes_codigo+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag+'&emb='+emb, "800px", "700px");
                }
            }

        }

    });


    $("#btnBorrarExamen").click(function(){
        $( "#txt_examenes" ).prop( "disabled", false );
        $("#txt_examenes").val('');
        $("#txt_examenes_codigo").val('');
        $("#descripcion_examen").val('');
        $("#tipoExamen_2").val('');
        $("#conContraste").val('');

        $("#Div_plano").show();
        $("#Div_extremidad").hide();

        $("#frm_plano").val(0);
        $("#frm_extremidad").val(0);

        $("#div_exacon").hide();
        // $("#div_exacon_hijos").hide();
        $("#div_exacon_hijos_espacio").show();
        $('.chk_exacon').prop('checked', false);
        $("#chk_exacon").prop('checked', false);
        $('.clase_contraste').prop('checked', false);
        $('.clase_contraste').attr('disabled',true);


        $("#div_informe_sin_contraste").hide();
        $('#chk_informe_sin_contraste').prop('checked', false);

        $( "#frm_examen_ni" ).attr( "disabled", false );

        $("#div_exacon").hide();
                $("#div_exacon_hijos").hide();
                $("#div_exacon_hijos_espacio").show();
                $("#chk_exacon").prop('checked', false);
                $('.clase_contraste').prop('checked', false);
                $('.clase_contraste').attr('disabled',true);
                $("#Div_plano").hide();

    });



    $(document).on('click', '.eliminaExamenTR_2', function (event) {
        event.preventDefault();
        var id = $(this).attr("id");
        // alert(id)
        // // alert(id)
        $("#trEX_"+id).css("display", "none");
        var valor0 = $(this).parents("tr").find("td").eq(7).html(3);
    });

    $('#chk_coninf').change(function(){
        var txt_examenes_codigo = encodeURIComponent($("#txt_examenes_codigo").val());
        // alert(txt_examenes_codigo)
        if ( $('input:checkbox[id=chk_coninf]:checked').val() == 'coninf' ) {
    		if( ! $('#chk_coninf').is(':checked') ) {
            
            }else{
                var embarazado     = $('input:checkbox[id=frm_Embarazo]:checked').val();
    			var tipoExamen_pdf = $("#tipoExamen_2").val();
                // alert(tipoExamen_pdf)
    			var id_paciente    = $("#pacId").val();
    			var txt_diag = ($(".text_diag").val() || "").trim();
                if(embarazado == 'S'){
                   
                    if(tipoExamen_pdf == 'TC'){
	    				showFile("/RCEDAU/PDF/ConsentimientoInformado/PDFconsentimiento_informado_tomografia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
	    			}else if(tipoExamen_pdf == 'RM'){
	    				showFile("/RCEDAU/PDF/ConsentimientoInformado/PDFconsentimiento_informado_resonancia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
	    			}
                    
                }else{
                    if(tipoExamen_pdf == 'TC'){
	    				showFile("/RCEDAU/PDF/ConsentimientoInformado/PDFconsentimiento_informado_tomografia.php?txt_examenes_codigo="+txt_examenes_codigo+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
	    			}else if(tipoExamen_pdf == 'RM'){
	    				showFile("/RCEDAU/PDF/ConsentimientoInformado/PDFconsentimiento_informado_resonancia.php?txt_examenes_codigo="+txt_examenes_codigo+'&embarazado='+embarazado+'&id_paciente='+id_paciente+'&txt_diag='+txt_diag, "800px", "700px");
	    			}
                }
            }
        }
    });

    $("#agregar_examen").click(function(){

        $.validity.start();

        if($("#idPacienteDau").val() == "" ){
            $("#cons").assert(false,'Debe Seleccionar un Paciente');
        }

        var busca = $("#detalle_examen option:selected").val();

        if($("#conContraste").val() == "S" || $("#conContraste").val() == "C"){
            if(!$("input[name='chk_exacon']").is(':checked')){
                $("#frm_examen_ni").assert(false,'Debe Seleccionar una Opción');
            }
        }

        if($("#txt_examenes_codigo").val() == "" && $("#descripcion_examen").val() == "" ){
            $("#txt_examenes").assert(false,'Debe indicar una prestacion valida');
        }


        result = $.validity.end();
        if(result.valid==false){
            return false;
        }

        var largoBody2 = $('#tablaContenido >tbody >tr').length;
        var largoBody    = $("#tbody_global_examenes").val();
        let largoBodyNuevo = parseInt(largoBody)+1;
        $("#tbody_global_examenes").val(largoBodyNuevo);
        
        var codigoExamen  = $("#txt_examenes_codigo").val();
        var nombreExamen  = $("#descripcion_examen").val();
        var nombreExamen2 = nombreExamen.trim();
        nombreExamen      = nombreExamen2;


        var tipoSolicitud = "<input type='hidden' id='tipoSolicitud_"+largoBody+"' value='I' />";
        
        if($('input:radio[name=chk_exacon]:checked').val() == 'S'){
            var contraste = "SI";
            var input = "<input type='hidden' id='contraste_"+largoBody+"' value='S' />";
        }else{
            var contraste = "NO";
            var input = "<input type='hidden' id='contraste_"+largoBody+"' value='N' />";
        }

        if($("#frm_plano option:selected").val() ==  0){
            var text_plano = "Sin Lateralidad";
        }else{
            var text_plano = $("#frm_plano option:selected").text();
        }

        if($("#frm_extremidad option:selected").val() ==  0){
            var text_extremidad = "-";
        }else{
            var text_extremidad = $("#frm_extremidad option:selected").text();
        }


        var datos = [];
        datos[0]  = codigoExamen; 
        datos[1]  = $("#tipoExamen_2").val();
        datos[2]  = nombreExamen;
        datos[3]  = $("#frm_examen_obs").val();
        datos[4]  = contraste;
        datos[5]  = input;
        datos[6]  = tipoSolicitud;
        datos[11] = text_plano;
        datos[12] = text_extremidad;
        datos[13] = "<input type='hidden' class='valor_input_lateridad'  id='plano_"+largoBody+"' value='"+$("#frm_plano option:selected").val()+"' />";
        datos[14] = "<input type='hidden' class='valor_input_extremidad' id='extremidad_"+largoBody+"' value='"+$("#frm_extremidad option:selected").val()+"' />";
        datos[15] = "2";
        datos[16] = "-";

        var i     = 7;



        var breakOut = false;
        if (largoBody2 > 0) {
            var consultarGrupo = consultarListaGrupo(datos[0]);
            if(consultarGrupo){
                $("#tablaContenido > tbody > tr").each(function () {
                    var id                      = $(this).find("td").eq(0).html();
                    var valor_input_lateridad   = $(this).find(".valor_input_lateridad").val();
                    var valor_input_extremidad = $(this).find(".valor_input_extremidad").val();
                    if(id == datos[0]){
                        if(valor_input_lateridad == $("#frm_plano option:selected").val() && valor_input_extremidad == $("#frm_extremidad option:selected").val()){
                            modalMensajeBtnExit('ATENCIÓN!', "<b>"+nombreExamen+"</b>, ya se encuentra en la lista", 'modalInformacion', 400, 200, 'warning');
                            breakOut = true; 
                            return false;
                        }
                    }
                });
            }
        }

        contrastesSeleccionados = [];
        $('.clase_contraste').each(function() {

            if ($(this).is(":checked")) {
                contrastesSeleccionados.push($(this).val());
            }
            var valor = $(this).is(":checked") ? "S" : "N";
            var tipo = "";
            console.log("i", i);
            if(i == 11){
                datos[17] = "<input hidden type='" + tipo + "' id='" + $(this).val() + "_" + largoBody + "' value='" + valor + "' />";                // alert(datos[17]) 
            }else if(i == 12){
                datos[18] = "<input hidden type='" + tipo + "' id='" + $(this).val() + "_" + largoBody + "' value='" + valor + "' />";                // alert(datos[17]) 
            }else{
                datos[i] = "<input  hidden type='" + tipo + "' id='" + $(this).val() + "_" + largoBody + "' value='" + valor + "' />";
            }
            i++;
        });
        console.log(contrastesSeleccionados);
        var arrExamen = "<tr id='trEX_"+largoBody+"' style='font-size:13px'>"
                            +"<td class='text-center ima_valorIdPrestacion' id='codExam_"+largoBody+"'    class='verificarGrupo' hidden>"+datos[0]+"</td>"
                            +"<td class='text-center ima_valorTipoExamen' id='tipo_"+largoBody+"'>"+datos[1]+datos[6]+"</td>"
                            +"<td class='text-center ima_valorExamen' id='nomExam_"+largoBody+"'>"+datos[2]+"</td>"
                            +"<td class='ima_valorObservacion' id='obsExam_"+largoBody+"'>"+datos[3]+"</td>"
                            +"<td class='text-center' >"+datos[4]+datos[5]+datos[7]+datos[8]+datos[9]+datos[10]+datos[17]+datos[18]+"</td>"
                            +"<td class='text-center'>"+datos[11]+datos[13]+"</td>"
                            +"<td class='ima_valorLateralidad' hidden >"+text_plano+"</<td>"
                            +"<td class='ima_valorContrastes' hidden>"+contrastesSeleccionados+"</<td>"
                            +"<td  id='bd_"+largoBody+"' hidden >"+datos[15]+"</td>"
                            +"<td  id='id_solicitud_"+largoBody+"' hidden >"+datos[16]+"</td>"
                            +"<td class='text-center'><button type='button' id='"+largoBody+"' class='btn btn-primary btn-sm eliminaExamenTR' value='-' ><i class='fa fa-times' aria-hidden='true'></i></button></td>"
                        +"</tr>";

        var id_paciente     = $('#id_paciente').val();
        var id_paciente_dau = $('#idPacienteDau').val();
        if(!breakOut) { 
            breakOut = false; 
            $("#contenidoRayo").append(arrExamen);
            $("#frm_examen_obs").val("");
            $("#chk_exacon").prop('checked', false);
            $('.clase_contraste').prop('checked', false);
            $("#detalle_examen").val(0);
            $("#inp_detalle_examen").val("");
            $("#detalle_examen_hidden").val("");
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker('render');
            $('.selectpicker').selectpicker('setStyle', 'test', 'add');

            $('#chk_exacon').val('');
            $('.clase_contraste').prop('checked', false);
            $('.clase_contraste').attr('disabled',true);
            $('.chk_exacon').prop('checked', false);

            $("#txt_examenes_codigo").val("");
            $("#descripcion_examen").val("");
            $("#tipoExamen_2").val("");
            $("#conContraste").val("");
            $("#txt_examenes").val("");
            $("#div_exacon").hide();
            $("#div_exacon_hijos").show();
            $("#div_exacon_hijos_espacio").show();
            $("#chk_exacon").prop('checked', false);
            $('.clase_contraste').prop('checked', false);
            $('.clase_contraste').attr('disabled',true);
            $("#Div_plano").show();
            $("#Div_extremidad").hide();

            $("#frm_plano").val(0);
            $("#frm_extremidad").val(0);

            $( "#txt_examenes" ).prop( "disabled", false );

            $("#div_informe_sin_contraste").hide();
            $('#chk_informe_sin_contraste').prop('checked', false);

            $( "#frm_examen_ni" ).attr( "disabled", false );
            $("#div_exacon").hide();
                $("#div_exacon_hijos").hide();
                $("#div_exacon_hijos_espacio").show();
                $("#chk_exacon").prop('checked', false);
                $('.clase_contraste').prop('checked', false);
                $('.clase_contraste').attr('disabled',true);
                $("#Div_plano").hide();

            $("#div_contenido").animate({ scrollTop: $('#div_contenido')[0].scrollHeight}, 1000);
            return false; 
        }
        //VACIAR INPUT
        $("#frm_examen_obs").val("");
        $("#chk_exacon").prop('checked', false);
        $('.clase_contraste').prop('checked', false);
        $("#detalle_examen").val(0);
        $("#inp_detalle_examen").val("");
        $("#detalle_examen_hidden").val("");
        $('.selectpicker').selectpicker('refresh');
        $('.selectpicker').selectpicker('render');
        $('.selectpicker').selectpicker('setStyle', 'test', 'add');

        $('#chk_exacon').val('');
        $('.clase_contraste').prop('checked', false);
        $('.clase_contraste').attr('disabled',true);

        $('.chk_exacon').prop('checked', false);


        $("#txt_examenes_codigo").val("");
        $("#descripcion_examen").val("");
        $("#tipoExamen_2").val("");
        $("#conContraste").val("");
        $("#txt_examenes").val("");

        $("#div_exacon").hide();
        $("#div_exacon_hijos").show();
        $("#div_exacon_hijos_espacio").show();
        $("#chk_exacon").prop('checked', false);
        $('.clase_contraste').prop('checked', false);
        $('.clase_contraste').attr('disabled',true);


        $("#Div_plano").show();
        $("#Div_extremidad").hide();

        $("#frm_plano").val(0);
        $("#frm_extremidad").val(0);

        $( "#txt_examenes" ).prop( "disabled", false );

        $("#div_informe_sin_contraste").hide();
        $('#chk_informe_sin_contraste').prop('checked', false);

        $( "#frm_examen_ni" ).attr( "disabled", false );
        $("#div_exacon").hide();
                $("#div_exacon_hijos").hide();
                $("#div_exacon_hijos_espacio").show();
                $("#chk_exacon").prop('checked', false);
                $('.clase_contraste').prop('checked', false);
                $('.clase_contraste').attr('disabled',true);
                $("#Div_plano").hide();

        $("#div_contenido").animate({ scrollTop: $('#div_contenido')[0].scrollHeight}, 1000);

        
    });


    $("#tablaContenido").on('click', '.eliminaExamenTR', function() {
        var idTR = $(this).attr('id');
        $("#trEX_"+idTR).remove();
    });
 
    $('#frm_Otro').on('click',function(){
        if ($('#frm_Otro').is(':checked')) {
            $('#frm_div_otros').show('fast');
        }else {
            $('#frm_div_otros').hide('fast');
            $("#frm_otros_text").val("")
        }
    });
    
    $("#tipoExamen").change(function(){
        var valor = $("#tipoExamen").val()
        if(valor == "RM"){
            $("#div_marca_paso").show()
        }else{
            $("#div_marca_paso").hide()
        }
    });

});