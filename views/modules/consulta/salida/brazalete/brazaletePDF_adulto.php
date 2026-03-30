<iframe height="100%" width="100%" hidden>
<?php
error_reporting(0);
require("../../../../../config/config.php");
require_once('../../../../../class/Connection.class.php');  $objCon         = new Connection; $objCon->db_connect();
require_once('../../../../../class/Consulta.class.php');    $objConsulta    = new Consulta;
require_once('../../../../../class/Util.class.php');        $objUtil        = new Util;
require_once('../../../../../../estandar/TCPDF-main/tcpdf.php');
// require_once('../../../../../../estandar/tcpdf/config/lang/spa.php');

 $parametros['Iddau']=$_POST['idDau'];
 $datos=$objConsulta->consultaDAU($objCon,$parametros);

if($datos[0]['sexo']=="M"){
 	$datos[0]['sexo']="M";
 }
if($datos[0]['sexo']=="F"){
 	$datos[0]['sexo']="F";
 }
if($datos[0]['sexo']=="O"){
 	$datos[0]['sexo']="O";
 }
if($datos[0]['sexo']=="D"){
 	$datos[0]['sexo']="D";
 }
if($datos[0]['rut']){	
	$datos[0]['rut']=$objUtil->formatearNumero($datos[0]['rut']).'-'.$objUtil->generaDigito($datos[0]['rut']);
}else{	
    $datos[0]['rut']=0;
	// if($datos[0]['rut_extranjero']){
	// 	$datos[0]['rut']=$datos[0]['rut_extranjero'];
	// }
}
if($datos[0]['nombreSocial']){

 $nombre= strtoupper($datos[0]['nombreSocial']);

}else{  

 $nombre=$datos[0]['nombres'];

}

 // $nombre=$datos[0]['nombres'];
 $apellido=$datos[0]['apellidopat']." ".$datos[0]['apellidomat'];
 $rut=$datos[0]['rut'];
 $sexo=$datos[0]['sexo'];
 $cta=$datos[0]['idctacte'];

$style = array(
    'position' => '',
    'align' => 'L',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetPrintHeader(false); 
$pdf->SetPrintFooter(false);
// add a page
$resolution= array(195, 28);
//$resolution= array(400, 400);
$pdf->AddPage('P', $resolution, true, 'UTF-8', false);
// set margins
$pdf->SetMargins(0, 0, 0);
// set font
$pdf->SetFont('helvetica', '', 10);

$largo = strlen($nombre);

if($largo > 29){
	$largo = $largo-29;
	$nombre = substr($nombre, 0, -$largo);
}

$pdf->StartTransform();
$pdf->Rotate(90,94,101);
$pdf->Image(PATH.'/assets/img/hjnc2.jpg',3 ,9 , 21, 25, '', '', '', true, 150);
$pdf->Text(26, 12, $nombre);
$pdf->Text(26, 17, $apellido);
$pdf->Text(26, 22, "RUN: ".$rut);
$pdf->Text(26, 27, "SEXO: ".$sexo);
$pdf->write1DBarcode($cta, 'C128', '88', '10', '40', 22, 0.4, $style, 'N');
$pdf->StopTransform();
$pdf->setLanguageArray($l);
// $pdf->Output('Brazalete_adulto.pdf', 'FI');
// $url = "/dauRCE/views/modules/consulta/salida/brazalete/Brazalete_adulto.pdf";


$pdf->lastPage();
$nombre_archivo = 'brazaletePDF_adulto.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
// C:\inetpub\wwwroot\php8site\RCEDAU\views\modules\consulta\salida\brazalete\brazaletePDF_adulto.php
$url = "/RCEDAU/views/modules/consulta/salida/brazalete/brazaletePDF_adulto.pdf";


//============================================================+
// END OF FILE
//============================================================+

?>
</iframe>

<div class="embed-responsive embed-responsive-16by9">
	<iframe id="ifFrameBrazalete" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#ifFrameBrazalete').ready(function(){
	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>