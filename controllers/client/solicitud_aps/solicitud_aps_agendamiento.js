"use strict";

$(document).ready(function () {

    const agendamientoSolicitudAPS = (function agendamientoSolicitudAPS() {

        //Variables Rescatadas
        const idSolicitudAPS            = $("#idSolicitudAPS").val();

        //Variables Formularios
        const $slcEstadoSolicitud       = $("#slc_estadoAgendamientoSolicitud"),
            $slcPrioridadSolicitud      = $("#slc_prioridadAgendamientoSolicitud"),
            $slcProgramasSolicitud      = $("#slc_programaAgendamientoSolicitud"),
            $txtObservacionSolicitud    = $("#txt_observacionSolicitudAPS"),
            $claseEgresadoConHora       = $(".egresadoConHora"),
            $btnAgendar                 = $("#btnCambiarEstadoSolicitudAPS");

        //Variables JS
        let egresadoConHora             = 3,
            datosSolicitudAPS           = {},
            estadosSolicitud            = {},
            prioridadesSolicitud        = {},
            programasSolicitud          = {};


        //Funciones privadas
        function _agendarSolicitud() {

            $btnAgendar.on("click", () => {

                if (!_datosIngresadosCorrectamente()) {

                    return;

                }
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Confirmación para Agendar </h4>  <hr>  <p class="mb-0">Se procederá a Agendar Solicitud APS del paciente correspondiente, <b>¿Desea continuar?</b></b></p></div>';
                 modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procedera a guardar los Examenes, <b>¿Desea continuar?</b>", "primary", _confimarAgendamientoSolicitud);

                // modalConfirmacion('Confirmación para Agendar', `Se procederá a Agendar Solicitud APS del paciente correspondiente`, _confimarAgendamientoSolicitud);

            });

        }

        function _booleanSelectSegunId(tipoSelect, idSelect) {

            return (datosSolicitudAPS[tipoSelect] === idSelect);

        }

        function _cambiarEstadoSolicitud() {

            $slcEstadoSolicitud.on("change", () => {

                $claseEgresadoConHora.find("select").val(0);

                $txtObservacionSolicitud.val('');

                _ocultarDivsNoCorrespondientesAEgresadoConHora();

            });

        }

        function _confimarAgendamientoSolicitud() {

            const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, $("#frm_agendamientoSolicitudAPS").serialize() + `&idSolicitudAPS=${datosSolicitudAPS.idSolicitudAPS}&accion=agendarSolicitudAPS`, 'POST', 'JSON', 1, '');

            switch (respuestaAjaxRequest.status) {

                case 'success':

                    $('#solicitud_aps_agendar').modal('hide').data('bs.modal', null);
                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito </h4>  <hr>  <p class="mb-0">Se ha agendado la solicitud APS de acuerdo a los parámetros ingresados</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                    ajaxContent('/RCEDAU/views/modules/solicitud_aps/solicitud_aps_worklist.php','','#contenido');

                    break;

                case 'error':

                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error! </h4>  <hr>  <p class="mb-0">Error en agendar solicitud APS al paciente:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
                    modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");

                    break;

                default:
                    ErrorSistemaDefecto();

                    break;

            }

        }

        function _datosIngresadosCorrectamente() {

            if (!_valorExiste($slcEstadoSolicitud.val())) {

                $("#slc_estadoAgendamientoSolicitud").assert(false, 'Seleccione Estado');

                return false;

            }

            if ($slcEstadoSolicitud.val() !== egresadoConHora) {

                return true;

            }

            if (!_valorExiste($slcPrioridadSolicitud.val())) {

                $("#slc_prioridadAgendamientoSolicitud").assert(false, 'Seleccione Prioridad');

                return false;

            }

            if (!_valorExiste($slcProgramasSolicitud.val())) {

                $("#slc_programaAgendamientoSolicitud").assert(false, 'Seleccione Programa');

                return false;

            }

            return true;

        }

        function _ocultarDivsNoCorrespondientesAEgresadoConHora() {

            if ($slcEstadoSolicitud.val() == egresadoConHora) {

                $claseEgresadoConHora.show(100);

                return;

            }

            $claseEgresadoConHora.hide(100);

        }

        function _obtenerDatosEstadosAPS() {

            const parametrosAEnviar = { 'accion': 'obtenerEstadosSolicitudAPS' };

            estadosSolicitud = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');

        }

        function _obtenerDatosPrioridadesAPS() {

            const parametrosAEnviar = { 'accion': 'obtenerPrioridadesSolicitudAPS' };

            prioridadesSolicitud = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');

        }

        function _obtenerDatosProgramasAPS() {

            const parametrosAEnviar = { 'accion': 'obtenerProgramasSolicitudAPS' };

            programasSolicitud = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');

        }

        function _obtenerDatosSolicitudAPS() {

            const parametrosAEnviar = { 'idSolicitudAPS': idSolicitudAPS, 'accion': 'obtenerDatosSolicitudAPS' };

            datosSolicitudAPS = ajaxRequest(`${raiz}/controllers/server/solicitud_aps/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1, '');

        }

        function _rellenarObservacionSolicitud() {

            $txtObservacionSolicitud.val(datosSolicitudAPS.observacionSolicitud);

        }

        function _rellenarSelects($slcARellenar, objetoDatos, tipoSelect) {

            $.each(objetoDatos, (index) => {

                let [id, descripcion] = Object.values(objetoDatos[index]);

                let selected = _booleanSelectSegunId(tipoSelect, id);

                $slcARellenar.append($('<option>', {

                    value: id,

                    text: descripcion,

                    selected: selected

                }));

            });

        }

        function _rellenarSelectEstadoSolicitud() {

            _rellenarSelects($slcEstadoSolicitud, estadosSolicitud, 'estadoSolicitud');

        }

        function _rellenarSelectPrioridadSolicitud() {

            _rellenarSelects($slcPrioridadSolicitud, prioridadesSolicitud, 'prioridadSolicitud');

        }

        function _rellenarSelectProgramasSolicitud() {

            _rellenarSelects($slcProgramasSolicitud, programasSolicitud, 'programaSolicitud');

        }

        function _valorExiste(valor) {

            return (valor !== '' && valor !== 0 && valor !== null && valor !== undefined) ? true : false;

        }



        //Funciones públicas
        function agendarSolicitud() {

            _agendarSolicitud();

        }

        function cambiarEstadoSolicitud() {

            _cambiarEstadoSolicitud();

        }

        function iniciarModalAgendamiento() {

            _obtenerDatosSolicitudAPS();

            _obtenerDatosEstadosAPS();

            _obtenerDatosPrioridadesAPS();

            _obtenerDatosProgramasAPS();

            _rellenarSelectEstadoSolicitud();

            _rellenarSelectPrioridadSolicitud();

            _rellenarSelectProgramasSolicitud();

            _rellenarObservacionSolicitud();

        }

        function ocultarDivsNoCorrespondientesAEgresadoConHora() {

            _ocultarDivsNoCorrespondientesAEgresadoConHora();

        }




        return {
            agendarSolicitud: agendarSolicitud,
            cambiarEstadoSolicitud: cambiarEstadoSolicitud,
            iniciarModalAgendamiento: iniciarModalAgendamiento,
            ocultarDivsNoCorrespondientesAEgresadoConHora: ocultarDivsNoCorrespondientesAEgresadoConHora
        }

    })();

    agendamientoSolicitudAPS.iniciarModalAgendamiento();
    agendamientoSolicitudAPS.ocultarDivsNoCorrespondientesAEgresadoConHora();
    agendamientoSolicitudAPS.cambiarEstadoSolicitud();
    agendamientoSolicitudAPS.agendarSolicitud();

});