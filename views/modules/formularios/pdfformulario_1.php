<iframe height="100%" width="100%" hidden>

<?php

//session_start();
error_reporting(0);
ini_set('post_max_size', '512M');
ini_set('memory_limit', '1G');
set_time_limit(0);
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");

require_once('../../../../estandar/TCPDF-main/tcpdf.php');
require("../../../config/config.php");
require_once('../../../class/Connection.class.php'); 			   $objCon      			     = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');       			   $objUtil     			     = new Util;
require_once('../../../class/Admision.class.php');   			   $objAdmision 			     = new Admision;
require_once('../../../class/RegistroClinico.class.php'); 	 $objRegistroClinico		 = new RegistroClinico;
require_once("../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../class/HojaEnfermeria.class.php');     $objHoja_enfermeria     = new Hoja_enfermeria;
require_once('../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../class/Diagnosticos.class.php');       $objDiagnosticos        = new Diagnosticos;
require_once('../../../class/Formulario_1.class.php');       $objFormulario_1        = new Formulario_1;
require_once('../../../class/FormPacienteGes.class.php');       $objFormPacienteGes        = new FormPacienteGes;


$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$PACGESid                         = $_POST['PACGESid'];
// $datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
// $horarioServidor                = $objUtil->getHorarioServidor($objCon);
// $datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
// $rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
// $parametros['cta_cte']          = $datosU[0]['idctacte'];
// $rsRce_diagnostico              = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);

$rsFormulario1                  = $objFormPacienteGes->SelectByIdFormPacienteGesSub($objCon,$PACGESid);

class MYPDF extends TCPDF {
    //Page header
    public function Test( $ae ) {
        if( !isset($this->xywalter) ) {
            $this->xywalter = array();
        }
        $this->xywalter[] = array($this->GetX(), $this->GetY());
    }
}

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HJNC-RCE');
$pdf->SetTitle('Datos de Atención de Urgencia DAU');
$pdf->SetSubject('Formularios');
$pdf->SetKeywords('RCE, Formularios');
//$pdf->SetHeaderData('logo_informe2.jpg', PDF_HEADER_LOGO_WIDTH,'HOSPITAL REGIONAL DE ARICA Y PARINACOTA','FORMULARIO DE CONSTANCIA INFORMACION AL PACIENTE GES');
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 3, 10);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 10, '', true);

// add a page
$pdf->AddPage();

if($rsFormulario1[0]['prevision'] == 1 || $rsFormulario1[0]['prevision'] == 2  || $rsFormulario1[0]['prevision'] == 3  || $rsFormulario1[0]['prevision'] == 4){
  // $rsFormulario1[0]['aseguradora']            = 'FONASA';
  $rsFormulario1[0]['FONASA'] = 'X';
}else{
  // $rsFormulario1[0]['aseguradora']            = 'ISAPRE';
  $rsFormulario1[0]['ISAPRE'] = 'X';
}

if($rsFormulario1[0]['PACGESconfDiagn'] =='Sí'){
  $rsFormulario1[0]['confirmacion_diagnostico_check'] = 'X';
}
if($rsFormulario1[0]['PACGEStratamiento'] =='Sí'){
  $rsFormulario1[0]['paciente_tratamiento_check'] = 'X';
}
// PROnombres
// PROapellidopat
$rsFormulario1[0]['nombre_medico']        = $rsFormulario1[0]['PROnombres']." ".$rsFormulario1[0]['PROapellidopat'];
$rsFormulario1[0]['rut_paciente']         = $objUtil->rutDigito($rsFormulario1[0]['rut']); 
$rsFormulario1[0]['PACGESprofesional']    = $objUtil->rutDigito($rsFormulario1[0]['PACGESprofesional']); 

$html = '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario GES</title>
  <style>
    
    .textoPequeño{
      font-size: 9px !important;
    }
    .textoNormal{
      font-size: 10.4px !important;
    }
    .textoGrande{
      font-size: 12px !important;
    }
    .textoGigante{
      font-size: 16px !important;
    }
    .checkbox {
      display: inline-block;
      margin-right: 10px;
    }
  </style>
  <style>
    .borde-inferior {
        border-bottom: 1px solid #000; /* Puedes ajustar el color y grosor */
        padding-bottom: 2px;
    }
</style>
</head>
<body>
<table >
  <tr>
    <td>
      <p class="textoGigante" align="center"><b>FORMULARIO DE CONSTANCIA INFORMACIÓN AL PACIENTE GES</b></p>
    </td>
  </tr>
  <tr>
    <td>
      <p class="textoGrande" align="center"><b>(Artículo 24°, Ley 19.966)</b></p>
    </td>
  </tr>
</table>

<hr>
  <table cellpadding="2" >
  <tr><td colspan="3" class="textoNormal">Datos del prestador</td></tr>
  <tr>
    <td colspan="3" class="textoNormal">Nombre de la Institución:</td>
  </tr>
  <tr>
    <td colspan="3"><strong class="textoGigante">Hospital Regional de Arica “Dr. Juan Noe Crevani”</strong></td>
  </tr>
  <tr>
    <td colspan="3" class="textoNormal">Dirección:</td>
  </tr>
  <tr>
    <td colspan="3"><strong class="textoGigante">18 de Septiembre Nº 1000</strong></td>
  </tr>

  <tr class="textoNormal">
    <td width="49%">Nombre persona que notifica</td>
    <td width="2%"></td>
    <td width="49%">RUT persona que notifica en representación del Prestador de Salud</td>
  </tr>
  </table>
  <table cellpadding="4" >
    <tr class="textoNormal" >
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['nombre_medico'].'</td>
      <td width="2%"></td>
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['PACGESprofesional'].'</td>
    </tr>
  </table>
  <br>
  <hr>
  <table cellpadding="4">
    <tr><td colspan="10" class="textoGrande">
      <b>Antecedentes del paciente</b> </td></tr>
    <tr>
  </table>
  <table cellpadding="4">
    <tr class="textoNormal">
      <td width="49%">Nombre completo</td>
      <td width="2%"></td>
      <td width="21%" >RUT</td>
      <td width="2%"></td>
      <td colspan="5">Aseguradora (Seleccione una opción)</td>
    </tr>
  </table>
  <table cellpadding="4" >
    <tr class="textoNormal" >
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['nombre_paciente'].'</td>
      <td width="2%"></td>
      <td width="21%" style="border: 1px solid black; ">'.$rsFormulario1[0]['rut_paciente'].'</td>
      <td width="2%"></td>
      <td width="4%" style="border: 1px solid black; " align="center" >'.$rsFormulario1[0]['FONASA'].'</td>
      <td width="9%">FONASA</td>
      <td width="4%" style="border: 1px solid black; " align="center" >'.$rsFormulario1[0]['ISAPRE'].'</td>
      <td width="9%">ISAPRE</td>
    </tr>
  </table>
 
  <table cellpadding="4">
    <tr class="textoNormal">
      <td width="49%">Dirección</td>
      <td width="2%"></td>
      <td colspan="7" >Comuna / Región</td>
    </tr>
  </table>
  <table cellpadding="4" >
    <tr class="textoNormal" >
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['direccion_paciente'].'</td>
      <td width="2%"></td>
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['comuna'].'</td>
    </tr>
  </table>
   <table cellpadding="4">
    <tr class="textoNormal">
      <td width="49%">Teléfono de contacto</td>
      <td width="2%"></td>
      <td colspan="7" >Correo electrónico (E-MAIL)</td>
    </tr>
  </table>
  <table cellpadding="4" >
    <tr class="textoNormal" >
      <td width="24.5%" style="border: 1px solid black; ">Fijo:&nbsp;&nbsp;'.$rsFormulario1[0]['telefono_fijo'].'</td>
      <td width="24.5%" style="border: 1px solid black; ">Celular:&nbsp;&nbsp;'.$rsFormulario1[0]['telefono_celular'].'</td>
      <td width="2%"></td>
      <td width="49%" style="border: 1px solid black; ">'.$rsFormulario1[0]['email'].'</td>
    </tr>
  </table>
  <br>
  <hr>
  <table cellpadding="4">
    <tr>
      <td  width="60%" class="textoGrande">
      <b>Información medica</b> 
      </td>
      <td width="2%"></td>      
      <td  width="49%" class="textoGrande">
      <b>Notificación</b> 
      </td>
    </tr>
  </table>
  <br>
  <table cellpadding="4">
    <tr class="textoNormal">
      <td width="60%">Confirmación diagnóstico GES (Problema de Salud - Patología)</td>
      <td width="2%"></td>
      <td  >Fecha : </td>
    </tr>
  </table>
  <table cellpadding="4" >
    <tr class="textoNormal" >
      <td rowspan="3" width="60%" style="border: 1px solid black; ">'.$rsFormulario1[0]['PACGESdiagGes'].'</td>
      <td width="2%"></td>
      <td width="38%" style="border: 1px solid black; ">'.date("d-m-Y", strtotime($rsFormulario1[0]['PACGESfecha'])).'</td> 
    </tr>
    <tr class="textoNormal" >
      <td width="2%"></td>
      <td width="38%" >Hora :</td>
    </tr>
    <tr class="textoNormal" >
      <td width="2%"></td>
      <td width="38%" style="border: 1px solid black; ">'.date("H:i", strtotime($rsFormulario1[0]['PACGESfecha'])).'</td>
    </tr>
  </table>
  <br>
  <br>
  <table cellpadding="4">
    <tr class="textoNormal">
      <td width="4%" style="border: 1px solid black; " align="center" >'.$rsFormulario1[0]['confirmacion_diagnostico_check'].'</td>
      <td width="26%">Confirmación diagnóstica</td>
      <td width="4%" style="border: 1px solid black; " align="center" >'.$rsFormulario1[0]['paciente_tratamiento_check'].'</td>
      <td width="26%">Paciente en tratamiento</td>
    </tr>
  </table>
  <br>
  <hr>
  <table cellpadding="4">
    <tr>
      <td  width="100%" class="textoGrande">
      <b>Constancia</b> 
      </td>
    </tr>
  </table>
  <table cellpadding="0">
    <tr class="textoNormal">
      <td width="41%" rowspan="3" style="text-align: justify;">Declaro que, con esta fecha y hora, he tomado conocimiento que tengo derecho a acceder a las “Garantías
Explícitas en Salud”, GES, siempre que la atención sea otorgada en la “Red de Prestadores” que me corresponde
según Fonasa o la isapre, a la que me encuentro adscrito.</td>
      <td width="2%"></td>
      <td width="27.5%"  align="center" ><br><br><br>_________________________</td>
      <td width="2%"></td>
      <td width="27.5%"  align="center" ><br><br><br>_________________________</td>
    </tr>
    <tr class="textoNormal">
      <td width="2%"></td>
      <td width="27.5%"  align="center" ><b>Informé diagnóstico GES</b></td>
      <td width="2%"></td>
      <td width="27.5%"  align="center" ><b>Tome conocimiento</b></td>
    </tr>
    <tr class="textoNormal">
      <td width="2%"></td>
      <td width="27.5%"  align="center" >Firma de la persona que notifica</td>
      <td width="2%"></td>
      <td width="27.5%"  align="center" >Firma o huella digital del paciente o representante</td>
    </tr>
  </table>
  <br><br>
  <table cellpadding="0">
    <tr class="textoPequeño">
      <td width="100%" rowspan="3" style="text-align: justify;">IMPORTANTE: El paciente debe tener presente que si no cumplen las garantías, puede reclamar ante el Fonasa o la Isapre, según corresponda. Si la respuesta no es satisfactoria, puede recurrir en segunda instancia a la Superintendencia de Salud.</td>
    </tr>
  </table>
  <br>
  <hr>
   <table cellpadding="0">
    <tr class="textoPequeño">
      <td width="100%" rowspan="3" style="text-align: center;">En caso que la persona que "tomó conocimiento" no sea el paciente, identificar a los siguientes datos:</td>
    </tr>
  </table>
   <table cellpadding="4">
    <tr>
      <td  width="100%" class="textoGrande" style="text-align: center;">
      <b>Antecedentes del representante</b> 
      </td>
    </tr>
  </table>
  <table cellpadding="1">
    <tr class="textoNormal" >
      <td width="15%">Nombre completo &nbsp;:&nbsp;</td>
      <td width="35%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario1[0]['PACGESnomApoderado'].'</td>
      <td width="7%" >&nbsp;&nbsp;RUT&nbsp;:&nbsp;</td>
      <td width="43%" class="highlight-blue borde-inferior" colspan="2" >&nbsp; '.$rsFormulario1[0]['PACGESrunApoderado'].'</td>
    </tr>
    <tr class="textoNormal" >
      <td colspan="6" >Teléfono de contacto</td>
    </tr>
    <tr class="textoNormal" >
      <td width="7%" >Fijo&nbsp;:&nbsp;</td>
      <td width="18%" class="highlight-blue borde-inferior">&nbsp; '.$rsFormulario1[0]['PACGESfonoApoderado'].'</td>
      <td width="8%" >&nbsp;&nbsp;Celular&nbsp;:&nbsp;</td>
      <td width="17%" class="highlight-blue borde-inferior">&nbsp; '.$rsFormulario1[0]['celular_representante'].'</td>
      <td width="22%" >&nbsp;&nbsp;Correo electrónico (E-mail)&nbsp;:&nbsp;</td>
      <td width="28%" class="highlight-blue borde-inferior">&nbsp; '.$rsFormulario1[0]['PACGESmailApoderado'].'</td>
    </tr>
  </table>

</body>
</html>
';


// $html = " holas";
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// array with names of columns
$arr_nomes = array(
    array("", 20, 53) // array(name, new X, new Y);
);

// num of pages
$ttPages = $pdf->getNumPages();
for($i=1; $i<=$ttPages; $i++) {
    // set page
    $pdf->setPage($i);
    // all columns of current page
    foreach( $arr_nomes as $num => $arrCols ) {
        $x = $pdf->xywalter[$num][0] + $arrCols[1]; // new X
        $y = $pdf->xywalter[$num][1] + $arrCols[2]; // new Y
        $n = $arrCols[0]; // column name
        // transforme Rotate
        $pdf->StartTransform();
        // Rotate 90 degrees counter-clockwise
        $pdf->Rotate(90, $x, $y);
        $pdf->Text($x, $y, $n);
        // Stop Transformation
        $pdf->StopTransform();
    }
}

// reset pointer to the last page
$pdf->lastPage();
$nombre_archivo = 'pdfformulario_1.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/formularios/pdfformulario_1.pdf";
?>

</iframe>
<div class="embed-responsive embed-responsive-16by9">
	<iframe id="pdfHojaHospitalizacion" class="embed-responsive-item" src="<?=$url?>" height="700" width="700" allowfullscreen></iframe>
</div>

<script>
$('#pdfHojaHospitalizacion').ready(function(){
	ajaxRequest(raiz+'/controllers/server/admision/main_controller.php','nombreArchivo=<?=$url?>&accion=eliminarPDF', 'POST', 'JSON', 1, '', true);
});
</script>
