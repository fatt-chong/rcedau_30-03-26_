$(document).ready(function(){
    tablaSimple('#solEspecialidad');
    localStorage.setItem('urlAtras', '/views/modules/solicitud_especialista/solicitud_especialista.php');
    localStorage.setItem('parametrosAtras', '');


    $('#checkTodas').click(function() {
        if ($(this).is(':checked')) {
            ajaxContent(`${raiz}/views/modules/solicitud_especialista/solicitud_especialista.php`, $('#frm_solicitud_especialidad').serialize(), '#contenido', 'Cargando Vista...', true);
            return;
        }
        unsetSesion();
        ajaxContent(raiz+'/views/modules/solicitud_especialista/solicitud_especialista.php','','#contenido','Cargando Vista...', true);
    });

    $("#contenidoSolicitud").on('click','.aprobarSolicitud',function() {
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        let llaves      = $(this).attr('id');
        let arreglo     = llaves.split('-');
        let dau_id      = arreglo[0];
        let paciente_id = arreglo[1];
        let id_especia  = arreglo[2];
        let estado_esp  = arreglo[3];
        let confirmar = function(){
            let aprobarSol = function(){
                let respServer = function(response){
                    switch(response.status){
                        case "success":
                            var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Solicitud Especialista </h4>  <hr>  <p class="mb-0">Se ha ingresado correctamente la aprobación de solicitud de especialista.</p></div>';
                            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                            $('#modalEspecialista').modal( 'hide' ).data( 'bs.modal', null );
                            ajaxContent(`${raiz}/views/modules/solicitud_especialista/solicitud_especialista.php`, $('#frm_solicitud_especialidad').serialize(), '#contenido', 'Cargando Vista...', true);
                        break;

                        case "info":
                            var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">No se ha podido ingresar la solicitud de especialista, vuelva a intentarlo</p></div>';
                            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                        break;

                        case "error":
                            var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en la transacción, no se aprobo la solicitud, el siguiente error de sistema se ha desplegado:<br><br>'+response.message+'.</p></div>';
                            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                        break;

                        default:
                            ErrorSistemaDefecto();
                        break;
                    }
                    ajaxContent(raiz+'/views/modules/solicitud_especialista/solicitud_especialista.php','','#contenidoDAU','Cargando Vista...', true);
                }
                ajaxRequest(raiz+'/controllers/server/rce/especialista/main_controller.php',$('#frm_ingresarSolicitudEspecialista').serialize()+'&accion=aprobarSolicitudEspecialista', 'POST', 'JSON', 1, 'Guardando Solicitud Especialidad',respServer);
            }
            modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procederá a guardar los datos de Especialista para Paciente, <b>¿Desea continuar?</b>", "primary", aprobarSol);
        }
        if ( estado_esp != 4 ) {

            var botones =   [
                                { id: 'btnAprobarSol', value: 'Aprobar', class: 'btn btn-primary', function: confirmar}
                            ];
        }
        modalFormulario("<label class='mifuente ml-2'>Elección de Especialista</label>",`${raiz}/views/modules/rce/especialista/especialista.php`,`dau_id=${dau_id}&paciente_id=${paciente_id}&tipoFormulario=aprobacionEspecialista&idSolicitudEspecialista=${id_especia}&llamadaDesde=especialistaWorklist`,'#modalEspecialista',"modal-lg","light","fas fa-folder-plus",botones);

    });
    $("#contenidoSolicitud").on("click", ".verDetalle", function() {
        $('.tooltip').tooltip('hide')
        idDau = $(this).attr('id');
        ajaxContent('views/modules/rce/medico/rce.php', `dau_id=${idDau}`, '#contenido', 'Cargando...', true);
    });
    $("#contenidoSolicitud").on('click','.verSolicitudEspe',function() {
        if ( perfilUsuario === 'administrativo') {
            return;
        }
        let llaves      = $(this).attr('id');
        let arreglo     = llaves.split('-');
        let dau_id      = arreglo[0];
        let paciente_id = arreglo[1];
        let id_especia  = arreglo[2];
        let estado_esp  = arreglo[3];
        let botones     = [];
        modalFormulario('Elección de Especialista', `${raiz}/views/modules/rce/especialista/especialista.php`, `dau_id=${dau_id}&paciente_id=${paciente_id}&tipoFormulario=aprobacionEspecialista&idSolicitudEspecialista=${id_especia}`, '#modalEspecialista', '40%', 'auto', botones);
    });
});