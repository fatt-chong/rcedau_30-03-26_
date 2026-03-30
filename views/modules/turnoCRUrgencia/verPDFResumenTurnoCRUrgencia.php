<?php
error_reporting(0);
require_once("../../../config/config.php");
?>
<div id="modalPDFResumenTurnoCRUrgencia" class="embed-responsive embed-responsive-16by9">
    <?php
	require("../../../config/config.php");
    $parametros['idTurnoCRUrgencia'] = $_POST['idTurnoCRUrgencia'];
    $parametros['fechaEntregaTurno'] = $_POST['fechaEntregaTurno'];
    $anio = date("Y",strtotime($parametros['fechaEntregaTurno']));
    $mes  = date("m",strtotime($parametros['fechaEntregaTurno']));
    # $URL  = "http://".FTP_IP."/dauEntregaTurnoUrgencia/".$anio."/".$mes."/resumenTurnoUrgencia_Numero(".$parametros['idTurnoCRUrgencia'].").pdf";
     $URL  = "http://".FTP_IP."/dauEntregaTurnoUrgencia/".$anio."/".$mes."/resumenTurnoUrgencia_Numero(".$parametros['idTurnoCRUrgencia'].").pdf";
    ?>

    <iframe class="embed-responsive-item" id="pdfRCE" name="pdfRCE" width="800" height="450" src="<?php echo $URL; ?>" allowfullscreen ></iframe>

</div>