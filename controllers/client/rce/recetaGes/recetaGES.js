"use strict";

$(document).ready(function () {
  const recetaGES       = (function recetaGES() {
    //Variables
    const idDau         = $("#recetaGES #idDAU").val();
    const idRCE         = $("#recetaGES #idRCE").val();
    const idPaciente    = $("#recetaGES #idPaciente").val();
    const tabla         = "#tablaRecetaGES";
    const claseDosis    = "dosis";
    const claseDias     = "dias";
    const $btnIngresar  = $("#btnIngresarRecetaGES");
    let medicamentos    = [];
    let detalleReceta   = [];
    //Funciones públicas
    function iniciar() {
      medicamentos = _obtenerMedicamentos();
      if (medicamentos.length === 0) {
        return;
      }
      _adjuntarATablaLosMedicamentos(medicamentos);
      _validarTipoDatosFormulario();

      detalleReceta = _obtenerDetalleRecetaGES();
      if (detalleReceta.length === 0) {
        return;
      }
      _rellenarTablaConDetalleReceta(medicamentos, detalleReceta);

      if (!_pacieteHaSidoEgresado()) {
        return;
      }
      _camposReadOnly();
    }
    function ingresarReceta() {

      $btnIngresar.on("click", () => _ingresarReceta(medicamentos, detalleReceta[0]?.idRecetaGES));
    }
    //Funciones privadas
    function _obtenerMedicamentos() {
      const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,{accion: "obtenerMedicamentos"},'POST','JSON',1,'');
      return (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null)
        ? respuestaAjaxRequest
        : [];
    }
    function _adjuntarATablaLosMedicamentos(medicamentos) {

      $(`${tabla} > tbody`).append(_generarHTMLFilaDeMedicamentos(medicamentos));
    }
    function _generarHTMLFilaDeMedicamentos(medicamentos) {
      return medicamentos.map(({descripcionMedicamento, idMedicamentoRecetaGES}) =>
        `
        <tr>
          <td class=" my-2 py-2 mx-1 px-1 mifuente " >
            ${descripcionMedicamento}
          </td>
          <td class=" my-1 py-1 mx-1 px-1 mifuente " >
            <input type="text" class="form-control form-control-sm mifuente  text-center ${claseDosis}" id="dosis-${idMedicamentoRecetaGES}">
          </td>
          <td class=" my-1 py-1 mx-1 px-1 mifuente " >
            <input type="text" class="form-control form-control-sm mifuente  text-center  ${claseDias}" id="dias-${idMedicamentoRecetaGES}">
          </td>
        </tr>
        `
      ).join("");
    }
    function _validarTipoDatosFormulario(tabla, claseDosis, claseDias) {
      $(`${tabla} .${claseDosis}, ${tabla} .${claseDias}`).each(function() {
        const id = $(this).attr("id");
        validar(`#${id}`, "letras_numeros");
      });
    }
    function _obtenerDetalleRecetaGES() {
      if (idDau === undefined || idDau === null || idDau === "") {
        return [];
      }
      const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,{idDau,accion: "obtenerDetalleRecetaGES"},'POST','JSON',1,'');
      return (respuestaAjaxRequest !== undefined && respuestaAjaxRequest !== null)
        ? respuestaAjaxRequest
        : [];
    }
    function _rellenarTablaConDetalleReceta(medicamentos, detalleReceta) {
      medicamentos.forEach(medicamento => {
        const detalle = detalleReceta.find(detalle =>
          Number(detalle.idMedicamentoRecetaGES) === Number(medicamento.idMedicamentoRecetaGES)
        );
        if (detalle === undefined || detalle === null || Object.keys(detalle).length === 0) {
          return false;
        }
        $(`#dosis-${detalle.idMedicamentoRecetaGES}`).val(detalle.dosis);
        $(`#dias-${detalle.idMedicamentoRecetaGES}`).val(detalle.dias);
      });
    }
    function _pacieteHaSidoEgresado() {
      const respuestaAjaxRequest = ajaxRequest(raiz+'/controllers/server/medico/main_controller.php',{idDau,accion: 'pacienteEgresado'},'POST','JSON',1);
      return respuestaAjaxRequest.status === 'success';
    }
    function _camposReadOnly() {
      $(`${tabla} .${claseDosis}, ${tabla} .${claseDias}`).each(function() {
        $(this).prop("readonly", true)
      });
    }
    function _ingresarReceta(medicamentos, idRecetaGES) {
      if (_formularioVacio(medicamentos)) {
        console.log("RECETA GES: formulario vacío");
        return;
      }
      if (!_verificarDatos(medicamentos)) {
        console.log("RECETA GES: datos faltantes");
        return;
      }
      if (_pacieteHaSidoEgresado()) {
        console.log("Paciente egresado");
        _imprimirRecetaGES(idRecetaGES)
        return;
      }
      _ingresarRecetaGES(medicamentos);
    }
    function _formularioVacio(medicamentos) {
      let formularioVacio = medicamentos.every(medicamento => {
        const $dosis = $(`#dosis-${medicamento.idMedicamentoRecetaGES}`);
        const $dias = $(`#dias-${medicamento.idMedicamentoRecetaGES}`);
        return (
          ($dosis.val() === undefined || $dosis.val() === null || $dosis.val() === "")
          && ($dias.val() === undefined || $dias.val() === null || $dias.val() === "")
        )
      });
      if (formularioVacio) {
        $("#dosis-1").assert(false,'Fomulario No Debe Estar Vacío');
      }
      return formularioVacio;
    }
    function _verificarDatos(medicamentos) {
      let banderaNoError = true;
      medicamentos.forEach(medicamento => {
        const $dosis = $(`#dosis-${medicamento.idMedicamentoRecetaGES}`);
        const $dias = $(`#dias-${medicamento.idMedicamentoRecetaGES}`);
        if (
          ($dosis.val() !== undefined && $dosis.val() !== null && $dosis.val() !== "")
          && ($dias.val() === undefined || $dias.val() === null || $dias.val() === "")
        ) {
          $dias.assert(false,'Debe Ingresar Información');
          banderaNoError = false;
        }
        if (
          ($dias.val() !== undefined && $dias.val() !== null && $dias.val() !== "")
          && ($dosis.val() === undefined || $dosis.val() === null || $dosis.val() === "")
        ) {
          $dosis.assert(false,'Debe Ingresar Información');
          banderaNoError = false;
        }
      });
      return banderaNoError;
    }
    function _ingresarRecetaGES(medicamentos) {
      const respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/rce/recetaGES/main_controller.php`,{idDau,idRCE,idPaciente,detalleRecetaGES: _parametrosDetalleRecetaGES(medicamentos),accion: "ingresarRecetaGES"},'POST','JSON',1,'');
      if (respuestaAjaxRequest.status !== "success") {
        return;
      }
      _imprimirRecetaGES(respuestaAjaxRequest.idRecetaGES);
      $('#recetaGES').modal( 'hide' ).data( 'bs.modal', null );
    }
    function _parametrosDetalleRecetaGES(medicamentos) {
      return medicamentos
        .filter(_medicamentosIngresados)
        .map(_dosisYDiasMedicamentosIngresados);
    }
    function _medicamentosIngresados({idMedicamentoRecetaGES}) {
      const $dosis = $(`#dosis-${idMedicamentoRecetaGES}`);
      const $dias = $(`#dias-${idMedicamentoRecetaGES}`);
      return (
        ($dosis.val() !== undefined && $dosis.val() !== null && $dosis.val() !== "")
        && ($dias.val() !== undefined && $dias.val() !== null && $dias.val() !== "")
      );
    } 
    function _dosisYDiasMedicamentosIngresados({idMedicamentoRecetaGES}) {
      return {
        idMedicamentoRecetaGES: idMedicamentoRecetaGES,
        dosis: $(`#dosis-${idMedicamentoRecetaGES}`).val(),
        dias: $(`#dias-${idMedicamentoRecetaGES}`).val()
      }
    }
    function _imprimirRecetaGES(idRecetaGES) {
      const botones =   [{
        id: 'btnImprimir',
        value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir',
        function: imprimir,
        class: 'btn btn-primary btnPrint'
      }];
        modalFormulario("<label class='mifuente ml-2'>Receta GES Urgencia, DAU N°"+idDau+"</label>", `${raiz}/views/modules/rce/rce/pdfRecetaGES.php`, {idRecetaGES}, "#modalPDFRecetaGES", "modal-lg", "light",'', botones);
        
      // modalFormulario(`Receta GES Urgencia, DAU N° ${idDau}`,raiz+"/views/modules/rce/rce/pdfRecetaGES.php",{idRecetaGES},"#modalPDFRecetaGES","66%","100%",botones);
      function imprimir() {
        $('#pdfRecetaGES').get(0).contentWindow.focus();
        $("#pdfRecetaGES").get(0).contentWindow.print();
      }
    }
    //Retorno objeto
    return {
      iniciar,
      ingresarReceta
    }
  })();
  recetaGES.iniciar();
  recetaGES.ingresarReceta();
});
