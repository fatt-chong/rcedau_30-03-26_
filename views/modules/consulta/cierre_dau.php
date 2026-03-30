<?php
ini_set('max_execution_time', 600); //300 seconds = 5 minutes
ini_set('memory_limit', '128M');
error_reporting(0);
require("../../../config/config.php");
require_once('../../../class/Connection.class.php');        $objCon             = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');              $objUtil            = new Util;
require_once('../../../class/Indicacion.class.php');        $objIndicacion      = new Indicacion;
require_once('../../../class/Consulta.class.php');          $objConsulta        = new Consulta;
require_once('../../../class/Dau.class.php');               $objDau             = new Dau;
require_once('../../../class/Cierre.class.php');            $objCierre          = new Cierre;
require_once('../../../class/Servicios.class.php');         $objServicio        = new Servicios;
require_once("../../../class/Agenda.class.php" );           $objAgenda          = new Agenda;
require_once("../../../class/RegistroClinico.class.php" );  $objRegistroClinico = new RegistroClinico;
require_once('../../../class/Rce.class.php');               $objRce             = new Rce;

$parametros['Iddau']           = $_POST['Iddau'];
$parametros                    = $objUtil->getFormulario($_POST);
$parametros['dau_id']          = $parametros['Iddau'];
$obtenerIndicacionEgreso       = $objDau->obtenerIndicacionEgreso($objCon,$parametros);
$listaDestino                  = $objDau->getDatosEgreso($objCon,$parametros);
$destinoControl                = $listaDestino[0]['des_id'];
$resEspecialidad               = $objAgenda->getEspecialidad($objCon);
$rsDerivacion                  = $objDau->getAltaDerivacion($objCon);
$rsAPS                         = $objDau->getAPS($objCon);
$rsRce                         = $objRegistroClinico->consultaRCE($objCon,$parametros);
$listarIndicacion              = $objIndicacion->listarIndicacion($objCon);
$parametros['Iddau']           = $parametros['dau_id'];
$datos                         = $objConsulta->consultaDAU($objCon,$parametros);
$fechaActualAlcoholemia        = date("d/m/Y");
$fechaActualAlcoholemiaMax     = date("Y-m-d");
$horaActualAlcoholemia         = date("H      : i: s");

if ( $datos[0]['dau_alcoholemia_fecha'] ) {

  $fechaAcoholemia = date("d-m-Y H: i", strtotime($datos[0]['dau_alcoholemia_fecha']));

}

if ( ! $datos[0]['cat_id'] ) {

    $datos[0]['cat_id'] = "Sin definir";

}

$listaCondicionIngreso         = $objCierre->listarCondicionIngreso($objCon);
$listaPronostico               = $objCierre->listarPronostico($objCon);
$listaTratamiento              = $objCierre->listarTratamiento($objCon);
$listaAtendidoPor              = $objCierre->listarAtendidoPor($objCon);
$listaEtilico                  = $objDau->listarEtilico($objCon);
$listaTurnos                   = $objCierre->listarTurno($objCon);
$listaTipoAtencion             = $objCierre->listarTipoAtencion($objCon);
$listarServiciosDau            = $objServicio->ListarServiciosDau($objCon);
$parametros['dau_id']          = $_POST['Iddau'];
$servicioEgreso                = $objDau->getIndicacionEgreso($objCon, $parametros);

if ( $datos[0]['dau_cierre_administrativo_fecha'] ) {

  $tiempoCierre = $datos[0]['dau_cierre_administrativo_fecha'];

} else if ( $datos[0]['dau_indicacion_egreso_aplica_fecha'] ) {

  $tiempoCierre = $datos[0]['dau_indicacion_egreso_aplica_fecha'];
}

if ( $tiempoCierre  == '' ) {

    $fechaCierreAdministrativo = date("d-m-Y H: i");

} else {

    $fechaCierreAdministrativo = date("d-m-Y H: i", strtotime($tiempoCierre));

}

$parametros['dau_id']        = $_POST['Iddau'];
$resRce                      = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['rce_id']        = $resRce[0]['regId'];
$eventos                     = 1;
$listadoIndicaciones         = $objRegistroClinico->listarIndicacionesRCE_enf($objCon,$parametros);

$version    = $objUtil->versionJS();


$transexual_bd   = $datos[0]['transexual'];
$nombreSocial_bd = $datos[0]['nombreSocial'];
$nombrePaciente  = $datos[0]['nombres']." ".$datos[0]['apellidopat']." ".$datos[0]['apellidomat'];
$nombreLabel     = 'Paciente';
$InfoNombre      = $objUtil->vista_dau_input_label_modo_2($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'S');
// print('<pre>');  print_r($listadoIndicaciones);  print('</pre>');
?>



<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/moment.js"></script>
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/locale/es.js"></script>
<script type="text/javascript" src="<?=PATH?>/assets/libs/dateTimePicker/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/consulta/cierre.js?v=<?=$version;?>"></script>



<!--
################################################################################################################################################
                                                                    ESTILOS
-->
<style type="text/css">
    .panel-body{
        margin-bottom: -2%;
    }
</style>



<!--
################################################################################################################################################
                                                                  DESPLIEGUE FORMULARIO
-->
<form id="frm_cierre" name="frm_cierre" class="formularios form-horizontal" role="form" method="POST"  onsubmit="return false">

  <!--
  **************************************************************************
                              Campos Ocultos
  **************************************************************************
  -->
  <input type="hidden" name="frm_est_id"        id="frm_est_id"       value="<?=$datos[0]['est_id']?>">
  <input type="hidden" name="frm_dau_atencion"  id="frm_dau_atencion" value="<?=$datos[0]['dau_atencion']?>">
  <input type="hidden" name="frm_id_paciente"   id="frm_id_paciente"  value="<?=$datos[0]['id_paciente']?>">
  <input type="hidden" name="idDau"             id="idDau"            value="<?=$_POST['Iddau']?>">

  <!--
  **************************************************************************
                              Datos del Paciente
  **************************************************************************
  -->
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos del paciente</b></h6>
            <!-- <legend>Datos del paciente</legend>       -->
    <div class="row" >
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">N° DAU </label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['dau_id']?></span>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">Cuenta Cte</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['idctacte']?></span>
      </div>
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">Previsión:</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['prevision']?></span>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">Convenio</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['prevision']?></span>
      </div>

    </div>
    <div class="row" >
        <!-- Paciente -->
        <div class="col-md-1 mifuente">
            <label class="control-label encabezado"><?=$InfoNombre['label']?></label>
        </div>

        <div class="col-md-4 mifuente">
            <span>:&nbsp;<?=$InfoNombre['input']?></span>
        </div>
        <!-- Tiempo Atención -->
        <div class="col-md-2 text-right mifuente">
            <label class="control-label encabezado">Tiempo atención</label>
        </div>
        <div class="col-md-5 mifuente">
            <span>:&nbsp;<?=$tiempoCierre?></span>
        </div>
    </div>
    <hr>
  <!--
  **************************************************************************
                              Datos Clínicos
  **************************************************************************
  -->
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos Clínicos</b></h6>
    <div class="row">
        <div class="col-lg-12">       
            <label class="titulo"> * Admisión</label>
        </div>
    </div>
    <div class="row" >  
        <!-- Paciente llega en... -->
        <div class="col-md-2" "><label class="encabezado">Paciente llega en</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "  value="<?=$datos[0]['med_descripcion']?>" disabled></div>
        <!-- Categorización -->
        <div class="col-md-1" ><label class="encabezado">Categorizacion</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "  value="<?=$datos[0]['cat_id']?>" disabled></div>
        <!-- Fecha -->            
        <div class="col-md-1" ><label class="encabezado">Fecha</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente "   value="<?=date('d-m-Y',strtotime($datos[0]['dau_admision_fecha']))?>" disabled></div>
        <!-- Hora -->
        <div class="col-md-1" ><label class="encabezado">Hora</label></div>
        <div class="col-md-1"><input type="text" class="form-control form-control-sm mifuente "  value="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>" disabled></div>
    </div>
    <div class="row mt-2" >
        <!-- Condición Ingreso -->
        <div class="col-md-2" ><label class="encabezado">Condicion ingreso</label></div>
        <div class="col-md-2">
            <select class="form-control form-control-sm mifuente " id='frm_condicion_ingreso' name="frm_condicion_ingreso" > 
                <option value="">Seleccione</option>                        
                <?php for ($i=0; $i <count($listaCondicionIngreso) ; $i++) { ?>
                    <option value="<?=$listaCondicionIngreso[$i]['con_ingreso_id']?>" <?php if($datos[0]['dau_cierre_condicion_ingreso_id']==$listaCondicionIngreso[$i]['con_ingreso_id']){ echo "selected";}?>> <?=$listaCondicionIngreso[$i]['con_ingreso_nombre']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <!-- Pronóstico -->
        <div class="col-md-1" ><label class="encabezado">Pronostico</label></div>
        <div class="col-md-2">  
            <select class="form-control form-control-sm mifuente " id='frm_pronostico' name="frm_pronostico" > 
            <option value="">Seleccione</option> 
                <?php for ($i=0; $i <count($listaPronostico) ; $i++) { ?>
                    <option value="<?=$listaPronostico[$i]['pro_pronostico_id']?>" <?php if($rsRce[0]["PRONcodigo"]===$listaPronostico[$i]['pro_pronostico_id']){ echo "selected";}?>> <?=$listaPronostico[$i]['pro_pronostico_nombre']?> 
                    </option>
                <?php } ?>
            </select>
        </div>
        <?php
            $idPaciente = $datos[0]['id_paciente'];
            $idRCE      = $rsRce[0]['regId'];
            $signosVitales = $objRce->listarSignosVitalesCierreDAU($objCon, $idPaciente, $idRCE);
        ?>
        <!-- Peso -->
        <div class="col-md-1" ><label class="encabezado">Peso</label></div>
        <div class="col-md-2"><input type="text" class="form-control form-control-sm mifuente " id="frm_peso" name="frm_peso" placeholder="(Kgs)" maxlength="5" value="<?=$signosVitales['SVITALpeso']?>"></div>
        <!-- Estatura -->
        <div class="col-md-1" ><label class="encabezado">Estatura</label></div>
        <div class="col-md-1"><input type="text" class="form-control form-control-sm mifuente " id="frm_estatura" name="frm_estatura" placeholder="(cms)" maxlength="5" value="<?=$signosVitales['SVITALtalla']?>"></div>
    </div>


      <!--
      **************************************************************************
                                  Datos Atención
      **************************************************************************
      -->
      <div class="row">
        <div class="col-lg-12">       
            <label class="titulo"> * Atención</label>
        </div>
      </div>

      <?php
      if ( ! empty($listadoIndicaciones) ) {
      ?>

        <div class="row">

          <div class="col-md-4"></div>

          <div class="col-md-4 encabezado" style="margin-top: -15px;"><center>Tratamiento</center></div>

          <div class="col-md-4"></div>

        </div>

        <div class="row" ">

          <div class="col-md-12 center-block" style="width: 100%; height: 200px; overflow-y: scroll;">

            <table id="tablaContenidoIndicaciones_cierreDau"   class="table table-bordered table-hover table-condensed mifuente11 tablasHisto" style="text-align:center;  width: 100%;">

              <thead>

                <tr class="detalle">

                  <th width="20%" style="text-align:center;" class="encabezado mifuente12">Ind. Registrada</th>

                  <th width="20%" style="text-align:center;" class="encabezado mifuente12">Tipo Ind.</th>

                  <th width="20%" style="text-align:center;"  class="encabezado mifuente12">Estado</th>

                  <th width="20%" style="text-align:center;" class="encabezado mifuente12">F/H Sol</th>

                  <th width="20%" style="text-align:center;" class="encabezado mifuente12">F/H Apl</th>

                </tr>

              </thead>

              <tbody >

                <?php

                // servicio == 6 => Procedimiento
                // servicio == 1 => Imagenologia
                // servicio == 3 => Laboratorio

                for ( $i = 0; $i < count($listadoIndicaciones); $i++ ) {

                  switch ($listadoIndicaciones[$i]['estado']) {

                    case 1:
                      $clase_seleccionada = "seleccionable";
                      $color = "color-E7F4FF";
                    break;

                    case 4:
                      $clase_seleccionada = "restringida";
                      $color = "color-F0FFF0";
                    break;

                    case 6:
                      $clase_seleccionada = "restringida";
                      $color = "color-FFF0F6";
                    break;

                  }

                  if ( $listadoIndicaciones[$i]['servicio'] == 6 || $listadoIndicaciones[$i]['servicio'] == 1 || $listadoIndicaciones[$i]['servicio'] == 3 ) {
                  ?>

                    <tr class="<?=$color?> <?=$clase_seleccionada?>">

                      <!-- Indicación Registrada -->
                      <td  class="mifuente my-1 py-1 mx-1 px-1 mifuente11" style="width:20%;" >
                        <?=$listadoIndicaciones[$i]['Prestacion'];?>
                      </td>

                      <!-- Tipo Indicación -->
                      <td  class="mifuente my-1 py-1 mx-1 px-1 mifuente11" style="width:20%;" >

                      <?php
                      if ( $listadoIndicaciones[$i]['servicio'] == 4 ) {

                        echo 'Otros';

                      } else if ( $listadoIndicaciones[$i]['servicio'] == 6 ) {

                        echo 'Procedimiento';

                      } else {

                        $tipoSolicitud = explode("Solicitud ", $listadoIndicaciones[$i]['descripcion']);

                        echo $tipoSolicitud[1];

                      }
                      ?>

                      </td>

                      <!-- Estado Indicación -->
                      <td>

                        <?=$listadoIndicaciones[$i]['estadoDescripcion'];?>

                      </td>

                        <!-- Fecha/Hora Solicitud -->
                        <td  class="mifuente my-1 py-1 mx-1 px-1 mifuente11" >

                        <?php
                        if ( ! is_null($listadoIndicaciones[$i]['fechaInserta']) && ! empty($listadoIndicaciones[$i]['fechaInserta']) ) {

                          echo date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaInserta']));

                        }
                        ?>

                        </td>

                        <!-- Fecha/Hora Cambio Estado Solicitud -->
                        <td  class="mifuente my-1 py-1 mx-1 px-1 mifuente11" >

                        <?php
                        if ( ! is_null($listadoIndicaciones[$i]['fechaAplica']) && ! empty($listadoIndicaciones[$i]['fechaAplica']) ) {

                          echo date("d-m-Y H:i:s",strtotime($listadoIndicaciones[$i]['fechaAplica']));

                        }
                        ?>

                        </td>

                      </tr>

                    <?php
                    }

                  }
                  ?>

                </tbody>

              </table>

            </div>

        </div>

        <br>

      <?php
      } else {
      ?>
        <div class="row">

          <div class="col-md-4"></div>

          <div class="col-md-4 encabezado" style="margin-top: -15px;"><center>No Tiene Tratamiento</center></div>

          <div class="col-md-4"></div>

        </div>

        <br>

      <?php
      }
      ?>
    <div class="row mt-2" >
      <div class="col-md-4">
          <div class="row"> 
            <div class="col-md-6" ><label class="encabezado">Atendido por</label></div>
            <div class="col-md-6">
                <select class="form-control form-control-sm mifuente " id='frm_atendido_por' name="frm_atendido_por" > 
                    <option value="">Seleccione</option> 
                    <?php for ($i=0; $i <count($listaAtendidoPor) ; $i++) { ?>
                        <option value="<?=$listaAtendidoPor[$i]['ate_atendidopor_id']?>" <?php if($datos[0]['dau_cierre_atendidopor_id']==$listaAtendidoPor[$i]['ate_atendidopor_id']){ echo "selected";}?>> <?=$listaAtendidoPor[$i]['ate_atendidopor_nombre']?> 
                        </option>
                    <?php } ?>
                </select>
            </div>
              <div class="col-md-6 mt-2" ><label class="encabezado">Profesional</label></div>   
              <div class="col-md-6 mt-2">
                  <input type="text" class="form-control form-control-sm mifuente " id="Profesional" name="Profesional" value="<?php echo $datos[0]['nombreUsuario']; ?>" >
              </div>
              <div class="col-md-6 mt-2" ><label class="encabezado">Hora Atencion</label></div>   
              <div class="col-md-6 mt-2">
                  <input type="text" class="form-control form-control-sm mifuente " id="frm_hora_atencion" name="frm_hora_atencion" value="<?php echo date('H:i', strtotime($datos[0]['dau_inicio_atencion_fecha'])); ?>">
              </div>
              <div class="col-md-6 mt-2" ><label class="encabezado">Entrega Postinor</label></div>   
              <div class="col-md-6 mt-2">
                  <input type="checkbox" id="frm_postinor" name="frm_postinor" <?php if($datos[0]['dau_cierre_entrega_postinor']=='S'){ echo "checked";}?> value="S">
              </div>
              <div class="col-md-6 mt-2" ><label class="encabezado">Auge</label></div>   
              <div class="col-md-6 mt-2">
                  <input type="checkbox"  id="frm_auge" name="frm_auge" <?php if($datos[0]['dau_cierre_auge']=='S'){echo "checked";}?> value="S">
              </div>
              <div class="col-md-6 mt-2" ><label class="encabezado">Pertinencia</label></div>   
              <div class="col-md-6 mt-2">
                  <input type="checkbox"  id="frm_pertinencia" name="frm_pertinencia" <?php if($datos[0]['dau_cierre_pertinencia']=='S'){ echo "checked";}?> value="S">
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="row">
          <div class="col-md-4" ><label class="encabezado">Estado Etilico</label></div>
          <div class="col-md-8">
              <select class="form-control form-control-sm mifuente " id='frm_etilico' name="frm_etilico" > 
                  <option value="">Seleccione</option>
                  <?php for ($i=0; $i <count($listaEtilico) ; $i++) { ?>
                      <option value="<?=$listaEtilico[$i]['eti_id']?>" <?php if($datos[0]['dau_alcoholemia_estado_etilico']==$listaEtilico[$i]['eti_id']){ echo "selected";}?>> <?=$listaEtilico[$i]['eti_descripcion']?> 
                      </option>
                  <?php } ?>
              </select>
          </div>

              <div class="col-md-4 mt-2" ><label class="encabezado">Turno</label></div>
              <div class="col-md-8 mt-2"> 
                  <select class="form-control form-control-sm mifuente " id='frm_turno' name="frm_turno" > 
                      <option value="">Seleccione</option> 
                      <?php for ($i=0; $i <count($listaTurnos) ; $i++) { ?>
                          <option value="<?=$listaTurnos[$i]['tur_turno_id']?>" <?php if($datos[0]['dau_cierre_turno_id']==$listaTurnos[$i]['tur_turno_id']){ echo "selected";}?>> <?=$listaTurnos[$i]['tur_nombre']?> 
                          </option>
                      <?php } ?>
                  </select>
              </div>
              <div class="col-md-4 mt-2" ><label class="encabezado">Fecha Egreso</label></div>
              <div class="col-md-8 mt-2"> 
                  <input type="text"  class="form-control form-control-sm mifuente  date" id="frm_fecha_egreso_adm" name="frm_fecha_egreso_adm" placeholder="DD-MM-AA"  value="<?=$fechaCierreAdministrativo;?>" readonly>
              </div>
          </div>
      </div>
      <div class="col-md-5 ">                  
          <div class="row border-left  border-top" >
              <fieldset class="col-md-12">
                   <div class="row">
                      <div class="col-lg-12">       
                          <label class="titulo mt-2"> * Alcoholemia</label>
                      </div>
                  </div>
                  <div class="row">
                      <!-- Número Frasco -->
                      <div class="col-sm-3">
                          <label class="encabezado">Nro:</label>
                      </div>
                      <div class="col-sm-3">
                          <?php 
                          if(!is_null($datos[0]['dau_alcoholemia_numero_frasco']) && !empty($datos[0]['dau_alcoholemia_numero_frasco'])){
                              $readonly = "readonly";
                          }
                          else{
                              $readonly = "";
                          }
                          ?>
                          <input type="text" class="form-control form-control-sm mifuente " id="frm_nro" name="frm_nro" value="<?=$datos[0]['dau_alcoholemia_numero_frasco']?>" placeholder="Nro" <?php echo $readonly; ?>>
                      </div>
                      <!-- Resultado -->
                      <div class="col-sm-3">
                          <label class="encabezado">Resultado:</label>
                      </div>
                      <div class="col-sm-3 text-center" >
                          +&nbsp;&nbsp;<input type="radio"  name="resultado" id="positivo" <?php if($datos[0]['dau_alcoholemia_resultado']=='P'){ echo "checked";}?> value="P">
                          &nbsp;&nbsp;-&nbsp;&nbsp;<input type="radio" name="resultado" id="negativo" <?php if($datos[0]['dau_alcoholemia_resultado']=='N'){ echo "checked";}?> value="N">                                
                      </div>
                  </div>
                  <!-- Variables ocultas -->
                  <?php
                      $dau_alcoholemia_fecha = date("Y-m-d",strtotime($datos[0]['dau_alcoholemia_fecha']));
                      $dau_alcoholemia_hora = date("H:i:s", strtotime($datos[0]['dau_alcoholemia_fecha']));
                   ?>
                  <!-- Fecha y hora de atención -->
                  <input type="hidden" name="inpH_atencion_fecha"  id="inpH_atencion_fecha"   value="<?=date('d/m/Y',strtotime($datos[0]['dau_admision_fecha']))?>">
                  <input type="hidden" name="inpH_atencion_hora"   id="inpH_atencion_hora"    value="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>">
                  <!-- Fecha y hora actual -->
                  <input type="hidden" name="inpH_horaActual"      id="inpH_horaActual"   value="<?=$horaActualAlcoholemia?>">
                  <input type="hidden" name="inpH_FechaActual"     id="inpH_FechaActual"  value="<?=$fechaActualAlcoholemia?>">
                  <!-- Input a guardar fecha y hora ingresadas -->
                  <input type="hidden"  name="horaAcoholemia" id="horaAcoholemia">
                  <div class="row mt-2">
                      <!-- Fecha -->
                      <div class="col-sm-3">
                          <label class="encabezado">Fecha:</label>
                      </div>                  
                      <div class="col-sm-9 input-group-date" id="date_fecha_alcoholemia">
                          <input type="text"  class="form-control form-control-sm mifuente" placeholder="DD-MM-AA" id="frm_fecha_alcoholemia" min="<?=date('Y-m-d',strtotime($datos[0]['dau_admision_fecha']))?>"  max="<?=$fechaActualAlcoholemiaMax?>" name="frm_fecha_date" value="<?php echo ($datos[0]['dau_alcoholemia_fecha']) ? date("d/m/Y",strtotime($datos[0]['dau_alcoholemia_fecha'])) : ""; ?>">
                      </div>
                  </div>
                  <div class="row mt-2">
                      <!-- Hora -->
                      <div class="col-sm-3">
                          <label class="encabezado">Hora:</label>
                      </div>
                      <div class="col-sm-9">
                          <input type="text"  class="form-control form-control-sm mifuente" placeholder="HH:MM" id="frm_hora_alcoholemia" min="<?=date('H:i:s',strtotime($datos[0]['dau_admision_fecha']))?>" max="<?=$horaActualAlcoholemia?>" name="frm_hora_date" value="<?php echo ($datos[0]['dau_alcoholemia_fecha']) ? $dau_alcoholemia_hora : ""; ?>" onClick="this.value=''">  
                      </div>
                  </div>
                  <div class="row mt-2">
                      <?php
                      $fechaAlcoholemia   = $datos[0]['dau_alcoholemia_fecha'];
                      $profesional        = '';
                      if ( existeAlcoholemia( $fechaAlcoholemia ) ) {
                          $profesionalesAlcoholemia = $objDau->profesionalAlcoholemia($objCon, $_POST['Iddau']);
                          $profesional = profesionalQueRegistroAlcoholemia($profesionalesAlcoholemia);
                      } else {
                          $placeHolder = 'Profesional No Registrado';
                      }
                      ?>
                      <!-- Profesional -->
                      <div class="col-sm-3">
                          <label class="encabezado">Profesional:</label>
                      </div>
                      <div class="col-sm-9">
                          <input type="text"  class="form-control form-control-sm mifuente " id="frm_profesional_alcoholemia" name="frm_profesional_alcoholemia" value="<?php echo $profesional; ?>" placeholder='<?php echo $placeHolder; ?>' >
                      </div>
                      <!-- Observación -->
                      <div class="col-sm-3 mt-2">
                          <label class="encabezado">Observacion:</label>
                      </div>
                      <div class="col-sm-9 mt-2">
                          <input type="text"  class="form-control form-control-sm mifuente " id="frm_observacion_alcoholemia" name="frm_observacion_alcoholemia" value="<?=$datos[0]['dau_alcoholemia_apreciacion']?>" placeholder="Ingrese Observacion">
                      </div>
                  </div>
              </fieldset>
          </div>
      </div>
    </div>
    <hr>
     

  <!--
  **************************************************************************
                            Datos Glosa y Derivación
  **************************************************************************
  -->
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Glosa Egreso / Derivación</b></h6>                                      
  <div class="row">
    <div class="col-md-12" >
      <div class="row">
            <div class="col-sm-1">
                <label class="encabezado">Egreso</label>
            </div>
            <div class="col-sm-1">
              <input type="hidden" name="radio_egreso_h" id="frm_egreso_h" value="5">
              <div class="form-check ">
                <input type="radio"  class="form-check-input" name="radio_egreso" id="frm_egreso" value="5" <?php if($datos[0]['est_id']=='4'){ echo " checked";}?> disabled >
              </div>
            </div>
            <div class="col-sm-2"  id="contenidoIndicacion" >
              <input type="hidden" name="frm_indicacion_egreso_h" id="frm_indicacion_egreso_h" value="">
              <select class="form-control form-control-sm mifuente " id='frm_indicacion_egreso' name="frm_indicacion_egreso" > 
                <option value="">Seleccione</option>
                <?php
                for ( $i = 0; $i < count($listarIndicacion) ; $i++ ) {
                ?>
                    <option value="<?=$listarIndicacion[$i]['ind_egr_id']?>" <?php if($datos[0]['dau_indicacion_egreso']==$listarIndicacion[$i]['ind_egr_id']){ echo "selected";}?>> <?=$listarIndicacion[$i]['ind_egr_descripcion']?></option>
                <?php
                }
                ?>
              </select>
            </div>
            <div class="col-sm-1"  id="contenidoTituloServicio">
                <label class="encabezado mifuente">Destino</label>
            </div>
            <div class="col-sm-2"  id="contenidoServicio" >
              <input type="hidden" name="frm_servicio_h" id="frm_servicio_h" value="">
              <select class="form-control form-control-sm mifuente input-sm" id='frm_servicio' name="frm_servicio" >
                <option value="">Seleccione</option>
                <?php
                $id_servicio_slct;
                $desc_servicio_slct;
                if ( isset($datos[0]['servicio']) ) {
                  $id_servicio_slct     = $datos[0]['dau_cierre_servicio'];
                  $desc_servicio_slct   = $datos[0]['servicio'];
                } else {
                  $id_servicio_slct     = $servicioEgreso[0]['dau_ind_servicio'];
                  $desc_servicio_slct   = $servicioEgreso[0]['servicio'];
                }
                for ( $i = 0; $i < count($listarServiciosDau) ; $i++ ) {
                ?>
                  <option value="<?=$listarServiciosDau[$i]['id']?>"  <?php if ($desc_servicio_slct == $listarServiciosDau[$i]['servicio']) {echo "selected";}?>   ><?=$listarServiciosDau[$i]['servicio']?> </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-1"  id="contenidoTituloDestino">
              <label class="encabezado">Destino: </label>
            </div>
            <?php if ( $listaDestino[0]['ind_egr_id'] != "" ) { ?>
            <div class="col-sm-2">
              <div id="frm_control" name="frm_control" >
                <input type="hidden" name="frm_destionos_h" id="frm_destionos_h" value="">
                <select class="form-control form-control-sm mifuente input-sm" id="frm_alta_derivacion" name="frm_alta_derivacion">
                  <option value="0" disabled selected>Seleccione</option>
                  <?php
                  for ( $i = 0; $i < count($rsDerivacion); $i++ ) {
                  ?>
                    <option value="<?=$rsDerivacion[$i]['alt_der_id']?>"<?php if($listaDestino[0]['alt_der_id']==$rsDerivacion[$i]['alt_der_id']){echo"selected";}?>><?=$rsDerivacion[$i]['alt_der_descripcion']?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <?php } else { ?>
            <div class="col-sm-2">
              <div id="frm_control" name="frm_control" >
                <input type="hidden" name="frm_destionos_h" id="frm_destionos_h" value="">
                <select class="form-control form-control-sm mifuente input-sm" id="frm_alta_derivacion" name="frm_alta_derivacion">
                  <option value="0" disabled selected>Seleccione</option>
                  <?php for ( $i = 0; $i < count($rsDerivacion); $i++ ) { ?>
                    <option value="<?=$rsDerivacion[$i]['alt_der_id']?>"<?php if($listaDestino[0]['alt_der_id']==$rsDerivacion[$i]['alt_der_id']){echo"selected";}?>><?=$rsDerivacion[$i]['alt_der_descripcion']?></option>
                  <?php  } ?>
                </select>
              </div>
            </div> 
            <?php } ?>
            <?php if ( $listaDestino[0]['dau_ind_especialidad'] != "" ) { ?>
            <div id="frm_especialidad_oculto" name="frm_especialidad_oculto" >
              <div class="col-sm-3">
                <label class="encabezado">Especialidad:</label>
              </div>
              <div class="col-sm-3">
                <input type="hidden" name="frm_especialidad_h" id="frm_especialidad_h" value="">
                <?php
                $descripcionesEspecialidad = '';
                for ( $esp = 0; $esp < count($resEspecialidad); $esp++) {
                  if ( strpos($listaDestino[0]['dau_ind_especialidad'], $resEspecialidad[$esp]['ESPcodigo']) !== false ){
                    if ( empty($descripcionesEspecialidad) || is_null($descripcionesEspecialidad) ) {
                      $descripcionesEspecialidad = $resEspecialidad[$esp]['ESPdescripcion'];
                      continue;
                    }
                    $descripcionesEspecialidad = $descripcionesEspecialidad.' - '.$resEspecialidad[$esp]['ESPdescripcion'];
                  }
                }
                ?>
                <textarea id="frm_especialidad" name="frm_especialidad" class="form-control form-control-sm mifuente input-sm"><?php echo $descripcionesEspecialidad; ?></textarea>
              </div>
            </div>
      <?php  } else { ?>
        <div id="frm_especialidad_oculto" name="frm_especialidad_oculto" >
          <div class="col-sm-2">
            <label class="encabezado">Especialidad:</label>
          </div>
          <div class="col-sm-4">
            <input type="hidden" name="frm_especialidad_h" id="frm_especialidad_h" value="">
            <select id="frm_especialidad" name="frm_especialidad" class="form-control form-control-sm mifuente input-sm">
              <option value="0" disabled selected>Seleccione</option>
              <?php for ( $i = 0; $i < count($resEspecialidad); $i++ ) { ?>
              <option value="<?=$resEspecialidad[$i]['ESPcodigo']?>"<?php if($listaDestino[0]['dau_ind_especialidad']==$resEspecialidad[$i]['ESPcodigo']){echo "selected";}?>><?=$resEspecialidad[$i]['ESPdescripcion']?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      <?php } ?>
      <?php if ( $listaDestino[0]['dau_ind_aps'] != "" ) { ?>
        <div id="frm_aps_oculto" name="frm_aps_oculto" >
          <div class="col-sm-3">
              <label class="encabezado">APS:</label>
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="frm_aps_h" id="frm_aps_h" value="">
            <select id="frm_aps" name="frm_aps" class="form-control form-control-sm mifuente  input-sm" disabled>
              <option value="0" disabled selected>Seleccione</option>
              <?php
              for ( $i = 0; $i < count($rsAPS); $i++ ) {
              ?>
              <option value="<?=$rsAPS[$i]['ESTAcodigo']?>" <?php if($listaDestino[0]['dau_ind_aps']==$rsAPS[$i]['ESTAcodigo']){echo "selected";}?>><?=$rsAPS[$i]['ESTAdescripcion']?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      <?php  } else { ?>
        <div id="frm_aps_oculto" name="frm_aps_oculto" >
          <div class="col-sm-3">
            <label class="encabezado">APS:</label>
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="frm_aps_h" id="frm_aps_h" value="">
            <select id="frm_aps" name="frm_aps" class="form-control form-control-sm mifuente  input-sm">
              <option value="0" disabled selected>Seleccione</option>
              <?php
              for ( $i = 0; $i < count($rsAPS); $i++ ) {
              ?>
               <option value="<?=$rsAPS[$i]['ESTAcodigo']?>" <?php if($listaDestino[0]['dau_ind_aps']==$rsAPS[$i]['ESTAcodigo']){echo "selected";}?>><?=$rsAPS[$i]['ESTAdescripcion']?></option>
              <?php } ?>
            </select>
          </div>
        </div>
      <?php }  ?>
      <?php  if ( $listaDestino[0]['dau_ind_otros'] != "" ) { ?>
        <div id="frm_otros_oculto" nam="frm_otros_oculto" >
          <div class="col-sm-3">
            <label class="encabezado">Otros Motivo:</label>
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="frm_otros_h" id="frm_otros_h" value="">
            <textarea class="form-control input-sm form-control-sm mifuente " rows="4" cols="5" id="frm_otros" name="frm_otros" disabled placeholder="Indique Otros..."><?=$obtenerIndicacionEgreso[0]['dau_ind_otros'];?></textarea>
          </div>
        </div>
      <?php } else { ?>
        <div id="frm_otros_oculto" nam="frm_otros_oculto" >
          <div class="col-sm-3">
            <label class="encabezado">Otros Motivo:</label>
          </div>
          <div class="col-sm-3">
            <input type="hidden" name="frm_otros_h" id="frm_otros_h" value="">
            <textarea class="form-control input-sm form-control-sm mifuente " rows="4" cols="5" id="frm_otros" name="frm_otros" placeholder="Indique Otros..."><?=$obtenerIndicacionEgreso[0]['dau_ind_otros'];?></textarea>
          </div>
        </div>
      <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-1">
            <label class="encabezado mifuente">N.E.A</label>
        </div>
        <div class="col-sm-1">
          <div class="form-check mt-2">
              <input type="radio" class="form-check-input" name="radio_egreso" id="frm_nea" 
                  <?php if($datos[0]['est_id']=='4'){ echo "disabled";}?> value="7" disabled>
              <label class="form-check-label" for="frm_nea"></label>
          </div>
        </div>
        <div class="col-sm-10"  id="contenidoMotivoEgreso">
            <textarea class="form-control form-control-sm mifuente" rows="3" 
                id="frm_motivo_egreso" name="frm_motivo_egreso" placeholder="Ingrese motivo..." ></textarea>
        </div>
      </div>
      <?php if($datos[0]['dau_indicacion_egreso']==4 || $datos[0]['dau_indicacion_egreso']==6 ){?>
      <hr>
      <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Brazalete Identificatorio</b></h6>    
      <div class="row"   id="contenidoBrazalete">
          <div class="col-sm-2">
              <label class="encabezado">Tamaño brazalete:</label>
          </div>
          <div class="col-sm-2">
              <select class="form-control form-control-sm mifuente " id='frm_brazalette' name="frm_brazalette">
                  <option value="">Seleccione</option>   
                  <?php for ($i=0; $i <count($listaTipoAtencion) ; $i++) { ?>
                      <option value="<?=$listaTipoAtencion[$i]['ate_id']?>" <?php if($datos[0]['dau_atencion'] == $listaTipoAtencion[$i]['ate_id']){echo "selected";}?> ><?=$listaTipoAtencion[$i]['ate_descripcion']?> </option>
                  <?php   } ?>
              </select>
          </div>
          <div class="col-sm-2">
              <button id="<?=$datos[0]['dau_id']?>" class="btn  col-lg-12 btn-primary btn-sm mifuente generaBrazaleteBtn">Generar Brazalete</button>
          </div>
          <div class="col-sm-2" id="btn_genInfDEIS">
              <button id="<?=$datos[0]['dau_id']?>" class="btn col-lg-12 btn-primary btn-sm mifuente generaInformeDEIS">Generar Informe DEIS</button>
          </div>
      </div>
      <?php if($datos[0]['dau_indicacion_egreso']==6){?>

      <div  id="contenidoFallecimiento">
        <hr>
        <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i>Fallecimiento</b></h6>    
        <div class="row"  >         
            <div class="col-sm-2">                                
                <label class="encabezado">Fecha:</label>                            
            </div>
            <div class="col-sm-6">
                <div class="input-group">
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <?php  $fecha_def;
                    if ( isset($datos[0]['dau_defuncion_fecha']) ) {
                      $fecha_def = date('d-m-Y H:i', strtotime($datos[0]['dau_defuncion_fecha']));
                    }?>
                    <input type="hidden" name="frm_fallecimiento_fecha_h" id="frm_fallecimiento_fecha_h" value="<?=$fecha_def;?>">
                    <input type="text"  class="form-control form-control-sm mifuente  date" id="frm_fallecimiento_fecha" name="frm_fallecimiento_fecha" placeholder="DD-MM-AA" value="<?=$datos[0]['dau_defuncion_fecha']?>" disabled>
                </div>
            </div>
            <div class="col-sm-2">
                <label class="encabezado">Destino:</label>
            </div>
            <div class="col-sm-5">
                <div class="input-group">
                    <div id="frm_destino" name="frm_destino">
                        <input type="hidden" name="frm_radio_defuncion"   id="frm_radio_defuncion"            value="5">
                        <input type="hidden" name="frm_radio_destino"     id="frm_radio_destino"              value="<?=$destinoControl;?>">
                        <input type="radio" name="frm_destino_defuncion"  id="frm_destino_defuncion" disabled value="1" <?php if($destinoControl == 7){echo "checked";}?>> ANATO.PATOLOGICA<br>
                        <input type="radio" name="frm_destino_defuncion"  id="frm_destino_defuncion" disabled value="2" <?php if($destinoControl == 8){echo "checked";}?>> SERV.MED.LEGAL
                    </div>
                </div>
            </div>
        </div>
      </div>
          <?php } ?>
      <?php } ?>

    </div>
  </div>

</form>



<!--
################################################################################################################################################
                                                                    FUNCIONES PHP
-->


<?php
function existeAlcoholemia ( $fechaAlcoholemia ) {
  if ( $fechaAlcoholemia != '' && $fechaAlcoholemia != NULL && $fechaAlcoholemia ) {
    return true;
  }
  return false;
}
function profesionalQueRegistroAlcoholemia( $profesionalesAlcoholemia ) {
  if ( $profesionalesAlcoholemia['usuarioModificaAtencion'] != '' && $profesionalesAlcoholemia['usuarioModificaAtencion'] != NULL  ) {
    return $profesionalesAlcoholemia['usuarioModificaAtencion'];
  }
  return $profesionalesAlcoholemia['usuarioIniciaAtencion'];
}  ?>