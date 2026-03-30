<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');

require("../../../config/config.php");
$permisos = $_SESSION['permisosDAU'.SessionName];

if ( array_search(834, $permisos) == null ) {

	$GoTo = "../error_permisos.php"; header(sprintf("Location: %s", $GoTo));

}
require("../../../config/config.php");
require_once('../../../class/Connection.class.php');       $objCon            = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');             $objUtil           = new Util;
require_once('../../../class/SqlDinamico.class.php');      $objSqlDinamico    = new SqlDinamico;

$cargarMotivo            		= $objSqlDinamico->generarSelect($objCon,'dau.motivo_consulta' , $parametrosSelect, $order);
$cargarAtencion          		= $objSqlDinamico->generarSelect($objCon,'dau.atencion' , $parametrosSelect, $order);

$parametrosSelect['est_id'] 	= "estado.est_id IN ('1','2','3','4','8')";
$cargarEstados          		= $objSqlDinamico->generarSelect($objCon,'dau.estado' , $parametrosSelect, $order);
if ( $campos["frm_rut"] ) {
	$campos['frm_nroDocumento'] = $objUtil->formatearNumero($campos['frm_rut']).'-'.$objUtil->generaDigito($campos['frm_rut']);
}
?>

<!-- <script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/admision/busquedaAdmision.js?v=<?=$version;?>"></script> -->

<form id="frm_consultaAdmision" class="formularios" role="form" method="POST">
	<div class="row">

	    <input id="frm_rut" type="hidden"  name="frm_rut" >
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_dau"><i class="fas fa-pen darkcolor-barra2"></i></div>
			    </div>
			    <input id="frm_dau" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_dau" placeholder="Número de DAU" value="<?=$campos['frm_dau']?>" aria-describedby="btnGroupAddonfrm_dau">
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddondocumento"><i class="fas fa-list darkcolor-barra2"></i></div>
			    </div>
			    <select class="form-control form-control-sm mifuente12" id='documento' name="documento" aria-describedby="btnGroupAddondocumento">	
					<option value="1" <?php if($campos["documento"]==1) echo "selected"?>>Rut</option>
					<option value="2" <?php if($campos["documento"]==2) echo "selected"?>>Nro de Documento</option>
				</select>
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nroDocumento"><i class="fas fa-pen darkcolor-barra2"></i></div>
			    </div>
			    <input id="frm_nroDocumento" onDrop="return false" type="text" class="form-control form-control-sm mifuente12" name="frm_nroDocumento" placeholder="Número de documento" aria-describedby="btnGroupAddonfrm_nroDocumento"  value="<?php echo $campos['frm_nroDocumento'];?>">
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_nombreCompleto"><i class="fas fa-pen darkcolor-barra2"></i></div>
			    </div>
			    <input id="frm_nombreCompleto" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_nombreCompleto" placeholder="Nombre" value="<?=$campos['frm_nombreCompleto']?>" aria-describedby="btnGroupAddonfrm_nombreCompleto">
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupfrm_ctacte"><i class="fas fa-pen darkcolor-barra2"></i></div>
			    </div>
			    <input id="frm_ctacte" type="text" onDrop="return false" class="form-control form-control-sm mifuente12" name="frm_ctacte" placeholder="Número de Ctacte" value="<?=$campos['frm_ctacte']?>" aria-describedby="btnGroupAddonfrm_ctacte">
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_estados"><i class="fas fa-list darkcolor-barra2"></i></div>
			    </div>
			    <select class="form-control form-control-sm mifuente12" id='frm_estados' name="frm_estados" aria-describedby="btnGroupAddonfrm_estados">
					<option value="">Estados</option>
					<?php for ( $i = 0; $i < count($cargarEstados); $i++ ) { ?>
					<option value="<?=$cargarEstados[$i]['est_id']?>" <?php if($campos["frm_estados"]==$cargarEstados[$i]['est_id']){ echo "selected";}?>>		<?=$cargarEstados[$i]['est_descripcion']?>	
					</option>
					<?php } ?>
				</select>
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_motivo"><i class="fas fa-list darkcolor-barra2"></i></div>
			    </div>
			    <select class="form-control form-control-sm mifuente12" id='frm_motivo' name="frm_motivo" aria-describedby="btnGroupAddonfrm_motivo">
					<option value="">Motivo Consulta</option>
					<?php for ( $i = 0; $i < count($cargarMotivo); $i++ ) { ?>
					<option value="<?=$cargarMotivo[$i]['mot_id']?>" <?php if($campos["frm_motivo"]==$cargarMotivo[$i]['mot_id']){ echo "selected";}?>>
						<?=$cargarMotivo[$i]['mot_descripcion']?>
					</option>
					<?php } ?>
				</select>
		  	</div>			
		</div>
		<div class="col-md-2 form-group has-feedback">
			<div class="input-group  shadow">
			    <div class="input-group-prepend ">
			      <div class="input-group-text mifuente12" id="btnGroupAddonfrm_atencion"><i class="fas fa-list darkcolor-barra2"></i></div>
			    </div>
			    <select class="form-control form-control-sm mifuente12" id='frm_atencion' name="frm_atencion" aria-describedby="btnGroupAddonfrm_atencion">
					<option value="">Tipo Atención</option>
					<?php for ( $i = 0; $i < count($cargarAtencion); $i++ ) { ?>
					<option value="<?=$cargarAtencion[$i]['ate_id']?>" <?php if($campos["frm_atencion"]==$cargarAtencion[$i]['ate_id']){ echo "selected";}?>>
						<?=$cargarAtencion[$i]['ate_descripcion']?>
					</option>
					<?php } ?>
				</select>
		  	</div>			
		</div>
		<div class="col-lg-2 col-md-2 col-2">
			<div class="input-group-append shadow" id="button-addon4">
			    <button id="btnBuscarPacienteDau" class="btn btn-secondary2  mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;"><i class="fas fa-search mr-2"></i>Buscar</button>
			    <button id="btnLimpiarPacienteDau" class="btn btn-outline-secondary2 mifuente12 col-lg-6" type="button" style="border-radius: 0rem !important;">Limpiar</button>
			</div>
			<!-- <div class="row">
				<button id="btnBuscarPacienteDau" type="button" class="btn  btn-secondary enviar btn-sm col-lg-5 mifuente12"><i class="fas fa-search mr-1"></i> Buscar</button>
				<button type="button" class="btn btn-light btn-sm col-lg-5 mifuente12" alt="Limpiar" title="Limpiar" id="btnEliminarFiltrosDAU"><i class="fas fa-times mr-1"></i>Limpiar</button>
			</div> -->
		</div>
		<div class="col-lg-2 col-md-2 col-2">
		</div>
		<?php if ( array_search(835, $permisos) != null ) { ?> 
		<div class="col-lg-4 col-md-4 col-4">
			<button type="button" class="btn btn-primary2 btn-sm mifuente12 col-lg-12 shadow"  id="btnNuevoAdmision" >Nueva Admisión	
				<i class="fas fa-plus ml-2"></i>
			</button>
		</div>
		<?php } ?>	
	</div>
</form>
<div class="row"  id="div_worklist_admision">
</div>

<script type="text/javascript">
	$(document).ready(function() {
		ajaxContent(raiz+'/views/modules/admision/worklist_admision.php', '', '#div_worklist_admision', '', true);
		$('#btnBuscarPacienteDau').on("click",function(){
			if( $('#frm_nroDocumento').val()!='' || $('#frm_dau').val()!='' || $('#frm_ctacte').val()!='' || $('#frm_nombreCompleto').val()!='' || $('#frm_estados option:selected').val()!="" || $('#frm_motivo option:selected').val()!="" || $('#frm_atencion option:selected').val()!="" ){
				if($('#documento option:selected').val()==1){
					var rut = $("#frm_nroDocumento").val();
					rut     = $.Rut.quitarFormato(rut);
					rut     = rut.substring(0, rut.length-1);
					$("#frm_rut").val(rut)
				}
				ajaxContent(raiz+'/views/modules/admision/worklist_admision.php', $('#frm_consultaAdmision').serialize(), '#div_worklist_admision', '', true);
				$("#frm_rut").val("")
			}
		});
		$('#btnLimpiarPacienteDau').on("click",function(){
			ajaxContent(raiz+'/views/modules/admision/worklist_admision.php', '', '#div_worklist_admision', '', true);
		});

		$('#btnNuevoAdmision').on("click",function(){
			modalFormulario_noCabecera('', raiz+"/views/modules/admision/admision.php", '', "#modal_admision", "modal-lgg", "", "fas fa-plus");
		});
	});
</script>