
$(document).ready(function(){
    $('#frm_fecha_pizarra').on('change', function () {
        ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/turnoCRUrgencia.php`, 'chk_enfermeria='+$('#frm_chk_enfermeria').val()+'&frm_fecha_pizarra='+$('#frm_fecha_pizarra').val(), '#contenido', 'Cargando...', true);
    });


function simularAgregarLista(tipo, items) {
  if (!Array.isArray(items)) return;
  const sel = (tipo === "residentes") ? "#input_residente"
           : (tipo === "cirujanos")  ? "#input_cirujano"
           : (tipo === "tens")       ? "#input_tens"
           :                           "#input_enfermero";

  items.forEach(p => {
    const rutNum  = (p.rut_profesional_digito || '').toString().trim();
    const rutFmt  = rutNum ? (rutNum ) : '';
    const nombre  = (p.nombre_profesional || '').toString().trim();
    const id      = p.id_profesional || p.id || null;

    if (!rutFmt || !nombre) return;

    // Cargar los data-* en el input que tu función ya usa
    const $inp = $(sel);
    $inp.data('rut', rutFmt)
        .data('nombre', nombre)
        .data('id', id);

    // Llamar a tu función actual
    agregarALista(tipo);
  });
}
var residentes = []; // {rut, nombre, id?}
var cirujanos  = []; // {rut, nombre, id?}
var tens       = []; // {rut, nombre, id?}
var enfermeros = []; // {rut, nombre, id?}

    $(".verPizarra").click(function(){
        const id_pizarra = $(this).attr('id');
        $(document).off('click', '#btnSincronizarPizarra').on('click', '#btnSincronizarPizarra', function () {
            let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/turnoCRUrgencia/main_controller.php`, "id_pizarra="+id_pizarra+'&accion=ObtenerDatosPizarra', 'POST', 'JSON', 1,'Aplicando Acción a Solicitud Guardar Datos Turno...');
              if (respuestaAjaxRequest.status === 'error') {
                return;
              }
              const { arrayMedico, arrayTens, arrayEnfermero, arrayCirujano } = respuestaAjaxRequest;

            // Simular agregarALista para cada grupo
              simularAgregarLista("residentes",  arrayMedico    || []);
              simularAgregarLista("tens",        arrayTens      || []);
              simularAgregarLista("enfermeros",  arrayEnfermero || []);
              simularAgregarLista("cirujanos",   arrayCirujano  || []);
                    $('#mdl_pizarra').modal( 'hide' ).data( 'bs.modal', null );
                });
        let botones =   [
                            {
                                id      : 'btnSincronizarPizarra',
                                value   : '<i class="fas fa-sync-alt"></i> Sincronizar',
                                class   : 'btn btn-primary'
                            }
                        ]
        modalFormulario("<label class='mifuente ml-2'>Hoja Ingreso Enfermeria</label>", raiz+"/views/modules/turnoCRUrgencia/ContenidoPizarra.php", 'id_pizarra='+id_pizarra+'&frm_fecha_pizarra='+$('#frm_fecha_pizarra').val(), "#mdl_pizarra", "modal-lg", "light",'', botones);
     });
// función helper para mostrar error temporal
$.fn.assertTemp = function (cond, msg, timeout = 3000) {

    
    const elem = this[0];
    if (!cond) {
        // alerta de validity
        elem.setCustomValidity(msg);
        elem.reportValidity();

        // borde rojo
        this.removeClass("input-error");
        this.addClass("input-error");

        // limpiar error después del tiempo
        setTimeout(() => {
            elem.setCustomValidity("");
            this.removeClass("input-error");
        }, 100000000000);
    }
    return this;
}
    identificador = false;
    const turnoCRUrgencia = ( function TurnoRCUrgencia ( ) {
        //Declaración variables
        const   $slcTipoHorarioTurno    = $('#frm_tipoHorarioTurno'),
                $frmNovedadesTurno      = $('#frm_novedadesTurno'),
                $btnGuardarDatosTurno   = $('#btnGuardarDatosTurno'),
                $btnResetearDatosTurno  = $('#btnResetearDatosTurno'),
                $accionProfesionalTurno = $('.accionProfesionalTurno');
        let     idDiv               = '',
                $profesionalTurno   = $,
                $idProfesionalTurno = $,
                arrayIdsProfesionalesIngresados = [];
                profesionalEntregaTurnoIngresado = false;
        //Funciones privadas
        function _cargarPaginasConInformacionSegunProfesional ( ) {
            parametros.tipoHorarioTurno = $slcTipoHorarioTurno.val();
            parametros.idProfesion = ( $idProfesionalTurno != null ) ? $idProfesionalTurno.val() : "";
            // ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/cirugiasRealizadas.php`, parametros, '#divCirugiasRealizadas', 'Cargando...', true);
            // ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/tiemposAtencion.php`, parametros, '#divTiemposAtencion', 'Cargando...', true);
            // ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/tiemposPromedioCategorizacion.php`, parametros, '#divTiemposPromedio', 'Cargando...', true);
        }
        function _confirmarGuardarDatosTurno ( ) {
            let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/turnoCRUrgencia/main_controller.php`, $('#frm_despliegueParametrosTurno').serialize()+'&accion=guardarDatosTurno&residentes='+encodeURIComponent(JSON.stringify(residentes))+'&cirujanos='+encodeURIComponent(JSON.stringify(cirujanos ))+'&tens='+encodeURIComponent(JSON.stringify(tens))+'&enfermeros='+encodeURIComponent(JSON.stringify(enfermeros)), 'POST', 'JSON', 1,'Aplicando Acción a Solicitud Guardar Datos Turno...');
            if ( respuestaAjaxRequest.status === 'error' ) {
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Entrega Turno </h4>  <hr>  <p class="mb-0">Se ha producido el siguiente error:<br><br> '+respuestaAjaxRequest.mensaje+'.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return;
            }else{
                modalFormulario_noCabecera('Documento Entrega Turno', `${raiz}/views/modules/turnoCRUrgencia/pdfTurnoCRUrgencia.php`, `idTurnoCRUrgencia=${respuestaAjaxRequest.idTurnoCRUrgencia}`, "#pdfTurnoCRUrgencia", "modal-lg", "", "fas fa-plus");
                ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/turnoCRUrgencia.php`, 'chk_enfermeria='+$('#frm_chk_enfermeria').val(), '#contenido', 'Cargando...', true);
      
            }
        }
        function _guardarDatosTurno ( ) {

            // console.log(residentes);
            // console.log(cirujanos);
            if ( ! _verificarDatosTurno() ) {
                return;
            }
            modalConfirmacionNuevo("Advertencia", "ATENCIÓN, se procederá a guardar la Entrega de Turno, revise que todos los datos estén correctos. <b>¿Desea continuar?</b>", "primary", _confirmarGuardarDatosTurno);
        }
        function _obtenerCirugiasRealizadas ( ) {
            if ( $slcTipoHorarioTurno !== null && idDomIdProfesionalTurno == 'frm_idProfesionalEntregaTurno' ) {
                _cargarPaginasConInformacionSegunProfesional();
            }
        }
        function _obtenerFechaActual ( ) {
            let fechaActual = new Date();
            return ('0' + fechaActual.getDate()).slice(-2) + '-' + ('0' + (fechaActual.getMonth()+1)).slice(-2) + '-' + fechaActual.getFullYear();
        }
        function _obtenerHoraActual ( ) {
            let horaActual = new Date();
            return  ('0' + horaActual.getHours()).slice(-2)+ ":" + ('0' + horaActual.getMinutes()).slice(-2) + ":" + ('0' + horaActual.getSeconds()).slice(-2);
        }
        function _obtenerIdCamposFormularios ( ) {
            idDomProfesionalTurno = $(`#${idDiv} .profesionalTurno`).attr('id');
            idDomIdProfesionalTurno = $(`#${idDiv} .idProfesionalTurno`).attr('id');
            $profesionalTurno = $(`#${idDomProfesionalTurno}`);
            $idProfesionalTurno = $(`#${idDomIdProfesionalTurno}`);
        }
        function _obtenerProfesionalTurno ( ) {
            if ( $slcTipoHorarioTurno.val() == null ) {
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Entrega Turno </h4>  <hr>  <p class="mb-0">Debe elegir primero un Tipo de Turno antes de cargar datos de un Profesional (Quien Entrega o Recibe Turno).</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return;
            }
            _obtenerIdCamposFormularios();
            _resetearCampoProfesional();
            if ( idDomIdProfesionalTurno == 'frm_idProfesionalEntregaTurno' ) {
                _resetearPaginasConInformacionSegunProfesional();
            }
            fn_global = _profesionalTurno;
            modalFormulario("<label class='mifuente ml-2'>Acceso DAU</label>", `${raiz}/views/modules/identificacion/identificacion.php`, `accessRequest=turnoCRUrgencia`, "#accesoPistola", "modal-md", "light",'', '');

            // usuario.inicializarPermisosUsuario(_profesionalTurno, 'turnoCRUrgencia', 'validarAccion', 1);
            // usuario.verificarPermisoUsuario();
        }
        function _obtenerSolicitudesEspecialista ( ) {
            if ( $slcTipoHorarioTurno !== null ) {
                const parametros = { 'tipoHorarioTurno' : $slcTipoHorarioTurno.val() };
                ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/solicitudesEspecialista.php`, parametros, '#divSolicitudesEspecialista', 'Cargando...', true);
            }
        }
        function _profesionalTurno ( ) {
            if ( ! _verificarSiProfesionalYaHaSidoAgregado() ) {
                _resetearParametrosLocales();
                _resetearCampoProfesional();
                return;
            }
            if ( ! _verificarProfesionalTurnoUrgencia() ) {
                _resetearParametrosLocales();
                _resetearCampoProfesional();
                return;
            }
        }
        function _resetearCamposFormularios ( ) {
            $('#frm_fechaActualTurno').val(_obtenerFechaActual());
            $('#frm_horaActualTurno').val(_obtenerHoraActual());
            $slcTipoHorarioTurno.val('');
            $accionProfesionalTurno.each(function(){
                $(`#${$(this).attr('id')} .profesionalTurno`).val('');
                $(`#${$(this).attr('id')} .idProfesionalTurno`).val('');
            });
            $frmNovedadesTurno.val('');
        }
        function _resetearCampoProfesional ( ) {
            $profesionalTurno.val('');
            $idProfesionalTurno.val('');
        }
        function _resetearDatosTurno ( ) {
            _resetearParametrosLocales();
            _resetearCamposFormularios();
            _resetearPaginasConInformacionSegunProfesional();
        }
        function _resetearPaginasConInformacionSegunProfesional ( ) {
            $('#divCirugiasRealizadas').html('');
            $('#divTiemposAtencion').html('');
            $('#divTiemposPromedio').html('');
        }
        function _resetearParametrosLocales ( ) {
            if ( idDomIdProfesionalTurno == 'frm_idProfesionalEntregaTurno' ) {
                _resetearPaginasConInformacionSegunProfesional();
            }
            idDiv = '';
            arrayIdsProfesionalesIngresados.length = 0;
            idDomProfesionalTurno = '';
            idDomIdProfesionalTurno = '';
            // usuario.resetearPermisosUsuario();
        }
        function _verificarDatosTurno ( ) {
            let noErrorFlag = true;
            $(".input-error").removeClass("input-error");
            $accionProfesionalTurno.each(function(){
                let idDiv = $(this).attr('id');
                if ( $(`#${idDiv} .idProfesionalTurno`).val() == '' ) {
                    $(`#${idDiv} .profesionalTurno`).assertTemp(false,'Debe Ingresar Profesional', 2000);
                    noErrorFlag = false;
                }else if ( $('#medico_jef_turno').val() == "" ) {
                    $('#medico_jef_turno').assertTemp(false,'Debe indicar el nombre', 2000);
                    noErrorFlag = false;
                }
                if ( $('#frm_chk_enfermeria').val() == "S" ) { 
                    if ( $('#enf_jef_turno').val() == "" ) {
                        $('#enf_jef_turno').assertTemp(false, 'Debe indicar el nombre', 2000);
                        noErrorFlag = false;
                    }
                }
                if ($("#list_residentes .list-group-item").length <= 0) {
                    $('#input_residente').assertTemp(false,'Debe Agregar Medicos', 2000);
                    noErrorFlag = false;
                } 
                if ($("#list_cirujanos .list-group-item").length <= 0) {
                    $('#input_cirujano').assertTemp(false,'Debe Agregar Cirujanos', 2000);
                    noErrorFlag = false;
                } 
                if ( $('#frm_chk_enfermeria').val() == "S" ) { 
                    if ($("#list_tens .list-group-item").length <= 0) {
                        $('#input_tens').assertTemp(false,'Debe Agregar TENS', 2000);
                        noErrorFlag = false;
                    } 
                    if ($("#list_enfermeros .list-group-item").length <= 0) {
                        $('#input_enfermero').assertTemp(false,'Debe Agregar Enfermeros', 2000);
                        noErrorFlag = false;
                    } 
                }
            });
            // if ( $('#medico_jef_turno').val() == null ) {
            //     $('#medico_jef_turno').assert(false,'Debe indicar el nombre');
            //     noErrorFlag = false;
            // }
            if ( $slcTipoHorarioTurno.val() == null ) {
                $slcTipoHorarioTurno.assert(false,'Debe Seleccionar Horario Turno');
                noErrorFlag = false;
            }
            return noErrorFlag;
        }
        function _verificarProfesionalTurnoUrgencia ( ) {
            parametros = { 'idProfesional' : $idProfesionalTurno.val(), 'accion' : 'verificarProfesionalTurnoUrgencia' };
            respuestaAjaxRequest = ajaxRequest( `${raiz}/controllers/server/turnoCRUrgencia/main_controller.php`, parametros, 'POST', 'JSON', 1, 'Verificando USUARIO ...');
            if ( respuestaAjaxRequest.status !== 'success' ) {
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Entrega Turno </h4>  <hr>  <p class="mb-0">El Profesional <strong>'+$idProfesionalTurno.val()+' </strong> no se encuentra dentro del listado de Profesionales o Suplentes que realizan Turnos de Urgencia.</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return false;
            }
            if ( idDomIdProfesionalTurno == 'frm_idProfesionalEntregaTurno' ) {
                _cargarPaginasConInformacionSegunProfesional($('#frm_idProfesionalEntregaTurno').val());
            }
            return true;
        }
        function _verificarSiProfesionalYaHaSidoAgregado ( ) {
            arrayIdsProfesionalesIngresados.length = 0;
            $accionProfesionalTurno.each(function(){
                arrayIdsProfesionalesIngresados.push($(`#${$(this).attr('id')} .idProfesionalTurno`).val());
            });
            if ( $.unique(arrayIdsProfesionalesIngresados.sort()).sort().length === 1  ) {
                var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Entrega Turno </h4>  <hr>  <p class="mb-0">El Profesional <strong>'+$idProfesionalTurno.val()+' </strong> ya se encuentra agregado (como Profesional que Entrega ó Recibe Turno).</p></div>';
                modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
                return false;
            }
            return true;
        }
        //Funciones públicas
        function guardarDatosTurno ( ) {
            $btnGuardarDatosTurno.on('click', function(){
                _guardarDatosTurno();
            });
        }
        function obtenerCirugiasRealizadas ( ) {
            $slcTipoHorarioTurno.on('change', function(){
                _obtenerCirugiasRealizadas();
            });
        }
        function obtenerProfesionalTurno ( ) {
            $accionProfesionalTurno.on('click', function(){
                idDiv = $(this).attr('id');
                _obtenerProfesionalTurno();
            });
        }
        function obtenerSolicitudesEspecialista ( ) {
            _obtenerSolicitudesEspecialista();
            $slcTipoHorarioTurno.on('change', function(){
                _obtenerSolicitudesEspecialista();
            });
        }
        function resetearDatosTurno ( ) {
            $btnResetearDatosTurno.on('click', function(){
                _resetearDatosTurno();
            });
        }
        //Retorno de objeto
        return {
            guardarDatosTurno               : guardarDatosTurno,
            obtenerCirugiasRealizadas       : obtenerCirugiasRealizadas,
            obtenerProfesionalTurno         : obtenerProfesionalTurno,
            obtenerSolicitudesEspecialista  : obtenerSolicitudesEspecialista,
            resetearDatosTurno              : resetearDatosTurno
        }
    })();
    turnoCRUrgencia.obtenerProfesionalTurno();
    // turnoCRUrgencia.obtenerSolicitudesEspecialista();
    // turnoCRUrgencia.obtenerCirugiasRealizadas();
    turnoCRUrgencia.guardarDatosTurno();
    turnoCRUrgencia.resetearDatosTurno();
    var medicoAgregadoJefe = null;

$("#medico_jef_turno").autocomplete({
  close: function(event, ui) {
    if (!medicoAgregadoJefe && $("#medico_jef_turno").val() === "") {
      $("#medico_jef_turno").val("");
    }
  },
  source: function(request, response) {
    $.ajax({
      type: "POST",
      url: raiz + "/controllers/server/consulta/main_controller.php",
      dataType: "json",
      data: {
        term: request.term,
        accion: 'busquedaSensitivaMedicos',
      },
      success: function(data) {
        response(data);
      }
    });
  },
  minLength: 3,
  select: function(event, ui) {
    $('#medico_jef_turno_rut').val(ui.item.id);
    $('#rut_medico').val(ui.item.rut);
    $('#medico_jef_turno').val(ui.item.nombre);
  },
  open: function() {
    $('.ui-menu').addClass("col-md-12 mifuente11");
  }
});
// Arrays de trabajo
// var residentes = []; // {rut, nombre, id?}
// var cirujanos  = []; // {rut, nombre, id?}
// 
// ------ Autocomplete genérico (reutilizable) ------
function setupAutocomplete(selectorInput,tipo) {
  $(selectorInput).autocomplete({
    source: function(request, response) {
      $.ajax({
        type: "POST",
        url: raiz + "/controllers/server/consulta/main_controller.php",
        dataType: "json",
        data: { term: request.term,tipo :tipo, accion: 'busquedaSensitivaUsuarios' },
        success: function(data){ response(data); }
      });
    },
    minLength: 3,
    select: function(event, ui) {
      // Guarda los datos seleccionados en "data-*" del input
      const $inp = $(this);
      $inp.data("id", ui.item.id);
      $inp.data("rut", ui.item.rut);
      $inp.data("nombre", ui.item.nombre);
      $inp.val(ui.item.nombre);
      return false; // evita que jQueryUI reemplace el valor de nuevo
    },
    open: function() {
      $('.ui-menu').addClass("col-md-12 mifuente11");
    }
  });
}

// Inicializa los cuatro autocompletes
setupAutocomplete("#input_residente",1);
setupAutocomplete("#input_cirujano",1);
setupAutocomplete("#input_tens",17);
setupAutocomplete("#input_enfermero",2);
// Autocomplete para Jefe Turno Enfermería (usa mismo endpoint)
// setupAutocomplete("#enf_jef_turno");

$("#enf_jef_turno").autocomplete({
  close: function(event, ui) {
    if (!medicoAgregadoJefe && $("#enf_jef_turno").val() === "") {
      $("#enf_jef_turno").val("");
    }
  },
  source: function(request, response) {
    $.ajax({
      type: "POST",
      url: raiz + "/controllers/server/consulta/main_controller.php",
      dataType: "json",
      data: {
        term: request.term,
        accion: 'busquedaSensitivaMedicos',
      },
      success: function(data) {
        response(data);
      }
    });
  },
  minLength: 3,
  select: function(event, ui) {
    $('#enf_jef_turno_rut').val(ui.item.id);
    $('#rut_medico').val(ui.item.rut);
    $('#enf_jef_turno').val(ui.item.nombre);
  },
  open: function() {
    $('.ui-menu').addClass("col-md-12 mifuente11");
  }
});

// ------ Utilidades de render y ocultos ------
function renderLista(contenedorId, items, tipo) {
  if (!items.length) {
    $(contenedorId).html(`<div class="text-muted mifuente11">Sin ${tipo} agregados.</div>`);
    return;
  }

  // List group con botón eliminar por ítem
  const html = items.map(m => `
    <div class="list-group-item d-flex justify-content-between align-items-center p-2">
      <div class="mifuente11">
        <strong>${m.nombre}</strong><br>
        <span class="text-muted">RUT: ${m.rut}</span>
      </div>
      <button type="button" class="btn btn-sm btn-outline-danger btn-remove" data-rut="${m.rut}" data-tipo="${tipo}">
        <i class="fas fa-times"></i>
      </button>
    </div>
  `).join("");

  $(contenedorId).html(`<div class="list-group list-group-flush border rounded-2">${html}</div>`);
}

function renderOcultos(contenedorHiddenId, items, nombreCampo = "") {
  // Limpia y regenera los inputs ocultos como arrays
  const $c = $(contenedorHiddenId).empty();
  items.forEach(m => {
    $c.append(`<input type="hidden" name="${nombreCampo}_rut[]" value="${m.rut}">`);
    $c.append(`<input type="hidden" name="${nombreCampo}_nombre[]" value="${$('<div>').text(m.nombre).html()}">`);
  });
}

// ------ Lógica de agregar ------
function agregarALista(tipo) {

            console.log(residentes);
            console.log(cirujanos);
  const sel = (tipo === "residentes") ? "#input_residente" : (tipo === "cirujanos" ? "#input_cirujano" : (tipo === "tens" ? "#input_tens" : "#input_enfermero"));
  const $inp = $(sel);
  const rut = ($inp.data("rut") || "").trim();
  const nombre = ($inp.data("nombre") || "").trim();

  if (!rut || !nombre) {
    // alert("Debes seleccionar un profesional válido desde el autocompletar.");
    return;
  }

  let arr = (tipo === "residentes") ? residentes : (tipo === "cirujanos" ? cirujanos : (tipo === "tens" ? tens : enfermeros));

  // evita duplicados por RUT
  if (arr.some(x => x.rut === rut)) {
    // alert("Este profesional ya fue agregado.");
    return;
  }

  arr.push({ rut, nombre, id: $inp.data("id") || null });

  // Render UI y ocultos
  if (tipo === "residentes") {
    renderLista("#list_residentes", residentes, "residentes");
    renderOcultos("#hidden_residentes", residentes, "residentes");
  } else if (tipo === "cirujanos") {
    renderLista("#list_cirujanos", cirujanos, "cirujanos");
    renderOcultos("#hidden_cirujanos", cirujanos, "cirujanos");
  } else if (tipo === "tens") {
    renderLista("#list_tens", tens, "tens");
    renderOcultos("#hidden_tens", tens, "tens");
  } else {
    renderLista("#list_enfermeros", enfermeros, "enfermeros");
    renderOcultos("#hidden_enfermeros", enfermeros, "enfermeros");
  }

  // Limpia el input y sus datas
  $inp.val("").removeData("rut").removeData("nombre").removeData("id");
}

// Botones agregar
$("#btn_add_residente").on("click", function(){ agregarALista("residentes"); });
$("#btn_add_cirujano").on("click",  function(){ agregarALista("cirujanos");  });
$("#btn_add_tens").on("click",      function(){ agregarALista("tens");       });
$("#btn_add_enfermero").on("click",  function(){ agregarALista("enfermeros"); });

// ------ Eliminar de lista (delegado) ------
$(document).on("click", ".btn-remove", function() {
  const rut  = $(this).data("rut");
  const tipo = $(this).data("tipo"); // "residentes", "cirujanos", "tens", "enfermeros"

  if (tipo === "residentes") {
    residentes = residentes.filter(x => x.rut !== rut);
    renderLista("#list_residentes", residentes, "residentes");
    renderOcultos("#hidden_residentes", residentes, "residentes");
  } else if (tipo === "cirujanos") {
    cirujanos = cirujanos.filter(x => x.rut !== rut);
    renderLista("#list_cirujanos", cirujanos, "cirujanos");
    renderOcultos("#hidden_cirujanos", cirujanos, "cirujanos");
  } else if (tipo === "tens") {
    tens = tens.filter(x => x.rut !== rut);
    renderLista("#list_tens", tens, "tens");
    renderOcultos("#hidden_tens", tens, "tens");
  } else {
    enfermeros = enfermeros.filter(x => x.rut !== rut);
    renderLista("#list_enfermeros", enfermeros, "enfermeros");
    renderOcultos("#hidden_enfermeros", enfermeros, "enfermeros");
  }
});

// ------ Render inicial ------
renderLista("#list_residentes", residentes, "residentes");
renderLista("#list_cirujanos",  cirujanos,  "cirujanos");
renderLista("#list_tens",       tens,       "tens");
renderLista("#list_enfermeros", enfermeros, "enfermeros");

// Mostrar/ocultar según Sí/No
function toggleNovedadesBox(show) {
  if (show) {
    $("#box_novedades").slideDown(120);
  } else {
    // limpiar valores y contadores
    $("#box_novedades").slideUp(120);
    $("#novedades_general, #novedades_adm, #novedades_infra, #novedades_equip").val("");
    $("#cnt_general").text("0");
    $("#cnt_adm").text("0");
    $("#cnt_infra").text("0");
    $("#cnt_equip").text("0");
  }
}

$("input[name='novedades_turno_si_no']").on("change", function() {
  toggleNovedadesBox($(this).val() === "S");
});

// ------ Entrega conforme: motivo si No ------
$(document).on("change", "input[name='entrega_conforme']", function(){
  if ($("input[name='entrega_conforme']:checked").val() === 'N') {
    $("#box_entrega_no").slideDown(120);
  } else {
    $("#box_entrega_no").slideUp(120);
    $("#entrega_no_motivo").val("");
  }
});

// ------ Ecógrafo: motivo si No ------
$(document).on("change", "input[name='ecografo_disponible']", function(){
  if ($("input[name='ecografo_disponible']:checked").val() === 'N') {
    $("#box_eco_no").slideDown(120);
  } else {
    $("#box_eco_no").slideUp(120);
    $("#ecografo_no_motivo").val("");
  }
});

// ------ Restricciones BIC y Celulares (0-10) ------
function clamp0a10($el){
  let v = parseInt(($el.val()||'0'),10);
  if (isNaN(v)) v = 0;
  if (v < 0) v = 0;
  if (v > 10) v = 10;
  $el.val(v);
}
$(document).on('input change', '#bic_cantidad, #celulares_cantidad', function(){
  clamp0a10($(this));
});

// Estado inicial de cajas
$("input[name='entrega_conforme']").trigger('change');
$("input[name='ecografo_disponible']").trigger('change');

// ------ Checkbox Enfermería cambia título ------
$(document).on("change", "#chk_enfermeria", function(){
  const $title = $("#ensure-correct-role-and-provide-a-label");
  const $bloque = $("#bloque_enfermeria");
  const $bloqueJefeEnf = $("#bloque_jefe_enfermeria");
  const $cardEntregaRecursos = $(".card-soft:contains('Entrega y Recursos')");
  if ($(this).is(":checked")) {
    $title.html('<i class="fas fa-circle-notch text-danger mr-2"></i> Crear Resumen Turno CR Urgencia - Enfermeria');
    $bloque.slideDown(120);
    $bloqueJefeEnf.slideDown(120);
    $cardEntregaRecursos.slideDown(120);
    $("#frm_chk_enfermeria").val('S');
    $("#frm_chk_enfermeria_form").val('S');
  } else {
    $title.html('<i class="fas fa-circle-notch text-danger mr-2"></i> Crear Resumen Turno CR Urgencia - Médica');
    $bloque.slideUp(120);
    $bloqueJefeEnf.slideUp(120);
    $cardEntregaRecursos.slideUp(120);
    $("#frm_chk_enfermeria").val('N');
    $("#frm_chk_enfermeria_form").val('N');
    // limpiar listas y ocultos al desactivar
    tens = [];
    enfermeros = [];
    renderLista("#list_tens", tens, "tens");
    renderOcultos("#hidden_tens", tens, "tens");
    renderLista("#list_enfermeros", enfermeros, "enfermeros");
    renderOcultos("#hidden_enfermeros", enfermeros, "enfermeros");
    // limpiar jefe enfermería
    $("#enf_jef_turno").val("").removeData("rut").removeData("nombre").removeData("id");
    $("#enf_jef_turno_rut").val("");
    // limpiar entrega/recursos
    $("#entrega_si").prop('checked', true);
    $("#box_entrega_no").hide();
    $("#entrega_no_motivo").val("");
    $("#bic_cantidad").val(0);
    $("#eco_si").prop('checked', true);
    $("#box_eco_no").hide();
    $("#ecografo_no_motivo").val("");
    $("#celulares_cantidad").val(0);
  }
});

// Inicialización: mantener oculto el bloque si el checkbox arranca apagado
if (!$("#chk_enfermeria").is(":checked")) {
  $("#bloque_enfermeria").hide();
  $("#bloque_jefe_enfermeria").hide();
  $(".card-soft:contains('Entrega y Recursos')").hide();
  $("#frm_chk_enfermeria").val('N');
  $("#frm_chk_enfermeria_form").val('N');
} else {
  $(".card-soft:contains('Entrega y Recursos')").show();
  $("#frm_chk_enfermeria").val('S');
  $("#frm_chk_enfermeria_form").val('S');
}
// Disparar estado inicial para actualizar título y bloque
$("#chk_enfermeria").trigger("change");

// Contadores de caracteres
function bindCounter(textareaSelector, counterSelector) {
  $(textareaSelector).on("input", function() {
    $(counterSelector).text($(this).val().length);
  });
}
bindCounter("#novedades_general", "#cnt_general");
bindCounter("#novedades_adm",     "#cnt_adm");
bindCounter("#novedades_infra",   "#cnt_infra");
bindCounter("#novedades_equip",   "#cnt_equip");

// Validación mínima al enviar (puedes adaptarlo a $.validity si ya lo usas)
function validarNovedadesAntesDeEnviar() {
  const opcion = $("input[name='novedades_turno_si_no']:checked").val();
  if (opcion === "SI") {
    const gen = $("#novedades_general").val().trim();
    if (!gen) {
      alert("Debes ingresar las Novedades (general).");
      $("#novedades_general").focus();
      return false;
    }
  }
  return true;
}
// Si usas un <form id="mi_form">:
$("#mi_form").on("submit", function(e) {
  if (!validarNovedadesAntesDeEnviar()) {
    e.preventDefault();
  }
});

});
