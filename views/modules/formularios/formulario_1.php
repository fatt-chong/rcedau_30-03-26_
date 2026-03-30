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
require_once('../../../class/Formulario_1.class.php');       $objFormulario_1        = new Formulario_1;
require_once('../../../class/FormPacienteGes.class.php');    $objFormPacienteGes     = new FormPacienteGes;

$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor                = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['cta_cte']          = $datosU[0]['idctacte'];
$parametros['ges']              = 'S';
$rsRce_diagnostico              = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$rsFormulario1                  = $objFormulario_1->SelectByDauFormulario_1($objCon,$dau_id);
$GesExistente                   = "display: none;";
$GesExistentePrint              = ""; 
if(count($rsFormulario1) == 0){

  if ( $rsRce[0]['regDiagnosticoCie10'] != null ){
      $parametrosGes['cie10_Codigo']    = $rsRce[0]['regDiagnosticoCie10'];
      $parametrosGes['PACGESpaciente']  = $datosU[0]['id_paciente'];
      $rsSelectFormPacienteGes          = $objFormPacienteGes->SelectFormPacienteGes($objCon,$parametrosGes);
      if(count($rsSelectFormPacienteGes) > 0){
        $GesExistente         = "";
        $GesExistentePrint    = "display: none;";
      }
  }
// print('<pre>'); print_r($rsRce_diagnostico); print('</pre>');
$codigoCIE = trim($_POST['frm_codigoCIE10Ges'] ?? '');
$nomCompleto = trim($_POST['frm_hipotesis_finalGes'] ?? '');

// (Opcional) otros datos del contexto si los tienes
$dau_id   = $_POST['dau_id']  ?? null;         // si viene en el form
$cta_cte  = $_POST['cta_cte'] ?? null;         // si aplica

if ($codigoCIE !== '' && $nomCompleto !== '') {

    // Evitar duplicados simples (mismo CIE10 en el mismo DAU)
    $yaExiste = false;
    if (!empty($rsRce_diagnostico)) {
        foreach ($rsRce_diagnostico as $dx) {
            if ($dx['codigoCIE'] === $codigoCIE && (string)($dx['dau_id'] ?? '') === (string)($dau_id ?? '')) {
                $yaExiste = true;
                break;
            }
        }
    }

    if (!$yaExiste) {
        // Si quieres extraer "nombreCIE" sin el código al inicio:
        // A001 Cólera ...  ->  "Cólera ..."
        $nombreCIE = preg_replace('/^\s*[A-Z0-9.]+\s*/u', '', $nomCompleto);

        $rsRce_diagnostico[] = [
            'id_compartido'                          => null,
            'id_cie10'                               => $codigoCIE,
            'fecha'                                  => date('Y-m-d'),
            'hora'                                   => date('H:i:s'),
            'usuario'                                => null,
            'origen'                                 => 3,             // según tu flujo
            'cta_cte'                                => $cta_cte,
            'diagnistico_descripcion_text'           => $nomCompleto,  // texto completo
            'diagnistico_descripcion_text_comentario'=> '',
            'id_detalle_entrega_turno'               => null,
            'id_sol_req_control'                     => null,
            'dau_id'                                 => $dau_id,
            'codigoCIE'                              => $codigoCIE,
            'nombreCIE'                              => $nombreCIE,    // solo la descripción
            'nomcompletoCIE'                         => $nomCompleto,  // A001 + descripción
            'ocupaNeo'                               => null,
            'UAPO'                                   => null,
            'tapsa_dscr'                             => $nombreCIE,
            'cie_tipo'                               => 'C',           // lo que uses por defecto
            'tipo_ca'                                => null,
            'ges'                                    => 'S',           // ajusta según tu lógica
            'onco'                                   => null,
            'cesarea'                                => 'N',
            'filtroSolicitudAPS'                     => null,
        ];
    }
}
// print('<pre>'); print_r($rsRce); print('</pre>');
// print('<pre>'); print_r($codigoCIE); print('</pre>');

  $rsFormulario1[0]['fecha_notificacion']       = $horarioServidor[0]['fecha'];
  $rsFormulario1[0]['hora_notificacion']        = $horarioServidor[0]['hora'];
  $rsFormulario1[0]['nombre_paciente']          = $datosU[0]['nombres'].' '.$datosU[0]['apellidopat'].' '.$datosU[0]['apellidomat'];
  $rsFormulario1[0]['edad']                     = $datosU[0]['dau_paciente_edad'];
  $rsFormulario1[0]['direccion_paciente']       = $datosDAUPaciente[0]['Direccion'];
  $rsFormulario1[0]['telefono_fijo']            = $datosDAUPaciente[0]['fono1'];
  $rsFormulario1[0]['telefono_celular']         = $datosDAUPaciente[0]['fono2'];
  $rsFormulario1[0]['email']                    = $datosDAUPaciente[0]['email'];
  if($rsRce[0]['ges'] == 'S'){
    $rsFormulario1[0]['cie10']                    = $rsRce[0]['regHipotesisFinal'];
    $rsFormulario1[0]['cie10_Codigo']             = $rsRce[0]['regDiagnosticoCie10'];
  }
  $rsFormulario1[0]['comuna_region']            = $datosDAUPaciente[0]['comuna'];
  if($datosDAUPaciente[0]['REG_Descripcion'] != ""){
    $rsFormulario1[0]['comuna_region']            = $datosDAUPaciente[0]['comuna']."/".$datosDAUPaciente[0]['REG_Descripcion'];
  }
  if($datosDAUPaciente[0]['id_prevision'] == 1 || $datosDAUPaciente[0]['id_prevision'] == 2  || $datosDAUPaciente[0]['id_prevision'] == 3  || $datosDAUPaciente[0]['id_prevision'] == 4){
    $rsFormulario1[0]['aseguradora']            = 'FONASA';
  }else{
    $rsFormulario1[0]['aseguradora']            = 'ISAPRE';
  }
  $rsFormulario1[0]['rut_paciente']             = $objUtil->rutDigito($datosU[0]['rut']);  
}


?>
<script type="text/javascript">
  function seleccionarPrestacion(radio) {
    var nombre = radio.getAttribute('data-nombre');
    var codigo = radio.getAttribute('data-id');
    document.getElementById('cie10').value = nombre;
    document.getElementById('cie10_Codigo').value = codigo;
    respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,'cie10_Codigo='+codigo+'&dau_id='+$('#dau_id').val()+'&idPaciente='+$('#id_paciente').val()+'&accion=buscarCie10GesPacienteActivo', 'POST','JSON', 1, '' );
    if(respuestaAjaxRequest.GesActivo == 'S'){
      $('#div_PACGESid').show();
      $('#div_imprimir').hide();
      
    }else{
      $('#div_PACGESid').hide();
      $('#div_imprimir').show();
    }
  }
  $(document).ready(function() {
    validar("#email", "correo");
    validar("#nombre_representante", "letras_numeros");
    validar("#nombre_medico", "letras_numeros");
    validar("#rut_representante", "rut");
    validar("#telefono_fijo", "celular");
    validar("#telefono_celular", "celular");
    validar("#telefono_representante", "celular");
    validar("#celular_representante", "celular");
    validar("#email_representante", "correo");
    // validar($('#email'),tipo)
    $('#rut_representante').Rut({
        on_error: function () { },
        format_on: 'keyup'
    });
    $('#guardarFormulario1').on('click', function(){
      var idDau = $('#dau_id').val(); 
       $.validity.start();
    
      if($("#nombre_medico").val() == ""){
          $('#nombre_medico').assert(false,'Debe ingresar un médico');
          $.validity.end();
          return false;
      }
      
      if($("#cie10_Codigo").val() == ""){
          $('#cie10').assert(false,'Debe seleccionar un CIE10 GES');
          $.validity.end();
          return false;
      }

      respuestaAjaxRequest = ajaxRequest(`${raiz}/controllers/server/enfermera/main_controller.php`,$("#formulario_1").serialize()+'&accion=guardarFormularioController', 'POST','JSON', 1, '' );
      switch ( respuestaAjaxRequest.status ) {
      case "success":
        ajaxContent(raiz+'/views/modules/formularios/formulario_1.php','dau_id='+$('#dau_id').val(),'#formContainer','', true);
        modalFormulario("<label class='mifuente ml-2'>Hoja Hospitalización DAU N°"+idDau+"</label>", `${raiz}/views/modules/formularios/pdfformulario_1.php`, 'PACGESid='+respuestaAjaxRequest.PACGESid, "#modalHojaEnfermeria", "modal-lg", "light",'', '');
      }
    });
    $('#verFormulario').on('click', function(){
      
        modalFormulario("<label class='mifuente ml-2'>Formulario GES</label>", `${raiz}/views/modules/formularios/pdfformulario_1.php`, 'PACGESid='+$('#PACGESid').val(), "#modalHojaEnfermeria", "modal-lg", "light",'', '');
    });
    $("#nombre_medico").autocomplete({ 
      close: function( event, ui ) {
      if ( fila == "" ) {
        $("#nombre_medico").val("");
      }
      },
      source: function(request, response) {      
        $.ajax({
          type: "POST",
          url: raiz+"/controllers/server/consulta/main_controller.php",
          dataType: "json",
          data: {
            term : request.term,
            accion : 'busquedaSensitivaMedicos',
          },
          success: function(data) {
            response(data)
          }
        });                
      },
        minLength: 3, 
        select: function(event, ui){
        $('#rut_medico_hidden').val( ui.item.id );
        $('#rut_medico').val( ui.item.rut );
        $('#nombre_medico').val( ui.item.nombre );
        },
        open: function(){
        $('.ui-menu').addClass("col-md-12");
        $('.ui-menu').addClass("mifuente11");
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
</style>
<form class="formularios " name="formulario_1" id="formulario_1" >
  <input type="hidden" name="id_formulario" id="id_formulario" value="<?=$rsFormulario1[0]['id'];?>" >
  <input type="hidden" name="dau_id" id="dau_id" value="<?=$dau_id;?>" >
  <input type="hidden" name="id_paciente" id="id_paciente" value="<?=$datosU[0]['id_paciente'];?>" >
  <input type="hidden" name="PACGESid" id="PACGESid" value="<?=$rsSelectFormPacienteGes[0]['PACGESid'];?>" >

    <div id="div_PACGESid" style="<?=$GesExistente;?>">
    <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
      <div>
        <strong>Atención:</strong> Este paciente ya cuenta con un formulario GES vigente.
      </div>

      <button id="verFormulario" type="button" class="verFormulario btn btn-sm btn btn-danger "><i class="far fa-file-pdf mr-3"></i> Ver Formulario</button>
    </div>
  </div>

  <h5 class="mt-2 mifuente14">Datos del Médico 
  <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
  <div class="row mb-2">
    <div class="col-md-6">
      <label class="mifuente12">Nombre médico</label>
      <input type="text" name="nombre_medico" id="nombre_medico" class="form-control form-control-sm mifuente11" value="<?=$rsFormulario1[0]['nombre_medico']?>">
    </div>
    <div class="col-md-6">
      <label class="mifuente12">RUT médico</label>
      <input type="text" name="rut_medico" id="rut_medico" readonly class="form-control form-control-sm mifuente11" value="<?=$rsFormulario1[0]['rut_medico']?>">
      <input type="hidden" name="rut_medico_hidden" id="rut_medico_hidden" >
    </div>
  </div>
  <h5 class="mt-2 mifuente14">Datos del Paciente
  <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
  <div class="row mb-2">
    <div class="col-md-3">
      <label class="mifuente12">Nombre completo</label>
      <input type="text" name="nombre_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['nombre_paciente']?>">
    </div>
    <div class="col-md-2">
      <label class="mifuente12">RUT</label>
      <input type="text" name="rut_paciente" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['rut_paciente']?>">
    </div>
    <div class="col-md-2">
      <label class="mifuente12">Aseguradora</label>
      <select name="aseguradora" class="form-select form-control form-control-sm mifuente10" readonly>
        <option value="">Seleccione</option>
        <option value="FONASA" <?php if( $rsFormulario1[0]['aseguradora'] == 'FONASA' ){ echo "selected" ;} ?> >FONASA</option>
        <option value="ISAPRE" <?php if( $rsFormulario1[0]['aseguradora'] == 'ISAPRE' ){ echo "selected" ;} ?> >ISAPRE</option>
      </select>
    </div>
    <div class="col-md-2">
      <label class="mifuente12">Comuna/Región</label>
      <input type="text" name="comuna_region" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['comuna_region']?>">
    </div>
    <div class="col-md-3">
      <label class="mifuente12">Dirección</label>
      <input type="text" name="direccion_paciente" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['direccion_paciente']?>">
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-md-4">
      <label class="mifuente12">Teléfono fijo</label>
      <input type="text" name="telefono_fijo"  id="telefono_fijo" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['telefono_fijo']?>">
    </div>
    <div class="col-md-4">
      <label class="mifuente12">Celular</label>
      <input type="text" name="telefono_celular" id="telefono_celular" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['telefono_celular']?>">
    </div>
    <div class="col-md-4">
      <label class="mifuente12">Email</label>
      <input type="email" name="email" id="email" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['email']?>" >
    </div>
  </div>
  <h5 class="mt-2 mifuente14">Información Médica
  <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
  <div class="row mb-2">
    <?php if( count($rsRce_diagnostico) > 0  ){ ?>
    <div class=" col-lg-12 col-md-12 mt-2" >
    <label class="mifuente12 text-danger"><b>Listado CIE 10</b></label>
      <div class="table-responsive-lg">
      <table id="tabla_contenido_insumos" width="100%" class="table table-hover" style="font-size: 14px;">
              <tbody id="contenido_diagnostico" >
                <?php  for ($i=0; $i < count($rsRce_diagnostico) ; $i++) { 
                  $codigo = $rsRce_diagnostico[$i]['id_cie10'];
                  $texto = $rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                  $posicion = strpos($texto, $codigo);
                  $textoDiag = "";
                  if ($posicion !== false) {
                  }else{
                      $rsRce_diagnostico[$i]['diagnistico_descripcion_text'] = $rsRce_diagnostico[$i]['id_cie10']." ".$rsRce_diagnostico[$i]['diagnistico_descripcion_text'];
                  }

                  if($rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario'] != ""){
                      $textoDiag = $rsRce_diagnostico[$i]['diagnistico_descripcion_text']." <br> -&nbsp;&nbsp;".$rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']; 
                      $textoDiag = $rsRce_diagnostico[$i]['diagnistico_descripcion_text']." -&nbsp;&nbsp;".$rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']; 
                  }else{
                      $textoDiag .= $rsRce_diagnostico[$i]['diagnistico_descripcion_text']; 
                  }
                  ?>
                <tr id="id<?php echo $rsRce_diagnostico[$i]['id_compartido'];?>">
                  <td class="my-1 py-1 mx-1 px-1 mifuente11 td_id_cie10_TABLA " hidden ><?php echo $rsRce_diagnostico[$i]['id_cie10'];?></td>

                  <td class="my-1 py-1 mx-1 px-1 mifuente11  " width="90%"><?php echo $textoDiag;?></td>
                  <td class="my-1 py-1 mx-1 px-1 mifuente11 text-center" style="vertical-align:middle;" >
                     <input type="radio" name="select_prestacion" data-id="<?php echo $rsRce_diagnostico[$i]['id_cie10']; ?>" data-nombre='<?php echo $textoDiag; ?>'  data-abierto="<?php echo $rsRce_diagnostico[$i]['diagnistico_descripcion_text_comentario']; ?>" onchange="seleccionarPrestacion(this)">
                  </td>
                </tr>
                <?php } ?>
              </tbody>
          </table>
      </div>
    </div>
  <?php } ?>
  <div class="col-md-6">
    <label class="mifuente12">CIE 10</label>
    <input type="text" id="cie10" name="cie10" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['cie10']?>">
    <input type="hidden" id="cie10_Codigo" name="cie10_Codigo" value="<?=$rsFormulario1[0]['cie10_Codigo']?>">
  </div>
  <div class="col-md-3">
    <label class="mifuente12">Fecha</label>
    <input type="date" name="fecha_notificacion" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['fecha_notificacion']?>">
  </div>
  <div class="col-md-3">
    <label class="mifuente12">Hora</label>
    <input type="time" name="hora_notificacion" class="form-control form-control-sm mifuente11" readonly value="<?=$rsFormulario1[0]['hora_notificacion']?>">
  </div>
  </div>
  <div class="row mb-2">
  <div class="col-md-3">
    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" name="confirmacion_diagnostico" value="Sí" name="confirmacion_diagnostico" <?php if($rsFormulario1[0]['confirmacion_diagnostico'] == 'Sí'){ echo "checked"; } ?> >
      <label class="form-check-label mifuente12 ">Confirmación diagnóstica</label>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" name="paciente_tratamiento" value="Sí" name="paciente_tratamiento" <?php if($rsFormulario1[0]['paciente_tratamiento'] == 'Sí'){ echo "checked"; } ?>>
      <label class="form-check-label mifuente12 ">Paciente en tratamiento</label>
    </div>
  </div>
  </div>
  <hr>
  <h5 class="mt-2 mifuente14">Representante (opcional)
  <i class="fas fa-pen ml-2 writing-icon text-primary"></i></h5>
  <div class="row mb-2">
  <div class="col-md-6">
    <label class="mifuente12">Nombre</label>
    <input type="text" name="nombre_representante" id="nombre_representante" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['nombre_representante']?>" >
  </div>
  <div class="col-md-6">
    <label class="mifuente12">RUT</label>
    <input type="text" name="rut_representante"  id="rut_representante"  class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['rut_representante']?>" >
  </div>
  </div>
  <div class="row mb-2">
  <div class="col-md-4">
    <label class="mifuente12">Teléfono fijo</label>
    <input type="text" name="telefono_representante" id="telefono_representante" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['telefono_representante']?>" >
  </div>
  <div class="col-md-4">
    <label class="mifuente12">Celular</label>
    <input type="text" name="celular_representante" id="celular_representante" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['celular_representante']?>" >
  </div>
  <div class="col-md-4">
    <label class="mifuente12">Email</label>
    <input type="email" name="email_representante" id="email_representante" class="form-control form-control-sm mifuente11"  value="<?=$rsFormulario1[0]['email_representante']?>" >
  </div>
  </div>

  <hr>
  <div class="row" id="div_imprimir" style="<?=$GesExistentePrint;?>" >
    <div class="col-lg-4">
    </div>
    <div class="col-lg-4">
    </div>
    <div class="col-lg-4">
      <button id="guardarFormulario1" type="button" class="guardarFormulario1 btn btn-sm btn btn-primary col-lg-12"><i class="fas fa-print mr-3"></i> Imprimir</button>
    </div>
  </div>
  <!-- <button id="guardarFormulario1" type="button" class="btn float-right btn-sm btn-outline-primarydiag guardarFormulario1" data-toggle="tooltip" data-placement="top" title="" style="border-color: #007bff00;padding: 0rem 0.5rem;" data-original-title="Detalle de Categorización">Guardar -->
                            <!-- </button> -->
</form>
