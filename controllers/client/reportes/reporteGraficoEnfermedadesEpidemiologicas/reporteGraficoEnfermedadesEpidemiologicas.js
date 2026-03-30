"use strict";
$(document).ready(function(){
    const enfermedadesEpidemiologicas = ( function enfermedadesEpidemiologicas ( ) {
        //Declaración de variables formulario
        const $slcAnioResumen                   = $("#slc_anioEnfermedadesEpidemiologicas"),
              $claseResultadoBusqueda           = $(".resultadoBusqueda"),
              $btnBuscarResultados              = $("#btnBuscarResumenesEnfermedadesEpidemiologicas"),
              $btnEliminarParametrosBusqueda    = $("#btnEliminar"),
              $btnVerPDF                        = $("#btnVerPDF"),
              $divDespliegueResultado           = $("#divDespliegueResultados");
        //Declaración de variables JS
        const anioActual                        = (new Date).getFullYear(),
              anioInicio                        = 2017;
        let dauCerradosSemanas                  = {},
            dauEnfermedadesEpidemiologicas      = {};
        //Funciones privadas
        function _booleanSelectedSegunAnioActual ( anio ) {
            return ( anioActual === anio ) ? true : false;
        }
        function _buscarResumenEnfermedadesEpidemiologicas ( ) {
            if ( ! _datosIngresadosCorrectamente() ) {
                return;
            }
            $divDespliegueResultado.html("");
            if ( ! _obtenerDauCerrados() || ! _obtenerDauEnfermedadesEpidemiologicas() ) {
                const html = '<h3 style="text-align:center"> No se encontraron resultados</h3>';
                $divDespliegueResultado.append(html).hide().show(200);
                $claseResultadoBusqueda.hide(100);
                return;
            }
            const parametrosAEnviar = { 'anioResumen' : $slcAnioResumen.val() , 'arrayDauCerradosSemanas' : JSON.stringify(dauCerradosSemanas) , 'arrayDauEnfermedadesEpidemiologicas' :  JSON.stringify(dauEnfermedadesEpidemiologicas) };
            ajaxContent(`${raiz}/views/modules/reportes/graficoEnfermedadesEpidemiologicas/resultadoGraficoEnfermedadesEpidemiologicas.php`, parametrosAEnviar, '#divDespliegueResultados', '', true);
            $claseResultadoBusqueda.show(100);
        }
        function _datosIngresadosCorrectamente ( ) {
            if ( ! _valorExiste($slcAnioResumen.val()) ) {
                $("#slc_anioEnfermedadesEpidemiologicas").assert(false,"Seleccione Año de Reporte");
                return false;
            }
            return true;
        }
        function _eliminarParametrosBusqueda ( ) {
            $slcAnioResumen.val(0);
            $divDespliegueResultado.html("");
            $claseResultadoBusqueda.hide(100);
        }
        function _obtenerDauCerrados ( ) {
            const parametrosAEnviar = { 'anioResumen' : $slcAnioResumen.val() , 'accion' : 'obtenerDauCerradosEnSemanas' };
            return _obtenerInformacionSolicitada(parametrosAEnviar, dauCerradosSemanas);
        }
        function _obtenerDauEnfermedadesEpidemiologicas ( ) {
            const parametrosAEnviar = { 'anioResumen' : $slcAnioResumen.val() , 'accion' : 'obtenerDauEnfermedadesEpidemiologicas' };
            return _obtenerInformacionSolicitada(parametrosAEnviar, dauEnfermedadesEpidemiologicas);
        }
        function _obtenerInformacionSolicitada ( parametrosAEnviar, objetoDau ) {
            const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/reportes/main_controller.php`, parametrosAEnviar, 'POST', 'JSON', 1,'');
            if ( respuestaAjaxRequest.status === 'error' ) {
                return false;
            }
            Object.assign(objetoDau, respuestaAjaxRequest.arrayDau);
            return true;
        }
        function _ocultarBotonesResultadoBusqueda ( ) {
            $claseResultadoBusqueda.hide(100);
        }
        function _rellenarSelectAnio ( ) {
            for ( let i = anioActual; i >= anioInicio; i-- ) {
                const selected = _booleanSelectedSegunAnioActual(i);
                $slcAnioResumen.append($('<option>', {
                    value: i,
                    text: i,
                    selected: selected
                }));
            }
        }
        function _valorExiste ( valor ) {
            return ( valor !== '' && valor !== 0 && valor !== null && valor !== undefined ) ? true : false;
        }
        function _verPedfResumenEnfermedadesEpidemiologicas ( ) {
            const parametrosAEnviar = { 'anioResumen' : $slcAnioResumen.val() , 'arrayDauCerradosSemanas' : JSON.stringify(dauCerradosSemanas) , 'arrayDauEnfermedadesEpidemiologicas' :  JSON.stringify(dauEnfermedadesEpidemiologicas) };
            modalFormulario_noCabecera('',  `${raiz}/views/modules/reportes/graficoEnfermedadesEpidemiologicas/pdfGraficoEnfermedadesEpidemiologicas.php`, parametrosAEnviar, "#pdfEnfermedadesEpidemiologicas", "modal-lg", "", "fas fa-plus");
        }
        //Funciones públicas
        function buscarResumenEnfermedadesEpidemiologicas ( ) {
            $btnBuscarResultados.on("click", _buscarResumenEnfermedadesEpidemiologicas);
        }
        function eliminarParametrosBusqueda ( ) {
            $btnEliminarParametrosBusqueda.on("click", _eliminarParametrosBusqueda);
        }
        function iniciarReportesEnfermedadesEpidemiologicas ( ) {
            _rellenarSelectAnio();
            _ocultarBotonesResultadoBusqueda();
        }
        function verPDFResumenEnfermedadesEpidemiologicas ( ) {
            $btnVerPDF.on("click", _verPedfResumenEnfermedadesEpidemiologicas);
        }
        return {
            iniciarReportesEnfermedadesEpidemiologicas  : iniciarReportesEnfermedadesEpidemiologicas,
            buscarResumenEnfermedadesEpidemiologicas    : buscarResumenEnfermedadesEpidemiologicas,
            eliminarParametrosBusqueda                  : eliminarParametrosBusqueda,
            verPDFResumenEnfermedadesEpidemiologicas    : verPDFResumenEnfermedadesEpidemiologicas
        }
    })();
    enfermedadesEpidemiologicas.iniciarReportesEnfermedadesEpidemiologicas();
    enfermedadesEpidemiologicas.buscarResumenEnfermedadesEpidemiologicas();
    enfermedadesEpidemiologicas.eliminarParametrosBusqueda();
    enfermedadesEpidemiologicas.verPDFResumenEnfermedadesEpidemiologicas();
});