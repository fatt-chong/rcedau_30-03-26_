"use strict";

$(document).ready(function(){

    const reporteEnfermedadesEpidemiologicas = ( function reporteEnfermedadesEpidemiologicas ( ) {

        //Declaración de variables
        const formulario              = "#frm_reporteEnfermedadesEpidemiologicas",
              $inputFechaInicio       = $(`${formulario} #frm_fechaInicioReporte`),
              $inputFechaTermino      = $(`${formulario} #frm_fechaTerminoReporte`);

        const divDespliegueReporte    = "#divDespliegueReporteEnfermedadesEpidemiologicas",
              tablaInformacionReporte = "#tablaReporteEnfermedadesEpidemiologicas";


        const $btnBuscar              = $(`${formulario} #btnBuscarResumenEnfermedadesEpidemiologicas`),
              $btnLimpiar             = $(`${formulario} #btnEliminar`),
              $btnVerPDF              = $(`${formulario} #btnVerPDF`),
              $btnVerExcel            = $(`${formulario} #btnVerExcel`);

        let   informacionReporte      = [];



        //Funciones privadas
        function _busquedaEnfermedadesEpidemiologicas ( ) {

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

                _obtenerReporteEnfermedadesEpidemiologicas();

                _despliegueReporteEnfermedadesEpidemiologicas();

            });

        }



        function _desplegarEncabezado ( ) {

                return `
                        <tr>

                            <th width="100%" colspan="9" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">REPORTE ENFERMEDADES EPIDEMIOLÓGICAS (DESDE ${$inputFechaInicio.val()}  -  HASTA ${$inputFechaTermino.val()})</th>

                        </tr>

                        <tr>

                            <th width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">DAU</th>

                            <th width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Nombre</th>

                            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">RUN</th>

                            <th width="11%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Fecha Admisión</th>

                            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Fecha Cierre</th>

                            <th width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Indicación Egreso</th>

                            <th width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Destino</th>

                            <th width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">CIE10</th>

                            <th width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">Hipótesis Final</th>

                        </tr>
                        `;

        }



        function _desplegarFila ( indice ) {

            const destino = ( informacionReporte[indice].destino != undefined || informacionReporte[indice].destino != null ) ? capitalizarString(informacionReporte[indice].destino) : "";
            // var nombre = "";
            // if(informacionReporte[indice].transexual == 'S' || informacionReporte[indice].transexual == 's' ){
            //     if( informacionReporte[indice].nombreSocial!= ""){
            //         nombre = '<b>'+informacionReporte[indice].nombreSocial.toUpperCase()+'</b> / '+informacionReporte[indice].nombre.toUpperCase();
            //     }
            // }else{
            //     nombre = informacionReporte[indice].nombre.toUpperCase();
            // }
            var transexual   = informacionReporte[indice].transexual;
            var nombreSocial = informacionReporte[indice].nombreSocial;
            var nombrePac    = informacionReporte[indice].nombre;
            var nombre = infoNombreDoc(transexual,nombreSocial,nombrePac)


            return  `
                    <tr>

                        <td width="7%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${informacionReporte[indice].idDau}</td>

                        <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${nombre}</td>

                        <td width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${informacionReporte[indice].run}</td>

                        <td width="11%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${informacionReporte[indice].fechaAdmision}</td>

                        <td width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${informacionReporte[indice].fechaCierre}</td>

                        <td width="12%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${capitalizarString(informacionReporte[indice].indicacionEgreso)}</td>

                        <td width="10%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${destino}</td>

                        <td width="13%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >${informacionReporte[indice].CIE10}</td>

                        <td width="15%" style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center">${informacionReporte[indice].hipotesisFinal}</td>

                    </tr>
                    `;


        }



        function _despliegueReporteEnfermedadesEpidemiologicas ( ) {

            if ( informacionReporte.length === 0 ) {
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al ver PDF </h4>  <hr>  <p class="mb-0"><strong>No se han encontrado resultados</strong> en las fechas señaladas.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");

                // modalMensajeBtnExit('Error al ver reporte', '<strong>No se han encontrado resultados</strong> en las fechas señaladas', 'msjError', '500', '300', 'danger', '');

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



        function _obtenerReporteEnfermedadesEpidemiologicas ( ) {

            const parametrosAEnviar =   {
                                              fechaInicio : $inputFechaInicio.val()
                                            , fechaTermino: $inputFechaTermino.val()
                                            , accion      : "obtenerReporteEnfermedadesEpidemiologicas"
                                        };

            const respuestaAjaxRequest = ajaxRequest(
                                                        `${raiz}/controllers/server/reportes/main_controller.php`,
                                                        parametrosAEnviar,
                                                        'POST',
                                                        'JSON',
                                                        1,
                                                        undefined
                                                    );

            if ( respuestaAjaxRequest === null || respuestaAjaxRequest === undefined || respuestaAjaxRequest.length === 0 ) {

                informacionReporte = [];

                return;

            }

            informacionReporte = respuestaAjaxRequest;

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



        function _verExcelReporteEnfermedadesEpidemiologicas ( ) {

            $btnVerExcel.on("click", function(){

                if ( informacionReporte.length == 0 ) {
                        var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al ver Excel </h4>  <hr>  <p class="mb-0">Debe primero <strong>seleccionar un rago de fechas</strong> para poder obtener una vista en Excel del resumen de enfermedades epidemiológicas.</p></div>';
                        modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                        return;
                    }
                const parametros = {
                    fechaInicio: $inputFechaInicio.val(),
                    fechaTermino: $inputFechaTermino.val(),
                    informacionReporte: JSON.stringify(informacionReporte)
                };

                const url = raiz + '/views/modules/reportes/enfermedadesEpidemiologicas/excelEnfermedadesEpidemiologicas.php';

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


            });

        }



        function _verPDFReporteEnfermedadesEpidemiologicas ( ) {
            $btnVerPDF.on("click", function(){
                if ( informacionReporte.length == 0 ) {
                    var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error al ver PDF </h4>  <hr>  <p class="mb-0">Debe primero <strong>seleccionar un rago de fechas</strong> para poder obtener una vista en PDF del resumen de enfermedades epidemiológicas.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");


                    // modalMensajeBtnExit('Error al ver PDF', 'Debe primero <strong>seleccionar un rago de fechas</strong> para poder obtener una vista en PDF del resumen de enfermedades epidemiológicas', 'msjError', '500', '300', 'danger', '');
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
  
                modalFormulario_noCabecera("PDF Reporte Enfermedades Epidemiológicas",`${raiz}/views/modules/reportes/enfermedadesEpidemiologicas/pdfEnfermedadesEpidemiologicas.php`, parametrosAEnviar, "#pdf", "modal-lg", "", "fas fa-plus");

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
        function busquedaEnfermedadesEpidemiologicas ( ) {

            _busquedaEnfermedadesEpidemiologicas();

        }



        function iniciarReporteEnfermedadesEpidemiologicas ( ) {

            $btnLimpiar.hide();

            $(`${divDespliegueReporte}`).hide();

            _validarCampos();

            _inicializarCamposFechas();

        }



        function limpiarBusquedaEnfermedadesEpidemiologicas ( ) {

            _limpiarBusquedaEnfermedadeEpidemiologicas();

        }



        function verExcelReporteEnfermedadesEpidemiologicas ( ) {

            _verExcelReporteEnfermedadesEpidemiologicas();

        }



        function verPDFReporteEnfermedadesEpidemiologicas ( ) {

            _verPDFReporteEnfermedadesEpidemiologicas();

        }



        return {

              busquedaEnfermedadesEpidemiologicas       : busquedaEnfermedadesEpidemiologicas
            , iniciarReporteEnfermedadesEpidemiologicas : iniciarReporteEnfermedadesEpidemiologicas
            , limpiarBusquedaEnfermedadesEpidemiologicas: limpiarBusquedaEnfermedadesEpidemiologicas
            , verExcelReporteEnfermedadesEpidemiologicas: verExcelReporteEnfermedadesEpidemiologicas
            , verPDFReporteEnfermedadesEpidemiologicas  : verPDFReporteEnfermedadesEpidemiologicas

        }

    })();

    reporteEnfermedadesEpidemiologicas.iniciarReporteEnfermedadesEpidemiologicas();
    reporteEnfermedadesEpidemiologicas.busquedaEnfermedadesEpidemiologicas();
    reporteEnfermedadesEpidemiologicas.verPDFReporteEnfermedadesEpidemiologicas();
    reporteEnfermedadesEpidemiologicas.verExcelReporteEnfermedadesEpidemiologicas();
    reporteEnfermedadesEpidemiologicas.limpiarBusquedaEnfermedadesEpidemiologicas();

});