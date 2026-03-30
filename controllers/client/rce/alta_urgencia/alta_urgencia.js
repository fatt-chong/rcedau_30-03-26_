function seleccionarPrestacionRadio(radio) {
    const filas = document.querySelectorAll('tr');
    filas.forEach(fila => fila.classList.remove('table-success'));
    const trSeleccionado = radio.closest('tr');
    trSeleccionado.classList.add('table-success');
    const prestacionId = radio.dataset.id;
    const prestacionNombre = radio.dataset.nombre;
    const abierto = radio.dataset.abierto;
    $("#frm_hipotesis_final").val(prestacionNombre);
    $("#frm_codigoCIE10").val(prestacionId);
    $("#frm_cie10Abierto").val(abierto);
    checkCodeByAge($('#frm_codigoCIE10').val(), $('#edadPaciente').val());
}
async function checkCodeByAge(id_cie10, age) {
    const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, 'id_cie10='+id_cie10+"&accion=ObtenerCIE10GES", 'POST', 'JSON', 1,'');
    console.log(respuestaAjaxRequest)
    isGes=false;
    var record = respuestaAjaxRequest.respuestaConsulta[0];
    if (respuestaAjaxRequest && respuestaAjaxRequest.respuestaConsulta.length > 0) {
        if(record.Id_cie10 == id_cie10){
            if (record.todos === 'S') {
                 isGes = true;
              }
              if (age <= 5 && record.under5 === 'S') {
                 isGes = true;
              }
              if (age > 65 && record.over65 === 'S') {
                 isGes = true;
              }
        }else{
            isGes = false;
        }
    }else{
        isGes = false;
    }
    const checkbox = document.getElementById('frm_auge');
    if (isGes == true) {
      $('#recetaGes').show();
      $('#PacienteGESReceta').val('S');
      checkbox.checked = true;
      $('#frm_auge').val('S');
    } else {
      $('#recetaGes').hide();
      $('#PacienteGESReceta').val('N');
      checkbox.checked = false;
    }
    if(respuestaAjaxRequest.ges == 'S'){
      $('#cie10Ges').show();
    }else{
      $('#cie10Ges').hide();
    }
}
$(document).ready(function() {
    // checkCodeByAge($('#frm_codigoCIE10').val(), $('#edadPaciente').val());
    // $('#recetaGes').hide();
    console.log($('#frm_codigoCIE10').val())
    if($('#frm_codigoCIE10').val() != " "){
        checkCodeByAge($('#frm_codigoCIE10').val(), $('#edadPaciente').val());
    }

    // checkCodeByAge($('#frm_codigoCIE10').val(), $('#edadPaciente').val());
    //Variables
    var atencion_fecha                     = $('#inpH_atencion_fecha').val(),
        atencion_hora                      = $('#inpH_atencion_hora').val(),
        horaActual                         = $('#inpH_horaActual').val(),
        FechaActual                        = $('#inpH_FechaActual').val(),
        fecha_actual                       = $('#fecha_actual').val(),
        fecha_admision                     = $('#fecha_admision').val(),
        derivacion                         = $('#frm_alta_derivacion').val(),
        $frmEspecialidad                   = $('#frm_especialidad'),
        dau_id                             = $('#dau_id').val(),
        rce_id                             = $('#rce_id').val(),
        paciente_id                        = $('#paciente_id').val(),
        edadPaciente                       = $('#edadPaciente').val(),
        $divViolencias                     = $("#divViolencias"),
        $existeViolencia                   = $("#slc_existeViolencia"),
        $tipoViolencia                     = $("#frm_tipoViolencia"),
        $divTipoAgresor                    = $('#tipoAgresor'),
        $tipoAgresor                       = $('#frm_tipoAgresor'),
        $tipoLesionVictima                 = $("#frm_tipoLesionVictima"),
        $sospechaPenetracion               = $("#frm_tipoSospechaPenetracion"),
        $profilaxis                        = $("#frm_profilaxis"),
        $victimaEmbarazada                 = $("#frm_victimaEmbarazada"),
        $peritoSexual                      = $("#frm_peritoSexual"),
        $claseViolenciasNoAuntoinfringidas = $(".violenciaNoAutoinfringidas"),
        $claseViolenciasVIFONoVIF          = $(".violenciasVIFONoVIF"),
        $claseViolenciaSexual              = $(".violenciaSexual"),
        $claseVictimaEmbarazada            = $(".victimaEmbarazada"),
        divAQuien                          = "#divAQuienSeEntregaInformacion",
        $seEntregaInfo                     = $("#frm_entregaInformacion"),
        $aQuien                            = $("#frm_aQuienSeEntregaInformacion"),
        $divSeguimientoPaciente            = $("#divSeguimientoPaciente"),
        $seguimientoPaciente               = $("#frm_seguimientoPaciente"),
        idBotonPlantilla                   = '',
        funcionPlantilla                   = function(){},
        tituloPlantilla                    = '',
        autoInfringidas                    = 1,
        otrasViolencias                    = 2,
        sexual                             = 3,
        VIF                                = 4;
        actualizarSeguimiento              = false;
        tipoMapa                           = $('#tipoMapa').val();

    //Validaciones
    validar("#frm_fecha_defuncion","fecha");
    validar("#frm_hipotesis_final","letras_numeros");
    validar("#frm_otrosMotivoConsulta","letras_numeros");
    validar("#frm_aQuienSeEntregaInformacion","letras_numeros");
    validar("#frm_cie10Abierto","letras_numeros");

    $('#selectIndicacionEgresoEspecialista').hide();
    $('#otrosMotivoConsulta').hide();
    $('#frm_control_form').hide();
    $('#frm_especialidad_oculto').hide();
    $('#frm_aps_oculto').hide();
    $('#frm_otros_oculto').hide();
    $('#frm_servicio_destino_oculto').hide();
    $('#frm_destino').hide();
    $('#divViolencias').hide();
    $('#tipoAgresor').hide();
    $('#tipoLesiones').hide();
    $('#sospechaPenetracion').hide();
    $('#profilaxis').hide();
    $('#victimaEmbarazada').hide();
    $('#peritoSexual').hide();
    $('#tipoViolencias').hide();

    $("#selectSegunIndicacionEgreso").show("slow");
        $frmEspecialidad.selectpicker({
        size: 8,
        noneSelectedText : 'Seleccione'
    });
    $(".formulariosEnfermeria").click(function(){
        var frm_codigoCIE10Ges = $('#frm_codigoCIE10').val();
        var frm_hipotesis_finalGes = $('#frm_hipotesis_final').val();
        modalFormulario_noCabecera('', raiz+"/views/modules/formularios/contenido_formularios.php", 'dau_id='+dau_id+'&frm_codigoCIE10Ges='+frm_codigoCIE10Ges+'&frm_hipotesis_finalGes='+frm_hipotesis_finalGes, "#modalFormulariosEnfermeria", "modal-lgg", "", "fas fa-plus",'');
        
        // modalFormulario_noCabecera('', raiz+"/views/modules/formularios/contenido_formularios.php", `dau_id=${dau_id}`, "#modalFormulariosEnfermeria", "modal-lgg", "", "fas fa-plus",'');
    });
    if ( $frmEspecialidad.val() != null && $frmEspecialidad.val() != undefined && $frmEspecialidad.val() != 0 ){
        $('#selectIndicacionEgresoEspecialista').show();
        $('#slc_prioridad').val($('#idPrioridad').val());
        $('#slc_motivoConsulta').val($('#idMotivoConsulta').val());
        if ( $('#idMotivoConsulta').val() == 5 ) {
            $('#otrosMotivoConsulta').show();
            $('#frm_otrosMotivoConsulta').val($('#otrosMotivos').val());
        }
    }

    $("#frm_hora_date").attr({
        "max" : horaActual,
        "min" : '00:00'
    });

    $("#frm_fecha_date").change(function(){
        var fecha = $('#frm_fecha_date').val();
        if ( fecha >  atencion_fecha && fecha < FechaActual) {
            $("#frm_hora_date").attr({
                "max" : '23:59',
                "min" : '00:00'
            });
        } else if ( fecha == atencion_fecha ) {
            $("#frm_hora_date").val(atencion_hora);
            if ( atencion_fecha == FechaActual ) {
                $("#frm_hora_date").attr({
                    "max" : horaActual,
                    "min" : atencion_hora
                });
            } else {
                $("#frm_hora_date").attr({
                    "max" : '23:59',
                    "min" : atencion_hora
                });
            }
        } else if ( fecha == FechaActual ) {
            $("#frm_hora_date").val(horaActual);
            $("#frm_hora_date").attr({
                "max" : horaActual,
                "min" : '00:00'
            });
        }
    });



    $("#frm_hora_date").keypress(function(e){
        if ( e.keyCode == 13 ) {

            cambiarFormaDigitacionHora('frm_hora_date');

            let fecha         = $('#frm_fecha_date').val();

            let hora          = $('#frm_hora_date').val();

            if ( fecha == FechaActual && hora > horaActual ) {

                $("#frm_hora_date").val(horaActual);

            } else if ( fecha == atencion_fecha && hora < atencion_hora ) {

                $("#frm_hora_date").val(atencion_hora);

            }
        }
    });



    $("#frm_hora_date").change(function(e){

        cambiarFormaDigitacionHora('frm_hora_date');

        let fecha         = $('#frm_fecha_date').val();

        let hora          = $('#frm_hora_date').val();

        if ( fecha == FechaActual && hora > horaActual ) {

            $("#frm_hora_date").val(horaActual);

        } else if ( fecha == atencion_fecha && hora < atencion_hora ) {

            $("#frm_hora_date").val(atencion_hora);

        }
    });



    //Rescatar texto de indicación de egreso y asignarlo a campo oculto
    let comboIndicacionEgreso   = document.getElementById("frm_Indicacion_Egreso");
    descripcionIndicacionEgreso = comboIndicacionEgreso.options[comboIndicacionEgreso.selectedIndex].text;
    if ( descripcionIndicacionEgreso == "Seleccione" ) {
        descripcionIndicacionEgreso = "";
    }
    $('#descripcionIndicacionEgreso').val(descripcionIndicacionEgreso);

    //Rescatar texto de servicio de destino y asignarlo a campo oculto
    let comboServicioDestinos   = document.getElementById("frm_servicio_destino");
    descripcionServicioDestinos = comboServicioDestinos.options[comboServicioDestinos.selectedIndex].text;
    if ( descripcionServicioDestinos == "Seleccione" ) {
        descripcionServicioDestinos = "";
    }
    $('#descripcionServicioDestinos').val(descripcionServicioDestinos);

    //Rescatar texto de destino y asignarlo a campo oculto
    let comboAltaDestinos   = document.getElementById("frm_alta_derivacion");
    descripcionAltaDestinos = comboAltaDestinos.options[comboAltaDestinos.selectedIndex].text;
    if ( descripcionAltaDestinos == "Seleccione" ) {
        descripcionAltaDestinos = "";
    }
    $('#descripcionAltaDestinos').val(descripcionAltaDestinos);

    //Rescatar texto de APS y asignarlo a campo oculto
    let comboAltaAps    = document.getElementById("frm_aps");
    descripcionAltaAps  = comboAltaAps.options[comboAltaAps.selectedIndex].text;
    if ( descripcionAltaAps == "Seleccione" ){
        descripcionAltaAps = "";
    }



    function sacarCheck(){
        $('input[type="radio"]').prop('checked', false);
    }



    $('input[name="frm_auge"]').on('change', function(){

        if($(this).is(':checked')) {
            $(this).val('S');
        } else {
            $(this).val('N');
        }
    });



    $('input[name="frm_pertinencia"]').on('change', function(){

        if($(this).is(':checked')) {
            $(this).val('S');
        } else {
            $(this).val('N');
        }
    });



    $('input[name="frm_postinor"]').on('change', function(){

        if($(this).is(':checked')) {
            $(this).val('S');
        } else {
            $(this).val('N');
        }
    });



    $('input[name="frm_hepatitisB"]').on('change', function(){

        if($(this).is(':checked')) {
            $(this).val('S');
        } else {
            $(this).val('N');
        }
    });


    $("#frm_hipotesis_final").autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
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
            $("#frm_hipotesis_final").val(ui.item.nomcompletoCIE);
            $("#frm_codigoCIE10").val(ui.item.id);
            checkCodeByAge(ui.item.id, $('#edadPaciente').val());


            // checkGesDau(ui.item.id, $('#paciente_id').val(), $('#dau_id').val() );

            const filas = document.querySelectorAll('tr');
            filas.forEach(fila => fila.classList.remove('table-success'));
        },
        open: function(){

            const filas = document.querySelectorAll('tr');
            filas.forEach(fila => fila.classList.remove('table-success'));
            $('.ui-menu').css( "font-weight" );

            $('.ui-menu').addClass( "col-md-12" );
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

            const filas = document.querySelectorAll('tr');
            filas.forEach(fila => fila.classList.remove('table-success'));
            const radios = document.querySelectorAll('input[name="select_prestacion"]');
            radios.forEach(radio => (radio.checked = false));
        // $(this).autocomplete("search", '');
    });

    $('#frm_fecha_defuncion').datetimepicker({
        language: 'es',
        todayHighlight: true,
        autoclose: true,
        clearBtn: true,
        minDate: fecha_admision,
        maxDate: fecha_actual
    });


    if ( $('#frm_Indicacion_Egreso').val() == 3 ) {
       $("#frm_control_form").show();
        if ( derivacion == 2 ) {
            $('#frm_especialidad_oculto').show();
        } else if ( derivacion == 3 ) {
            $('#frm_aps_oculto').show();
        } else if ( derivacion == 5 ) {
            $('#frm_otros_oculto').show();
        }
    }



    // if ( $('#frm_Indicacion_Egreso').val() == 4 ) {
    //     $("#frm_servicio_destino_oculto").show("fast");
    // } else {
    //     $("#frm_servicio_destino_oculto").hide("fast");
    // }
    if ( $('#frm_Indicacion_Egreso').val() == 4 ) {
        $("#frm_servicio_destino_oculto").show("fast");
        $divSeguimientoPaciente.show("fast");
    } else {
        $("#frm_servicio_destino_oculto").hide("fast");
        $divSeguimientoPaciente.hide("fast");
    }



    if ( $('#frm_Indicacion_Egreso').val() == 6 ) {
        $("#frm_defuncion_Fecha").show();
        $('#frm_destino').show();
    } else {
        $("#frm_defuncion_Fecha").hide();
        $('#frm_destino').hide();
    }



    $("#frm_Indicacion_Egreso").change(function(){
        $('#selectIndicacionEgresoEspecialista').hide("slow");

        if ( $('#frm_Indicacion_Egreso option:selected').val() == 3 ) {
            $("#frm_control_form").show("slow");
            $('#frm_alta_derivacion').prop('selectedIndex',0);
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            sacarCheck();
        } else {
            if ( $('#frm_Indicacion_Egreso option:selected').val() != 3 ) {
                $('#frm_alta_derivacion').prop('selectedIndex',0);
                $("#frm_control_form").hide("slow");
                $("#frm_especialidad_oculto").hide("slow");
                $frmEspecialidad.val('default');
                $frmEspecialidad.selectpicker("refresh");
                $('#frm_otros_oculto').hide("slow");
                $('#frm_especialidad_oculto').hide("slow");
                $('#frm_aps_oculto').hide("slow");
                // $('#frm_aps').prop('selectedIndex',0);
                $('#frm_otros').val("");
                sacarCheck();
            }
        }

        if ( $('#frm_Indicacion_Egreso option:selected').val() == 4 ) {
            $("#frm_servicio_destino_oculto").show("slow");
            $('#frm_servicio_destino').prop('selectedIndex',0);
            $divSeguimientoPaciente.show("fast");
        } else {
            if ( $('#frm_Indicacion_Egreso option:selected').val() != 4 ) {
                $("#frm_servicio_destino_oculto").hide("slow");
                $('#frm_servicio_destino').prop('selectedIndex',0);
                $divSeguimientoPaciente.hide("fast");
            }
        }

        if ( $('#frm_Indicacion_Egreso option:selected').val() == 6 ) {
            $("#frm_defuncion_Fecha").show("slow");
            $("#frm_fecha_defuncion").val("");
            $('#frm_fecha_defuncion').val(fecha_actual);
            $("#frm_destino").show("slow");
            sacarCheck();
        } else {
            if ( $('#frm_Indicacion_Egreso option:selected').val() != 6 ) {
                $("#frm_defuncion_Fecha").hide("slow");
                $("#frm_fecha_defuncion").val("");
                $("#frm_destino").hide("slow");
                sacarCheck();
            }
        }

        //Rescatar texto de indicación de egreso y asignarlo a campo oculto
        let comboIndicacionEgreso = document.getElementById("frm_Indicacion_Egreso");
        descripcionIndicacionEgreso = comboIndicacionEgreso.options[comboIndicacionEgreso.selectedIndex].text;
        $('#descripcionServicioDestinos').val("");
        $('#descripcionAltaDestinos').val("");
        $('#descripcionAltaEspecialidad').val("");
        $('#descripcionAltaAps').val("");
        $('#destinoDefuncion').val("");
        $('#fechaDefuncion').val("");
        $('#descripcionIndicacionEgreso').val(descripcionIndicacionEgreso);

    });



    $("#frm_alta_derivacion").change(function(){

        let combo = $('#frm_alta_derivacion option:selected').val();

        $('#selectIndicacionEgresoEspecialista').hide("slow");

        if ( $('#frm_alta_derivacion option:selected').val() == 1 ) {
            $('#frm_especialidad_oculto').hide("slow");
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            $('#frm_aps_oculto').hide("slow");
            // $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        } else if ( $('#frm_alta_derivacion option:selected').val() == 2 ) {
            $('#frm_especialidad_oculto').show("slow");
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            $('#frm_otros_oculto').hide("slow");
            $('#frm_aps_oculto').hide("slow");
            // $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros').val("");
        } else if ( $('#frm_alta_derivacion option:selected').val() == 3 ) {
            $('#frm_aps_oculto').show("slow");
            // $('#frm_aps').prop('selectedIndex',0);
            $('#frm_especialidad_oculto').hide("slow");
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        } else if ( $('#frm_alta_derivacion option:selected').val() == 4 ) {
            $('#frm_especialidad_oculto').hide("slow");
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            $('#frm_aps_oculto').hide("slow");
            // $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros_oculto').hide("slow");
            $('#frm_otros').val("");
        } else if ( $('#frm_alta_derivacion option:selected').val() == 5 ) {
            $('#frm_otros_oculto').show("slow");
            $('#frm_especialidad_oculto').hide("slow");
            $frmEspecialidad.val('default');
            $frmEspecialidad.selectpicker("refresh");
            $('#frm_aps_oculto').hide("slow");
            // $('#frm_aps').prop('selectedIndex',0);
            $('#frm_otros').val("");
        }

         //Rescatar texto de destino y asignarlo a campo oculto
         let comboAltaDestinos = document.getElementById("frm_alta_derivacion");
         descripcionAltaDestinos = comboAltaDestinos.options[comboAltaDestinos.selectedIndex].text;
         if(descripcionAltaDestinos == "Domicilio" || descripcionAltaDestinos == "Derivado Ginecologia Urgencia"){
             $('#descripcionServicioDestinos').val("");
             $('#descripcionAltaEspecialidad').val("");
             $('#descripcionAltaAps').val("");
         }
         $('#descripcionAltaDestinos').val(descripcionAltaDestinos);

    });



    //Rescatar texto de servicio de destino y asignarlo a campo oculto
    $("#frm_servicio_destino").change(function(){
        let comboServicioDestinos = document.getElementById("frm_servicio_destino");
        descripcionServicioDestinos = comboServicioDestinos.options[comboServicioDestinos.selectedIndex].text;
        $('#descripcionServicioDestinos').val(descripcionServicioDestinos);
    });



    //Rescatar texto de especialidad y asignarlo a campo oculto
    $frmEspecialidad.change(function(){
        let arrayTextoOpcionSeleccionada = [];
        $.each($(".selectpicker option:selected"), function(){
            if ( $.inArray($(this).text(), arrayTextoOpcionSeleccionada) === -1 ) {
                arrayTextoOpcionSeleccionada.push($(this).text());
            }
        });
        arrayTextoOpcionSeleccionada = JSON.stringify(arrayTextoOpcionSeleccionada);
        $('#descripcionAltaEspecialidad').val(arrayTextoOpcionSeleccionada);
    });



    //Rescatar texto de APS y asignarlo a campo oculto
    $("#frm_aps").change(function(){
        let comboAltaAps = document.getElementById("frm_aps");
        descripcionAltaAps = comboAltaAps.options[comboAltaAps.selectedIndex].text;
        $('#descripcionAltaAps').val(descripcionAltaAps);
    });



    //Rescatar destino de defunción y asignarlo a campo oculto
    $("input:radio").click(function() {
        let descripcionLabel = this.parentElement.outerText;
        $('#destinoDefuncion').val(descripcionLabel);
        $('#fechaDefuncion').val($("#frm_fecha_defuncion").val());
     } );



     $("#btnGrabarEgreso").click(function() {
        if ( ! verificarDatosAltaUrgencia() ) {
            return;
        }
        if ( ! verificarDatosViolencia() ) {
            return;
        }

         modalConfirmacionNuevo("ATENCIÓN", "ATENCIÓN, se procederá a registrar la indicacion de egreso, <b>¿Desea continuar?</b>", "primary", confirmarDarAltaUrgencia);

    });


     $('#modalPlantillaAltaUrgencia').click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_ind');
        if (estadoPermiso) {
            const botones =   [
                                { id: 'crearPlantillaAltaUrgencia', value: 'Crear Plantilla Alta Urgencia', function: plantillaAltaUrgencia, class: 'btn btn-primary' }
                              ];
            modalFormulario("<label class='mifuente ml-2'>Crear Plantilla Indicaciones</label>", `${raiz}/views/modules/rce/plantillas/modalNombrePlantilla.php`, 'rce=S', "#modalNombrePlantilla", "modal-lg", "light",'', botones);
        }
    });
     $('#modalUpdatePlantillaAltaUrgencia').click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_ind');
        const parametros = { 'idCie10' : $('#frm_codigoCIE10').val(), 'slc_nombrePlantilla' :  $('#slc_nombrePlantilla').val(), 'cie10Abierto' :  $('#frm_cie10Abierto').val(), 'indicaciones' :  $('#frm_indicaciones_alta').val(), 'idPronostico' : $('#frm_pronostico option:selected').val(), 'idIndicacionEgreso' :  $('#frm_Indicacion_Egreso option:selected').val(), 'nombrePlantilla' : $('#input_nombrePlantilla').val(), 'accion' : 'UpdatePlantillaAltaUrgencia' };
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, parametros, 'POST', 'JSON', 1,'');
        switch(respuestaAjaxRequest.status) {
            case "success":
                $('#slc_nombrePlantilla').append($('<option>', {
                    value: respuestaAjaxRequest.idPlantilla,
                    text: parametros.nombrePlantilla
                }));
                $(`select option[value='${respuestaAjaxRequest.idPlantilla}']`).attr("selected","selected");
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Plantilla Creada </h4>  <hr>  <p class="mb-0">Plantilla actualizada satisfactoriamente.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success"); 
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );

            break;

        }
    });
     $('#modalEliminarPlantillaAltaUrgencia').click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_ind');
        const parametros = { 'idCie10' : $('#frm_codigoCIE10').val(), 'slc_nombrePlantilla' :  $('#slc_nombrePlantilla').val(), 'cie10Abierto' :  $('#frm_cie10Abierto').val(), 'indicaciones' :  $('#frm_indicaciones_alta').val(), 'idPronostico' : $('#frm_pronostico option:selected').val(), 'idIndicacionEgreso' :  $('#frm_Indicacion_Egreso option:selected').val(), 'nombrePlantilla' : $('#input_nombrePlantilla').val(), 'accion' : 'EliminarPlantillaAltaUrgencia' };
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, parametros, 'POST', 'JSON', 1,'');
        switch(respuestaAjaxRequest.status) {
            case "success":
                const valorSeleccionado = $('#slc_nombrePlantilla').val();

                // Elimina la opción correspondiente si existe
                if (valorSeleccionado) {
                    $(`#slc_nombrePlantilla option[value="${valorSeleccionado}"]`).remove();
                }
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );

            break;

        }
    });
    // $('#modalPlantillaAltaUrgencia').on('click', function(){

    //     idBotonPlantilla = 'crearPlantillaAltaUrgencia';

    //     funcionPlantilla = plantillaAltaUrgencia;

    //     tituloPlantilla  = 'Crear Plantilla Alta Urgencia';

    //     modalCrearPlantilla();

    // });



    $frmEspecialidad.on('change', function(){

        if ( $('#frm_especialidad option:selected').length == 0 ) {

            $('#selectIndicacionEgresoEspecialista').hide();

        } else {

            $('#selectIndicacionEgresoEspecialista').show('slow');

        }

    });



    $('#slc_motivoConsulta').on('change', function(){

        if ( $('#slc_motivoConsulta').val() == 5 ) {

            $('#otrosMotivoConsulta').show('slow');

        } else {

            $('#otrosMotivoConsulta').hide('slow');

        }

    });



    $existeViolencia.on('change', function(){

        $divViolencias.hide(200);

            $('#tipoViolencias').hide(200);

        vaciarSelectsViolencia();

        esconderOpcionesViolenciaNoAutoinfringidas();

        if ( $existeViolencia.val() == 'S' ) {

            $divViolencias.show(200);
            $('#tipoViolencias').show(200);

        }

        if ( $existeViolencia.val() == 'N' ) {

            $tipoViolencia.val(0);

        }

    });



    $tipoViolencia.on('change', function(){

        const idTipoViolencia = $tipoViolencia.val();

        if ( idTipoViolencia == autoInfringidas ) {

            esconderOpcionesViolenciaNoAutoinfringidas();

            return;

        }

        vaciarSelectsViolencia();

        rellenarSelectTipoAgresor(idTipoViolencia);

        desplegarLesionesDeVictima(idTipoViolencia);

        desplegarSospechaPenetracion(idTipoViolencia);

        desplegarVictimaEmbarazada();

    });



    if ( $("#hiddenExisteViolencia").val() == 'S' ) {

        $existeViolencia.val($("#hiddenExisteViolencia").val()).trigger("change");

        $tipoViolencia.val($("#hiddenTipoViolencia").val()).trigger("change");

        $tipoAgresor.val($("#hiddenTipoAgresor").val());

        $tipoLesionVictima.val($("#hiddenTipoLesionVictima").val());

        $sospechaPenetracion.val($("#hiddenSospechaPenetracion").val());

        $profilaxis.val($("#hiddenProfilaxis").val());

        $victimaEmbarazada.val($("#hiddenVictimaEmbarazada").val());

        $peritoSexual.val($("#hiddenPeritoSexual").val());

    }



    function verificarDatosAltaUrgencia ( ) {
        $.validity.start();
        if( $('#frm_fecha_date').val() == "" ) {
			$('#frm_fecha_date').assert(false,'Debe Indicar la fecha de egreso');
		}
		if( $('#frm_hora_date').val() == "" || $('#frm_hora_date').val() == 'HH:MM' ) {
			$('#frm_hora_date').assert(false,'Debe Indicar la hora de egreso');
		}
        if ( ($('#frm_hipotesis_final').val() == "" || $('#frm_codigoCIE10').val() == "" ) && banderapiso === 'RCE' ) {
            $('#frm_hipotesis_final').assert(false,'Debe Ingresar CIE10');
        }
        if ( $('#frm_indicaciones_alta').val() == "" ) {
            $('#frm_indicaciones_alta').assert(false,'Debe Ingresar Indicaciones');
        }
        if ( $('#frm_pronostico option:selected').val() == "" ) {
            $('#frm_pronostico').assert(false,'Debe Seleccionar algun pronostico');
        }
        if ( $('#frm_Indicacion_Egreso option:selected').val() == "" ) {
            $('#frm_Indicacion_Egreso').assert(false,'Debe Seleccionar indicación de egreso');
            $.validity.end();
            return false;
        } else if ( $('#frm_Indicacion_Egreso option:selected').val() == 3 ) {
            if( $('#frm_alta_derivacion option:selected').val() == "0" ) {
                $('#frm_alta_derivacion').assert(false,'Debe Seleccionar alguna derivacion');
            } else if ( $('#frm_alta_derivacion option:selected').val() == 2 ) {
                if ( $('#frm_especialidad option:selected').length == 0 ) {
                    $frmEspecialidad.assert(false,'Debe Seleccionar alguna especialidad');
                }
            } else if ( $('#frm_alta_derivacion option:selected').val() == 3 ) {
                if( $('#frm_aps option:selected').val() == "0" ) {
                    $('#frm_aps').assert(false,'Debe Seleccionar algun APS');
                }
            } else if ( $('#frm_alta_derivacion option:selected').val() == 5 ) {
                if ( $('#frm_otros').val() == "" ) {
                    $('#frm_otros').assert(false,'Debe indicar alguna informacion');
                }
            }
        } else if ( $('#frm_Indicacion_Egreso option:selected').val() == 4 ) {
            if ( $('#frm_servicio_destino option:selected').val() == "0" ) {
                $('#frm_servicio_destino').assert(false,'Debe Seleccionar algun destino');
            }
        } else if ( $('#frm_Indicacion_Egreso option:selected').val() == 6 ) {
            if ( $('#frm_fecha_defuncion').val() == "" ) {
                $('#frm_fecha_defuncion').require("debe ingresar fecha de defuncion");
            } else if ( $("input[name='frm_destino_defuncion']:checked").length <= 0 ) {
                $('#frm_destino_defuncion').assert(false,'Debe Seleccionar alguna opcion');
            }
        }
        if ( $('#frm_alta_derivacion').val() == 2 ) {
            if ( $('#slc_prioridad').val() == null ) {
                $('#slc_prioridad').assert(false,'Debe Seleccionar alguna opción');

            }
            if ( $('#slc_motivoConsulta').val() == null ) {
                $('#slc_motivoConsulta').assert(false,'Debe Seleccionar alguna opción');
            }
            if ( $('#slc_motivoConsulta').val() == 5 && $('#frm_otrosMotivoConsulta').val() == '' ) {
                $('#frm_otrosMotivoConsulta').assert(false,'Debe Ingresar Otros Motivos');
            }
        }
        if ( $seEntregaInfo.val() === null || $seEntregaInfo.val() === undefined || Number($seEntregaInfo.val()) === 0 ) {
            $('#frm_entregaInformacion').assert(false,'Debe Seleccionar si se ingresó información');
        }
        if ( $('#frm_nombreFamiliarOAcompaniante').val() == null || $('#frm_nombreFamiliarOAcompaniante').val() == '' ) {
            $('#frm_nombreFamiliarOAcompaniante').assert(false,'Debe Ingresar nombre familar o acompañante');
        }

        if ( String($seEntregaInfo.val()) === "S" && ($aQuien.val() === undefined || $aQuien.val() === null || String($aQuien.val()) === "") ) {
            $('#frm_aQuienSeEntregaInformacion').assert(false,"Debe ingresar ¿a quién?");
        }
        result = $.validity.end();
        return result.valid;
    }
    async function confirmarDarAltaUrgencia ( ) {
        const estadoPermiso = await validarPermisoUsuario('btn_ind');
        if (estadoPermiso) {
            const estadoValido = await pacienteYaConNEA(dau_id,tipoMapa);
            if (estadoValido) {

                if ( ! tiempoPermitidoTranscurridoDesdeIndicacionEgreso(dau_id, tipoMapa) ) {
                    $('#modalAltaUrgencia').modal( 'hide' ).data( 'bs.modal', null );
                    return;
                }
                if ( pacienteEgresado() ) {

                    $('#modalAltaUrgencia').modal( 'hide' ).data( 'bs.modal', null );
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error! </h4>  <hr>  <p class="mb-0">Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona o DAU Automático), no se puede indicar egreso.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
                    return;

                }
                const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, $("#frmIndicacionEgreso").serialize()+`&frm_especialidad=${JSON.stringify($frmEspecialidad.val())}&dau_id=${dau_id}&paciente_id=${paciente_id}&accion=registrarIndicacionEgreso`, 'POST', 'JSON', 1,'Indicando Alta Urgencia...');
                switch ( respuestaAjaxRequest.status ) {
                    case "success":
                    
                        $('#modalAltaUrgencia').modal( 'hide' ).data( 'bs.modal', null );
                        ajaxContentFast(`${raiz}/views/modules/rce/medico/rce.php`,'tipoMapa='+tipoMapa+`&dau_id=${dau_id}`, '#contenido');
                        if ( $("#frm_Indicacion_Egreso").val() == 3 ) {
                            imprimirRCE();
                        }
                        // alert($('#PacienteGESReceta').val());
                        if($('#PacienteGESReceta').val()=='S'){
                           const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,'idDau='+dau_id+'&accion=obtenerDetalleRecetaGES','POST','JSON',1,'');
                            console.log(respuestaAjaxRequest);
                            if (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null){
                                recetaGES = respuestaAjaxRequest;
                            }else{
                                recetaGES = [];
                            }
                            console.log(recetaGES);
                            const idRecetaGES = recetaGES[0]?.idRecetaGES ?? 0;
                            if (pacienteEgresado()) {
                                modalImprimirRecetaGES(dau_id, idRecetaGES);
                                return;
                            }
                            const botones = [{
                                id: 'btnIngresarRecetaGES',
                                value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar',
                                class: 'btn btn-primary'
                            }];
                            const existeRecetaGES = idRecetaGES !== undefined && idRecetaGES !== null && idRecetaGES !== 0;
                            if (existeRecetaGES) {
                                botones.push({
                                    id: 'btnDesplegarPDFRecetaGES',
                                    value: '<i class="glyphicon glyphicon-file" aria-hidden="true"></i> PDF',
                                    function: () => modalImprimirRecetaGES(dau_id, idRecetaGES),
                                    class: 'btn btn-primary'
                                });
                            }  
                            modalFormulario("<label class='mifuente ml-2'>Receta GES</label>", `${raiz}/views/modules/rce/rce/recetaGES.php`, `idPaciente=${paciente_id}&idRCE=${rce_id}&idDau=${dau_id}`, "#recetaGES", "modal-lg", "light",'', botones);

                        }
                        // const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,'idDau='+dau_id+'&accion=obtenerDetalleRecetaGES','POST','JSON',1,'');
                        // console.log(respuestaAjaxRequest);
                        // if (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null){
                        //     recetaGES = respuestaAjaxRequest;
                        // }else{
                        //     recetaGES = [];
                        // }
                        // console.log(recetaGES);
                        // const idRecetaGES = recetaGES[0]?.idRecetaGES ?? 0;
                        // if (pacienteEgresado()) {
                        //     modalImprimirRecetaGES(dau_id, idRecetaGES);
                        //     return;
                        // }
                        // const botones = [{
                        //     id: 'btnIngresarRecetaGES',
                        //     value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar',
                        //     class: 'btn btn-primary'
                        // }];
                        // const existeRecetaGES = idRecetaGES !== undefined && idRecetaGES !== null && idRecetaGES !== 0;
                        // if (existeRecetaGES) {
                        //     botones.push({
                        //         id: 'btnDesplegarPDFRecetaGES',
                        //         value: '<i class="glyphicon glyphicon-file" aria-hidden="true"></i> PDF',
                        //         function: () => modalImprimirRecetaGES(dau_id, idRecetaGES),
                        //         class: 'btn btn-primary'
                        //     });
                        // }
                        // modalFormulario("<label class='mifuente ml-2'>Receta GES</label>", `${raiz}/views/modules/rce/rce/recetaGES.php`, `idPaciente=${idPaciente}&idRCE=${rce_id}&idDau=${dau_id}`, "#recetaGES", "modal-lg", "light",'', botones);


                    break;
                    case "warning":
                        var recargarDetalle_dau = function(){
                            ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
                        }
                        $('#modalAltaUrgencia').modal( 'hide' ).data( 'bs.modal', null );
                        texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ¡¡¡ ATENCION !!! </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</b></p></div>';
                        modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", recargarDetalle_dau);

                    break;
                    default:
                        ErrorSistemaDefecto();
                    break;
                }
            }
        }
    }





    // function modalCrearPlantilla ( ) {

    //     const botones =   [
    //                         { id: idBotonPlantilla, value: 'Crear', function: funcionPlantilla, class: 'btn btn-primary' }
    //                       ];

    //     modalFormulario(tituloPlantilla, `${raiz}/views/modules/rce/plantillas/modalNombrePlantilla.php`, '', '#modalNombrePlantilla', '30%', '35%', botones);

    // }



    function plantillaAltaUrgencia ( ) {

        if ( ! verificacionDatosAltaUrgencia() ) {

            return;

        }

        if ( ! verificacionNombrePlantillaAltaUrgencia() ) {

            return;

        }
        crearPlantillaAltaUrgencia();

    }



    function verificacionDatosAltaUrgencia ( ) {

        let camposFaltantes = '';

        let banderaNoError = true;

		if ( $('#frm_hipotesis_final').val() == '' || $('#frm_codigoCIE10').val() == '' ) {

            camposFaltantes += "<br> - Hipótesis Final";

            banderaNoError = false;

        }

        if ( $('#frm_indicaciones_alta').val() == '' ) {

            camposFaltantes += "<br> - Indicaciones";

            banderaNoError = false;

        }

        if ( $('#frm_pronostico option:selected').val() == '' ) {

            camposFaltantes += "<br> - Pronóstico";

            banderaNoError = false;

        }

        if ( $('#frm_Indicacion_Egreso option:selected').val() == '' ) {

            camposFaltantes += "<br> - Indicación de Egreso";

            banderaNoError = false;

        }

		if ( banderaNoError === false ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en crear Plantilla Alta Urgencia</h4>  <hr>  <p class="mb-0">`Debe rellenar los campos necesarios para poder crear la planilla: <br>'+camposFaltantes+'.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");



            // modalMensajeBtnExit('Error en crear Plantilla Alta Urgencia', `Debe rellenar los campos necesarios para poder crear la planilla: <br>${camposFaltantes}`, "error_crear_plantilla_alta_urgencia", 500, 300, 'danger');

            $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );

			return false;

		}

		return true;

    }



    function verificacionNombrePlantillaAltaUrgencia ( ) {

        if ( $('#input_nombrePlantilla').val() == '' ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en crear Plantilla Alta Urgencia </h4>  <hr>  <p class="mb-0">Debe rellenar el campo de Nombre de Plantilla.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");


            // modalMensajeBtnExit('Error en crear Plantilla Alta Urgencia','Debe rellenar el campo de Nombre de Plantilla', "error_crear_plantilla_alta_urgencia", 500, 300, 'danger');

            return false;

        }

        return true;

    }



    function crearPlantillaAltaUrgencia ( ) {
        const parametros = { 'idCie10' : $('#frm_codigoCIE10').val(), 'cie10Abierto' :  $('#frm_cie10Abierto').val(), 'indicaciones' :  $('#frm_indicaciones_alta').val(), 'idPronostico' : $('#frm_pronostico option:selected').val(), 'idIndicacionEgreso' :  $('#frm_Indicacion_Egreso option:selected').val(), 'nombrePlantilla' : $('#input_nombrePlantilla').val(), 'accion' : 'crearPlantillaAltaUrgencia' };
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, parametros, 'POST', 'JSON', 1,'');
        switch(respuestaAjaxRequest.status) {
            case "success":
                $('#slc_nombrePlantilla').append($('<option>', {
                    value: respuestaAjaxRequest.idPlantilla,
                    text: parametros.nombrePlantilla
                }));
                $(`select option[value='${respuestaAjaxRequest.idPlantilla}']`).attr("selected","selected");
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Plantilla Creada </h4>  <hr>  <p class="mb-0">Plantilla creada satisfactoriamente.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success"); 
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );
            break;
            default:
                ErrorSistemaDefecto();
                $('#modalNombrePlantilla').modal( 'hide' ).data( 'bs.modal', null );

            break;

        }

    }


	$("#slc_nombrePlantilla").on('change', function() {

	    if ( $("#slc_nombrePlantilla").val() == '' ) {

            $("#frm_hipotesis_final").val('');

			$("#frm_codigoCIE10").val('');

            $("#frm_cie10Abierto").val('');

            $('#frm_indicaciones_alta').val('');

            $('#frm_pronostico').prop('selectedIndex',0);

            $('#frm_Indicacion_Egreso').prop('selectedIndex',0);

            $("#selectSegunIndicacionEgreso").hide("slow");

            return;

		}

		cargarParametrosPlantillaAltaUrgencia();

    });



    function cargarParametrosPlantillaAltaUrgencia ( ) {

        const parametros = { 'idPlantilla' : $('#slc_nombrePlantilla').val(), 'accion' : 'obtenerPlantillaAltaUrgencia' };

        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/indicaciones/main_controller.php`, parametros, 'POST', 'JSON', 1,'');

        switch(respuestaAjaxRequest.status) {

            case "success":

                $("#frm_hipotesis_final").val(respuestaAjaxRequest.descripcionCie10);

                $("#frm_codigoCIE10").val(respuestaAjaxRequest.idCie10);

                $("#frm_cie10Abierto").val(respuestaAjaxRequest.cie10Abierto);

                $("#frm_indicaciones_alta").val(respuestaAjaxRequest.indicaciones);

                $("#frm_pronostico").val(respuestaAjaxRequest.idPronostico);

                $("#frm_Indicacion_Egreso").val(respuestaAjaxRequest.idIndicacionEgreso);

                $("#selectSegunIndicacionEgreso").show("slow");

                $('#frm_Indicacion_Egreso').trigger("change");

            break;



            case "error":

                modalMensajeBtnExit('Error en Cargar Parámetros',`Error en cargar parámetros de Plantilla Alta urgencia:<br><br>${respuestaAjaxRequest.message}`, "error_cargar_parametros", 500, 300, 'danger');

            break;



            default:

                modalMensaje("Error genérico", respuestaAjaxRequest, "error_generico", 400, 300);

            break;

        }

    }

    function imprimirRCE ( ) {
        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+dau_id+'&banderaLlamada=altaUrgencia', "#detalle_rce_pdf", "modal-lg", "", "fas fa-plus");
    }



    function vaciarSelectsViolencia ( ) {

        $claseViolenciasNoAuntoinfringidas.children('select').each(function(){

            $(this).val(0);

        });

    }



    function rellenarSelectTipoAgresor ( idTipoViolencia ) {

        const parametros = { 'idTipoViolencia' : idTipoViolencia , 'accion' : 'agresorSegunTipoViolencia' };
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, parametros, 'POST', 'JSON', 1,'');

        const totalRespuesta = respuestaAjaxRequest.length;

        $tipoAgresor.html('');

        $divTipoAgresor.hide(100);

        $tipoAgresor.append($('<option>', {

            value: 0,

            text: 'Seleccione',

            selected: true,

            disabled: true

        }));

        for ( let i = 0; i < totalRespuesta; i++ ) {

            $tipoAgresor.append($('<option>', {

                value: respuestaAjaxRequest[i].idTipoAgresor,

                text: respuestaAjaxRequest[i].descripcionTipoAgresor

            }));

        }

        $divTipoAgresor.show(100);

    }



    function esconderOpcionesViolenciaNoAutoinfringidas ( ) {

        $claseViolenciasNoAuntoinfringidas.hide(100);

    }



    function desplegarLesionesDeVictima ( idTipoViolencia ) {

        if ( idTipoViolencia == otrasViolencias || idTipoViolencia == VIF ) {

            $claseViolenciaSexual.hide(100);

            $claseViolenciasVIFONoVIF.show(100);

        }


    }



    function desplegarSospechaPenetracion ( idTipoViolencia ) {

        if ( idTipoViolencia == sexual ) {

            $claseViolenciasVIFONoVIF.hide(100);

            $claseViolenciaSexual.show(100);

        }

    }



    function desplegarVictimaEmbarazada ( ) {

        $claseVictimaEmbarazada.show(100);

    }



    function verificarDatosViolencia ( ) {

        if ( $existeViolencia.val() == 'N' ) {

            return true;

        }

        if ( $tipoViolencia.val() == 0 || $tipoViolencia.val() == '' || $tipoViolencia.val() == undefined || $tipoViolencia.val() == null ) {

            $("#frm_tipoViolencia").assert(false, 'Debe Seleccionar Tipo de Violencia');

            return false;

        }

        if ( $tipoViolencia.val() == autoInfringidas ) {

            return true;

        }

        if ( edadPaciente >= 18 ) {

            if ( $tipoViolencia.val() == 0 || $tipoAgresor.val() == '' || $tipoAgresor.val() == undefined || $tipoAgresor.val() == null ) {

                $("#frm_tipoAgresor").assert(false,'Debe Seleccionar Tipo Agresor');

                return false;

            }

        }

        if ( $tipoViolencia.val() == otrasViolencias || $tipoViolencia.val() == VIF ) {

            if ( $tipoLesionVictima.val() == 0 || $tipoLesionVictima.val() == '' || $tipoLesionVictima.val() == undefined || $tipoLesionVictima.val() == null  ) {

                $("#frm_tipoLesionVictima").assert(false,'Debe Seleccionar Lesión de la Víctima');

                return false;

            }

        }

        if ( $tipoViolencia.val() == sexual ) {

            if ( $sospechaPenetracion.val() == 0 || $sospechaPenetracion.val() == '' || $sospechaPenetracion.val() == undefined || $sospechaPenetracion.val() == null  ) {

                $("#frm_tipoSospechaPenetracion").assert(false,'Debe Seleccionar Tiempo Agresión');

                return false;

            }

            if ( $profilaxis.val() == 0 || $profilaxis.val() == '' || $profilaxis.val() == undefined || $profilaxis.val() == null  ) {

                $("#frm_profilaxis").assert(false,'Debe Seleccionar Profilaxis');

                return false;

            }

            if ( $peritoSexual.val() == 0 || $peritoSexual.val() == '' || $peritoSexual.val() == undefined || $peritoSexual.val() == null  ) {

                $("#frm_peritoSexual").assert(false,'Debe Seleccionar Perito');

                return false;

            }

        }

        if ( $victimaEmbarazada.val() == 0 || $victimaEmbarazada.val() == '' || $victimaEmbarazada.val() == undefined || $victimaEmbarazada.val() == null  ) {

            $("#frm_victimaEmbarazada").assert(false,'Debe Seleccionar Víctima Embarazada');

            return false;

        }

        return true;

    }



    $("#seguimientoEnfermedadRespiratoria").on("click", function(){

        const parametros = { 'idPaciente' : paciente_id , 'accion' : 'verificarSeguimientoEnfermedadRespiratoria' }

        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/historial_clinico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

        if ( respuestaAjaxRequest != null && respuestaAjaxRequest.length != 0 ) {

            actualizarSeguimiento = true;

            modalMensajeBtnExit('Alerta en Registrar Seguimiento', `Alerta en resgistrar seguimiento enfermedad respiratoria: <b>Paciente ya cuenta con un registro de seguimiento</b>`, "alerta_registrar_seguimiento_enfermedad_respiratoria", 500, 300, 'warning', modalSeguimientoEnfermedadRespiratoria);

            return;

        }

        modalSeguimientoEnfermedadRespiratoria();

    });



    function existeSeguimientoEnfermedadRespiratoria ( ) {

        const parametros = { 'idPaciente' : paciente_id , 'accion' : 'verificarSeguimientoEnfermedadRespiratoria' };

        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/historial_clinico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

        if ( respuestaAjaxRequest == null || respuestaAjaxRequest.length == 0 ) {

            return false;

        }

        return true;

    }



    function existeExamenCOVID19 ( ) {

        const parametros = { 'idRCE' : rce_id , 'accion' : 'verificarProcedimientoExamenCOVID19' }

        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/historial_clinico/main_controller.php`, parametros, 'POST', 'JSON', 1, '');

        if ( respuestaAjaxRequest.length == 0 || respuestaAjaxRequest == null ) {

            return false;

        }

        return true;

    }



    function existeCodigoCIE10COVID19 ( ) {

        if ( $("#frm_codigoCIE10").val() == 'U071' || $("#frm_codigoCIE10").val() == 'U072' ) {

            return true;

        }

        return false;

    }



    function modalSeguimientoEnfermedadRespiratoria ( ) {

        let botones =   [
                            { id: 'btnGuardarSeguimiento', value: '<i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Guardar', class: 'btn btn-primary btnPrint' }
                        ];

        if ( actualizarSeguimiento === true ) {

            botones =   [
                            { id: 'btnActualizarSeguimiento', value: '<i class="glyphicon glyphicon-refresh" aria-hidden="true"></i> Actualizar', class: 'btn btn-primary btnPrint' }
                        ];

        }

        modalFormulario("Formulario Seguimiento Enfermedad Respiratoria", `${raiz}/views/modules/rce/alta_urgencia/seguimientoEnfermedadRespiratoria.php`, `idPaciente=${paciente_id}&idDau=${dau_id}`, "#formularioSeguimiento", "50%", "100%", botones);

        $('.modal-body').css('max-height','calc(100vh - 210px)');

        $('.modal-body').css('overflow','auto');

    }


    function pacienteEgresado ( ) {
        const parametros 		   =  {idDau : dau_id, accion : 'pacienteEgresado'};
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php', parametros, 'POST', 'JSON', 1);
        if ( respuestaAjaxRequest.status == 'success' ) {
            return true;
        }
	    return false;
    }
    $('#divAQuienSeEntregaInformacion').hide();
    if( $('#frm_entregaInformacion').val() == 'S'){
        $('#divAQuienSeEntregaInformacion').show();
        $('#divObservacionSeEntregaInformacion').hide();
    }
    if( $('#frm_entregaInformacion').val() == 'N'){
        $('#divAQuienSeEntregaInformacion').hide();
        $('#divObservacionSeEntregaInformacion').show();
    }

    document.getElementById('slc_nombrePlantilla').addEventListener('change', function () {
        document.getElementById('actualizarplantilla').style.display = this.value ? 'block' : 'none';
    });
    //Se entrega información
    (function(){

        const iniciarEntregaInformacion = ( ) => {

            $(`${divAQuien}`).hide();

            $seEntregaInfo.val("N");

        }

        const seEntregaInfo = ( ) => {

            $seEntregaInfo.on("change", function(){

                $aQuien.val("");

                if ( String($seEntregaInfo.val()) === "N" ) {

                    $(`${divAQuien}`).hide(100);

                    $('#divObservacionSeEntregaInformacion').show();
                    return;

                }

                $(`${divAQuien}`).show(100);

                $('#divObservacionSeEntregaInformacion').hide();

            });

        }

        // iniciarEntregaInformacion();
        seEntregaInfo();


    })();

});
