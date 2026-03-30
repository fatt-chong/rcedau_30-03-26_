<?php
session_start();
require("../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];
error_reporting(0);
require_once ("../../../class/Util.class.php"); 		 	 $objUtil            = new Util;
require_once("../../../class/Connection.class.php"); 	 	 $objCon        	 = new Connection();
require_once("../../../class/RegistroClinico.class.php" );   $objRegistroClinico = new RegistroClinico;
require_once('../../../class/Imagenologia.class.php');       $objRayos           = new Imagenologia;
require_once('../../../class/Laboratorio.class.php');        $objLaboratorio     = new Laboratorio;
// require("../../../config/config.php");

$objCon->db_connect();
$parametros 		    = $objUtil->getFormulario($_POST);	
$resRce                 = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['rce_id']   = $resRce[0]['regId'];
$rsTipoExmamen          = $objRayos->getTipoExamen($objCon);
$listaServicios         = $objRegistroClinico->listarServiciosIndicaciones($objCon);
$listadoExaLab          = $objLaboratorio->getExamenesLaboratorio($objCon);
// $listadoIndicaciones    = $objRegistroClinico->listarIndicacionesRCE($objCon,$parametros);
?>



<!-- 
################################################################################################################################################
                                                                JS
-->
<script>
	$(document).ready(function(){
		ajaxContent(raiz+'/views/modules/enfermera/detalleIndicaciones.php','dau_id='+<?php echo $parametros['dau_id']; ?>+'&regId='+<?php echo $parametros['rce_id'];?>,'#contenidoImagenologia_'+<?php echo $parametros['dau_id']; ?>,'', true);
	});
	// $('.modal-body').css('max-height','calc(100vh - 210px)');
	// $('.modal-body').css('overflow','auto');
</script>



<!-- 
################################################################################################################################################
                                                            DESPLIGUE CONTENIDO EN DIVS
-->
<div>

	<input id="banderaDetalleDau" type="hidden" name="" value="<?=$_POST['banderaDetalleDau'];?>">

</div>

<div id="contenidoImagenologia_<?=$parametros['dau_id']?>"></div>
