$(document).ready(function(){

    const tiemposCRUrgencia = ( function VerTurnosRCUrgencia ( ) {

        //Declaración de variables general (tiempos CR urgencia)
        const   $fechaResumenInicio                         = $('#frm_fechaResumenInicio'),
                $fechaResumenTermino                        = $('#frm_fechaResumenTermino'),
                $btnBuscarResumenTiemposCRUrgencia          = $('#btnBuscarResumenTiemposTurnoCRUrgencia'),
                $btnResetarParametrosTiemposCRUrgencia      = $('#btnEliminar'),
                $btnVerPDFResumenTiemposCRUrgencia          = $('#btnVerPDF'),
                $btnVerExcelResumenTiemposCRUrgencia        = $("#btnVerExcel");

        //Declaración de variables para tablas
        let tablaDemandaUrgenciaAdultoPediatrica        = [],
            tablaResumenTiempoEsperaAdultosPediatricos  = [],
            tablaResumenTiempoEsperaAdultoDeciles       = [],
            tablaResumenTiempoEsperaPediatricoDeciles   = [],
            tablaResumenCumplimientoCategorizacionESI   = [],
            tablaResumenDiagnosticosInespecificos       = [],
            tablaResumenTiemposIndicacionesLaboratorio  = [],
            tablaResumenTiemposIndicacionesImagenologia = [];

        //Declaración de variables (demanda urgencia adulto pediatrico)
        const   $claseDemandaUrgenciaAdultoPediatrico = $('.demandaUrgencia');

        //Declaración de variables (resumen tiempos espera)
        const   $claseResumenTiemposEspera = $('.resumenTiemposEspera');

        //Declaración de variables (resumen tiempos espera deciles)
        const   $claseResumenTiemposEsperaDeciles = $('.resumenTiemposEsperaDeciles');

        //Declaración de variables (cumplimiento categorizacion ESI)
        const   $claseCumplimientoCategorizacionESI = $('.cumplimientoCategorizacionESI');

        //Declaración de variables (diagnóticos inespecíficos)
        const   $claseDiagnosticosInespecificos = $('.diagnosticosInespecificos');

        //Declaración de variables (tiempos laboratorio)
        const   $claseTiemposLaboratorio = $('.tiemposLaboratorio');

        //Declaración de variables (tiempos imagenología)
        const   $claseTiemposImagenologia = $('.tiemposImagenologia');



        //Funciones privadas
        function _despligueTiemposCRUrgencia ( ) {

            if ( ! _verificarDatos() ){

                return;

            }

            ajaxContent('/RCEDAU/views/modules/reportes/tiemposCRUrgencia/tiemposCRUrgencia.php',$("#frm_despliegueParametrosBusqueda").serialize(),'#contenido');

        }

        function _obtenerTablas ( ) {

            _obtenerTablaDemandaUrgenciaAdultoPediatrico();

            _obtenerTablaResumenTiempoEsperaAdultoPediatrico();

            _obtenerTablaResumeTiempoEsperaAdultoDeciles();

            _obtenerTablaResumenTiempoEsperaPediatricoDeciles();

            _obtenerTablaResumenCumplimientoCategorizacionESI();

            _obtenerTablaResumenDiagnosticosInespecificos();

            _obtenerTablaResumenTiemposIndicacionesLaboratorio();

            _obtenerTablaResumenTiemposIndicacionesImagenologia();


        }

        function _obtenerTablaDemandaUrgenciaAdultoPediatrico ( ) {

            let TableData = [];

            $(`#tablaDemandaUrgenciaAdultoPediatrico tr`).each(function(row, tr){

                TableData[row]={
                      "tipoDemanda"          : $(tr).find('td:eq(0)').text()
                    , "adultos"              : $(tr).find('td:eq(1)').text()
                    , "adultosPorcentaje"    : $(tr).find('td:eq(2)').text()
                    , "pediatricos"          : $(tr).find('td:eq(3)').text()
                    , "pediatricosPorcentaje": $(tr).find('td:eq(4)').text()
                    , "todos"                : $(tr).find('td:eq(5)').text()
                    , "todosPorcentaje"      : $(tr).find('td:eq(6)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            tablaDemandaUrgenciaAdultoPediatrica = TableData;

        }

        function _obtenerTablaResumenTiempoEsperaAdultoPediatrico ( ) {

            let TableData = [];

            $(`#tablaResumenTiemposEsperaAdultoPediatrico tr`).each(function(row, tr){

                TableData[row]={
                      "tipCategorizacion"             : $(tr).find('td:eq(0)').text()
                    , "cantidadDauAdulto"             : $(tr).find('td:eq(1)').text()
                    , "tiempoEsperaPromedioAdulto"    : $(tr).find('td:eq(2)').text()
                    , "tiempoEsperaMaximoAdulto"      : $(tr).find('td:eq(3)').text()
                    , "cantidadDauPediatrico"         : $(tr).find('td:eq(4)').text()
                    , "tiempoEsperaPromedioPediatrico": $(tr).find('td:eq(5)').text()
                    , "tiempoEsperaMximoPediatrico"   : $(tr).find('td:eq(6)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            tablaResumenTiempoEsperaAdultosPediatricos = TableData;

        }

        function _obtenerTablaResumeTiempoEsperaAdultoDeciles ( ) {

            let TableData = [];

            $(`#tablaResumenTiemposEsperaDeciles-1 tr`).each(function(row, tr){

                TableData[row]={
                      "tipCategorizacion": $(tr).find('td:eq(0)').text()
                    , "d1"               : $(tr).find('td:eq(1)').text()
                    , "d2"               : $(tr).find('td:eq(2)').text()
                    , "d3"               : $(tr).find('td:eq(3)').text()
                    , "d4"               : $(tr).find('td:eq(4)').text()
                    , "d5"               : $(tr).find('td:eq(5)').text()
                    , "d6"               : $(tr).find('td:eq(6)').text()
                    , "d7"               : $(tr).find('td:eq(7)').text()
                    , "d8"               : $(tr).find('td:eq(8)').text()
                    , "d9"               : $(tr).find('td:eq(9)').text()
                    , "d10"              : $(tr).find('td:eq(10)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            tablaResumenTiempoEsperaAdultoDeciles = TableData;

        }

        function _obtenerTablaResumenTiempoEsperaPediatricoDeciles ( ) {

            let TableData = [];

            $(`#tablaResumenTiemposEsperaDeciles-2 tr`).each(function(row, tr){

                TableData[row]={
                      "tipCategorizacion": $(tr).find('td:eq(0)').text()
                    , "d1"               : $(tr).find('td:eq(1)').text()
                    , "d2"               : $(tr).find('td:eq(2)').text()
                    , "d3"               : $(tr).find('td:eq(3)').text()
                    , "d4"               : $(tr).find('td:eq(4)').text()
                    , "d5"               : $(tr).find('td:eq(5)').text()
                    , "d6"               : $(tr).find('td:eq(6)').text()
                    , "d7"               : $(tr).find('td:eq(7)').text()
                    , "d8"               : $(tr).find('td:eq(8)').text()
                    , "d9"               : $(tr).find('td:eq(9)').text()
                    , "d10"              : $(tr).find('td:eq(10)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            tablaResumenTiempoEsperaPediatricoDeciles = TableData;

        }

        function _obtenerTablaResumenCumplimientoCategorizacionESI ( ) {

            let TableData = [];

            $(`#tablaResumenCumplimientoCategorizacionESI tr`).each(function(row, tr){

                TableData[row]={
                      "tipCategorizacion"                   : $(tr).find('td:eq(0)').text()
                    , "cantidadDauAdulto"                   : $(tr).find('td:eq(1)').text()
                    , "atendidosATiempoAdulto"              : $(tr).find('td:eq(2)').text()
                    , "atendidosATiempoAdultoPorcentaje"    : $(tr).find('td:eq(3)').text()
                    , "cantidadDauPediatrico"               : $(tr).find('td:eq(4)').text()
                    , "atendidosATiempoPediatrico"          : $(tr).find('td:eq(5)').text()
                    , "atendidosATiempoPediatricoPorcentaje": $(tr).find('td:eq(6)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            tablaResumenCumplimientoCategorizacionESI = TableData;

        }

        function _obtenerTablaResumenDiagnosticosInespecificos ( ) {

            let TableData = [];

            $(`#tablaResumenDiagnosticosInespecificos tr`).each(function(row, tr){

                TableData[row]={
                      "tipoDiagnostico"      : $(tr).find('td:eq(0)').text()
                    , "adultos"              : $(tr).find('td:eq(1)').text()
                    , "adultosPorcentaje"    : $(tr).find('td:eq(2)').text()
                    , "pediatricos"          : $(tr).find('td:eq(3)').text()
                    , "pediatricosPorcenjate": $(tr).find('td:eq(4)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            tablaResumenDiagnosticosInespecificos = TableData;

        }

        function _obtenerTablaResumenTiemposIndicacionesLaboratorio ( ) {

            let TableData = [];

            $(`#tablaResumenTiemposIndicacionesLaboratorio tr`).each(function(row, tr){

                TableData[row]={
                      "cantidadDau"                                 : $(tr).find('td:eq(0)').text()
                    , "cantidadIndicaciones"                        : $(tr).find('td:eq(1)').text()
                    , "tiempoPromedioIndicacionTomaMuestra"         : $(tr).find('td:eq(2)').text()
                    , "tiempoMaximoIndicacionTomaMuestra"           : $(tr).find('td:eq(3)').text()
                    , "tiempoMinimoIndicacionTomaMuestra"           : $(tr).find('td:eq(4)').text()
                    , "tiempoPromedioIndicacionMuestraRecepcion"    : $(tr).find('td:eq(5)').text()
                    , "tiempoMaximoIndicacionMuestraRecepcion"      : $(tr).find('td:eq(6)').text()
                    , "tiempoMinimoIndicacionMuestraRecepcion"      : $(tr).find('td:eq(7)').text()
                    , "tiempoPromedioIndicacionRecepcionRealizacion": $(tr).find('td:eq(8)').text()
                    , "tiempoMaximoIndicacionRecepcionRealizacion"  : $(tr).find('td:eq(9)').text()
                    , "tiempoMinimoIndicacionRecepcionRealizacion"  : $(tr).find('td:eq(10)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            tablaResumenTiemposIndicacionesLaboratorio = TableData;

        }

        function _obtenerTablaResumenTiemposIndicacionesImagenologia ( ) {

            let TableData = [];

            $(`#tablaResumenTiemposIndicacionesImagenologia tr`).each(function(row, tr){

                TableData[row]={
                      "tipoExamen"                            : $(tr).find('td:eq(0)').text()
                    , "cantidadDau"                           : $(tr).find('td:eq(1)').text()
                    , "cantidadIndicaciones"                  : $(tr).find('td:eq(2)').text()
                    , "tiempoPromedio"                        : $(tr).find('td:eq(3)').text()
                    , "tiempoMaximo"                          : $(tr).find('td:eq(4)').text()
                    , "tiempoMinimo"                          : $(tr).find('td:eq(5)').text()
                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            tablaResumenTiemposIndicacionesImagenologia = TableData;

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

        function _resetearParametrosDespliegueTiemposCRUrgencia ( ) {

            unsetSesion();
            ajaxContent('/RCEDAU/views/modules/reportes/tiemposCRUrgencia/tiemposCRUrgencia.php','','#contenido');

            // ajaxContent(`${raiz}/views/reportes/tiemposCRUrgencia/tiemposCRUrgencia.php`, '','#contenido','', true);

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
// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\reportes\tiemposCRUrgencia\tiemposLaboratorio\detalleResumenTiemposLaboratorio.php
        function _verDetalleCumplimientoCategorizacionESI(tipoCategorizacion) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoCategorizacion=" + tipoCategorizacion;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/cumplimientoCategorizacionESI/detalleCumplimientoCategorizacionESI.php`, parametros, "#detalleCumplimientoCategorizacionESI", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleDemandaUrgenciaAdultoPediatrico(tipoDetalleADesplegar) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoDetalleADesplegar=" + tipoDetalleADesplegar;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/demandaUrgenciaAdultoPediatrica/detalleDemandaUrgenciaAdultoPediatrica.php`, parametros, "#detalleDemandaUrgenciaAdultoPediatrica", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleDiagnosticosInespecificos(tipoDiagnostico) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoDiagnostico=" + tipoDiagnostico;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/diagnosticosInespecificos/detalleDiagnosticosInespecificos.php`, parametros, "#detalleDiagnosticosInespecificos", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleResumenTiemposEspera(tipoCategorizacion) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoCategorizacion=" + tipoCategorizacion;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/resumenTiemposEspera/detalleResumenTiemposEspera.php`, parametros, "#detalleResumenTiemposEspera", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleResumenTiemposEsperaDeciles(tipoAtencion, tipoCategorizacion) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoAtencion=" + tipoAtencion + "&tipoCategorizacion=" + tipoCategorizacion;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/resumenTiemposEsperaDeciles/detalleResumenTiemposEsperaDeciles.php`, parametros, "#detalleResumenTiemposEsperaDeciles", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleTiemposImagenologia(tipoExamen) {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val() + "&tipoExamen=" + tipoExamen;
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/tiemposImagenologia/detalleResumenTiemposImagenologia.php`, parametros, "#detalleResumenTiemposImagenologia", "modal-lg", "", "fas fa-plus");
        }

        function _verDetalleTiemposLaboratorio() {
            const parametros = "fechaAnterior=" + $fechaResumenInicio.val() + "&fechaActual=" + $fechaResumenTermino.val();
            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCRUrgencia/tiemposLaboratorio/detalleResumenTiemposLaboratorio.php`, parametros, "#detalleResumenTiemposLaboratorio", "modal-lg", "", "fas fa-plus");
        }

        function _verExcelResumenTiemposCRUrgencia ( ) {

            if ( ! _verificarDatos() ){

                return;

            }

            _obtenerTablas();
            let url = `${raiz}/views/modules/reportes/tiemposCRUrgencia/excelTiemposCRUrgencia.php`;

            let parametros = { 'fechaInicio' : $fechaResumenInicio.val() , 'fechaTermino' : $fechaResumenTermino.val() };

            parametros.tablaDemandaUrgenciaAdultoPediatrica        = JSON.stringify(tablaDemandaUrgenciaAdultoPediatrica);

            parametros.tablaDemandaUrgenciaAdultoPediatrica        = JSON.stringify(tablaDemandaUrgenciaAdultoPediatrica);

            parametros.tablaResumenTiempoEsperaAdultosPediatricos  = JSON.stringify(tablaResumenTiempoEsperaAdultosPediatricos);

            parametros.tablaResumenTiempoEsperaAdultoDeciles       = JSON.stringify(tablaResumenTiempoEsperaAdultoDeciles);

            parametros.tablaResumenTiempoEsperaPediatricoDeciles   = JSON.stringify(tablaResumenTiempoEsperaPediatricoDeciles);

            parametros.tablaResumenCumplimientoCategorizacionESI   = JSON.stringify(tablaResumenCumplimientoCategorizacionESI);

            parametros.tablaResumenDiagnosticosInespecificos       = JSON.stringify(tablaResumenDiagnosticosInespecificos);

            parametros.tablaResumenTiemposIndicacionesLaboratorio  = JSON.stringify(tablaResumenTiemposIndicacionesLaboratorio);

            parametros.tablaResumenTiemposIndicacionesImagenologia = JSON.stringify(tablaResumenTiemposIndicacionesImagenologia);

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


            // _openWindowWithPost(url, "toolbar=0,location=0, directories=0,status=0,menubar=0,scrollbars=1,resizable=1,left=0,top=0,height=600,width=850", "NewFile", parametros);

        }

        function _verPDFResumenTiemposCRUrgencia ( ) {
            if ( ! _verificarDatos() ){
                return;
            }
            const parametros = {'fechaAnterior' : $fechaResumenInicio.val() , 'fechaActual' : $fechaResumenTermino.val() }
            modalFormulario_noCabecera("Resumen Tiempos CR Urgencia PDF", raiz+"/views/modules/reportes/tiemposCRUrgencia/pdfTiemposCRUrgencia.php", parametros, "#pdfTiemposCRUrgencia", "modal-lg", "", "fas fa-plus");
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

        function despliegueTiemposCRUrgencia ( ) {

            $btnBuscarResumenTiemposCRUrgencia .on('click', _despligueTiemposCRUrgencia);

        }

        function resetearParametrosDespliegueTiemposCRUrgencia ( ) {

            $btnResetarParametrosTiemposCRUrgencia.on('click', _resetearParametrosDespliegueTiemposCRUrgencia);

        }

        function validarCampos ( ) {

            validar("#frm_fechaResumenInicio", "fecha");

            validar("#frm_fechaResumenTermino", "fecha");

        }

        function verDetalleCumplimientoCategorizacionESI ( ) {

            $claseCumplimientoCategorizacionESI.on('click', function(){

                let tipoCategorizacion = $(this).attr('id');

                _verDetalleCumplimientoCategorizacionESI(tipoCategorizacion);

            });

        }

        function verDetalleDemandaUrgenciaAdultoPediatrico ( ) {

            $claseDemandaUrgenciaAdultoPediatrico.on('click', function(){

                let tipoDemanda = $(this).attr('id');

                _verDetalleDemandaUrgenciaAdultoPediatrico(tipoDemanda);

            });

        }

        function verDetalleDiagnosticosInespecificos ( ) {

            $claseDiagnosticosInespecificos.on('click', function(){

                let tipoDiagnostico = $(this).attr('id');

                _verDetalleDiagnosticosInespecificos(tipoDiagnostico);

            });

        }

        function verDetalleResumenTiemposEspera ( ) {

            $claseResumenTiemposEspera.on('click', function(){

                let tipoCategorizacion = $(this).attr('id');

                _verDetalleResumenTiemposEspera(tipoCategorizacion);

            });

        }

        function verDetalleResumenTiemposEsperaDeciles ( ) {

            $claseResumenTiemposEsperaDeciles.on('click', function(){

                let parametros = $(this).attr('id');

                let arregloParametros = parametros.split('/');

                let tipoAtencion = arregloParametros[0];

                let tipoCategorizacion = arregloParametros[1];

                _verDetalleResumenTiemposEsperaDeciles(tipoAtencion, tipoCategorizacion);

            });

        }

        function verDetalleTiemposImagenologia ( ) {

            $claseTiemposImagenologia.on('click', function(){

                let tipoExamen = $(this).attr('id');

                _verDetalleTiemposImagenologia(tipoExamen);

            });

        }

        function verDetalleTiemposLaboratorio ( ) {

            $claseTiemposLaboratorio.on('click', _verDetalleTiemposLaboratorio);

        }

        function verExcelResumenTiemposCRUrgencia ( ) {

            $btnVerExcelResumenTiemposCRUrgencia.on("click", _verExcelResumenTiemposCRUrgencia);

        }

        function verPDFResumenTiemposCRUrgencia ( ) {

            $btnVerPDFResumenTiemposCRUrgencia.on('click', _verPDFResumenTiemposCRUrgencia);

        }



        return {
            validarCampos                                   : validarCampos,
            despliegueDatePicker                            : despliegueDatePicker,
            despliegueTiemposCRUrgencia                     : despliegueTiemposCRUrgencia,
            resetearParametrosDespliegueTiemposCRUrgencia   : resetearParametrosDespliegueTiemposCRUrgencia,
            verExcelResumenTiemposCRUrgencia                : verExcelResumenTiemposCRUrgencia,
            verDetalleDemandaUrgenciaAdultoPediatrico       : verDetalleDemandaUrgenciaAdultoPediatrico,
            verDetalleResumenTiemposEspera                  : verDetalleResumenTiemposEspera,
            verDetalleResumenTiemposEsperaDeciles           : verDetalleResumenTiemposEsperaDeciles,
            verDetalleCumplimientoCategorizacionESI         : verDetalleCumplimientoCategorizacionESI,
            verDetalleDiagnosticosInespecificos             : verDetalleDiagnosticosInespecificos,
            verDetalleTiemposLaboratorio                    : verDetalleTiemposLaboratorio,
            verDetalleTiemposImagenologia                   : verDetalleTiemposImagenologia,
            verPDFResumenTiemposCRUrgencia                  : verPDFResumenTiemposCRUrgencia,
        }

    })();

    tiemposCRUrgencia.validarCampos();
    tiemposCRUrgencia.despliegueDatePicker();
    tiemposCRUrgencia.despliegueTiemposCRUrgencia();
    tiemposCRUrgencia.resetearParametrosDespliegueTiemposCRUrgencia();
    tiemposCRUrgencia.verPDFResumenTiemposCRUrgencia();
    tiemposCRUrgencia.verExcelResumenTiemposCRUrgencia();
    tiemposCRUrgencia.verDetalleDemandaUrgenciaAdultoPediatrico();
    tiemposCRUrgencia.verDetalleResumenTiemposEspera();
    tiemposCRUrgencia.verDetalleResumenTiemposEsperaDeciles();
    tiemposCRUrgencia.verDetalleCumplimientoCategorizacionESI();
    tiemposCRUrgencia.verDetalleDiagnosticosInespecificos();
    tiemposCRUrgencia.verDetalleTiemposLaboratorio();
    tiemposCRUrgencia.verDetalleTiemposImagenologia();
    enlaceBoton();

});