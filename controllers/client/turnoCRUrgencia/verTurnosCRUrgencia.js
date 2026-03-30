$(document).ready(function(){
    const verTurnosCRUrgencia = ( function VerTurnosRCUrgencia ( ) {
        //Declaración variables
        const   $fechaResumenTurno               = $('#frm_fechaResumenTurno'),
                $tipoHorarioTurno                = $('#frm_tipoHorarioTurno'),   
                $btnBuscarResumenTurnoCRUrgencia = $('#btnBuscarResumenTurnoCRUrgencia'),
                $btnEliminar                     = $('#btnEliminar'),
                $btnPDFResumenTurnoRCUrgencia    = $('.verPDFResumenTurnoRCUrgencia'),
                $primeraPagina                   = $("#primero_l"),
                $paginaPrevia                    = $("#atras_l"),
                $paginaSiguiente                 = $("#siguiente_l"),
                $ultimaPagina                    = $("#ultimo_l"),
                totalPag                         = $("#totalPag").val();
        //Funciones privadas
        function _buscarResumenTurnoCRUrgencia ( ) {
            _irAPagina(1);

        }
        function _desplegarToolTip ( ) {
		    $('[data-toggle="tooltip"]').tooltip();
            $('[data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
                $('.tooltip').addClass('animated tada'); 
            });

        }
        function _eliminarFiltroBusqueda ( ) {
            unsetSesion();
            $fechaResumenTurno.val('');
            $tipoHorarioTurno.val(''); 
            _irAPagina(1);
        }
        function _irAPagina ( accionPagina ) {
            ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/verTurnosCRUrgencia.php`, $("#frm_despliegueParametrosTurno").serialize()+`&accion=${accionPagina}&totalPag=${totalPag}`,'#contenido','', true);
        }
        function _verPDFResumenTurnoCRUrgencia ( ) {
            $('.tooltip').tooltip('hide'); 
            let idPDFResumen = $(this).attr('id');
            let  arreglo = idPDFResumen.split("/");
            let parametros = { idTurnoCRUrgencia : arreglo[0] , fechaEntregaTurno : arreglo[1] };

            modalFormulario("<label class='mifuente ml-2'>Resumen Turno CR Urgencia</label>", `${raiz}/views/modules/turnoCRUrgencia/verPDFResumenTurnoCRUrgencia.php`, parametros, "#verPDFResumenTurnoCRUrencia", "modal-lg", "light",'', '');
        }
        //Funciones públicas
        function buscarResumenTurnoCRUrgencia ( ) {
            $btnBuscarResumenTurnoCRUrgencia.on('click', _buscarResumenTurnoCRUrgencia);
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
            $fechaResumenTurno.datepicker({
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                container: $("#date_fecha_desde"),
                format: 'dd/mm/yyyy',
                clearBtn: true,
                language: 'es'
            });  
        }
        function desplegarToolTip ( ) {
            _desplegarToolTip();
        }
        function eliminarFiltrosBusqueda ( ) {
            $btnEliminar.on('click', _eliminarFiltroBusqueda);
        }
        function primeraPagina ( ) {
            $primeraPagina.click(function(){
                _irAPagina(4);
            });
        }
        function paginaPrevia ( ) {
            $paginaPrevia.click(function(){
                _irAPagina(2);
            });
        }
        function paginaSiguiente ( ) {
            $paginaSiguiente.click(function(){
                _irAPagina(3);
            });
        }
        function ultimaPagina ( ) {
            $ultimaPagina.click(function(){
                _irAPagina(5);
            });
        }
        function validarCamposFormulario ( ) {
            validar("#frm_fechaResumenTurno","fecha");
        }
        function verPDFResumenTurnoRCUrgencia ( ) {
            $btnPDFResumenTurnoRCUrgencia.on('click', _verPDFResumenTurnoCRUrgencia);
        }
        //Retorno de objeto
        return {
            buscarResumenTurnoCRUrgencia    : buscarResumenTurnoCRUrgencia,
            despliegueDatePicker            : despliegueDatePicker,
            desplegarToolTip                : desplegarToolTip,
            eliminarFiltrosBusqueda         : eliminarFiltrosBusqueda,
            primeraPagina                   : primeraPagina,
            paginaPrevia                    : paginaPrevia,
            paginaSiguiente                 : paginaSiguiente,
            ultimaPagina                    : ultimaPagina,
            validarCamposFormulario         : validarCamposFormulario,        
            verPDFResumenTurnoRCUrgencia    : verPDFResumenTurnoRCUrgencia  
        }
    })();
    verTurnosCRUrgencia.despliegueDatePicker();
    verTurnosCRUrgencia.validarCamposFormulario();
    verTurnosCRUrgencia.buscarResumenTurnoCRUrgencia();
    verTurnosCRUrgencia.eliminarFiltrosBusqueda();
    verTurnosCRUrgencia.desplegarToolTip();
    verTurnosCRUrgencia.verPDFResumenTurnoRCUrgencia();
    verTurnosCRUrgencia.primeraPagina();
    verTurnosCRUrgencia.paginaPrevia();
    verTurnosCRUrgencia.paginaSiguiente();
    verTurnosCRUrgencia.ultimaPagina();
});