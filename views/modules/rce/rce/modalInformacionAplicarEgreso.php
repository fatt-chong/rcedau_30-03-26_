<?php

require_once ("../../../../class/Util.class.php");              $objUtil        = new Util;
require_once("../../../../class/Connection.class.php");         $objCon         = new Connection();
require_once("../../../../class/Dau.class.php" );               $objDetalleDau  = new Dau;
require("../../../../config/config.php");

$objCon->db_connect();
$parametros                 = $objUtil->getFormulario($_POST);
$datosDau                   = $objDetalleDau->getDatosDauAplicarEgreso($objCon,$parametros);
?>

<!-- Formulacio ver información inicio atención -->
<form id="frm_Informacion_Aplicar_Egreso" name="frm_Informacion_Aplicar_Egreso" class="formularios mr-3 ml-3" role="form" method="POST">
    <div class="row mb-2">
        <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> Información Aplicar Egreso</label>
    </div>
    <?php if ( is_null($datosDau) || empty($datosDau) ) { ?>
    <div class="row">
        <div class="alert alert-secondary col-12 text-center" role="alert">
          Debe aplicar <b>Indicación Egreso</b> antes de <b>Aplicar Egreso</b>
        </div>
    </div>
    <?php } else { ?>
    <div class="row">
        <div class="alert  col-12 text-center" role="alert">
            La aplicación de egreso ya fue iniciada por el siguiente profesional
            <br> <br>
            <div class="row">
                <!-- Fecha Inicio -->
                <div class="col-md-6">
                    <label class="light mifuente">Fecha Apliación Egreso</label>
                    <input type="input"  class="form-control form-control-sm mifuente text-center" id="frm_informacion_fecha_date" name="frm_informacion_fecha_date" value="<?php echo date("d/m/Y",strtotime($datosDau[0]['dau_indicacion_egreso_aplica_fecha']));?>" disabled>
                </div>
                <!-- Hora Inicio -->
                <div class="col-md-6">
                    <label class="light mifuente">Hora Aplicación Egreso</label>
                    <input type="input"  class="form-control form-control-sm mifuente text-center" id="frm_informacion_hora_date" name="frm_informacion_hora_date" value="<?php echo date("H:i:s",strtotime($datosDau[0]['dau_indicacion_egreso_aplica_fecha']));?>" disabled>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</form>