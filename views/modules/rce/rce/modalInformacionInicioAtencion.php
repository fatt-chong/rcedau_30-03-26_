<?php
error_reporting(0);
require("../../../../config/config.php");
require_once ("../../../../class/Util.class.php");          $objUtil        = new Util;
require_once("../../../../class/Connection.class.php");     $objCon         = new Connection();
require_once("../../../../class/Dau.class.php" );           $objDetalleDau  = new Dau;

$objCon->db_connect();
$parametros                 = $objUtil->getFormulario($_POST);
$datosDau                   = $objDetalleDau->getDatosDauInicioAtencion($objCon,$parametros);
?>
<!-- Formulacio ver información inicio atención -->
<form id="frm_Informacion_Inicio_Atencion" name="frm_Informacion_Inicio_Atencion" class="formularios" method="POST">
    <fieldset style="padding: 0px">
        <div class="col-md-12">
            <div class="row">
                <label class="text-secondary ml-3"><i class="fas fa-minus mr-1" style="color: #59a9ff;"></i> La atención ya fue iniciada por el siguiente profesional:</label>
            </div>
            <div class="row">
                <!-- Fecha Inicio -->
                <div class="col-md-6">
                    <label class="encabezado">Fecha de Inicio de  Atención</label>
                    <input type="input"  class="form-control form-control-sm mifuente12" id="frm_informacion_fecha_date" name="frm_informacion_fecha_date" value="<?php echo date("d/m/Y",strtotime($datosDau[0]['dau_inicio_atencion_fecha']));?>" disabled>
                </div>
                <!-- Hora Inicio -->
                <div class="col-md-6">
                    <label class="encabezado">Hora de Inicio de Atención</label>
                    <input type="input"  class="form-control form-control-sm mifuente12" id="frm_informacion_hora_date" name="frm_informacion_hora_date" value="<?php echo date("H:i:s",strtotime($datosDau[0]['dau_inicio_atencion_fecha']));?>" disabled>
                </div>
            </div>
             <div class="row">
                <!-- Realizado Por -->
                <div class="col-md-12">
                    <label class="encabezado">Realizado Por</label>
                    <input type="input"  class="form-control form-control-sm mifuente12" id="frm_informacion_realizado_por" name="frm_informacion_realizado_por" value="<?=$datosDau[0]['nombreusuario'];?>" disabled>
                </div>
            </div>
        </div>
    </fieldset>
</form>