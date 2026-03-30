<?php
session_start();
error_reporting(0);
require("../../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
require_once ("../../../../class/Util.class.php");      $objUtil       = new Util;

$parametros   = $objUtil->getFormulario($_POST);
$version      = $objUtil->versionJS();
?>



<!--
################################################################################################################################################
CARGA JS
-->
<script type="text/javascript" src="<?= PATH ?>/controllers/client/mapa_piso_full/lpp.js?v=<?= $version; ?>133"></script>



<!--
################################################################################################################################################
VARIABLES ENVIADAS POR POST
-->
<?php
if ($objUtil->existe($_POST)) {
  $parametros = $objUtil->getFormulario($_POST);

  echo '<div id="divPOSTLPP">';

  if ($objUtil->existe($parametros["idDau"])) {
    //Id dau
    echo '
      <input
        type="hidden"
        id="idDau"
        name="idDau"
        value="' . $parametros['idDau'] . '"
      >
    ';
  }

  echo '</div>';
}
?>



<!--
################################################################################################################################################
DESPLIEGUE FORMULARIO
-->
<style>
  .tabla-scroll {
    width: 100%;
  }

  .tabla-scroll tbody {
    display: block;
    max-height: 200px;
    overflow-y: auto;
  }

  .tabla-scroll thead,
  .tabla-scroll tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
  }
</style>

<form id="frm_lpp">
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Valoración de la Piel</h6>
  <div class="form-group row">
    <label for="frm_valoracionPiel" class="col-lg-5 col-form-label mifuente" >
      Valoración de la Piel
    </label>
    <div class="col-lg-7">
      <select class="form-control form-control-sm mifuente selectpicker " id="frm_valoracionPiel"  multiple >
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label
      for="frm_zonaAfectada"
      class="col-lg-5 col-form-label mifuente">
      Zona Afectada
    </label>
    <div class="col-lg-7">
      <input
        type="text"
        class="form-control form-control-sm mifuente "
        id="frm_zonaAfectada"
      >
    </div>
  </div>
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Evaluación de Riesgo LPP</h6>
  <div class="form-group row">
    <label for="frm_puntajeEvalucion" class="col-lg-5 col-form-label mifuente">
      Puntaje de Evaluación
    </label>
    <div class="col-lg-7">
      <input type="number" class="form-control form-control-sm mifuente " id="frm_puntajeEvalucion" min="0" max="28" placeholder="Entre 0 a 28">
    </div>
  </div>
  <div class="form-group row">
    <label for="frm_riesgo" class="col-lg-5 col-form-label mifuente">
      Riesgo LPP
    </label>
    <div class="col-lg-7">
      <select
        class="form-control form-control-sm mifuente "
        id="frm_riesgo"
      >
      </select>
    </div>
  </div>
  <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Aplicación Medidas Preventiva</h6>
  <div class="form-group row">
    <label
      for="frm_aplicacionSEMP"
      class="col-lg-5 col-form-label mifuente"
    >
      Aplicación de SEMP
    </label>
    <div class="col-lg-7">
      <select
        class="form-control form-control-sm mifuente "
        id="frm_aplicacionSEMP"
      >
      </select>
    </div>
  </div>
  <div class="form-group row">
    <label
      for="frm_cambioPosicion"
      class="col-lg-5 col-form-label mifuente"
    >
      Cambio de Posición
    </label>
    <div class="col-lg-7">
      <select
        class="form-control form-control-sm mifuente "
        id="frm_cambioPosicion"
      >
      </select>
    </div>
  </div>
  <div id="divRegistroEjecucion">
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Registro Ejecución Aplicación de Medidas</h6>
    <div class="form-group row">
      <div class="col-lg-12">
        <textarea class="form-control form-control-sm mifuente " id="frm_registroEjecucion" rows="3"></textarea>
      </div>
    </div>
  </div>
  <div  id="divLOGRegistrosEjecucion" style="display:none;" >
    <hr>
    <h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> LOG Registros Ejecución Aplicación de Medidas</h6>
    <div class="col-lg-12">
      <table class="table table-bordered table-hover table-condensed tablasHisto tabla-scroll" id="tablaLOGRegistrosEjecucion" >
        <thead style="width:100% !important">
          <tr>
            <th  style="text-align:center;width:50%;" class=" font-weight-bold  mifuente11" >
              Registro
            </th>
            <th  style="text-align:center;width:25%;" class=" font-weight-bold  mifuente11">
              Fecha
            </th>
            <th  style="text-align:center;width:25%;" class=" font-weight-bold  mifuente11">
              Usuario
            </th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</form>
