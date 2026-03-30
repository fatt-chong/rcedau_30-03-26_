$(document).ready(function(){
    let variablesCambioTurno = { dauId: $('#dauId').val(), observacionEntregarTurno : '' , observacionRecibirTurno : '',  rutEntregaTurno : $('#rutMedicoTratanteEntrega').val(), accion : 'cambiarTurno', codigoMedicoTratanteEntrega : $('#codigoMedicoTratanteEntrega').val() };
    let banderaEntregaTurno  = '';

    $('#btnEntregarTurno').on('click', function(){
        banderaEntregaTurno = 'verdadero';
        identificador       = false;
        verificarUsuario( entregarTurno, banderaEntregaTurno );
    });
    function entregarTurno () {
        let botones     =   [
                                { id: 'btnRecibirTurno', value: 'Recibir Turno', class: 'btn btn-warning', function: recibirTurno }
                            ]
        variablesCambioTurno.observacionEntregarTurno  = $('#txt_observacionCambioTurno').val();
        ocultarModales('modalEntregaTurno');
        modalFormulario("<label class='mifuente ml-2'>Recibimiento de Turno</label>", `${raiz}/views/modules/rce/rce/cambioTurno.php`,`dau_id=${variablesCambioTurno.dauId}&banderaTipoTurno=recibirTurno`, "#modalRecibirTurno", "modal-lg", "light",'', botones);
    }
    function recibirTurno () {
        variablesCambioTurno.observacionRecibirTurno  = $('#txt_observacionCambioTurno').val();
        banderaEntregaTurno = 'falso';
        ocultarModales('modalRecibirTurno');
        identificador = false;
        verificarUsuario( guardarDatosTurno, banderaEntregaTurno );
    }
    
    function guardarDatosTurno () {
        let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/cambioTurno/main_controller.php`, variablesCambioTurno, 'POST', 'JSON', 1);
        switch(respuestaAjaxRequest.status){
            case "success":
                clearInterval(interval);
                inicializaReloj();  

                ajaxContent(raiz+'/views/modules/rce/medico/rce.php','tipoMapa='+$('#tipoMapa').val()+'&dau_id='+$('#dau_id').val(),'#contenido','', true);   
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito en Entrega de Turno </h4>  <hr>  <p class="mb-0">La Entrega de Turno se ha realizado en forma exitosa.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success"); 

            break;
            case   "error":
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el Proceso </h4>  <hr>  <p class="mb-0">No se ha podido realizar la entrega de turno en forma exitosa: </br></br> '+respuestaAjaxRequest.message+'</div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            break;
        }
    }
    function verificarUsuario ( funcionALlamar, banderaEntregaTurno ) {      
        fn_global = funcionALlamar;
        if ( ! identificador ) { 
            modalFormulario("<label class='mifuente ml-2'>Acceso DAU</label>", `${raiz}/views/modules/identificacion/identificacion.php`, `accessRequest=btn_cambioTurno&rutCambioTurno=${variablesCambioTurno.rutEntregaTurno}&codigoBarraCambioTurno=${variablesCambioTurno.codigoMedicoTratanteEntrega}&banderaEntregaTurno=${banderaEntregaTurno}`, "#accesoPistola", "modal-md", "light",'', '');
        }  
        return false;
    }
    function ocultarModales ( nombreModal ) {
        $('#accesoPistola').modal('hide').data('bs.modal', null);
        $(`#${nombreModal}`).modal('hide').data('bs.modal', null);
    }
})