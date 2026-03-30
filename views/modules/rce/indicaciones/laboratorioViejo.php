<?php
session_start();
error_reporting(0);
ini_set('max_execution_time', 600);
ini_set('memory_limit', '128M');
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 	$objCon   		= new Connection;$objCon->db_connect();
require_once('../../../../class/Laboratorio.class.php'); 	$objLaboratorio = new Laboratorio;
require_once('../../../../class/Dau.class.php'); 			$objDau 		= new Dau;
require_once('../../../../class/Util.class.php'); 			$objUtil 		= new Util;
$pacienteComplejo     = $_SESSION['datosPacienteDau']['dau_paciente_complejo'];
switch ( $pacienteComplejo ) {
	case 'S':		
		$rsLaboratorio  = $objLaboratorio->getExamenesLaboratorio($objCon);
		$rsQuimico 		= $objLaboratorio->listarPrestaciones($objCon,1);
		$rshormonas		= $objLaboratorio->listarPrestaciones($objCon,2);
		$rsHemato 		= $objLaboratorio->listarPrestaciones($objCon,3);
		$rsOrina		= $objLaboratorio->listarPrestaciones($objCon,4);
		$rsInmunologico = $objLaboratorio->listarPrestaciones($objCon,5);
		$rsbacterio     = $objLaboratorio->listarPrestaciones($objCon,7);
		$rsGases		= $objLaboratorio->listarPrestaciones($objCon,10);
		$rsDeposiciones	= $objLaboratorio->listarPrestaciones($objCon,11);
		$rsLiquidos		= $objLaboratorio->listarPrestaciones($objCon,12);
	break;
	default:
		$rsQuimico_urg		= $objLaboratorio->listarPrestaciones_urg($objCon,1);
		$rsHemato_urg 		= $objLaboratorio->listarPrestaciones_urg($objCon,3);
		$rsOrina_urg		= $objLaboratorio->listarPrestaciones_urg($objCon,4);
		$rsGases_urg		= $objLaboratorio->listarPrestaciones_urg($objCon,10);
		$rsDeposiciones_urg	= $objLaboratorio->listarPrestaciones_urg($objCon,11);
		$rsLiquidos_urg		= $objLaboratorio->listarPrestaciones_urg($objCon,12);
	break;
}
$parametros['aLab'] = $_SESSION['indicaciones']['laboratorio'];
$parametros['aLab'] = json_decode(stripslashes($parametros['aLab']));
$rsAlab = $parametros['aLab'];
$version            = $objUtil->versionJS();
?>
<script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/laboratorio.js?v=<?=$version;?>"></script>
<br>
<div id="complejo" <?php if( ! $pacienteComplejo == "S"){?> hidden <?php }?> > 
	<form id="frm_laboratorio_master" name="frm_laboratorio_master">
		<div class="row"  id="hemaDiv">
			<div class="col-md-4">
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Químicos</div>
					<div class="panel-body mifuente   ml-3 mr-3 mb-2 examenesNormales" >
						<?php
							$rsQuimico = is_array($rsQuimico) ? $rsQuimico : [];
							$rsAlab = is_array($rsAlab) ? $rsAlab : [];
							$rshormonas = is_array($rshormonas) ? $rshormonas : [];
							for ($i = 0; $i < count($rsQuimico); $i++) {
							    $chekeado = 0;
							    $idQuimico = $rsQuimico[$i]['pre_codOmega'];
							    // Iterar sobre $rsAlab
							    for ($j = 0; $j < count($rsAlab); $j++) {
							        $labCheck = $rsAlab[$j]['0'];

							        if ($idQuimico == $labCheck) {
							            $chekeado = 1;
							        }
							    }
							?>
						    <label style="margin-bottom: -4px; font-weight: normal;">
						        <input
						            class="<?= htmlspecialchars($rsQuimico[$i]['pre_pacienteUrgencia']) ?> checkPruebaComplejo"
						            style="margin-right: 5px;"
						            type="checkbox"
						            name="frm_laboratorio"
						            id="frm_laboratorio"
						            value="<?= htmlspecialchars($rsQuimico[$i]['pre_codOmega']) ?>"
						            <?php if ($chekeado == 1) { ?> checked <?php } ?>
						        >
						        <?= htmlspecialchars($rsQuimico[$i]['pre_examen']) ?>
						        <input
						            type="hidden"
						            id="<?= htmlspecialchars($rsQuimico[$i]['pre_codOmega']) ?>"
						            value="<?= htmlspecialchars($rsQuimico[$i]['pre_examen']) ?>"
						        >
						    </label>
						    <br>
						<?php
						}
						?>							
					
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Hormonas</div>
							<div class="panel-body mifuente   ml-3 mr-3 mb-2 examenesNormales" >
								<?php
								if (is_array($rshormonas) && count($rshormonas) > 0) {
								    for ($i = 0; $i < count($rshormonas); $i++) {
								?>
								        <label style="margin-bottom: -4px;font-weight: normal">
								            <input class="<?=$rshormonas[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo" style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rshormonas[$i]['pre_codOmega']?>">
								            <?=$rshormonas[$i]['pre_examen'];?>
								            <input type="hidden" id="<?=$rshormonas[$i]['pre_codOmega'];?>" value="<?=$rshormonas[$i]['pre_examen'];?>">
								        </label>
								        <br>
								<?php
								    }
								} else {
								}?>		
							</div>
						</div>
					</div>
					<div class="col-md-12">						
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Hematológicos</div>
							<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
								<?php
								if (is_array($rsHemato) && count($rsHemato) > 0) {
								for ( $i = 0; $i < count($rsHemato) ; $i++ ) {
									$chekeado2  = 0; 
									$idrsHemato = $rsHemato[$i]['pre_codOmega'];  
									for ( $j = 0; $j < count($rsAlab) ; $j++ ) {     
										$labCheck  = $rsAlab[$j][0];
										if ( $idrsHemato == $labCheck ) {
											$chekeado2 = 1; 
										}
									}
									?>
									<label style="margin-bottom: -4px;font-weight: normal"><input class="<?=$rsHemato[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo" style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsHemato[$i]['pre_codOmega']?>" <?php if($chekeado2 ==1){ ?> checked <?php }?>><?=$rsHemato[$i]['pre_examen']?>
										<input type="hidden" id="<?=$rsHemato[$i]['pre_codOmega'];?>" value="<?=$rsHemato[$i]['pre_examen'];?>"></label>
									<br>
								<?php } } ?>							
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Orina</div>
							<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
								<?php
								if (is_array($rsOrina) && count($rsOrina) > 0) {
								for ( $i = 0; $i < count($rsOrina) ; $i++ ) {
									$chekeado2  = 0; 
									$idrsOrina  = $rsOrina[$i]['pre_codOmega'];  
									for ( $j = 0; $j < count($rsAlab) ; $j++ ) {
										$labCheck  = $rsAlab[$j]['0'];
										if ( $idrsOrina == $labCheck ) { 
											$chekeado2 = 1; 
										}
									} ?>
									<label style="margin-bottom: -4px;font-weight: normal"><input class="<?=$rsOrina[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo" style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsOrina[$i]['pre_codOmega']?>" <?php if($chekeado2 ==1){ ?> checked <?php }?>><?=$rsOrina[$i]['pre_examen']?>
										<input type="hidden" id="<?=$rsOrina[$i]['pre_codOmega'];?>" value="<?=$rsOrina[$i]['pre_examen'];?>">
									</label>
									<br>
								<?php } } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Inmunológicos</div>
							<div class="panel-body mifuente   ml-3 mr-3 mb-2 examenesNormales" >
								<?php
								if (is_array($rsInmunologico) && count($rsInmunologico) > 0) {
								for ( $i = 0; $i < count($rsInmunologico) ; $i++ ) { 	
								?>
								<label style="margin-bottom: -4px;font-weight: normal">
									<input class="<?=$rsInmunologico[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo"  style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsInmunologico[$i]['pre_codOmega']?>">
										<?=$rsInmunologico[$i]['pre_examen'];?>
									<input type="hidden" id="<?=$rsInmunologico[$i]['pre_codOmega'];?>" value="<?=$rsInmunologico[$i]['pre_examen'];?>">
								</label>
								<br>
								<?php } } ?>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Bacteriológicos</div>
							<div class="panel-body mifuente   ml-3 mr-3 mb-2 examenesNormales" >
								<?php
								if (is_array($rsbacterio) && count($rsbacterio) > 0) {
								for ( $i = 0; $i < count($rsbacterio) ; $i++ ) { 	
								?>
								<label style="margin-bottom: -4px;font-weight: normal">
									<input class="<?=$rsbacterio[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo"  style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsbacterio[$i]['pre_codOmega']?>">
									<?=$rsbacterio[$i]['pre_examen'];?>
									<input type="hidden" id="<?=$rsbacterio[$i]['pre_codOmega'];?>" value="<?=$rsbacterio[$i]['pre_examen'];?>">
								</label>
								<br>
								<?php } } ?>	
							</div>
						</div>
					</div>
					<div class="col-md-12">					
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Gases</div>
					
				
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
					
						<?php
						if (is_array($rsGases) && count($rsGases) > 0) {
						for ( $i = 0; $i < count($rsGases) ; $i++ ) {
							
							$chekeado  = 0; 
							
							$idrsGases = $rsGases[$i]['pre_codOmega']; 
							
							if (is_array($rsAlab) && count($rsAlab) > 0) {
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) { 
								
								$labCheck = $rsAlab[$j]['0']; 
								
								if ( $idrsGases == $labCheck ){
								
									$chekeado = 1; 

								}

							} }
							?>
							
							<label style="margin-bottom: -4px;font-weight: normal">
							
								<input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsGases[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsGases[$i]['pre_examen']?>
							
								<input type="hidden" id="<?=$rsGases[$i]['pre_codOmega'];?>" value="<?=$rsGases[$i]['pre_examen'];?>" >

							</label>
							
							<br>

						<?php 
						}
						}
						?>
					
					</div>
						
				</div>
				</div>
					<div class="col-md-12">
						<!-- Deposiciones -->
						<div class="panel panel-default border mb-2">
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Deposiciones</div>
						
							<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
						
								<?php
								if (is_array($rsDeposiciones) && count($rsDeposiciones) > 0) {
								for ( $i = 0; $i < count($rsDeposiciones) ; $i++ ) {
									$chekeado = 0; 
									
									$idrsDeposiciones = $rsDeposiciones[$i]['pre_codOmega']; 
									
									for ( $j = 0; $j < count($rsAlab) ; $j++ ) {
										
										$labCheck = $rsAlab[$j]['0']; 
									
										if ( $idrsDeposiciones == $labCheck ) {
											
											$chekeado = 1; 
										
										}	
										}	
									}
									?>
								
									<label style="margin-bottom: -4px;font-weight: normal"><input class="<?=$rsDeposiciones[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo" style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsDeposiciones[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsDeposiciones[$i]['pre_examen']?>
									
										<input type="hidden" id="<?=$rsDeposiciones[$i]['pre_codOmega'];?>" value="<?=$rsDeposiciones[$i]['pre_examen'];?>">
								
									</label>
									
									<br>

								<?php
								}
								?>
									
							</div>

						</div>
				</div>
				<div class="col-md-12">
					<!-- Líquido -->
					<div class="panel panel-default border mb-2">
						
						<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Líquido</div>
						
						<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
						
							<?php

							if (is_array($rsDeposiciones) && count($rsDeposiciones) > 0) {
							for ( $i = 0; $i < count($rsLiquidos) ; $i++ ) {
								
								$chekeado = 0; 
								
								$idrsLiquidos = $rsLiquidos[$i]['pre_codOmega']; 
								
								if (is_array($rsAlab) && count($rsAlab) > 0) {
								for ( $j = 0; $j < count($rsAlab) ; $j++) { 
								
									$labCheck = $rsAlab[$j]['0']; 
										
									if ( $idrsLiquidos == $labCheck ) {
										
										$chekeado = 1; 
									
									}

								} } }
								?>
								
								<label style="margin-bottom: -4px;font-weight: normal"><input class="<?=$rsLiquidos[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo" style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsLiquidos[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsLiquidos[$i]['pre_examen']?>
									
									<input type="hidden" id="<?=$rsLiquidos[$i]['pre_codOmega'];?>" value="<?=$rsLiquidos[$i]['pre_examen'];?>">
								
								</label>

								<br>

							<?php 
							}
							?>
								
						</div>
							
					</div>		
				</div>
					<div class="col-md-12">

						<!-- Solicitudes -->
						<div class="panel panel-default border mb-2">
							
							<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Solicitudes</div>
						
							<div id="divInv" class="panel-body mifuente  ml-3 mr-3 mb-2" style="margin-top: -10px;">
								
								<label id="hiddenInv" style="margin-bottom: -4px;font-weight: normal;margin-top: 10px;"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="11"  <?php echo $checked; ?> />INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS
									
									<input type="hidden" id="11" value="INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS">
								
								</label>
							
							</div>
						
						</div>
					
					</div>
				</div>

			</div>

		</div>

	</form>

</div>


<div id="urgencia" <?php if($pacienteComplejo == "S"){?> hidden <?php }?> > 
	<input type="hidden" id="frm_des_lab" name="frm_des_lab" value="<?=$Destino?>">
	<form id="frm_laboratorio_master2" name="frm_laboratorio_master2">
		<div class="row"  id="hemaDiv">
			<div class="col-md-4">
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Químicos</div>
					<div class="panel-body mifuente   ml-3 mr-3 mb-2 examenesNormales" >
						<?php
						for ( $i = 0; $i < count($rsQuimico_urg) ; $i++ ) { 		
							$chekeado = 0;
							$idQuimico = $rsQuimico_urg[$i]['pre_codOmega'];
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) { 
								$labCheck = $rsAlab[$j]['0'];
								if ( $idQuimico == $labCheck ) {
									$chekeado = 1;
								}	
							} 
							?>
							<label style="margin-bottom: -4px;font-weight: normal">
								<input class="<?=$rsQuimico_urg[$i]['pre_pacienteUrgencia']?> checkPruebaComplejo"  style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsQuimico_urg[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>>
								<?=$rsQuimico_urg[$i]['pre_examen'];?>
								<input type="hidden" id="<?=$rsQuimico_urg[$i]['pre_codOmega'];?>" value="<?=$rsQuimico_urg[$i]['pre_examen'];?>">
							</label>
							<br>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Hematológicos</div>
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
						<?php
						for ( $i = 0; $i < count($rsHemato_urg) ; $i++ ) {
							$chekeado2  = 0; 
							$idrsHemato = $rsHemato_urg[$i]['pre_codOmega']; 
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) {  
								$labCheck = $rsAlab[$j]['0']; 
								if ( $idrsHemato == $labCheck ) { 
									$chekeado2 = 1;
								} 
							} ?>
							<label style="margin-bottom: -4px;font-weight: normal"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsHemato_urg[$i]['pre_codOmega']?>" <?php if($chekeado2 ==1){ ?> checked <?php }?>><?=$rsHemato_urg[$i]['pre_examen']?>
								<input type="hidden" id="<?=$rsHemato_urg[$i]['pre_codOmega'];?>" value="<?=$rsHemato_urg[$i]['pre_examen'];?>">
							</label>
							<br>
						<?php } ?>	
					</div>
				</div>			
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Orina</div>
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
						<?php
						for ( $i=0; $i < count($rsOrina_urg) ; $i++ ) {
							$chekeado2      = 0; 
							$idrsOrina_urg  = $rsOrina_urg[$i]['pre_codOmega'];  
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) {
								$labCheck  = $rsAlab[$j]['0'];
								if ( $idrsOrina_urg == $labCheck ) { 
									$chekeado2 = 1; 
								}
							} ?>
							<label style="margin-bottom: -4px;font-weight: normal"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsOrina_urg[$i]['pre_codOmega']?>" <?php if($chekeado2 ==1){ ?> checked <?php }?>><?=$rsOrina_urg[$i]['pre_examen']?>
								<input type="hidden" id="<?=$rsOrina_urg[$i]['pre_codOmega'];?>" value="<?=$rsOrina_urg[$i]['pre_examen'];?>">
							</label>
							<br>
						<?php } ?>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">

				<!-- Gases -->								
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Gases</div>
					

				<!-- 	<div class="panel-heading" align="center" style="height: 30px; background-color: #337ab7 !important;">
					
						<span class="encabezado" style="position: relative; color: #ffffff;">Gases</span>
						
					</div> -->
						
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
							
						<?php					
						for ( $i = 0; $i < count($rsGases_urg) ; $i++ ) {
							
							$chekeado      = 0; 
							
							$idrsGases_urg = $rsGases_urg[$i]['pre_codOmega']; 
							
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) { 
							
								$labCheck = $rsAlab[$j]['0']; 
								
								if ( $idrsGases_urg == $labCheck ) {
									
									$chekeado = 1; 
								
								}	
							
							}
							?>
							
							<label style="margin-bottom: -4px;font-weight: normal"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsGases_urg[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsGases_urg[$i]['pre_examen']?>
								
								<input type="hidden" id="<?=$rsGases_urg[$i]['pre_codOmega'];?>" value="<?=$rsGases_urg[$i]['pre_examen'];?>">
							
							</label>
							
							<br>

						<?php
						}
						?>
							
					</div>
						
				</div>

				<!-- Deposiciones -->

				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Deposiciones</div>
					
				<!-- 	<div class="panel-heading" align="center" style="height: 30px; background-color: #337ab7 !important;">
					
						<span class="encabezado" style="position: relative; color: #ffffff;">Deposiciones</span>
						
					</div> -->
				
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
				
						<?php
						for ( $i = 0; $i < count($rsDeposiciones_urg) ; $i++ ) {
							
							$chekeado = 0; 
							
							$idrsDeposiciones_urg = $rsDeposiciones_urg[$i]['pre_codOmega']; 
							
							for ( $j = 0; $j < count($rsAlab) ; $j++ ) { 
							
								$labCheck = $rsAlab[$j]['0']; 
								
								if ( $idrsDeposiciones_urg == $labCheck ) {
									
									$chekeado = 1; 
								
								}
							
							}
							?>
						
							<label style="margin-bottom: -4px;font-weight: normal"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsDeposiciones_urg[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsDeposiciones_urg[$i]['pre_examen']?>
								
								<input type="hidden" id="<?=$rsDeposiciones_urg[$i]['pre_codOmega'];?>" value="<?=$rsDeposiciones_urg[$i]['pre_examen'];?>">
						
							</label>
							
							<br>
						
						<?php
						}
						?>							
					
					</div>

				</div>

				<!-- Líquido -->			
				<div class="panel panel-default border mb-2">
					
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Líquido</div>
					
					<div class="panel-body mifuente  ml-3 mr-3 mb-2" >
					
						<?php
						for ( $i = 0; $i < count($rsLiquidos_urg) ; $i++ ) {
							
							$chekeado = 0; 
							
							$idrsLiquidos_urg = $rsLiquidos_urg[$i]['pre_codOmega']; 
							
							for ( $j = 0; $j < count($rsAlab) ; $j++) { 
								
								$labCheck = $rsAlab[$j]['0']; 
								
								if ( $idrsLiquidos_urg == $labCheck ) {

									$chekeado = 1; 
								
								}	
							
							}
							?>
							
							<label style="margin-bottom: -4px;font-weight: normal"><input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="<?=$rsLiquidos_urg[$i]['pre_codOmega']?>" <?php if($chekeado ==1){ ?> checked <?php }?>><?=$rsLiquidos_urg[$i]['pre_examen']?>
								
								<input type="hidden" id="<?=$rsLiquidos_urg[$i]['pre_codOmega'];?>" value="<?=$rsLiquidos_urg[$i]['pre_examen'];?>">
							
							</label>
							
							<br>

						<?php
						}
						?>
							
					</div>
						
				</div>		
				
				<!-- Solicitudes -->
				<div class="panel panel-default border mb-2">
					<div class="card-header text-center" style="background-color: rgb(184 218 255);border-bottom: 1px solid rgb(184 218 255);font-weight: 500;color: #25748e;font-size: 14px; padding: .25rem 1.25rem !important">Solicitudes</div>
				
					<?php
					for ( $i=0; $i < count($rsAlab); $i++ ) { 
						
						if ( $rsAlab[$i][0] == 11 ) {
							
							$checked = 'checked';
						
						} else {
							
							$checked = '';
						
						}
					}
					?>

					<div id="divInv" class="panel-body mifuente  ml-3 mr-3 mb-2" style="margin-top: -10px;">
						
						<label id="hiddenInv" style="margin-bottom: -4px;font-weight: normal;margin-top: 10px;">
						
							<input style="margin-right: 5px;" type="checkbox" name="frm_laboratorio" id="frm_laboratorio" value="11" <?php echo $checked; ?> />INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS
							
							<input type="hidden" id="11" value="INVESTIGACIÓN BACTERIOLÓGICA DE TUBERCULOSIS">

						</label>
						
					</div>
				
				</div>
			
			</div>

		</div>

	</form>

</div>