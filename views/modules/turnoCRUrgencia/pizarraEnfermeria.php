<?php
error_reporting(0);
session_start();
require("../../../config/config.php");
require_once("../../../class/Connection.class.php");      $objCon         = new Connection();
require_once("../../../class/Util.class.php");            $objUtil        = new Util;
require_once("../../../class/Parametros.class.php");      $objParametros  = new Parametros();
require_once("../../../class/TurnoCRUrgencia.class.php"); $objTurno       = new TurnoCRUrgencia();
require_once("../../../class/Pizarra.class.php");         $objPizarra     = new Pizarra();

$objCon->db_connect();
$parametros['tipo_parametros']  = 2;
$rsDotacion                     = $objParametros->getParametros($objCon,$parametros);
$parametros['tipo_entrega']     = 2;
$tipoHorarioTurno               = $objTurno->obtenerTipoHorarioTurnoParametros($objCon,$parametros);
$rsServer                       = $objUtil->getHorarioServidor($objCon);
$version                        = $objUtil->versionJS();

if($_POST){
  $parametrosPizarra['idTipoHorarioTurno'] = $_POST['horarioPizarra'];
}else{
  $_POST['horarioPizarra']                 =  $tipoHorarioTurno[0]['idTipoHorarioTurno'];
  $parametrosPizarra['idTipoHorarioTurno'] = $tipoHorarioTurno[0]['idTipoHorarioTurno'];
}
// $parametrosPizarra['fecha_crea']           = $rsServer[0]['fecha'];
if($_POST['frm_fecha_pizarra'] == null){
  $parametrosPizarra['fecha_crea']           = $rsServer[0]['fecha'];
}else{
  $parametrosPizarra['fecha_crea']           = $_POST['frm_fecha_pizarra'];
}
// print('<pre>'); print_r($parametrosPizarra); print('</pre>');
$rsPizarra = $objPizarra->SelectPizarraDetalle($objCon,$parametrosPizarra);
// print('<pre>'); print_r($rsPizarra); print('</pre>');

$hoy      = $parametrosPizarra['fecha_crea'];
$ayer     = date('Y-m-d', strtotime('-1 day'));
$manana   = date('Y-m-d');

?>
<form id="frm_generadorPizarra" name="frm_generadorPizarra" class="formularios" role="form" method="POST">
  <div class="row ">
    <div class="col-lg-10">
      <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-highlighter text-primary"></i> Pizarra de Turno</h6>
    </div>
  </div>
  <div class="row mr-4 ml-4">
    <div class=" col-md-3">
      <select class="form-control form-control-sm mifuente" id="horarioPizarra" name="horarioPizarra">
        <?php for ($i = 0; $i < count($tipoHorarioTurno); $i++) { ?>
          <option value="<?php echo $tipoHorarioTurno[$i]['idTipoHorarioTurno']; ?>" <?php if( $tipoHorarioTurno[$i]['idTipoHorarioTurno'] == $_POST['horarioPizarra'] ){ $horarioActivo = $tipoHorarioTurno[$i]['descripcionHorarioTurno']; echo "selected" ;} ?> >
            <?php echo $tipoHorarioTurno[$i]['descripcionHorarioTurno']; ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="col-md-5 ">&nbsp;
    </div>
    <div class="col-md-2 ">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-calendar darkcolor-barra2"></i></div>
        </div>
        <input
    id="frm_fecha_pizarra"
    type="date"
    class="form-control form-control-sm text-center mifuente12"
    name="frm_fecha_pizarra"
    value="<?= $hoy ?>"
    min="<?= $ayer ?>"
    max="<?= $manana ?>" >

      </div>
    </div>
    <div class="col-md-2 ">
      <div class="input-group shadow">
        <div class="input-group-prepend">
          <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-clock darkcolor-barra2"></i></div>
        </div>
        <input id="frm_horaActualTurno" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_horaActualTurno" placeholder="Hora Actual" value="<?php echo $rsServer[0]['hora'];?>" aria-describedby="btnGroupAddonfrm_dau" readonly>
      </div>
    </div>
  </div>
  <hr>
  <?php if( count($rsPizarra) == 0 ){ ?>
  <div class="row mr-4 ml-4">
    <div class="form-group col-md-3">
      <label>Sección</label>
      <select class="form-control form-control-sm mifuente" id="selectSection">
        <option value="" selected disabled>Seleccionar sección</option>
        <?php for ($i = 0; $i < count($rsDotacion); $i++) { ?>
          <option value="<?php echo $rsDotacion[$i]['id_parametros']; ?>">
            <?php echo $rsDotacion[$i]['descripcion_parametros']; ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group col-md-3">
      <label>Rol</label>
      <select class="form-control form-control-sm mifuente" id="selectRol">
      </select>
    </div>
    <div class="form-group col-md-4">
      <label>Nombre</label>
      <input type="text" class="form-control form-control-sm mifuente" id="inputNombre" placeholder="Nombre y apellido">
    </div>
    <div class="form-group col-md-2">
      <label>&nbsp;</label>
      <button type="button" class="btn btn-sm mifuente btn-primary btn-block" id="btnAgregar">
        <i class="fas fa-plus"></i> Agregar
      </button>
    </div>
  </div>
  <ul class="list-group mb-3 mifuente14" id="cart-list">
  </ul>
  <?php }else{ ?>
  <div class="row ">
    <div class="col-lg-12 text-center">
      <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;">  Pizarra <B>GENERADA</B> <i class="fas fa-highlighter text-success ml-1 mr-1"></i> en el horario <b><?=$horarioActivo;?></b> </h6>
    </div>
  </div>
  <?php } ?>
  <?php if( count($rsPizarra) > 0 ){ ?>
    <div id="ContenidoPizarra"></div>
  <?php } ?>
  <?php if( count($rsPizarra) == 0 ){ ?>
  <div class="text-center">
    <button type="button" id="btnGenerarPizarra" name="btnGenerarPizarra" class="btn btn-success shadow-sm rounded-pill   btn-sm mifuente  col-lg-4" ><i class="fas fa-save mr-2"></i>Generar Pizarra</button>
  </div>
  <?php } ?>
</form>
<script>
  $('#horarioPizarra').on('change', function () {

    ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/pizarraEnfermeria.php`, $('#frm_generadorPizarra').serialize(), '#contenido', 'Cargando...', true);
  });
  $('#frm_fecha_pizarra').on('change', function () {

    ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/pizarraEnfermeria.php`, $('#frm_generadorPizarra').serialize(), '#contenido', 'Cargando...', true);
  });

  ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/ContenidoPizarra.php`, $('#frm_generadorPizarra').serialize(), '#ContenidoPizarra', 'Cargando...', true);
  // Pizarra mágica urgencia
  $('#btnGenerarPizarra').on('click', function(){
    function _confirmarGuardarDatosTurno () {
      const turno = [];
      $('#cart-list .cart-item').each(function(){
        const d = $(this).data();
        turno.push({
          seccion: String(d.seccion || ''),
          seccion_nombre: String(d.seccion_nombre || ''),
          rol: String(d.rol || ''),
          nombre: String(d.nombre || ''),
          id_usuario: d.id_usuario ? String(d.id_usuario) : null,
          rut: d.rut ? String(d.rut) : null
        });
      });
      $.validity.start();
      if (turno.length === 0) {
        $('#inputNombre').assert(false,'Debe agregar personal');
        $.validity.end();
        return false;
      }
      let respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/turnoCRUrgencia/main_controller.php`, $('#frm_generadorPizarra').serialize()+'&accion=guardarPizarra&turno='+encodeURIComponent(JSON.stringify(turno)), 'POST', 'JSON', 1,'Aplicando Acción a Solicitud Guardar Datos Turno...');
      if ( respuestaAjaxRequest.status === 'error' ) {
        var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error Entrega Turno </h4>  <hr>  <p class="mb-0">Se ha producido el siguiente error:<br><br> '+respuestaAjaxRequest.mensaje+'.</p></div>';
        modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
        return;
      }else{
        // modalFormulario_noCabecera('Documento Entrega Turno', `${raiz}/views/modules/turnoCRUrgencia/pdfTurnoCRUrgencia.php`, `idTurnoCRUrgencia=${respuestaAjaxRequest.idTurnoCRUrgencia}`, "#pdfTurnoCRUrgencia", "modal-lg", "", "fas fa-plus");
        ajaxContent(`${raiz}/views/modules/turnoCRUrgencia/pizarraEnfermeria.php`, 'id_pizarra='+respuestaAjaxRequest.id_pizarra+'&horarioPizarra='+$('#horarioPizarra').val()+'&frm_fecha_pizarra='+$('#frm_fecha_pizarra').val(), '#contenido', 'Cargando...', true);
      }
    }
    modalConfirmacionNuevo("Advertencia","ATENCIÓN, Se procederá a generar la Pizarra. <b>¿Desea continuar?</b>","primary",_confirmarGuardarDatosTurno);
  });
  function setupAutocomplete(selectorInput, tipo) {
    $(selectorInput).autocomplete({
      source: function(request, response) {
        $.ajax({
          type: "POST",
          url: raiz + "/controllers/server/consulta/main_controller.php",
          dataType: "json",
          data: { term: request.term, tipo: tipo, accion: 'busquedaSensitivaUsuarios' },
          success: function(data){ response(data); }
        });
      },
      minLength: 3,
      select: function(event, ui) {
        const $inp = $(this);
        $inp.data("id", ui.item.id);
        $inp.data("rut", ui.item.rut);
        $inp.data("nombre", ui.item.nombre);
        $inp.val(ui.item.nombre);
        return false;
      },
      open: function() {
        $('.ui-menu').addClass("col-md-12 mifuente11");
      }
    });
  }
  (function(){
    const badgeByRole = {
      "EU": "primary",
      "TENS": "info",
      "MEDICO": "success",
      "CIRUJANO": "warning",
      "KINESIOLOGO": "secondary",
      "NEURO CX": "dark",
      "NEURO ACV": "dark"
    };
    let index = 0;
    const reglas = {
      5:  ["EU", "TENS", "KINESIOLOGO"],
      7:  ["KINESIOLOGO"],
      9:  ["TENS"],
      10: ["MEDICO"],
      11: ["MEDICO"],
      12: ["AUXILIAR"],
      13: ["EU"],
      14: ["EU"],
      15: ["TENS"],
      17: ["MEDICO"],
      19: ["CIRUJANO"],
      20: ["MEDICO", "CIRUJANO"],
      21: ["MEDICO"],
      22: ["MEDICO"],
      23: ["MEDICO"],
      24: ["MEDICO"],
      25: ["NEURO CX", "NEURO ACV"],
      26: ["EU"],
    };
    const rolesGenerales = ["EU", "TENS", "MEDICO", "CIRUJANO"];
    const tipoPorRol = {
      "EU": 2,
      "TENS": 17,
      "MEDICO": 1,
      "CIRUJANO": 1,
      "KINESIOLOGO": 4,
      "AUXILIAR": 'A',
      "NEURO CX": 1,
      "NEURO ACV": 1
    };
    function activarAutocompletePorRol(rol){
      const $inp = $("#inputNombre");
      // limpiar datos previos
      $inp.val('').removeData('id').removeData('rut').removeData('nombre');

      // destruye cualquier autocomplete anterior
      if ($inp.data("ui-autocomplete")) {
        $inp.autocomplete("destroy");
      }

      const tipo = tipoPorRol[rol];
      if (tipo) {
        setupAutocomplete('#inputNombre', tipo);
        $inp.attr('placeholder','Escribe 3+ letras y selecciona de la lista');
      } else {
        $inp.attr('placeholder','Nombre y apellido');
      }
    }
    $("#selectSection").on("change", function(){
      const section = $(this).val();
      const selectRol = $("#selectRol");
      selectRol.empty(); // sin opción "Seleccionar rol"

      const roles = reglas[section] || rolesGenerales;

      // Agregar roles al select
      roles.forEach(r => selectRol.append(`<option value="${r}">${r}</option>`));

      // Seleccionar automáticamente el primer rol
      if (roles.length > 0) {
        selectRol.val(roles[0]).trigger('change');
        activarAutocompletePorRol(roles[0]);
      } else {
        activarAutocompletePorRol(null);
      }

      // limpiar input
      $("#inputNombre").val('');
    });
    $("#selectRol").on("change", function(){
      const rol = $(this).val();
      activarAutocompletePorRol(rol);
    });
    $("#btnAgregar").on("click", function(){
      const $selSection = $("#selectSection");
      const sectionId   = $selSection.val();
      const sectionText = $selSection.find("option:selected").text().trim();
      const rol         = $("#selectRol").val();
      const $inp        = $("#inputNombre");
      const nombre      = ($inp.val() || '').trim();

      if(!sectionId){ alert("Seleccione una sección"); return; }
      if(sectionText === "Seleccionar sección"){ alert("Seleccione una sección"); return; }
      if(!rol){ alert("Seleccione un rol"); return; }

      const requiereSensitiva = !!tipoPorRol[rol];
      const idSel  = $inp.data("id");
      const rutSel = $inp.data("rut");
      const nomSel = $inp.data("nombre");

      if (requiereSensitiva) {
        if (!idSel || !rutSel || !nomSel) {
          alert("Por favor, escribe y SELECCIONA un nombre desde la lista para el rol elegido.");
          return;
        }
      } else {
        if (!nombre) { alert("Ingrese un nombre"); return; }
      }

      const badge = badgeByRole[rol] || "secondary";
      const nombreFinal = requiereSensitiva ? nomSel : nombre;

      const $li = $(`
        <li class="list-group-item d-flex justify-content-between align-items-center cart-item pb-1 pt-1 pr-3 pl-3 m-0">
          <div>
            <span class="badge badge-${badge} mr-2 text-uppercase">${rol}</span>
            <strong>[${sectionText}]</strong> ${nombreFinal}
          </div>
          <button type="button" class="btn btn-sm btn-outline-danger remove-item">&times;</button>
        </li>
      `);

      // guarda los datos *en el elemento* (no en inputs)
      $li.data({
        seccion: sectionId,
        seccion_nombre: sectionText,
        rol: rol,
        nombre: nombreFinal,
        id_usuario: requiereSensitiva ? idSel : null,
        rut: requiereSensitiva ? rutSel : null
      });

      $("#cart-list").append($li);

      activarAutocompletePorRol(rol); 
    });
    $(document).on("click", ".remove-item", function(){
      $(this).closest(".cart-item").remove();
    });

    function formatearSeccion(slug){
      return String(slug).replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());
    }
  })();
</script>
