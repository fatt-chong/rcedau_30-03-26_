
$(document).ready(function(){
    // $('#gestionRealizada').hide();
    //Variables
    let idDau                           = $("#idDau").val(),
        idRCE                           = $("#idRCE").val(),
        idPaciente                      = $("#idPaciente").val(),
        tipoFormulario                  = $("#tipoFormulario").val(),
        idProfesionalEspecialista       = $("#idProfesionalEspecialista").val(),
        llamadaDesde                    = $("#llamadaDesde").val();
    let $btnAprobacionEspecialista      = $("#btnAprobacionEspecialista");
        $btnGuardarEspecialista         = $("#btnGuardarEspecialista"),
        $frm_especialidad               = $("#frm_especialidad"),
        $frm_idEspecialidad             = $("#frm_idEspecialidad"),
        $checkEspecialistaDeLlamado     = $("#frm_especialistaDeLlamado"),
        $divGestionRealizada            = $("#gestionRealizada"),
        $slcMedicoEspecialista          = $("#frm_medicoEspecialista"),
        $checkGestionRealizada          = $("#frm_gestionRealizada"),
        $observacionGestionRealizada    = $("#frm_observacionGestionRealizada");
        $btnTrasladoAdulto              = $("#btnTrasladoAdulto");
        $btnTrasladoPediatrico          = $("#btnTrasladoPediatrico");
        $btnTrasladoGinecologico        = $("#btnTrasladoGinecologico");

    const   tipoAtencion                = $("#tipoAtencion").val();
    const   trasladoPaciente            =   {
                                                    "trasladoAdulto"        : { idTraslado: 9   , valorTraslado : "Traslado Adulto" }
                                                ,   "trasladoPediatrico"    : { idTraslado: 10  , valorTraslado : "Traslado Pediátrico" }
                                                ,   "trasladoGinecologico"  : { idTraslado: 8   , valorTraslado : "Traslado Ginecología" }

                                            }

    if ( String(llamadaDesde) === "especialistaWorklist" ) {
        $btnAprobacionEspecialista.hide();
    }


    if ( $frm_especialidad.val() == null || $frm_especialidad.val() == "" ) {
        $checkEspecialistaDeLlamado.attr("disabled", true);
        $checkGestionRealizada.attr("disabled", true);
        $divGestionRealizada.hide(0);
    }

    medicosEspecialidades();
    if ( tipoFormulario == "aprobacionEspecialista" || tipoFormulario == "verDetalle" ) {
        if ( $checkGestionRealizada.is(":checked") ) {
            $slcMedicoEspecialista.val(idProfesionalEspecialista);
            $slcMedicoEspecialista.prop("disabled", true);
            $observacionGestionRealizada.prop("disabled", true);
        }
    }

    //Validaciones
    validar("#frm_especialidad","letras");
    validar("#frm_observacion","letras_numeros_caracteres");
    //Búsqueda senstivia especialista
    // busquedaSensitivaEspecialidad();
    //Botón guardar solicitud especialista
    $("#btnGuardarEspecialista").click(async function () {
        const estadoValido = await validarEstadoPaciente(idDau);
        if (estadoValido) {
            if ( ! seHaIngresadoAlgunaEspecialidad() ) {
                return;
            }
            const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
            if (estadoPermiso) {
                ingresarSolicitudEspecialista();
            }
        }
    });
    //Botón aprobación de especialista

    $("#btnAprobacionEspecialista").click(async function () {
        if ( ! seHaIngresadoDatosAprobacionEspecialista() ) {
            return;
        }
        const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
        if (estadoPermiso) {
            modalConfirmacionNuevo("Advertencia","ATENCIÓN, se procederá a guardar la aprobación del Especialista el para Paciente, <b>¿Desea continuar?</b>", "primary", aprobarSolicitudEspecialista);
        }

    });
    //Al enfocar en el campo, borrar valores previos
    $frm_especialidad.on('focus', function( ) {
        $frm_especialidad.val("");
        $frm_idEspecialidad.val("");
        $checkEspecialistaDeLlamado.prop("checked", false);
        $checkGestionRealizada.prop("checked", false);
        $checkEspecialistaDeLlamado.prop("disabled", true);
        $checkGestionRealizada.prop("disabled", true);
        $observacionGestionRealizada.val("");
        $divGestionRealizada.hide(100);
    });
    $frm_especialidad.autocomplete({ //  INPUT TEXT BUSQUEDA DE PRODUCTO
        source: function(request, response) {
            $.ajax({
                type    : "POST",
                    url     : `${raiz}/controllers/server/rce/especialista/main_controller.php`,
                    dataType: "json",
                    data    : {
                                term : request.term,
                                accion : 'busquedaSensitivaEspecialista',
                            },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 3,
        select: function(event, ui){
            $frm_especialidad.val( ui.item.label );
            $frm_idEspecialidad .val( ui.item.value );
            $('#SESPfuente').val( ui.item.fuente );
            medicosEspecialidades();
            return false;
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
    function seHaIngresadoAlgunaEspecialidad () {
        $.validity.start();
        if ( $frm_especialidad.val() == "" ) {
            $frm_especialidad.assert(false,'Debe Ingresar Una Especialidad');
            return false;
        }
        if ( $frm_idEspecialidad.val() == "" ) {
            $frm_especialidad.assert(false,'Debe Ingresar Una Especialidad');
            return false;
        }
        return true;
    }

    function seHaIngresadoDatosAprobacionEspecialista ( ) {
        if ( $checkEspecialistaDeLlamado.is(":checked") && ! $checkGestionRealizada.is(":checked") ) {
            $checkGestionRealizada.assert(false, "Debe Marcar Opción");
            return false;
        }
        if ( $checkGestionRealizada.is(":checked") && $slcMedicoEspecialista.val() == null ) {
            $slcMedicoEspecialista.assert(false, "Debe Seleccionar Médico");
            return false;
        }
        return true;
    }
    function ingresarSolicitudEspecialista () {
        const respuestaAjaxRequest =  ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, $('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=ingresarSolicitudEspecialista', 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad');
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                $('#modalEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito en el proceso </h4>  <hr>  <p class="mb-0">Se ha ingresado correctamente la solicitud de especialista.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success"); 
                ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(),'#contenido','', true);
            break;
            default:
                ErrorSistemaDefecto();
            break;
        }
    }

    function aprobarSolicitudEspecialista () {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, $('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=aprobarSolicitudEspecialista', 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad');
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                $('#modalVerEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(),'#contenido','', true);
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito en el proceso </h4>  <hr>  <p class="mb-0">Se ha ingresado correctamente la aprobación de solicitud de especialista.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
            case "error":
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">No se ha podido ingresar la solicitud de especialista. </br></br> ERROR:'+respuestaAjaxRequest.message+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
        }
    }

    $checkEspecialistaDeLlamado.on("click", function(){
        if ( ! $checkEspecialistaDeLlamado.is(":checked") ) {
            $checkGestionRealizada.prop("checked", false);
            $checkGestionRealizada.prop("disabled", true);
            $slcMedicoEspecialista.val(0);
            $observacionGestionRealizada.val("");
            $divGestionRealizada.hide(100);
            return;
        }
        $checkEspecialistaDeLlamado.val('S');
        $checkGestionRealizada.prop("disabled", false);
    });

    $checkGestionRealizada.on("click", function(){
        // alert()
        if ( ! $checkGestionRealizada.is(":checked") ) {
            alert()
            $checkGestionRealizada.val("");
            $divGestionRealizada.hide(100);
            $observacionGestionRealizada.val("");
            return;
        }
        $checkGestionRealizada.val('S');
        $("#gestionRealizada").show();
        const textoObservacionGestionRealizada = { idEnFormulario : $("#frm_observacionGestionRealizada") , idCaptionTamanio : $("#lengthTextoObservacionGestionRealizada") , tamanioMaximo : $("#frm_observacionGestionRealizada").attr("maxlength") };
        contadorCaracteresTexto(textoObservacionGestionRealizada);
    });
    //Traslado adulto
    $btnTrasladoAdulto.on("click", function(){
        trasladarPaciente(trasladoPaciente["trasladoAdulto"]);
    });
    //Traslado pediátrico
    $btnTrasladoPediatrico.on("click", function(){
        trasladarPaciente(trasladoPaciente["trasladoPediatrico"]);
    });
    //Traslado ginecologico
    $btnTrasladoGinecologico.on("click", function(){
        trasladarPaciente(trasladoPaciente["trasladoGinecologico"]);
    })
    async function trasladarPaciente ( trasladoPaciente ) {
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        if ( ! verificarSignosVitales() ) {
            return;
        }
        const estadoValido = await pacienteYaConNEA(idDau,$('#tipoMapa').val());
        if (estadoValido) {
            if ( pacienteEgresado() ) {
                $('#modalVerEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error! </h4>  <hr>  <p class="mb-0">Este Paciente ya fue dado de Alta (Posiblemente por Otra Persona o DAU Automático), no se puede indicar egreso.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
                return;
            }
            if ( ! verificarDatosEspecialista() ) {
                return;
            }
            const funcionCallBacks = ( ) => { confirmarTraslado(trasladoPaciente) };

            modalConfirmacionNuevo("Advertencia","ATENCIÓN, se procederá a realizar traslado de paciente, cerrando el DAU actual, <b>¿Desea continuar?</b>", "primary", funcionCallBacks);
        }else{
            $('#modalVerEspecialista').modal( 'hide' ).data( 'bs.modal', null );
        }
    }

    async function confirmarTraslado ( trasladoPaciente ) {
        const funcionCallBacks = ( ) => { return darTraslado(trasladoPaciente); };
        const estadoPermiso = await validarPermisoUsuario('btn_ind');
        if (estadoPermiso) {
            funcionCallBacks();
        }
    }

    function darTraslado ( trasladoPaciente ) {
        let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/medico/main_controller.php`, `&dau_id=${idDau}&rce_id=${idRCE}&paciente_id=${idPaciente}&frm_indicaciones_alta=${$("#frm_observacionEspecialista").val()}&frm_Indicacion_Egreso=${trasladoPaciente['idTraslado']}&descripcionIndicacionEgreso=${trasladoPaciente['valorTraslado']}&frm_pronostico=${$("#frm_pronostico").val()}&accion=registrarIndicacionEgreso`, 'POST', 'JSON', 1,'Indicando Alta Urgencia...');
        if ( respuestaAjaxRequest.status !== "success" ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en aplicar traslado:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return;
        }
        ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, $('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=aprobarSolicitudEspecialista', 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad');

        respuestaAjaxRequest = respuestaAjaxRequest = ajaxRequest( `${raiz}/controllers/server/dau/main_controller.php`, `&dau_id=${idDau}&paciente_id=${idPaciente}&accion=registrarIndicacionAplica`, 'POST', 'JSON', 1,'Cerrando DAU...');
        if ( respuestaAjaxRequest.status !== "success" ) {
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en aplicar traslado:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            return;
        }
        switch ( respuestaAjaxRequest.status ) {
            case "success":
                if ( respuestaAjaxRequest.message != '' ) {
                    texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ATENCIÓN! </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</p></div>';
                    modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", '');
                }
                $('#modalVerEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                refrescarRCE();
                imprimirRCE();
            break;
            case "warning":
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> ADVERTENCIA </h4>  <hr>  <p class="mb-0">'+respuestaAjaxRequest.message+'</p></div>';
                modalConfirmacionNoExit("<label class='mifuente'>Advertencia</label>", texto, "primary", '');
                $('#modalVerEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                refrescarRCE();
            break;
            case "error":
                texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en registrar Aplicar Indicación de Egreso:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
            default:
                ErrorSistemaDefecto();
            break;
        }
    }

    function refrescarRCE ( ) {

        ajaxContentSlideLeft(`${raiz}/views/modules/mapa_piso_full/mapa_piso_full.php`,'tipoMapa='+tipoMapa, '#contenido');
    }
    function imprimirRCE ( ) {

        modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", "rce_id="+rce_id+'&dau_id='+idDau+'&banderaLlamada=altaUrgencia', "#detalle_rce_pdf", "modal-lg", "", "fas fa-plus");
    }

    function medicosEspecialidades ( ) {
        const parametrosAEnviar = { 'idEspecialidad' : $frm_idEspecialidad.val() , 'accion' : 'buscarMedicosEspecialistas' };
        const respuestaAjaxRequest =  ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');
        if ( respuestaAjaxRequest == undefined || respuestaAjaxRequest == null || respuestaAjaxRequest.length == 0 ) {
            return;
        }
        rellenarSelectMedicosEspecialistas(respuestaAjaxRequest);
        if ( tipoFormulario != "" ) {
            return;
        }
        $checkEspecialistaDeLlamado.attr("disabled", false);
    }

    function rellenarSelectMedicosEspecialistas ( medicosEspecialistas ) {
        $slcMedicoEspecialista.find('option').remove().end();
        $slcMedicoEspecialista.append($('<option>', {
            value: 0,
            text: "Seleccione",
            selected: true,
            disabled: true
        }));
        for ( let indice in medicosEspecialistas ) {
            $slcMedicoEspecialista.append($('<option>', {
                value: Object.values(medicosEspecialistas[indice])[0],
                text: Object.values(medicosEspecialistas[indice])[1]
            }));
        }
    }
    function contadorCaracteresTexto ( texto ) {
        if ( siExisteTexto(texto) ) {
            return;
        }
        desplegarContadorCaracteresTexto(texto);
    }
    function siExisteTexto ( texto ) {
        return ( texto.idEnFormulario.is(":disabled") || texto.idEnFormulario.val() != null && texto.idEnFormulario.val() != "" );
    }
    function desplegarContadorCaracteresTexto ( texto ) {
        texto.idCaptionTamanio.text(`${texto.tamanioMaximo}/${texto.tamanioMaximo}`);
        texto.idEnFormulario.on("input", function(e){
            const tamanioActualTexto = texto.tamanioMaximo - texto.idEnFormulario.val().length;
            texto.idCaptionTamanio.text(`${texto.tamanioMaximo}/${tamanioActualTexto}`);
        });
    }
    function verificarDatosEspecialista ( ) {
        let banderaNoError = true;
        if ( $("#frm_observacionEspecialista").val() === null || $("#frm_observacionEspecialista").val() === undefined || $("#frm_observacionEspecialista").val() === "" ) {
            $("#frm_observacionEspecialista").assert(false, "Debe Ingresar Evaluación");
            banderaNoError = false;
        }
        if ( $("#frm_pronostico").val() === null || $("#frm_pronostico").val() === undefined || $("#frm_pronostico").val() === 0 ) {
            $("#frm_pronostico").assert(false, "Debe Ingresar Pronóstico");
            banderaNoError = false;
        }
        return banderaNoError;
    }
    function verificarSignosVitales ( ) {
        if ( Number(tipoAtencion) !==  atencionAdulto ) {
            return true;
        }
        let banderaError = false;
        let camposFaltantes = "";
        const parametrosAEnviar = { 'idRCE' : idRCE , 'accion' : 'buscarIngresoSignosVitalesPrioritarios' };
        const respuestaAjaxRequest = ajaxRequest( `${raiz}/controllers/server/medico/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');
        if ( respuestaAjaxRequest.totalSignoVitalPulso == 0 ) {
            camposFaltantes += "<br> - Pulso";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalSistolica == 0 && respuestaAjaxRequest.totalSignoVitalDiastolica) {
            camposFaltantes += "<br> - Presión Arterial";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalTemperatura == 0 ) {
            camposFaltantes += "<br> - Temperatura";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalFR == 0 ) {
            camposFaltantes += "<br> - Frecuencia Respiratoria";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalSaturacion == 0 ) {
            camposFaltantes += "<br> - Saturación";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalEVA == 0 ) {
            camposFaltantes += "<br> - EVA";
            banderaError = true;
        }
        if ( respuestaAjaxRequest.totalSignoVitalGlasgow == 0 ) {
            camposFaltantes += "<br> - Glasgow";
            banderaError = true;
        }
        if ( banderaError === true ) {
            modalMensajeBtnExit('Error en Indicar Alta Urgencia', `Faltan Signos Vitales Prioritarios que se deben tomar al paciente, los cuales son: <br>${camposFaltantes}`, "errorFaltanSignosVitalesPrioritarios", 500, 300, 'danger');
			return false;
		}
        return true;
    }
    function pacienteEgresado ( ) {
        const parametros           =  {idDau : $('#dau_id').val(), accion : 'pacienteEgresado'};
        const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php', parametros, 'POST', 'JSON', 1);
        if ( respuestaAjaxRequest.status == 'success' ) {
            return true;
        }
        return false;
    }
    const textoSolicitud = { idEnFormulario : $("#frm_observacion") , idCaptionTamanio : $("#lengthTextoSolicitudEspecialista") , tamanioMaximo : $("#frm_observacion").attr("maxlength") };
    contadorCaracteresTexto(textoSolicitud);
    const textoObservacion = { idEnFormulario : $("#frm_observacionEspecialista") , idCaptionTamanio : $("#lengthTextoObservacionEspecialista") , tamanioMaximo : $("#frm_observacionEspecialista").attr("maxlength") };
    contadorCaracteresTexto(textoObservacion);
});