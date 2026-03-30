$(document).ready(function(){
    const solicitudesAPS = ( function solicitudesAPS ( ) {
        //Variables de botones de cada solicitud
        const $btnAccionDesplegarRCE                = $(".btnAccionDesplegarRCE"),
              $btnAccionDesplegarHistorialClinico   = $(".btnAccionDesplegarHistorialClinico"),
              $btnCambiarConsultorio                = $(".btnCambiarConsultorio"),
              $btnMostrarDetalleSolicitud           = $(".btnMostrarDetalleSolicitud"),
              $btnAgendamientoSolicitudAPS          = $(".btnAgendamientoSolicitudAPS");
        //Variables de búsqueda por parámetros
        const $numeroDau                            = $("#frm_numeroDau"),
              $rutPaciente                          = $("#frm_runPaciente"),
              $consultorio                          = $("#slc_consultorio"),
              $fechaSolicitudDesde                  = $("#frm_fechaSolicitudDesde"),
              $fechaSolicitudHasta                  = $("#frm_fechaSolicitudHasta"),
              $estadoSolicitud                      = $("#slc_estadoSolicitud"),
              $prioridadSolicitud                   = $("#slc_prioridadSolicitud");
        //Variables de botones de búsqueda y excel
        const $btnBuscarSolicitudesAPS              = $("#btnBuscarSolicitudesAPS"),
              $btnEliminarParametrosBusqueda        = $("#btnEliminar"),
              $btnExportarExcel                     = $("#btnExportarExcel");
        //Variables para paginación
        const $primeraPagina                        = $("#primero_l"),
              $paginaPrevia                         = $("#atras_l"),
              $paginaSiguiente                      = $("#siguiente_l"),
              $ultimaPagina                         = $("#ultimo_l"),
              totalPag                              = $("#totalPag").val();
        //Funciones privadas
        function _accionDesplegarHistorialClinico ( ) {
            let pacienteId = $(this).attr('id');
            modalFormulario('<label class="mifuente text-primary">Historial Clinico</label>',raiz+"/views/modules/rce/rce/historial_clinico.php",`paciente_id=${pacienteId}`,'#modal_historial','modal-lg','', 'fas fa-laptop-medical text-primary','');
        }
        function _accionDesplegarRCE ( ) {
            const idsBoton   = $(this).attr('id');
            const arregloIds = idsBoton.split("-");
            const idDau      = arregloIds[0];
            const idRce      = arregloIds[1];
            const parametrosAEnviar = { rce_id: idRce, dau_id : idDau, banderaLlamada : 'altaUrgenciaIncompleto' };
            modalFormulario_noCabecera('', raiz+"/views/modules/rce/rce/ver_rce.php", parametrosAEnviar, "#detalle_rce_pdf", "modal-lg", "", "fas fa-plus");
        }
        function _buscarSolicitudesAPS ( ) {
            if ( $rutPaciente.val() != '' ) {
                _verificarRut($rutPaciente.val());
                return;
            }
            if ( ! _fechasIngresadasCorrectamente() ) {
                return;
            }
            _irAPagina(1)
        }

        function _cambiarConsultorio ( ) {

            const idSolicitudAPS = $(this).attr('id');

            const parametrosAEnviar = { 'idSolicitudAPS' : idSolicitudAPS };

            const botones = [
                                { id: 'btnCambiarConsultorioSolicitudAPS', value: 'Cambiar Consultorio', class: 'btn btn-primary' }
                            ];

            modalFormulario("<label class='mifuente'>Cambiar Consultorio Solicitud APS  </label>",raiz+'/views/modules/solicitud_aps/solicitud_aps_cambiar_consultorio.php',parametrosAEnviar,'#solicitud_aps_cambiar_consultorio',"modal-md","primary","fas fa-folder-plus",botones);
  
            // modalFormulario("Cambiar Consultorio Solicitud APS", `${raiz}/views/modules/solicitud_aps/solicitud_aps_cambiar_consultorio.php`, parametrosAEnviar, "#solicitud_aps_cambiar_consultorio", "50%", "100%", botones);

        }

        function _cambioSelectEstado ( ) {

            $estadoSolicitud.on("change", function(){

                if ( $estadoSolicitud.val() == 3 ) {

                    $prioridadSolicitud.prop("disabled", false);

                    return;

                }

                $prioridadSolicitud.prop("disabled", true);

            });

        }

        function _despliegueAgendamientoSolicitud ( ) {

            const botones           = [
                                        { id : 'btnCambiarEstadoSolicitudAPS', value : "Agendar", class : 'btn btn-primary' }
                                      ];

            const parametrosAEnviar = { 'idSolicitudAPS' : $(this).attr('id') };


            modalFormulario("<label class='mifuente'>Agendamiento Solicitud APS  </label>",raiz+'/views/modules/solicitud_aps/solicitud_aps_agendamiento.php',parametrosAEnviar,'#solicitud_aps_agendar',"modal-md","primary","fas fa-folder-plus",botones);

        }

        function _despliegueDetalleSolicitudAPS ( ) {

            const idSolicitudAPS = $(this).attr('id');

            const parametrosAEnviar = { 'idSolicitudAPS' : idSolicitudAPS };

            modalFormulario("Detalle Solicitud APS", `${raiz}/views/modules/solicitud_aps/solicitud_aps_detalle.php`, parametrosAEnviar, "#detalle_solicitud_aps", "50%", "100%");

        }

        function _eliminarBusqueda ( ) {

            $rutPaciente.val('');

            $numeroDau.val('');

            $consultorio.val('');

            $fechaSolicitudDesde.val('');

            $fechaSolicitudHasta.val('');

            $estadoSolicitud.val('');

            $prioridadSolicitud.val('');

            _irAPagina(1);

        }

        function _exportarExcel ( ) {

            $btnExportarExcel.on("click", function(){

                let url = `${raiz}/views/modules/solicitud_aps/excel_solicitud_aps.php`;

                let parametros = $("#frm_busquedaSolicitudesAPS").serialize();

                 // const url = raiz + '/views/modules/reportes/enfermedadesEpidemiologicas/excelEnfermedadesEpidemiologicas.php';

                $.blockUI({
                    baseZ: 1060,
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: 0.5,
                        color: '#fff',
                        fontSize: '16px'
                    },
                    message: '<div class="centerTable"><table><tr><td><label class="loadingBlock">Generando Excel... </label></td><td><img src="/estandar/assets/img/loading-5.gif" alt="Generando Excel ... " height="50" width="50"  /></td></tr></table></div>'
                });

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(parametros) // Enviamos el cuerpo como JSON
                })
                .then(resp => {
                    console.log("resp", resp);
                    if (resp.ok) {
                        return resp.blob();
                    } else {
                        throw new Error('Error al generar el archivo');
                    }
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = 'xls_gestion_reporte.xls'; // Nombre del archivo
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    $.unblockUI();
                })
                .catch((e) => {
                    console.log("Error:", e);
                    modalMensaje('ATENCIÓN', 'Ha ocurrido un error, comuníquese con <b>mesa de ayuda.</b>', "#modal", "", "danger");
                    $.unblockUI();
                });
                // window.open(url+'?'+parametros,'planilla_extendida','toolbar=0,location=0, directories=0,status=0,menubar=0,scrollbars=1,resizable=1,left=0,top=0,height=600,width=850');

            });

        }

        function _fechasIngresadasCorrectamente ( ) {

            if ( $fechaSolicitudHasta.val() != '' && $fechaSolicitudDesde.val() == '' ) {

                $("#frm_fechaSolicitudDesde").assert(false, "Debe ingresar Fecha Solicitud (Inicio) si realizará una Búsqueda por Rango de Fecha");

                return false;

            }

            return true;

        }

        function _irAPagina ( accionPagina ) {
            ajaxContent('/RCEDAU/views/modules/solicitud_aps/solicitud_aps_worklist.php',$("#frm_busquedaSolicitudesAPS").serialize()+`&frm_runPaciente=${$rutPaciente.val()}&accion=${accionPagina}&totalPag=${totalPag}`,'#contenido');
            // ajaxContent(`${raiz}/views/modules/solicitud_aps/solicitud_aps_worklist.php`,$("#frm_busquedaSolicitudesAPS").serialize()+`&frm_runPaciente=${$rutPaciente.val()}&accion=${accionPagina}&totalPag=${totalPag}`,'#contenidoDAU','', true);

        }

        function _verificarRut ( rut ) {

            let rutValido = $.Rut.validar(rut);

            if ( rutValido == false ) {

                $("#frm_runPaciente").assert(false,'El Run Ingresado, no es válido');

            } else {

                rut     = $.Rut.quitarFormato(rut);

                rut     = rut.substring(0, rut.length-1);

                $rutPaciente.val(rut);

                _irAPagina(1);

            }

        }



        //Funciones públicas
        function accionDesplegarHistorialClinico ( ) {

            $btnAccionDesplegarHistorialClinico.on("click", _accionDesplegarHistorialClinico);

        }

        function accionDesplegarRCE ( ) {

            $btnAccionDesplegarRCE.on("click", _accionDesplegarRCE);

        }

        function buscarSolicitudesAPS ( ) {

            $btnBuscarSolicitudesAPS.on('click', _buscarSolicitudesAPS);

        }

        function cambiarConsultorio ( ) {

            $btnCambiarConsultorio.on("click", _cambiarConsultorio);

        }

        function cambioSelectEstado ( ) {

            _cambioSelectEstado();

        }

        function despliegueDatePicker ( ) {

            $.fn.datepicker.dates['es'] = {
                days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
                daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
                today: "Hoy",
                monthsTitle: "Meses",
                clear: "Borrar",
                weekStart: 1,
                format: "dd-mm-yyyy"
            };

            $fechaSolicitudDesde.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $("#date_fecha_desde"),
                language: 'es',
                endDate: '0d'
            }).on('changeDate', function(e){
                $('#date_fecha_hasta').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    language: 'es'
                }).datepicker('setStartDate', e.date);
            });

            $fechaSolicitudHasta.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd/mm/yyyy",
                container: $("#date_fecha_hasta"),
                language: 'es',
                endDate: '0d',
                startDate: '0d'
            }).on('changeDate', function(e){
                $('#date_fecha_desde').datepicker({
                    format: "dd/mm/yyyy",
                    autoclose: true,
                    language: 'es'
                }).datepicker('setEndDate', e.date);
            });

        }

        function despliegueAgendamientoSolicitud ( ) {

            $btnAgendamientoSolicitudAPS.on("click", _despliegueAgendamientoSolicitud);

        }

        function despliegueDetalleSolicitudAPS ( ) {

            $btnMostrarDetalleSolicitud.on('click', _despliegueDetalleSolicitudAPS);

        }

        function eliminarBusqueda ( ) {

            $btnEliminarParametrosBusqueda.on("click", _eliminarBusqueda);

        }

        function exportarExcel ( ) {

            _exportarExcel();

        }

        function formateoRut ( ) {

            $rutPaciente.Rut ( {

                on_error: function ( ) {

                    return false;

                },

                on_success: function ( ) {

                },

                format_on: 'keyup'

            });

        }

        function iniciarToolTip ( ) {

            $('[data-toggle="tooltip"]').tooltip()

            $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {

            $('.tooltip').addClass('animated tada');

        });

        }

        function validarCamposFormulario ( ) {

            validar("#frm_numeroDau","numero");

            validar("#frm_runPaciente","rut");

            validar("#frm_nombrePaciente","letras");

            validar("#frm_fechaSolicitudDesde","fecha");

            validar("#frm_fechaSolicitudHasta","fecha");

        }

        function primeraPagina ( ) {

            $primeraPagina.on("click", function(){

                _irAPagina(4);


            });

        }

        function paginaPrevia ( ) {

            $paginaPrevia.on("click", function(){

                _irAPagina(2);

            });

        }

        function paginaSiguiente ( ) {

            $paginaSiguiente.on("click", function(){

                _irAPagina(3);

            });

        }

        function ultimaPagina ( ) {

            $ultimaPagina.on("click", function(){

                _irAPagina(5);

            });

        }


        return {
            accionDesplegarHistorialClinico     : accionDesplegarHistorialClinico,
            accionDesplegarRCE                  : accionDesplegarRCE,
            buscarSolicitudesAPS                : buscarSolicitudesAPS,
            cambiarConsultorio                  : cambiarConsultorio,
            cambioSelectEstado                  : cambioSelectEstado,
            despliegueAgendamientoSolicitud     : despliegueAgendamientoSolicitud,
            despliegueDatePicker                : despliegueDatePicker,
            despliegueDetalleSolicitud          : despliegueDetalleSolicitudAPS,
            eliminarBusqueda                    : eliminarBusqueda,
            exportarExcel                       : exportarExcel,
            formateoRut                         : formateoRut,
            iniciarToolTip                      : iniciarToolTip,
            primeraPagina                       : primeraPagina,
            paginaPrevia                        : paginaPrevia,
            paginaSiguiente                     : paginaSiguiente,
            validarCamposFormulario             : validarCamposFormulario,
            ultimaPagina                        : ultimaPagina
        }

    })();

    enlaceBoton();
    solicitudesAPS.formateoRut();
    solicitudesAPS.validarCamposFormulario();
    solicitudesAPS.despliegueDatePicker();
    solicitudesAPS.cambioSelectEstado();
    solicitudesAPS.buscarSolicitudesAPS();
    solicitudesAPS.eliminarBusqueda();
    solicitudesAPS.exportarExcel();
    solicitudesAPS.accionDesplegarRCE();
    solicitudesAPS.iniciarToolTip();
    solicitudesAPS.accionDesplegarHistorialClinico();
    solicitudesAPS.despliegueDetalleSolicitud();
    solicitudesAPS.cambiarConsultorio();
    solicitudesAPS.despliegueAgendamientoSolicitud();
    solicitudesAPS.primeraPagina();
    solicitudesAPS.paginaPrevia();
    solicitudesAPS.paginaSiguiente();
    solicitudesAPS.ultimaPagina();

});
