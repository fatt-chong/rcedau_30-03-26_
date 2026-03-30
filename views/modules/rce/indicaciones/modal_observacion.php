<?php
session_start();
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); $objCon      = new Connection;$objCon->db_connect();
require_once('../../../../class/RegistroClinico.class.php'); $objRce = new RegistroClinico;
require_once('../../../../class/Util.class.php'); $objUtil = new Util;
$parametros             	= $objUtil->getFormulario($_POST);
$parametros['indicacion_id']= $parametros['ind_id'];
?>
<div id="contenidoImagenologia">
    <div class="panel panel-default">
        <fieldset>
            <div class="col-md-12">
            <form id="frm_modal_observacion" name="frm_modal_observacion" class="formularios" role="form" method="POST">
	            <div class="row">
	            	<div class="col-md-12">
	            	    <label class="encabezado">Observación: </label>
	            	</div>
	            	<div class="col-md-12">
	            	    <textarea id="frm_observacion_aplica" name="frm_observacion_aplica" class="form-control form-control-sm mifuente" maxlength="500" cols="40" rows="3"></textarea>
	            	</div>
	            </div>
	        </form>
            </div>
        </fieldset>
	</div>            
</div>	