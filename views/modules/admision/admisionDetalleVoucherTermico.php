<iframe height="100%" width="100%" hidden>
<?php
session_start();
error_reporting(0);
ini_set('post_max_size', '512M'); 
ini_set('memory_limit', '1G'); 
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require("../../../config/config.php");
require_once('../../../../estandar/tcpdf/tcpdf.php');
require_once('../../../../estandar/tcpdf/config/lang/spa.php');
require_once('../../../class/Connection.class.php'); $objCon      = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       $objUtil     = new Util;
require_once('../../../class/Admision.class.php');   $objAdmision = new Admision;




$parametros['dau_id'] = $_POST['id'];
$fechaActual          = date('d-m-Y');
$horaActual           = date("G:i:s");
$datos                = $objAdmision->listarDatosDau($objCon,$parametros);
$version    		  = $objUtil->versionJS();

//CREACION DEL DOCUMENTO PDF
$pdf = new TCPDF('', 'mm', PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetAutoPageBreak(FALSE,0);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(0, 0, 1, 1);

//RESOLUCION DEL VOUCHER
$resolution= array(80, 240);

$y = 40;
$pdf->AddPage('', $resolution);



//ENCABEZADO DEL DOCUMENTO
$pdf->SetFont('helvetica', '', 9);
$pdf->MultiCell(65, 1,'HOSPITAL REGIONAL DE ARICA DR. JUAN NOÉ CREVANI', 0, 'L', false, 1, 1, 2, false, 0, false, false, false, 'T', true);
$pdf->SetFont('helvetica', '', 9);
$pdf->writeHTMLCell(0, 0, 0, 2, date('d-m-Y', strtotime($datos[0]['dau_admision_fecha'])), 0, 0, false, true, 'R', true);
$pdf->writeHTMLCell(0, 0, 0, 5, date('H:i:s', strtotime($datos[0]['dau_admision_fecha'])), 0, 0, false, true, 'R', true);
$pdf->writeHTMLCell(0, 0, 0, 8, $datos[0]['dau_admision_usuario'], 0, 0, false, true, 'R', true);
$pdf->SetFont('helvetica', '', 15);
$pdf->writeHTMLCell(0, 0, 1, 12, 'DETALLE ADMISIÓN', 0, 0, false, true, 'C', true);
$pdf->SetFont('helvetica', '', 20);


//IDENTIFICACION DEL PACIENTE
//Folio
$pdf->MultiCell(40, 1,'FOLIO', 0, 'L', false, 1, 1, 20, false, 0, false, false, false, 'T', true);
$pdf->MultiCell(80, 1,': '.$datos[0]['dau_id'], 0, 'L', false, 1, 35, 20, false, 0, false, false, false, 'T', true);

//Cuenta Corriente
$pdf->MultiCell(40, 1,'CTACTE', 0, 'L', false, 1, 1, 28, false, 0, false, false, false, 'T', true);
$pdf->MultiCell(80, 1,': '.$datos[0]['idctacte'] , 0, 'L', false, 1, 35, 28, false, 0, false, false, false, 'T', true);

//Nombre Paciente
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Paciente', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['nombres'].' '.$datos[0]['apellidopat'].' '.$datos[0]['apellidomat'], 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Rut Paciente
if($datos[0]['extranjero']=="S" && $datos[0]['rut']!="0" && $datos[0]['rut_extranjero']!=""){
	$rutPaciente = $datos[0]['rut'].'-'.$objUtil->generaDigito($datos[0]['rut']);
}else{
	if(($datos[0]['rut'] || $datos[0]['rut']==0) && $datos[0]['extranjero']!="S"){
		$rutPaciente = $datos[0]['rut'].'-'.$objUtil->generaDigito($datos[0]['rut']);
	}else{
		$rutPaciente = $datos[0]['rut_extranjero'];
	}
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'RUT', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $rutPaciente, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Fecha Nacimiento
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Fech Nac', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, date("d-m-Y", strtotime($datos[0]['fechanac'])), 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Edad
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Edad', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $objUtil->edad_paciente($datos[0]['fechanac']), 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Sexo
if($datos[0]['sexo']=="M"){
	$sexoPaciente =  "MASCULINO";
}else{
	if($datos[0]['sexo']=="F"){
	$sexoPaciente =  "FEMENINO";
	}

	if($datos[0]['sexo']=="O"){
		$sexoPaciente =  "INDETERMINADO";
	}

	if($datos[0]['sexo']=="D"){
		$sexoPaciente = "DESCONOCIDO";
	}
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Sexo', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $sexoPaciente, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;


//DATOS ANEXOS PACIENTE
//Calle
if(!is_null($datos[0]['calle']) || !empty($datos[0]['calle'])){ 
	$callePaciente =  $datos[0]['calle'];
}
else{
	$callePaciente =  "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Calle', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $callePaciente, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Número de calle
if(!is_null($datos[0]['numero']) || !empty($datos[0]['numero'])){ 
	$numeroCallePaciente =  $datos[0]['numero'];
}
else{
	$numeroCallePaciente = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Número', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $numeroCallePaciente, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Resto de dirección
if(!is_null($datos[0]['restodedireccion']) && !empty($datos[0]['restodedireccion'])){ 
	$restoDireccion = $datos[0]['restodedireccion'];
}
else if (!is_null($datos[0]['dau_paciente_domicilio']) && !empty($datos[0]['dau_paciente_domicilio']) ) {
	$restoDireccion = $datos[0]['dau_paciente_domicilio'];
}
else {
	$restoDireccion = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Rest Direc', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $restoDireccion, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Sector domicilio
if(!is_null($datos[0]['sector_domicilio']) && !empty($datos[0]['sector_domicilio'])){ 
	$sectorDomicilio =  $datos[0]['descripcion_sector_domiciliario'];
}
else{
	$sectorDomicilio = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Sector', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $sectorDomicilio, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Tipo domicilio
if(!is_null($datos[0]['dau_paciente_domicilio_tipo']) && !empty($datos[0]['dau_paciente_domicilio_tipo'])){ 
	if($datos[0]['dau_paciente_domicilio_tipo'] == "R"){
		$tipoDomicilio = "RURAL";
	}
	else if($datos[0]['dau_paciente_domicilio_tipo'] == "U"){
		$tipoDomicilio = "URBANO";
	}
}
else{
	$tipoDomicilio = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Tipo Dom', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $tipoDomicilio, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Teléfono fijo
if($datos[0]['PACfono']==0){ 
	$telefonoFijo = "Fijo No Definido";
}
else{ 
	$telefonoFijo = $datos[0]['PACfono']; 
} 
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Tel Fijo', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $telefonoFijo, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Teléfono celular
if($datos[0]['fono1']==0){
	$telefonoCelular = "Celular No Definido";
}
else{
	$telefonoCelular = $datos[0]['fono1'];
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Celular', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $telefonoCelular, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Afrodescendiente
if(!is_null($datos[0]['PACafro']) || !empty($datos[0]['PACafro'])){  
	if($datos[0]['PACafro'] == 0){
		$afrodescendiente = "NO";
	}
	else{
		$afrodescendiente = "SI";
	}
}
else{
	$afrodescendiente = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Afrodesc.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $afrodescendiente, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Etnia
if($datos[0]['etn_descripcion']!=""){ 
	$etnia = $datos[0]['etn_descripcion'];
}
else{ 
	$etnia = "No definido"; 
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Etnia.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $etnia, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//País nacimiento
if(!is_null($datos[0]['NACpais']) || !empty($datos[0]['NACpais'])){ 
	$paisNacimiento = $datos[0]['NACpais'];
}
else{
	$paisNacimiento = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'País Nac.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $paisNacimiento, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Nacionalidad
if(!is_null($datos[0]['NACdescripcion']) || !empty($datos[0]['NACdescripcion'])){ 
	$nacionalidad = $datos[0]['NACdescripcion'].'(A)';
}
else{
	$nacionalidad = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Nac.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $nacionalidad, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Región
if(!is_null($datos[0]['REG_Descripcion']) || !empty($datos[0]['REG_Descripcion'])){ 
	$region = ucwords(mb_strtolower($datos[0]['REG_Descripcion'], "UTF-8"));
}
else{
	$region = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Región.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $region, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Ciudad
if(!is_null($datos[0]['CIU_Descripcion']) || !empty($datos[0]['CIU_Descripcion'])){ 
	$ciudad = ucwords(mb_strtolower($datos[0]['CIU_Descripcion'], "UTF-8"));
}
else{
	$ciudad = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Ciudad.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $ciudad, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Comuna
if(!is_null($datos[0]['comuna']) || !empty($datos[0]['comuna'])){ 
	$comuna = ucwords(mb_strtolower($datos[0]['comuna'], "UTF-8"));
}
else{
	$comuna = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Comuna.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $comuna, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Consultorio
if(!is_null($datos[0]['comuna']) || !empty($datos[0]['comuna'])){ 
	$comuna = ucwords(mb_strtolower($datos[0]['comuna'], "UTF-8"));
}
else{
	$comuna = "No Definido";
}
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Comuna.', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $comuna, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Previsión
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Previsión', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['prevision'], 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Forma pago
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Pago', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['instNombre'], 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Tipo atención
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Atención', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['ate_descripcion'], 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Consultorio
if($datos[0]['con_descripcion']!=""){
	$consultorio = $datos[0]['con_descripcion']; 
}
else{ 
	$consultorio = "No Definido";
}
$consultorio = ucwords(mb_strtolower($consultorio, "UTF-8"));
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Consultorio', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $consultorio, 0, 0, false, true, 'L', true);
//29
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//LLega en...
$llegaEn = ucwords(mb_strtolower($datos[0]['med_descripcion'], "UTF-8"));
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Llega En', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $llegaEn, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;


//Motivo consulta
$motivoConsulta = ucwords(mb_strtolower($datos[0]['mot_descripcion'].' - '.$datos[0]['dau_motivo_descripcion'], "UTF-8"));
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTMLCell(0, 0, 1, $y, 'Motivo', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
$pdf->writeHTMLCell(0, 0, 24, $y, $motivoConsulta, 0, 0, false, true, 'L', true);
$saltoLinea = ceil($pdf->getLastH());
$y += $saltoLinea;

//Mordedura
if($datos[0]['mor_descripcion']!=''){
	$pdf->SetFont('helvetica', '', 12);
	$pdf->writeHTMLCell(0, 0, 1, $y, 'Mordedura', 0, 0, false, true, 'L', true);
	$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
	$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['mor_descripcion'], 0, 0, false, true, 'L', true);
	$saltoLinea = ceil($pdf->getLastH());
	$y += $saltoLinea;;
}

//Intoxicación
if($datos[0]['int_descripcion']!=''){

	if($datos[0]['que_descripcion']!=''){
		$datosIntoxicacion = ' - '.$datos[0]['que_descripcion'];
	}
	$pdf->SetFont('helvetica', '', 12);
	$pdf->writeHTMLCell(0, 0, 1, $y, 'Intox.', 0, 0, false, true, 'L', true);
	$pdf->writeHTMLCell(0, 0, 22, $y, ':', 0, 0, false, true, 'L', true);
	$pdf->writeHTMLCell(0, 0, 24, $y, $datos[0]['int_descripcion'].$datosIntoxicacion, 0, 0, false, true, 'L', true);
}

$nombre_archivo = 'detalleAdmisionVoucherTermico.pdf';
$pdf->Output($nombre_archivo,'FI');

$url = "/dauRCE/views/modules/admision/detalleAdmisionVoucherTermico.pdf";
?>
</iframe>
<div class="embed-responsive embed-responsive-16by9">
	<iframe id="iframePDFDAU" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>