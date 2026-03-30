<?php
error_reporting(0);
session_start();
require     ("../../../../config/config.php");
require_once('../../../../class/Connection.class.php');  $objCon  = new Connection; $objCon->db_connect();
require_once('../../../../class/Util.class.php'); 		 $objUtil = new Util;
$objCon->db_connect();
$version    = $objUtil->versionJS();
$parametros = $objUtil->getFormulario($_POST);

if ( $parametros['banderaTipoTurno'] == 'entregarTurno' ) {
?>
    <script type="text/javascript" src="<?=PATH?>/controllers/client/rce/cambioTurno/cambioTurno.js?v=<?=$version;?>" ></script>
<?php
}
?>
<script>
    validar('#txt_observacionCambioTurno', "letras_numeros_caracteres");
</script>
<!-- Despliegue texto área observación -->
<div id="observacionCambioTurno">
    <div class="panel panel-default">
        <fieldset>
            <div class="col-md-12">
                <form id="frmObservacionCambioTurno" name="frmObservacionCambioTurno" class="formularios" role="form" method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="encabezado">Observación: </label>
                        </div>
                        <div class="col-md-12">
                            <textarea id="txt_observacionCambioTurno" name="txt_observacionCambioTurno" class="form-control form-control-sm mifuente " maxlength="500" cols="40" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
	</div>
    <!-- Campos ocultos -->      
    <input type="hidden" name="dauId"                           id="dauId"                          value="<?php echo $parametros['dau_id']; ?>" >
    <input type="hidden" name="rutMedicoTratanteEntrega"        id="rutMedicoTratanteEntrega"       value="<?php echo $parametros['rutMedicoTratanteEntrega']; ?>" >
    <input type="hidden" name="codigoMedicoTratanteEntrega"     id="codigoMedicoTratanteEntrega"    value="<?php echo $parametros['codigoMedicoTratanteEntrega']; ?>" >
</div>	