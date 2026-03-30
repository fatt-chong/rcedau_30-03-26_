"use strict";

$(document).ready(() => {
  const ingresarAcompaniante = (() => {
    //Variables
    const divPOST             = "#divPOSTAcompaniante";
    const POST                = {idDau: $(`${divPOST} #idDau`).val()};

    const formulario          = "#frm_acompaniante";
    const $entregaInformacion = $(`${formulario} #frm_entregaInformacion`);
    const $motivo             = $(`${formulario} #frm_motivo`);
    const $acompaniante       = $(`${formulario} #frm_nombreFamiliarOAcompaniante`);
    const $hora               = $(`${formulario} #frm_horaEntregaInformacion`);
    const $medicoTratante     = $(`${formulario} #frm_nombreMedicoTratante`);

    const modal               = "#acompaniante";
    const $ingresar           = $(`${modal} #btnIngresarAcompaniante`);

    const dauCerrado          = 5;
    const dauAnulado          = 6;
    const dauNEA              = 7;
    const estadosDau          = [dauCerrado, dauAnulado, dauNEA];

    let horaServidor          = [];
    let medicoTratante        = [];
    let acompaniante          = [];


    //Funciones públicas
    async function iniciar() {
      if (!_existe(POST.idDau)) {
        console.error("Error en iniciar: no existe idDau enviada por POST");
        return;
      }

      try {
        _validarCampos();
        await _cargarDatosIniciar();
        if (!_existe(acompaniante)) return;

        _setearCamposDisabled(acompaniante.estadoDau);

      } catch(error) {
        console.error("Error en iniciar: ", error);
      }
    }



    function ingresar() {
      if (!_existe(POST.idDau)) {
        console.error("Error en ingresar: no existe idDau enviada por POST");
        return;
      }

      $ingresar.on("click", () => {
        if (!_esFormularioValido()) {
          return;
        }
         modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procederá a ingresar familiar o acompañante de paciente, <b>¿Desea continuar?</b>", "primary", () => _agregar());

        // modalConfirmacion(
        //   "Advertencia",
        //   "ATENCIÓN, se procederá a ingresar familiar o acompañante de paciente, \
        //   <b>¿Desea continuar?</b>",
        //   () => _agregar()
        // );
      });
    }



    //Funciones privadas
    function _existe(valor) {
      return !(
        valor === undefined
        || valor === null
        || (
            valor.constructor === Object
            && Object.keys(valor).length === 0
          )
        || valor.length === 0
        || valor === 0
        || valor === ""
        || valor === "0"
        || valor === "0.0"
        || valor === "0000-00-00"
        || valor === "00-00-0000"
        || String(valor) === "undefined"
        || String(valor) === "null"
      );
    }



    function _validarCampos() {
      validar(
        "#frm_motivo",
        "letras_numeros"
      );

      validar(
        "#frm_nombreFamiliarOAcompaniante",
        "letras"
      );
    }



    async function _cargarDatosIniciar() {
      [
        horaServidor,
        [medicoTratante],
        [acompaniante]
      ] = await Promise.all([
        _obtenerHoraServidor(),
        _obtenerMedicoTratante(),
        _obtenerAcompaniante()
      ]);

      _desplegarHora(horaServidor);
      _desplegarMedicoTratante(medicoTratante);
      _setearIdMedicoTratante(medicoTratante);
      _desplegarAcompaniante(acompaniante);
    }


    async function _obtenerHoraServidor() {
      try {
        return await ajaxRequest(
          raiz
          + "/controllers"
          + "/server"
          + "/rce"
          + "/hospitalAmigo"
          + "/main_controller.php",
          {accion: "obtenerHoraServidor"},
          'POST',
          'JSON',
          1,
          ''
        );
      } catch(error) {
        console.error("Error en obtenerHoraServidor", error);

        return [];
      }
    }



    function _desplegarHora(horaServidor) {
      const hora = (_existe(horaServidor))
        ? horaServidor
        : new Date().toLocaleTimeString("es-CL", {hour12: false});


      $hora.val(hora);
    }



    async function _obtenerMedicoTratante() {
      try {
        return await ajaxRequest(
          raiz
          + "/controllers"
          + "/server"
          + "/rce"
          + "/hospitalAmigo"
          + "/main_controller.php",
          {
            idDau: POST.idDau,
            accion: "obtenerMedicoTratante"
          },
          'POST',
          'JSON',
          1,
          ''
        );

      } catch(error) {
        console.error("Error en obtenerMedicoTratante", error);

        return [];
      }
    }



    function _desplegarMedicoTratante({nombreMedico} = {}) {
      const medicoTratante = (_existe(nombreMedico))
        ? nombreMedico
        : "Médico sin nombre";

      $medicoTratante.val(capitalizarString(medicoTratante));
    }



    function _setearIdMedicoTratante({idUsuario} = {}) {
      const idUsuarioMedico = (_existe(idUsuario))
        ? idUsuario
        : 0

      $medicoTratante.data("idUsuarioMedico", idUsuarioMedico);
    }



    async function _obtenerAcompaniante() {
      try {
        return await ajaxRequest(
          raiz
          + "/controllers"
          + "/server"
          + "/rce"
          + "/hospitalAmigo"
          + "/main_controller.php",
          {
            idDau: POST.idDau,
            accion: "obtenerAcompaniante"
          },
          'POST',
          'JSON',
          1,
          ''
        );

      } catch(error) {
        console.error("Error en obtenerAcompaniante", error);

        return [];
      }
    }



    function _desplegarAcompaniante(acompaniante) {
      if (!_existe(acompaniante)) return;

      const {
        entregaInformacion,
        motivo,
        nombreAcompaniante
      } = acompaniante;

      $entregaInformacion.val(entregaInformacion);
      $motivo.val(motivo);
      $acompaniante.val(nombreAcompaniante);
    }



    function _setearCamposDisabled(estadoDau) {
      if (!_existe(estadoDau)) {
        console.error("Error en _setearCamposDisabled: no existe estadoDau");
        return;
      }

      if (!estadosDau.includes(Number(estadoDau))) return;

      $(`
        ${formulario} input,
        ${formulario} select,
        ${formulario} textarea
      `).prop("disabled", true);

    }



    function _esFormularioValido() {
      let formularioValido = true;

      if (!_existe($entregaInformacion.val())) {
        $("#frm_entregaInformacion").assert(
          false,
          "Debe seleccionar entrega"
        );
        formularioValido = false;
      }

      if (!_existe($acompaniante.val())) {
        $("#frm_nombreFamiliarOAcompaniante").assert(
          false,
          "Debe ingresar nombre"
        );
        formularioValido = false;
      }

      return formularioValido;
    }



    async function _agregar() {
      const respuesta = await ajaxRequest(
        raiz
        + "/controllers"
        + "/server"
        + "/rce"
        + "/hospitalAmigo"
        + "/main_controller.php",
        {
          idDau: POST.idDau,
          entregaInformacion: $entregaInformacion.val(),
          motivo: $motivo.val(),
          nombreAcompaniante: $acompaniante.val(),
          horaEntregaInformacionMedica: $hora.val(),
          idUsuarioMedico: $medicoTratante.data("idUsuarioMedico"),
          nombreMedico: $medicoTratante.val(),
          accion: "ingresarFamiliarOAcompaniante"
        },
        'POST',
        'JSON',
        1,
        ''
      );

      switch (respuesta.status) {
        case "success":
          $(`${modal}`).modal( 'hide' ).data( 'bs.modal', null );
          var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito! </h4>  <hr>  <p class="mb-0">Se ha ingresado con éxito el familiar o acompañate a paciente en DAU.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
          break;

        case "error":
          var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error en el proceso </h4>  <hr>  <p class="mb-0">Error en ingresar acompañante:<br><br><br><br>'+respuesta.message+'.</p></div>';
          modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success")

          break;

        default:
          ErrorSistemaDefecto();
          break;
      }
    }



    //Retorno objeto
    return {
      iniciar,
      ingresar
    }
  })();

  ingresarAcompaniante.iniciar();
  ingresarAcompaniante.ingresar();
});
