<?php
	session_start();
	error_reporting(0);
	require("../../../config/config.php");
	$permisos = $_SESSION['permisosDAU'.SessionName];
	if ( array_search(833, $permisos) == null ) {$GoTo = "../modules/error_permisos.php"; header(sprintf("Location: %s", $GoTo));}
	require_once('../../../class/Connection.class.php');       $objCon      = new Connection; $objCon->db_connect();
	require_once('../../../class/Util.class.php');             $objUtil     = new Util;
	if($_POST){
	   $campos 		= $objUtil->getFormulario($_POST);
	   $_SESSION['views']["repostes"] = $campos;
	}else if(isset($_SESSION['views']["repostes"])){
	   $campos 		= $_SESSION['views']["repostes"];
	}else{
		   $campos 	= array();
	}
	$fechaHoy       = $objUtil->fechaNormal(date('Y-m-d'));
	$version    	= $objUtil->versionJS();
?>

<script type="text/javascript" charset="utf-8" src="<?=PATH?>/controllers/client/reportes/consultaReporte.js?v=<?=$version;?>"></script>

<form id="frm_reportes" class="" role="form" method="POST">
<div class="m-3">
	<div class="row">
	<label class="text-secondary ml-3"><i class="fas fa-file   mifuente20" style="color: #59a9ff;"></i> Consulta de Reportes</label>
	<div class=" col-lg-12 dropdown-divider   mt-2 mb-4 " ></div>
	</div>
	<div class="row ">
		<div id="" class="col-md-2 form-group has-feedback">
		<label class="control-label mifuente">Tipo de Reporte</label>
		<div class="input-group">
		<span class="input-group-addon"><i class="glyphicon glyphicon-menu-hamburger"></i></span>
		<select id="tipoReporte" name="tipoReporte" class="form-control form-control-sm mifuente" >
		<option value="1">LIBROS DE URGENCIA</option>
		<option value="2">ESTADISTICAS PARA REM</option>
		<option value="3">AUDITORIA</option>
		</select>
		</div>
		</div>
		<!-- Select de LIBROS DE URGENCIA -->
		<div id="reportes" class="col-md-3 form-group has-feedback"> <!-- inicio del div -->
		<label for="inputRUN" class="control-label mifuente">Reporte</label>
		<div class="input-group"> <!-- inicio del input-group -->
		<span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
		<select id="frm_reportesDau" name="frm_reportesDau" class="form-control form-control-sm mifuente">
		<option value="">Seleccione Reporte</option>
		</select>
		</div>
		</div>
		<div id="reportesAtencion" class="col-md-2 form-group has-feedback" > <!-- inicio del div -->
		<label for="inputRUN" class="control-label mifuente">Tipo de Atención</label>
		<div class="input-group"> <!-- inicio del input-group -->
		<span class="input-group-addon"><i class="glyphicon glyphicon-adjust"></i></span>
		<select id="frm_tipoAtencion" name="frm_tipoAtencion" class="form-control form-control-sm mifuente">
		</select>
		</div>
		</div>
		<!-- Select de LIBROS DE URGENCIA -->
		<div class="form-group col-lg-2">
		<label class="control-label mifuente">Fecha(Desde)</label>
		<div class="form-group">
		<div class='input-group date' id="date_fecha_desde">
		<input type='text' class="form-control form-control-sm mifuente" name="frm_fecha_admision_desde" id="frm_fecha_admision_desde" onDrop="return false" placeholder="DD/MM/YY" value="<?=$fechaHoy?>" />
		<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div>
		</div>
		</div>
		<div class="form-group col-lg-2" id="divFechaHasta" >
		<label class="control-label mifuente">Fecha(Hasta)</label>
		<div class="form-group">
		<div class='input-group date' id="date_fecha_hasta">
		<input type='text' class="form-control form-control-sm mifuente" name="frm_fecha_admision_hasta" id="frm_fecha_admision_hasta" onDrop="return false" placeholder="DD/MM/YY" value="<?=$fechaHoy?>" />
		<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
		</div>
		</div>
		</div>
		<div id="turnos" class="col-md-2 form-group has-feedback">
		<label class="control-label mifuente">Turno</label>
		<div class="input-group">
		<span class="input-group-addon"><i class="glyphicon glyphicon-menu-hamburger"></i></span>
		<select class="form-control form-control-sm mifuente" id='frm_turno' name="frm_turno" >
		<option value="">Seleccione Turno</option>
		<option value="1" <?php if($campos["frm_turno"]==1) echo "selected"?>>Día</option>
		<option value="2" <?php if($campos["frm_turno"]==2) echo "selected"?>>Noche</option>
		</select>
		</div>
		</div>
		<div  class="form-group col">
			<div class="row">
			<label class="control-label">&nbsp;</label>
			<div class="input-group">
			<button id="generarPDF" type="button" class="btn btn-outline-primary btn-sm mifuente resultadoBusqueda col mr-3" alt="Generar PDF" title="Generar PDF"> <i class="mr-2 fas fa-search"></i> Buscar</button>
			<button id="excelRegistroHospitalizacion" type="button" class="btn btn-outline-success btn-sm mifuente resultadoBusqueda col mr-3" alt="Generar PDF" title="Generar PDF"> <i class="fas fa-file-excel"></i></button>
			<button id="excelRegistroAtencionDiaria" type="button" class="btn btn-outline-success btn-sm mifuente resultadoBusqueda col mr-3" alt="Excel Registro Atención Diaria" title="Generar PDF"> <i class="fas fa-file-excel"></i></button>
			<button id="generarPDF2" type="button" class="btn btn-outline-primary btn-sm mifuente resultadoBusqueda col mr-3" alt="Generar PDF" title="Generar PDF"> <i class="mr-2 fas fa-search"></i> Buscar</button>
			<button id="sala_Hidratacion_u_observacion_listado" type="button" class="btn btn-outline-success btn-sm mifuente resultadoBusqueda col mr-3" alt="Generar PDF" title="Generar PDF"> <i class="fas fa-file-excel"></i></button>
			<?php if(count($campos)>1){ ?>
			<button id="btnEliminarFiltrosReportes" type="button" class="btn btn-outline-danger btn-sm mifuente resultadoBusqueda col mr-3" alt="Limpiar" title="Limpiar"> <i class=" fas fa-times"></i></button> 
			<?php } ?>
			</div>
			</div>
		</div>

	<!-- condicion para cuando se seteen las variables -->
	<!-- <div class="row"> -->

	<div id="contenidoAtenciones2" class="col-lg-12" ></div>

	</div>
</div>
</form>
