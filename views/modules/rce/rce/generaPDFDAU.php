<iframe height="100%" width="100%" hidden>
<?php if(!isset($_SESSION)) //session_start();
error_reporting(0);
date_default_timezone_set("America/Santiago");
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
require_once('../../../../../estandar/TCPDF-main/tcpdf.php');
require_once('../../../../../estandar/tcpdf/config/lang/spa.php');
class MYPDF extends TCPDF {
//Page header
	public function Header() {
	//get the current page break margin
	$bMargin = $this->getBreakMargin();
	// get current auto-page-break mode
	$auto_page_break = $this->AutoPageBreak;
	// disable auto-page-break
	$this->SetAutoPageBreak(false, 0);
	// restore auto-page-break status
	$this->SetAutoPageBreak($auto_page_break, $bMargin);
	// set the starting point for the page content
	$this->setPageMark();
	}
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//SET DOCUMENT INFORMATION
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('DAU');
$pdf->SetTitle('PDF DAU');
$pdf->SetSubject('cccccc');
$pdf->SetKeywords('dddd, eeee, fffff');
//$pdf->SetHeaderData('../../assets/img/ABA-05.png', PDF_HEADER_LOGO_WIDTH,'SERVICIO DE SALUD ARICA ','HOSPITAL REGIONAL DE ARICA Y PARINACOTA');
$pdf->setHeaderFont(Array('helvetica', '', 6));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, 8, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(true);
$pdf->SetFont('helvetica', '', 8, '', true);
//CREA UNA PAGINA
$pdf->AddPage();
require("../../../../config/config.php");
require_once('../../../../class/Connection.class.php'); 		$objCon    		= new Connection; $objCon->db_connect();
require_once('../../../../class/Consulta.class.php'); 			$objConsulta    = new Consulta;
require_once('../../../../class/Util.class.php'); 				$objUtil    	= new Util;
require_once('../../../../class/Dau.class.php');   				$objDau      	= new Dau;
require_once('../../../../class/Admision.class.php');         	$objAdmision    = new Admision;



$parametros                 = $objUtil->getFormulario($_POST);
$listaMedicosUrgencia       = $objDau->listarMedicosUrgencia($objCon);
$listaMedicosUrgenciaAcceso = $objDau->listarMedicosUrgenciaAcceso($objCon);
$listaConsultorios          = $objAdmision->listarConsultorios($objCon);
$url = "";
if($_GET['Iddau'] && $_GET['tipo'] == "DAU"){
	$parametros['Iddau'] = $_GET['Iddau'];
	$parametros['tipo']  = $_GET['tipo'];
	$detalle     =	$objConsulta->consultaDAU($objCon, $parametros);

	//highlight_string(print_r($detalle, true));

	$detalle[0]['nombre']=$detalle[0]['servicio'];


	if($detalle[0]['sexo']=="M"){
		$detalle[0]['sexo']="Masculino";
	}

	if($detalle[0]['sexo']=="F"){
		$detalle[0]['sexo']="Femenino";
	}

	if($detalle[0]['dau_imputado']=="S"){
		$detalle[0]['dau_imputado']="SI";
	}

	if($detalle[0]['dau_paciente_domicilio_tipo']=="U"){
		$detalle[0]['tip_nombre']="Urbano";
	}else{
		$detalle[0]['tip_nombre']="Rural";
	}

	if($detalle[0]['dau_cierre_pertinencia']=="S"){
		$detalle[0]['dau_cierre_pertinencia']="SI";
	}

	if($detalle[0]['dau_cierre_entrega_postinor']=="S"){
		$detalle[0]['dau_cierre_entrega_postinor']="SI";
	}

	if($detalle[0]['dau_cierre_auge']=="S"){
		$detalle[0]['dau_cierre_auge']="SI";
	}



	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_hora']=date('H:i',strtotime($detalle[0]['dau_alcoholemia_fecha']));
	}

	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_fecha']=date('d-m-Y',strtotime($detalle[0]['dau_alcoholemia_fecha']));
	}



	if($detalle[0]['dau_alcoholemia_resultado']=="P"){
		$detalle[0]['dau_alcoholemia_resultado']="Positivo";
	}
	if($detalle[0]['dau_alcoholemia_resultado']=="N"){
		$detalle[0]['dau_alcoholemia_resultado']="Negativo";
	}

 	for ($i=0; $i <count($listaMedicosUrgencia) ; $i++) {
 		if($listaMedicosUrgencia[$i]['PROcodigo']==$detalle[0]['dau_cierre_profesional_id']){
 			$detalle[0]['dau_cierre_profesional_id']= $listaMedicosUrgencia[$i]['PROdescripcion'];
 		}
 	}

 	if($detalle[0]['rut']){
 		$detalle[0]['rut']=$objUtil->formatearNumero($detalle[0]['rut']).'-'.$objUtil->generaDigito($detalle[0]['rut']);
 	}else{
 		if($detalle[0]['rut_extranjero']){
 			$detalle[0]['rut']=$detalle[0]['rut_extranjero'];
 		}
 	}

 	$parametros['fecha_admision']= date("Y-m-d", strtotime($detalle[0]['dau_admision_fecha']));
 	$edad=$objUtil->edadActualAdmision($detalle[0]['fechanac'],$parametros['fecha_admision']);
	//$edad=$objUtil->edadActualCompleto($detalle[0]['fechanac']);
	$fechanac=$objUtil->fechaInvertida($detalle[0]['fechanac']);
	if($detalle[0]['dau_ingreso_sala_fecha']){
		$detalle[0]['dau_ingreso_sala_fecha']=date("d-m-Y h:i", strtotime($detalle[0]['dau_ingreso_sala_fecha']));
	}
}



if($parametros['tipo']=="DAU"){
	$parametros['tipo']="D.A.U";

	$detalle     =	$objConsulta->consultaDAU($objCon, $parametros);

	if($detalle[0]['dau_tipo_choque']!='0'){
		$tipo_choque = '<td width="30%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Descripcion Choque : </strong>    '.$detalle[0]['tip_choque_descripcion'].'</td>';
	}

	$detalle[0]['nombre']=$detalle[0]['servicio'];


	if($detalle[0]['sexo']=="M"){
		$detalle[0]['sexo']="Masculino";
	}

	if($detalle[0]['sexo']=="F"){
		$detalle[0]['sexo']="Femenino";
	}

	if($detalle[0]['dau_imputado']=="S"){
		$detalle[0]['dau_imputado']="SI";
	}

	if($detalle[0]['dau_paciente_domicilio_tipo']=="U"){
		$detalle[0]['tip_nombre']="Urbano";
	}else{
		$detalle[0]['tip_nombre']="Rural";
	}

	if($detalle[0]['dau_cierre_pertinencia']=="S"){
		$detalle[0]['dau_cierre_pertinencia']="SI";
	}

	if($detalle[0]['dau_cierre_entrega_postinor']=="S"){
		$detalle[0]['dau_cierre_entrega_postinor']="SI";
	}

	if($detalle[0]['dau_cierre_auge']=="S"){
		$detalle[0]['dau_cierre_auge']="SI";
	}



	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_hora']=date('H:i',strtotime($detalle[0]['dau_alcoholemia_fecha']));
	}

	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_fecha']=date('d-m-Y',strtotime($detalle[0]['dau_alcoholemia_fecha']));
	}



	if($detalle[0]['dau_alcoholemia_resultado']=="P"){
		$detalle[0]['dau_alcoholemia_resultado']="Positivo";
	}
	if($detalle[0]['dau_alcoholemia_resultado']=="N"){
		$detalle[0]['dau_alcoholemia_resultado']="Negativo";
	}

 	for ($i=0; $i <count($listaMedicosUrgencia) ; $i++) {
 		if($listaMedicosUrgencia[$i]['PROcodigo']==$detalle[0]['dau_cierre_profesional_id']){
 			$detalle[0]['dau_cierre_profesional_id']= $listaMedicosUrgencia[$i]['PROdescripcion'];
 		}
 	}

 	if($detalle[0]['rut']){
 		$detalle[0]['rut']=$objUtil->formatearNumero($detalle[0]['rut']).'-'.$objUtil->generaDigito($detalle[0]['rut']);
 	}else{
 		if($detalle[0]['rut_extranjero']){
 			$detalle[0]['rut']=$detalle[0]['rut_extranjero'];
 		}
 	}

 	$parametros['fecha_admision']= date("Y-m-d", strtotime($detalle[0]['dau_admision_fecha']));
 	$edad=$objUtil->edadActualAdmision($detalle[0]['fechanac'],$parametros['fecha_admision']);
	//$edad=$objUtil->edadActualCompleto($detalle[0]['fechanac']);
	$fechanac=$objUtil->fechaInvertida($detalle[0]['fechanac']);
	if($detalle[0]['dau_ingreso_sala_fecha']){
		$detalle[0]['dau_ingreso_sala_fecha']=date("d-m-Y h:i", strtotime($detalle[0]['dau_ingreso_sala_fecha']));
	}
	//highlight_string(print_r($detalle),true);

}else{
	//RAU-------------------------------------------------------------------------------------
	$parametros['tipo']="R.A.U";
	$parametros['fechaAdmisionAnio']=$_POST['fechaA'];
	$parametros['fechaAdmisionAnio']=date("Y", strtotime($parametros['fechaAdmisionAnio']));
	$detalle     =	$objConsulta->consultaRau($objCon, $parametros);
	//igualando variables del dau para visualizar le pdf
	 $detalle[0]['dau_admision_fecha']=$detalle[0]['fecha'];
     $detalle[0]['dau_paciente_domicilio']=$detalle[0]['direccion'];

	 $detalle[0]['ate_descripcion']=$detalle[0]['ate_descripcion'];
	 $detalle[0]['dau_motivo_descripcion']=$detalle[0]['motivoconsulta'];
	 $detalle[0]['dau_imputado']=$detalle[0]['imputado'];
	 $detalle[0]['dau_cierre_peso']=$detalle[0]['peso'];
	 $detalle[0]['dau_cierre_estatura']=$detalle[0]['talla'];
	 $detalle[0]['dau_ingreso_sala_fecha']=$detalle[0]['horabox'];
	 $detalle[0]['dau_categorizacion_actual']=$detalle[0]['cat_id'];
	 $detalle[0]['dau_cierre_pertinencia']=$detalle[0]['pertinencia'];
	 $detalle[0]['ind_egr_descripcion']=$detalle[0]['des_nombre'];
	 $detalle[0]['dau_alcoholemia_fecha']=$detalle[0]['horaalcoholemia'];
	 $detalle[0]['dau_alcoholemia_numero_frasco']=$detalle[0]['boletaalcoholemia'];
	 $detalle[0]['dau_alcoholemia_resultado']=$detalle[0]['resultadoalcoholemia'];
	 $detalle[0]['PROdescripcion']=$detalle[0]['nombremedico'];
	 $detalle[0]['dau_cierre_profesional_id']=$detalle[0]['medicotratante'];
	 $detalle[0]['dau_cierre_administrativo_observacion']=$detalle[0]['rechazar'];


	 if($detalle[0]['sexo']=="M"){
	 	$detalle[0]['sexo']="Masculino";
	 }

	 if($detalle[0]['sexo']=="F"){
	 	$detalle[0]['sexo']="Femenino";
	 }

	 if($detalle[0]['dau_imputado']==1){
		$detalle[0]['dau_imputado']="SI";
	 }else{
	 	$detalle[0]['dau_imputado']="";
	 }

	 if($detalle[0]['dau_ingreso_sala_fecha']=="00:00:00"){
	 	$detalle[0]['dau_ingreso_sala_fecha']="";
	 }

	 if($detalle[0]['dau_cierre_pertinencia']==1){
		$detalle[0]['dau_cierre_pertinencia']="SI";
	 }else{
	 	$detalle[0]['dau_cierre_pertinencia']="";
	 }

	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_hora']=date('H:i',strtotime($detalle[0]['dau_alcoholemia_fecha']));
	}

	if($detalle[0]['dau_alcoholemia_fecha']){
		$detalle[0]['dau_alcoholemia_fecha']=date('d-m-Y',strtotime($detalle[0]['fecha']));
	}


	if($detalle[0]['dau_alcoholemia_resultado']=="0"){
		$detalle[0]['dau_alcoholemia_resultado']="Negativo";
	}

	if($detalle[0]['dau_alcoholemia_resultado']=="1"){
		$detalle[0]['dau_alcoholemia_resultado']="Positivo";

	}

	for ($j=0; $j <count($listaMedicosUrgenciaAcceso) ; $j++) {
		if($listaMedicosUrgenciaAcceso[$j]['rut']==$detalle[0]['medicotratante']){
			$detalle[0]['dau_cierre_profesional_id']= $listaMedicosUrgenciaAcceso[$j]['nombremedico'];
		}
	}

	if($detalle[0]['rut']){
		$detalle[0]['rut']=$objUtil->formatearNumero($detalle[0]['rut']).'-'.$objUtil->generaDigito($detalle[0]['rut']);
	}else{
		if($detalle[0]['rut_extranjero']){
			$detalle[0]['rut']=$detalle[0]['rut_extranjero'];
		}
	}

	 //$edad=$objUtil->edadActualCompleto($detalle[0]['fechanac']);
	$parametros['fecha_admision']= date("Y-m-d", strtotime($detalle[0]['fecha']));
	$edad=$objUtil->edadActualAdmision($detalle[0]['fechanac'],$parametros['fecha_admision']);
	 $fechanac=$objUtil->fechaInvertida($detalle[0]['fechanac']);


	//highlight_string(print_r($detalle),true);
}

$manifestaciones = "";

if ( ! is_null($detalle[0]['dau_manifestaciones']) && ! empty($detalle[0]['dau_manifestaciones']) && $detalle[0]['dau_manifestaciones'] == 'S' ) {

	$manifestaciones = " (Manifestaciones)";

}

$transexual_bd   = $detalle[0]['transexual'];
$nombreSocial_bd = $detalle[0]['nombreSocial'];
$nombrePaciente	 = $detalle[0]['nombres']." ".$detalle[0]['apellidopat']." ".$detalle[0]['apellidomat'];
$infoNombre      = $objUtil->infoNombreDoc($transexual_bd,$nombreSocial_bd,$nombrePaciente);



	$html = '
	<table width="650" border="0">
		<tr>
		<td border="0" width="115">
		<pre></pre>
			<img src="'.PATH.'/assets/img/logo.png" width="55" height="55" />
			<img src="'.PATH.'/assets/img/nuestroHospital.png" width="55" height="55" />
		</td>

		<td  border="0" valign="top">
			<pre></pre>

			<tr>
				<td>GOBIERNO DE CHILE</td>
			</tr>

			<tr>
				<td>MINISTERIO DE SALUD</td>
			</tr>

			<tr>
				<td>HOSPITAL DR. JUAN NOÉ CREVANI</td>
			</tr>

			<tr>
				<td>RUT: 61.606.000-7</td>
			</tr>

			<tr>
				<td>18 DE SEPTIEMBRE N°1000</td>
			</tr>
		</td>
			<td>
				<table td width="50%" align="left" border="0">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<tr>
						<!-- <td align="right">Fecha: '.date('d-m-y').'</td> -->
					</tr>

					<tr>
						<!-- <td align="right">Hora: '.date('H:i:s', time()).'</td> -->
					</tr>
				</table>
			</td>
		</tr>
	</table>


	<table>
		<tr align="center">
			<td><strong style="margin:30px; font-size:10;">'.$parametros['tipo'].' - N°: '.$parametros['Iddau'].' </strong></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
	</table>

	<table border="0">
		<tr>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Ctacte:</strong> '.$detalle[0]['idctacte'].'</td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Fecha: </strong> '.date('d-m-Y',strtotime($detalle[0]['dau_admision_fecha'])).'</td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Hora:</strong> '.date('H:i',strtotime($detalle[0]['dau_admision_fecha'])).' </td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de Atencion:</strong> '.$detalle[0]['ate_descripcion'].'</td>

		</tr>
	</table>
	<br>

	<table border="0">
		<tr>
			<td><strong style="font-size:10; color: ">DATOS PERSONALES DEL PACIENTE</strong><hr></td>

	    </tr>

	</table>
	<br>

	<table border="0"  >
	    <tr>
	    	<td width="14%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Nombre Paciente: </strong> </td>
	    	<td width="51%">'.$infoNombre.'</td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Rut: </strong> '.$detalle[0]['rut'].'</td>
	    </tr>

	    <tr>
	    	<td width="27%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Sexo: </strong> '.$detalle[0]['sexo'].'</td>
	    	<td width="38%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Edad: </strong> '.$edad.'</td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Nacimiento: </strong>'.$fechanac.'</td>
	    </tr>

	     <tr>
	    	<td width="27%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Telefono: </strong> '.$detalle[0]['fono1'].'</td>
	    	<td width="38%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Celular: </strong>'.$detalle[0]['PACfono'].'</td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">N°ficha: </strong>'.$detalle[0]['nroficha'].'</td>
	    </tr>

	    <tr>
	    	<td width="65%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Domicilio: </strong>'.$detalle[0]['dau_paciente_domicilio'].'</td>
	    	<td width="48%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Consultorio: </strong> '.$detalle[0]['con_descripcion'].'</td>
	    	<td width="25%"></td>
	    </tr>

	     <tr>
	    	<td width="27%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de Zona: </strong>'.$detalle[0]['tip_nombre'].' </td>
	    	<td width="38%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Prevision: </strong>'.$detalle[0]['prevision'].' </td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Pais: </strong>'.$detalle[0]['NACpais'].'</td>
	    </tr>


	     <tr>
	    	<td width="27%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Etnia: </strong>'.$detalle[0]['etnia_descripcion'].'</td>
	    	<td width="38%"></td>
	    	<td width="25%"></td>
	    </tr>





	</table>
	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">CONDICIONES INICIALES</strong><hr></td>
		</tr>
	</table>
	<br>

 ';


 $html.='
 	<table border="0" >

	    <tr>
	    	<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Paciente llega en: </strong> '.$detalle[0]['med_descripcion'].'</td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Motivo Consulta: </strong>'.$detalle[0]['mot_descripcion'].''.$manifestaciones.'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Motivo Descripcion: </strong>'.$detalle[0]['dau_motivo_descripcion'].'</td>
	    </tr>



	</table>
';

// DAU-------------------------------------------------------------------------------------------------------------------------------
if($detalle[0]['dau_motivo_consulta']==1 && $parametros['tipo']=="D.A.U" && $detalle[0]['dau_tipo_accidente']==1){ // cuando es accidente ESCOLAR DAU

	$parametros['tip_id']=$detalle[0]['dau_tipo_accidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['dau_accidente_escolar_institucion']){
			$detalle[0]['dau_accidente_escolar_institucion']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}

	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Institución: </strong>  '.$detalle[0]['dau_accidente_escolar_institucion'].'  </td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Numero : </strong>  '.$detalle[0]['dau_accidente_escolar_numero'].'  </td>

		</tr>

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Nombre </strong> '.$detalle[0]['dau_accidente_escolar_nombre'].'  </td>

		</tr>

	</table>
	';
}


if($detalle[0]['dau_motivo_consulta']==1 && $parametros['tipo']=="D.A.U" && $detalle[0]['dau_tipo_accidente']==2){ // cuando es accidente TRABAJO DAU

	$parametros['tip_id']=$detalle[0]['dau_tipo_accidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['dau_accidente_trabajo_mutualidad']){
			$detalle[0]['dau_accidente_trabajo_mutualidad']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}

	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Mutualidad: </strong>  '.$detalle[0]['dau_accidente_trabajo_mutualidad'].'  </td>
			<td> </td>

		</tr>

	</table>
	';
}

if($detalle[0]['dau_motivo_consulta']==1 && $parametros['tipo']=="D.A.U" && $detalle[0]['dau_tipo_accidente']==3){ // cuando es accidente TRANSITO DAU

	$parametros['tip_id']=$detalle[0]['dau_tipo_accidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['dau_accidente_trabajo_mutualidad']){
			$detalle[0]['dau_accidente_trabajo_mutualidad']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}

	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo : </strong>  '.$detalle[0]['tran_descripcion'].'  </td>
			'.$tipo_choque.'

		</tr>



	</table>
	';
}


if($detalle[0]['dau_motivo_consulta']==1 && $parametros['tipo']=="D.A.U" && $detalle[0]['dau_tipo_accidente']==4){ // cuando es accidente HOGAR DAU

	$parametros['tip_id']=$detalle[0]['dau_tipo_accidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['dau_accidente_hogar_lugar']){
			$detalle[0]['dau_accidente_hogar_lugar']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}

	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Se produjo en : </strong>  '.$detalle[0]['dau_accidente_hogar_lugar'].'  </td>
			<td></td>

		</tr>


	</table>
	';
}


if($detalle[0]['dau_motivo_consulta']==1 && $parametros['tipo']=="D.A.U" && $detalle[0]['dau_tipo_accidente']==5){ // cuando es accidente HOGAR DAU

	$parametros['tip_id']=$detalle[0]['dau_tipo_accidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['dau_accidente_otro_lugar']){
			$detalle[0]['dau_accidente_otro_lugar']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}

	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Lugar Publico : </strong>  '.$detalle[0]['dau_accidente_otro_lugar'].'  </td>
			<td></td>

		</tr>


	</table>
	';
}

// RAU--------------------------------------------------------------------------------------------------------------------------------
if($detalle[0]['tipoconsulta']==1 && $parametros['tipo']!="DAU" && $detalle[0]['tipoaccidente']==1){ // cuando es accidente ESCOLAR RAU

	$parametros['tip_id']=$detalle[0]['tipoaccidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['escolar_tipoinstitucion']){
			$detalle[0]['escolar_tipoinstitucion']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}
	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Institución: </strong>  '.$detalle[0]['escolar_tipoinstitucion'].'  </td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Numero : </strong>  '.$detalle[0]['escolar_nro'].'  </td>

		</tr>

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Nombre </strong> '.$detalle[0]['escolar_nombre'].'  </td>

		</tr>

	</table>
	';
}

if($detalle[0]['tipoconsulta']==1 && $parametros['tipo']!="DAU" && $detalle[0]['tipoaccidente']==2){ // cuando es accidente Trabajo RAU

	$parametros['tip_id']=$detalle[0]['tipoaccidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['mutualidad']){
			$detalle[0]['mutualidad']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}
	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Mutualidad: </strong>  '.$detalle[0]['mutualidad'].'  </td>
			<td> </td>

		</tr>

	</table>
	';
}

if($detalle[0]['tipoconsulta']==1 && $parametros['tipo']!="DAU" && $detalle[0]['tipoaccidente']==3){ // cuando es accidente TRANSITO RAU
	if($detalle[0]['transito_colision']!=""){
		$detalle[0]['transito_colision']="SI";
	}
	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Atropellado : </strong>  '.$detalle[0]['atr_nombre'].'  </td>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Chocado : </strong>  '.$detalle[0]['cho_nombre'].'  </td>

		</tr>

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Colision </strong> '.$detalle[0]['transito_colision'].'  </td>

		</tr>

	</table>
	';
}

if($detalle[0]['tipoconsulta']==1 && $parametros['tipo']!="DAU" && $detalle[0]['tipoaccidente']==4){ // cuando es accidente HOGAR RAU

	$parametros['tip_id']=$detalle[0]['tipoaccidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['hogar_tipo']){
			$detalle[0]['hogar_tipo']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}


	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Se produjo en : </strong>  '.$detalle[0]['hogar_tipo'].'  </td>
			<td></td>

		</tr>


	</table>
	';
}

if($detalle[0]['tipoconsulta']==1 && $parametros['tipo']!="DAU" && $detalle[0]['tipoaccidente']==5){ // cuando es accidente OTRO RAU

	$parametros['tip_id']=$detalle[0]['tipoaccidente'];
	$listaInstitucion=$objDau->listaInstituciones($objCon,$parametros);

	for ($i=0; $i < count($listaInstitucion) ; $i++) {
		if($listaInstitucion[$i]['ins_id']==$detalle[0]['otro_tipo']){
			$detalle[0]['otro_tipo']= $listaInstitucion[$i]['ins_descripcion'];
		}
	}


	$html.='
	<table border="0"  >

		<tr>
			<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Tipo de accidente: </strong> '.$detalle[0]['sub_mot_descripcion'].'  </td>
			<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Lugar Público : </strong>  '.$detalle[0]['otro_tipo'].'  </td>
			<td></td>

		</tr>


	</table>
	';
}


$html.='
<table border="0" >
		     <tr>
	    	<td width="40%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Mordedura: </strong>'.$detalle[0]['mor_descripcion'].'</td>
	    	<td width="25%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Intoxicación: </strong>'.$detalle[0]['int_descripcion'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Quemado: </strong>'.$detalle[0]['que_descripcion'].'</td>

	    </tr>

	       <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Imputado: </strong> '.$detalle[0]['dau_imputado'].'</td>
	    	<td></td>
	    	<td></td>

	    </tr>
</table>

';

$html.='

	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">DATOS CLINICOS</strong><hr></td>
		</tr>
	</table>
	<br>

 <table border="0" >

	    <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Peso:</strong> '.$detalle[0]['dau_cierre_peso'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Talla:</strong> '.$detalle[0]['dau_cierre_estatura'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Atendido Por:</strong> '.$detalle[0]['ate_atendidopor_nombre'].'</td>
	    </tr>

	     <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Hora ingreso a Box:</strong> '.$detalle[0]['dau_ingreso_sala_fecha'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Condición Ingreso:</strong> '.$detalle[0]['con_ingreso_nombre'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Categorización: </strong>'.$detalle[0]['dau_categorizacion_actual'].'</td>
	    </tr>

	     <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Derivación:</strong> '.$detalle[0]['der_nombre'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Pronóstico: </strong>'.$detalle[0]['pro_pronostico_nombre'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Pertinencia: </strong> '.$detalle[0]['dau_cierre_pertinencia'].'</td>
	    </tr>

	     <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Estado Etilico: </strong>'.$detalle[0]['eti_descripcion'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Entrega Postinor: </strong>'.$detalle[0]['dau_cierre_entrega_postinor'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Auge: </strong>'.$detalle[0]['dau_cierre_auge'].'</td>
	    </tr>

	      <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Destino: </strong>'.$detalle[0]['ind_egr_descripcion'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Servicio: </strong> '.$detalle[0]['nombre'].'</td>
	    	<td></td>
	    </tr>

	</table>
	';

$html.='
	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">TRATAMIENTO</strong><hr></td>
		</tr>
	</table>
	<br>

	<table border="0" >

	    <tr>
	    	<td>'.$detalle[0]['tra_tratamiento_nombre'].'</td>
	    </tr>


	</table>

	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">ALCOHOLEMIA</strong><hr></td>
		</tr>
	</table>
	<br>

	<table border="0" >

	    <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Número de frasco: </strong>'.$detalle[0]['dau_alcoholemia_numero_frasco'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Resultado:</strong> '.$detalle[0]['dau_alcoholemia_resultado'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Hora: </strong>'.$detalle[0]['dau_alcoholemia_hora'].'</td>

	    </tr>

	     <tr>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Fecha: </strong>'.$detalle[0]['dau_alcoholemia_fecha'].'</td>
	    	<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Medico: </strong>'.$detalle[0]['PROdescripcion'].'</td>
	    	<td></td>
	    </tr>


	</table>

	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">DIAGNOSTICO</strong><hr></td>
		</tr>
	</table>
	<br>
	<table border="0" >

		<tr>
			<td width="20%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Codigo CIE: </strong>'.$detalle[0]['idcie10'].'</td>
			<td width="80%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Descripcion: </strong>'.$detalle[0]['cie10_nombre'].'</td>
			<!-- <td></td>	 -->
		</tr>
';
if($detalle[0]['dau_cierre_fundamento_diag'] <> ''){
$html.='<tr>
			<td width="20%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Fundamento Diag </strong></td>
			<td width="80%"><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Descripcion: </strong>'.$detalle[0]['dau_cierre_fundamento_diag'].'</td>
		</tr>';
}
$html.='

	</table>
	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">MEDICO TRATANTE</strong><hr></td>
		</tr>
	</table>
	<br>
<br>

	<table border="0" >

		<tr>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Medico: </strong>'.$detalle[0]['dau_cierre_profesional_id'].'</td>

		</tr>

	</table>

	<br>
	<table border="0">
		<tr>
			<td><br><br><strong style="font-size:10;">GLOSA DE DERIVACION, N.E.A. O NULO</strong><hr></td>
		</tr>
	</table>
	<br>

	<table border="0" >

		<tr>
			<td><strong style="color: black; font-family: "SourceSansPro-Semibold", Fallback, sans-serif; ">Observacion: </strong>'.$detalle[0]['dau_cierre_administrativo_observacion'].'</td>

		</tr>

	</table>';




$pdf->writeHTML($html, true, false, true, false, '');

$nombre_archivo = "DAU.pdf";
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');

$url = "/RCEDAU/views/modules/rce/rce/DAU.pdf";
// unlink($nombre_archivo);
?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframePDFDAU" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>