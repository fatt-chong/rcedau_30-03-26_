<?php
session_start();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
error_reporting(0);
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');   $objCon   = new Connection;$objCon->db_connect();
require_once("../../../../class/Util.class.php");           $objUtil    = new Util;
?>




<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/recetaGes/recetaGES.js?v=<?php echo $version; ?>23"></script>



<!--
################################################################################################################################################
                                                       	    VARIABLES ENVIADAS POR POST
-->
<?php
if ($objUtil->existe($_POST)) {
  $parametros = $objUtil->getFormulario($_POST);
  echo '<div id="recetaGES">';
  //Id dau
  echo '<input type="hidden" id="idDau" name="idDau" value="' . $parametros['idDau'] . '">';
  //Id rce
  echo '<input type="hidden" id="idRCE" name="idRCE" value="' . $parametros['idRCE'] . '">';
  //Id paciente
  echo '<input type="hidden" id="idPaciente" name="idPaciente" value="' . $parametros['idPaciente'] . '">';
  echo '</div>';
}

?>

<!--
################################################################################################################################################
                                                      	    DESPLIEGUE HOJA HOSPITALIZACIÓN
-->
<div class="container-fluid">
  <div id='divFormularioRecetaGES' class="row">
    <div class="col-lg-12">
      <form id="frm_receteGES" name="frm_receteGES" role="form" method="POST">
        <table id="tablaRecetaGES" class="table table-hover table-striped  ">
          <thead >
            <tr class="">
              <th width="50%" class=" mifuente13 text-center" >MEDICAMENTO</th>
              <th width="25%" class=" mifuente13 text-center" >DOSIS</th>
              <th width="25%" class=" mifuente13 text-center" >NÚMERO DÍAS</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <br />
        <table width="100%">
          <tbody>
            <tr>
              <td width="5%" class="text-center mifuente11  text-secondary">(*)</td>
              <td width="90%" class="mifuente11  text-secondary">SÓLO ADULTOS</td>
            </tr>
            <tr>
              <td width="5%" class="text-center mifuente11  text-secondary">(**)</td>
              <td width="90%" class="mifuente11  text-secondary">SÓLO ASMA, EPOC Y MENORES DE 5 AÑOS</td>
            </tr>
            <tr>
              <td width="5%" class="text-center mifuente11  text-secondary">(***)</td>
              <td width="90%" class="mifuente11  text-secondary">SÓLO ASMA, EN MAYORES DE 15 AÑOS</td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
