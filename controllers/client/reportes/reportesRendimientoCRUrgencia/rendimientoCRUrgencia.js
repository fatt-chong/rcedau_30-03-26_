$(document).ready(function(){

    const rendimientoCRUrgencia = ( function rendimientoCRUrgencia ( ) {

        //Declaración de variables general (tiempos CR urgencia)
        const   $fechaResumenInicio                         = $('#frm_fechaResumenInicio'),
                $fechaResumenTermino                        = $('#frm_fechaResumenTermino'),
                $medicoUrgencia                             = $('#frm_medicoUrgencia'),
                $btnBuscarResumenRendimientoCRUrgencia      = $('#btnBuscarReporteRendimientoCRUrgencia'),
                $btnResetarParametrosRendimientoCRUrgencia  = $('#btnEliminar'),
                $btnVerPDFResumenRendimientoCRUrgencia      = $('#btnVerPDF'),
                $btnVerExcelResumenRendimientoCRUrgancia    = $("#btnVerExcel");

        let     tablaResumenRendimientoCRUrgencia           = [];



        //Funciones privadas
        function _despligueTiemposCRUrgencia ( ) {

            if ( ! _verificarDatos() ){

                return;

            }

            ajaxContent(`${raiz}/views/modules/reportes/rendimientoCRUrgencia/rendimientoCRUrgencia.php`, $("#frm_despliegueParametrosBusqueda").serialize(),'#contenido');

        }

        function _obtenerTabla ( ) {

            let TableData = [];

            $(`#tablaReporteRendimientoCRUrgencia tr`).each(function(row, tr){

                TableData[row]={
                      "fechas"                              : $(tr).find('td:eq(0)').text()
                    , "cantidadAtendidos"                   : $(tr).find('td:eq(1)').text()
                    , "cantidadEgresados"                   : $(tr).find('td:eq(2)').text()
                    , "atendidosESI1"                       : $(tr).find('td:eq(3)').text()
                    , "egresadosESI1"                       : $(tr).find('td:eq(4)').text()
                    , "atendidosESI2"                       : $(tr).find('td:eq(5)').text()
                    , "egresadosESI2"                       : $(tr).find('td:eq(6)').text()
                    , "atendidosESI3"                       : $(tr).find('td:eq(7)').text()
                    , "egresadosESI3"                       : $(tr).find('td:eq(8)').text()
                    , "atendidosESI4"                       : $(tr).find('td:eq(9)').text()
                    , "intravenososESI4"                    : $(tr).find('td:eq(10)').text()
                    , "porcentajeIntravenososESI4"          : $(tr).find('td:eq(11)').text()
                    , "egresadosESI4"                       : $(tr).find('td:eq(12)').text()
                    , "atendidosESI5"                       : $(tr).find('td:eq(13)').text()
                    , "intravenososESI5"                    : $(tr).find('td:eq(14)').text()
                    , "porcentajeIntravenososESI5"          : $(tr).find('td:eq(15)').text()
                    , "egresadosESI5"                       : $(tr).find('td:eq(16)').text()
                    , "solicitudEspecialistasPedidas"       : $(tr).find('td:eq(17)').text()
                    , "solicitudEspecialistasRealizadas"    : $(tr).find('td:eq(18)').text()

                }

            });

            TableData.shift();

            TableData.shift();

            tablaResumenRendimientoCRUrgencia = TableData;

        }

        function _openWindowWithPost (url, windowoption, name, params) {

            let form = document.createElement("form");

            form.setAttribute("method", "post");

            form.setAttribute("action", url);

            form.setAttribute("target", name);

            for ( let i in params ) {

                if ( params.hasOwnProperty(i) ) {

                    let input = document.createElement('input');

                    input.type = 'hidden';

                    input.name = i;

                    input.value = params[i];

                    form.appendChild(input);

                }

            }

            document.body.appendChild(form);

            window.open("post.htm", name, windowoption);

            form.submit();

            document.body.removeChild(form);

        }

        function _resetearParametrosRendimientoCRUrgencia ( ) {

            unsetSesion();

            ajaxContent(`${raiz}/views/modules/reportes/rendimientoCRUrgencia/rendimientoCRUrgencia.php`, '','#contenido');

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

            if ( $medicoUrgencia.val() == null || $medicoUrgencia.val() == '' ) {

                $('#frm_medicoUrgencia').assert(false,'Debe seleccionar médico');

                return false;

            }

            return true;

        }


        function _verExcelResumenRendimientoCRUrgencia ( ) {

            if ( ! _verificarDatos() ){

                return;

            }

            _obtenerTabla();

            const arraySelectMedico = $medicoUrgencia.val().split('/');

            const nombreMedico = arraySelectMedico[1];

            const parametros = { 'fechaInicio' : $fechaResumenInicio.val() , 'fechaTermino' : $fechaResumenTermino.val() , 'nombreMedico' : nombreMedico , 'tablaResumenRendimientoCRUrgencia' : JSON.stringify(tablaResumenRendimientoCRUrgencia) };

            const url = `${raiz}/views/modules/reportes/rendimientoCRUrgencia/excelRendimientoCRUrgencia.php`;

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


        }

        function _verPDFResumenRendimientoCRUrgencia ( ) {

            if ( ! _verificarDatos() ){

                return;

            }

            const parametros = {'fechaAnterior' : $fechaResumenInicio.val() , 'fechaActual' : $fechaResumenTermino.val(), 'medicoUrgencia' : $medicoUrgencia.val() }

            modalFormulario_noCabecera("Reporte Rendimiento CR Urgencia", `${raiz}/views/modules/reportes/rendimientoCRUrgencia/pdfRendimientoCRUrgencia.php`, parametros, "#pdfReporteRendimientoCRUrgencia", "modal-lg", "", "fas fa-plus");

        }



        //Funciones públicas
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

        function despliegueSelectPicker ( ) {

            $('#frm_medicoUrgencia').selectpicker({
                size: 15,
                noneSelectedText : 'Seleccione'
            });

        }

        function despliegueRendimientoCRUrgencia ( ) {

            $btnBuscarResumenRendimientoCRUrgencia.on('click', _despligueTiemposCRUrgencia);

        }

        function resetearParametrosDespliegueRendimientoCRUrgencia ( ) {

            $btnResetarParametrosRendimientoCRUrgencia.on('click', _resetearParametrosRendimientoCRUrgencia);

        }

        function validarCampos ( ) {

            validar("#frm_fechaResumenInicio", "fecha");

            validar("#frm_fechaResumenTermino", "fecha");

        }

        function verExcelResumenRendimientoCRUrgencia ( ) {

            $btnVerExcelResumenRendimientoCRUrgancia.on("click", _verExcelResumenRendimientoCRUrgencia)

        }

        function verPDFResumenTiemposCRUrgencia ( ) {

            $btnVerPDFResumenRendimientoCRUrgencia.on('click', _verPDFResumenRendimientoCRUrgencia);

        }





        return {
            validarCampos                                       : validarCampos,
            despliegueDatePicker                                : despliegueDatePicker,
            despliegueSelectPicker                              : despliegueSelectPicker,
            despliegueRendimientoCRUrgencia                     : despliegueRendimientoCRUrgencia,
            resetearParametrosDespliegueRendimientoCRUrgencia   : resetearParametrosDespliegueRendimientoCRUrgencia,
            verExcelResumenRendimientoCRUrgencia                    : verExcelResumenRendimientoCRUrgencia,
            verPDFResumenTiemposCRUrgencia                      : verPDFResumenTiemposCRUrgencia
        }

    })();

    rendimientoCRUrgencia.validarCampos();
    rendimientoCRUrgencia.despliegueDatePicker();
    rendimientoCRUrgencia.despliegueSelectPicker();
    rendimientoCRUrgencia.despliegueRendimientoCRUrgencia();
    rendimientoCRUrgencia.resetearParametrosDespliegueRendimientoCRUrgencia();
    rendimientoCRUrgencia.verExcelResumenRendimientoCRUrgencia();
    rendimientoCRUrgencia.verPDFResumenTiemposCRUrgencia();
    enlaceBoton();

});