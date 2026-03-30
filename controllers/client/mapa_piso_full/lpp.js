"use strict";

$(document).ready(() => {
  const lpp = (() => {
    //Variables
    const divPOST = "#divPOSTLPP";
    const POST = {
      idDau: $(`${divPOST} #idDau`).val()
    };

    const formulario = "#frm_lpp";
    const $valoracionesPiel = $(`${formulario} #frm_valoracionPiel`);
    const $zonaAfectada = $(`${formulario} #frm_zonaAfectada`);
    const $puntajeEvaluacion = $(`${formulario} #frm_puntajeEvalucion`);
    const $riesgos = $(`${formulario} #frm_riesgo`);
    const $aplicacionesSEMP = $(`${formulario} #frm_aplicacionSEMP`);
    const $cambiosPosiciones = $(`${formulario} #frm_cambioPosicion`);
    const $divRegistroEjecucion = $(`${formulario} #divRegistroEjecucion`);
    const $registroEjecucion = $(`${formulario} #frm_registroEjecucion`);
    const $divLOGRegistros = $(`${formulario} #divLOGRegistrosEjecucion`);
    const $tablaLOGRegistros = $(`${formulario} #tablaLOGRegistrosEjecucion`);

    const modal = "#LPP";
    const $ingresar = $(`${modal} #btnIngresarLPP`);

    const SELECTS = [
      {
        $select: $valoracionesPiel,
        datos: () => valoracionesPiel
      },
      {
        $select: $riesgos,
        datos: () => riesgos
      },
      {
        $select: $aplicacionesSEMP,
        datos: () => aplicacionesSEMP
      },
      {
        $select: $cambiosPosiciones,
        datos: () => cambiosPosiciones
      }
    ];
    const DAU_CERRADO = 5;
    const PUNTAJE_MINIMO = 0;
    const PUNTAJE_MAXIMO = 28;
    const LPP_CONTROLLER_URL = raiz
      + "/controllers"
      + "/server"
      + "/lpp"
      + "/main_controller.php";

    let valoracionesPiel = [];
    let idsValoracionPiel = [];
    let descripcionesValoracionPiel = [];
    let riesgos = [];
    let aplicacionesSEMP = [];
    let cambiosPosiciones = [];
    let LPP = [];



    //Funciones públicas
    async function iniciar() {
      if (!_existe(POST.idDau)) {
        _desplegarErrorIdDau();

        return;
      }

      const datosSelectsYLPP = await _obtenerDatosSelectsYLPP();
      const {
        LPP: datosLPP,
        ...datosSelects
      } = datosSelectsYLPP;

      if (!_existe(datosSelects)) {
        _desplegarErrorDatosSelects();

        return;
      }

      _asignarDatosSelects(datosSelects);
      _asignarDatosLPP(datosLPP[0]);

      _validarTiposCampos();
      _rellenarSelects();
      _rellenarFormulario();
      _rellenarTabla();
      _aplicarEstiloPickerMultiple();

      _setearCamposDisabled();
    }


    function cambiosEnSelects() {
      $valoracionesPiel.on("change", function() {
        idsValoracionPiel = [];
        descripcionesValoracionPiel = [];

        $valoracionesPiel.find(":selected").each(function() {
          idsValoracionPiel.push($(this).val());
          descripcionesValoracionPiel.push($(this).text().trim());
        });
      });
    }



    function ingresar() {
      if (
        !_existe(POST.idDau)
        || !$ingresar.length
      ) {
        return;
      }

      $ingresar.on("click", () => {
        if (!_esFormularioValido()) return;
        modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procederá a ingresar registro LPP, <b>¿Desea continuar?</b>", "primary", async () => await _ingresar());

      });
    }



    //Funciones privadas
    function _asignarDatosSelects(datos) {
      valoracionesPiel = [...datos.valoracionesPiel];
      riesgos = [...datos.riesgos];
      aplicacionesSEMP = [...datos.aplicacionesSEMP];
      cambiosPosiciones = [...datos.cambiosPosiciones];

      
    }



    function _asignarDatosLPP(datos) {
      LPP = datos;
    }



    function _validarTiposCampos() {
      validar(
        `#${$zonaAfectada.attr("id")}`,
        "letras_numeros"
      );

      validar(
        `#${$puntajeEvaluacion.attr("id")}`,
        "numero"
      );

      validar(
        `#${$registroEjecucion.attr("id")}`,
        "letras_numeros"
      );
    }



    function _rellenarSelects() {
      SELECTS.forEach(s => {
        _rellenarSelect({
          $select: s.$select,
          datos: s.datos()
        })
      });
    }



    function _rellenarSelect({$select, datos}) {
      if (!_existe(datos)) return;

      $select.empty();
      ($select === $valoracionesPiel)
        ? $select.append(_opcionSeleccionePicker())
        : $select.append(_opcionSeleccione());
      $select.append(_opciones(datos));
    }



    function _opcionSeleccionePicker() {
      return `
        <option disabled>
          Seleccione
        </option>
      `;
    }



    function _opcionSeleccione() {
      return `
        <option selected disabled>
          Seleccione
        </option>
      `;
    }



    function _opciones(datos) {
      return datos
        .map(_opcionHTML)
        .join("");
    }



    function _opcionHTML(dato) {
      const value = Object.values(dato)[0];
      const text = Object.values(dato)[1];

      return `
        <option value="${value}">
          ${text}
        </option>
      `;
    }



    function _rellenarFormulario() {
      if (!_existe(LPP)) return;

      const {
        idsValoracionPiel,
        zonaAfectada,
        puntajeEvaluacion,
        idRiesgo,
        idAplicacionSEMP,
        idCambioPosicion
      } = LPP;

      $valoracionesPiel.val(idsValoracionPiel.split(","));
      $zonaAfectada.val(zonaAfectada);
      $puntajeEvaluacion.val(puntajeEvaluacion);
      $riesgos.val(idRiesgo);
      $aplicacionesSEMP.val(idAplicacionSEMP);
      $cambiosPosiciones.val(idCambioPosicion);
    }



    function _rellenarTabla() {
      if (!_existe(LPP)) return;

      let {
        registrosEjecucion,
        usuarios,
        fechas
      } = LPP;

      [registrosEjecucion, usuarios, fechas] = [
        registrosEjecucion.split(","),
        usuarios.split(","),
        fechas.split(",")
      ];

      const filas = _filasHTML({registrosEjecucion, usuarios, fechas});

      _vaciarTbody();
      _anexarFilas(filas);
      _mostrarDivLOG();
    }



    function _filasHTML({registrosEjecucion, usuarios, fechas}) {
      return registrosEjecucion.map((registro, indice) => {
        const usuario = usuarios[indice];
        const fecha = fechas[indice];

        return _filaHTML({registro, usuario, fecha});
      })
      .join("");
    }



    function _filaHTML({registro, usuario, fecha}) {
      const fechaFormateada = _formatearFechaYHora(fecha);

      return `
        <tr>
          <td style="vertical-align:middle;width:50%;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
            ${registro}
          </td>
          <td style="vertical-align:middle;width:25%;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
            ${fechaFormateada}
          </td>
          <td style="vertical-align:middle;width:25%;" class="mifuente11 my-1 py-1 mx-1 px-1 text-center" >
            ${usuario}
          </td>
        </tr>
      `
    }



    function _aplicarEstiloPickerMultiple() {
      $valoracionesPiel.selectpicker({
        size: 8,
        noneSelectedText : "Seleccione"
      });
    }



    function _setearCamposDisabled() {
      if (
        !_existe(LPP)
        || Number(LPP.estadoDau) !== DAU_CERRADO) {
        return;
      }

      $divRegistroEjecucion.hide();

      $(`
        ${formulario} input,
        ${formulario} select,
        ${formulario} textarea
      `).prop("disabled", true);

      $(`${formulario} .selectpicker`).prop("disabled", true);
      $(`${formulario} .selectpicker`).selectpicker("refresh");
    }



    async function _ingresar() {
      const respuesta = await _ingresarLPP();
      if (!_existe(respuesta)) return;

      const accionesSegunStatus = {
        success: () => _statusIngresarSuccess(),
        error: (respuesta) => _statusIngresarError(respuesta),
        default: (respuesta) => _statusIngresarDefault(respuesta)
      };

      (
        accionesSegunStatus[respuesta.status]
        || accionesSegunStatus["default"]
      )(respuesta);
    }



    //Funciones privadas obtención datos
    async function _obtenerDatosSelectsYLPP() {
      try {
        return await ajaxRequest(
          LPP_CONTROLLER_URL,
          {
            idDau: POST.idDau,
            accion: "obtenerDatosSelectsYLPP"
          },
          "POST",
          "JSON",
          1,
          ""
        );

      } catch(error) {
        console.error("Error en obtenerDatosSelects: ", error);
        return [];
      }
    }



    async function _ingresarLPP() {
      try {
        return await ajaxRequest(
          LPP_CONTROLLER_URL,
          {
            ..._parametrosLPP(),
            accion: "ingresarLPP"
          },
          "POST",
          "JSON",
          1,
          ""
        );

      } catch(error) {
        console.error("Error en ingresar: ", error);
        return false;
      }
    }



    //Funciones privadas status ingresar
    function _statusIngresarSuccess() {
      _esconderModal();
      var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito </h4>  <hr>  <p class="mb-0">Se ha ingresado con éxito los registros de LPP al DAU N°: '+POST.idDau+'.</p></div>';
      modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success"); 
    }



    function _statusIngresarError(respuesta) {
      var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en ingresar acompañante:<br><br> '+respuesta.message+'.</p></div>';
      modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
    }



    function _statusIngresarDefault(respuesta) {
     ErrorSistemaDefecto();
    }



    //Funciones privadas auxiliares
    function _desplegarErrorIdDau() {

      var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Valor de ID DAU no encontrado.</p></div>';
      modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");

      // modalMensajeBtnExit(
      //   "Error",
      //   "Valor de ID DAU no encontrado",
      //   "errorLPP",
      //   "800",
      //   "300",
      //   "danger",
      //   () => _esconderModal()
      // );
      // throw new Error("Valor de ID DAU no encontrado");
    }



    function _desplegarErrorDatosSelects() {

      var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">VDatos para rellenar selects no encontrados.</p></div>';
      modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");

      // modalMensajeBtnExit(
      //   "Error",
      //   "Datos para rellenar selects no encontrados",
      //   "errorLPP",
      //   "800",
      //   "300",
      //   "danger",
      //   () => _esconderModal()
      // );
      // throw new Error("Datos para rellenar selects no encontrados");
    }



    function _existe(valor) {
      if (
        valor === undefined
        || valor === null
      ){
        return false;
      }

      if (Array.isArray(valor)) {
        return valor.length > 0;
      }

      if (typeof valor === "object") {
        return Object
          .values(valor)
          .every(_existe);
      }

      const valoresFalsy = [
        "",
        "0",
        "0.0",
        "0000-00-00",
        "00-00-0000",
        "undefined",
        "null"
      ];
      return (
        !valoresFalsy.includes(String(valor))
        && valor !== 0
      );
    }



    function _vaciarTbody() {
      $(`#${$tablaLOGRegistros.attr("id")} > tbody`).empty();
    }



    function _anexarFilas(filas) {
      $(`#${$tablaLOGRegistros.attr("id")} > tbody`).append(filas);
    }



    function _mostrarDivLOG() {
      $divLOGRegistros.show();
    }



    function _esFormularioValido() {
      let esFormularioValido = true;

      if (!_existe($valoracionesPiel.val())) {
        esFormularioValido = false;
        $(`#${$valoracionesPiel.attr("id")}`).assert(
          false,
          "Debe seleccionar Valoración"
        );
      }

      if (!_existe($zonaAfectada.val())) {
        esFormularioValido = false;
        $(`#${$zonaAfectada.attr("id")}`).assert(
          false,
          "Debe Ingresar Zona"
        );
      }

      if (!_existe($puntajeEvaluacion.val())) {
        esFormularioValido = false;
        $(`#${$puntajeEvaluacion.attr("id")}`).assert(
          false,
          "Debe Ingresar Puntaje"
        );
      }

      if (
        _existe($puntajeEvaluacion.val())
        && !_esPuntajeDentroRango()
      ) {
        esFormularioValido = false;
        $(`#${$puntajeEvaluacion.attr("id")}`).assert(
          false,
          "Puntaje debe estar dentro del rango"
        );
      }

      if (!_existe($riesgos.val())) {
        esFormularioValido = false;
        $(`#${$riesgos.attr("id")}`).assert(
          false,
          "Debe Seleccionar Riesgo"
        );
      }

      if (!_existe($aplicacionesSEMP.val())) {
        esFormularioValido = false;
        $(`#${$aplicacionesSEMP.attr("id")}`).assert(
          false,
          "Debe Seleccionar Apliación"
        );
      }

      if (!_existe($cambiosPosiciones.val())) {
        esFormularioValido = false;
        $(`#${$cambiosPosiciones.attr("id")}`).assert(
          false,
          "Debe Seleccionar Cambio"
        );
      }

      if (!_existe($registroEjecucion.val())) {
        esFormularioValido = false;
        $(`#${$registroEjecucion.attr("id")}`).assert(
          false,
          "Debe Ingresar Registro"
        );
      }

      return esFormularioValido;
    }



    function _esPuntajeDentroRango() {
      return (
        $puntajeEvaluacion.val() >= PUNTAJE_MINIMO
        && $puntajeEvaluacion.val() <= PUNTAJE_MAXIMO
      );
    }



    function _esconderModal() {
      $(`${modal}`)
        .modal("hide")
        .data("bs.modal", null);
    }



    function _formatearFechaYHora(fechaYhora) {
      const [fecha, hora] = fechaYhora.split(" ")
      const [anio, mes, dia] = fecha.split("-");

      return `${dia}-${mes}-${anio} ${hora}`;
    }



    function _parametrosLPP() {
      idsValoracionPiel = [];
      descripcionesValoracionPiel = [];
      $valoracionesPiel.find(":selected").each(function() {
        idsValoracionPiel.push($(this).val());
        descripcionesValoracionPiel.push($(this).text().trim());
      });
      return {
        idDau: POST.idDau,
        idsValoracionPiel: idsValoracionPiel.join(","),
        descripcionesValoracionPiel: descripcionesValoracionPiel.join(","),
        zonaAfectada: $zonaAfectada.val(),
        puntajeEvaluacion: $puntajeEvaluacion.val(),
        idRiesgo: $riesgos.val(),
        idAplicacionSEMP: $aplicacionesSEMP.val(),
        idCambioPosicion: $cambiosPosiciones.val(),
        registroEjecucion: $registroEjecucion.val()
      };
    }



    //Retorno objeto
    return {
      iniciar,
      cambiosEnSelects,
      ingresar
    };
  })();

  lpp.iniciar();
  lpp.cambiosEnSelects();
  lpp.ingresar();
});
