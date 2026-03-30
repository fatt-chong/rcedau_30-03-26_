
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


    //Validaciones
    // validar("#frm_especialidad","letras");
    validar("#frm_observacion","letras_numeros_caracteres");
    //Búsqueda senstivia especialista
    // busquedaSensitivaEspecialidad();
    //Botón guardar solicitud especialista
    $("#btnAprobacionEspecialista").click(async function () {
        const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
        if (estadoPermiso) {
            modalConfirmacionNuevo("Advertencia","ATENCIÓN, se procederá a guardar la aprobación del Especialista el para Paciente, <b>¿Desea continuar?</b>", "primary", aprobarSolicitudEspecialista);
        }

    });
    function aprobarSolicitudEspecialista () {
        const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, $('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=aprobarSolicitudEspecialistaOtros', 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad');
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
    $("#btnGuardarEspecialistaOtro").click(async function () {
        const estadoValido = await validarEstadoPaciente(idDau);
        if (estadoValido) {
            $.validity.start();
            if ( $("#ftm_especialista_otro").val() == null ) {
                $("#ftm_especialista_otro").assert(false,'Debe Ingresar Una Especialidad');
                return false;
            }
            if ( $("#frm_observacion").val() == "" ) {
                $("#frm_observacion").assert(false,'Debe Ingresar Una Observacion');
                return false;
            }
            // return true;
            const estadoPermiso = await validarPermisoUsuario('btn_rce_guardar');
            if (estadoPermiso) {
                const respuestaAjaxRequest =  ajaxRequest(`${raiz}/controllers/server/rce/especialista/main_controller.php`, $('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=ingresarSolicitudEspecialistaOtro&NombreEspecialista='+$("#ftm_especialista_otro option:selected").text(), 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad');
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
        }
    });

    
});