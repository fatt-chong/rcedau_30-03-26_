<?php
session_start();
require_once("../../../config/config.php");
require_once('../../../class/Util.class.php');                          $objUtil                = new Util;
require_once('../../../class/Connection.class.php');                    $objCon                 = new Connection; $objCon->db_connect();
require_once('../../../class/Procedimientos_enfermeria.class.php');     $objProc = new Procedimientos_enfermeria;
$listaProcedimientos = $objProc->SelectAll($objCon);
?>
<div class="row mb-2">
    <label class="text-secondary ml-3"><svg class="svg-inline--fa fa-minus fa-w-14 mr-1" style="color: #59a9ff;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="minus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M416 208H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg> Agregar Procedimiento de Enfermería</label>
</div>
<form method="post" action="guardar_procedimiento.php">
  <div class="row">
    <div class="col-lg-2">
      <legend class="mifuente13" for="procedimiento_id" >Procedimiento</legend>
    </div>
    <div class="col-lg-3">
      <legend class="mifuente13" for="procedimiento_id" >Subcategoría</legend>
    </div>
    <div class="col-lg-7">
      <legend class="mifuente13" for="procedimiento_id" >Observación (opcional)</legend>
    </div>
  </div>
   <div class="row">
    <div class="col-lg-2">
    <select id="procedimiento_id" name="procedimiento_id" class="form-select form-control-sm mifuente col-lg-12" required>
      <option value="">Seleccione...</option>
      <?php foreach ($listaProcedimientos as $proc): ?>
        <option value="<?= $proc['id'] ?>"><?= $proc['nombre'] ?></option>
      <?php endforeach; ?>
    </select>
    </div>
    <div class="col-lg-3">
    <select id="subcategoria_id" name="subcategoria_id" class="form-select form-control-sm mifuente col-lg-12" required>
      <option value="">Seleccione un procedimiento primero...</option>
    </select>
    </div>
    <div class="col-lg-5">
    <textarea id="comentario" name="comentario" class="form-control form-control-sm mifuente col-lg-12" rows="1"></textarea>
    </div>
    <div class="col-lg-2">
      <input type="button" class="btn btn-sm mifuente col-lg-12 btn-primary agregarCarrito" value="Agregar Carrito">
    </div>
  </div>

  <input type="hidden" name="dau_id" value="<?= $_POST['dau_id'] ?>">

</form>
<hr>
<div class="mt-3" id="bloqueProcedimientos" style="display: none;">
  <h5 class="mifuente13">Procedimientos Agregados</h5>
  <table class="table table-sm table-bordered table-striped mifuente13" id="tablaProcedimientos">
    <thead class="table-light">
      <tr>
        <th class="text-center">Procedimiento</th>
        <th class="text-center">Subcategoría</th>
        <th class="text-center">Observación</th>
        <th class="text-center">Acción</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
  <br>
<hr>
  <form id="formularioFinalProcedimientosEnfermeria" method="post" action="guardar_carrito_procedimientos.php">
    <input type="hidden" name="dau_id" value="<?= $_POST['dau_id'] ?>">
    <input type="hidden" name="procedimientos_json" id="procedimientos_json">

      <input type="button" class="btn btn-sm mifuente col-lg-3 btn-success guardarProcedimientoEnfermeria" value="Guardar Todo">
  </form>
</div>
<script>
var carritoProcedimientos     = [];
var procedimientoNombrePorId  = {};
<?php foreach ($listaProcedimientos as $proc): ?>
  procedimientoNombrePorId[<?= $proc['id'] ?>] = "<?= addslashes($proc['nombre']) ?>";
<?php endforeach; ?>

$('#procedimiento_id').change(function() {
  var proc_id = $(this).val();
  $('#subcategoria_id').html('<option>Cargando...</option>');
  $.post('./views/modules/enfermera/obtener_subcategorias.php', { procedimiento_id: proc_id }, function(data) {
    $('#subcategoria_id').html(data);
  });
});
$(".agregarCarrito").click(function(e){
  e.preventDefault();
  var procedimiento_id = $('#procedimiento_id').val();
  var subcategoria_id = $('#subcategoria_id').val();
  var subcategoria_nombre = $('#subcategoria_id option:selected').text();
  var comentario = $('#comentario').val();
  if (!procedimiento_id || !subcategoria_id) {
    alert("Debe seleccionar un procedimiento y subcategoría.");
    return;
  }
  var procedimiento_nombre = procedimientoNombrePorId[procedimiento_id];
  var item = {
    procedimiento_id,
    procedimiento_nombre,
    subcategoria_id,
    subcategoria_nombre,
    comentario
  };
  carritoProcedimientos.push(item);
  actualizarTabla();
  limpiarFormulario();
});
function actualizarTabla() {
  var tbody = $('#tablaProcedimientos tbody');
  tbody.empty();
  if (carritoProcedimientos.length === 0) {
    $('#bloqueProcedimientos').hide();
  } else {
    $('#bloqueProcedimientos').show();
    carritoProcedimientos.forEach((item, index) => {
      var fila = `
        <tr>
          <td>${item.procedimiento_nombre}</td>
          <td>${item.subcategoria_nombre}</td>
          <td>${item.comentario}</td>
          <td><button class="btn btn-danger btn-sm" onclick="eliminarProcedimiento(${index})">Eliminar</button></td>
        </tr>`;
      tbody.append(fila);
    });
  }
  $('#procedimientos_json').val(JSON.stringify(carritoProcedimientos));
}
function eliminarProcedimiento(index) {
  carritoProcedimientos.splice(index, 1);
  actualizarTabla();
}
function limpiarFormulario() {
  $('#procedimiento_id').val('');
  $('#subcategoria_id').html('<option value="">Seleccione un procedimiento primero...</option>');
  $('#comentario').val('');
}
// $('.guardarProcedimientoEnfermeria').on('click', function () {
//   if (carritoProcedimientos.length === 0) {
//     alert("Debe agregar al menos un procedimiento.");
//     return;
//   }

  // const idDau = $('#dau_id').val();
  // const fechaHora = new Date().toISOString().slice(0, 19).replace('T', ' '); // yyyy-mm-dd hh:mm:ss
// 
  // Agregar campos comunes
  // const procedimientosParaEnviar = carritoProcedimientos.map(item => {
  //   return {
      // dau_id: idDau,
      // fecha: fechaHora.split(' ')[0],
      // hora: fechaHora.split(' ')[1],
    // };
  // });

//   $.ajax({
//     url: `${raiz}/controllers/server/enfermera/main_controller.php`,
//     method: 'POST',
//     data: {
//       accion: 'IngresarProcedimientoEnfermera',
//       procedimientos: JSON.stringify(procedimientosParaEnviar)
//     },
//     dataType: 'json',
//     success: function (respuesta) {
//       if (respuesta.status === 'success') {
//         alert("Procedimientos guardados correctamente.");
//         carritoProcedimientos = [];
//         actualizarTabla();
//       } else {
//         alert("Error: " + respuesta.message);
//       }
//     },
//     error: function (xhr) {
//       console.error(xhr.responseText);
//       alert("Error al guardar los procedimientos.");
//     }
//   });
// });
$('.guardarProcedimientoEnfermeria').on('click', function(){
    var idDau = $('#dau_id').val(); 
    respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formularioFinalProcedimientosEnfermeria").serialize()+'&accion=IngresarProcedimientoEnfermera', 'POST','JSON', 1, '' );
    switch(respuestaAjaxRequest.status){
        case 'success':
            $('#modalIndicacionesEnfermeria').modal('hide').data('bs.modal', null);

                      var texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-check-double throb2 text-success" style="font-size:29px"></i> Éxito </h4>  <hr>  <p class="mb-0">Las nuevas indicaciones han sido registradas exitosamente.</p></div>';
                      modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
            // ajaxContent(`${raiz}/views/modules/enfermera/despliegueIndicacionesEnfermeria.php`,'dau_id='+$('#dau_id').val(),'#div_indicacion','', true);
        break;
        case 'error' :
            texto = '<div class="alert alert-light" role="alert"><h4 class="alert-heading"><i class="fas fa-exclamation-triangle throb2 text-warning" style="font-size:29px"></i> Error </h4>  <hr>  <p class="mb-0">Error en aplicar categorización al paciente:<br><br>'+respuestaAjaxRequest.message+'.</p></div>';
            modalMensajNoCabecera('',texto,  "#modal", "modal-md", "success");
        break;
    }
  });
</script>