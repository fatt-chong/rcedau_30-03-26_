$(document).ready(function(){

    "use strict";

    const reporteREM08 = ( function VerReporteREM08 ( ) {

        //Declaración de variables general (Reporte REM 08)
        const   $fechaResumenInicio                 = $('#frm_fechaResumenInicio'),
                $fechaResumenTermino                = $('#frm_fechaResumenTermino'),
                $btnBuscarReporteREM08              = $('#btnBuscarResporteREM08'),
                $btnResetarParametrosReporteREM08   = $('#btnEliminar');



        //Funciones privadas
        function _buscarReporteREM08 ( ) {

            if ( ! _verificarDatos() ) {

                return;

            }

            ajaxContent(`${raiz}/views/reportes/reporteREM08/reporteREM08.php`, $("#frm_despliegueParametrosBusqueda").serialize(),'#contenidoDAU','', true);

        }

        function _resetearPatrametosBusqueda ( ) {

            unsetSesion();

            ajaxContent(`${raiz}/views/reportes/reporteREM08/reporteREM08.php`, '','#contenidoDAU','', true);

        }

        function _verificarDatos ( ) {

            if ( $fechaResumenInicio.val() == null || $fechaResumenInicio.val() == '' ) {

                $('#frm_fechaResumenInicio').assert(false,'Debe ingresar fecha inicio');

                return false;

            }

            if ( $fechaResumenTermino.val() == null || $fechaResumenTermino.val() == '' ) {

                $('#frm_fechaResumenTermino').assert(false,'Debe ingresar fecha término');

                return false;

            }

            return true;

        }




        //Funciones públicas
        function buscarReporteREM08 ( ) {

            $btnBuscarReporteREM08.on("click", _buscarReporteREM08);

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

            $fechaResumenInicio.datepicker({
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

            $fechaResumenTermino.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $("#date_fecha_hasta"),
                language: 'es',
                endDate: '0d',
                startDate: '0d'
            }).on('changeDate', function(e){
                $('#date_fecha_desde').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true,
                    language: 'es'
                }).datepicker('setEndDate', e.date);
            });

        }

        function iniciarReporteREM08 ( ) {

            despliegueDatePicker();

            validarCampos();

            buscarReporteREM08();

            resetearParametrosBusqueda();

        }

        function resetearParametrosBusqueda ( ) {

            $btnResetarParametrosReporteREM08.on("click", _resetearPatrametosBusqueda);

        }

        function validarCampos ( ) {

            validar("#frm_fechaResumenInicio", "fecha");

            validar("#frm_fechaResumenTermino", "fecha");

        }




        return {

            iniciarReporteREM08 : iniciarReporteREM08

        }

    })();



    reporteREM08.iniciarReporteREM08();

});