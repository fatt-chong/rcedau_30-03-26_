"use strict";

$(document).ready(function(){

  const reportesDiariosDAURCE = (function reportesDiariosDAURCE() {

    //Declaración variables
    const _formulario = "#frm_busquedaReportesDiariosDAURCE";
    const $_formulario = document.querySelector(`${_formulario}`);
    const $_anios = document.querySelector(`${_formulario} #frm_anios`);
    const $_meses = document.querySelector(`${_formulario} #frm_meses`);
    const $_btnBuscar = document.querySelector(`${_formulario} #btnBuscarResportesDiariosDAURCE`);
    const $_btnLimpiar = document.querySelector(`${_formulario} #btnLimpiar`);
    const $_divError = document.querySelector("#divErrorBuscarReportesDiariosDAURCE");
    const $_divDespligue = document.querySelector("#despliegueListadoReportesDiariosDAURCE");
    const _tablaListado = "listadoReportesDiariosDAURCE";
    const $_cuerpoTabla = document.querySelector(`#${_tablaListado} > tbody`);

    const _anioEmpezar = 2019;
    const _anioActual = new Date().getFullYear();
    const _mesActual = new Date().getMonth();
    const _meses = [
      {id: 0, cardinalidad: "01", nombre:"Enero"},
      {id: 1, cardinalidad: "02", nombre:"Febrero"},
      {id: 2, cardinalidad: "03", nombre:"Marzo"},
      {id: 3, cardinalidad: "04", nombre:"Abril"},
      {id: 4, cardinalidad: "05", nombre:"Mayo"},
      {id: 5, cardinalidad: "06", nombre:"Junio"},
      {id: 6, cardinalidad: "07", nombre:"Julio"},
      {id: 7, cardinalidad: "08", nombre:"Agosto"},
      {id: 8, cardinalidad: "09", nombre:"Septiembre"},
      {id: 9, cardinalidad: "10", nombre:"Octubre"},
      {id: 10, cardinalidad: "11", nombre:"Noviembre"},
      {id: 11, cardinalidad: "12", nombre:"Diciembre"},
    ];



    //Funciones públicas
    function iniciar() {
      _rellenarSelecAnio();
      _rellenarSelectMeses();
      _valoresSelectsPorDefecto();

      _agregarEventos();

      _desplegarReportes();
    }



    //Funciones privadas
    function _rellenarSelecAnio() {
      for (let anioBusqueda = _anioActual; anioBusqueda >= _anioEmpezar; anioBusqueda--) {
        const opcion = new Option(
          anioBusqueda,
          anioBusqueda,
          false,
          false
        );

        $_anios.add(opcion);
      }
    }



    function _rellenarSelectMeses() {
      _meses.forEach(mes => {
        const opcion = new Option(
          mes.nombre,
          mes.cardinalidad,
          false,
          false
        );

        $_meses.add(opcion);
      });
    }



    function _valoresSelectsPorDefecto() {
      $_anios.value = _anioActual;

      $_meses.value = _meses.reduce((cardinalidad, mes) => {
        return  Number(mes.id) === _mesActual
                ? mes.cardinalidad
                : cardinalidad;
      }, 0);
    }



    function _agregarEventos() {
      $_anios.addEventListener("change", _desplegarReportes);
      $_meses.addEventListener("change", _desplegarReportes);
      $_btnBuscar.addEventListener("click", _desplegarReportes);
      $_btnLimpiar.addEventListener("click", _resetear);
    }



    async function _desplegarReportes() {
      _destruirListado();

      const reportes = await _obtenerReportes();
      if (! _existeValor(reportes)) {
        _desplegarDivError("No se han encontrado reportes");
        return;
      }

      _listadoReportes(reportes.toReversed());

      _agregarEventosBotonesTabla(".verArchivos", _verArchivos);
    }



    function _destruirListado() {
      $_divError.innerHTML = "";
      $_divDespligue.style.display = "none";
      $(`#${_tablaListado}`).dataTable().fnDestroy();
      $(`#${_tablaListado} > tbody tr`).each(() => $(this).remove());
    }



    async function _obtenerReportes() {
      const directorio = `${raiz}/controllers/server/reportes/main_controller.php`;

      const respuesta = await fetch(
      directorio,
        {
          method: "POST",
          headers: {'Content-Type':'application/x-www-form-urlencoded'},
          body: `anio=${$_anios.value}&mes=${$_meses.value}&accion=obtenerReportesDiariosDAURCE`
        }
      );

      return await respuesta.json();
    }



    function _desplegarDivError(mensaje) {
      $_divError.style.display = "block";

      $_divError.innerHTML = `
        <div
          class="alert alert-danger alert-dismissible show"
          role="alert"
          style="text-align:center;"
        >
          <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
          <i class="fa fa-exclamation-circle fa-lg text-danger"></i>
          &nbsp;&nbsp;&nbsp;${mensaje}
        </div>
      `;
    }



    function _listadoReportes(reportes) {
      $_divDespligue.style.display = "block";

      $_cuerpoTabla.innerHTML = _filasTablaListadoReportes(reportes);

      tablaSimple(`#${_tablaListado}`);
      _iniciarToolTipsTabla(_tablaListado);
    }



    function _filasTablaListadoReportes(reportes) {
      const nombreMes = $_meses
                        .options[$_meses.selectedIndex]
                        .text;
      let html = "";

      reportes.forEach(reporte => {
        const fechaReporte =  reporte
                              .split("_").at(1)
                              .split(".").at(0);

        html += `
          <tr>
            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
              ${$_anios.value}
            </td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
              ${nombreMes}
            </td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
              ${fechaReporte}
            </td>

            <td>
              ${reporte}
            </td>

            <td style="vertical-align:middle;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
              ${_botoVerArchivo(reporte)}
            </td>
          </tr>
        `;
      });

      return html;
    }



    function _filaTablaListadoReportes(reporte) {


    }



    function _botoVerArchivo(reporte) {
      return `
        <button
          type="button"
          id="${reporte}"
          class="btn btn-sm btn-danger item-menu verArchivos"
          data-toggle="tooltip"
          data-placement="top"
          title="Ver Reporte"
        >
        <i class="fas fa-file-pdf"></i>
        </button>
      `;
    }



    function _iniciarToolTipsTabla(tabla) {
      const $tabla = document.querySelector(`#${tabla} tbody`);

      $tabla.addEventListener("mouseover",(elemento) => {
        if (! elemento.target.matches("td")) {
          return;
        }

        $('[data-toggle="tooltip"]').tooltip({ trigger: 'hover', html: true });
      });
    }



    function _agregarEventosBotonesTabla(clase, funcion) {
      $_cuerpoTabla.addEventListener("click", $tds => {
        $tds.stopPropagation();

        const $td = $tds.target
                    .closest(clase);
        if (! _existeValor($td)) {
          return
        };

        const reporte = $td.getAttribute("id")

        funcion(reporte);
      });
    }



    function _verArchivos(reporte) {
      let url = "http://10.6.21.29/";
      url += "reportesHJNC/";
            url += "dauRCE/";
      url += "reportesDiarioDAURCE/";
      url += `${$_anios.value}/`;
      url += `${$_meses.value}/`;
      url += `${reporte}`;

      _abrirArchivo(url);
    }



    function _abrirArchivo(url) {
      const archivo = `${url}?v=${(Math.random()*100)}`;
      const nameWindows = `id-${archivo}`;

      const ancho = "800px";
      const alto = "800px";

      window.open(archivo, nameWindows, `width=${ancho}, height=${alto}, menubar=no, status=no, titlebar=yes`);
    }



    function _resetear() {
      $_formulario.reset();
      _valoresSelectsPorDefecto();
      _desplegarReportes();
    }



    function _existeValor(valor) {
      return(
        valor === undefined
        || valor === null
        || $.isEmptyObject(valor)
        || valor.length === 0
        || valor === 0
        || valor === ''
        || valor === '0'
        || valor === '0000-00-00'
        || valor === '00-00-0000'
        || String(valor) === "null"
      )
        ? false
        : true;
    }




    //Retorno objeto
    return{
      iniciar
    };
  })();

  reportesDiariosDAURCE.iniciar();
});