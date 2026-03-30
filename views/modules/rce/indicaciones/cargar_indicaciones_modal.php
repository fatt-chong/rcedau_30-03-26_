<div class="scrollModal" style="overflow-x: hidden;"> 
<?php
session_start();

ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require_once("../../../../class/Util.class.php");               $objUtil             = new Util;
require_once("../../../../class/Connection.class.php");         $objCon              = new Connection();     $objCon->db_connect();
require_once("../../../../class/Dau.class.php" );               $objDau              = new Dau;
require_once("../../../../class/Categorizacion.class.php" );    $objCategorizacion   = new Categorizacion;
require_once("../../../../class/Admision.class.php" );          $objAdmision         = new Admision;


$version    = $objUtil->versionJS();
$parametros = $objUtil->getFormulario($_POST);
$dau_id     = $parametros['dau_id'];
$rce_id     = $parametros['rce_id'];

$_SESSION['indicaciones']['post']['cargarIndicacionesModal'] = $parametros;
// print('<pre>'); print_r($parametros); print('</pre>');
// print('<pre>'); print_r($_SESSION['indicaciones']['post']['cargarIndicacionesModal']); print('</pre>');
?>


<!-- Cargar Tipo Pacientes en Div -->
<script>
	ajaxContentFast(raiz+'/views/modules/rce/indicaciones/indicaciones_modal.php','dau_id='+$("#dau_id").val()+'&rce_id='+$("#rce_id").val(),'#cargarIndicacionesModal','', true);
</script>


<!-- Div Cargar Indicaciones -->
<div class="alert alert-warning" role="alert" style="text-align: center; font-size:13px;">

	IMPORTANTE, Examen para Enfermedades Respiratorias (Test COVID-19) debe ser ingresado en <b>PROCEDIMIENTO ( 01 Examen COVID19 )</b>

</div>

<br>


<div id = 'cargarIndicacionesModal'></div>
</div>
