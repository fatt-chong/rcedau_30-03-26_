<?php
error_reporting(0);
require("../../../config/config.php");
require_once('../../../class/Util.class.php');      	$objUtil       = new Util;

$version    = $objUtil->versionJS();
?>

<script type="text/javascript">
$.fn.delayPasteKeyUp = function(fn, ms){
	var timer = 0;
	$(this).on("propertychange input", function(){
		clearTimeout(timer);
		timer = setTimeout(fn, ms);
	});
};

</script>
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/identificacion/identificarse.js?v=<?=$version;?>"></script>
<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/identificacion/identificador.js?v=<?=$version;?>"></script>

<form id="frm_codigoBarra"  class="formularios" name="frm_codigoBarra"   role="form" method="POST" onsubmit="return false">
	<input type="hidden"  name="accessRequest"  			id="accessRequest"   			value="<?=$_POST['accessRequest']?>">	
	<input type="hidden"  name="rutCambioTurno"  			id="rutCambioTurno"   			value="<?=$_POST['rutCambioTurno']?>">	
	<input type="hidden"  name="codigoBarraCambioTurno"  	id="codigoBarraCambioTurno"   	value="<?=$_POST['codigoBarraCambioTurno']?>">
	<input type="hidden"  name="banderaEntregaTurno"  		id="banderaEntregaTurno"   		value="<?=$_POST['banderaEntregaTurno']?>">
	<div class="alert alert-danger" id="gifPistola">
		<center>
			<label style="font-size: 18px;">Para continuar es necesario identificarse, acerque su credencial al lector</label> <img src="<?=PATH?>/assets/img/codigo-barra.gif" width="50%"/>
		</center>
	</div>
	<div class="row" id="divIdentificacionSinPistola">
		<div class="col-md-4">
			<input type="text" id="inputUsuarioModalSinPistola" name="inputUsuarioModalPistola" class="form-control form-control-sm mifuente" style="margin-left:20px" placeholder="Ingresar Usuario">
		</div>
		<div class="col-md-4">
			<input type="password" id="inputContraseniaModalSinPistola" name="inputContraseniaModalPistola" class="form-control form-control-sm mifuente" style="margin-left:20px" placeholder="Ingresar Contraseña">
		</div>
		<div class="col-md-4">
			<input id="btnIdentificacionModalSinPistola" name="btnIdentificacionModalSinPistola" type="button"  class="btn btn-sm btn-outline-primary col-lg-12 verindicacionaplica pull-right" value="Identificarse" > 
		</div>
	</div>
	<div class="row" id="divOtraSeccion">
		<div class="col-xs-12 col-md-12">
			<center>
				<div class="col-lg-12"  id="identificacionAlerta"></div>
				<div class="col-lg-12"  id="identificacionAlertaError"></div>
				<div class="col-lg-12"  id="identificacionAlertaErrorUsuarioNoExiste"></div>
			</center>
		</div>
		<div class="col-xs-12 col-md-0">
			<input class="form-control"  name="codigoBarra" id="codigoBarra" value="" autocomplete="off" autofocus="" style="opacity: 0; height: 0px;" >				
		</div>
		<div id="respuesta" hidden="true"></div>
	</div>
</form>