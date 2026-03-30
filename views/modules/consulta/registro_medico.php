<?php
ini_set('max_execution_time', 600); 
ini_set('memory_limit', '128M'); 
error_reporting(0);
require("../../../config/config.php"); 
require_once('../../../class/Connection.class.php');  $objCon      = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');        $objUtil     = new Util;
require_once('../../../class/Consulta.class.php');    $objConsulta = new Consulta;
require_once('../../../class/Dau.class.php');         $objDau      = new Dau;
require_once('../../../class/Cierre.class.php');      $objCierre   = new Cierre;

$parametros['Iddau']  = $_POST['Iddau'];
$datos                = $objConsulta->consultaDAU($objCon,$parametros);
// transexual
// nombreSocial
$transexual_bd        = $datos[0]['transexual'];
$nombreSocial_bd      = $datos[0]['nombreSocial'];
$nombrePaciente       = $datos[0]['nombres']." ".$datos[0]['apellidopat']." ".$datos[0]['apellidomat'];
$nombreLabel          = 'Paciente';
$InfoNombre           = $objUtil->vista_dau_input_label($transexual_bd,$nombreSocial_bd,$nombrePaciente,$nombreLabel,'S');
$version              = $objUtil->versionJS();
?>
<!-- 
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/consulta/registro_medico.js?v=<?=$version;?>"></script>
<!-- 
################################################################################################################################################
                                                                      ESTILOS
-->
<style>
  .ui-autocomplete{
    z-index:1050;
    width: 45%  !important;
  }
</style>



<!-- 
################################################################################################################################################
                                                                  DESPLIEGUE FORMULARIO
-->
<form id="frm_registro_medico" name="frm_registro_medico" class="formularios form-horizontal" role="form" method="POST">
  <!-- 
	**************************************************************************
                               Campos Ocultos
	**************************************************************************
	--> 
  <input type="hidden" id="idDau" 		name="idDau" value="<?=$_POST['Iddau'];?>">
  <!-- 
	**************************************************************************
                               Datos del Paciente
	**************************************************************************
	-->  
 <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Datos del paciente</b></h6>
  <div class="row" >
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">N° DAU </label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['dau_id']?></span>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">Fecha</label>
      </div>
      <div class="col-md-1 mifuente">
          <label class="control-label encabezado">:&nbsp;<?=date('d-m-Y',strtotime($datos[0]['dau_admision_fecha']))?></label>
      </div>
      <div class="col-md-2 mifuente text-right">
          <label class="control-label encabezado">Hora</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=date('H:i',strtotime($datos[0]['dau_admision_fecha']))?>  </span>
      </div>
      <div class="col-md-1 mifuente">
         <label class="control-label encabezado">Rut</label>
      </div>
      <div class="col-md-2 mifuente">
         <span>:&nbsp; <?php 
              if ( $datos[0]['rut'] ) {

                echo $objUtil->formatearNumero($datos[0]['rut']).'-'.$objUtil->generaDigito($datos[0]['rut']);
              
              } else {

                echo $datos[0]['rut_extranjero']." (Nro documento)"; 
              
              } 
              ?>   </span>
      </div>
  </div>
  <div class="row" >
        <!-- Paciente -->
        <div class="col-md-1 mifuente">
        <label class="control-label encabezado"><?=$InfoNombre['label']?></label>
        </div>
        <div class="col-md-5 mifuente">
        <span>:&nbsp;<?=$InfoNombre['input']?></span>
        </div>
        <div class="col-md-1 mifuente text-righ">
          <label class="control-label encabezado">Cta Cte</label>
        </div>
        <div class="col-md-2 mifuente">
         <span>:&nbsp;<?=$datos[0]['idctacte']?></span>
        </div>
        <div class="col-md-1  mifuente">
          <label class="control-label encabezado">Categorización</label>
        </div>
        <div class="col-md-1 mifuente">
          <span>:&nbsp;<?php
            if ( $datos[0]['dau_categorizacion_actual'] ) { 
              
              echo $datos[0]['dau_categorizacion_actual'];
              
            } else {
              
              echo "S/C";}
              
            ?></span>
        </div>
        <?php  if ( $datos[0]['dau_hipotesis_diagnostica_inicial'] != "" ) {  ?>
        <div class="col-md-12  mifuente">
          <label class="control-label encabezado">Hipótesis Diagnóstica</label>
        </div>
        <div class="mifuente col-md-12">
        <textarea class="form-control" rows="3" id="comment" disabled="disabled"><?= $datos[0]['dau_hipotesis_diagnostica_inicial']?></textarea>
        </div>
        <?php } ?>
  </div>
  <hr> 
  <!-- 
	**************************************************************************
                                Hipótesis Diagnóstica
	**************************************************************************
	-->  


    <?php 
    if ( $datos[0]['idcie10'] == "" ) {
    ?>    
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Hipótesis Diagnóstica</b></h6>
        <div class="row"  id="contenidoBusquedaCie10">
          <div class=" col-md-12" >  
            <table class="table table-condensed table-hover top mifuente" >
              <thead>
                <tr class="detalle">
                  <th class="text-center">Código</th>
                  <th  class="text-center" width="50%">Descripción</th>                                
                </tr>
              </thead>
              <tbody id="tbItem">
              </tbody>
            </table>
          </div>
          <div class=" col-md-11">                
            <input type="text" name="frm_item" id="frm_item" class="form-control form-control-sm mifuente" value="" placeholder="Ingrese Cie10 urgencia">  
          </div>
          <div class=" col-md-1" >
            <button type="button" id="btnAgregarLinea" class="btn btn-success" alt="Agregar Cie10" title="Agregar CIE10"><i class="fas fa-plus-square "></i></button>
          </div>
        </div>
        <div id="alertaMensajeProd">
          <input type="text" id="mensajeAlert" hidden>
        </div>
        
    <?php 
    } else { ?>
     <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Hipótesis Diagnóstica</b></h6>  
        <div class="row" > 
          <div class=" col-md-12" >  
            <table class="table table-condensed table-hover top mifuente" >
              <thead>
                <tr class="detalle">
                  <th class="text-center">Codigo</th>
                  <th class="text-center"width="50%">Descripción</th>
                  <th class="text-center" ></th>
                </tr>
              </thead>
              <tbody id="tbItem">
              <?php 
              for ( $i = 0; $i < count($datos); $i++ ) {
              ?>
                <tr class="" id="<?=$datos[$i]["idcie10"]?>">
                    <td class="my-1 py-1 mx-1 px-1 mifuente text-center" ><?=$datos[$i]["idcie10"];?></td>
                    <td class="my-1 py-1 mx-1 px-1 mifuente text-center" ><?=$datos[$i]["cie10_nombre"];?></td>
                    <td class="my-1 py-1 mx-1 px-1 mifuente text-center" ><button type="button" align="right" class="btn btn-sm mifuente btn-danger puntero removerCR" id="cod<?=$datos[$i]["idcie10"]?>" >
                      <i class="fas fa-trash "></i></button></td>
                  </tr>
              <?php  }  ?>
              </tbody>
            </table>
          </div>
          <div class=" col-md-11"  >            
            <input type="text" name="frm_item" id="frm_item" class="form-control form-control-sm mifuente" value="" placeholder="Ingrese Cie10 urgencia">
          </div>
          <div class=" col-md-1">
            <button type="button" id="btnAgregarLinea" class="btn btn-sm mifuente btn-success" alt="Agregar Cie10" title="Agregar CIE10"><i class="fas fa-plus-square "></i></button>
          </div>
        </div>
    <?php  } ?>
  </div>
</form> 