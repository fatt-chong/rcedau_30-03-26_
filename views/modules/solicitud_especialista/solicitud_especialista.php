<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 		$objCon      			= new Connection; 		$objCon->db_connect();
require_once("../../../class/Especialista.class.php");		$objEspecialista 		= new Especialista;
require_once("../../../class/Util.class.php");				$objUtil 	 			= new Util;
require_once("../../../class/Config.class.php");      		$Config    				= new Config;

$idsuario 			= $objUtil->usuarioActivo();
$permisosPerfil 	= $Config->cargarPermisoDau($objCon,$idsuario);
$parametros['rut'] 	= $_SESSION['MM_RUNUSU'.SessionName];
if ( $_POST ) {
	$datos			= $objUtil->getFormulario($_POST);
	$_SESSION['modulos']["solicitud"]["Worklist"] = $datos;
} else if ( isset($_SESSION['modulos']["solicitud"]["Worklist"]) ) {
	$datos  		= $_SESSION['modulos']["solicitud"]["Worklist"];
}

$parametros['checkTodas'] = $datos['checkTodas'];
$parametros['estados']	  = 1;
$parametros['est_esp']	  = 1;
if ( $parametros['checkTodas'] == 'S' ) {
	$rsEspecialidad = $objEspecialista->getEspecialidad($objCon,$parametros);
	for ( $u = 0; $u < count($rsEspecialidad) ; $u++ ) {
		$prueba  	= $rsEspecialidad[$u]['ESPcodigo'];
		if ( $u < (count($rsEspecialidad)-1) ) {
			$coma 	= ",";
		} else {
	      $coma 	= '';
		}
		$parametros['especialidad'] .= "'".$prueba."'".$coma;
	}
	$rsEspecialista	= $objEspecialista->getSolicitudEspecialista($objCon,$parametros);
} else {
	$rsEspecialista	= $objEspecialista->getSolicitudEspecialista($objCon,$parametros);
}
$version 			= $objUtil->versionJS();
?>
<!--
################################################################################################################################################
                                                        		 ESTILOS
-->
<style type="text/css">
	#solEspecialidad {
		table-layout:fixed;
	}
	#solEspecialidad td {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}
</style>
<!--
################################################################################################################################################
                                                                    ARCHIVO JS
-->
<script type="text/javascript" src="<?=RAIZ?>/controllers/client/solicitud_especialista/solicitud_especialista.js?v=<?=$version;?>"></script>

<form id="frm_solicitud_especialidad" name="frm_solicitud_especialidad" class="formularios" role="form" method="POST" >
	<div class="row ">
		<div class="col-lg-10">
			<h6 id="ensure-correct-role-and-provide-a-label" style="padding: 0.5rem;  border-radius: inherit; font-size:15px;"> <i class="fas fa-circle-notch text-danger mr-2"></i> Solicitudes Especialistas</b></h6>
		</div>
    <div class="form-group col-md-2 float-right text-right">
        <div class="form-check">
            <input 
                class="form-check-input" 
                id="checkTodas"  
                name="checkTodas" 
                type="checkbox" 
                value="S" 
                <?php if($datos['checkTodas']) { echo "checked"; } ?>
            >
            <label class="form-check-label mifuente" for="checkTodas">Mis Especialidades</label>
        </div>
    </div>
</div>
</form>
<!--
**************************************************************************
						Despliegue resultados
**************************************************************************
-->
<div class="row">
	<div class="col-md-12">
		<table id="solEspecialidad" class="table table-hover table-condensed" width="100%">
			<thead>
				<tr>
					<td width="5%" 	class=" mifuente12 text-center">N° Dau</td>
					<td width="3%" 	class=" mifuente12 text-center">Categorización</td>
					<td width="6%" 	class=" mifuente12 text-center">Sala</td>
					<td width="15%" class=" mifuente12 text-center">Paciente</td>
					<td width="7%" 	class=" mifuente12 text-center">Especialidad</td>
					<td width="18%" class=" mifuente12 text-center">Medico Solicitante</td>
					<td width="10%" class=" mifuente12 text-center">Estado Solicitud</td>
					<td width="9%" 	class=" mifuente12 text-center">Fecha Solicitud</td>
					<td width="8%" 	class=" mifuente12 text-center rendicion">Observación</td>
					<td width="5%" 	class=" mifuente12 text-center">Acción</td>
				</tr>
			</thead>
			<tbody id="contenidoSolicitud">
				<?php
				for ( $i = 0; $i < count($rsEspecialista); $i++ ) {
					$fecha 			 = date('d-m-Y H:i', strtotime($rsEspecialista[$i]['SESPfecha']));
					$transexual_bd 	 = $rsEspecialista[$i]['transexual'];
					$nombreSocial_bd = $rsEspecialista[$i]['nombreSocial'];
					$nombrePaciente  = $rsEspecialista[$i]['Nombres'];
					$width           = 28;
					$height          = 23;
					$infoPaciente    = $objUtil->infoDatosNombreTabla($transexual_bd,$nombreSocial_bd,$nombrePaciente,$width,$height);
					if ( $rsEspecialista[$i]['dau_categorizacion'] == '' ) { $rsEspecialista[$i]['dau_categorizacion'] = "No Categorizado"; }
					?>
					<tr class="detalle varDetalle" id="<?=$rsEspecialista[$r]['LEQcodigo']?>" >
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$rsEspecialista[$i]['dau_id']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center"  align=""><?=$rsEspecialista[$i]['dau_categorizacion']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$rsEspecialista[$i]['sal_descripcion']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11" ><?=$infoPaciente?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$rsEspecialista[$i]['ESPdescripcion']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$rsEspecialista[$i]['usuarioInserta']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$rsEspecialista[$i]['est_descripcion']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" ><?=$fecha?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center"  class="rendicion"><?=$rsEspecialista[$i]['SESPobservacion']?></td>
						<td class="mifuente my-1 py-1 mx-1 px-1 mifuente11 text-center" >
							<?php
							if ( $rsEspecialista[$i]['SESPestado'] == 4 ) {
							?>
								<button type="button" id="<?=$rsEspecialista[$i]['dau_id'].'-'.$rsEspecialista[$i]['SESPidPaciente'].'-'.$rsEspecialista[$i]['SESPid'].'-'.$rsEspecialista[$i]['SESPestado']?>" name="" class="btn btn-sm mifuente btn-outline-primary verSolicitudEspe"><i class="fa fa-search"></i></button>
							<?php
							}
							if ( array_search(998, $permisosPerfil) != null ) {

								if($rsEspecialista[$i]['SESPestado'] !=4){
								?>	
								<button type="button" id="<?=$rsEspecialista[$i]['dau_id']?>" name="" class="btn btn-sm mifuente btn-outline-success verDetalle"><i class="fas fa-search"></i></button>

								<button type="button" id="<?=$rsEspecialista[$i]['dau_id'].'-'.$rsEspecialista[$i]['SESPidPaciente'].'-'.$rsEspecialista[$i]['SESPid'].'-'.$rsEspecialista[$i]['SESPestado']?>" name="" class="btn btn-sm mifuente btn-outline-success aprobarSolicitud"><i class="fa fa-check"></i></button>
								<?php
								}

							}
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
