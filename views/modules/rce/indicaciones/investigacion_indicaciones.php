<?php
	session_start();
	ini_set('max_execution_time', 600);
	ini_set('memory_limit', '128M');
	require("../../../../config/config.php");
	require_once('../../../../class/Connection.class.php'); $objCon   = new Connection;$objCon->db_connect();
	require_once('../../../../class/Util.class.php'); $objUtil = new Util;
	require_once('../../../../class/Categorizacion.class.php'); $objCat = new Categorizacion;
	$rsPac = $objCat->searchPaciente($objCon, $_POST['dau_id']);
	// highlight_string(print_r($_POST['dau_id'],true));
?>
<!-- <script type="text/javascript" src="<?=PATH?>/controllers/client/rce/indicaciones/laboratorio/laboratorio.js?v=0.0.5"></script> -->

<div id="contenidoInvBact" style="">
	<fieldset style="background-color: #ffffff !important;border: 0px !important">
		<form id="frm_investigacion" name="frm_investigacion">
			<div class="row">
				<!-- <h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> 1. Identificación</h6> -->

				<label class="encabezado2 mb-0 pb-0 col-lg-12"><h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> 1. Identificación <i class="fas fa-edit ml-1 " style="color:#e57e88;"></i> </h6></label>
				<div class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">Nombre</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;<?=$rsPac[0]['nombres']." ".$rsPac[0]['apellidopat']." ".$rsPac[0]['apellidomat']?></label>
				</div>
				<div class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">Edad</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;<?=$objUtil -> edadActualCompleto($rsPac[0]['fechanac']); ?></label>
				</div>
				<div class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">Sexo</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;<?php if($rsPac[0]['sexo'] == "M"){echo "Masculino";}else if($rsPac[0]['sexo'] == "F"){echo "Femenino";}?></label>
				</div>
				<div  class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">Domicilio</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;<?=$rsPac[0]['direccion']?></label>
				</div>
				<div  class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">Procedencia</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;</label>
				</div>
				<div  class="col-md-6">
					<label class="encabezado2 mb-0 pb-0 col-lg-2">F. Clínica</label><label class="mifuente mb-0 pb-0 col-lg-8">:&nbsp;&nbsp;</label>
				</div>
				<div class="col-md-12 mt-1">
					<label class="encabezado2 mb-0 pb-0 col-lg-12">¿Pertenece usted a alguno de los siguientes pueblos originarios o indígena?</label>
				</div>
				<div class="col-md-12 mr-3 ml-3 mt-1">
					<div class="radio row" style="">
						<div class="col-lg-2">
							<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi_1" id="frm_lab_poi_1" value="S"/>Alacalufe</label>
						</div>
						<div class="col-lg-2">
							<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi_2" id="frm_lab_poi_2" value="S"/>Atacameño</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi_3" id="frm_lab_poi_3" value="S"/>Aymara</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi_4" id="frm_lab_poi_4" value="S"/>Colla</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S"/>Diaguita</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S"/>Mapuche</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S"/>Quechua</label>
						</div>
						<div class="col-lg-2">
						<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S"/>Rapa Nui</label>
						</div>
						<div class="col-lg-2">
							<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S"/>Yámana (Yagán)</label>
						</div>
						<div class="col-lg-4">
							<label class="mifuente mb-0 pb-012"><input type="checkbox" class="mr-2" name="frm_lab_poi" id="frm_lab_poi" value="S0"/>Ninguna de las anteriores</label>
						</div>
					</div>
				</div>
			</div>
			<hr class="mt-2 mb-2">
			<div class="row">
				<label class="encabezado2 mb-0 pb-0 col-lg-6"><h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> 2. Muestra <i class="fas fa-edit ml-1 " style="color:#e57e88;"></i> </h6></label>
				

				<!-- <label class="encabezado2 mb-0 pb-0 col-lg-6">2. Muestra</label> -->
				<label class="encabezado2 mb-0 pb-0 col-lg-6">Calidad de Muestra</label>
				<div class="col-md-3">
					<div class="radio col-lg-12">
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_muestra" id="frm_lab_muestra" value="S" class="mr-3" />Expectoración</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_muestra" id="frm_lab_muestra" value="S" class="mr-3" />Otra</label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="radio row">
						<label class="mifuente mb-0 pb-0 col-lg-4"><input type="radio" name="frm_lab_muestraexp" id="frm_lab_muestraexp" value="S" class="mr-3" />1°</label>
						<label class="mifuente mb-0 pb-0 col-lg-6"><input type="radio" name="frm_lab_muestraexp" id="frm_lab_muestraexp" value="S" class="mr-3" />2°</label>
					</div>
				</div>
				<div class="col-md-6" style="">
					<div class="radio">
						<label class="mifuente mb-0 pb-0 col-lg-12"><input type="checkbox" name="frm_lab_muestracal" id="frm_lab_muestracal" value="S" class="mr-3" />Saliva</label><br/>
						<label class="mifuente mb-0 pb-0 col-lg-12"><input type="checkbox" name="frm_lab_muestracal" id="frm_lab_muestracal" value="S" class="mr-3" />Mucosa</label><br/>
						<label class="mifuente mb-0 pb-0 col-lg-12"><input type="checkbox" name="frm_lab_muestracal" id="frm_lab_muestracal" value="S" class="mr-3" />Mucopurulenta</label>
					</div>
				</div>
			</div>
			<hr class="mt-2 mb-2">
			<div class="row">
				<label class="encabezado2 mb-0 pb-0 col-lg-6"><h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> 3. Antecedentes de Tratamiento <i class="fas fa-edit ml-1 " style="color:#e57e88;"></i> </h6></label>

				<!-- <label class="encabezado2 mb-0 pb-0 col-lg-12">3. Antecedentes de Tratamiento</label> -->
				<div class="col-md-12">
					<div class="radio row">
						<label class="mifuente mb-0 pb-0  col-lg-2  ml-3"><input type="radio" name="frm_lab_anttrat" id="frm_lab_anttrat" value="S" class="mr-3" />Virgen a tratamiento</label>
						<label class="mifuente mb-0 pb-0  col-lg-6 "><input type="radio" name="frm_lab_anttrat" id="frm_lab_anttrat" value="S" class="mr-3" />Antes tratado</label>
					</div>
				</div>
			</div>
			<hr class="mt-2 mb-2">
			<div class="row">
				<label class="encabezado2 mb-0 pb-0 col-lg-6"><h6 id="ensure-correct-role-and-provide-a-label" style=" border-radius: inherit; font-size:15px;"> 4. Razones del Examen y Grupo de Riesgo <i class="fas fa-edit ml-1 " style="color:#e57e88;"></i> </h6></label>
				<!-- <label class="encabezado2 mb-0 pb-0 col-lg-6">4. Razones del Examen y Grupo de Riesgo</label> -->
				<label class="encabezado2 mb-0 pb-0 col-lg-6">Identificar Grupo de Riesgo</label>
				<div class="col-md-6">
					<div class="radio ml-3">
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_razexa" id="frm_lab_razexa" value="S" class="mr-3" />Pesquisa de sintomático respiratorio</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_razexa" id="frm_lab_razexa" value="S" class="mr-3" />Investigación de contacto</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_razexa" id="frm_lab_razexa" value="S" class="mr-3" />Control de tratamiento</label>
					</div>
				</div>
				<div class="col-md-6" style="">
					<div class="radio ml-3">
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />GES 18</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Diabético o con otra inmunosupresión</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Situación de calle</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Población cautiva</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Extranjero</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Pueblo indígena</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Persona con Problemas de Alcoholismo y drogadicción</label><br/>
						<label class="mifuente mb-0 pb-0"><input type="checkbox" name="frm_lab_idegru" id="frm_lab_idegru" value="S" class="mr-3" />Contacto de TBC</label>
					</div>
				</div>
			</div>
		</form>
	</fieldset>
</div>