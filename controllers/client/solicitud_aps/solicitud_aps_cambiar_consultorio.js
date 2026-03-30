$(document).ready(function(){

    const cambiarConsultorioSolicitudAPS = ( function cambiarConsultorioSolicitudAPS ( ) {

        //Variables
        const $btnCambiarConsultorioSolicitudAPS    = $("#btnCambiarConsultorioSolicitudAPS"),
              $idSolicitudAPS                       = $("#idSolicitudAPS"),
              $idDau                                = $("#idDau"),
              $idPaciente                           = $("#idPaciente"),
              $codigoConsultorio                    = $("#slc_consultorioDetalleSolicitud");
        //Funciones privadas
        function _cambiarConsultorioSolicitudAPS ( ) {
            var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Confirmación para cambiar de consultorio al paciente </h4>  <hr>  <p class="mb-0">ATENCIÓN, Se procederá a cambiar de consultorio al paciente correspondient, <b>¿Desea continuar?</b></b></p></div>';
                 modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a guardar los Examenes, <b>¿Desea continuar?</b>", "primary", _confimarCambiarConsultorioSolicitudAPS);
        }
        function _confimarCambiarConsultorioSolicitudAPS ( ) {
            parametrosAEnviar = { 'idSolicitudAPS' : $idSolicitudAPS.val() , 'idPaciente' : $idPaciente.val() , 'codigoConsultorio' : $codigoConsultorio.val() , 'accion' : 'cambiarConsultorio' }
            const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1);
            switch(respuestaAjaxRequest.status){
                case 'success':
                    $('#solicitud_aps_cambiar_consultorio').modal( 'hide' ).data( 'bs.modal', null );
                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito </h4>  <hr>  <p class="mb-0">Se ha aplicado el cambio de consultorio al paciente de forma exitosa</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    ajaxContent('/RCEDAU/views/modules/solicitud_aps/solicitud_aps_worklist.php','','#contenido');

                break;
                case 'error' :
                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error! </h4>  <hr>  <p class="mb-0">Error en cambiar consultorio al paciente:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                break;
                default :
                    ErrorSistemaDefecto();
                break;
            }
        }
        //Funciones públicas
        function cambiarConsultorioSolicitudAPS ( ) {

            $btnCambiarConsultorioSolicitudAPS.on("click", _cambiarConsultorioSolicitudAPS);

        }


        return {
            cambiarConsultorioSolicitudAPS : cambiarConsultorioSolicitudAPS
        }

    })();

    enlaceBoton();
    cambiarConsultorioSolicitudAPS.cambiarConsultorioSolicitudAPS();

});