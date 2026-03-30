<?php
session_start();
error_reporting(0);
require_once("../../../../config/config.php");
require_once('../../../../class/Util.class.php');               $objUtil                = new Util;
require_once('../../../../class/Connection.class.php');         $objCon                 = new Connection; $objCon->db_connect();

require_once("../../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../../class/Config.class.php');             $objConfig              = new Config;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../../class/RegistroClinico.class.php');    $objRegistroClinico     = new RegistroClinico;
require_once('../../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../../class/Bitacora.class.php');           $objBitacora            = new Bitacora;
require_once('../../../../class/AltaUrgencia.class.php');       $objAltaUrgencia        = new AltaUrgencia;
require_once('../../../../class/Evolucion.class.php');          $objEvolucion           = new Evolucion;
require_once('../../../../class/Admision.class.php');           $objAdmision            = new Admision;
require_once('../../../../class/Imagenologia.class.php');       $objImagenologia        = new Imagenologia;
require_once('../../../../class/Laboratorio.class.php');        $objLaboratorio         = new Laboratorio;
require_once('../../../../class/Usuarios.class.php');           $objUsuarios            = new Usuarios;
require_once('../../../../class/Categorizacion.class.php');     $objCate        	= new Categorizacion;
// require("../../../../config/config.php");

$parametros                 	= $objUtil->getFormulario($_POST);
$dau_id                     	= $_POST['dau_id'];
$rsRce                      	= $objRegistroClinico->consultaRCE($objCon,$parametros);
// $rsEvolucion 					= $objEvolucion->obtenerDatosSolicitudEvolucionSegunRCE($objCon, $rsRce[0]['regId']);
$parametros['rce_id']       	= $rsRce[0]['regId'];
// $datosDAU                   	= $objDau->ListarPacientesDau($objCon, $parametros);
$rsInicioAtencion               = $objDau->obtenerDatosSolicitudInicioAtencionDATOS($objCon,$parametros['rce_id']);
// $datosU 						= $objCate -> searchPaciente($objCon, $parametros['dau_id']);
$listaSignos 					= $objRce ->listarSignosVitales($objCon, $rsRce[0]['id_paciente'], $rsRce[0]['regId']);

$parametrosBitacora['BITid'] 	= $parametros['dau_id'];
// $rsBitacora                   	= $objBitacora->listarBitacora($objCon, $parametrosBitacora);
$rsBitacora                   	= $objBitacora->listarBitacoraplusEnfermera($objCon, $parametrosBitacora);
$rsMovimientoEVOxEspe2 			= $objRegistroClinico->MovimientoEVOxEspe2($objCon,$parametros);
$atencionModifica = "";
// if($rsInicioAtencion[0]['SIAusuarioModifica'] != ""){
// 	$fechaHora 			= $rsInicioAtencion[0]['SIAfechaModificacion'];
// 	list($fecha, $hora) = explode(' ', $fechaHora);
// 	$atencionModifica = "/ Modifica : ".$rsInicioAtencion[0]['SIAusuarioModifica']." ".$objUtil->fechaInvertida($fecha)." a las ".substr($hora, 0, -3);
// }
if (!empty($rsInicioAtencion[0]['SIAusuarioModifica'])) {
    list($fecha, $hora) = explode(' ', $rsInicioAtencion[0]['SIAfechaModificacion']);
    $atencionModifica = "/ Modifica : {$rsInicioAtencion[0]['SIAusuarioModifica']} " . 
                        $objUtil->fechaInvertida($fecha) . 
                        " a las " . substr($hora, 0, -3);
}

?>
<!-- <script>
    $(document).ready(function() {
        $('#exampleSelect').select2({
            theme: 'bootstrap4',
            placeholder: "Selecciona una opción",
            allowClear: true
        });
    });
</script> -->
<!-- 
################################################################################################################################################
                                                        DESPLIEGUE FORMULARIO PROCEDIMIENTO
-->
<!-- <div class="container mt-5">
        <label for="exampleSelect">Selecciona una opción:</label>
        <select id="exampleSelect" class="form-control select2" style="width: 100%;">
            <option></option>
            <option value="1">Opción 1</option>
            <option value="2">Opción 2</option>
            <option value="3">Opción 3</option>
        </select>
    </div> -->
<div class="m-2">
<div class="accordion" id="accordionExample">
  <div class="card" style="-webkit-box-shadow:none;">
    <div class="card-header m-0 p-0" style="background-color: #ffffff !important;" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link mifuente12 btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fas fa-heartbeat throb mr-2 mifuente18 text-primary" ></i>&nbsp;Signos Vitales
        </button>
      </h2>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
      <div class="card-body m-0 p-1">
			<table id="lista_signos" class="table table-sm table-borderless  mifuente12" >
				<thead>
					<tr class="text-center border-bottom">
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold">Usuario y fecha</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >PAS / PAD</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >PAM</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >PULSO</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >SAT</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >FIO2</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >FR</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >HGT</th>
						<?php if($rsRce[0]['dau_atencion'] == 3){ ?>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >LCF</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >RBNE</th>
						<?php } ?>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >GCS</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >T°</th>
						<th class="my-1 py-1 mx-1 px-1 mifuente12 font-weight-bold" >EVA</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$contadorListaSignos = count($listaSignos);
					for($i=0;$i<count($listaSignos);$i++){ ?>
					<tr id="signos" class="text-center border-bottom">
						 <td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALusuario']. " <br>".date("d-m-Y", strtotime($listaSignos[$i]['SVITALfecha'])); ?> - <?= date("H:i", strtotime($listaSignos[$i]['SVITALfecha'])); ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALsistolica']; ?> / <?= $listaSignos[$i]['SVITALdiastolica']; ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= intval($listaSignos[$i]['SVITALPAM']); ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= intval($listaSignos[$i]['SVITALpulso']); ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALsaturacion']; ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['FIO2']; ?>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALfr']; ?>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALHemoglucoTest']; ?></td>
						<?php if($rsRce[0]['dau_atencion'] == 3){ ?>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALfeto']; ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALrbne']; ?></td>
						<?php } ?>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALglasgow']; ?></td>
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALtemperatura']; ?></td> 
						<td class="my-1 py-1 mx-1 px-1 mifuente11"><?= $listaSignos[$i]['SVITALeva']; ?></td> 
					</tr>
					<?php 
					}?>

				</tbody>
			</table>
      </div>
    </div>
  </div>
  <div class="card" style="-webkit-box-shadow:none;">
    <div class="card-header m-0 p-0" style="background-color: #ffffff !important;" id="headingTwo">
      <h2 class="mb-0">
        <button class="btn btn-link mifuente12 btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <i class="fas fa-file-medical-alt text-primary mifuente18 mr-2"></i> &nbsp;Registro Médico
        </button>
      </h2>
    </div>
    <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body m-0 p-1">
      	<div class="row " >
	    	<div class="col-md-12 "  >
	        	<label id="label_nombre" style="margin-bottom: 0!important; font-size: 12px" class=" letraFuente" ><b>Motivo Consulta </b></label><br><label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><?=($rsRce[0]['regMotivoConsulta'])?></label>
	    	</div>  
		    <div class="col-md-12  text-right"  >
		    	<?php if ($rsRce[0]['dau_inicio_atencion_usuario'] == $_SESSION['MM_Username'.SessionName]){
		    		$classNombre = "text-success";
		    	}else{
		    		$classNombre = "text-dark";
		    	} 
		    	$fechaHora 			= $rsRce[0]['dau_inicio_atencion_fecha'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				?>
		        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$rsRce[0]['dau_inicio_atencion_usuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3).$atencionModifica;?></b></label>
		    </div>                      
		</div>
		<hr class="m-1 p-0">
		<div class="row " >
	    	<div class="col-md-12 "  >
	        	<label id="label_nombre" style="margin-bottom: 0!important; font-size: 12px" class=" letraFuente" ><b>Hipotesis Diagnóstica </b></label><br><label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><?=nl2br($rsRce[0]['regHipotesisInicial'])?></label>
	    	</div>  
		    <div class="col-md-12  text-right"  >
		    	<?php if ($rsRce[0]['dau_inicio_atencion_usuario'] == $_SESSION['MM_Username'.SessionName]){
		    		$classNombre = "text-success";
		    	}else{
		    		$classNombre = "text-dark";
		    	} 
		    	$fechaHora 			= $rsRce[0]['dau_inicio_atencion_fecha'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				?>
		        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$rsRce[0]['dau_inicio_atencion_usuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3).$atencionModifica;?></b></label>
		    </div>                      
		</div>
		<hr class="m-1 p-0">
		<?php foreach ($rsMovimientoEVOxEspe2 as $rsMovimientoEVOxEspe2_clave => $rsMovimientoEVOxEspe2_valor) {  
			if($rsMovimientoEVOxEspe2_valor['tipo'] == 1){
				$rsMovimientoEVOxEspe2_valor['titulo'] = "Evolución Especialista (".$rsMovimientoEVOxEspe2_valor['titulo'].")";
			}if($rsMovimientoEVOxEspe2_valor['tipo'] == 3){
				$rsMovimientoEVOxEspe2_valor['titulo'] = "Solicitud Especialista (".$rsMovimientoEVOxEspe2_valor['titulo'].")";
			}
		?>
		<div class="row " >
	    	<div class="col-md-12 "  >
	        	<label id="label_nombre" style="margin-bottom: 0!important; font-size: 12px" class=" letraFuente" ><b><?=nl2br($rsMovimientoEVOxEspe2_valor['titulo'])?></b></label>
	    	</div>
	    	<div class="col-md-12 "  >
		        	<label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><?=nl2br($rsMovimientoEVOxEspe2_valor['evolucion'])?></label>
		    	</div>  
		    <div class="col-md-12  text-right"  >
		    	<?php if ($rsMovimientoEVOxEspe2_valor['usuario'] == $_SESSION['MM_Username'.SessionName]){
		    		$classNombre = "text-success";
		    	}else{
		    		$classNombre = "text-dark";
		    	} 
		    	$fechaHora 			= $rsMovimientoEVOxEspe2_valor['fecha'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				?>
		        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$rsMovimientoEVOxEspe2_valor['usuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3);?></b></label>
		    </div>                      
		</div>
		<hr class="m-1 p-0">
		<?php } ?>
      </div>
    </div>
  </div>
  <div class="card" style="-webkit-box-shadow:none;">
    <div class="card-header m-0 p-0" style="background-color: #ffffff !important;"  id="headingThree">
      <h2 class="mb-0">
        <button class="btn btn-link mifuente12 btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <i class="fas fa-hand-holding-medical mr-2 mifuente18 text-primary" ></i>&nbsp;Procesos
        </button>
      </h2>
    </div>
    <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
      <div class="card-body m-0 p-1">
        <?php foreach ($rsBitacora as $listadoBITACORA_clave => $listadoBITACORA_valor) { 
			if( ($listadoBITACORA_valor['BITtipo_codigo'] == 5 || $listadoBITACORA_valor['BITtipo_codigo'] == 28 || $listadoBITACORA_valor['BITtipo_codigo'] == 3 || $listadoBITACORA_valor['BITtipo_codigo'] == 4 || $listadoBITACORA_valor['BITtipo_codigo'] == 6 || $listadoBITACORA_valor['BITtipo_codigo'] == 7 || $listadoBITACORA_valor['BITtipo_codigo'] == 33) && $listadoBITACORA_valor['tipo'] == 1){ ?>
				<div class="row " >
			    	<div class="col-md-12 "  >
			        	<label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><?=nl2br($listadoBITACORA_valor['BITdescripcion'])?></label>
			    	</div>  
				    <div class="col-md-12  text-right"  >
				    	<?php if ($listadoBITACORA_valor['BITusuario'] == $_SESSION['MM_Username'.SessionName]){
				    		$classNombre = "text-success";
				    	}else{
				    		$classNombre = "text-dark";
				    	} 
				    	$fechaHora 			= $listadoBITACORA_valor['BITdatetime'];
						list($fecha, $hora) = explode(' ', $fechaHora);
						?>
				        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$listadoBITACORA_valor['BITusuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3);?></b></label>
				    </div>                      
				</div>
				<hr class="m-1 p-0">
			<?php } 
		if(  $listadoBITACORA_valor['tipo'] == 2){ ?>
	
		<div class="row " >
	    	<div class="col-md-12 "  >
	        	<label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><i class="fas fa-user-nurse text-primary mifuente16 mr-2"></i><b><?=nl2br($listadoBITACORA_valor['tipo_descripcion'])?></b> (<?=nl2br($listadoBITACORA_valor['BITdescripcion'])?>) - <?=nl2br($listadoBITACORA_valor['estado_solicitud'])?>
	        	<br>-&nbsp;&nbsp;<?=nl2br($listadoBITACORA_valor['observacion'])?>
	        	</label>

	    	</div>  
		    <div class="col-md-12  text-right"  >
		    	<?php if ($listadoBITACORA_valor['BITusuario'] == $_SESSION['MM_Username'.SessionName]){
		    		$classNombre = "text-success";
		    	}else{
		    		$classNombre = "text-dark";
		    	} 
		    	$fechaHora 			= $listadoBITACORA_valor['BITdatetime'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				?>
		        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$listadoBITACORA_valor['BITusuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3);?></b></label>
		    </div>                      
		</div>
		<hr class="m-1 p-0">


		<?php } ?>
		<?php } ?>

      </div>
    </div>
  </div>

  <div class="card" style="-webkit-box-shadow:none;">
    <div class="card-header m-0 p-0" style="background-color: #ffffff !important;"  id="headingfourth">
      <h2 class="mb-0">
        <button class="btn btn-link mifuente12 btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapsefourth" aria-expanded="false" aria-controls="collapsefourth">
          <i class="fas fa-file-medical mr-2 mifuente18 text-primary" ></i>&nbsp;Registro al Alta
        </button>
      </h2>
    </div>
    <div id="collapsefourth" class="collapse show" aria-labelledby="headingfourth" data-parent="#accordionExample">
      <div class="card-body m-0 p-1">
        <?php foreach ($rsBitacora as $listadoBITACORA_clave => $listadoBITACORA_valor) { 
			if( $listadoBITACORA_valor['BITtipo_codigo'] == 12 || $listadoBITACORA_valor['BITtipo_codigo'] ==  12 || $listadoBITACORA_valor['BITtipo_codigo'] ==  12 || $listadoBITACORA_valor['BITtipo_codigo'] ==  12 || $listadoBITACORA_valor['BITtipo_codigo'] ==  12 || $listadoBITACORA_valor['BITtipo_codigo'] ==  12){ ?>
		<div class="row " >
	    	<div class="col-md-12 "  >
	        	<label id="label_nombre"  style="margin-bottom: 0!important; font-size: 11px" class=" letraFuente text-justify" ><?=nl2br($listadoBITACORA_valor['BITdescripcion'])?></label>
	    	</div>  
		    <div class="col-md-12  text-right"  >
		    	<?php if ($listadoBITACORA_valor['BITusuario'] == $_SESSION['MM_Username'.SessionName]){
		    		$classNombre = "text-success";
		    	}else{
		    		$classNombre = "text-dark";
		    	} 
		    	$fechaHora 			= $listadoBITACORA_valor['BITdatetime'];
				list($fecha, $hora) = explode(' ', $fechaHora);
				?>
		        <label id="label_nombre" style="margin-bottom: 0!important; font-size: 10px" class=" letraFuente <?=$classNombre?>" ><b><?=$listadoBITACORA_valor['BITusuario']?> <?= $objUtil->fechaInvertida($fecha)?> a las <?=substr($hora, 0, -3);?></b></label>
		    </div>                      
		</div>
		<hr class="m-1 p-0">
			<?php } ?>
			<?php } ?>

      </div>
    </div>
  </div>
</div>



</div>