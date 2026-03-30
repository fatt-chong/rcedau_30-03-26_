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
require_once('../../../class/Connection.class.php');               $objCon                       = new Connection; $objCon->db_connect();
require_once('../../../class/Util.class.php');                     $objUtil                      = new Util;
require_once('../../../class/Admision.class.php');                 $objAdmision                  = new Admision;
require_once('../../../class/RegistroClinico.class.php');    $objRegistroClinico         = new RegistroClinico;
require_once("../../../class/Dau.class.php" );               $objDau                 = new Dau;
require_once('../../../class/Rce.class.php');                $objRce                 = new Rce;
require_once('../../../class/HojaEnfermeria.class.php');     $objHoja_enfermeria     = new Hoja_enfermeria;
require_once('../../../class/Categorizacion.class.php');     $objCategorizacion      = new Categorizacion;
require_once('../../../class/Diagnosticos.class.php');       $objDiagnosticos        = new Diagnosticos;
require_once('../../../class/Formulario_3.class.php');       $objFormulario_3        = new Formulario_3;
require_once('../../../class/Formulario_3_Dosis.class.php'); $objFormulario_3_Dosis  = new Formulario_3_Dosis;

$parametros                     = $objUtil->getFormulario($_POST);
$dau_id                         = $_POST['dau_id'];
$datosU                         = $objCategorizacion->searchPaciente($objCon, $parametros['dau_id']);
$horarioServidor                = $objUtil->getHorarioServidor($objCon);
$datosDAUPaciente               = $objDau->buscarListaPaciente($objCon,$parametros);
$rsRce                          = $objRegistroClinico->consultaRCE($objCon,$parametros);
$parametros['cta_cte']          = $datosU[0]['idctacte'];
$rsRce_diagnostico              = $objDiagnosticos->obtenerRce_diagnosticoCompartido($objCon,$parametros);
$rsFormulario3                  = $objFormulario_3->SelectByDauFormulario_3($objCon,$dau_id);
$rsFormulario3Dosis            = $objFormulario_3_Dosis->SelectByFormulario3Id($objCon,$rsFormulario3[0]['id']);


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
$pdf->SetTitle('Protocolo Vacunación Antirrábica');
$pdf->SetSubject('Formularios');
$pdf->SetKeywords('RCE, Formularios');
$pdf->setHeaderFont(Array('helvetica', '', 9));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 3, 10);
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->setFontSubsetting(false);
$pdf->SetFont('helvetica', '', 10, '', true);

// add a page
$pdf->AddPage();

// Procesar dosis para crear la tabla
$tablaDosis = '';
if ($rsFormulario3Dosis && count($rsFormulario3Dosis) > 0) {
    $tablaDosis = '<table cellpadding="2" border="1" style="border-collapse: collapse; width: 100%;" class="textoNormal">
        <tr style="background-color: #f0f0f0; font-weight: bold;">
            <th width="20%" style="border: 1px solid black; padding: 4px;" align="center">N° DOSIS INDICADOS</th>
            <th width="40%" style="border: 1px solid black; padding: 4px;" align="center">FECHA DE APLICACIÓN DE LAS DOSIS</th>
            <th width="40%" style="border: 1px solid black; padding: 4px;" align="center">CITACIÓN DE VACUNAS</th>
        </tr>';
    
    for ($i = 1; $i <= 10; $i++) {
        $dosisEncontrada = false;
        $fechaAplicacion = '';
        $citacionVacuna = '';
        
        foreach ($rsFormulario3Dosis as $dosis) {
            if ($dosis['numero_dosis'] == $i) {
                $dosisEncontrada = true;
                $fechaAplicacion = $dosis['fecha_aplicacion'] ? date('d-m-Y', strtotime($dosis['fecha_aplicacion'])) : '';
                $citacionVacuna = $dosis['citacion_vacuna'];
                break;
            }
        }
        
        $tablaDosis .= '<tr>';
        $tablaDosis .= '<td style="border: 1px solid black; padding: 4px;" align="center">' . $i . '°</td>';
        $tablaDosis .= '<td style="border: 1px solid black; padding: 4px;" align="center">' . $fechaAplicacion . '</td>';
        $tablaDosis .= '<td style="border: 1px solid black; padding: 4px;" align="center">' . $citacionVacuna . '</td>';
        $tablaDosis .= '</tr>';
    }
    
    $tablaDosis .= '</table>';
}

$html = '<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Protocolo Vacunación Antirrábica</title>
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
    .borde-inferior {
        border-bottom: 1px solid #000;
        padding-bottom: 2px;
    }
  </style>
</head>
<body>
<table style="width: 100%;">
  <tr>
    <td width="25%" style="vertical-align: top;">
      <img src="/estandar/img/logo_gobierno_chile.jpg" width="95" height="95">
    </td>
    <td width="50%" style="vertical-align: bottom;">
      <p class="textoGrande" align="center"><br><br><br><br><br><b><br>PROTOCOLO VACUNACIÓN ANTIRRÁBICA</b></p>
    </td>
    <td width="25%" style="vertical-align: top;">
      <p class="textoPequeño">CR. EMERGENCIA HOSPITALARIA<br>DRA. MRP/DR. CFR/EU GGWh</p>
    </td>
  </tr>
</table>

<hr>
&nbsp; <br>
<table cellpadding="2">
    <tr class="textoNormal" >
      <td width="12%">NOMBRE &nbsp;:&nbsp;</td>
      <td width="33%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['nombre_paciente'].'</td>
      <td width="15%" >&nbsp;&nbsp;APELLIDOS&nbsp;:&nbsp;</td>
      <td width="20%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['apellidos_paciente'].'</td>
      <td width="10%" >&nbsp;&nbsp;EDAD&nbsp;:&nbsp;</td>
      <td width="10%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['edad_paciente'].'</td>

    </tr>
    <tr>
    <td>&nbsp;</td></tr>
    <tr class="textoNormal">
      <td width="12%" >RELIGIÓN &nbsp;:&nbsp;</td>
      <td width="10%" class="highlight-blue borde-inferior" >&nbsp; '.(isset($rsFormulario3[0]['religion_descripcion']) ? $rsFormulario3[0]['religion_descripcion'] : '-').'</td>
    </tr>

    <tr>
    <td>&nbsp;</td></tr>
    <tr class="textoNormal" >
      <td width="12%">DIRECCIÓN &nbsp;:&nbsp;</td>
      <td width="33%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['direccion_paciente'].'</td>
      <td width="15%" >&nbsp;&nbsp;CONSULTORIO&nbsp;:&nbsp;</td>
      <td width="20%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['consultorio'].'</td>
      <td width="10%" >&nbsp;&nbsp;FECHAS&nbsp;:&nbsp;</td>
      <td width="10%" class="highlight-blue borde-inferior" >&nbsp; '.date("d-m-Y", strtotime($rsFormulario3[0]['fecha_registro'])).'</td>
    </tr>
  </table>

<h6 class="textoGrande"><b>MORDEDURA</b></h6>

<table cellpadding="2" >
    <tr class="textoNormal" >
        <td width="20%"  >ANIMAL MORDEDOR &nbsp;:&nbsp;</td>
        <td width="25%" class="highlight-blue borde-inferior" >&nbsp; '.$rsFormulario3[0]['animal_mordedor'].'</td>
        <td width="50%"  >
        </td>
    </tr>
</table>
<br>
<br>
<table cellpadding="4" >
    <tr class="textoNormal" >
        <td width="20%"  >Animal provocado
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario3[0]['animal_provocado'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
  $html .= '</td>
        <td width="3%"  >
        </td>
        <td width="18%"  >Animal no provocado
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario3[0]['animal_no_provocado'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="9%"  >
        </td>
        <td width="13%"  >Ubicable
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
if ($rsFormulario3[0]['ubicable'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
$html .= '</td>
        <td width="6%"  >
        </td>
    
    <td width="14%"  >No ubicable
        </td>
        <td width="4%" style="border: 1px solid black; " align="center" >';
  if ($rsFormulario3[0]['no_ubicable'] == 'Sí') $html .= 'X'; else $html .= '&nbsp;';
  $html .= '</td>
  </tr>
</table>

';

$html .= '<br>
<h6 class="textoGrande"><b>DOSIS DE VACUNACIÓN</b></h6>
'.$tablaDosis.'
&nbsp; <br>

  <br>
  <br>
  <br>
    <table cellpadding="4" >
      <tr>
        <td width="100%" align="center">
          <p class="textoNormal"><b>Firma</b> _________________________________</p>
        </td>
      </tr>
    </table>

</body>
</html>
';

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
$nombre_archivo = 'pdfformulario_3.pdf';
$pdf->Output(__DIR__ . '/' . $nombre_archivo, 'FI');
$url = "/RCEDAU/views/modules/formularios/pdfformulario_3.pdf";
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