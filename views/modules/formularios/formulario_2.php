<?php
session_start();
error_reporting(0);
require_once("../../../config/config.php");
require_once('../../../class/Util.class.php');               $objUtil                = new Util;
require_once('../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();
require_once("../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../class/Diagnosticos.class.php');       $objDiagnosticos        = new Diagnosticos;
require_once('../../../class/Formulario_2.class.php');       $objFormulario_2        = new Formulario_2;
require_once('../../../class/Formulario_2_Detalle.class.php');       $objFormulario_2_Detalle        = new Formulario_2_Detalle;

$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor                = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['cta_cte']          = $datosU[0]['idctacte'];
$rsRce_diagnostico              = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$rsFormulario2                  = $objFormulario_2->SelectByDauFormulario_2($objCon,$dau_id);
$rsFormulario2Detalle           = $objFormulario_2_Detalle->SelectByFormulario2Id($objCon,$rsFormulario2[0]['id']);
if(count($rsFormulario2) == 0){
  $rsFormulario2[0]['fecha']                    = $horarioServidor[0]['fecha'];
  $rsFormulario2[0]['hora']                     = $horarioServidor[0]['hora'];
  $rsFormulario2[0]['nombre_paciente']          = $datosU[0]['nombres'].' '.$datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
  $rsFormulario2[0]['ficha_numero']             = $dau_id;
}
?><script>
function agregarRegistro() {
  // Obtener valores
  $.validity.start();
    //Sección epidemiológica
  if($("#descripcion_registro").val() == ""){
    $('#descripcion_registro').assert(false,'Debe Ingresar Una opción');
    $.validity.end();
    return false;
  }
  const estado = document.getElementById("descripcion_registro").value;
  const extSuperior = document.getElementById("ext_superior").checked ? "Sí" : "No";
  const extInferior = document.getElementById("ext_inferior").checked ? "Sí" : "No";
  const hidratacion = document.getElementById("hidratacion").checked ? "Sí" : "No";
  const eliminacion = document.getElementById("eliminacion").checked ? "Sí" : "No";

  const fecha = document.querySelector('input[name="fecha"]').value;
  const hora = document.querySelector('input[name="hora"]').value;
  const fechaHora = `${fecha} ${hora}`;

  // Crear nueva fila
  const tabla = document.getElementById("tabla_registros").getElementsByTagName('tbody')[0];
  const fila = tabla.insertRow();
  var  nuevosCampos = [
    fechaHora,
    estado,
    extSuperior,
    extInferior,
    hidratacion,
    eliminacion
  ];
  respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formulario_2").serialize()+'&accion=guardarFormulario2DetalleController&nuevosCampos='+ encodeURIComponent(JSON.stringify(nuevosCampos)), 'POST','JSON', 1, '' );
  switch ( respuestaAjaxRequest.status ) {
  case "success":
    ajaxContent(raiz+'/views/modules/formularios/formulario_2.php','dau_id='+$('#dau_id').val(),'#formContainer','', true);
  }

}
</script>

<script type="text/javascript">
  $(document).ready(function() {
    // Array para almacenar los registros horarios
    var registrosHorarios = [];
    if ($('#chkOtraAlternativa').is(':checked')) {
    $('#divEspecifique').show();
  }

  // Toggle al marcar/desmarcar
  $('#chkOtraAlternativa').on('change', function () {
    if ($(this).is(':checked')) {
      $('#divEspecifique').slideDown();
    } else {
      $('#divEspecifique').slideUp();
    }
  });

    
    // Función para eliminar registro
    window.eliminarRegistro = function(index) {
      registrosHorarios.splice(index, 1);
      actualizarTablaRegistros();
    };
    
    // Función para actualizar la tabla de registros
    function actualizarTablaRegistros() {
      var html = '';
      registrosHorarios.forEach(function(registro, index) {
        html += '<tr class="mifuente11" >';
        html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " >' + registro.descripcion + '</td>';
        html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " >' + registro.hora + '</td>';
        html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " >' + (registro.estado || '') + '</td>';
        html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " >' + (registro.revision || '') + '</td>';
        html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " >' + (registro.necesidades || '') + '</td>';
        // html += '<td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center " ><button type="button" class="btn btn-sm btn-danger" onclick="eliminarRegistro(' + index + ')"><i class="fas fa-trash"></i></button></td>';
        html += '</tr>';
      });
      $('#tabla_registros tbody').html(html);
      $('#registros_json').val(JSON.stringify(registrosHorarios));
    }
    
    // Función para limpiar formulario
    function limpiarFormularioRegistro() {
      $('#descripcion_registro').val('');
      $('#hora_registro').val('');
      $('#estado_paciente').val('');
      $('#revision_sujeciones').val('');
      $('#necesidades_basicas').val('');
    }
    
    $('#guardarFormulario2').on('click', function(){
      var idDau = $('#dau_id').val();
      var registros = [];
      $('#tabla_registros tbody tr').each(function () {
        var columnas = $(this).find('td');

        // Evita filas vacías
        if (columnas.length >= 6) {
          registros.push({
            fechaHora: columnas.eq(0).text().trim(),
            estado: columnas.eq(1).text().trim(),
            extSuperior: columnas.eq(2).text().trim(),
            extInferior: columnas.eq(3).text().trim(),
            hidratacion: columnas.eq(4).text().trim(),
            eliminacion: columnas.eq(5).text().trim()
          });
        }
      });

      // Guardar JSON en el campo oculto
      $('#registros_json').val(JSON.stringify(registros));

      respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formulario_2").serialize()+'&accion=guardarFormulario2Controller', 'POST','JSON', 1, '' );
      switch ( respuestaAjaxRequest.status ) {
      case "success":
        ajaxContent(raiz+'/views/modules/formularios/formulario_2.php','dau_id='+$('#dau_id').val(),'#formContainer','', true);
        modalFormulario("<label class='mifuente ml-2'>Registro Contención Física DAU N°"+idDau+"</label>", `${raiz}/views/modules/formularios/pdfformulario_2.php`, `dau_id=${idDau}`, "#modalHojaEnfermeria", "modal-lg", "light",'', '');
      }
    });
  });
</script>

<style>
  .writing-icon {
    animation: wiggle 1s infinite;
  }
  @keyframes wiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(10deg); }
    75% { transform: rotate(-10deg); }
  }
  .table-formulario { border-collapse: collapse; width: 100%; }
  .table-formulario td, .table-formulario th { border: 1px solid #dee2e6; padding: 4px; }
  .titulo-seccion { background: #f8f9fa; font-weight: bold; }
</style>

<form class="formularios" name="formulario_2" id="formulario_2">
  <input type="hidden" name="id_formulario" id="id_formulario" value="<?=$rsFormulario2[0]['id'];?>" >
  <input type="hidden" name="dau_id" id="dau_id" value="<?=$dau_id;?>" >
  <input type="hidden" name="registros_json" id="registros_json" value="<?=$rsFormulario2[0]['registros_horarios']?>">
  
  <h5 class="mt-2 mifuente14">REGISTRO CONTENCIÓN FÍSICA PARA AGITACIÓN PSICOMOTORA EN URGENCIA
  <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
  
  <div class="row mb-3">
    <div class="col-md-6">
      <label class="mifuente12">NOMBRE</label>
      <input type="text" name="nombre_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario2[0]['nombre_paciente']?>">
    </div>
    <div class="col-md-3">
      <label class="mifuente12">FICHA N°</label>
      <input type="text" name="ficha_numero" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario2[0]['ficha_numero']?>">
    </div>
    <div class="col-md-3">
      <label class="mifuente12">FECHA</label>
      <input type="date" name="fecha" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario2[0]['fecha']?>">
    </div>
  </div>
  
  <div class="row mb-3">
    <div class="col-md-6">
      <label class="mifuente12">HORA</label>
      <input type="time" name="hora" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario2[0]['hora']?>">
    </div>
  </div>
  
  <h6 class="mt-3 mifuente13">MOTIVO DE CONTENCIÓN</h6>
  <div class="row mb-3">
    <div class="col-md-4">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="agitado" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['agitado']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Agitado</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="violento_agresivo" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['violento_agresivo']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Violento/agresivo</label>
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="impulsividad" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['impulsividad']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Impulsividad suicida</label>
      </div>
    </div>
  </div>
  
  <h6 class="mt-3 mifuente13">Medios fracasados antes de la contención:</h6>
  <div class="row mb-3">
    <div class="col-md-3">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="verbal" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['verbal']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Contención Verbal</label>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="ambiental" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['ambiental']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Contención ambiental</label>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" name="farmacologica" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['farmacologica']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12">Contención farmacológica</label>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="chkOtraAlternativa" name="otra_contencion" value="Sí" <?php if(in_array('Sí', explode(',', $rsFormulario2[0]['otra_contencion']))) echo "checked"; ?>>
        <label class="form-check-label mifuente12" for="chkOtraAlternativa">Otra alternativa</label>
      </div>
    </div>
  </div>
  
 <?php $mostrarEspecifique = !empty($rsFormulario2[0]['medios_fracasados_otro']) ? '' : 'display: none;'; ?>
<div class="row mb-3" id="divEspecifique" style="<?= $mostrarEspecifique ?>">
  <div class="col-md-12">
    <label class="mifuente12">Especifique:</label>
    <input type="text" name="medios_fracasados_otro" class="form-control form-control-sm mifuente11"
      value="<?= $rsFormulario2[0]['medios_fracasados_otro'] ?>">
  </div>
</div>
  
  <h6 class="mt-3 mifuente13">Registro Horario - Carrito de Registros</h6>
  
  <!-- Formulario para agregar registros -->
  <div class="card mb-3">
    <div class="card-header">
      <h6 class="mb-0">Agregar Registro</h6>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <label class="mifuente11">Estado del Paciente</label>
          <select id="descripcion_registro" class="form-control form-control-sm mifuente11">
            <option value="">Seleccione...</option>
            <option value="TRANQUILO">TRANQUILO</option>
            <option value="INQUIETO">INQUIETO</option>
            <option value="AGITADO">AGITADO</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="mifuente11">Revisión Sujeciones</label><br>
          <div class="form-check form-check-inline mifuente11">
            <input type="checkbox" id="ext_superior" name="ext_superior" class="form-check-input" value="Sí" 
              <?php if($rsHoja[0]['frm_hta'] == 'Sí'){ echo "checked"; } ?>>
            <label class="form-check-label encabezado" for="frm_hta">&nbsp;EXT.SUPERIOR</label>
          </div>

          <div class="form-check form-check-inline mifuente11">
            <input type="checkbox" id="ext_inferior" name="ext_inferior" class="form-check-input" value="Sí" 
              <?php if($rsHoja[0]['frm_diabetes'] == 'Sí'){ echo "checked"; } ?>>
            <label class="form-check-label encabezado" for="frm_diabetes">&nbsp;EXT.INFERIOR</label>
          </div>
        </div>
        <div class="col-md-3">
          <label class="mifuente11">Necesidades básicas</label><br>
          <div class="form-check form-check-inline mifuente11">
            <input type="checkbox" id="hidratacion" name="hidratacion" class="form-check-input" value="Sí" 
              <?php if($rsHoja[0]['frm_hta'] == 'Sí'){ echo "checked"; } ?>>
            <label class="form-check-label encabezado" for="frm_hta">&nbsp;HIDRATACIÓN Y ALIMENTACIÓN</label>
          </div>

          <div class="form-check form-check-inline mifuente11">
            <input type="checkbox" id="eliminacion" name="eliminacion" class="form-check-input" value="Sí" 
              <?php if($rsHoja[0]['frm_diabetes'] == 'Sí'){ echo "checked"; } ?>>
            <label class="form-check-label encabezado" for="frm_diabetes">&nbsp;ELIMINACIÓN URINARIA EN CAMA</label>
          </div>
        </div>
       <!--   <div class="col-md-3">
          <label class="mifuente11">Fecha y Hora</label><br>
          <div class="form-check form-check-inline mifuente11">
            <input type="date" name="fecha" class="form-control form-control-sm mifuente11 col-lg-7"  value="<?=$horarioServidor[0]['fecha'];?>"> 
            <input type="time" name="hora" class="form-control form-control-sm mifuente11 col-lg-5"  value="<?=$horarioServidor[0]['hora'];?>">
          </div>
        </div> -->
        <div class="col-md-1">
          <label class="mifuente11">&nbsp;</label>
          <button type="button" class="btn btn-sm btn-success form-control" onclick="agregarRegistro()">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Tabla de registros -->
  <div class="table-responsive">
    <table id="tabla_registros" class="table table-bordered table-sm">
      <thead class="table-dark mifuente11">
        <tr>
          <th class=" mifuente11 text-center" >Fecha y Hora</th>
          <th class=" mifuente11 text-center" >Estado</th>
          <th class=" mifuente11 text-center" >Ext.Superior</th>
          <th class=" mifuente11 text-center" >Ext.Inferior</th>
          <th class=" mifuente11 text-center" >Hidratacion y alimentación</th>
          <th class=" mifuente11 text-center" >Eliminacion Urinaria</th>
          <!-- <th class=" mifuente11 text-center" >Acciones</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rsFormulario2Detalle as $detalle): ?>
        <tr>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['fecha']; ?></td>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['estado_paciente']; ?></td>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['extremidad_superior']; ?></td>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['extremidad_inferior']; ?></td>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['hidratacion']; ?></td>
          <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><?= $detalle['eliminacion']; ?></td>
          <!-- <td style="vertical-align: middle;" class="text-nowrap my-1 py-1 mx-1 px-1 mifuente11 text-center "><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">Eliminar</button></td> -->
        </tr>

<?php endforeach; ?>
        <!-- Los registros se cargarán dinámicamente aquí -->
      </tbody>
    </table>
  </div>
  
  <div class="row mb-3">
    <div class="col-md-6">
      <label class="mifuente12">Administración de fármacos:</label>
      <input type="text" name="administracion_farmacos" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario2[0]['administracion_farmacos']?>">
    </div>
    <div class="col-md-6">
      <label class="mifuente12">Hora de retiro de contención:</label>
      <input type="time" name="hora_retiro_contencion" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario2[0]['hora_retiro_contencion']?>">
    </div>
  </div>
  
  <div class="row mb-3">
    <div class="col-md-12">
      <label class="mifuente12">Observaciones:</label>
      <textarea name="observaciones" class="form-control mifuente11" rows="4"><?=$rsFormulario2[0]['observaciones']?></textarea>
    </div>
  </div>
  

  
  <div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
      <button id="guardarFormulario2" type="button" class="btn btn-sm btn-primary col-lg-12">
        <i class="fas fa-check"></i> <i class="glyphicon glyphicon-print"></i> Imprimir
      </button>
    </div>
  </div>
</form> 