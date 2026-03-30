<?php

require("../../../../config/config.php");
require_once('../../../../class/Util.class.php');       $objUtil = new Util;

$parametros = $objUtil->getFormulario($_POST);
$version    = $objUtil->versionJS();
?>

<script type="text/javascript" src="<?php echo PATH; ?>/controllers/client/rce/hospitalAmigo/acompaniante.js?v=<?php echo $version; ?>"></script>

<?php
if ($objUtil->existe($_POST)) {
  $parametros = $objUtil->getFormulario($_POST);
  echo '<div id="divPOSTAcompaniante">';
  if ($objUtil->existe($parametros["idDau"])) {
    //Id dau
    echo '<input type="hidden" id="idDau" name="idDau" value="' . $parametros['idDau'] . '">';
  }
  echo '</div>';
}
?>
<div class="row">
  <div class="col-lg-12">
    <form id="frm_acompaniante">
      <div class="form-group row">
        <label for="frm_entregaInformacion" class="col-lg-5 col-form-label encabezado">
          ¿Se entrega información médica?
        </label>
        <div class="col-lg-7">
          <select class="form-control form-control-sm mifuente col-lg-12" id="frm_entregaInformacion">
            <option selected disabled> Seleccione </option>
            <option value="S"> Si </option>
            <option value="N"> No </option>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado"> Motivo </label>
        <div class="col-lg-7"> 
          <textarea class="form-control form-control-sm mifuente" id="frm_motivo" rows="4"></textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
          Nombre familiar o acompañante que se le entregó la información
        </label>
        <div class="col-lg-7">
          <input type="input" class="form-control form-control-sm mifuente" id="frm_nombreFamiliarOAcompaniante">
        </div>
      </div>
      <div class="form-group row">
        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
          Hora en que se entregó la información médica
        </label>
        <div class="col-lg-7">
          <input type="input" class="form-control form-control-sm mifuente" id="frm_horaEntregaInformacion" value="<?php echo date("H:i:s"); ?>" readonly>
        </div>
      </div>
      <div class="form-group row">
        <label for="frm_motivo" class="col-lg-5 col-form-label encabezado">
          Nombre Médico
        </label>
        <div class="col-lg-7">
          <input type="input" class="form-control form-control-sm mifuente" id="frm_nombreMedicoTratante" readonly>
        </div>
      </div>
    </form>
  </div>
</div>
