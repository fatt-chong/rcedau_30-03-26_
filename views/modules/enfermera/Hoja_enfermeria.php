<?php
session_start();
error_reporting(0);
require_once("../../../config/config.php");
require_once('../../../class/Util.class.php');               $objUtil                = new Util;
require_once('../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();
require_once("../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../class/HojaEnfermeria.class.php');     $objHoja_enfermeria     = new Hoja_enfermeria;
require_once('../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../class/Bitacora.class.php');           $objBitacora            = new Bitacora;

$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$listaSignos                    = $objRce ->listarSignosVitalesLectura($objCon, $rsRce[0]['id_paciente'], $rsRce[0]['regId']);
$datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor                = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
$rsHoja                         = $objHoja_enfermeria->SelectFormularioEnfermeriaById($objCon, $dau_id);
$rsExamenesRealizados           = $objHoja_enfermeria->SelectExamenesRealizados($objCon, $rsRce[0]['regId']);
$rsTratamientosRealizados       = $objHoja_enfermeria->SelectTratamientosRealizados($objCon, $rsRce[0]['regId']);

$fechaNacimiento  = new DateTime($datosU[0]['fechanac']);
$fechaServidor    = new DateTime($horarioServidor[0]['fecha']);

$diferencia       = $fechaNacimiento->diff($fechaServidor);
$diasNacido       = $diferencia->days;


$rsProcedimientosRealizados     = $objHoja_enfermeria->SelectIndicaciones_enfermeria($objCon, $parametros);
if(count($rsHoja) == 0){
  $rsHoja[0]['fecha_creacion']           = $horarioServidor[0]['fecha'];
  $rsHoja[0]['hora_creacion']            = $horarioServidor[0]['hora'];
  $rsHoja[0]['nombre']          = $datosU[0]['nombres'].' '.$datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
  $rsHoja[0]['edad']            = $datosU[0]['dau_paciente_edad'];
  $rsHoja[0]['prevision']       = $datosDAUPaciente[0]['prevision'];
  $rsHoja[0]['motivo_consulta'] = $rsRce[0]['regMotivoConsulta'];
  if($rsRce[0]['dau_categorizacion'] == "C1" || $rsRce[0]['dau_categorizacion'] == "C2" || $rsRce[0]['dau_categorizacion'] == "ESI-1" || $rsRce[0]['dau_categorizacion'] == "ESI-2"  ){
    $rsHoja[0]['sensorial']     = 1;
    $rsHoja[0]['humedad']       = 1;
    $rsHoja[0]['actividad']     = 1;
    $rsHoja[0]['movilidad']     = 1;
    $rsHoja[0]['nutricion']     = 1;
    $rsHoja[0]['sensorial']     = 1;
    $rsHoja[0]['tipo_riesgo']   = "ALTO RIESGO";
    $rsHoja[0]['puntaje_total'] = 6;
  }

}
if ($rsHoja[0]['puntaje_total'] == ""){
  $rsHoja[0]['puntaje_total']   = 0;
}
?>
<style>
  body {
    background-color: #f8f9fa;
  }
  fieldset {
    border: none;
    padding: 0;
    margin-bottom: 1rem;
  }
  legend {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.25rem;
  }
  .form-check-label{
    margin-top: 2px !important;
  }
</style>
<script>
$(document).ready(function() {
  function toggleCampoInventario() {
    if ($('#frm_valor_recaudacion_si').is(':checked')) {
      $('#campo_num_inventario').show();
    } else {
      $('#campo_num_inventario').hide();
    }
  }

  // Ejecutar al cargar la página
  toggleCampoInventario();

  // Ejecutar al cambiar el radio
  $('input[name="frm_valor_recaudacion"]').change(function() {
    toggleCampoInventario();
  });
  $("#nombre_enfermero").autocomplete({ 
    close: function( event, ui ) {
      if ( fila == "" ) {
        $("#nombre_enfermero").val("");
      }
    },
    source: function(request, response) {      
      $.ajax({
        type: "POST",
        url: raiz+"/controllers/server/consulta/main_controller.php",
        dataType: "json",
        data: {
          term : request.term,
          accion : 'busquedaSensitivaEnfermeros',
        },
        success: function(data) {
          response(data)
        }
      });                
    },
    minLength: 3, 
    select: function(event, ui){
      $('#nombre_enfermero_rut').val( ui.item.id );
      $('#nombre_enfermero').val( ui.item.nombre );
    },
    open: function(){
      $('.ui-menu').addClass("col-md-12");
      $('.ui-menu').addClass("mifuente");
      $('.ui-menu').css( "font-weight", "bold" );
    }
  });
});
  var radios = document.querySelectorAll('.braden');
  var totalSpan = document.getElementById('puntaje_total');
  var tipoRiesgo = document.getElementById('tipo_riesgo');

  radios.forEach(radio => {
    radio.addEventListener('change', () => {
      var grupos = ['sensorial', 'humedad', 'actividad', 'movilidad', 'nutricion', 'lesion', 'movilidad_ped', 'actividad_ped', 'Sensorial_ped','humedad_ped','friccion_ped','nutricion_ped','perfusion_ped','cond_fisica','humedad_neo','estado_mental','movilidad_neo','actividad_neo','nutricion_neo'];
      let total = 0;
      grupos.forEach(nombre => {
        var seleccionado = document.querySelector(`input[name="${nombre}"]:checked`);
        if (seleccionado) total += parseInt(seleccionado.value);
      });
      totalSpan.value = total;

      if (total <= 12) {
        tipoRiesgo.value = "ALTO RIESGO";
      } else if (total >= 13 && total <= 14) {
        tipoRiesgo.value = "RIESGO MODERADO";
      } else if (total >= 15 && total <= 18) {
        tipoRiesgo.value = "BAJO RIESGO";
      } else {
        tipoRiesgo.value = "SIN RIESGO";
      }
    });
  });

  var radiosGlas = document.querySelectorAll('.glasgow');
  var totalInputGlas = document.getElementById('total');

  function calcularTotal() {
    var gruposGlas = ['ojos', 'verbal', 'motora'];
    let totalGlas = 0;
    gruposGlas.forEach(grupo => {
      var seleccionado = document.querySelector(`input[name="${grupo}"]:checked`);
      if (seleccionado) {
        totalGlas += parseInt(seleccionado.value);
      }
    });
    totalInputGlas.value = totalGlas;
  }

  radiosGlas.forEach(radio => {
    radio.addEventListener('change', calcularTotal);
  });
 $('#btnGuardarHojaEnfermeria').on('click', function(){
    var idDau = $('#dau_id').val(); 
    respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formulario_hoja_hospitalizacion").serialize()+'&accion=ingresarHojaHospitalizacion', 'POST','JSON', 1, '' );
    switch ( respuestaAjaxRequest.status ) {
      case "success":
      let imprimir = function(){
                                  $('#pdfHojaHospitalizacionEnfermeria').get(0).contentWindow.focus();
                                  $("#pdfHojaHospitalizacionEnfermeria").get(0).contentWindow.print();
                    }
      let botones =   [
                          { id: 'btnImprimir', value: '<i class="glyphicon glyphicon-print" aria-hidden="true"></i> Imprimir', function: imprimir, class: 'btn btn-primary btnPrint' }
                      ]
      modalFormulario("<label class='mifuente ml-2'>Hoja Hospitalización DAU N°"+idDau+"</label>", `${raiz}/views/modules/enfermera/pdfHojaHospitalizacionEnfermeria.php`, `idDau=${idDau}`, "#modalHojaEnfermeria", "modal-lg", "light",'', botones);
    }
  });
</script>
<style type="text/css">
  .badgeHojaEnfermeria {
    display: inline-block;
    padding: .55em .54em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    /* border-radius: .25rem; */
}
</style>
<div class="ScrollStyleModal">
  <div class="container-fluid">
    <form class="formularios " name="formulario_hoja_hospitalizacion" id="formulario_hoja_hospitalizacion" >
      <input type="hidden" name="dau_id" id="dau_id" value="<?=$dau_id;?>" >
          <fieldset>
            <div class="row ">
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Vacunas Covid</label>
                <input type="text" name="vacuna_covid" class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['vacuna_covid']?>">
              </div>
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Vacunas Influenza</label>
                <input type="text" name="vacuna_influenza" class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['vacuna_influenza']?>">
              </div>
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Contacto Número</label>
                <input type="text" name="contacto_numero"   class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['contacto_numero']?>">
              </div>
              <div class="col-md-4">
                <label class="form-label mifuente13 encabezado">Contacto Nombre</label>
                <input type="text" name="contacto_nombre"   class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['contacto_nombre']?>">
              </div>
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Contacto Parentesco</label>
                <input type="text" name="contacto_parentesco"   class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['contacto_parentesco']?>">
              </div>
            </div>


            <div class="row ">
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Fecha</label>
                <input type="date" name="fecha_creacion" readonly class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['fecha_creacion']?>">
              </div>
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Hora</label>
                <input type="time" name="hora_creacion" readonly class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['hora_creacion']?>">
              </div>
              <div class="col-md-3">
                <label class="form-label mifuente13 encabezado">Nombre</label>
                <input type="text" name="nombre"  readonly class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['nombre']?>">
              </div>
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Edad</label>
                <input type="number" name="edad" readonly class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['edad']?>">
              </div>
              <div class="col-md-3">
                <label class="form-label mifuente13 encabezado">Previsión</label>
                <input type="text" name="prevision" readonly class="form-control form-control-sm mifuente12" value="<?=$rsHoja[0]['prevision']?>">
              </div>
              
              <div class="col-md-2">
                <label class="form-label mifuente13 encabezado">Religión</label>
                <input type="text" name="religion" readonly class="form-control form-control-sm mifuente12" value="<?= isset($rsHoja[0]['religion_descripcion']) ? htmlspecialchars($rsHoja[0]['religion_descripcion']) : ''; ?>">
              </div>
              <div class="col-12">
                <label class="form-label mifuente13 encabezado">Motivo de consulta</label>
                <textarea rows="5" name="motivo_consulta" readonly class="form-control form-control-sm mifuente12" ><?=str_replace('<br>', "\n", $rsHoja[0]['motivo_consulta'])?></textarea>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend class="mifuente13" >Antecedentes Médicos y Quirúrgicos</legend>
            <div class="row mt-1 ">
              <div class="col-lg-2 ">
                <label class="form-label mifuente13 encabezado">Medicos</label>
              </div>
              <div class="col-lg-2  form-check mifuente12">
                  <input type="checkbox" id="frm_hta" name="frm_hta" class=" form-check-input" value="Sí" <?php if($rsHoja[0]['frm_hta'] == 'Sí'){ echo "checked"; } ?> >
                  <label class="encabezado">&nbsp;HTA</label>
              </div>
              <div class="col-lg-2   form-check mifuente12">
                  <input type="checkbox" id="frm_diabetes" name="frm_diabetes" class=" form-check-input" value="Sí" <?php if($rsHoja[0]['frm_diabetes'] == 'Sí'){ echo "checked"; } ?>>
                  <label class="encabezado">&nbsp;Diabetes</label>
              </div>
              <div class="col-lg-6">
                <input type="text" name="otras" class="form-control form-control-sm mifuente12" placeholder="Otras" value="<?=$rsHoja[0]['otras']?>">
              </div>

              <div class="col-lg-12 ">
                <label class="form-label mifuente13 encabezado">Quirúrgicos</label>
                <textarea rows="5" name="frm_quirurgicos" class="form-control form-control-sm mifuente12" placeholder="Quirúrgicos" ><?=$rsHoja[0]['frm_quirurgicos']?></textarea>
              </div>
            </div>
            <div class="row mt-2 ">
              <div class="col-lg-2 ">
                <label class="form-label mifuente13 encabezado">Alergias</label>
              </div>
              <div class="col-lg-2 form-check mifuente12">
                <input type="radio" id="frm_alergia_si" name="frm_alergia" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_alergia'] == 'Sí'){ echo "checked"; } ?> >
                <label for="frm_alergia_si" class="form-check-label">&nbsp;Sí</label>
              </div>
              <div class="col-lg-2 form-check mifuente12">
                <input type="radio" id="frm_alergia_no" name="frm_alergia" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_alergia'] == 'No'){ echo "checked"; } ?> >
                <label for="frm_alergia_no" class="form-check-label">&nbsp;No</label>
              </div>
              <div class="col-lg-6 ">
                <input type="text" name="frm_desconocida" class="form-control form-control-sm mifuente12" placeholder="Desconocida" value="<?=$rsHoja[0]['frm_desconocida']?>">
              </div>
              <div class="col-lg-12 ">
                <label class="form-label mifuente13 encabezado">Medicamentos</label>
                <textarea rows="5" name="frm_medicamentos_medicos" class="form-control form-control-sm mifuente12" placeholder="Medicamentos" ><?=$rsHoja[0]['frm_medicamentos_medicos']?></textarea>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend class="mifuente13" >Anamnesis Enfermería</legend>
            <div class="row mt-1 ">
              <div class="col-lg-12 ">
                <textarea name="frm_evolucion_enfermeria" rows="5" class="form-control form-control-sm mifuente12" placeholder="Anamnesis Enfermería" ><?=$rsHoja[0]['frm_evolucion_enfermeria'];?></textarea>
              </div>
            </div>
          </fieldset>

          <fieldset>
            <legend class="mifuente13" >Examen Físico General</legend>
            <div class="row mt-1 ">
              <div class="col-lg-12 ">
                <textarea name="frm_examen_fisico_general" rows="5" class="form-control form-control-sm mifuente12" placeholder="Examen Físico General" ><?=$rsHoja[0]['frm_examen_fisico_general'];?></textarea>
              </div>
            </div>
          </fieldset>

           <fieldset>
            <legend class="mifuente13" >Valoración de piel y zonas de apoyo</legend>
            <div class="row mt-1 ">
              <div class="col-lg-12 ">
                <textarea  rows="5" name="frm_obs_piel_ubicacion" class="form-control form-control-sm mifuente12" placeholder="Valoración de piel y zonas de apoyo"><?=$rsHoja[0]['frm_obs_piel_ubicacion']?></textarea>
              </div>
            </div>
          </fieldset>
          <hr>
          <?php if ($datosU[0]['dau_paciente_edad'] > 14 ){  ?>

          <input type="hidden" name="tipobraden" id="tipobraden" value="A">
          <fieldset style="margin-bottom: 0px;">
            <h4 class="mb-3 text-center mifuente13">Escala de Braden para Paciente Adulto</h4>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="mifuente13 text-center">Puntaje</th>
                  <th class="mifuente13 text-center">Percepción Sensorial</th>
                  <th class="mifuente13 text-center">Exposición a la Humedad</th>
                  <th class="mifuente13 text-center">Actividad</th>
                  <th class="mifuente13 text-center">Movilidad</th>
                  <th class="mifuente13 text-center">Nutrición</th>
                  <th class="mifuente13 text-center">Riesgo de Lesiones Cutáneas</th>
                </tr>
              </thead>
              <tbody class="mifuente11 text-center">
                <tr >
                  <td>1</td>
                  <td><input type="radio" class="braden" name="sensorial" <?php if($rsHoja[0]['sensorial'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente limitada</td>
                  <td><input type="radio" class="braden" name="humedad"   <?php if($rsHoja[0]['humedad'] == '1'){ echo "checked"; } ?>    value="1"><br> Constantemente húmeda</td>
                  <td><input type="radio" class="braden" name="actividad" <?php if($rsHoja[0]['actividad'] == '1'){ echo "checked"; } ?>  value="1"><br> Encamado</td>
                  <td><input type="radio" class="braden" name="movilidad" <?php if($rsHoja[0]['movilidad'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente inmóvil</td>
                  <td><input type="radio" class="braden" name="nutricion" <?php if($rsHoja[0]['nutricion'] == '1'){ echo "checked"; } ?>  value="1"><br> Muy pobre</td>
                  <td><input type="radio" class="braden" name="lesion"    <?php if($rsHoja[0]['sensorial'] == '1'){ echo "checked"; } ?>  value="1"><br> Problema</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td><input type="radio" class="braden" name="sensorial" <?php if($rsHoja[0]['sensorial'] == '2'){ echo "checked"; } ?>  value="2"> <br> Muy limitada</td>
                  <td><input type="radio" class="braden" name="humedad"   <?php if($rsHoja[0]['humedad'] == '2'){ echo "checked"; } ?>    value="2"> <br> Húmeda con frecuencia</td>
                  <td><input type="radio" class="braden" name="actividad" <?php if($rsHoja[0]['actividad'] == '2'){ echo "checked"; } ?> value="2"> <br> En silla</td>
                  <td><input type="radio" class="braden" name="movilidad" <?php if($rsHoja[0]['movilidad'] == '2'){ echo "checked"; } ?>  value="2"> <br> Muy limitada</td>
                  <td><input type="radio" class="braden" name="nutricion" <?php if($rsHoja[0]['nutricion'] == '2'){ echo "checked"; } ?>  value="2"> <br> Probablemente inadecuada</td>
                  <td><input type="radio" class="braden" name="lesion"    <?php if($rsHoja[0]['lesion'] == '2'){ echo "checked"; } ?>    value="2"> <br> Problema potencial</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td><input type="radio" class="braden" name="sensorial" <?php if($rsHoja[0]['sensorial'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                  <td><input type="radio" class="braden" name="humedad"   <?php if($rsHoja[0]['humedad'] == '3'){ echo "checked"; } ?>    value="3"> <br> Ocasionalmente húmeda</td>
                  <td><input type="radio" class="braden" name="actividad" <?php if($rsHoja[0]['actividad'] == '3'){ echo "checked"; } ?> value="3"> <br> Deambula ocasionalmente</td>
                  <td><input type="radio" class="braden" name="movilidad" <?php if($rsHoja[0]['movilidad'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                  <td><input type="radio" class="braden" name="nutricion" <?php if($rsHoja[0]['nutricion'] == '3'){ echo "checked"; } ?>  value="3"> <br> Adecuada</td>
                  <td><input type="radio" class="braden" name="lesion"    <?php if($rsHoja[0]['lesion'] == '3'){ echo "checked"; } ?>    value="3"> <br> No existe problema aparente</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td><input type="radio" class="braden" name="sensorial" <?php if($rsHoja[0]['sensorial'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitaciones</td>
                  <td><input type="radio" class="braden" name="humedad"   <?php if($rsHoja[0]['humedad'] == '4'){ echo "checked"; } ?>    value="4"> <br> Raramente húmeda</td>
                  <td><input type="radio" class="braden" name="actividad" <?php if($rsHoja[0]['actividad'] == '4'){ echo "checked"; } ?> value="4"> <br> Deambula frecuentemente</td>
                  <td><input type="radio" class="braden" name="movilidad" <?php if($rsHoja[0]['movilidad'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitación</td>
                  <td><input type="radio" class="braden" name="nutricion" <?php if($rsHoja[0]['nutricion'] == '4'){ echo "checked"; } ?>  value="4"> <br> Excelente</td>
                </tr>
              </tbody>
            </table>
            <div class="row  "> 
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-4">
                <div class=" mifuente12">
                  <strong class="mifuente13">Valoración del riesgo de lesión por presión:</strong>
                </div>
              </div>
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Tipo de riesgo: <input type="text" name="tipo_riesgo" id="tipo_riesgo" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['tipo_riesgo']?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Puntaje: <input type="text" name="puntaje_total" id="puntaje_total" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['puntaje_total']?>"> 
                </div>
              </div>
            </div>
          </fieldset>
          <?php } else if ( $diasNacido < 30){ ?>
          <input type="hidden" name="tipobraden" id="tipobraden" value="N">
          <fieldset style="margin-bottom: 0px;">
            <h4 class="mb-3 text-center mifuente13">Escala de Braden para Paciente Neonatal NSRAS</h4>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="mifuente13 text-center">Puntaje</th>
                  <th class="mifuente13 text-center">Cond. Física general</th>
                  <th class="mifuente13 text-center">Estado Mental</th>
                  <th class="mifuente13 text-center">Movilidad</th>
                  <th class="mifuente13 text-center">Actividad</th>
                  <th class="mifuente13 text-center">Nutrición</th>
                  <th class="mifuente13 text-center">Humedad</th>
                </tr>
              </thead>
              <tbody class="mifuente11 text-center">
                <tr >
                  <td>1</td>
                  <td><input type="radio" class="braden" name="cond_fisica" <?php if($rsHoja[0]['cond_fisica'] == '1'){ echo "checked"; } ?>  value="1"><br> Muy Pobre</td>
                  <td><input type="radio" class="braden" name="estado_mental" <?php if($rsHoja[0]['estado_mental'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente limitado</td>
                  <td><input type="radio" class="braden" name="movilidad_neo" <?php if($rsHoja[0]['movilidad_neo'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente inmóvil</td>
                  <td><input type="radio" class="braden" name="actividad_neo" <?php if($rsHoja[0]['actividad_neo'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente encamado/a</td>
                  <td><input type="radio" class="braden" name="nutricion_neo" <?php if($rsHoja[0]['nutricion_neo'] == '1'){ echo "checked"; } ?>  value="1"><br> Muy deficiente</td>
                  <td><input type="radio" class="braden" name="humedad_neo" <?php if($rsHoja[0]['humedad_neo'] == '1'){ echo "checked"; } ?>  value="1"><br> Piel constantemente húmeda</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td><input type="radio" class="braden" name="cond_fisica" <?php if($rsHoja[0]['cond_fisica'] == '2'){ echo "checked"; } ?>  value="2"> <br>Edad Gestacional > 28 semanas y < 33 semanas</td>
                  <td><input type="radio" class="braden" name="estado_mental" <?php if($rsHoja[0]['estado_mental'] == '2'){ echo "checked"; } ?> value="2"> <br> Muy limitado</td>
                  <td><input type="radio" class="braden" name="movilidad_neo" <?php if($rsHoja[0]['movilidad_neo'] == '2'){ echo "checked"; } ?>  value="2"> <br> Muy limitado</td>
                  <td><input type="radio" class="braden" name="actividad_neo" <?php if($rsHoja[0]['actividad_neo'] == '2'){ echo "checked"; } ?>  value="2"> <br> Encamado/a</td>
                  <td><input type="radio" class="braden" name="nutricion_neo" <?php if($rsHoja[0]['nutricion_neo'] == '2'){ echo "checked"; } ?>  value="2"> <br> Inadecuada</td>
                  <td><input type="radio" class="braden" name="humedad_neo" <?php if($rsHoja[0]['humedad_neo'] == '2'){ echo "checked"; } ?>  value="2"> <br> Puel húmeda</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td><input type="radio" class="braden" name="cond_fisica" <?php if($rsHoja[0]['cond_fisica'] == '3'){ echo "checked"; } ?>  value="3"> <br> Edad Gestacional > 33 semanas y < 38 semanas</td>
                  <td><input type="radio" class="braden" name="estado_mental" <?php if($rsHoja[0]['estado_mental'] == '3'){ echo "checked"; } ?> value="3"> <br> Ligeramente limitado</td>
                  <td><input type="radio" class="braden" name="movilidad_neo" <?php if($rsHoja[0]['movilidad_neo'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                  <td><input type="radio" class="braden" name="actividad_neo" <?php if($rsHoja[0]['actividad_neo'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                  <td><input type="radio" class="braden" name="nutricion_neo" <?php if($rsHoja[0]['nutricion_neo'] == '3'){ echo "checked"; } ?>  value="3"> <br> Adecuada</td>
                  <td><input type="radio" class="braden" name="humedad_neo" <?php if($rsHoja[0]['humedad_neo'] == '3'){ echo "checked"; } ?>  value="3"> <br> Piel ocasionalmente húmeda</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td><input type="radio" class="braden" name="cond_fisica" <?php if($rsHoja[0]['cond_fisica'] == '4'){ echo "checked"; } ?>  value="4"> <br> Edad Gestacional > 38 semanas </td>
                  <td><input type="radio" class="braden" name="estado_mental" <?php if($rsHoja[0]['estado_mental'] == '4'){ echo "checked"; } ?> value="4"> <br> Sin limitaciones</td>
                  <td><input type="radio" class="braden" name="movilidad_neo" <?php if($rsHoja[0]['movilidad_neo'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitación</td>
                  <td><input type="radio" class="braden" name="actividad_neo" <?php if($rsHoja[0]['actividad_neo'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitación</td>
                  <td><input type="radio" class="braden" name="nutricion_neo" <?php if($rsHoja[0]['nutricion_neo'] == '4'){ echo "checked"; } ?>  value="4"> <br> Excelente</td>
                  <td><input type="radio" class="braden" name="humedad_neo" <?php if($rsHoja[0]['humedad_neo'] == '4'){ echo "checked"; } ?>  value="4"> <br> Piel rara vez húmeda</td>
                </tr>
              </tbody>
            </table>
            <div class="row  "> 
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-4">
                <div class=" mifuente12">
                  <strong class="mifuente13">Valoración del riesgo de lesión por presión:</strong>
                </div>
              </div>
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Tipo de riesgo: <input type="text" name="tipo_riesgo" id="tipo_riesgo" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['tipo_riesgo']?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Puntaje: <input type="text" name="puntaje_total" id="puntaje_total" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['puntaje_total']?>"> 
                </div>
              </div>
            </div>
          </fieldset>
          <?php }  else if ( $datosU[0]['dau_paciente_edad'] <= 14){ ?>

          <input type="hidden" name="tipobraden" id="tipobraden" value="P">
          <fieldset style="margin-bottom: 0px;">
            <h4 class="mb-3 text-center mifuente13">Escala de Braden para Paciente Pediátricos<br>Intensidad y duración de la presión</h4>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="mifuente13 text-center">Puntaje</th>
                  <th class="mifuente13 text-center">Movilidad</th>
                  <th class="mifuente13 text-center">Actividad</th>
                  <th class="mifuente13 text-center">Percepción Sensorial</th>
                </tr>
              </thead>
              <tbody class="mifuente11 text-center">
                <tr >
                  <td>1</td>
                  <td><input type="radio" class="braden" name="movilidad_ped" <?php if($rsHoja[0]['movilidad_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente Inmóvil</td>
                  <td><input type="radio" class="braden" name="actividad_ped" <?php if($rsHoja[0]['actividad_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Encamado</td>
                  <td><input type="radio" class="braden" name="Sensorial_ped" <?php if($rsHoja[0]['Sensorial_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Completamente limitada</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td><input type="radio" class="braden" name="movilidad_ped" <?php if($rsHoja[0]['movilidad_ped'] == '2'){ echo "checked"; } ?>  value="2"> <br> Muy limitada</td>
                  <td><input type="radio" class="braden" name="actividad_ped" <?php if($rsHoja[0]['actividad_ped'] == '2'){ echo "checked"; } ?> value="2"> <br> En silla</td>
                  <td><input type="radio" class="braden" name="Sensorial_ped" <?php if($rsHoja[0]['Sensorial_ped'] == '2'){ echo "checked"; } ?>  value="2"> <br> Muy limitada</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td><input type="radio" class="braden" name="movilidad_ped" <?php if($rsHoja[0]['movilidad_ped'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                  <td><input type="radio" class="braden" name="actividad_ped" <?php if($rsHoja[0]['actividad_ped'] == '3'){ echo "checked"; } ?> value="3"> <br> Camina ocasionalmente</td>
                  <td><input type="radio" class="braden" name="Sensorial_ped" <?php if($rsHoja[0]['Sensorial_ped'] == '3'){ echo "checked"; } ?>  value="3"> <br> Ligeramente limitada</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td><input type="radio" class="braden" name="movilidad_ped" <?php if($rsHoja[0]['movilidad_ped'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitaciones</td>
                  <td><input type="radio" class="braden" name="actividad_ped" <?php if($rsHoja[0]['actividad_ped'] == '4'){ echo "checked"; } ?> value="4"> <br> Camina frecuentemente</td>
                  <td><input type="radio" class="braden" name="Sensorial_ped" <?php if($rsHoja[0]['Sensorial_ped'] == '4'){ echo "checked"; } ?>  value="4"> <br> Sin limitación</td>
                </tr>
              </tbody>
            </table>

            <h4 class="mb-3 text-center mifuente13">Tolerancia de la piel y estructura de soporte</h4>
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="mifuente13 text-center">Puntaje</th>
                  <th class="mifuente13 text-center">Humedad</th>
                  <th class="mifuente13 text-center">Fricción</th>
                  <th class="mifuente13 text-center">Nutrición</th>
                  <th class="mifuente13 text-center">Perfusión Tisular y Oxigenación</th>
                </tr>
              </thead>
              <tbody class="mifuente11 text-center">
                <tr >
                  <td>1</td>
                  <td><input type="radio" class="braden" name="humedad_ped" <?php if($rsHoja[0]['humedad_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Piel constantemente húmeda</td>
                  <td><input type="radio" class="braden" name="friccion_ped" <?php if($rsHoja[0]['friccion_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Problema significativo</td>
                  <td><input type="radio" class="braden" name="nutricion_ped" <?php if($rsHoja[0]['nutricion_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Muy pobre</td>
                  <td><input type="radio" class="braden" name="perfusion_ped" <?php if($rsHoja[0]['perfusion_ped'] == '1'){ echo "checked"; } ?>  value="1"><br> Muy comprometida</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td><input type="radio" class="braden" name="humedad_ped" <?php if($rsHoja[0]['humedad_ped'] == '2'){ echo "checked"; } ?>  value="2"> <br> Piel muy húmeda</td>
                  <td><input type="radio" class="braden" name="friccion_ped" <?php if($rsHoja[0]['friccion_ped'] == '2'){ echo "checked"; } ?> value="2"> <br> Problema</td>
                  <td><input type="radio" class="braden" name="nutricion_ped" <?php if($rsHoja[0]['nutricion_ped'] == '2'){ echo "checked"; } ?>  value="2"> <br> Inadecuada</td>
                  <td><input type="radio" class="braden" name="perfusion_ped" <?php if($rsHoja[0]['perfusion_ped'] == '2'){ echo "checked"; } ?>  value="2"> <br> Comprometida</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td><input type="radio" class="braden" name="humedad_ped" <?php if($rsHoja[0]['humedad_ped'] == '3'){ echo "checked"; } ?>  value="3"> <br> Piel ocasionalmente húmeda</td>
                  <td><input type="radio" class="braden" name="friccion_ped" <?php if($rsHoja[0]['friccion_ped'] == '3'){ echo "checked"; } ?> value="3"> <br> Problema potencial</td>
                  <td><input type="radio" class="braden" name="nutricion_ped" <?php if($rsHoja[0]['nutricion_ped'] == '3'){ echo "checked"; } ?>  value="3"> <br> Adecuada</td>
                  <td><input type="radio" class="braden" name="perfusion_ped" <?php if($rsHoja[0]['perfusion_ped'] == '3'){ echo "checked"; } ?>  value="3"> <br> Adecuada</td>
                </tr>
                <tr>
                  <td>4</td>
                  <td><input type="radio" class="braden" name="humedad_ped" <?php if($rsHoja[0]['humedad_ped'] == '4'){ echo "checked"; } ?>  value="4"> <br> Piel raramente húmeda</td>
                  <td><input type="radio" class="braden" name="friccion_ped" <?php if($rsHoja[0]['friccion_ped'] == '4'){ echo "checked"; } ?> value="4"> <br> Sin problema aparente</td>
                  <td><input type="radio" class="braden" name="nutricion_ped" <?php if($rsHoja[0]['nutricion_ped'] == '4'){ echo "checked"; } ?>  value="4"> <br> Excelente</td>
                  <td><input type="radio" class="braden" name="perfusion_ped" <?php if($rsHoja[0]['perfusion_ped'] == '4'){ echo "checked"; } ?>  value="4"> <br> Excelente</td>
                </tr>
              </tbody>
            </table>
            <div class="row  "> 
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-4">
                <div class=" mifuente12">
                  <strong class="mifuente13">Valoración del riesgo de lesión por presión:</strong>
                </div>
              </div>
              <div class="col-md-8">
                &nbsp;
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Tipo de riesgo: <input type="text" name="tipo_riesgo" id="tipo_riesgo" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['tipo_riesgo']?>">
                </div>
              </div>
              <div class="col-md-2">
                <div class=" mifuente12">
                  Puntaje: <input type="text" name="puntaje_total" id="puntaje_total" readonly class="form-control form-control-sm mifuente mb-2" value="<?=$rsHoja[0]['puntaje_total']?>"> 
                </div>
              </div>
            </div>
          </fieldset>
          <?php } ?>
          <hr>
          <fieldset style="margin-bottom: 0px;">
            <div class="card" style="-webkit-box-shadow:none;">
              <div class="card-header m-0 p-0" style="background-color: #ffffff !important;" id="headingOne">
                <h2 class="mb-0">
                  <button class="btn btn-link mifuente12 btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-heartbeat throb mr-2 mifuente18 text-primary" ></i>&nbsp;Signos Vitales
                  </button>
                </h2>
              </div>
              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body m-0 p-1">
                  <table id="lista_signos" class="table table-sm table-borderless  mifuente12" >
                    <thead>
                      <tr class="text-center border-bottom">
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold">Usuario y fecha</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >PAS / PAD</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >FC</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >PAM</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >SAT</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >FIO2</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >FR</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >HGT</th>
                        <?php if($rsRce[0]['dau_atencion'] == 3){ ?>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >LCF</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >RBNE</th>
                        <?php } ?>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >GCS</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >T°</th>
                        <th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >EVA</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $contadorListaSignos = count($listaSignos);
                      for($i=0;$i< 3;$i++){ 
                        if ( $listaSignos[$i]['SVITALusuario'] != "" ){ ?>
                      <tr id="signos" class="text-center border-bottom">
                         <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALusuario']. " <br>".date("d-m-Y", strtotime($listaSignos[$i]['SVITALfecha'])); ?> - <?= date("H:i", strtotime($listaSignos[$i]['SVITALfecha'])); ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALsistolica']; ?> / <?= $listaSignos[$i]['SVITALdiastolica']; ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= intval($listaSignos[$i]['SVITALpulso']); ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= intval($listaSignos[$i]['SVITALPAM']); ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALsaturacion']; ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['FIO2']; ?>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALfr']; ?>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALHemoglucoTest']; ?></td>
                        <?php if($rsRce[0]['dau_atencion'] == 3){ ?>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALfeto']; ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALrbne']; ?></td>
                        <?php } ?>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALglasgow']; ?></td>
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALtemperatura']; ?></td> 
                        <td style="vertical-align: middle;" class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALeva']; ?></td> 
                      </tr>
                      <?php
                        } 
                      }?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </fieldset>
        <fieldset>
         <div class="row mt-2 ">
            <div class="col-lg-2 ">
              <label class="form-label mifuente13 "><i class="fas fa-circle-notch text-danger mr-2"></i>Contención Física</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_contencion_fisica_si" name="frm_contencion_fisica" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_contencion_fisica'] == 'Sí'){ echo "checked"; } ?> >
              <label for="frm_contencion_fisica_si" class="form-check-label">&nbsp;Sí</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_contencion_fisica_no" name="frm_contencion_fisica" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_contencion_fisica'] == 'No'){ echo "checked"; } ?> >
              <label for="frm_contencion_fisica_no" class="form-check-label">&nbsp;No</label>
            </div>
            <div class="col-lg-2 form-check mifuente12">
              <input type="checkbox" id="frm_ext_superiores" name="frm_ext_superiores" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_ext_superiores'] == 'Sí'){ echo "checked"; } ?> >
              <label for="frm_ext_superiores" class="form-check-label">&nbsp;Ext. Superiores</label>
            </div>
            <div class="col-lg-2 form-check mifuente12">
              <input type="checkbox" id="frm_ext_inferiores" name="frm_ext_inferiores" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_ext_inferiores'] == 'Sí'){ echo "checked"; } ?> >
              <label for="frm_ext_inferiores" class="form-check-label">&nbsp;Ext. Inferiores</label>
            </div>
          </div>


          <div class="row mt-1 ">
            <div class="col-lg-2 ">
              <label class="form-label mifuente13 encabezado"  >Hoja de Contención</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_hoja_contencion_si" name="frm_hoja_contencion" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_hoja_contencion'] == 'Sí'){ echo "checked"; } ?>>
              <label for="frm_hoja_contencion_si" class="form-check-label">&nbsp;Sí</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_hoja_contencion_no" name="frm_hoja_contencion" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_hoja_contencion'] == 'No'){ echo "checked"; } ?>>
              <label for="frm_hoja_contencion_no" class="form-check-label">&nbsp;No</label>
            </div>
            <div class="col-12 mt-2">
              <textarea name="obs_hoja_contencion" rows="5" id="obs_hoja_contencion" class="form-control form-control-sm mifuente12"><?=$rsHoja[0]['obs_hoja_contencion'];?></textarea>
            </div>
          </div>
          <div class="row mt-2 ">
            <div class="col-lg-12 ">
              <label class="form-label mifuente13 "><i class="fas fa-circle-notch text-danger mr-2"></i> Examenes Realizados e Interconsultas</label>
            </div>
            <div class="col-12 mt-1">
              <table class="table table-hover align-middle text-center shadow-sm border rounded mifuente11">
                <thead class="table-light">
                  <tr class="align-middle">
                    <th><i class="bi bi-calendar3"></i>Fecha</th>
                    <th><i class="bi bi-person-circle"></i>Registrado por</th>
                    <th><i class="bi bi-diagram-3"></i>Tipo</th>
                    <th><i class="bi bi-card-text"></i>Detalle</th>
                    <th><i class="bi bi-check2-circle"></i>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rsExamenesRealizados as $examen) { ?>
                    <tr>
                      <td style="vertical-align: middle;" class="text-nowrap"><?= date('d-m-Y H:i', strtotime($examen['fechaInserta'])) ?></td>
                      <td style="vertical-align: middle;" ><?= htmlspecialchars($examen['nombreUsuario']) ?></td>
                      <td style="vertical-align: middle;"  class="mifuente13">
                        <?php if ($examen['servicio'] == '1') {   $bg_class = "bg-primary"; }
                        else  if ($examen['servicio'] == '3') {   $bg_class = "bg-success"; }
                        else  if ($examen['servicio'] == '5') {   $bg_class = "bg-info";    }  ?>
                          <span class="badgeHojaEnfermeria <?=$bg_class;?> text-light"><i class="bi bi-image"></i> <?= htmlspecialchars($examen['descripcion']) ?></span>
                      </td>
                      <td style="vertical-align: middle;"  class="text-start "><?= htmlspecialchars($examen['Prestacion']) ?>
                        <?php 
                    if($examen['fechaTomaMuestra'] != null ){
                      $parametrosEnfermeria['rce_sol_id'] = $examen['sol_id'];
                      $parametrosEnfermeria['dau_id']     = $parametros['dau_id'];
                      $rsDauMovimientoEnfermeria = $objBitacora->SelectDauMovimientoEnfermeria($objCon, $parametrosEnfermeria);
                      // print('<pre>'); print_r($rsDauMovimientoEnfermeria); print('</pre>');
                      if(count ($rsDauMovimientoEnfermeria) > 0){ ?>
                        <br>
                            <?php foreach ($rsDauMovimientoEnfermeria as $movimiento) { ?>
                              <strong class="">Observación Enfermeria (<?= htmlspecialchars($movimiento['estado_solicitud']) ?>) :</strong> <?= htmlspecialchars($movimiento['observacion']) ?><br>
                            <?php } ?>

                     <?php 
                      }
                    } ?>
                      </td>
                      <td style="vertical-align: middle;" class="mifuente13" >
                        <?php if ($examen['estado'] == '1') { $bg_classEstado = "bg-secondary"; }
                        else  if ($examen['estado'] == '6') { $bg_classEstado = "bg-danger";    }
                        else  if ($examen['estado'] == '4') { $bg_classEstado = "bg-success";    }  ?>

                        <span class="badgeHojaEnfermeria <?=$bg_classEstado;?> text-light"><?= htmlspecialchars($examen['estadoDescripcion']) ?></span>
                      </td>
                    </tr>
                    <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row mt-2 ">
            <div class="col-lg-12 ">
              <label class="form-label mifuente13 "><i class="fas fa-circle-notch text-danger mr-2"></i> Observaciones Y/O Tratamientos Efectuados en Box</label>
            </div>
            <div class="col-12 mt-1">
              <table class="table table-hover align-middle text-center shadow-sm border rounded mifuente11">
                <thead class="table-light">
                  <tr class="align-middle">
                    <th><i class="bi bi-calendar3"></i>Fecha</th>
                    <th><i class="bi bi-person-circle"></i>Registrado por</th>
                    <th><i class="bi bi-diagram-3"></i>Tipo</th>
                    <th><i class="bi bi-card-text"></i>Detalle</th>
                    <th><i class="bi bi-check2-circle"></i>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rsTratamientosRealizados as $Tratamientos) { ?>
                    <tr>
                      <td style="vertical-align: middle;" class="text-nowrap"><?= date('d-m-Y H:i', strtotime($Tratamientos['fechaInserta'])) ?></td>
                      <td style="vertical-align: middle;" ><?= htmlspecialchars($Tratamientos['nombreUsuario']) ?></td>
                      <td style="vertical-align: middle;" class="mifuente13">
                        <?php if ($Tratamientos['tipo_solicitud_cabecera'] == '2') {   $bg_class = "bg-primary"; }
                        else  if ($Tratamientos['tipo_solicitud_cabecera'] == '4') {   $bg_class = "bg-success"; }
                        else  if ($Tratamientos['tipo_solicitud_cabecera'] == '6') {   $bg_class = "bg-info";    } 
                        else  if ($Tratamientos['tipo_solicitud_cabecera'] == '8') {   $bg_class = "bg-danger";    }  ?>
                          <span class="badgeHojaEnfermeria <?=$bg_class;?> text-light"><i class="bi bi-image"></i>SOLICITUD <?= htmlspecialchars($Tratamientos['descripcion']) ?></span>
                      </td>
                      <td style="vertical-align: middle;" class="text-start"><?= ($Tratamientos['Prestacion']) ?>
                        <?php 
                    if($Tratamientos['fechaIniciaIndicacion'] != null ){
                      $parametrosEnfermeria['rce_sol_id'] = $Tratamientos['sol_id'];
                      $parametrosEnfermeria['dau_id']     = $parametros['dau_id'];
                      $rsDauMovimientoEnfermeria = $objBitacora->SelectDauMovimientoEnfermeria($objCon, $parametrosEnfermeria);
                      // print('<pre>'); print_r($rsDauMovimientoEnfermeria); print('</pre>');
                      if(count ($rsDauMovimientoEnfermeria) > 0){ ?>
                        <br>
                            <?php foreach ($rsDauMovimientoEnfermeria as $movimiento) { ?>
                              <strong class="" >Observación Enfermeria (<?= htmlspecialchars($movimiento['estado_solicitud']) ?>) :</strong> <?= htmlspecialchars($movimiento['observacion']) ?><br>
                            <?php } ?>

                     <?php 
                      }
                    } ?>
                      </td>
                      <td style="vertical-align: middle;" class="mifuente13" >
                        <?php if ($Tratamientos['estado'] == '1') { $bg_classEstado = "bg-secondary"; }
                        else  if ($Tratamientos['estado'] == '6') { $bg_classEstado = "bg-danger";    }
                        else  if ($Tratamientos['estado'] == '4') { $bg_classEstado = "bg-success";    }  ?>

                        <span class="badgeHojaEnfermeria <?=$bg_classEstado;?> text-light"><?= htmlspecialchars($Tratamientos['estadoDescripcion']) ?></span>
                      </td>
                    </tr>
                    <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row mt-2 ">
            <div class="col-lg-12 ">
              <label class="form-label mifuente13 "><i class="fas fa-circle-notch text-danger mr-2"></i>Elementos Invasivos</label>
            </div>
            <div class="col-12 mt-1">
              <table class="table table-hover align-middle text-center shadow-sm border rounded mifuente11">
                <thead class="table-light">
                  <tr class="align-middle">
                    <th><i class="bi bi-calendar3"></i>Fecha</th>
                    <th><i class="bi bi-person-circle"></i>Registrado por</th>
                    <th><i class="bi bi-diagram-3"></i>Tipo</th>
                    <th><i class="bi bi-card-text"></i>Detalle</th>
                    <!-- <th><i class="bi bi-check2-circle"></i>Estado</th> -->
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rsProcedimientosRealizados as $Procedimientos) { ?>
                    <tr>
                      <td style="vertical-align: middle;" class="text-nowrap"><?= date('d-m-Y', strtotime($Procedimientos['fecha']))." ".$Procedimientos['hora'] ?></td>
                      <td style="vertical-align: middle;"><?= htmlspecialchars($Procedimientos['nombreUsuario']) ?></td>
                      <td style="vertical-align: middle;" class="mifuente13">
                        <?php  $bg_class = "bg-dark";    ?>
                          <span class="badgeHojaEnfermeria <?=$bg_class;?> text-light"><i class="bi bi-image"></i><?= htmlspecialchars($Procedimientos['nombre_procedimiento']) ?></span>
                      </td>
                      <td style="vertical-align: middle;" class="text-start"><?= htmlspecialchars($Procedimientos['nombre_subProcedimiento']) ?>
                      <?php if($Procedimientos['comentario'] != ""){ echo "<br> <label class='mifuente10' style='margin-bottom:0px !important;'><b>Observación:</b> ".$Procedimientos['comentario']."</label>";} ?>
                        
                      </td>
                    <!--   <td class="mifuente13" >
                        <?php if ($Procedimientos['estado'] == '1') { $bg_classEstado = "bg-secondary"; }
                        else  if ($Procedimientos['estado'] == '2') { $bg_classEstado = "bg-danger";    }
                        else  if ($Procedimientos['estado'] == '3') { $bg_classEstado = "bg-success";    }  ?>

                        <span class="badgeHojaEnfermeria <?=$bg_classEstado;?> text-light"><?= htmlspecialchars($Procedimientos['estadoDescripcion']) ?></span>
                      </td> -->
                    </tr>
                    <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row mt-2 ">
            <div class="col-lg-12 ">
              <label class="form-label mifuente13 "><i class="fas fa-circle-notch text-danger mr-2"></i>Pertenencias</label>
            </div>
            <div class="col-lg-4 ">
              <label class="form-label mifuente13 encabezado"  >VALOR (Inventario en Recaudación)</label>
            </div>
            <div class="col-lg-1  form-check mifuente12">
              <input type="radio" id="frm_valor_recaudacion_si" name="frm_valor_recaudacion" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_valor_recaudacion'] == 'Sí'){ echo "checked"; } ?>>
              <label for="frm_valor_recaudacion_si" class="form-check-label">&nbsp;Sí</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_valor_recaudacion_no" name="frm_valor_recaudacion" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_valor_recaudacion'] == 'No'){ echo "checked"; } ?>>
              <label for="frm_valor_recaudacion_no" class="form-check-label">&nbsp;No</label>
            </div>
          </div>
          <div class="row mt-1 " id="campo_num_inventario" style="display: none;">

            <div class="col-lg-4 ">
              <label class="form-label mifuente13 encabezado"  >Nº INVENTARIO</label>
            </div>
            <div class="col-lg-2" >
              <input type="text" name="frm_num_inventario" placeholder="Nº INVENTARIO" class="form-control form-control-sm mifuente12" value="<?php echo $rsHoja[0]['frm_num_inventario'] ?? ''; ?>">
            </div>
          </div>
          <div class="row mt-1 ">
            <div class="col-lg-4 ">
              <label class="form-label mifuente13 encabezado">ARTICULOS PERSONALES (Sube a piso)</label>
            </div>
            <div class="col-lg-1  form-check mifuente12">
              <input type="radio" id="frm_articulos_personales_si" name="frm_articulos_personales" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_articulos_personales'] == 'Sí'){ echo "checked"; } ?>>
              <label for="frm_articulos_personales_si" class="form-check-label">&nbsp;Sí</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_articulos_personales_no" name="frm_articulos_personales" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_articulos_personales'] == 'No'){ echo "checked"; } ?>>
              <label for="frm_articulos_personales_no" class="form-check-label">&nbsp;No</label>
            </div>
          </div>
          <div class="row mt-1 ">
            <div class="col-lg-4 ">
              <label class="form-label mifuente13 encabezado">CUSTODIA CR EMERGENCIA (Uci-Sai-Pabellón)</label>
            </div>
            <div class="col-lg-1  form-check mifuente12">
              <input type="radio" id="frm_custodia_cr_si" name="frm_custodia_cr" class="form-check-input" value="Sí" <?php if($rsHoja[0]['frm_custodia_cr'] == 'Sí'){ echo "checked"; } ?>>
              <label for="frm_custodia_cr_si" class="form-check-label">&nbsp;Sí</label>
            </div>
            <div class="col-lg-2  form-check mifuente12">
              <input type="radio" id="frm_custodia_cr_no" name="frm_custodia_cr" class="form-check-input" value="No" <?php if($rsHoja[0]['frm_custodia_cr'] == 'No'){ echo "checked"; } ?>>
              <label for="frm_custodia_cr_no" class="form-check-label">&nbsp;No</label>
            </div>

            <div class="col-12 mt-1">
              <textarea name="obs_custodia_cr" rows="5" id="obs_custodia_cr" class="form-control form-control-sm mifuente12"><?=$rsHoja[0]['obs_custodia_cr'];?></textarea>
            </div>
          </div>
        </fieldset>
<?php
// Determinas la edad del paciente
if ( $diasNacido < 30){
  $tipoGlas     = 'Lactante';
  $tipoGlasgow  = 'L';
}else if($datosU[0]['dau_paciente_edad'] >=15){
  $tipoGlas     = 'Adulto';
  $tipoGlasgow  = 'A';
}else{
  $tipoGlas     = 'Pediatrico';
  $tipoGlasgow  = 'P';
}
?>        
          <input type="hidden" name="tipoGlasgow"  value="<?php echo $tipoGlasgow ?>">
          <h4 class="mb-3 text-center mifuente13">Escala de Glasgow para Paciente <?=$tipoGlas?></h4>

<?php
// echo $tipoGlas;
// Arreglos dinámicos según edad
$glasgow = [
    'Adulto' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'Al dolor',
            3 => 'Al hablar',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Leng. incomprensible',
            3 => 'Leng. inapropiado',
            4 => 'Confuso',
            5 => 'Orientada'
        ],
        'motora' => [
            1 => 'Nula',
            2 => 'Extensión al dolor',
            3 => 'Flexión al dolor',
            4 => 'Mov. c/evitac. dolor',
            5 => 'Mov. sentido al dolor',
            6 => 'Obediente'
        ]
    ],

    'Pediatrico' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'En respuesta al dolor',
            3 => 'al Oir una voz',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Palabras incomprensibles o sonidos no especificos',
            3 => 'Palabras inapropiadas',
            4 => 'Confusa',
            5 => 'Orientada, apropiada'
        ],
        'motora' => [
            1 => 'Ninguna',
            2 => 'Extensión en respuesta al dolor',
            3 => 'Flexión en respuesta al dolor',
            4 => 'Retirada al dolor',
            5 => 'Localiza dolor',
            6 => 'Obedece ordenes'
        ]
    ],

    'Lactante' => [
        'ojos' => [
            1 => 'Ninguna',
            2 => 'En respuesta al dolor',
            3 => 'al Oir una voz',
            4 => 'Espontánea'
        ],
        'verbal' => [
            1 => 'Ninguna',
            2 => 'Gime en respuesta al dolor',
            3 => 'Llora en respuesta al dolor',
            4 => 'Irritable, llanto',
            5 => 'Arrullos y balbuceos'
        ],
        'motora' => [
            1 => 'Ninguna',
            2 => 'Postura de descerebración en respuesta al dolor',
            3 => 'Postura de decorticación en respuesta al dolor',
            4 => 'Retirada al dolor',
            5 => 'Se retrae al tacto',
            6 => 'Se mueve espontánea y deliberadamente'
        ]
    ]
];
?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="table-light">
                <tr>
                  <th class="mifuente13 text-center">Puntaje</th>
                  <th class="mifuente13 text-center">Apertura de Ojos</th>
                  <th class="mifuente13 text-center">Respuesta Verbal</th>
                  <th class="mifuente13 text-center">Respuesta Motora</th>
                </tr>
              </thead>
              <tbody class="mifuente11 text-center">
<?php
for ($i = 1; $i <= 6; $i++) {
    echo "<tr>";
    echo "<td>{$i}</td>";

    // --- Apertura de ojos ---
    echo "<td>";
    if (isset($glasgow[$tipoGlas]['ojos'][$i])) {
        echo '
        <div class="form-check justify-content-center">
            <input class="form-check-input glasgow" type="radio"
                   name="ojos"
                   value="'.$i.'"
                   '.($rsHoja[0]['ojos'] == $i ? 'checked' : '').'>
            <label class="form-check-label">'.$glasgow[$tipoGlas]['ojos'][$i].'</label>
        </div>';
    }
    echo "</td>";

    // --- Respuesta verbal ---
    echo "<td>";
    if (isset($glasgow[$tipoGlas]['verbal'][$i])) {
        echo '
        <div class="form-check justify-content-center">
            <input class="form-check-input glasgow" type="radio"
                   name="verbal"
                   value="'.$i.'"
                   '.($rsHoja[0]['verbal'] == $i ? 'checked' : '').'>
            <label class="form-check-label">'.$glasgow[$tipoGlas]['verbal'][$i].'</label>
        </div>';
    }
    echo "</td>";

    // --- Respuesta motora ---
    echo "<td>";
    if (isset($glasgow[$tipoGlas]['motora'][$i])) {
        echo '
        <div class="form-check justify-content-center">
            <input class="form-check-input glasgow" type="radio"
                   name="motora"
                   value="'.$i.'"
                   '.($rsHoja[0]['motora'] == $i ? 'checked' : '').'>
            <label class="form-check-label">'.$glasgow[$tipoGlas]['motora'][$i].'</label>
        </div>';
    }
    echo "</td>";

    echo "</tr>";
}
?>
</tbody>

            </table>
          </div>
          <div class="row">
            <div class="col-lg-10">
            </div>
            <div class="col-lg-2">
              <label class="fw-bold mifuente encabezado">TOTAL:</label>
              <input type="text" id="total" id="total"  name="totalGlasgow" class="form-control form-control-sm mifuente w-25 d-inline text-center ms-2" readonly value="<?=$rsHoja[0]['totalGlasgow']?>">
              <span class="ms-2 mifuente11">PTS.</span>
            </div>
          </div>

          <hr>
          <h4 class="mb-3 text-center mifuente13">Articulos Personales</h4>
          <div class="row mt-3">
            <div class="col-lg-2 ">
              <label class="form-label mifuente13 encabezado"  >Útiles de aseo</label>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['jabon'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="jabon" name="jabon"><label class="form-check-label mifuente11" for="jabon">Jabón</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['shampoo'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="shampoo" name="shampoo"><label class="form-check-label mifuente11" for="shampoo">Shampoo</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['pasta'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="pasta" name="pasta"><label class="form-check-label mifuente11" for="pasta">Pasta dental</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['desodorante'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="desodorante" name="desodorante"><label class="form-check-label mifuente11" for="desodorante">Desodorante</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['confort'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="confort" name="confort"><label class="form-check-label mifuente11" for="confort">Confort</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['pañal'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="pañal" name="pañal"><label class="form-check-label mifuente11" for="pañal">Pañal</label></div>
            </div>
            <div class="col-lg-12"> <input type="text" value="<?=$rsHoja[0]['frm_otro_util_aseo'];?>" name="frm_otro_util_aseo" class="form-control form-control-sm mifuente12" placeholder="Especificar otro útil de aseo"></div>
          </div>

          <div class="row mt-3">
            <div class="col-lg-2 ">
              <label class="form-label mifuente13 encabezado"  >Vestuario</label>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['pijama'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="pijama" name="pijama"><label class="form-check-label mifuente11" for="jabon">Pijama</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['pantuflas'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="pantuflas" name="pantuflas"><label class="form-check-label mifuente11" for="shampoo">Pantuflas</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['polera'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="polera" name="polera"><label class="form-check-label mifuente11" for="pasta">Polera</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['poleron'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="poleron" name="poleron"><label class="form-check-label mifuente11" for="desodorante">Polerón</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['pantalon'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="pantalon" name="pantalon"><label class="form-check-label mifuente11" for="confort">Pantalón</label></div>
            </div>
            <div class="col-lg-12"> <input type="text" value="<?=$rsHoja[0]['frm_otra_prenda'];?>" name="frm_otra_prenda" class="form-control form-control-sm mifuente12" placeholder="Especificar otra prenda de vestir"></div>
          </div>

          <div class="row mt-3">
            <div class="col-lg-2 ">
              <label class="form-label mifuente13 encabezado"  >Ropa de cama</label>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['almohada'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="almohada" name="almohada"><label class="form-check-label mifuente11" for="jabon">Almohada</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['frazada'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="frazada" name="frazada"><label class="form-check-label mifuente11" for="shampoo">Frazada</label></div>
            </div>
            <div class="col">
              <div class="form-check"><input class="form-check-input" type="checkbox" <?php if($rsHoja[0]['sabana'] == 'Sí'){ echo "checked"; } ?>  value="Sí" id="sabana" name="sabana"><label class="form-check-label mifuente11" for="pasta">Sábana</label></div>
            </div>
            <div class="col-lg-12"> <input type="text" value="<?=$rsHoja[0]['frm_otra_ropa_cama'];?>" name="frm_otra_ropa_cama" class="form-control form-control-sm mifuente12" placeholder="Especificar otra prenda de cama"></div>
          </div>
          <div class="row mt-3 gy-2">
  <!-- VÍA TELEFÓNICA -->
  <div class="col-lg-5">
    <div class="form-check">
      <input class="form-check-input" type="checkbox"
             id="frm_via_telefonica" name="frm_via_telefonica" value="Sí"
             <?php if($rsHoja[0]['frm_via_telefonica'] == 'Sí') echo 'checked'; ?>>
      <label class="form-check-label mifuente11" for="frm_via_telefonica">VÍA TELEFÓNICA</label>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="input-group input-group-sm">
      <span class="input-group-text"><i class="fas fa-user-nurse text-primary"></i></span>
      <input type="text" class="form-control form-control-sm mifuente" placeholder="Buscar Enfermero/a" name="nombre_enfermero"  id="nombre_enfermero" value="<?= $rsHoja[0]['nombre_enfermero'] ?? '' ?>">
      <input type="hidden"  id="nombre_enfermero_rut"  name="nombre_enfermero_rut" value="<?= $rsHoja[0]['nombre_enfermero_rut'] ?? '' ?>">
      <input type="datetime-local" class="form-control form-control-sm mifuente col-lg-4" name="entrega_fecha" value="<?= $rsHoja[0]['entrega_fecha'] ?? '' ?>">
    </div>
  </div>
  <!-- VÍA PRESENCIAL -->
  <div class="col-lg-6">
    <div class="form-check">
      <input class="form-check-input" type="checkbox"
             id="frm_via_presencial" name="frm_via_presencial" value="Sí"
             <?php if($rsHoja[0]['frm_via_presencial'] == 'Sí') echo 'checked'; ?>>
      <label class="form-check-label mifuente11" for="frm_via_presencial">VÍA PRESENCIAL</label>
    </div>
  </div>

          <div class="col-12 mt-2">
              <textarea name="obs_enfermeria" rows="5" id="obs_enfermeria" class="form-control form-control-sm mifuente12" placeholder="Observación Enfermeria..."><?=$rsHoja[0]['obs_enfermeria'];?></textarea>
            </div>
</div>

    </form>
  </div>
</div>
