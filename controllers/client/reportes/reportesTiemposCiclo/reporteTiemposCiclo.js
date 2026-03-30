$(document).ready(function(){

    const tiemposCiclo = ( function VerTurnosRCUrgencia ( ) {

        //Declaración de variables general (tiempos ciclos CR Urgencia)
        const   $fechaResumenInicio                = $('#frm_fechaResumenInicio'),
                $fechaResumenTermino               = $('#frm_fechaResumenTermino'),
                $btnBuscarResumenTiemposCiclo      = $('#btnBuscarResumenesTiemposCiclos'),
                $btnResetarParametrosTiemposCiclo  = $('#btnEliminar'),
                $btnVerPDFResumenTiemposCiclo      = $('#btnVerPDF'),
                $btnVerExcelResumenTiemposCiclo    = $("#btnVerExcel");

        let     tablaResumenTiemposCicloCRUrgencia = [];



        //Funciones privadas
        function _despligueTiemposCiclo ( ) {

            if ( ! verificarDatos() ){

                return;

            }

            ajaxContent(`${raiz}/views/modules/reportes/tiemposCiclo/tiemposCiclo.php`, $("#frm_despliegueParametrosBusqueda").serialize(),'#contenido');

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

        function verificarDatos ( ) {

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



        function _resetearParametrosDespliegueTiemposCiclo ( ) {

            unsetSesion();

            ajaxContent(`${raiz}/views/modules/reportes/tiemposCiclo/tiemposCiclo.php`, '','#contenido');

        }

        function _verExcelResumenTiemposCRUrgencia ( ) {

            if ( ! verificarDatos() ){

                return;

            }

            _obtenerTablas(tablaResumenTiemposCicloCRUrgencia);

            const parametros = { 'fechaInicio' : $fechaResumenInicio.val() , 'fechaTermino' : $fechaResumenTermino.val() , 'tablaResumenTiemposCicloCRUrgencia' : JSON.stringify(tablaResumenTiemposCicloCRUrgencia) };

            const url = `${raiz}/views/modules/reportes/tiemposCiclo/excelTiemposCiclo.php`;
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

            if ( ! verificarDatos() ){

                return;

            }

            let tablasAEnviar = [];

            _obtenerTablas(tablasAEnviar);

            const parametros  = {'fechaAnterior' : $fechaResumenInicio.val() , 'fechaActual' : $fechaResumenTermino.val() , 'tablasAEnviar' : tablasAEnviar }

            modalFormulario_noCabecera('', `${raiz}/views/modules/reportes/tiemposCiclo/pdfTiemposCiclo.php`, parametros, "#pdfTiemposCiclo", "modal-lg", "", "fas fa-plus");
       


        }



        function _obtenerTablas ( tablasAEnviar ) {

            const adulto        = 1,
                  pediatrico    = 2,
                  hospitalizado = 4,
                  alta          = 3;

            tablasAEnviar.push(_obtenerTablaTiemposCicloAdultoPediatrico(adulto));

            tablasAEnviar.push(_obtenerTablaTiemposCicloAdultoPediatrico(pediatrico));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(adulto, hospitalizado, 'cierre'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(adulto, hospitalizado, 'indicacionEgreso'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(pediatrico, hospitalizado, 'cierre'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(pediatrico, hospitalizado, 'indicacionEgreso'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(adulto, alta, 'cierre'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(adulto, alta, 'indicacionEgreso'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(pediatrico, alta, 'cierre'));

            tablasAEnviar.push(_obtenerTablaTiemposCicloHospitalizacionUrgencia(pediatrico, alta, 'indicacionEgreso'));

        }



        function _obtenerTablaTiemposCicloAdultoPediatrico ( tipoAtencion ) {

            let TableData = [];

            $(`#tablaResumenTiemposCicloAdultoPediatrico-${tipoAtencion} tr`).each(function(row, tr){

                TableData[row]={
                      "categorizacion"              : $(tr).find('td:eq(0)').text()
                    , "atenciones"                  : $(tr).find('td:eq(1)').text()
                    , "tiempoPromedio"              : $(tr).find('td:eq(2)').text()
                    , "tiempoMinimo"                : $(tr).find('td:eq(3)').text()
                    , "tiempoMaximo"                : $(tr).find('td:eq(4)').text()
                    , "hospitalizados"              : $(tr).find('td:eq(5)').text()
                    , "hospitalizadosTiempoPromedio": $(tr).find('td:eq(6)').text()
                    , "hospitalizadosTiempoMinimo"  : $(tr).find('td:eq(7)').text()
                    , "hospitalizadosTiempoMaximo"  : $(tr).find('td:eq(8)').text()
                    , "alta"                        : $(tr).find('td:eq(9)').text()
                    , "altaTiempoPromedio"          : $(tr).find('td:eq(10)').text()
                    , "altaTiempoMinimo"            : $(tr).find('td:eq(11)').text()
                    , "altaTiempoMaximo"            : $(tr).find('td:eq(12)').text()

                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            TableData.shift();

            return TableData;

        }



        function _obtenerTablaTiemposCicloHospitalizacionUrgencia ( tipoAtencion, tipoEgreso, tipoResumen ) {

            let TableData = [];

            $(`#tablaResumenTiemposCicloAdultoHospitalizados-${tipoAtencion}-${tipoEgreso}-${tipoResumen} tr`).each(function(row, tr){

                TableData[row]={
                      "categorizacion" : $(tr).find('td:eq(0)').text()
                    , "total"          : $(tr).find('td:eq(1)').text()
                    , "d1"             : $(tr).find('td:eq(2)').text()
                    , "d2"             : $(tr).find('td:eq(3)').text()
                    , "d3"             : $(tr).find('td:eq(4)').text()
                    , "d4"             : $(tr).find('td:eq(5)').text()
                    , "d5"             : $(tr).find('td:eq(6)').text()
                    , "d6"             : $(tr).find('td:eq(7)').text()
                    , "d7"             : $(tr).find('td:eq(8)').text()
                    , "d8"             : $(tr).find('td:eq(9)').text()
                    , "d9"             : $(tr).find('td:eq(10)').text()
                    , "d10"            : $(tr).find('td:eq(11)').text()


                }

            });

            TableData.shift();

            TableData.shift();

            TableData.shift();

            return TableData;

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
                    language: 'es',
                    autoclose: true
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
                    language: 'es',
                    autoclose: true
                }).datepicker('setEndDate', e.date);
            });

        }

        function despligueTiemposCiclo ( ) {

            $btnBuscarResumenTiemposCiclo.on('click', _despligueTiemposCiclo);

        }

        function resetearParametrosDespliegueTiemposCiclo ( ) {

            $btnResetarParametrosTiemposCiclo.on('click', _resetearParametrosDespliegueTiemposCiclo);

        }

        function validarCampos ( ) {

            validar("#frm_fechaResumenInicio", "fecha");

            validar("#frm_fechaResumenTermino", "fecha");

        }

        function verExcelResumenTiemposCRUrgencia ( ) {

            $btnVerExcelResumenTiemposCiclo.on("click", _verExcelResumenTiemposCRUrgencia);

        }

        function verPDFResumenTiemposCRUrgencia ( ) {

            $btnVerPDFResumenTiemposCiclo.on('click', _verPDFResumenTiemposCRUrgencia);

        }



        return {
            despliegueDatePicker                    : despliegueDatePicker,
            despligueTiemposCiclo                   : despligueTiemposCiclo,
            resetearParametrosDespliegueTiemposCiclo: resetearParametrosDespliegueTiemposCiclo,
            validarCampos                           : validarCampos,
            verExcelResumenTiemposCRUrgencia       : verExcelResumenTiemposCRUrgencia,
            verPDFResumenTiemposCRUrgencia          : verPDFResumenTiemposCRUrgencia
        }

    })();


    tiemposCiclo.validarCampos();
    tiemposCiclo.despliegueDatePicker();
    tiemposCiclo.despligueTiemposCiclo();
    tiemposCiclo.resetearParametrosDespliegueTiemposCiclo();
    tiemposCiclo.verPDFResumenTiemposCRUrgencia();
    tiemposCiclo.verExcelResumenTiemposCRUrgencia();

});