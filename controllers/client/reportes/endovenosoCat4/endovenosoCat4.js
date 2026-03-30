"use strict";

$(document).ready(function(){

    const reporteEndovenosoCat4 = ( function reporteEndovenosoCat4 ( ) {

        //Declaración de variables
        const formulario              = "#frm_reporteEndovenosoCat4",
              $inputFechaInicio       = $(`${formulario} #frm_fechaInicioReporte`),
              $inputFechaTermino      = $(`${formulario} #frm_fechaTerminoReporte`);

        const divDespliegueReporte    = "#divDespliegueReporteEndovenosoCat4",
              tablaInformacionReporte = "#tablaReporteEndovenosoCat4";


        const $btnBuscar              = $(`${formulario} #btnBuscarResumenEndovenosoCat4`),
              $btnLimpiar             = $(`${formulario} #btnEliminar`),
              $btnVerPDF              = $(`${formulario} #btnVerPDF`),
              $btnVerExcel            = $(`${formulario} #btnVerExcel`);

        let   informacionReporte      = [];



        //Funciones privadas
        function _busquedaEndovenosoCat4 ( ) {

            $btnBuscar.on("click", function(){

                $(`${divDespliegueReporte}`).hide();

                $(`${tablaInformacionReporte} thead`).empty();

                $(`${tablaInformacionReporte} tbody`).empty();

                if ( ! _verificarDatos() ) {

                    return;

                }

                if ( ! _verificarRangoFechas() ) {

                    return;

                }

                _obtenerReporteEndovenosoCat4();

            });

        }



        function _desplegarEncabezado ( ) {

                return `
                        <tr>

                            <th width="100%" colspan="15" style="text-align:center;">REPORTE ESI-4 CON INDICACIÓN TRATAMIENTO ENDOVENOSO (DESDE ${$inputFechaInicio.val()}  -  HASTA ${$inputFechaTermino.val()})</th>

                        </tr>

                        <tr>

                            <th width="4%" style="text-align:center;">DAU</th>

                            <th width="5%" style="text-align:center;">Id Pac.</th>

                            <th width="5%" style="text-align:center;">Ate.</th>

                            <th width="10%" style="text-align:center;">Nombre</th>

                            <th width="7%" style="text-align:center;">RUN</th>

                            <th width="4%" style="text-align:center;">Edad</th>

                            <th width="8%" style="text-align:center;">Tratamiento</th>

                            <th width="7%" style="text-align:center;">F. Admisión</th>

                            <th width="7%" style="text-align:center;">F. Indicación</th>

                            <th width="7%" style="text-align:center;">U. Indicación</th>

                            <th width="7%" style="text-align:center;">F. Inicio</th>

                            <th width="7%" style="text-align:center;">U. Inicia</th>

                            <th width="7%" style="text-align:center;">F. Aplicación</th>

                            <th width="7%" style="text-align:center;">U. Aplica</th>

                            <th width="8%" style="text-align:center;">CIE 10</th>

                        </tr>
                        `;

        }



        function _desplegarFila ( indice ) {

            var transexual   = informacionReporte[indice].transexual;
            var nombreSocial = informacionReporte[indice].nombreSocial;
            var nombrePac    = informacionReporte[indice]["Nombre Paciente"];
            var nombre = infoNombreDoc(transexual,nombreSocial,nombrePac)

            return  `
                    <tr nobr="true">

                        <td width="4%" style="text-align:center;">${informacionReporte[indice]["Id Dau"]}</td>

                        <td width="5%" style="text-align:center">${informacionReporte[indice]["Id Paciente"]}</td>

                        <td width="5%" style="text-align:center;">${informacionReporte[indice]["Tipo Atención"]}</td>

                        <td width="10%">${nombre}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["RUT Paciente"]}</td>

                        <td width="4%" style="text-align:center;">${informacionReporte[indice]["Edad Paciente"]}</td>

                        <td width="8%">${informacionReporte[indice]["Tratamiento"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Fecha Admisión"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Fecha Solicitud Indicación"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Usuario Inserta Solicitud"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Fecha Inicio Indicación"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Usuario Inicia Indicación"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Fecha Aplica Indicación"]}</td>

                        <td width="7%" style="text-align:center;">${informacionReporte[indice]["Usuario Aplica Indicación"]}</td>

                        <td width="8%" style="text-align:justify;">${informacionReporte[indice]["CIE10"]}</td>

                    </tr>
                    `;


        }



        function _despliegueReporteEndovenosoCat4 ( ) {

            if ( informacionReporte.length === 0 ) {

                modalMensajeBtnExit('Error al ver reporte', '<strong>No se han encontrado resultados</strong> en las fechas señaladas', 'msjError', '500', '300', 'danger', '');

                return;

            }

            $btnLimpiar.show();

            $(`${divDespliegueReporte}`).show();

            let html = "";

            $(`${tablaInformacionReporte} thead`).empty();

            $(`${tablaInformacionReporte} tbody`).empty();

            html += _desplegarEncabezado();

            $(`${tablaInformacionReporte} > thead`).append(html);

            for ( let indice in informacionReporte ) {

                html = "";

                html += _desplegarFila(indice);

                $(`${tablaInformacionReporte} > tbody`).append(html);

            }

        }



        function __formatearFechaAMilisegundos ( fecha ) {

            const [dia, mes, anio] = fecha.split("-");

            return (new Date(anio, mes-1, dia)).getTime();

        }



        function _inicializarCamposFechas ( ) {

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

            $inputFechaInicio.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $("#date_fecha_inicio"),
                language: 'es',
                endDate: '0d'
            }).on('changeDate', function(e){
                $('#date_fecha_termino').datepicker({
                    format: "dd-mm-yyyy",
                    language: 'es',
                    autoclose: true
                }).datepicker('setStartDate', e.date);
            });

            $inputFechaTermino.datepicker({
                todayHighlight: true,
                autoclose: true,
                format: "dd-mm-yyyy",
                container: $("#date_fecha_termino"),
                language: 'es',
                endDate: '0d',
                startDate: '0d'
            }).on('changeDate', function(e){
                $('#date_fecha_inicio').datepicker({
                    format: "dd-mm-yyyy",
                    language: 'es',
                    autoclose: true
                }).datepicker('setEndDate', e.date);
            });

        }



        function _limpiarBusquedaEnfermedadeEpidemiologicas ( ) {

            $btnLimpiar.on("click", function(){

                $(`${formulario}`)[0].reset();

                $(`${divDespliegueReporte}`).hide();

                $(`${tablaInformacionReporte} thead`).empty();

                $(`${tablaInformacionReporte} tbody`).empty();

            });

        }



        function _obtenerReporteEndovenosoCat4 ( ) {

            const functionCallBack = ( respuestaAjaxRequest ) => {

                if ( respuestaAjaxRequest === null || respuestaAjaxRequest === undefined || respuestaAjaxRequest.length === 0 ) {

                    informacionReporte = [];

                }

                informacionReporte = respuestaAjaxRequest;

                _despliegueReporteEndovenosoCat4();


            }

            const parametrosAEnviar =   {
                                              fechaInicio : $inputFechaInicio.val()
                                            , fechaTermino: $inputFechaTermino.val()
                                            , accion      : "obtenerReporteEndovenosoCat4"
                                        };

            ajaxRequest(
                                                        `${raiz}/controllers/server/reportes/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        "Cargando Reporte...",
                                                        functionCallBack
                                                    );

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



        function _validarCampos ( ) {

            validar("#frm_fechaInicioReporte", "fecha");

            validar("#frm_fechaTerminoReporte", "fecha");

        }



        function _verificarDatos ( ) {

            if ( $inputFechaInicio.val() == null || $inputFechaInicio.val() == '' ) {

                $('#frm_fechaInicioReporte').assert(false,'Debe ingresar fecha inicio');

                return false;

            }

            if ( $inputFechaTermino.val() == null || $inputFechaTermino.val() == '' ) {

                $('#frm_fechaTerminoReporte').assert(false,'Debe ingresar fecha término');

                return false;

            }

            return true;

        }



        function _verExcel ( ) {

            $btnVerExcel.on("click", function(){

                if ( informacionReporte.length == 0 ) {

                    modalMensajeBtnExit('Error al ver Excel', 'Debe primero <strong>seleccionar un rago de fechas</strong> para poder obtener una vista en Excel del resumen ESI-4 con indicación tratamiento endovenoso', 'msjError', '500', '300', 'danger', '');

                    return;

                }

                const parametros = {
                                          'fechaInicio'       : $inputFechaInicio.val()
                                        , 'fechaTermino'      : $inputFechaTermino.val()
                                    };

                const url = `${raiz}/views/reportes/endovenosoCat4/excelEndovenosoCat4.php`;

                _openWindowWithPost(url, "toolbar=0,location=0, directories=0,status=0,menubar=0,scrollbars=1,resizable=1,left=0,top=0,height=600,width=850", "NewFile", parametros);

            });

        }



        function _verPDF ( ) {

            $btnVerPDF.on("click", function(){

                if ( informacionReporte.length == 0 ) {

                    modalMensajeBtnExit('Error al ver PDF', 'Debe primero <strong>seleccionar un rago de fechas</strong> para poder obtener una vista en PDF del resumen de ESI-4 con indicación tratamiento endovenoso', 'msjError', '500', '300', 'danger', '');

                    return;

                }

                let parametrosAEnviar = {};

                parametrosAEnviar = {
                                          "fechaInicio" : $inputFechaInicio.val()
                                        , "fechaTermino": $inputFechaTermino.val()
                                        , "htmlPDF"     : $(`${tablaInformacionReporte}`)
                                                            .html()
                                                            .replace(/&/g, '&amp;')
                                                            .replace(/>/g, '&gt;')
                                                            .replace(/</g, '&lt;')
                                                            .replace(/"/g, '&quot;')
                                                            .replace(/'/g, '&apos;')
                                    };

                const imprimir = function () {

                    $("#pdf").get(0).contentWindow.focus();

                    $("#pdf").get(0).contentWindow.print();

                }

                const botones = 	[
                                        { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
                                    ]

                modalFormulario('PDF Reporte Enfermedades Epidemiológicas', `${raiz}/views/reportes/endovenosoCat4/pdfEndovenosoCat4.php`, parametrosAEnviar, '#pdf', '66%', '100%', botones);

                $('.modal-body').css('max-height','calc(100vh - 135px)');

                $('.modal-body').css('overflow','auto');

            });

        }



        function _verificarRangoFechas ( ) {

            const milisegundosFechaInicio  = __formatearFechaAMilisegundos($inputFechaInicio.val()),
                  milisegundosFechaTermino = __formatearFechaAMilisegundos($inputFechaTermino.val());

            const diferenciaFechas = milisegundosFechaTermino - milisegundosFechaInicio;

            if ( diferenciaFechas < 0 ) {

                $('#frm_fechaInicioReporte').assert(false,'Fecha inicio debe ser menor a fecha término');

                return false;

            }

            return true;

        }



        //Funciones públicas
        function busquedaEndovenosoCat4 ( ) {

            _busquedaEndovenosoCat4();

        }



        function iniciarReporteEndonvenosoCat4 ( ) {

            $btnLimpiar.hide();

            $(`${divDespliegueReporte}`).hide();

            _validarCampos();

            _inicializarCamposFechas();

        }



        function limpiarBusqueda ( ) {

            _limpiarBusquedaEnfermedadeEpidemiologicas();

        }



        function verExcel ( ) {

            _verExcel();

        }



        function verPDF ( ) {

            _verPDF();

        }



        return {

              busquedaEndovenosoCat4     : busquedaEndovenosoCat4
            , inicarReporteEndovenosoCat4: iniciarReporteEndonvenosoCat4
            , limbarBusqueda             : limpiarBusqueda
            , verExcel                   : verExcel
            , verPDF                     : verPDF

        }

    })();

    reporteEndovenosoCat4.inicarReporteEndovenosoCat4();
    reporteEndovenosoCat4.busquedaEndovenosoCat4();
    reporteEndovenosoCat4.verPDF();
    reporteEndovenosoCat4.verExcel();
    reporteEndovenosoCat4.limbarBusqueda();

});