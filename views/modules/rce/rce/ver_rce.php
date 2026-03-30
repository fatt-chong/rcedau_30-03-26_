<div id="modalPDFRCEdiv">
<?php
header('Access-Control-Allow-Origin: *');
error_reporting(0);
// session_start();
?>
<style type="text/css">
    /* Estilo para el GIF de carga */
        .loading-gif {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }

        /* Oculta el GIF cuando el iframe se ha cargado */
        .iframe-loaded .loading-gif {
            display: none;
        }
</style>
<div id="modalPDFRCE" class="embed-responsive embed-responsive-16by9">
    <img src="assets/img/loading-5.gif" alt="Cargando..." class="loading-gif">
        
<?php



require("../../../../config/config.php");
$permisos = $_SESSION['permiso'.SessionName];
require_once("../../../../class/Connection.class.php");     $objCon         = new Connection();     $objCon->db_connect();
require_once ("../../../../class/Util.class.php");          $objUtil        = new Util;
require_once("../../../../class/Dau.class.php" );           $objDau         = new Dau;
require_once("../../../../class/Rce.class.php" );          $objRce         = new Rce ;


$parametros['dau_id'] 	= $_POST['dau_id'];
$parametros['id_dau'] 	= $_POST['dau_id'];
$banderaLlamada         = $_POST['banderaLlamada'];
$datosPDFRCE            = $objDau->obtenerDatosBusquedaPDFRCE($objCon, $parametros['dau_id']);
$rsRCE                  = $objRce->obtenerIdRCESegunDAU($objCon, $parametros['dau_id']);
$_POST['rce_id']        = $rsRCE['regId'];

$idPaciente             = $datosPDFRCE['id_paciente'];
$fechaDau               = date("d-m-Y",strtotime($datosPDFRCE['dau_indicacion_egreso_fecha']));
$horaDau                = date("H-i-s",strtotime($datosPDFRCE['dau_indicacion_egreso_fecha']));
$anio                   = date("Y",strtotime($datosPDFRCE['dau_indicacion_egreso_fecha']));
$mes                    = date("m",strtotime($datosPDFRCE['dau_indicacion_egreso_fecha']));
$URL                    = "http://".FTP_IP."/rce/urgencia/".$anio."/".$mes."/Informe_RCE_".$parametros['dau_id']."_".$idPaciente."_".$fechaDau."_".$horaDau.".pdf";
$file_headers           = @get_headers($URL);

$fechaDau               = date("d-m-Y",strtotime ($datosPDFRCE['dau_admision_fecha']));
$horaDau                = date("H-i-s",strtotime($datosPDFRCE['dau_admision_fecha']));
$anio                   = date("Y",strtotime($datosPDFRCE['dau_admision_fecha']));
$mes                    = date("m",strtotime($datosPDFRCE['dau_admision_fecha']));
$URL2                   = "http://".FTP_IP."/rce/urgencia/".$anio."/".$mes."/Informe_RCE_".$parametros['dau_id']."_".$idPaciente."_".$fechaDau."_".$horaDau.".pdf";
$file_headers2 = @get_headers($URL2);
if ( $banderaLlamada == 'mantenedor' ) {

    //echo "crear archivo desde mantenedor";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rce.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'&banderaLlamada=altaUrgencia" allowfullscreen ></iframe>';

} else if ( $banderaLlamada == 'altaUrgenciaCompleto' ) {

    //echo "visualizar rce médico";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rceMedico.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'" allowfullscreen ></iframe>';

} else if ( $banderaLlamada == 'altaUrgencia' ) {

    //echo "crear archivo";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rce.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'&banderaLlamada=altaUrgencia" allowfullscreen ></iframe>';

} else if ( $file_headers[0] == 'HTTP/1.1 200 OK' ) {

    //echo "visualizar";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rce.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'" allowfullscreen ></iframe>';

} else if ( $file_headers2[0] == 'HTTP/1.1 200 OK' ) {

    //echo "visualizar archivo antiguo";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" name="pdfRCE" width="800"  src="'.$URL2.'" allowfullscreen ></iframe>';

} else if ( ($datosPDFRCE['est_id'] == 4 || $datosPDFRCE['est_id'] == 5 || $datosPDFRCE['est_id'] == 6 || $datos['est_id'] == 7 ) && ($file_headers[0] != 'HTTP/1.1 200 OK' || $file_headers2[0] != 'HTTP/1.1 200 OK') ) {

    //echo "crear archivo";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rce.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'&banderaLlamada=altaUrgencia" allowfullscreen ></iframe>';

} else {

    //echo "visualizar";
    echo '<iframe onload="hideLoading()" class="embed-responsive-item" id="pdfRCE" width="800"  src="'.PATH.'/views/modules/rce/rce/detalle_rce.php?rce_id='.$_POST['rce_id'].'&dau_id='.$_POST['dau_id'].'" allowfullscreen ></iframe>';

}
?>
<script>
    function hideLoading() {
        document.getElementById('modalPDFRCE').classList.add('iframe-loaded');
    }
</script>
</div>